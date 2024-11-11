<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/InformDelays.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inform Delays</title>
</head>

<body>
<script>
    var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
    localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    ?>


    <?php
    $data = [
        'currentController' => 'ConductorPages', // Adjust this based on your controller
        'currentMethod' => 'informDelays', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <div class="hero-container">
        <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
        <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

        <div class="background"></div>
    </div>

    <div class="body-section">

        <div class="sidebar">
            <h2 class="sidebar-header">Contact Admin</h2>
            <button class="InformDelays" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/InformDelays'">Inform Delays</button>
            <button class="RequestLeave" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/RequestLeave'">Request Leave</button>
            <button class="Notifications" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/Notifications'">Notifications</button>
        </div>

        <div class="detailbox">
            <form class="form-container">
                <label for="routeNo">Route Number</label>
                <input type="text" id="routeNo" name="routeNo" required>

                <label for="busNo">Bus Number</label>
                <input type="text" id="busNo" name="busNo" required>

                <label for="busRoute">Bus Route</label>
                <input type="text" id="busRoute" name="busRoute" required>

                <label for="time">Departure Time</label>
                <input type="time" id="time" name="time" required>

                <label for="newTime">New Departure Time</label>
                <input type="time" id="newTime" name="newTime" required>

                <label for="reason">Reason</label>
                <input type="text" id="reason" name="reason" required>

                <br>
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>