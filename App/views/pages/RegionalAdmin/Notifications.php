<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/buttons/button.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/searchBar/searchBar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/RotateText/rotateText.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusCard/busCard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegionalAdmin/Notifications.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification</title>
</head>

<body>
<script>
    var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Admin'); ?>;
    localStorage.setItem('userRole', userRole);
</script>

<?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'Admin';
    $data = [
        'currentController' => 'RegionalAdminPages',
        'currentMethod' => 'notifications',
        'userRole' => $userRole
    ];
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<div class="hero-container">
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

    <div class="background"></div>
    <div class="text-container">
        <?php require APPROOT . '/views/inc/Components/RotateText/rotateText.php'; ?>
    </div>
    <img class="name-image" src="<?php echo URLROOT; ?>/public/images/BlackName.png" alt="Name Image" />
</div>

<div class="body-section">
    <h2>Send Notification</h2>

    <form id="notificationForm" onsubmit="return submitNotification()" class="notification-form">
        <label for="notificationType">Select Notification Type:</label>
        <select id="notificationType" name="notificationType" onchange="toggleNotificationFields()">
            <option value="">Select Type</option>
            <option value="employee">Employee</option>
            <option value="passenger">Passenger</option>
        </select>

        <div id="employeeFields" class="notification-section" style="display: none;">
            <label for="employeeId">Employee ID:</label>
            <input type="text" id="employeeId" name="employeeId" placeholder="Enter Employee ID">

            <label for="employeeMessage">Notification Content:</label>
            <textarea id="employeeMessage" name="employeeMessage" placeholder="Enter notification for employee"></textarea>
        </div>

        <div id="passengerFields" class="notification-section" style="display: none;">
            <label for="schedule">Select Schedule:</label>
            <select id="schedule" name="schedule">
                <option value="1">Route 1 - Bus 1001</option>
                <option value="2">Route 2 - Bus 1002</option>
                <option value="3">Route 3 - Bus 1003</option>
            </select>

            <label for="passengerMessage">Notification Content:</label>
            <textarea id="passengerMessage" name="passengerMessage" placeholder="Enter notification for passengers"></textarea>
        </div>

        <button type="submit">Send Notification</button>
    </form>

    <script>
        function toggleNotificationFields() {
            const notificationType = document.getElementById("notificationType").value;
            const employeeFields = document.getElementById("employeeFields");
            const passengerFields = document.getElementById("passengerFields");

            employeeFields.style.display = notificationType === "employee" ? "block" : "none";
            passengerFields.style.display = notificationType === "passenger" ? "block" : "none";
        }

        function submitNotification() {
            const notificationType = document.getElementById("notificationType").value;

            if (notificationType === "employee") {
                const employeeId = document.getElementById("employeeId").value;
                const employeeMessage = document.getElementById("employeeMessage").value;
                
                if (!employeeId || !employeeMessage) {
                    alert("Please fill in all fields for the employee notification.");
                    return false;
                }

                alert(`Notification sent to Employee ID: ${employeeId}`);
            } else if (notificationType === "passenger") {
                const schedule = document.getElementById("schedule").value;
                const passengerMessage = document.getElementById("passengerMessage").value;

                if (!schedule || !passengerMessage) {
                    alert("Please fill in all fields for the passenger notification.");
                    return false;
                }

                alert(`Notification sent for Schedule: ${schedule}`);
            } else {
                alert("Please select a notification type.");
                return false;
            }

            return true;
        }
    </script>

    <div class="footer-container">
        <?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>
    </div>
</div>
</body>
</html>
