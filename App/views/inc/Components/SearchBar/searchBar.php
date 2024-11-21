<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
</head>
<?php
    include APPROOT . '/views/inc/Components/Button/button.php';
?>

<!-- <script>
    // Populate scheduleData and busData from PHP variables, assuming these are passed from the server.
    const scheduleData = <?php echo json_encode($scheduleData); ?>;
    const busData = <?php echo json_encode($busData); ?>;
</script> -->


<div class="search-bar-container">
    <div class="input-group">
        <div class="icon"><i class="fas fa-bus"></i></div>
        <select class="search-input" id="from">
            <option value="" disabled selected>From</option>
            <?php
                // Extract unique 'start' locations from $distanceData
                $starts = array_unique(array_column($distanceData, 'start'));
                foreach ($starts as $start) {
                    echo "<option value=\"$start\">$start</option>";
                }
            ?>
        </select>
    </div>
    <div class="input-group">
        <div class="icon"><i class="fas fa-bus"></i></div>
        <select class="search-input" id="to">
            <option value="" disabled selected>To</option>
            <?php
                // Extract unique 'location' from $distanceData
                $locations = array_unique(array_column($distanceData, 'location'));
                foreach ($locations as $location) {
                    echo "<option value=\"$location\">$location</option>";
                }
            ?>
        </select>
    </div>
    <div class="input-group">
        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
        <input type="date" class="search-input" id="travelDate">
    </div>
    <button class="search-button" id="searchButton">Search</button>
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
                const URLROOT = "<?php echo URLROOT; ?>";
    // Set the date input field to today's date on page load
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0]; // Format as YYYY-MM-DD
        document.getElementById('travelDate').value = today;
    });

    document.getElementById('searchButton').addEventListener('click', function () {
        const from = document.getElementById('from').value;
        const to = document.getElementById('to').value;
        const travelDate = document.getElementById('travelDate').value;
        console.log(from, to, travelDate);

        // Filter the busData for buses that have the 'from' and 'to' in their 'stops' field
        const filteredBusIds = busData.filter(bus => {
            if (bus.stops) {
                // console.log(bus.stops);
                const stopsArray = bus.stops.split(',').map(stop => stop.trim());
                console.log(stopsArray);
                console.log(stopsArray.includes(from) && stopsArray.includes(to))
                return stopsArray.includes(from) && stopsArray.includes(to);
            }else {
                return false;
            }
        }).map(bus => bus.busId);
        console.log(filteredBusIds);

        // Filter the scheduleData to match the selected date and bus IDs
        if(travelDate){
        const filteredSchedules = scheduleData.filter(schedule =>
            filteredBusIds.includes(schedule.busId) && schedule.date === travelDate
        );

        // Render the filtered schedules in the bus card container
        renderFilteredSchedules(filteredSchedules , busData);
    }else{
        const filteredSchedules = scheduleData.filter(schedule =>
            filteredBusIds.includes(schedule.busId)
        );

        // Render the filtered schedules in the bus card container
        renderFilteredSchedules(filteredSchedules , busData);
    }
    });

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
            // Find the corresponding bus data for each schedule by busId
            const bus = busData.find(b => b.busId === schedule.busId);

            if (!bus) {
                console.warn(`No matching bus found for busId: ${schedule.busId}`);
                return; // Skip rendering this schedule if bus data is missing
            }

            // Process the stops array to remove extra spaces
            const stopsArray = bus.stops.split(',').map(stop => stop.trim());

            // Get the URL dynamically based on the user role
            const bookingUrl = `${URLROOT}/${
                userRole === 'GuestUser' ? 'GuestPages' : 'RegisteredPages'
            }/BusBooking?busId=${encodeURIComponent(bus.busId)}&scheduleId=${encodeURIComponent(schedule.scheduleId)}`;

            // Create the schedule card
            const scheduleDiv = document.createElement('div');
            scheduleDiv.classList.add('bus-card');

            

            // Create the internal elements of the bus card without wrapping them in the anchor tag
            scheduleDiv.innerHTML = `
                <div class="bus-card-header">
                    <div class="route-info">
                        <h2>${bus.route}</h2>
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
                        ${stopsArray.join(' <span class="route-line"></span> ')}
                    </div>
                </div>
                <div class="bus-card-footer">
                    <div class="rating">
                        ${Array(5).fill().map((_, i) =>
                            `<i class="fas fa-star" style="color: ${i < bus.rating ? '#FFD700' : '#ccc'};"></i>`
                        ).join('')}
                        <span>${bus.rating}</span>
                    </div>
                    <div class="price"><span>${schedule.price}</span></div>
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


</script>
