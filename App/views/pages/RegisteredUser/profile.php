<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Profile <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/profile.css?v=<?php echo time(); ?>">
</head>
<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['user_role'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'RegisteredUser';
    ?>


    <?php
    $profile = $data['user'] ?? [];
    $data = [
        'currentController' => 'RegisteredPages', // Adjust this based on your controller
        'currentMethod' => 'profile', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>

    <script>
        // Encode the PHP array as JSON for JavaScript
        var profileData = <?php echo json_encode($profile); ?>;
        console.log("Profile Data:", profileData);
    </script>

    
    <!-- Header and Navbar -->
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>


    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Left Side: User Info -->
        <div class="profile-left">
            <div class="profile-pic">
                <img src="<?php echo URLROOT; ?>/public/images/profile.png" alt="User Profile Picture"> 
            </div>
            <button class="edit-image-button"><i class="fa-solid fa-pencil fa-sm"></i> Edit</button>            
            <h2><?php echo $profile['Name']; ?></h2>
            <p><?php echo $profile['User_id']; ?></p>
            <div class="btn">
                <button class="logout-button"><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>   LogOut</button>
                <button class="delete-account-button"><i class="fa fa-trash fa-lg" ></i>  Delete Account</button>
            </div>
        </div>

        <!-- Right Side: User Details -->
        <div class="profile-right">
            <div class="detail">
                <label>Full Name</label>
                <input type="text" value="<?php echo $profile['Name']; ?>" readonly>
            </div>
            <div class="detail">
                <label>Email Address</label>
                <input type="email" value="<?php echo $profile['Email']; ?>" readonly>
            </div>
            <div class="detail">
                <label>Contact Number</label>
                <input type="text" value="<?php echo $profile['Contact_number']; ?>" readonly>
            </div>
            <div class="detail">
                <label>NIC</label>
                <input type="text" value="<?php echo $profile['NIC']; ?>" readonly>
            </div>
            <div class="detail">
                <label>Address</label>
                <input type="text" value="<?php echo $profile['Address']; ?>" readonly>
            </div>

            <button class="edit-button">Edit</button>

        </div>
    </div>


</body>
</html>
