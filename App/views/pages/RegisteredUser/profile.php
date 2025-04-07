<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['RegisteredUser'])
?>
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
    <br/>
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Left Side: User Info -->
        <div class="profile-left">
            <div class="profile-pic">
                <img src="<?php echo URLROOT;?>/images/profileImages/<?php echo $_SESSION['user_profile_image'];?>" alt="Profile Picture" class="profile-pic">
            </div>
            <button class="edit-image-button" onclick="showImageUpdateBox()"><i class="fa-solid fa-pencil fa-sm"></i> Edit</button>            
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

        <!--Pop Up the image update box-->
        <div class="imageUpdateBox hidden" id="imageUpdateBox">
            <div class="imageUpdateBoxContent">
            <form action="<?php echo URLROOT ?>/RegisteredPages/updateProfileImage" method="POST" enctype="multipart/form-data">
                <!-- Profile Image Upload Section -->
                    <div class="form-drag-area">
                        <div class="icon">
                            <img src="<?php echo URLROOT; ?>/public/images/placeholder.jpg" alt="placeholder" width="90px" height="90px" id="placeholder">
                        </div>
                        <div class="right_content">
                            <div class="form_upload">
                                <input type="file" name="profile_image" id="profile_image" style="display:none" >
                                Browse File
                            </div>
                        </div>
                    </div>
                    <div class="form-validation">
                        <div class="profile_image_validation">
                            <img src="<?php echo URLROOT; ?>/public/images/tick1.png" alt="tick" width="35px" height="35px">
                            Selected a profile image
                        </div>
                    </div>
                    <span class="form-invalid"><?php echo isset($data['profile_image_err']) ? $data['profile_image_err'] : ''; ?></span>
                <div class="close-btn" onclick="closeImageUpdateBox()">×</div>

                <center><button class="Done" onclick="confirmImage()">Done</button></center>
            </form>
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
                <input type="mail" value="<?php echo $profile['Email']; ?>">
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
    console.log("Action Type Set:", actionType);
    document.getElementById("confirmBox").classList.remove("hidden");
}

function showImageUpdateBox() {
    document.getElementById("imageUpdateBox").classList.remove("hidden");
}

function closeConfirmBox() {
    document.getElementById("confirmBox").classList.add("hidden");
}

function closeImageUpdateBox() {
    document.getElementById("imageUpdateBox").classList.add("hidden");
}

function confirmAction() {
    console.log("Action Type:", actionType);
    if (actionType === 'logout') {
        window.location.href = '<?php echo URLROOT; ?>/GuestPages/logout'; // Ensure correct logout URL
    } else if (actionType === 'delete') {
        window.location.href = '<?php echo URLROOT; ?>/RegisteredPages/deleteAccount'; // Ensure correct delete URL
    }
    closeConfirmBox(); // Close the confirmation box after action is confirmed
}

function confirmImage(){
    window.location.href = '<?php echo URLROOT; ?>/RegisteredPages/updateProfileImage'; // Ensure correct update URL
}

function showUpdateBox() {
    document.getElementById('updateBox').classList.remove('hidden');
}

function closeUpdateBox() {
    document.getElementById('updateBox').classList.add('hidden');
}
</script>
    <script src="<?php echo URLROOT; ?>/public/js/signup.js"></script>


</body>
</html>
