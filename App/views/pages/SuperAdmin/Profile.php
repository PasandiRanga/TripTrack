<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Conductor/profile.css?v=<?php echo time(); ?>">
</head>
<body>

<?php
    $userRole = $_SESSION['userRole'] ?? 'Admin';
    $profile = $data;
?>

<script>
    var userRole = <?php echo json_encode($userRole); ?>;
    localStorage.setItem('userRole', userRole);
</script>

<button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

<div class="page-header">
    <h1>Admin Profile</h1>
</div>

<!-- Profile Container -->
<div class="profile-container">
    <!-- Left Side: Profile Image + Logout -->
    <div class="profile-left">
        <div class="profile-pic">
            <img src="<?php echo URLROOT;?>/images/profileImages/<?php echo $_SESSION['user_profile_image']; ?>" alt="Profile Picture" class="profile-pic">
        </div>
        <h2><?php echo $profile['name']; ?></h2>
        <p><?php echo $profile['employee_id']; ?></p>

        <button class="logout-button" onclick="Openpopup()"><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i> LogOut</button>
    </div>

    <!-- Right Side: Admin Info -->
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
            <label>Role</label>
            <input type="text" value="<?php echo $profile['role']; ?>" readonly>
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
            <input type="text" value="<?php echo $profile['address']; ?>" readonly>
        </div>
    </div>
</div>

<!-- Logout Modal -->
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
        document.getElementById("logoutModal").classList.add("open-popup");
    }

    function proceedLogout() {
        window.location.href = "<?php echo URLROOT; ?>/GuestPages/logout";
    }

    function cancelLogout() {
        window.location.href = "<?php echo URLROOT; ?>/SuperAdminPages/profile";
    }
</script>

</body>
</html>
