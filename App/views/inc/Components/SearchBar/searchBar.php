<?php
    include APPROOT . '/views/inc/Components/Button/button.php';
?>

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
    <button class="search-button" id="searchButton" onclick="handleSearchClick()">Search</button>
</div>

<script>
// Include APPROOT path into JS using PHP
const appRoot = "<?php echo APPROOT; ?>";

// Function to display the bus cards
function displayBusCards(filteredData) {
    const container = document.getElementById('bus-card-container');
    if (!container) {
        console.error("Container not found.");
        return;
    }

    container.innerHTML = ''; // Clear existing content

    if (filteredData.length === 0) {
        container.innerHTML = '<p>No results found.</p>';
        return;
    }

    // Send the filtered data to PHP for processing via fetch
    fetch(appRoot + '/views/inc/Components/BusCard/busCardGenerator.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(filteredData), // Convert the filteredData array to JSON
    })
    .then(response => response.text()) // Get the response as text (HTML content)
    .then(data => {
        container.innerHTML = data; // Insert the generated HTML from PHP
    })
    .catch(error => {
        console.error("Error fetching bus card data:", error);
    });
}

// Handle search button click
function handleSearchClick() {
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    const travelDate = document.getElementById('travelDate').value;

    console.log("Search Button Clicked");
    console.log("From:", from, "To:", to, "Travel Date:", travelDate);

    // Filter scheduleData based on selected values
    const filteredData = scheduleData.filter(item => {
        const bus = busData.find(b => b.busId === item.busId); // Find the related bus in busData
        if (!bus || !bus.stops) {
            console.warn("Skipping item due to missing or invalid stops:", item);
            return false;
        }
        const busStops = bus.stops.split(',').map(stop => stop.trim());
        return busStops.includes(from) && busStops.includes(to) && item.date === travelDate;
    });
    
    // Display filtered bus cards
    displayBusCards(filteredData);
}

// Event listener to initialize search button behavior
document.addEventListener('DOMContentLoaded', () => {
    // You can choose to display initial data or leave empty
    displayBusCards(scheduleData); // Initially display all data or handle default state
});
</script>
