<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/LoginBox/loginBox.css?v=<?php echo time(); ?>">
   
</head>
<body>
    <div class="signInContent">
        <center><h1 style="color:#43cea2">LOGIN</h1></center>
        <div class="login-section">
            <form class="form" action="<?php echo URLROOT ?>/GuestPages/Login" method="POST" id="loginForm">
                
                <label>Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($headerData['email'] ?? ''); ?>" placeholder="Enter email">
                <span class="form-invalid">
                    <?php echo !empty($headerData['email_err']) ? $headerData['email_err'] : ''; ?>
                </span>
                
                <label>Password</label>
                <div class="password-container">
                    <input type="password" name="password" id="password"
                           value="<?php echo htmlspecialchars($headerData['password'] ?? ''); ?>"
                           placeholder="Enter password">
                    <span class="toggle-password" id="togglePassword"><i class="fas fa-eye-slash"></i></span>
                </div>

                <span class="form-invalid">
                    <?php echo !empty($headerData['password_err']) ? $headerData['password_err'] : ''; ?>
                </span>
                
                <div class="checkbox-group">
                    <div class="tosign"><a href="javascript:void(0)" id="forgotPasswordLink">Forgot password?</a></div>
                </div>
                
                <center><button type="submit" class="sign-in-btn">Sign In</button></center>
                
                <div class="tosign">Not a member? <a href="<?php echo URLROOT ?>/GuestPages/GuestSignUp">Signup Now</a></div>
            </form>
        </div>
    </div>
    
    <div class="popup-overlay" id="forgotPasswordPopup">
        <div class="popup-content">
            <span class="close-popup" id="closePopup">&times;</span>
            <h2 style="color:#43cea2; text-align:center;">Forgot Password</h2>
            
            <div class="popup-message" id="popupMessage"></div>
            
            <form id="forgotPasswordForm">
                <label>Email</label>
                <input type="email" name="email" id="resetEmail" placeholder="Enter your registered email">
                <span class="form-invalid" id="resetEmailError"></span>
                
                <div class="spinner" id="loadingSpinner"></div>
                
                <center><button type="submit" class="sign-in-btn" id="sendResetLinkBtn">Submit</button></center>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            
            togglePassword.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.setAttribute('type', 'text');
                    passwordInput.classList.add('password-visible');
                    togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
                } else {
                    passwordInput.setAttribute('type', 'password');
                    passwordInput.classList.remove('password-visible');
                    togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>';
                }
            });
            
            const forgotPasswordLink = document.getElementById('forgotPasswordLink');
            const forgotPasswordPopup = document.getElementById('forgotPasswordPopup');
            const closePopup = document.getElementById('closePopup');
            const forgotPasswordForm = document.getElementById('forgotPasswordForm');
            const popupMessage = document.getElementById('popupMessage');
            const resetEmailError = document.getElementById('resetEmailError');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            forgotPasswordLink.addEventListener('click', function() {
                forgotPasswordPopup.style.display = 'flex';
                document.getElementById('resetEmail').value = '';
                resetEmailError.textContent = '';
                popupMessage.style.display = 'none';
            });
            
            closePopup.addEventListener('click', function() {
                forgotPasswordPopup.style.display = 'none';
            });
            
            forgotPasswordPopup.addEventListener('click', function(e) {
                if (e.target === forgotPasswordPopup) {
                    forgotPasswordPopup.style.display = 'none';
                }
            });
            
            forgotPasswordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('resetEmail').value.trim();
                resetEmailError.textContent = '';
                popupMessage.style.display = 'none';
                
                if (!email) {
                    resetEmailError.textContent = 'Please enter your email';
                    return;
                }
                
                loadingSpinner.style.display = 'block';
                document.getElementById('sendResetLinkBtn').disabled = true;
                
                fetch('<?php echo URLROOT ?>/GuestPages/processForgotPassword', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'email=' + encodeURIComponent(email)
                })
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.style.display = 'none';
                    document.getElementById('sendResetLinkBtn').disabled = false;
                    
                    if (data.success) {
                        popupMessage.className = 'popup-message success';
                        popupMessage.textContent = data.message;
                        popupMessage.style.display = 'block';
                        
                        document.getElementById('resetEmail').value = '';
                        
                        setTimeout(() => {
                            forgotPasswordPopup.style.display = 'none';
                        }, 3000);
                    } else {
                        if (data.email_err) {
                            resetEmailError.textContent = data.email_err;
                        } else if (data.message) {
                            popupMessage.className = 'popup-message error';
                            popupMessage.textContent = data.message;
                            popupMessage.style.display = 'block';
                        }
                    }
                })
                .catch(error => {
                    loadingSpinner.style.display = 'none';
                    document.getElementById('sendResetLinkBtn').disabled = false;
                    
                    popupMessage.className = 'popup-message error';
                    popupMessage.textContent = 'Something went wrong. Please try again.';
                    popupMessage.style.display = 'block';
                    console.error('Error:', error);
                });
            });
        });
    </script>
</body>
</html>