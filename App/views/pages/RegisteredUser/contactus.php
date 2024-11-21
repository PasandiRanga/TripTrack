<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>ContactUs <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/contactus.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/ContactForm/contactForm.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'RegisteredUser';
    ?>


    <?php
    $data = [
        'currentController' => 'RegisteredPages', // Adjust this based on your controller
        'currentMethod' => 'contactUs', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<div class="hero-container">
    <br/>
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>

    <img class="name-image" src="<?php echo URLROOT; ?>/public/images/BlackName.png" alt="Name Image" />


    <?php require APPROOT.'/views/inc/Components/ContactForm/contactForm.php'; ?>
</div>

    <?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>

</body>
</html>