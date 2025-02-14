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

    <div class="header-container">
        <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>
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
    <script>
        function updateRoute(routeNo){
            //update query
        }

        function deleteRoute(routeNo){
            //update query
        }

    </script>

</body>
</html>
