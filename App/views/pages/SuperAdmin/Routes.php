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
    <h1>Routes</h1>

    <div class="add-button-container">
        <a href="<?php echo URLROOT; ?>/SuperAdminPages/AddRoute">
            <button>Add Route</button>
        </a>
    </div>
<?php 
        $data['routes'] = [
            (object) ['route_no' => '101', 'route_name' => 'Downtown Express', 'stops' => 'Stop A, Stop B, Stop C'],
            (object) ['route_no' => '202', 'route_name' => 'City Loop', 'stops' => 'Stop X, Stop Y, Stop Z'],
        ];
?>
    <table class="">
        <thead>
            <tr>
                <th>Route No.</th>
                <th>Route</th>
                <th>Stops</th>
            </tr>
        </thead>
        <tbody>
            
            <?php foreach ($data['routes'] as $route): ?>
                <tr>
                    <td><?php echo htmlspecialchars($route->route_no); ?></td>
                    <td><?php echo htmlspecialchars($route->route_name); ?></td>
                    <td><?php echo htmlspecialchars($route->route_stops); ?></td>
                </tr>
                <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>