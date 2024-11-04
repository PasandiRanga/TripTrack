<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../Component/NavBar/navbar.css">
    <link rel="stylesheet" href="./../../Component/Header/header.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
</head>
<body>
    <?php
    // Set the user role in PHP and pass it to JavaScript
    $userRole = 'Admin'; // Define the user role variable
    ?>
    
    <script>
        // Ensure this is set before the header loads
        localStorage.setItem('userRole', '<?php echo $userRole; ?>'); // Inject the PHP variable into JavaScript
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

