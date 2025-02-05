<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Column Layout</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/SignUp/signUp.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

</head>
<body>
    <div class="signupbody">
    <div class="container">
        <div class="left-column">
            <h2 class="zoom-in">Welcome to Our Platform!</h2>
            <p class="zoom-in">We're thrilled to have you here. Explore amazing features, connect with others, and achieve your goals effortlessly.</p>
            <a href="#back" class="back-button" onClick="window.location.href='<?php echo URLROOT; ?>/GuestPages/home'">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
      </div>

        <div class="right-column">
                
                <form action="<?php echo URLROOT ?>/GuestPages/GuestSignUp" method="POST" enctype="multipart/form-data">

                    <center><h1 style="color:#43cea2">SIGN UP</h1></center>
                    <!----Full Name---->
                    <div class="form-input-title">Full Name <span class="required">*</span></div>
                    <input type="text" name="name" id="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>

                    <!----Contact Number---->
                    <div class="form-input-title">Contact Number <span class="required">*</span></div>
                    <input type="text" name="number" id="number" value="<?php echo isset($data['number']) ? $data['number'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['number_err']) ? $data['number_err'] : ''; ?></span>

                    <!----NIC---->
                    <div class="form-input-title">NIC <span class="required">*</span></div>
                    <input type="text" name="nic" id="nic" value="<?php echo isset($data['nic']) ? $data['nic'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['nic_err']) ? $data['nic_err'] : ''; ?></span>

                    <!----Address---->
                    <div class="form-input-title">Address <span class="required">*</span></div>
                    <input type="text" name="address" id="address" value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['address_err']) ? $data['address_err'] : ''; ?></span>

                    <!----Email---->
                    <div class="form-input-title">Email <span class="required">*</span></div>
                    <input type="text" name="email" id="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>

                    <!-- <button class="otp" id="sendOTP" type="button">Send OTP</button> -->

                    <!-- Password Field -->
                    <div class="form-input-title">Password <span class="required">*</span></div>
                    <div class="password-container">
                        <input type="password" name="password" id="password" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>">
                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                    <span class="form-invalid"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>

                    <!-- Confirm Password Field -->
                    <div class="form-input-title">Confirm Password <span class="required">*</span></div>
                    <div class="password-container">
                        <input type="password" name="confirm" id="confirm" value="<?php echo isset($data['confirm']) ? $data['confirm'] : ''; ?>">
                        <i class="fas fa-eye" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                    </div>
                    <span class="form-invalid"><?php echo isset($data['confirm_err']) ? $data['confirm_err'] : ''; ?></span>

                    <!-- Profile Image Upload Section -->
                    <div class="form-drag-area">
                        <div class="icon">
                            <img src="<?php echo URLROOT; ?>/public/images/placeholder.jpg" alt="placeholder" width="90px" height="90px" id="placeholder">
                        </div>
                        <div class="right_content">
                            <div class="description">Drag & Drop to Upload Image</div>
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


                    <!-- Checkbox Section -->
                    <div class="form-agreement">
                        <p>
                            <input type="checkbox" id="terms" name="terms">
                            I agree to the Terms of Service and Privacy Policy.
                        </p>
                    </div>

                    <!-- Register Button Section -->
                    <div class="form-register">
                    <center><input id="Register" class="button" type="submit" value="Register"  onclick="showConfirmBox()"></center>                 
                    </div>

                    <div class="confirmBox hidden" id="confirmBox">
                        <div class="confirmBoxContent">
                            <h2>Verify Your Email</h2>
                            <p>A 6-digit OTP has been sent to your email. Please enter it below to verify your account.</p>
                            
                            <div class="otpverify">
                                <input type="text" name="otp" id="otp" maxlength="6" placeholder="Enter OTP" required>
                                <span class="form-invalid"><?php echo isset($data['otp_err']) ? $data['otp_err'] : ''; ?></span>
                            </div>
                            <button type="button" class="verify-button" id="verify">Verify</button>
                            
                            <div class="resend-otp">
                                <p>Didn't receive an OTP? <a href="">Resend OTP</a></p>
                            </div>

                            <div class="close-btn" onclick="closeConfirmBox()">×</div>
                        </div>
                    </div>

                    <script>
                    // Profile image drag-and-drop
                    const dropArea = document.querySelector(".form-drag-area");
                    const dropText = document.querySelector(".description");
                    const browseButton = document.querySelector(".form_upload");
                    const inputPath = document.querySelector("#profile_image");
                    const placeholder = document.querySelector("#placeholder");
                    const validate = document.querySelector(".profile_image_validation");
                    let file;

                    // Browse option and upload functionality
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

                        // Adding the file to the input element programmatically
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        inputPath.files = dataTransfer.files;

                        showImage();
                        dropArea.classList.remove("active");
                    });

                    function showImage() {
                        const fileType = file.type;

                        // Valid image extensions
                        const validExtensions = ["image/jpeg", "image/jpg", "image/png"];

                        if (validExtensions.includes(fileType)) {
                            const fileReader = new FileReader();

                            fileReader.onload = () => {
                                const fileURL = fileReader.result;

                                // Set image preview
                                placeholder.setAttribute("src", fileURL);
                            };

                            fileReader.readAsDataURL(file);

                            // Show validation tick
                            validate.classList.add("active");
                        } else {
                            alert("This is not a valid image file. Please upload a JPEG, JPG, or PNG file.");
                            dropArea.classList.remove("active");
                        }
                    }

                    // Show password
                    const togglePassword = document.querySelector("#togglePassword");
                    const passwordField = document.querySelector("#password");
                    const toggleConfirmPassword = document.querySelector("#toggleConfirmPassword");
                    const confirmPasswordField = document.querySelector("#confirm");

                    togglePassword.addEventListener("click", () => {
                        // Toggle password visibility
                        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                        passwordField.setAttribute("type", type);

                        // Toggle the icon
                        togglePassword.classList.toggle("fa-eye-slash");
                    });

                    toggleConfirmPassword.addEventListener("click", () => {
                        // Toggle confirm password visibility
                        const type = confirmPasswordField.getAttribute("type") === "password" ? "text" : "password";
                        confirmPasswordField.setAttribute("type", type);

                        // Toggle the icon
                        toggleConfirmPassword.classList.toggle("fa-eye-slash");
                    });

                    function showConfirmBox() {
                        document.getElementById("confirmBox").classList.remove("hidden");
                    }

                    function closeConfirmBox() {
                        document.getElementById("confirmBox").classList.add("hidden");
                    }

                    let generatedOTP = "";

                    // Function to generate a 6-digit OTP
                    function generateOTP() {
                        return Math.floor(100000 + Math.random() * 900000);
                    }

                    // Send OTP Button Click (Generate OTP)
                    document.getElementById("Register").addEventListener("click", function () {
                        generatedOTP = generateOTP();
                        alert("Your OTP is: " + generatedOTP); // Display OTP alert
                    });

                    // Verify OTP Button Click
                    document.getElementById("verify").addEventListener("click", function (event) {
                        event.preventDefault(); // Prevent form submission
                        let enteredOTP = document.getElementById("otp").value;

                        if (enteredOTP === generatedOTP.toString()) {
                            alert("OTP Verified Successfully!");
                            // You can proceed with form submission or other actions here
                            document.querySelector("form").submit(); // Submit the form
                        } else {
                            alert("Incorrect OTP. Please try again.");
                        }
                    });
                </script>
            </form>
        </div>
    </div>
    </div>
</body>
</html>