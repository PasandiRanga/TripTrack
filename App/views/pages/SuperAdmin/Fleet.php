<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Management</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Fleet.css?v=<?php echo time(); ?>">
    <style>
        .add-button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .add-button-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-button-container button:hover {
            background-color: #45a049;
        }

        .fleet-table button {
            padding: 5px 10px;
            margin: 2px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .fleet-table button.delete-button {
            background-color: #FF6347;
        }

        .fleet-table button:hover {
            opacity: 0.9;
        }

        .fleet-table .selected {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- Back button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

    <h1>Fleet Management</h1>

    <!-- Add button -->
    <div class="add-button-container">
        <a href="<?php echo URLROOT; ?>/SuperAdminPages/AddFleet">
            <button>Add Bus</button>
        </a>
    </div>

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
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody id="fleet-table-body">
            <?php
            if (isset($data['bus']) && is_array($data['bus'])) {
                foreach ($data['bus'] as $bus) {
                    echo "<tr>";
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
                    echo "<td><button class='update-button' onclick='updateBus(this)'>Update</button></td>";
                    echo "<td><button class='delete-button' onclick='deleteBus(this)'>Delete</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='14'>No bus data available.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        // Clear the search input
        function clearSearch() {
            document.getElementById("search").value = "";
        }

        function deleteBus(button) {
            const row = button.closest('tr');
            const busId = row.cells[0].innerText;

            if (confirm("Are you sure you want to delete this bus?")) {
                fetch('<?php echo URLROOT; ?>/SuperAdminPages/deleteBus', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ busId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        row.remove();
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => alert('Error deleting the bus.'));
            }
        }

        function updateBus(button) {
            const row = button.closest('tr');
            const busId = row.cells[0].innerText;

            // Collect current row data
            const data = {
                busId: busId,
                License_id: row.cells[1].innerText,
                routeNumber: row.cells[2].innerText,
                route: row.cells[3].innerText,
                busType: row.cells[4].innerText,
                stops: row.cells[5].innerText,
                start_location: row.cells[6].innerText,
                destination: row.cells[7].innerText,
                rating: row.cells[8].innerText,
                passengers: row.cells[9].innerText,
                price: row.cells[10].innerText,
                priceperkm: row.cells[11].innerText
            };

            fetch('<?php echo URLROOT; ?>/SuperAdminPages/updateBus', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => alert('Error updating the bus.'));
        }

    </script>
</body>
</html>
