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

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/home'">Back</button>

    <h1>Inform Delays</h1>

        <div class="container">
            <div class="delay-form">
                <h2>Fill the following details</h2>
                <form id="delayForm">
                
            `       <div class="form-group">
                        <div>
                            <label for="routeNo">Route Number</label>
                            <input type="text" id="routeNo" name="routeNo" required>
                        </div>
                        <div>
                            <label for="busNo">Bus Number</label>
                            <input type="text" id="busNo" name="busNo" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label for="busRoute">Bus Route</label>
                            <input type="text" id="busRoute" name="busRoute" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label for="time">Departure Time</label>
                            <input type="time" id="time" name="time" required>
                        </div>
                        <div>
                            <label for="newTime">New Departure Time</label>
                            <input type="time" id="newTime" name="newTime" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label for="reason">Reason</label>
                            <input type="text" id="reason" name="reason" required>
                        </div>
                    </div>

                        <br>
                        <button type="submit" class="submit-btn">Submit</button>

                </form>
            </div>
        </div>
</body>
</html>