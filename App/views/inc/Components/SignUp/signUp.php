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
                    <div class="form-input-title">Full Name <span class="required">*</span></div>
                    <input type="text" name="name" id="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>

                    <div class="form-input-title">Contact Number <span class="required">*</span></div>
                    <input type="text" name="number" id="number" value="<?php echo isset($data['number']) ? $data['number'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['number_err']) ? $data['number_err'] : ''; ?></span>

                    <div class="form-input-title">NIC <span class="required">*</span></div>
                    <input type="text" name="nic" id="nic" value="<?php echo isset($data['nic']) ? $data['nic'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['nic_err']) ? $data['nic_err'] : ''; ?></span>

                    <div class="form-input-title">Address <span class="required">*</span></div>
                    <input type="text" name="address" id="address" value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['address_err']) ? $data['address_err'] : ''; ?></span>

                    <div class="form-input-title">Email <span class="required">*</span></div>
                    <input type="text" name="email" id="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
                    <span class="form-invalid"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>

                    <div class="form-input-title">Password <span class="required">*</span></div>
                    <div class="password-container">
                        <input type="password" name="password" id="password" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>">
                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                    <span class="form-invalid"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>

                    <div class="form-input-title">Confirm Password <span class="required">*</span></div>
                    <div class="password-container">
                        <input type="password" name="confirm" id="confirm" value="<?php echo isset($data['confirm']) ? $data['confirm'] : ''; ?>">
                        <i class="fas fa-eye" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                    </div>
                    <span class="form-invalid"><?php echo isset($data['confirm_err']) ? $data['confirm_err'] : ''; ?></span>

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

                    <div class="form-agreement">
                        <p>
                            <input type="checkbox" id="terms" name="terms">
                            I agree to the Terms of Service and Privacy Policy.
                        </p>
                    </div>

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
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const otpErrorElement = document.querySelector('[class="form-invalid"]:not(:empty)');
    if (otpErrorElement && otpErrorElement.textContent.includes('OTP')) {
        document.getElementById('confirmBox').classList.remove('hidden');
    }

    const form = document.querySelector('form');
    const nameInput = document.getElementById('name');
    const numberInput = document.getElementById('number');
    const nicInput = document.getElementById('nic');
    const addressInput = document.getElementById('address');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm');
    const termsCheckbox = document.getElementById('terms');
    const registerButton = document.getElementById('Register');

    document.querySelectorAll('.form-invalid').forEach(error => {
        if (!error.textContent.trim()) {
            error.style.display = 'none';
        }
    });

    registerButton.disabled = true;
    registerButton.style.opacity = '0.5';
    registerButton.style.cursor = 'not-allowed';

    const createErrorElement = (inputId) => {
        const errorId = `${inputId}_err`;
        let errorElement = document.getElementById(errorId);
        
        if (!errorElement) {
            errorElement = document.createElement('span');
            errorElement.id = errorId;
            errorElement.className = 'form-invalid';
            const inputElement = document.getElementById(inputId);
            inputElement.insertAdjacentElement('afterend', errorElement);
        }
        
        return errorElement;
    };

    const validateName = () => {
        const namePattern = /^[A-Za-z\s]+$/;
        const errorElement = createErrorElement('name');
        
        if (!nameInput.value.trim()) {
            errorElement.textContent = 'Name is required';
            errorElement.style.display = 'block';
            return false;
        } else if (!namePattern.test(nameInput.value)) {
            errorElement.textContent = 'Name should only contain alphabetic characters';
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            return true;
        }
    };

    const validateNumber = () => {
        const numberPattern = /^[0-9]{10}$/;
        const errorElement = createErrorElement('number');
        
        if (!numberInput.value.trim()) {
            errorElement.textContent = 'Contact number is required';
            errorElement.style.display = 'block';
            return false;
        } else if (!numberPattern.test(numberInput.value)) {
            errorElement.textContent = 'Contact number should be exactly 10 digits';
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            return true;
        }
    };

    const validateNIC = () => {
        const nicPattern = /^([0-9]{9}[vV]|[0-9]{12})$/;
        const errorElement = createErrorElement('nic');
        
        if (!nicInput.value.trim()) {
            errorElement.textContent = 'NIC is required';
            errorElement.style.display = 'block';
            return false;
        } else if (!nicPattern.test(nicInput.value)) {
            errorElement.textContent = 'NIC should be 9 digits followed by v or 12 digits';
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            return true;
        }
    };

    const validateAddress = () => {
        const errorElement = createErrorElement('address');
        
        if (!addressInput.value.trim()) {
            errorElement.textContent = 'Address is required';
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            return true;
        }
    };

    const validateEmail = () => {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const errorElement = createErrorElement('email');
        
        if (!emailInput.value.trim()) {
            errorElement.textContent = 'Email is required';
            errorElement.style.display = 'block';
            return false;
        } else if (!emailPattern.test(emailInput.value)) {
            errorElement.textContent = 'Please enter a valid email address';
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            return true;
        }
    };

    const validatePassword = () => {
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        const errorElement = createErrorElement('password');
        
        if (!passwordInput.value) {
            errorElement.textContent = 'Password is required';
            errorElement.style.display = 'block';
            return false;
        } else if (!passwordPattern.test(passwordInput.value)) {
            errorElement.textContent = 'Password must be at least 8 characters with 1 uppercase, 1 lowercase, 1 number, and 1 special character';
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            return true;
        }
    };

    const validateConfirmPassword = () => {
        const errorElement = createErrorElement('confirm');
        
        if (!confirmInput.value) {
            errorElement.textContent = 'Confirm password is required';
            errorElement.style.display = 'block';
            return false;
        } else if (confirmInput.value !== passwordInput.value) {
            errorElement.textContent = 'Passwords do not match';
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            return true;
        }
    };

    const validateTerms = () => {
        const errorElement = createErrorElement('terms');
        
        if (!termsCheckbox.checked) {
            errorElement.textContent = 'You must agree to the Terms of Service';
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            return true;
        }
    };

    const validateForm = () => {
        const isNameValid = validateName();
        const isNumberValid = validateNumber();
        const isNICValid = validateNIC();
        const isAddressValid = validateAddress();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isConfirmValid = validateConfirmPassword();
        const isTermsChecked = validateTerms();

        if (isNameValid && isNumberValid && isNICValid && isAddressValid && 
            isEmailValid && isPasswordValid && isConfirmValid && isTermsChecked) {
            registerButton.disabled = false;
            registerButton.style.opacity = '1';
            registerButton.style.cursor = 'pointer';
        } else {
            registerButton.disabled = true;
            registerButton.style.opacity = '0.5';
            registerButton.style.cursor = 'not-allowed';
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Check if there's an OTP error and show the confirmation box
        <?php if(isset($data['otp_err']) && !empty($data['otp_err'])): ?>
            document.getElementById('confirmBox').classList.remove('hidden');
        <?php endif; ?>
        
        // This will ensure the OTP box stays visible when there's an error
        const otpErrorElement = document.querySelector('[name="otp"] + .form-invalid');
        if (otpErrorElement && otpErrorElement.textContent.trim() !== '') {
            document.getElementById('confirmBox').classList.remove('hidden');
        }
    });

    nameInput.addEventListener('input', function() {
        validateName();
    });
    
    numberInput.addEventListener('input', function() {
        validateNumber();
    });
    
    nicInput.addEventListener('input', function() {
        validateNIC();
    });
    
    addressInput.addEventListener('input', function() {
        validateAddress();
    });
    
    emailInput.addEventListener('input', function() {
        validateEmail();
    });
    
    passwordInput.addEventListener('input', function() {
        validatePassword();
    });
    
    confirmInput.addEventListener('input', function() {
        validateConfirmPassword();
    });
    
    termsCheckbox.addEventListener('change', function() {
        validateTerms();
        validateForm();
    });

    form.addEventListener('submit', function(event) {
        validateForm();
        if (registerButton.disabled) {
            event.preventDefault();
        }
    });
});

    </script>

</body>
</html>