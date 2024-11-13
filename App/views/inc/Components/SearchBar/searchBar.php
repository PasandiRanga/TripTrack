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

<div id="bus-card-container"></div> <!-- Placeholder for bus cards -->

<script>
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
        const filteredSchedules = scheduleData.filter(schedule =>
            filteredBusIds.includes(schedule.busId) && schedule.date === travelDate
        );

        // Render the filtered schedules in the bus card container
        renderFilteredSchedules(filteredSchedules , busData);
    });

    function renderFilteredSchedules(filteredSchedules, busData) {
    const busCardContainer = document.getElementById('bus-card-container');
    busCardContainer.innerHTML = '';

    // Check if there are schedules to display
    if (filteredSchedules.length === 0) {
        busCardContainer.innerHTML = '<p>No matching schedules found.</p>';
        return;
    }

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

        // Create the schedule card and set the inner HTML based on the busCard layout
        const scheduleDiv = document.createElement('div');
        scheduleDiv.classList.add('bus-card');

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

        busCardContainer.appendChild(scheduleDiv);
    });
}

</script>
