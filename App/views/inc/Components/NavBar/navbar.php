<?php
// Assuming $data['currentController'] and $data['currentMethod'] are passed to this view
$currentController = $data['currentController'] ?? '';
$currentMethod = $data['currentMethod'] ?? '';

// Helper function to check if the current page matches
function isCurrentPage($controller, $method, $currentController, $currentMethod) {
    // Debug output
    

    return (strtolower($currentController) === strtolower($controller) && 
            strtolower($currentMethod) === strtolower($method));
}
?>

<nav class="navbar">
    <div id="navbar-container">
        <div id="navbar-items">
            <?php
            // Retrieve user role from session or default to 'GuestUser'
            $userRole = isset($_SESSION['userRole']) ? $_SESSION['userRole'] : 'GuestUser';

            // Generate navbar items based on the user role
            if ($userRole === "GuestUser") {
                echo '<a href="' . URLROOT . '/GuestPages/home" class="navbar-item ' . (isCurrentPage('GuestPages', 'home', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa fa-bus"></i>
                        <span class="text">Search Buses</span>
                    </a>
                    <a href="' . URLROOT . '/GuestPages/about" class="navbar-item ' . (isCurrentPage('pages', 'about', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa fa-users"></i>
                        <span class="text">About Us</span>
                    </a>
                    <a href="' . URLROOT . '/GuestPages/contact" class="navbar-item ' . (isCurrentPage('pages', 'contact', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa fa-phone"></i>
                        <span class="text">Contact Us</span>
                    </a>';
            } elseif ($userRole === "Admin") {
                echo '<a href="' . URLROOT . '/admin/home" class="navbar-item ' . (isCurrentPage('admin', 'home', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa fa-house"></i>
                        <span class="text">Home</span>
                    </a>
                    <a href="' . URLROOT . '/admin/support" class="navbar-item ' . (isCurrentPage('admin', 'support', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa-regular fa-message"></i>
                        <span class="text">Support</span>
                    </a>
                    <a href="' . URLROOT . '/admin/profile" class="navbar-item ' . (isCurrentPage('admin', 'profile', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa-regular fa-user"></i>
                        <span class="text">Profile</span>
                    </a>
                    <a href="' . URLROOT . '/admin/logout" class="navbar-item ' . (isCurrentPage('admin', 'logout', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span class="text">Log out</span>
                    </a>';
            } elseif ($userRole === "RegisteredUser") {
                echo '<a href="' . URLROOT . '/users/home" class="navbar-item ' . (isCurrentPage('users', 'home', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa fa-home"></i>
                        <span class="text">Home</span>
                    </a>
                    <a href="' . URLROOT . '/users/bookings" class="navbar-item ' . (isCurrentPage('users', 'bookings', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa fa-ticket" aria-hidden="true"></i>
                        <span class="text">Bookings</span>
                    </a>
                    <a href="' . URLROOT . '/users/searchbus" class="navbar-item ' . (isCurrentPage('users', 'searchbus', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa fa-bus"></i>
                        <span class="text">Search Buses</span>
                    </a>
                    <a href="' . URLROOT . '/users/notifications" class="navbar-item ' . (isCurrentPage('users', 'notifications', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa fa-bell" aria-hidden="true"></i>
                        <span class="text">Notifications</span>
                    </a>
                    <a href="' . URLROOT . '/users/contact" class="navbar-item ' . (isCurrentPage('users', 'contact', $currentController, $currentMethod) ? 'selected' : '') . '">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <span class="text">Contact Us</span>
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
