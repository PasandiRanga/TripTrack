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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>

    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'RegisteredUser';
    $scheduleData = $data['schedule'] ?? [];
    $busData = $data['bus'] ?? [];
    $distanceData = $data['distance'] ?? [];

    ?>
    <script>
        var scheduleData = <?php echo json_encode($scheduleData); ?>;
        var busData = <?php echo json_encode($busData); ?>;
        console.log("Schedule Data: ", scheduleData);
        console.log("Bus Data: ", busData);  
    </script>

<?php
    $data = [
        'currentController' => 'RegisteredPages', // Adjust this based on your controller
        'currentMethod' => 'home', // Adjust this based on the method
        'userRole' => $userRole,
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
    
    <class="body-section">
        <br>
        <?php require APPROOT.'/views/inc/Components/SearchBar/searchBar.php'; ?>
        
        <div id="bus-card-container" class="bus-card-container">
            <?php 
                // Pass $data['schedule'] to busCardGenerator.php
                require APPROOT . '/views/inc/Components/BusCard/busCardGenerator.php';
            ?>
        </div>

        
        <?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>
    </div>

    
</body>
</html>
