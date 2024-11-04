<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../Component/NavBar/navbar.css">
    <link rel="stylesheet" href="./../../Component/Header/header.css">
    <!--<link rel="stylesheet" href="../../../../Public/CSS/SuperAdmin/Super_Admin_Logout.css"> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
</head>
<body>
    <?php
    // You can set any necessary PHP variables here
    $userRole = 'Admin'; // Example variable
    ?>
    
    <script>
        // Pass the PHP variable to JavaScript
        localStorage.setItem('userRole', '<?php echo $userRole; ?>'); // Ensure this is set before the header loads
    </script>
    
    <!-- Header container where the header.html content will be injected -->
    <div id="header-container"></div>

    <!-- Navbar container where the navbar.html content will be injected -->
    <div id="navbar-container"></div>   

    <!-- Link the external JavaScript files -->
    <script src="./../../Component/Header/header.js"></script>
    <script src="./../../Component/NavBar/navbar.js"></script>
</body>
</html>
