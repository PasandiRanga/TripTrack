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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/SuperAdmin/Profile.css?v=<?php echo time(); ?>">

    <style>
        .imageUpdateBox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .imageUpdateBoxContent {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 8px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        .profile_image_validation {
            display: none;
            margin-top: 10px;
            color: green;
        }

        .profile_image_validation.active {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

<?php
    $userRole = $_SESSION['userRole'] ?? 'Admin';
    $profile = $data;
    $_SESSION['user_profile_image'] = $profile['profile_pic'];
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
    <!-- Image Update Modal -->
    <div class="imageUpdateBox" id="imageUpdateBox">
        <div class="imageUpdateBoxContent">
            <form action="<?php echo URLROOT ?>/SuperAdminPages/updateProfileImage" method="POST" enctype="multipart/form-data">
                <div class="form-drag-area" id="dropArea">
                    <div class="icon">
                        <img src="<?php echo URLROOT; ?>/public/images/placeholder.jpg" alt="Current Profile" width="90px" height="90px" id="placeholder">
                    </div>
                    <div class="right_content">
                        <div class="form_upload">
                            <input type="file" name="profile_image" id="profile_image" style="display: none;" />
                            <button type="button" onclick="document.getElementById('profile_image').click();">Browse File</button>
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
                <center><button class="Done" type="submit">Done</button></center>
            </form>
        </div>
    </div>

    <!-- Left Side: Profile Image -->
    <div class="profile-left">
        <div class="profile-pic">
            <img src="<?php echo URLROOT;?>/images/profileImages/<?php echo $_SESSION['user_profile_image']; ?>" alt="Profile Picture" class="profile-pic">
        </div>
        <button class="edit-image-button" onclick="showImageUpdateBox()">
            <i class="fa-solid fa-pencil fa-sm"></i> Edit
        </button>
        <h2><?php echo $profile['name']; ?></h2>
        <p><?php echo $profile['employee_id']; ?></p>

        <button class="logout-button" onclick="Openpopup()">
            <i class="fa fa-sign-out fa-lg" aria-hidden="true"></i> LogOut
        </button>
    </div>

    <!-- Right Side: Admin Details -->
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
        document.getElementById("logoutModal").classList.remove("open-popup");
    }

    function showImageUpdateBox() {
        document.getElementById("imageUpdateBox").style.display = "flex";
    }

    function closeImageUpdateBox() {
        document.getElementById("imageUpdateBox").style.display = "none";
    }

    const dropArea = document.getElementById("dropArea");
    const inputPath = document.getElementById("profile_image");
    const placeholder = document.getElementById("placeholder");
    const validate = document.querySelector(".profile_image_validation");
    let file;

    inputPath.addEventListener("change", function () {
        file = this.files[0];
        showImage();
    });

    dropArea.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropArea.classList.add("active");
    });

    dropArea.addEventListener("dragleave", () => {
        dropArea.classList.remove("active");
    });

    dropArea.addEventListener("drop", (e) => {
        e.preventDefault();
        file = e.dataTransfer.files[0];
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        inputPath.files = dataTransfer.files;
        showImage();
        dropArea.classList.remove("active");
    });

    function showImage() {
        const validExtensions = ["image/jpeg", "image/jpg", "image/png"];
        if (validExtensions.includes(file.type)) {
            const reader = new FileReader();
            reader.onload = () => {
                placeholder.src = reader.result;
            };
            reader.readAsDataURL(file);
            validate.classList.add("active");
        } else {
            alert("Invalid file type. Only JPG, JPEG, PNG are allowed.");
        }
    }
</script>

</body>
</html>