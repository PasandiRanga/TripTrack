<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Management</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Fleet.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back </button>

    <h1>Fleet Management</h1>

    <!-- Search and Clear -->
    <div class="search-container">
        <label for="search">Search: </label>
        <input type="text" id="search" placeholder="Search buses...">
        <button class="search-button" onclick="Search()">Search</button>
        <button class="search-button-clear" onclick="clearSearch()">Clear</button>
    </div>

   <!-- Fleet table -->
<table class="fleet-table">
    <thead>
        <tr>
            <th>Bus ID</th>
            <th>Licence ID</th>
            <th>Route No</th>
            <th>Route</th>
            <th>Bus Type</th>
            <th>Stops</th>
            <th>Starts</th>
            <th>Destination</th>
            <th>Ratings</th>
            <th>Passengers</th>
            <th>Price</th>
            <th>Price per KM</th>
        </tr>
    </thead>
    <tbody id="fleet-table-body">
        <?php
        // Sample data; replace this with PHP code to fetch data from a database
        $fleetData = [
            ['BUS101', 'LIC12345', 'R001', 'Colombo - Kandy', 'Luxury', 'Colombo, Kadawatha, Kegalle, Kandy', 'Colombo', 'Kandy', 4.5, 32, 1200.00, 10.00],
            ['BUS102', 'LIC54321', 'R002', 'Galle - Matara', 'Semi-Luxury', 'Galle, Weligama, Matara', 'Galle', 'Matara', 4.2, 28, 700.00, 8.50],
            ['BUS103', 'LIC67890', 'R003', 'Jaffna - Colombo', 'Express', 'Jaffna, Kilinochchi, Vavuniya, Colombo', 'Jaffna', 'Colombo', 4.8, 40, 2500.00, 12.00]
            // Add more data as needed
        ];

        if (isset($data['bus']) && is_array($data['bus'])) {
            foreach ($data['bus'] as $bus) {
                echo "<tr onclick='selectRow(this)'>";
                echo "<td>{$bus['busId']}</td>";
                echo "<td>{$bus['License_id']}</td>";
                echo "<td>{$bus['routeNumber']}</td>";
                echo "<td>{$bus['route']}</td>";
                echo "<td>{$bus['busType']}</td>";
                echo "<td>{$bus['stops']}</td>";
                echo "<td>{$bus['start_location']}</td>";
                echo "<td>{$bus['destination']}</td>";
                echo "<td>{$bus['rating']}</td>";
                echo "<td>{$bus['passengers']}</td>";
                echo "<td>{$bus['price']}</td>";
                echo "<td>{$bus['priceperkm']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No bus data available.</td></tr>";
        }
        ?>
        
    </tbody>
</table>


    <!-- Action buttons -->
    <div class="action-buttons">
        <a href="<?php echo URLROOT; ?>/SuperAdminPages/AddFleet">
            <button>Add</button>
        </a>
        <button onclick="deleteBus()">Delete</button>
        <button onclick="updateBus()">Update</button>
    </div>


    <script>
        let selectedRow = null;

        // Go back to the previous page
        function goBack() {
            window.history.back();
        }

        // Clear the search input
        function clearSearch() {
            document.getElementById("search").value = "";
        }

        // Add a bus (Placeholder function)
        function addBus() {
            window.location.href = "Fleet/Add_fleet.php";
            alert("Add Bus functionality to be implemented");
        }

        // Delete the selected bus
        function deleteBus() {
            if (selectedRow) {
                selectedRow.remove();
                selectedRow = null;
                alert("Bus deleted successfully.");
            } else {
                alert("Please select a row to delete.");
            }
        }

        // Update the selected bus (Placeholder function)
        function updateBus() {
            if (selectedRow) {
                alert("Update Bus functionality to be implemented.");
                // You can add a modal or form to edit the selected row details here
            } else {
                alert("Please select a row to update.");
            }
        }

        // Select a row
        function selectRow(row) {
            // Clear previous selection
            if (selectedRow) {
                selectedRow.classList.remove("selected");
            }
            // Set the new selection
            selectedRow = row;
            selectedRow.classList.add("selected");
        }

    </script>
</body>
</html>
