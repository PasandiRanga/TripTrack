<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Notification <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/Notification.css">
   
</head>
<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole); // Ensure this is set before the header loads
    </script>
    <!-- Header and Navbar -->
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?> 

    <!-- Link the external JavaScript files -->
    <script src="./../../Component/Header/header.js"></script>
    <script src="./../../Component/NavBar/navbar.js"></script>

    <div class="notification-container">
        <!-- Notification Item -->
        <div class="notification-item">
            <div class="notification-header">
                <div>
                    <h3 class="notification-topic">Booking confiramtion </h3>
                    <h4> August 12, 2024, 2:00 PM</h4>
                </div>
                <span class="dropdown-arrow">&#9660;</span>
            </div>
            <div class="notification-content">
                <p>Your booking for bus route #45 from City A to City B on August 12, 2024, at 3:00 PM has been 
                    confirmed.Your seat number is 12A.</p>
            </div>
        </div>
    </div>

    <div class="notification-container">
        <!-- Notification Item -->
        <div class="notification-item">
            <div class="notification-header">
                <div>
                    <h3 class="notification-topic">Bus delay alert</h3>
                    <h4>August 10, 2024, 11:45 AM</h4>
                </div>
                <span class="dropdown-arrow">&#9660;</span>
            </div>
            <div class="notification-content">
                <p>This is the description of Notification 2. Here you can provide details about the notification topic.</p>
            </div>
        </div>
    </div>

    <div class="notification-container">
        <!-- Notification Item -->
        <div class="notification-item">
            <div class="notification-header">
                <div>
                    <h3 class="notification-topic">Cancelation confirmation</h3>
                    <h4>  August 9, 2024, 5:00 PM</h4>
                </div>
                <span class="dropdown-arrow">&#9660;</span>
            </div>
            <div class="notification-content">
                <p>This is the description of Notification 3. Here you can provide details about the notification topic.</p>
            </div>
        </div>
    </div>
            <!-- Add more notifications as needed -->

    <script src="scripts.js"></script>
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
