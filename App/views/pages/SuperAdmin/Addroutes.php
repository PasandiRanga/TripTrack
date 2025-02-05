<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assigns</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/AddRoutes.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Back Button -->
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/routes'">Back</button>

    <!-- Page Title -->
    <h2>Add New Route</h2>

    <!-- Add Assignment Form -->
    <form id="routeForm" method="POST" action="<?php echo URLROOT; ?>/SuperAdminPages/addroutes" class="route-form">
        <label for="routeNumber">Route No:</label>
        <input type="text" id="routeNumber" name="routeNumber" placeholder="Enter Route Number" required>

        <label for="route">Route:</label>
        <input type="text" id="route" name="route" placeholder="Enter Route" required>

        <label for="stops">Stops:</label>
        <input type="text" id="stops" name="stops" placeholder="Enter Stops" required>

        <button type="submit">Add Route</button>
    </form>

</body>
</html>