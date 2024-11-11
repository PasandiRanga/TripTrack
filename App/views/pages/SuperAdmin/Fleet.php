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
    <button class="back-button" onclick="location.href='Dashboard.php'">Back</button>

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
                <th>Licence ID</th>
                <th>Driver ID</th>
                <th>Conductor ID</th>
                <th>No. of Seats</th>
                <th>Bus Route No.</th>
            </tr>
        </thead>
        <tbody id="fleet-table-body">
            <?php
            // Sample data; replace this with PHP code to fetch data from a database
            $fleetData = [
                ['LIC123', 'DRV001', 'CON123', 45, 25],
                ['LIC124', 'DRV002', 'CON124', 40, 30],
                ['LIC125', 'DRV003', 'CON125', 50, 35]
                // Add more data as needed
            ];

            foreach ($fleetData as $bus) {
                echo "<tr onclick='selectRow(this)'>";
                foreach ($bus as $item) {
                    echo "<td>$item</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Action buttons -->
    <div class="action-buttons">
        <a href="Fleet/Add_fleet.php"><button onclick="addBus()">Add</button></a>
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
