<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/Notifications.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
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
        'currentMethod' => 'notifications', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/newhome'">Back</button>

    <h1>Notifications</h1>

    <div class="container">

    <div class="notification-container">
        <!-- Notification Item -->
        <div class="notification-item">
            <div class="notification-header">
                <div>
                    <h3 class="notification-topic">Change of the upcoming schedule</h3>
                    <h4> Noivember 10, 2024, 3:00 PM</h4>
                </div>
                <span class="dropdown-arrow">&#9660;</span>
            </div>
            <div class="notification-content">
                <p>Your schedule for 15th of November has been changed. View the "Dashboard" to see the updated schedule.</p>
            </div>
        </div>
    </div>

    <div class="notification-container">
        <!-- Notification Item -->
        <div class="notification-item">
            <div class="notification-header">
                <div>
                    <h3 class="notification-topic">Approval of leave request</h3>
                    <h4> November 11, 2024, 4:14 AM</h4>
                </div>
                <span class="dropdown-arrow">&#9660;</span>
            </div>
            <div class="notification-content">
                <p>Your leave request for following dates have been approved.<br>November 15, 2024<br>November 16, 2024<br>November 17, 2024</p>
            </div>
        </div>
    </div>

    <div class="notification-container">
        <!-- Notification Item -->
        <div class="notification-item">
            <div class="notification-header">
                <div>
                    <h3 class="notification-topic">Approval of leave request</h3>
                    <h4> November 22, 2024, 4:14 AM</h4>
                </div>
                <span class="dropdown-arrow">&#9660;</span>
            </div>
            <div class="notification-content">
                <p>Your leave request for following dates have been approved.<br>November 25, 2024<br>November 26, 2024<br>November 27, 2024</p>
            </div>
        </div>
    </div>

    </div>

    <script>
        document.querySelectorAll('.notification-header').forEach(header => {
        header.addEventListener('click', function() {
        const notificationItem = this.parentElement;
        notificationItem.classList.toggle('open');
    });
});

    </script>
</body>
</html>