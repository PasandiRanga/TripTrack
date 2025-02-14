<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Column Layout</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/SignUp/signUp.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
        console.log('URLROOT is:', URLROOT); 
    </script>
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
                    <center><input id="Register" class="button" type="submit" value="Register"  ></center>                 
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
            </form>
        </div>
    </div>
    </div>
    <script src="<?php echo URLROOT; ?>/public/js/signup.js"></script>

</body>
</html>