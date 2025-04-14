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
                    <span class="toggle-password" id="togglePassword"><i class="fas fa-eye-slash"></i>'</span>
                </div>
                <span class="form-invalid">
                    <?php echo !empty($headerData['password_err']) ? $headerData['password_err'] : ''; ?>
                </span>
                
                <div class="checkbox-group">
                    <div class="tosign"><a href="blank">Forgot password?</a></div>
                    <label>
                        <input type="checkbox"> Remember me
                    </label>
                </div>
                
                <center><button type="submit" class="sign-in-btn">Sign In</button></center>
                
                <div class="tosign">Not a member? <a href="<?php echo URLROOT ?>/GuestPages/GuestSignUp">Signup Now</a></div>
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
        });
    </script>
</body>
</html>