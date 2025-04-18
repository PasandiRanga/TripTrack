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
    $profile = $data;
    error_log("profile details at view: " . print_r($data, true));
    // echo '<pre>';
    //         print_r($_SESSION['user_profile_image']);
    //         echo '</pre>';
    //         exit();

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

    <div class="page-header">
        <h1>Employee Profile</h1>
    </div>

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Left Side: User Info -->
        <div class="profile-left">
            <div class="profile-pic">
            <img src="<?php echo URLROOT;?>/images/profileImages/<?php echo $profile['profile_pic'];?>" alt="Profile Picture" class="profile-pic">
            </div>
            <!--<button class="edit-image-button"><i class="fa-solid fa-pencil fa-sm"></i> Edit</button>-->         
            <h2><?php echo $profile['name']; ?></h2>
            <p><?php echo $profile['employee_id']; ?></p>

            <button class="logout-button" onclick="Openpopup()"><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>  LogOut</button>
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

    <div class="modal-overlay" id="logoutModal">
        <div class="modal-content">
            <h2>Are you sure you want to logout?</h2>
            <p>This will end your current session.</p>
            <div class="modal-buttons">
                <button class="modal-button btn-yes" onclick="proceedLogout()">Yes</button>
                <button class="modal-button btn-no" onclick="cancelLogout()">No</button>
            </div>
        </div>
    </div>

    <script>

        function Openpopup() {
            const popup = document.getElementById("logoutModal");
            popup.classList.add("open-popup"); // Add the class to make modal visible
        }
        
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        // Hide the logout modal
        function hideLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        // Proceed with logout and redirect to login page
        function proceedLogout() {
            window.location.href = "<?php echo URLROOT; ?>/GuestPages/logout";// Replace with your login form file
        }


        // Function to redirect back to profile
        function cancelLogout() {
            window.location.href = "<?php echo URLROOT; ?>/ConductorPages/profile"; // Replace with your dashboard file
        }

    </script>


</body>
</html>
