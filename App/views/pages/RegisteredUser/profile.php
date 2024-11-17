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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/ProfileForm/profileForm.css?v=<?php echo time(); ?>">

</head>
<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['user_role'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['user_role'] ?? 'RegisteredUser';
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
                <img src="<?php echo URLROOT;?>/images/profileImages/<?php echo $_SESSION['user_profile_image'];?>" alt="Profile Picture" class="profile-pic">
            </div>
            <button class="edit-image-button"><i class="fa-solid fa-pencil fa-sm"></i> Edit</button>            
            <h2><?php echo $profile['Name']; ?></h2>
            <p><?php echo $profile['User_id']; ?></p>

            <div class="btn">

            <button class="logout-button" onclick="showConfirmBox('logout')">
            <i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>   LogOut</button>

            <button class="delete-account-button" onclick="showConfirmBox('delete')">
            <i class="fa fa-trash fa-lg" ></i>  Delete Account</button>

            </div>
        </div>

        <!--Pop Up the confirmation box-->
        <div class="confirmBox hidden" id="confirmBox">
            <div class="confirmBoxContent">
                <h1>Are You Sure ? </h1>
                <h4>You won't be able to revert this !</h4>
                <p>
                <button id="yes" onclick="confirmAction()">Yes</button>
                <button id="no" onclick="closeConfirmBox()">No</button>
                </p>
                <div class="close-btn" onclick="closeConfirmBox()">×</div>
            </div>
        </div>

        <!-- Right Side: User Details -->
        <div class="profile-right">
            <div class="detail">
                <label>Full Name</label>
                <input type="text" value="<?php echo $profile['Name']; ?>">
            </div>
            <div class="detail">
                <label>Email Address</label>
                <input type="email" value="<?php echo $profile['Email']; ?>">
            </div>
            <div class="detail">
                <label>Contact Number</label>
                <input type="text" value="<?php echo $profile['Contact_number']; ?>">
            </div>
            <div class="detail">
                <label>NIC</label>
                <input type="text" value="<?php echo $profile['NIC']; ?>">
            </div>
            <div class="detail">
                <label>Address</label>
                <input type="text" value="<?php echo $profile['Address']; ?>">
            </div>

            <button class="edit-button" onclick="showUpdateBox()">Edit</button>
            
        </div>
    </div>

    <!-- UPDATION FORM-->
    <?php require APPROOT.'/views/inc/Components/ProfileForm/profileForm.php'; ?>

    <script>
        let actionType = "";

        function showConfirmBox(type) {
            actionType = type;
            document.getElementById("confirmBox").classList.remove("hidden");
        }

        function closeConfirmBox() {
            document.getElementById("confirmBox").classList.add("hidden");
        }

        function confirmAction() {
            if (actionType === 'logout') {
                window.location.href = '<?php echo URLROOT; ?>/GuestPages/logout';
            } else if (actionType === 'delete') {
                window.location.href = '<?php echo URLROOT; ?>/RegisteredPages/deleteAccount';
            }
            closeConfirmBox();
        }

        function showUpdateBox() {
             document.getElementById('updateBox').classList.remove('hidden');
         }

        function closeUpdateBox() {
            document.getElementById('updateBox').classList.add('hidden');
        }
    </script>

</body>
</html>
