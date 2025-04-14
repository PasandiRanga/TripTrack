<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/LoginBox/loginBox.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="signInContent">
        <center><h1 style="color:#43cea2">FORGOT PASSWORD</h1></center>
        <div class="login-section">
            <form class="form" action="<?php echo URLROOT ?>/GuestPages/processForgotPassword" method="POST">
                <label>Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($headerData['email'] ?? ''); ?>" placeholder="Enter your registered email">
                <span class="form-invalid">
                    <?php echo !empty($headerData['email_err']) ? $headerData['email_err'] : ''; ?>
                </span>
                
                <center><button type="submit" class="sign-in-btn">Send Reset Link</button></center>
                
                <div class="tosign"><a href="<?php echo URLROOT ?>/GuestPages/Login">Back to Login</a></div>
            </form>
        </div>
    </div>
</body>
</html>