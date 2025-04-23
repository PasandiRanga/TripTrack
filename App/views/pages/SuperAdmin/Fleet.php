<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
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
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-button-container button:hover {
            background-color: #007bff;
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
    

    <!-- Add button -->
    <div class="box">
    <h1>Fleet Management</h1>
    
    <div class="add-button-wrapper">
        <a href="<?php echo URLROOT; ?>/SuperAdminPages/AddFleet" class="add-button">Add Bus</a>
    </div>

    <!-- Search and Clear -->
    <div class="search-container">
        <label for="search">Search: </label>
        <input type="text" id="search" class="search-input" placeholder="Search buses...">
        
        <button class="search-button" onclick="searchFleet()">Search</button>
        <button class="search-button-clear" onclick="clearSearch()">Clear</button>

        
    </div>
    

    <!-- Fleet table -->
    <div class="fleet-table-container">
    <table class="fleet-table">
        <thead>
            <tr>
                <th>Licence ID</th>
                <th>Route No</th>
                <!-- <th>Route</th> -->
                <!-- <th>Bus Type</th> -->
                <!-- <th>Stops</th> -->
                <th>Starts</th>
                <th>Destination</th>
                <!-- <th>Ratings</th> -->
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
                    echo "<tr class='selected'>";
                    echo "<td>{$bus['License_id']}</td>";
                    echo "<td>{$bus['routeNumber']}</td>";
                    //echo "<td>{$bus['route']}</td>";
                    // echo "<td>{$bus['busType']}</td>";
                    //echo "<td>{$bus['stops']}</td>";
                    echo "<td>{$bus['start_location']}</td>";
                    echo "<td>{$bus['destination']}</td>";
                    // echo "<td>{$bus['rating']}</td>";
                    echo "<td>{$bus['passengers']}</td>";
                    echo "<td>{$bus['price']}</td>";
                    echo "<td>{$bus['priceperkm']}</td>";
                    echo "<td><button class='update-button' onclick='updateBus(\"{$bus['License_id']}\")'>Update</button></td>";
                    echo "<td><button class='delete-button' onclick='deleteBus(\"{$bus['License_id']}\")'>Delete</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='14'>No bus data available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div>
    </div>

    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-box">
            <p id="popupMessage"></p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

        <!-- Delete Confirmation Popup -->
    <div class="popup-overlay" id="deletePopupOverlay">
        <div class="popup-box">
            <p id="deletePopupMessage">Are you sure you want to delete this schedule?</p>
            <div class="popup-buttons">
                <button class="confirm-btn" id="confirmDeleteBtn">Yes</button>
                <button class="cancel-btn" onclick="closeDeletePopup()">No</button>
            </div>
        </div>
    </div>

    <script>

        function showPopup(message) {
            const popupOverlay = document.getElementById("popupOverlay");
            const popupMessage = document.getElementById("popupMessage");

            popupMessage.innerText = message;
            popupOverlay.style.display = "flex";
        }

        function closePopup() {
            const popupOverlay = document.getElementById("popupOverlay");
            popupOverlay.style.display = "none";
        }
        // Delete Bus Function
        function deleteBus(License_id) {
            const deletePopupOverlay = document.getElementById("deletePopupOverlay");
            const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");

            // Show the delete confirmation popup
            deletePopupOverlay.style.display = "flex";

            // Attach event listener to the confirm button
            confirmDeleteBtn.onclick = function () {
            fetch('<?php echo URLROOT; ?>/SuperAdminPages/deleteBus', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ License_id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                // Find the row with the matching License_id and remove it
                const rows = Array.from(document.querySelectorAll("table.fleet-table tbody tr"));
                const row = rows.find(row => row.cells[0].innerText === License_id);
                if (row) {
                    row.remove(); // Remove the row if it matches the License_id
                }
                showPopup(data.message); // Show success popup
                } else {
                showPopup(data.message); // Show error popup
                }
            })
            .catch(() => showPopup('Error deleting the bus from the fleet.'))
            .finally(() => {
                // Hide the delete confirmation popup
                deletePopupOverlay.style.display = "none";
            });
            };
        }

        function closeDeletePopup() {
            const deletePopupOverlay = document.getElementById("deletePopupOverlay");
            deletePopupOverlay.style.display = "none";
        }


        // Update Bus Function
        // function updateBus(License_id) {
        //     window.location.href = '<//?php echo URLROOT; ?>/SuperAdminPages/updatefleet?License_id=' + encodeURIComponent(License_id);
        // }

        // Search Function
        function searchFleet() {
            const searchQuery = document.getElementById("search").value.trim();
            console.log(searchQuery);

            fetch('<?php echo URLROOT; ?>/SuperAdminPages/searchFleet', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ searchQuery })
            })
            .then(response => response.json())
            .then(data => {

                if (data.status === 'success' && data.data) {
                    const count = data.data.length;

                    showPopup(`Total Buses found: ${count}`);
                    updateTable(data.data);
                } else {
                    showPopup('No matching buses found.');
                }
            })
            .catch(() => showPopup('An error occurred while searching.'));
        }

        // Clear Search Function
        function clearSearch() {
            document.getElementById("search").value = "";

            fetch('<?php echo URLROOT; ?>/SuperAdminPages/getAllFleet', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    updateTable(data.data);
                } else {
                    alert('Error reloading data.');
                }
            })
            .catch(() => alert('An error occurred while reloading data.'));
        }

        //Update Table Function
        function updateTable(buses) {
            const tableBody = document.getElementById("fleet-table-body");
            tableBody.innerHTML = ''; // Clear the table before inserting new rows

            if (buses.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="14">No buses found.</td></tr>';
            return;
            }

            buses.forEach(bus => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${bus.License_id}</td>
                <td>${bus.routeNumber}</td>
                <td>${bus.start_location}</td>
                <td>${bus.destination}</td>
                <td>${bus.passengers}</td>
                <td>${bus.price}</td>
                <td>${bus.priceperkm}</td>
                <td>
                <button class='update-button' onclick='updateBus("${bus.License_id}")'>Update</button>
                </td>
                <td>
                <button class='delete-button' onclick='deleteBus("${bus.License_id}")'>Delete</button>
                </td>
            `;
            tableBody.appendChild(row);
            });
        }

        function updateBus(License_id) {

        // Find the row with the matching License_id
        const rows = Array.from(document.querySelectorAll("table.fleet-table tbody tr"));
        const row = rows.find(row => row.cells[0].innerText === License_id);

        if (row) {

            // Extract data from the row
            const routeNumber = row.cells[1].innerText;
            const startLocation = row.cells[2].innerText;
            const destination = row.cells[3].innerText;
            const passengers = row.cells[4].innerText;
            const price = row.cells[5].innerText;
            const pricePerKm = row.cells[6].innerText;

            // Redirect to the AddFleet page with pre-filled data
            const url = new URL('<?php echo URLROOT; ?>/SuperAdminPages/AddFleet');
            url.searchParams.append('License_id', License_id);
            url.searchParams.append('routeNumber', routeNumber);
            url.searchParams.append('start_location', startLocation);
            url.searchParams.append('destination', destination);
            url.searchParams.append('passengers', passengers);
            url.searchParams.append('price', price);
            url.searchParams.append('priceperkm', pricePerKm);

            window.location.href = url.toString();

        } else {
            alert("Bus not found.");
        }
    }


        // function redirectToUpdateForm(License_id) {
        //     window.location.href = '<//?php echo URLROOT; ?>/SuperAdminPages/AddFleet?License_id=' + encodeURIComponent(License_id);
        // }
    </script>
</body>
</html>

