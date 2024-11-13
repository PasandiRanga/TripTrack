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

<!-- Define scheduleData and busData using PHP -->

<script>
    document.addEventListener('DOMContentLoaded', () => {
    console.log("Document Ready");
    const searchButton = document.getElementById('searchButton');
    if (!searchButton) {
        console.error("Search button not found!");
        return;
    }
    console.log("Schedule Data: ", scheduleData);
    console.log("Bus Data: ", busData);

    searchButton.addEventListener('click', () => {
        
        const from = document.getElementById('from').value;
        const to = document.getElementById('to').value;
        const travelDate = document.getElementById('travelDate').value;

        console.log("Search Parameters - From:", from, "To:", to, "Travel Date:", travelDate);

        // Filter scheduleData based on search parameters
        const filteredData = scheduleData.filter(scheduleItem => {
            const bus = busData.find(b => b.busId === scheduleItem.busId);
            if (!bus || !bus.stops) {
                console.error("Bus data or stops data is missing for bus ID:", scheduleItem.busId);
                return false; // Skip if bus or stops data is missing
            }

            const stopsArray = bus.stops.split(',').map(stop => stop.trim());
            return stopsArray.includes(from) && stopsArray.includes(to) && scheduleItem.date === travelDate;
        });

        console.log("Filtered Data:", filteredData);

        // Display the filtered data as bus cards
        displayBusCards(filteredData);
    });
});

// Function to display bus cards dynamically
function displayBusCards(filteredData) {
    const container = document.getElementById('bus-card-container');
    container.innerHTML = ''; // Clear previous results

    if (filteredData.length === 0) {
        container.innerHTML = '<p>No results found for the selected criteria.</p>';
        return;
    }

    // Send filtered data to PHP to render bus cards
    fetch('<?php echo APPROOT; ?>\viewsinc\Components\BusCard\busCardGenerator.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(filteredData), // Convert filteredData array to JSON
    })
    .then(response => response.text()) // Get response as text (HTML content)
    .then(data => {
        container.innerHTML = data; // Insert the generated HTML from PHP
    })
    .catch(error => {
        console.error("Error fetching bus card data:", error);
    });
}


</script>