<!-- filepath: c:\xampp\htdocs\TripTrack\App\views\pages\SuperAdmin\Profile.php -->
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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Profile.css?v=<?php echo time(); ?>">
</head>
<body>
    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>
    <div class="profile-container">
        <h1>Admin Profile</h1>
        <div class="profile-details">
            <!-- Profile Image -->
            <div class="profile-image-container">
                <img id="profile-image-preview" src="<?php echo URLROOT; ?>/public/uploads/<?php echo $data['profileImage'] ?? 'default.png'; ?>" alt="Profile Image">
            </div>

            <!-- Admin Details -->
            <div class="details-box">
                <div class="detail-group">
                    <label>Employee ID:</label>
                    <p><?php echo $data['employeeId']; ?></p>
                </div>

                <div class="detail-group">
                    <label>Name:</label>
                    <p><?php echo $data['name']; ?></p>
                </div>

                <div class="detail-group">
                    <label>NIC:</label>
                    <p><?php echo $data['nic']; ?></p>
                </div>

                <div class="detail-group">
                    <label>Address:</label>
                    <p><?php echo $data['address']; ?></p>
                </div>

                <div class="detail-group">
                    <label>Contact No:</label>
                    <p><?php echo $data['phone']; ?></p>
                </div>

                <div class="detail-group">
                    <label>Email:</label>
                    <p><?php echo $data['email']; ?></p>
                </div>

                <div class="detail-group">
                    <label>Role:</label>
                    <p><?php echo $data['role']; ?></p>
                </div>
            </div>

            <!-- Update Profile Button -->
            <div class="button-group">
                <button onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/editProfile'">Edit Profile</button>
            </div>
        </div>
    </div>
</body>
</html>