<!DOCTYPE html>
<html lang="en">
<head>
    
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/RequestLeave.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Leave</title>
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
        'currentMethod' => 'requestLeave', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/home'">Back</button>
    
    <div class="page-header">
        <h1>Request Leaves</h1>
    </div>

        <div class="container">
            <div class="leave-form">
                <h2>Fill the following details</h2>
                <form id="leaveForm">

                    <div class="form-group">
                        <div>
                            <label for="employeeId">Emplyee ID</label>
                            <input type="text" id="employeeId" name="employeeId" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label for="from-date">From:</label>
                            <input type="date" id="from-date" name="from-date" required>
                        </div>
                        <div>
                            <label for="to-date">To:</label>
                            <input type="date" id="to-date" name="to-date" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label for="noOfDays">Number of Days</label>
                            <input type="number" id="noOfDays" name="noOfDays" required>
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