<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/LoginBox/resetPassword.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="signInContent">
        <div class="login-wrapper">
            <div class="logo-section">
                <img src="<?php echo URLROOT; ?>/public/images/logo.png" alt="Company Logo">
            </div>

            <div class="login-section">
                <center><h1>RESET PASSWORD</h1></center>
                <form class="form" action="<?php echo URLROOT ?>/GuestPages/processResetPassword" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($data['token']); ?>">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" readonly>

                    <label>New Password</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password"
                            value="<?php echo htmlspecialchars($headerData['password'] ?? ''); ?>"
                            placeholder="Enter new password">
                        <span class="toggle-password" id="togglePassword"><i class="fas fa-eye-slash"></i></span>
                    </div>
                    <span class="form-invalid">
                        <?php echo !empty($headerData['password_err']) ? $headerData['password_err'] : ''; ?>
                    </span>

                    <label>Confirm Password</label>
                    <div class="password-container">
                        <input type="password" name="confirm_password" id="confirm_password"
                            value="<?php echo htmlspecialchars($headerData['confirm_password'] ?? ''); ?>"
                            placeholder="Confirm new password">
                        <span class="toggle-password" id="toggleConfirmPassword"><i class="fas fa-eye-slash"></i></span>
                    </div>
                    <span class="form-invalid">
                        <?php echo !empty($headerData['confirm_password_err']) ? $headerData['confirm_password_err'] : ''; ?>
                    </span>

                    <center><button type="submit" class="sign-in-btn">Reset Password</button></center>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const form = document.querySelector('.form');

            togglePassword.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                togglePassword.innerHTML = isPassword ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });

            toggleConfirmPassword.addEventListener('click', () => {
                const isPassword = confirmPasswordInput.type === 'password';
                confirmPasswordInput.type = isPassword ? 'text' : 'password';
                toggleConfirmPassword.innerHTML = isPassword ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });

            form.addEventListener('submit', function (e) {
                const password = passwordInput.value.trim();
                const confirmPassword = confirmPasswordInput.value.trim();

                document.querySelectorAll('.form-invalid').forEach(el => el.textContent = '');

                let isValid = true;

                if (!password) {
                    passwordInput.parentElement.nextElementSibling.textContent = "Password is required";
                    isValid = false;
                } else if (password.length < 8) {
                    passwordInput.parentElement.nextElementSibling.textContent = "Password must be at least 8 characters long";
                    isValid = false;
                } else if (!/[A-Z]/.test(password)) {
                    passwordInput.parentElement.nextElementSibling.textContent = "Must include at least one uppercase letter";
                    isValid = false;
                } else if (!/[a-z]/.test(password)) {
                    passwordInput.parentElement.nextElementSibling.textContent = "Must include at least one lowercase letter";
                    isValid = false;
                } else if (!/\d/.test(password)) {
                    passwordInput.parentElement.nextElementSibling.textContent = "Must include at least one number";
                    isValid = false;
                } else if (!/[\W_]/.test(password)) {
                    passwordInput.parentElement.nextElementSibling.textContent = "Must include at least one special character";
                    isValid = false;
                }

                if (!confirmPassword) {
                    confirmPasswordInput.parentElement.nextElementSibling.textContent = "Please confirm your password";
                    isValid = false;
                } else if (password !== confirmPassword) {
                    confirmPasswordInput.parentElement.nextElementSibling.textContent = "Passwords do not match";
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault(); 
                }
            });
        });
    </script>
</body>
</html>
