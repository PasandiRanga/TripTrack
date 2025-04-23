<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Routes</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Routes.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>
    <div class="box">
    <div class="header-container">
        
        <h1>Routes</h1>
    </div>

    <div class="add-button-container">
        <a href="<?php echo URLROOT; ?>/SuperAdminPages/AddRoute">
            <button class="add-button">Add Route</button>
        </a>
    </div>


    <!-- Routes Table -->
    <div class="routes-container">
        <table class="routes-table">
            <thead>
                <tr>
                    <th>Route No.</th>
                    <th>Route</th>
                    <th>Stops</th>
                    <th>Price</th>
                    <th>Price per km</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(isset($data['routes']) && is_array($data['routes'])){
                    foreach ($data['routes'] as $routes) {
                        echo "<tr>";
                        echo "<td>{$routes['routeNumber']}</td>";
                        echo "<td>{$routes['route']}</td>";
                        echo "<td>{$routes['stops']}</td>";
                        echo "<td>{$routes['price']}</td>";
                        echo "<td>{$routes['priceperkm']}</td>";
                        echo "<td><button class='update-btn' onclick='updateRoute(\"{$routes['routeNumber']}\")'>Update</button></td>";
                        echo "<td><button class='delete-btn' onclick='deleteRoute(\"{$routes['routeNumber']}\")'>Delete</button></td>";
                        echo "</tr>";
                    }
                }
                else {
                echo "<tr><td colspan='14'>No bus data available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
    <script>
        function updateRoute(routeNumber) {
            // Find the row with the matching route number
            const rows = Array.from(document.querySelectorAll("table.routes-table tbody tr"));
            const row = rows.find(row => row.cells[0].innerText === routeNumber);

            if (row) {
                // Extract data from the row
                const route = row.cells[1].innerText;
                const stops = row.cells[2].innerText;
                const price = row.cells[3].innerText;
                const pricePerKm = row.cells[4].innerText;

                // Redirect to the AddRoute page with pre-filled data
                const url = new URL('<?php echo URLROOT; ?>/SuperAdminPages/AddRoute');
                url.searchParams.append('routeNumber', routeNumber);
                url.searchParams.append('route', route);
                url.searchParams.append('stops', stops);
                url.searchParams.append('price', price);
                url.searchParams.append('priceperkm', pricePerKm);

                window.location.href = url.toString();
            } else {
                alert("Route not found.");
            }
        }

        function deleteRoute(routeNumber){
            if (confirm("Are you sure you want to delete this Route?")) {
                fetch('<?php echo URLROOT; ?>/SuperAdminPages/deleteRoute', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ routeNumber })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Find the row with the matching License_id and remove it
                        const rows = Array.from(document.querySelectorAll("table.routes-table tbody tr"));
                        const row = rows.find(row => row.cells[0].innerText === routeNumber);
                        if (row) {
                            row.remove(); // Remove the row if it matches the routeNumber
                        }
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => alert('Error deleting the route.'));
            }
        }

    </script>

</body>
</html>
