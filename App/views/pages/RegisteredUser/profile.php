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
        $userRole = $_SESSION['user_role'] ?? 'RegisteredUser';
    ?>


    <?php
    $profile = $data['user'] ?? [];
    $notifications = $data['notifications'] ?? [];
    $data['currentController'] = 'RegisteredPages';
    $data['currentMethod'] = 'profile';
    $data['userRole'] = $userRole;
    ?>

    <script>
        var profileData = <?php echo json_encode($profile); ?>;
        console.log("Profile Data:", profileData);
    </script>

    
    <br/>
    <center><?php require APPROOT.'/views/inc/Components/Header/header.php'; ?></center>

    <div class="profile-container">
        <div class="profile-left">
            <div class="profile-pic">
                <img src="<?php echo URLROOT;?>/images/profileImages/<?php echo $_SESSION['user_profile_image'];?>" alt="Profile Picture" class="profile-pic">
            </div>
            <button class="edit-image-button" onclick="showImageUpdateBox()"><i class="fa-solid fa-pencil fa-sm"></i> Edit</button>            
            <h2><?php echo $profile['Name']; ?></h2>

            <div class="btn">

            <button class="logout-button" onclick="showConfirmBox('logout')">
            <i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>   LogOut</button>

            <button class="delete-account-button" onclick="showConfirmBox('delete')">
            <i class="fa fa-trash fa-lg" ></i>  Delete Account</button>

            </div>
        </div>

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

        <div class="imageUpdateBox hidden" id="imageUpdateBox">
            <div class="imageUpdateBoxContent">
            <form action="<?php echo URLROOT ?>/RegisteredPages/updateProfileImage" method="POST" enctype="multipart/form-data" id="imageUploadForm">
    <div class="form-drag-area">
        <div class="icon">
            <img src="<?php echo URLROOT; ?>/public/images/placeholder.jpg" alt="placeholder" width="90px" height="90px" id="placeholder">
        </div>
        <div class="right_content">
            <div class="form_upload">
                <input type="file" name="profile_image" id="profile_image" style="display:none" required>
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

    <center><button type="submit" class="Done" id="submitImage">Done</button></center>
</form>
            </div>
        </div>

        <div class="profile-right">
            <div class="detail">
                <label>Full Name</label>
                <input type="text" value="<?php echo $profile['Name']; ?>"readonly>
            </div>
            <div class="detail">
                <label>Email Address</label>
                <input type="mail" value="<?php echo $profile['Email']; ?>"readonly>
            </div>
            <div class="detail">
                <label>Contact Number</label>
                <input type="text" value="<?php echo $profile['Contact_number']; ?>"readonly>
            </div>
            <div class="detail">
                <label>NIC</label>
                <input type="text" value="<?php echo $profile['NIC']; ?>"readonly>
            </div>
            <div class="detail">
                <label>Address</label>
                <input type="text" value="<?php echo $profile['Address']; ?>"readonly>
            </div>

            <button class="edit-button" onclick="showUpdateBox()">Edit</button>
            
        </div>
    </div>

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

    const dropArea = document.querySelector(".form-drag-area");
    const dropText = document.querySelector(".description");
    const browseButton = document.querySelector(".form_upload");
    const inputPath = document.querySelector("#profile_image");
    const placeholder = document.querySelector("#placeholder");
    const validate = document.querySelector(".profile_image_validation");
    let file;

    browseButton.onclick = () => {
        inputPath.click();
    };

    inputPath.addEventListener("change", function () {
        file = this.files[0];
        showImage();
    });

    dropArea.addEventListener("drop", (event) => {
        event.preventDefault();
        file = event.dataTransfer.files[0];

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        inputPath.files = dataTransfer.files;
        showImage();
        dropArea.classList.remove("active");
    });

    function showImage() {
        const fileType = file.type;

        const validExtensions = ["image/jpeg", "image/jpg", "image/png"];

        if (validExtensions.includes(fileType)) {
            const fileReader = new FileReader();
            fileReader.onload = () => {
                const fileURL = fileReader.result;

                placeholder.setAttribute("src", fileURL);
            };

            fileReader.readAsDataURL(file);

            validate.classList.add("active");
        } else {
                alert("This is not a valid image file. Please upload a JPEG, JPG, or PNG file.");
                dropArea.classList.remove("active");
        }
    }
    </script>

</body>
</html>
