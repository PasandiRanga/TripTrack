<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbarCopy.css?v=<?php echo time(); ?>"0>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
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
                        <span class="text">SEARCH BUSES</span>
                    </a>
                    <a href="' . URLROOT . '/GuestPages/about" class="navbar-item ' . (isCurrentPage('GuestPages', 'about', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">ABOUT US</span>
                    </a>
                    <a href="' . URLROOT . '/GuestPages/contact" class="navbar-item ' . (isCurrentPage('Guestpages', 'contact', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">CONTACT US</span>
                    </a>';
            } elseif ($userRole === "RegisteredUser") {
                echo '<a href="' . URLROOT . '/RegisteredPages/Home" class="navbar-item ' . (isCurrentPage('RegisteredPages', 'home', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">HOME</span>
                    </a>
                    <a href="' . URLROOT . '/RegisteredPages/bookings" class="navbar-item ' . (isCurrentPage('RegisteredPages', 'bookings', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">BOOKINGS</span>
                    </a>
                    <a href="' . URLROOT . '/RegisteredPages/contactUs" class="navbar-item ' . (isCurrentPage('RegisteredPages', 'contactUs', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <span class="text">CONTACT US</span>
                    </a>';
            } 
            // elseif ($userRole === "Conductor") {
            //     echo '<a href="' . URLROOT . '/ConductorPages/home" class="navbar-item ' . (isCurrentPage('ConductorPages', 'home', $currentController, $currentMethod) ? 'selected' : '') . '">
            //             <span class="text">Home</span>
            //         </a>
            //         <a href="' . URLROOT . '/ConductorPages/viewAssigns" class="navbar-item ' . (isCurrentPage('ConductorPages', 'viewAssigns', $currentController, $currentMethod) ? 'selected' : '') . '">
            //             <span class="text">View Assigns</span>
            //         </a>
            //         <a href="' . URLROOT . '/ConductorPages/scanQRcode" class="navbar-item ' . (isCurrentPage('ConductorPages', 'scanQRcode', $currentController, $currentMethod) ? 'selected' : '') . '">
            //             <span class="text">Scan QR Code</span>
            //         </a>
            //         <a href="' . URLROOT . '/ConductorPages/informDelays" class="navbar-item ' . (isCurrentPage('ConductorPages', 'informDelays', $currentController, $currentMethod) ? 'selected' : '') . '">
            //             <span class="text">Contact Admin</span>
            //         </a>';
            // }
            ?>
        </div>
        <button id="navbar-toggle" class="navbar-toggle" aria-label="Toggle navigation">&#9776;</button>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarItems = document.getElementById('navbar-items');
    
    // Create overlay element
    const overlay = document.createElement('div');
    overlay.className = 'navbar-overlay';
    document.body.appendChild(overlay);

    navbarToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        navbarItems.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    // Close menu when clicking overlay
    overlay.addEventListener('click', function() {
        navbarItems.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navbarItems.contains(e.target) && !navbarToggle.contains(e.target)) {
            navbarItems.classList.remove('active');
            overlay.classList.remove('active');
        }
    });
});
</script>

</body>
</html> 

