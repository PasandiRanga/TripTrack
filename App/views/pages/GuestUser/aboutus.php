
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About Us - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/aboutus.css">
</head>
<body>

    <script>
        // Safely encode PHP variable for use in JavaScript
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'GuestUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>



    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>

    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>


    <div class="about-container">
        <div class="about-section">
            <h2>About Trip Track</h2>
            <p>Welcome to Trip Track! We're dedicated to making your travel experience as seamless and enjoyable as possible. With our user-friendly platform, you can book bus tickets, check schedules, and plan your journeys with ease. Trip Track is your go-to solution for comfortable and convenient bus travel across Sri Lanka.</p>
        </div>

        <div class="owner-section">
            <h2>Meet the Owner</h2>
            <div class="owner-details">
                <img src="./images/owner.jpg" alt="Owner's Picture" class="owner-image">
                <div class="owner-info">
                    <h3>John Doe</h3>
                    <p>John Doe is the visionary behind Trip Track. With a passion for technology and a deep understanding of the transportation industry, John founded Trip Track to address the challenges faced by travelers in Sri Lanka. His mission is to provide a hassle-free, reliable, and modern platform for booking bus tickets and making travel planning easier for everyone.</p>
                    <p>Under John's leadership, Trip Track has grown into a trusted name in the travel industry, known for its commitment to customer satisfaction and innovation. When he's not working on improving Trip Track, John enjoys traveling and exploring new places, always on the lookout for ways to make travel more accessible and enjoyable for everyone.</p>
                </div>
            </div>
        </div>

        <div class="vision-section">
            <h2>Our Vision</h2>
            <p>At Trip Track, our vision is to revolutionize the way people travel by providing a platform that is easy to use, reliable, and offers the best options for bus travel across the country. We aim to be the leading travel companion for every traveler in Sri Lanka, ensuring that your journey is as smooth and enjoyable as possible.</p>
        </div>
    </div>  
   
</body>
</html>
