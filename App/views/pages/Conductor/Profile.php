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
    $userRole = $_SESSION['userRole'] ?? 'Conductor';
    ?>


    <?php
    $profile = $data;
    error_log("profile details at view: " . print_r($data, true));

    $data = [
        'currentController' => 'ConductorPages',
        'currentMethod' => 'profile',
        'userRole' => $userRole
    ];
    ?>

    <script>
        var profileData = <?php echo json_encode($profile); ?>;
        console.log("Profile Data:", profileData);
    </script>

    <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/ConductorPages/newhome'">Back</button>

    <div class="page-header">
        <h1>Employee Profile</h1>
    </div>

    <div class="profile-container">
        <!-- Left Side: User Info -->
        <div class="profile-left">
            <div class="profile-pic">
                <img src="<?php echo URLROOT;?>/images/profileImages/<?php echo $profile['profile_pic'];?>" alt="Profile Picture" class="profile-pic">
            </div>
            <button class="edit-image-button" onclick="showImageUpdateBox()"><i class="fa-solid fa-pencil fa-sm"></i> Edit</button>        
            <h2><?php echo $profile['name']; ?></h2>

            <button class="logout-button" onclick="openLogoutModal()"><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>  LogOut</button>
        </div>

        <div class="imageUpdateBox hidden" id="imageUpdateBox">
            <div class="imageUpdateBoxContent">
            <form action="<?php echo URLROOT ?>/ConductorPages/updateProfileImage" method="POST" enctype="multipart/form-data">
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

            <button class="edit-button" onclick="showUpdateBox()">Edit</button>

        </div>
    </div>

    <div class="modal-overlay" id="logoutModal">
        <div class="modal-content">
            <h1>Are you sure you want to logout?</h1>
            <h4>You won't be able to revert this !</h4>
            <p>
                <button id="yes" onclick="proceedLogout()">Yes</button>
                <button id="no" onclick="cancelLogout()">No</button>
            </p>
            <div class="close-btn" onclick="cancelLogout()">×</div>
        </div>
    </div>

    <?php require APPROOT.'/views/pages/Conductor/Conductor_Inc/profile/profileForm.php'; ?>

    <script>
        function showUpdateBox() {
            document.getElementById('updateBox').classList.remove('hidden');
        }

        function closeUpdateBox() {
            document.getElementById('updateBox').classList.add('hidden');
        }

        //logout
        function openLogoutModal() {
            document.getElementById("logoutModal").classList.add("open-modal");
        }

        function cancelLogout() {
            document.getElementById("logoutModal").classList.remove("open-modal");
        }

        function proceedLogout() {
            window.location.href = "<?php echo URLROOT; ?>/GuestPages/logout";
        }

        function showImageUpdateBox() {
            document.getElementById("imageUpdateBox").classList.remove("hidden");
        }

        function closeImageUpdateBox() {
            document.getElementById("imageUpdateBox").classList.add("hidden");
        }

        function confirmImage(){
            window.location.href = '<?php echo URLROOT; ?>/ConductorPages/updateProfileImage'; // Ensure correct update URL
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

        dropArea.addEventListener("dragover", (event) => {
            event.preventDefault();
            dropArea.classList.add("active");
            dropText.textContent = "Release to Upload the Image";
        });

        dropArea.addEventListener("dragleave", () => {
            dropArea.classList.remove("active");
            dropText.textContent = "Drag & Drop to Upload Image";
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
