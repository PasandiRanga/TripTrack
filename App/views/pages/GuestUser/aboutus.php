
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About Us - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/aboutus.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

</head>
<body>

    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'GuestUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'GuestUser';
    ?>

    <?php
    $data = [
        'currentController' => 'GuestPages', // Adjust this based on your controller
        'currentMethod' => 'about', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    
    <div class="hero-container">
    
        <br/>
        <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    
        <div class="about-container">
        
            <div class="about-section">
                <div class="about-info">
                    <h2>Trip Track</h2>
                    <p>We're dedicated to making your travel experience as seamless and enjoyable as possible. With our user-friendly platform, you can book bus tickets, check schedules, and plan your journeys with ease. <span class="name">Trip Track</span> is your go-to solution for comfortable and convenient bus travel across Sri Lanka.</p>
                </div>
                <img src="<?php echo URLROOT; ?>/Public/images/logo2.png" alt="Company Logo" class="company-logo">
             </div>

            <div class="owner-section">
                <div class="owner-details">
                <img src="<?php echo URLROOT; ?>/Public/images/owner.jpg" alt="Owner's Picture" class="owner-image">
                <div class="owner-info">
                        <h2>Nadika Perera</h2>
                        <p>Nadika Pererais the visionary behind <span class="name">Trip Track</span>. With a passion for technology and a deep understanding of the transportation industry, John founded <span class="name">Trip Track</span> to address the challenges faced by travelers in Sri Lanka. His mission is to provide a hassle-free, reliable, and modern platform for booking bus tickets and making travel planning easier for everyone.</p>
                        <p>Under John's leadership, <span class="name">Trip Track</span> has grown into a trusted name in the travel industry, known for its commitment to customer satisfaction and innovation. When he's not working on improving <span class="name">Trip Track</span>, John enjoys traveling and exploring new places, always on the lookout for ways to make travel more accessible and enjoyable for everyone.</p>
                    </div>
                </div>
            </div>

            <div class="vision-section">
                <div class="about-info">
                    <h2>Our Vision</h2>
                    <p>At <span class="name">Trip Track</span>, our vision is to revolutionize the way people travel by providing a platform that is easy to use, reliable, and offers the best options for bus travel across the country. We aim to be the leading travel companion for every traveler in Sri Lanka, ensuring that your journey is as smooth and enjoyable as possible.</p>
                </div>
                <img src="<?php echo URLROOT; ?>/Public/images/vision.png" alt="vision" class="vision-image">
            </div>
        </div>  
        <br/>
        
    <?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>
    </div>
      
</body>
</html>
