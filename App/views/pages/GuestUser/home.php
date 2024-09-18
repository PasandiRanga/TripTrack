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

    <link rel="stylesheet" href="./../../Component/Footer/footer.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>

    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'GuestUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    $data = [
        'currentController' => 'GuestPages', // Adjust this based on your controller
        'currentMethod' => 'home' // Adjust this based on the method
    ];
    ?>

    
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="./../../Component/Footer/footer.js"></script>
    <script src="./../../Component/Footer/topRoutes.js"></script>
    

    <div class="hero-container">
        <div class="background"></div>
        <img class="headerpic" src="<?php echo URLROOT; ?>/public/images/Main.png" alt="Main Image" />
        <div class="text-container">
            <?php require APPROOT . '/views/inc/Components/RotateText/rotateText.php'; ?>
        </div>
        <img class="name-image" src="<?php echo URLROOT; ?>/public/images/BlackName.png" alt="Name Image" />
    </div>
    <class="body-section">
        <br>
        <?php require APPROOT.'/views/inc/Components/SearchBar/searchBar.php'; ?>
        
        <div id="bus-card-container" class="bus-card-container">
            <?php require APPROOT . '/views/inc/Components/BusCard/busCardGenerator.php'; ?>
        </div>

        

        <div id="footer-container"></div>
    </div>

    
</body>
</html>
