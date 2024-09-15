<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/buttons/button.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/searchBar/searchBar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/RotateText/rotateText.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/home.css">

    <link rel="stylesheet" href="./../../Component/BusCard/busCard.css">
    <link rel="stylesheet" href="./../../Component/Footer/footer.css">
    <link rel="stylesheet" href="./Components/RotatingText/rotateText.css" type="text/css"> <!-- Add this line -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>

    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'GuestUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>
    
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>
    
    <!-- Link the external JavaScript files -->
   
    <script src="./../../Component/BusCard/busData.js"></script>
    <script src="./../../Component/BusCard/busCardGenerator.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="./../../Component/Footer/footer.js"></script>
    <script src="./../../Component/Footer/topRoutes.js"></script>
    

    <div class="hero-container">
        <div class="background"></div>
        <img class="headerpic" src="<?php echo URLROOT; ?>/public/images/Main.png" alt="Main Image" />
        <div class="text-container">
            <?php require APPROOT . '/views/inc/Components/RotateText/rotateText.php'; ?>
        </div>
        <img class="name-image" src="<?php echo URLROOT; ?>/public/images/BlackName.png" alt="Main Image" />
    </div>
    <div class="body-section">
        <br>
        <?php require APPROOT.'/views/inc/Components/SearchBar/searchBar.php'; ?>
        <div class="busCard-container">
            <div id="bus-card-container" class="bus-card-container"></div>
        </div>

        <div id="footer-container"></div>
    </div>

    
</body>
</html>
