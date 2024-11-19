<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbarCopy.css?v=<?php echo time(); ?>">
</head>
<body>


<?php

// Assuming $data['currentController'] and $data['currentMethod'] are passed to this view
$currentController = $data['currentController'] ?? '';
// echo "Current controller is: " . $currentController;
$currentMethod = $data['currentMethod'] ?? '';
// echo "Current method is: " . $currentMethod;
$userRole = $data['userRole'] ?? '';

// Helper function to check if the current page matches

// Check if the function is already defined before declaring it
if (!function_exists('isCurrentPage')) {
    function isCurrentPage($controller, $method, $currentController, $currentMethod) {
        return (strtolower($currentController) === strtolower($controller) && 
                strtolower($currentMethod) === strtolower($method));
    }
}


?>

<nav class="navbar">
    <div id="navbar-container">
        <div id="navbar-items">
            <?php
            // Retrieve user role from session or default to 'GuestUser'
            // $userRole = isset($_SESSION['userRole']) ? $_SESSION['userRole'] : 'GuestUser';
            
            // Debug output for user role

            // Generate navbar items based on the user role
            if ($userRole === "GuestUser") {
                echo '<a href="' . URLROOT . '/GuestPages/home" class="navbar-item ' . (isCurrentPage('GuestPages', 'home', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Search Buses</span>
                    </a>
                    <a href="' . URLROOT . '/GuestPages/about" class="navbar-item ' . (isCurrentPage('GuestPages', 'about', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">About Us</span>
                    </a>
                    <a href="' . URLROOT . '/GuestPages/contact" class="navbar-item ' . (isCurrentPage('Guestpages', 'contact', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Contact Us</span>
                    </a>';
            } elseif ($userRole === "Admin") {
                echo '<a href="' . URLROOT . '/RegionalAdminPages/home" class="navbar-item ' . (isCurrentPage('RegionalAdminPages', 'home', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Home</span>
                    </a>
                    <a href="' . URLROOT . '/RegionalAdminPages/support" class="navbar-item ' . (isCurrentPage('RegionalAdminPages', 'support', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Support</span>
                    </a>
                    <a href="' . URLROOT . '/RegionalAdminPages/viewbookings" class="navbar-item ' . (isCurrentPage('RegionalAdminPages', 'viewbookings', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Bookings</span>
                    </a>
                    <a href="' . URLROOT . '/RegionalAdminPages/schedule" class="navbar-item ' . (isCurrentPage('RegionalAdminPages', 'schedule', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Schedule</span>
                    </a>
                    <a href="' . URLROOT . '/RegionalAdminPages/notifications" class="navbar-item ' . (isCurrentPage('RegionalAdminPages', 'notifications', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Notifications</span>
                    </a>';
            } elseif ($userRole === "RegisteredUser") {
                echo '<a href="' . URLROOT . '/RegisteredPages/Home" class="navbar-item ' . (isCurrentPage('RegisteredPages', 'home', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">HOME</span>
                    </a>
                    <a href="' . URLROOT . '/RegisteredPages/bookings" class="navbar-item ' . (isCurrentPage('RegisteredPages', 'bookings', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">BOOKINGS</span>
                    </a>
                    <a href="' . URLROOT . '/RegisteredPages/notification" class="navbar-item ' . (isCurrentPage('RegisteredPages', 'notification', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">NOTIFICATIONS</span>
                    </a>
                    <a href="' . URLROOT . '/RegisteredPages/contactUs" class="navbar-item ' . (isCurrentPage('RegisteredPages', 'contactUs', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">CONTACT US</span>
                    </a>';
            } elseif ($userRole === "Conductor") {
                echo '<a href="' . URLROOT . '/ConductorPages/home" class="navbar-item ' . (isCurrentPage('ConductorPages', 'home', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Home</span>
                    </a>
                    <a href="' . URLROOT . '/ConductorPages/viewAssigns" class="navbar-item ' . (isCurrentPage('ConductorPages', 'viewAssigns', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">View Assigns</span>
                    </a>
                    <a href="' . URLROOT . '/ConductorPages/scanQRcode" class="navbar-item ' . (isCurrentPage('ConductorPages', 'scanQRcode', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Scan QR Code</span>
                    </a>
                    <a href="' . URLROOT . '/ConductorPages/informDelays" class="navbar-item ' . (isCurrentPage('ConductorPages', 'informDelays', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">Contact Admin</span>
                    </a>';
            }
            ?>
        </div>
        <button id="navbar-toggle" class="navbar-toggle">&#9776;</button>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarItems = document.getElementById('navbar-items');

    navbarToggle.addEventListener('click', function() {
        navbarItems.classList.toggle('active');
    });
});
</script>

</body>
</html> 

