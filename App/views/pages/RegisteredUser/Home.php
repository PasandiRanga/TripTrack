<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Home <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/Home.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusCard/busCard.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/seachBar/searchBar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/RotateText/nrotateText.css">
    <link rel="stylesheet" href="./../../Component/Footer/footer.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css">


</head>
<body>
    <!-- Header Placeholder -->
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole); // Ensure this is set before the header loads
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

    <!-- Link the external JavaScript files -->
    <script src="./../../Component/Header/header.js"></script>
    <script src="./../../Component/NavBar/navbar.js"></script>
    <script src="./../../Component/BusCard/busData.js"></script>
    <script src="./../../Component/SearchBar/searchBar.js"></script>
    <script src="./../../Component/BusCard/busCardGenerator.js"></script>
    <script src="./../Guest user/Components/RotatingText/heroTextRotater.js"></script>
    <script src="./../../Component/Footer/footer.js"></script>
    <script src="./../../Component/Footer/topRoutes.js"></script>

    <!-- Hero Section -->
    <div class="hero-container">
        <div class="background"></div>
        <img class="main-image" src="./../Guest user/images/image.png" />
        <img class="bus" src="./../Guest user/images/bus.png" />
        <div class="text-container">
            <div class="rotate-text"></div>
        </div>
        <img class="name-image" src="./../Guest user/images/name.png" />
    </div>

    <!--SearchBar-->
    <div id="search-bar-container"></div>

    <!--Reminder section-->
    <section class="booking-box">
         <h3>Up Coming Bookings  <i class="fa-solid fa-bell"></i></h3>
    <div class="booking">
        <div class="route">
            <i class="fa-solid fa-bus"></i>
            Shripura-Colombo
        </div>
        <div class="details">
            <span>Due on: 20/09/2024</span>
            <span>08.00 a.m.</span>
        </div>
    </div>
    <div class="booking">
        <div class="route">
            <i class="fa-solid fa-bus"></i>
            Colombo-Jaffna
        </div>
        <div class="details">
            <span>Due on: 22/09/2024</span>
            <span>12.00 p.m.</span>
        </div>
    </div>
    <div class="booking">
        <div class="route">
            <i class="fa-solid fa-bus"></i>
            Mtale-Colombo
        </div>
        <div class="details">
            <span>Due on: 24/09/2024</span>
            <span>07.00 p.m.</span>
        </div>
    </div>
    </section>

    <!-- Bus Cards Section -->
    <div class="body-section">
        <br>
        <div class="busCard-container">
            <div id="bus-card-container" class="bus-card-container"></div>
        </div>
    </div>

    <div class="body-section">
        <div id="footer-container"></div>
    </div>

    <script>
        function redirectToBusLayout() {
            window.location.href = "Bus_layout.html"; // Redirect to buslayout.html
        }
        function redirectToBusSearch() {
            window.location.href = "searchbus.html"; // Redirect to buslayout.html
        }
    </script>
        

</body>
</html>
