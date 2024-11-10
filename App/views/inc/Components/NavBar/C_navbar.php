<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<nav class="navbar">
    <div id="navbar-container">
        <div id="navbar-items">
            <a href="<?php echo URLROOT; ?>/ConductorPages/home" class="navbar-item">
                <i class="fa fa-home"></i>
                <span class="text">Home</span>
            </a>
            <a href="<?php echo URLROOT; ?>/ConductorPages/viewAssigns" class="navbar-item">
                <i class="fa fa-bus"></i>
                <span class="text">View Assigns</span>
            </a>
            <a href="<?php echo URLROOT; ?>/ConductorPages/notifications" class="navbar-item">
                <i class="fa fa-qrcode"></i>
                <span class="text">Scan QR Code</span>
            </a>
            <a href="<?php echo URLROOT; ?>/ConductorPages/informDelays" class="navbar-item">
                <i class="fa fa-phone"></i>
                <span class="text">Contact Admin</span>
            </a>
        </div>
        <!-- Navbar Toggle Button -->
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
            