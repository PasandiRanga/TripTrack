<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
</head>
<?php
    include APPROOT . '/views/inc/Components/Button/button.php';
?>


<div class="search-bar-container">
    <div class="input-group">
        <div class="icon"><i class="fas fa-bus"></i></div>
        <select class="search-input" id="from">
            <option value="" disabled selected>From</option>
            <?php
                // Extract unique locations from routeData stops
                $allStops = [];
                foreach ($routeData as $route) {
                    // Handle stops
                    if (!empty($route['stops'])) {
                        $stops = array_map('trim', explode(',', $route['stops']));
                        $allStops = array_merge($allStops, $stops);
                    }
                    
                    // Handle from-to locations
                    if (!empty($route['route'])) {
                        $routeEndpoints = array_map('trim', explode('-', $route['route']));
                        $allStops = array_merge($allStops, $routeEndpoints);
                    }
                }

                // Get unique values and sort them
                $uniqueLocations = array_unique($allStops);
                sort($uniqueLocations);

                // Generate options
                foreach ($uniqueLocations as $location) {
                    echo "<option value=\"$location\">$location</option>";
                }
            ?>
        </select>
    </div>
    <div class="input-group">
        <div class="icon"><i class="fas fa-bus"></i></div>
        <select class="search-input" id="to">
            <option value="" disabled selected>To</option>
            <?php
                // Reuse the same unique locations for the "To" dropdown
                foreach ($uniqueLocations as $location) {
                    echo "<option value=\"$location\">$location</option>";
                }
            ?>
        </select>
    </div>
    <div class="input-group">
        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
        <input type="date" class="search-input" id="travelDate" min="">
    </div>
    <button class="search-button" id="searchButton"  onclick="handleSearch()">Search</button>
</div>

<!-- <div id="bus-card-container"></div> Placeholder for bus cards -->

<div id="overlay" class="overlay"></div>

<!-- Popup for no schedules found -->
<div id="no-schedules-popup" class="popup">
    <div class="popup-content">
        <p>No schedules found </p>
        <button id="closePopupButton">Close</button>
    </div>
</div>

<script>
    let storedBusData = ''; // Declare and initialize the variable to store bus card data
    // Set the date input field to today's date on page load
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0]; // Format as YYYY-MM-DD
        document.getElementById('travelDate').value = today;
    });

    function handleSearch() {
        const from = document.getElementById('from').value;
        const to = document.getElementById('to').value;
        const travelDate = document.getElementById('travelDate').value;
        console.log(from, to, travelDate);

        // Update date bar selection when searching
        updateDateBarSelection(travelDate);

        // First filter routes that contain both 'from' and 'to' stops
        const filteredRoutes = routeData.filter(route => {
            if (route.stops) {
                // Get stops from the stops field
                const stopsArray = route.stops.split(',').map(stop => stop.trim());
                
                // Get endpoints from the route field
                const routeEndpoints = route.route ? route.route.split('-').map(stop => stop.trim()) : [];
                
                // Combine all possible stops
                const allStops = [...stopsArray, ...routeEndpoints];
                
                return allStops.includes(from) && allStops.includes(to);
            }
            return false;
        });

        // Get the route numbers from filtered routes
        const matchingRouteNumbers = filteredRoutes.map(route => route.routeNumber);

        // Then filter buses that operate on these routes
        const filteredBusIds = busData
            .filter(bus => matchingRouteNumbers.includes(bus.routeNumber))
            .map(bus => bus.License_id);

        console.log(scheduleData);

        console.log('Filtered Routes:', filteredRoutes);
        console.log('Matching Route Numbers:', matchingRouteNumbers);
        console.log('Filtered Bus IDs:', filteredBusIds);

        // Filter the scheduleData to match the selected date and bus IDs
        if(travelDate){
            const filteredSchedules = scheduleData.filter(schedule =>
                filteredBusIds.includes(schedule.License_id) && schedule.date === travelDate
            );

            console.log('Filtered schedules: ', filteredSchedules)

            // Render the filtered schedules in the bus card container
            renderFilteredSchedules(filteredSchedules , busData);
        }else{
            const filteredSchedules = scheduleData.filter(schedule =>
                filteredBusIds.includes(schedule.License_id)
            );

            console.log('Filtered Routes:', filteredSchedules);
            // Render the filtered schedules in the bus card container
            renderFilteredSchedules(filteredSchedules , busData);
        }
    };

    function renderFilteredSchedules(filteredSchedules, busData) {
    const busCardContainer = document.getElementById('bus-card-container');
    const popup = document.getElementById('no-schedules-popup');
    const overlay = document.getElementById('overlay');
    // Store the current bus data if it's not already stored
    if (!storedBusData) {
        storedBusData = busCardContainer.innerHTML;
    }

    busCardContainer.innerHTML = '';

    // Check if there are schedules to display
    if (filteredSchedules.length === 0) {
        overlay.style.display = 'block';
        popup.style.display = 'block';
    } else {
        overlay.style.display = 'none';
        popup.style.display = 'none';

        // Dynamically load bus cards based on filtered schedules
        filteredSchedules.forEach(schedule => {
            // Find the corresponding bus data for each schedule by License_id
            const bus = busData.find(b => b.License_id === schedule.License_id);

            const route = routeData.find(r=>r.routeNumber === bus.routeNumber );

            if (!bus) {
                console.warn(`No matching bus found for License_number: ${schedule.License_id}`);
                return; // Skip rendering this schedule if bus data is missing
            }

            // Process the stops array to remove extra spaces
            const stopsArray = route.stops.split(',').map(stop => stop.trim());

            // Get the URL dynamically based on the user role
            const bookingUrl = `${URLROOT}/${
                userRole === 'GuestUser' ? 'GuestPages' : 'RegisteredPages'
            }/busLayout?license_number=${encodeURIComponent(bus.license_number)}&scheduleId=${encodeURIComponent(schedule.scheduleId)}`;

            // Create the schedule card
            const scheduleDiv = document.createElement('div');
            scheduleDiv.classList.add('bus-card');

            

            // Create the internal elements of the bus card without wrapping them in the anchor tag
            scheduleDiv.innerHTML = `
                <div class="bus-card-header">
                    <div class="route-info">
                        <h2>${bus.start_location} - ${bus.destination}</h2>
                        <span class="bus-type">${bus.routeNumber}</span>
                    </div>
                </div>
                <div class="bus-card-timing">
                    <div class="date"><span>${schedule.date}</span></div>
                    <div class="timing-info">
                        <div class="departure-time"><span>${schedule.departureTime}</span></div>
                        <div class="arrival-time"><span>${schedule.arrivalTime}</span></div>
                    </div>
                    <div class="duration"><span>${schedule.duration}</span></div>
                    <div class="route-stops">
                         ${formatStops(stopsArray)}
                    </div>
                </div>
                <div class="bus-card-footer">
                    <div class="rating">
                        ${Array(5).fill().map((_, i) =>
                            `<i class="fas fa-star" style="color: ${i < bus.rating ? '#FFD700' : '#ccc'};"></i>`
                        ).join('')}
                        <span>${bus.rating}</span>
                    </div>
                    <div class="price"><span>${bus.price}</span></div>
                </div>
            `;

            scheduleDiv.addEventListener('click', function() {
                window.location.href = bookingUrl;  // Redirect to booking URL
            });

            // Append the bus card to the container
            busCardContainer.appendChild(scheduleDiv);
        });
    }

    // Close button functionality
    const closePopupButton = document.getElementById('closePopupButton');
    closePopupButton.addEventListener('click', function() {
        overlay.style.display = 'none';
        popup.style.display = 'none';
        busCardContainer.style.display = 'block';
        busCardContainer.style.display = 'flex';  
        busCardContainer.innerHTML = storedBusData;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const today = new Date().toISOString().split('T')[0]; // Format as YYYY-MM-DD
    const travelDateInput = document.getElementById('travelDate');
    travelDateInput.value = today; // Set the default value to today
    travelDateInput.min = today;  // Set the min attribute to today
});


function formatStops(stopsArray) {
    const length = stopsArray.length;

    if (length === 1) {
        // If there is only one stop, just return it
        return stopsArray[0];
    } else if (length === 2) {
        // If there are two stops, return both with a line
        return stopsArray[0] + ' <span class="route-line"></span> ' + stopsArray[1];
    } else {
        // Otherwise, return first, middle, and last stops
        const middleIndex = Math.floor(length / 2);
        return (
            stopsArray[0] +
            ' <span class="route-line"></span> ' +
            stopsArray[middleIndex] +
            ' <span class="route-line"></span> ' +
            stopsArray[length - 1]
        );
    }
}

function updateDateBarSelection(selectedDate) {
    const dateItems = document.querySelectorAll('.date-item');
    dateItems.forEach(item => {
        const itemDate = item.dataset.date;
        if (itemDate === selectedDate) {
            // Remove active class from all items
            dateItems.forEach(di => di.classList.remove('active'));
            // Add active class to matching date
            item.classList.add('active');
            // Scroll the date into view
            item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    });
}

</script>
