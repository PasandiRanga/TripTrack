<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Conductor' , 'Driver']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Profile</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/profile.css?v=<?php echo time(); ?>">
</head>
<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Conductor'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    ?>


    <?php
    $profile = $data['employee'] ?? [];
    $data = [
        'currentController' => 'ConductorPages', // Adjust this based on your controller
        'currentMethod' => 'profile', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>

    <script>
        // Encode the PHP array as JSON for JavaScript
        var profileData = <?php echo json_encode($profile); ?>;
        console.log("Profile Data:", profileData);
    </script>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/home'">Back</button>

    <h1>Employee Profile</h1>

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Left Side: User Info -->
        <div class="profile-left">
            <div class="profile-pic">
                <img src="<?php echo URLROOT; ?>/public/images/profile.png" alt="User Profile Picture"> 
            </div>
            <!--<button class="edit-image-button"><i class="fa-solid fa-pencil fa-sm"></i> Edit</button>-->         
            <h2><?php echo $profile['name']; ?></h2>
            <p><?php echo $profile['employee_id']; ?></p>
            <div class="btn">
                <button class="logout-button"><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>   LogOut</button>
            </div>
        </div>

        <!-- Right Side: User Details -->
        <div class="profile-right">
            <div class="detail">
                <label>Full Name</label>
                <input type="text" value="<?php echo $profile['name']; ?>" readonly>
            </div>
            <div class="detail">
                <label>Employee ID</label>
                <input type="text" value="<?php echo $profile['employee_id']; ?>" readonly>
            </div>
            <div class="detail">
                <label>Occupation</label>
                <input type="text" value="<?php echo $profile['role']; ?>"readonly>
            </div>
            <div class="detail">
                <label>Email Address</label>
                <input type="email" value="<?php echo $profile['email']; ?>" readonly>
            </div>
            <div class="detail">
                <label>Contact Number</label>
                <input type="text" value="<?php echo $profile['contactNo']; ?>" readonly>
            </div>
            <div class="detail">
                <label>NIC</label>
                <input type="text" value="<?php echo $profile['nic']; ?>" readonly>
            </div>
            <div class="detail">
                <label>Address</label>
                <input type="text" value="<?php echo $profile['address']; ?>"readonly>
            </div>
        </div>
    </div>


</body>
</html>
