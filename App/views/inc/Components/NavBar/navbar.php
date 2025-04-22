<?php
// Helper function to check if the current page matches
if (!function_exists('isCurrentPage')) {
    function isCurrentPage($controller, $method, $currentController, $currentMethod) {
        return (strtolower($currentController) === strtolower($controller) && 
                strtolower($currentMethod) === strtolower($method));
    }
}

// Get current controller and method
$currentController = $data['currentController'] ?? '';
$currentMethod = $data['currentMethod'] ?? '';
$userRole = $data['userRole'] ?? '';
$notifications = $data['notifications'] ?? []; // Ensure the variable exists
?>

<nav class="navbar">
    <div id="navbar-container">
        <div id="navbar-items">
            <?php if ($userRole === "GuestUser"): ?>
                <a href="<?= URLROOT ?>/GuestPages/home" class="navbar-item <?= isCurrentPage('GuestPages', 'home', $currentController, $currentMethod) ? 'selected' : '' ?>">
                    <span class="text">SEARCH BUSES</span>
                </a>
                <a href="<?= URLROOT ?>/GuestPages/about" class="navbar-item <?= isCurrentPage('GuestPages', 'about', $currentController, $currentMethod) ? 'selected' : '' ?>">
                    <span class="text">ABOUT US</span>
                </a>
                <a href="<?= URLROOT ?>/GuestPages/contact" class="navbar-item <?= isCurrentPage('Guestpages', 'contact', $currentController, $currentMethod) ? 'selected' : '' ?>">
                    <span class="text">CONTACT US</span>
                </a>
            <?php elseif ($userRole === "RegisteredUser"): ?>
                <a href="<?= URLROOT ?>/RegisteredPages/Home" class="navbar-item <?= isCurrentPage('RegisteredPages', 'home', $currentController, $currentMethod) ? 'selected' : '' ?>">
                    <span class="text">HOME</span>
                </a>
                <a href="<?= URLROOT ?>/RegisteredPages/newBookings" class="navbar-item <?= isCurrentPage('RegisteredPages', 'bookings', $currentController, $currentMethod) ? 'selected' : '' ?>">
                    <span class="text">BOOKINGS</span>
                </a>
                <a href="<?= URLROOT ?>/RegisteredPages/contactUs" class="navbar-item <?= isCurrentPage('RegisteredPages', 'contactUs', $currentController, $currentMethod) ? 'selected' : '' ?>">
                    <span class="text">CONTACT US</span>
                </a>
                
                <!-- Add notifications and profile for mobile view -->
                <div class="mobile-only">
                    <a href="#" class="navbar-item notifications-item">
                        <i class="fa-solid fa-bell"></i>
                        <span class="text">NOTIFICATIONS</span>
                        <span class="badge"><?= count($notifications) ?></span>
                    </a>
                    <a href="<?= URLROOT ?>/RegisteredPages/profile" class="navbar-item profile-item">
                        <img src="<?= URLROOT ?>/images/profileImages/<?= $_SESSION['user_profile_image'] ?>" alt="Profile" class="mobile-profile-pic">
                        <span class="text">PROFILE</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <button id="navbar-toggle" class="navbar-toggle" aria-label="Toggle navigation">&#9776;</button>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarItems = document.getElementById('navbar-items');
    const header = document.querySelector('.header');
    
    navbarToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        navbarItems.classList.toggle('active');
        if (header) header.classList.toggle('expanded');
    });

    document.addEventListener('click', function(e) {
        if (!navbarItems.contains(e.target) && !navbarToggle.contains(e.target)) {
            navbarItems.classList.remove('active');
            if (header) header.classList.remove('expanded');
        }
    });

    navbarItems.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>