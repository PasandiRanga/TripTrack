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
            <a href="#back" class="back-button" onClick="window.location.href='/GuestPages/Home'">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
      </div>

        <div class="right-column">
                
                <form action="<?php echo URLROOT ?>/GuestPages/GuestSignUp" method="POST" enctype="multipart/form-data">

                    <center><h1 style="color:black">Sign Up</h1></center>
                    <!----Full Name---->
                    <div class="form-input-title">Full Name</div>
                    <input type="text" name="name" id="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>

                    <!----Contact Number---->
                    <div class="form-input-title">Contact Number</div>
                    <input type="text" name="number" id="number" value="<?php echo isset($data['number']) ? $data['number'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['number_err']) ? $data['number_err'] : ''; ?></span>

                    <!----NIC---->
                    <div class="form-input-title">NIC</div>
                    <input type="text" name="nic" id="nic" value="<?php echo isset($data['nic']) ? $data['nic'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['nic_err']) ? $data['nic_err'] : ''; ?></span>

                    <!----Address---->
                    <div class="form-input-title">Address</div>
                    <input type="text" name="address" id="address" value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['address_err']) ? $data['address_err'] : ''; ?></span>

                    <!----Email---->
                    <div class="form-input-title">Email</div>
                    <input type="text" name="email" id="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>

                    <!----Password---->
                    <div class="form-input-title">Password</div>
                    <input type="text" name="password" id="password" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>

                    <!----Confirm Password---->
                    <div class="form-input-title">Confirm Password</div>
                    <input type="text" name="confirm" id="confirm" value="<?php echo isset($data['confirm']) ? $data['confirm'] : ''; ?>">
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
                    <center><input class="button" type="submit" value="Register"></center>                 
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
                    </script>
                </form>
            

        </div>
    </div>
    </div>
</body>
</html>