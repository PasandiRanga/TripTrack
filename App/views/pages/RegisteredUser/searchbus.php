<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>SearchBus <?php echo SITENAME; ?></title>

    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/searchbus.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusCard/busCard.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/searchBar/searchBar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/RotateText/rotateText.css">
    
</head>
<body>
<script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole); // Ensure this is set before the header loads
    </script>
    <!-- Header and Navbar -->
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

    <!-- Link the external JavaScript files -->
    <script src="./../../Component/Header/header.js"></script>
    <script src="./../../Component/NavBar/navbar.js"></script>
    <script src="./../../Component/SearchBar/searchBar.js"></script>
    <script src="./../Guest user/Components/RotatingText/heroTextRotater.js"></script>
    <!-- <script src="./../../Component/BusCard/busCard.js"></script> -->
    <script src="./../../Component/BusCard/busData.js"></script>
    <script src="./../../Component/BusCard/busCardGenerator.js"></script>

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

    <!-- Bus Cards Section -->
    <div class="body-section">
        <br>
        <div class="busCard-container">
            <div id="bus-card-container" class="bus-card-container"></div>
        </div>
    </div>

    <!-- Script to import header and navbar -->
    <script>
        // Import header
        fetch('./../../Component/HeaderRU.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });

        // Import navbar
        fetch('./../../Component/navbarRU.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('navbar').innerHTML = data;

            // After the navbar is loaded, add the event listeners
            const navbarItems = document.querySelectorAll('.navbar-item');

            // Add click event listener to each navbar item
            navbarItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove the 'selected' class from all items
                    navbarItems.forEach(nav => nav.classList.remove('selected'));

                    // Add the 'selected' class to the clicked item
                    this.classList.add('selected');
                });
            });
        })
        .catch(error => console.error('Error loading navbar:', error));

        function redirectToBusLayout() {
            window.location.href = "Bus_layout.html"; // Redirect to buslayout.html
        }
    </script>
    <script src="./../app.js"></script>

</body>
</html>