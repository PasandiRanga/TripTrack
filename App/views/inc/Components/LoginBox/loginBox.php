<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/LoginBox/loginBox.css?v=<?php echo time(); ?>">
    <style>
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 0.5px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            color: #8c8c8c; 
            font-size: 20px;
            z-index: 10;
        } 
        /* Maintain consistent styling for both input types */
        /* input[type="password"] , input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #43cea2;
            border-radius: 5px;
            font-size: 1rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            outline: none;
            transition: all 0.3s ease;
        } */
        
    </style>
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
                    <span class="toggle-password" id="togglePassword">👁</span>
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
                } else {
                    passwordInput.setAttribute('type', 'password');
                    passwordInput.classList.remove('password-visible');
                }
            });
        });
    </script>
</body>
</html>