<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Profile <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/profile.css">
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

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Left Side: User Info -->
        <div class="profile-left">
            <div class="profile-pic">
                <img src="./../../images/profile.png" alt="User Profile Picture">
                <button class="edit-button"><i class="fa-solid fa-pencil fa-sm"></i> Edit</button>
            </div>
            <h2>Susan S.Doe</h2>
            <p>TT3452D</p>
            <button class="logout-button">LogOut</button>
        </div>

        <!-- Right Side: User Details -->
        <div class="profile-right">
            <div class="detail">
                <label>Full Name</label>
                <input type="text" value="Susan Sofia Doe" readonly>
            </div>
            <div class="detail">
                <label>Email Address</label>
                <input type="email" value="susansofiadoe@gmail.com" readonly>
            </div>
            <div class="detail">
                <label>Contact Number</label>
                <input type="text" value="+94 528510362" readonly>
            </div>
            <div class="detail">
                <label>NIC</label>
                <input type="text" value="931650254869" readonly>
            </div>
            <div class="detail">
                <label>HomeTown</label>
                <input type="text" value="Colombo" readonly>
            </div>
        </div>
    </div>


</body>
</html>
