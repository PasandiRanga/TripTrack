<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>SeeTicket <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/seeTicket.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">

</head>
<body>
<script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole); // Ensure this is set before the header loads
    </script>
    <!-- Header and Navbar -->
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>

    <!-- Link the external JavaScript files -->
    <script src="./../../Component/Header/header.js"></script>
    
</body>
</html>