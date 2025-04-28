<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
</head>


<div class="search-bar-container">
    <div class="input-group">
        <div class="icon"><i class="fas fa-bus"></i></div>
        <select class="search-input" id="from">
            <option value="" disabled selected>From</option>
            <?php
                $allStops = [];
                foreach ($routeData as $route) {
                    if (!empty($route['stops'])) {
                        $stops = array_map('trim', explode(',', $route['stops']));
                        $allStops = array_merge($allStops, $stops);
                    }
                    
                    if (!empty($route['route'])) {
                        $routeEndpoints = array_map('trim', explode('-', $route['route']));
                        $allStops = array_merge($allStops, $routeEndpoints);
                    }
                }

                $uniqueLocations = array_unique($allStops);
                sort($uniqueLocations);

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

<div id="overlay" class="overlay"></div>

<div id="no-schedules-popup" class="popup">
    <div class="popup-content">
        <p>No schedules found </p>
        <button id="closePopupButton">Close</button>
    </div>
</div>

<script>
    let storedBusData = ''; 
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0]; 
        document.getElementById('travelDate').value = today;
    });

    function handleSearch() {
        const from = document.getElementById('from').value;
        const to = document.getElementById('to').value;
        const travelDate = document.getElementById('travelDate').value;
        console.log(from, to, travelDate);

        updateDateBarSelection(travelDate);

        const filteredRoutes = routeData.filter(route => {
            if (route.stops) {
                const stopsArray = route.stops.split(',').map(stop => stop.trim());
    
                const routeEndpoints = route.route ? route.route.split('-').map(stop => stop.trim()) : [];
                
                const allStops = [...stopsArray, ...routeEndpoints];
                
                return allStops.includes(from) && allStops.includes(to);
            }
            return false;
        });

        const matchingRouteNumbers = filteredRoutes.map(route => route.routeNumber);

        const filteredBusIds = busData
            .filter(bus => matchingRouteNumbers.includes(bus.routeNumber))
            .map(bus => bus.License_id);

        console.log(scheduleData);
        console.log('Filtered Routes:', filteredRoutes);
        console.log('Matching Route Numbers:', matchingRouteNumbers);
        console.log('Filtered Bus IDs:', filteredBusIds);

        if(travelDate){
            const filteredSchedules = scheduleData.filter(schedule =>
                filteredBusIds.includes(schedule.License_id) && schedule.date === travelDate
            );

            console.log('Filtered schedules: ', filteredSchedules)

            renderFilteredSchedules(filteredSchedules , busData);
        }else{
            const filteredSchedules = scheduleData.filter(schedule =>
                filteredBusIds.includes(schedule.License_id)
            );

            console.log('Filtered Routes:', filteredSchedules);
            renderFilteredSchedules(filteredSchedules , busData);
        }
    };

    function renderFilteredSchedules(filteredSchedules, busData) {
        const busCardContainer = document.getElementById('bus-card-container');
        const popup = document.getElementById('no-schedules-popup');
        const overlay = document.getElementById('overlay');
        if (!storedBusData) {
            storedBusData = busCardContainer.innerHTML;
        }

        busCardContainer.innerHTML = '';

        if (filteredSchedules.length === 0) {
            overlay.style.display = 'block';
            popup.style.display = 'block';
        } else {
            overlay.style.display = 'none';
            popup.style.display = 'none';

            filteredSchedules.forEach(schedule => {
                const bus = busData.find(b => b.License_id === schedule.License_id);
                const route = routeData.find(r=>r.routeNumber === bus.routeNumber );

                if (!bus) {
                    console.warn(`No matching bus found for License_number: ${schedule.License_id}`);
                    return;
                }

                const busRating = averageRatings[bus.License_id] || 0;
                const ratingIsNumeric = !isNaN(parseFloat(busRating)) && isFinite(busRating);
                const numericRating = ratingIsNumeric ? parseFloat(busRating) : 0;
                const roundedRating = Math.round(numericRating * 2) / 2; // Round to nearest 0.5

                const stopsArray = route.stops.split(',').map(stop => stop.trim());

                const bookingUrl = `${URLROOT}/${
                    userRole === 'GuestUser' ? 'GuestPages' : 'RegisteredPages'
                }/busLayout?Licenseid=${encodeURIComponent(bus.License_id)}&scheduleId=${encodeURIComponent(schedule.scheduleId)}`;

                const scheduleDiv = document.createElement('div');
                scheduleDiv.classList.add('bus-card');

                let starsHTML = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= Math.floor(roundedRating)) {
                        starsHTML += '<i class="fas fa-star" style="color: #FFD700;"></i>';
                    } else if (i - 0.5 <= roundedRating) {
                        starsHTML += '<i class="fas fa-star-half-alt" style="color: #FFD700;"></i>';
                    } else {
                        starsHTML += '<i class="fas fa-star" style="color: #ccc;"></i>';
                    }
                }

                scheduleDiv.innerHTML = `
                    <div class="bus-card-header">
                        <div class="route-info">
                            <h2>${schedule.direction === 'backward' ? `${bus.destination} - ${bus.start_location}` : `${bus.start_location} - ${bus.destination}`}</h2>
                            <span class="bus-type">Route : ${bus.routeNumber}</span>
                        </div>
                    </div>
                    <div class="bus-card-timing">
                        <div class="date"><span>${schedule.date}</span></div>
                        <div class="timing-info">
                            <div class="departure-time"><span>${schedule.departureTime}</span></div>
                            <div class="arrival-time"><span>${schedule.arrivalTime}</span></div>
                        </div>
                        <div class="duration">
                            <span>${formatDuration(schedule.duration)}</span>
                        </div>                    <div class="route-stops">
                            ${formatStops(stopsArray)}
                        </div>
                    </div>
                    <div class="bus-card-footer">
                        <div class="rating">
                            ${starsHTML}
                            <span>${ratingIsNumeric ? numericRating.toFixed(1) : 'No rating'}</span>
                        </div>
                        <div class="price"><span>${bus.price}</span></div>
                    </div>
                `;

                scheduleDiv.addEventListener('click', function() {
                    window.location.href = `${URLROOT}/${
                        userRole === 'GuestUser' ? 'GuestPages' : 'RegisteredPages'
                    }/busLayout?Licenseid=${encodeURIComponent(bus.License_id)}&scheduleId=${encodeURIComponent(schedule.scheduleId)}`;
                });

                busCardContainer.appendChild(scheduleDiv);
            });
        }

        const closePopupButton = document.getElementById('closePopupButton');
        closePopupButton.addEventListener('click', function() {
            overlay.style.display = 'none';
            popup.style.display = 'none';
            busCardContainer.style.display = 'block';
            busCardContainer.style.display = 'flex';  
            busCardContainer.innerHTML = storedBusData;
            updateDateBarSelection(selectedDate); 
            renderFilteredSchedules(filteredSchedules, busData);
        });
    }

document.addEventListener('DOMContentLoaded', function () {
    const today = new Date().toISOString().split('T')[0]; 
    const travelDateInput = document.getElementById('travelDate');
    travelDateInput.value = today;
    travelDateInput.min = today;  
});


function formatDuration(duration) {
    if (!duration) return '';
    const [hours, minutes, seconds] = duration.split(':');
    let formattedDuration = '';
    if (parseInt(hours) > 0) {
        formattedDuration += parseInt(hours) + ' hour' + (parseInt(hours) > 1 ? 's' : '');
    }
    if (parseInt(minutes) > 0) {
        formattedDuration += (formattedDuration ? ' ' : '') + parseInt(minutes) + ' minute' + (parseInt(minutes) > 1 ? 's' : '');
    }
    return formattedDuration || 'N/A';
}

function formatStops(stopsArray) {
    const length = stopsArray.length;
    if (length === 1) {
        return stopsArray[0];
    } else if (length === 2) {
        return stopsArray[0] + ' <span class="route-line"></span> ' + stopsArray[1];
    } else {
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
            dateItems.forEach(di => di.classList.remove('active'));
            item.classList.add('active');
            item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    });
}

</script>
