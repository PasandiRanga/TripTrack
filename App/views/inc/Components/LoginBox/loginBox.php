<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/LoginBox/loginBox.css?v=<?php echo time(); ?>">

<div class="signInContent">
    <center><h1 style="color:#43cea2">LOGIN</h1></center>

    <div class="login-section">
        
        <form action="<?php echo URLROOT ?>/GuestPages/Login" method="POST" id="loginForm">

        <label>Email</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($headerData['email'] ?? ''); ?>" placeholder="Enter username / email">
        <span class="form-invalid">
            <?php echo !empty($headerData['email_err']) ? $headerData['email_err'] : ''; ?>
        </span>

        <label>Password</label>
        <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($headerData['password'] ?? ''); ?>" placeholder="Enter password">
        <span class="form-invalid">
            <?php echo !empty($headerData['password_err']) ? $headerData['password_err'] : ''; ?>
        </span>
        <div class="checkbox-group">
            <div class="tosign"><a href="blank"> Forgot password?</a></div>
            <label>
                <input type="checkbox"> Remember me
            </label>
        </div>

        <center><button type="submit" class="sign-in-btn">Sign In</button></center>
        <div class="tosign">Not a member? <a href="<?php echo URLROOT ?>/GuestPages/GuestSignUp">Signup Now</a></div>

        </form>
    </div>

    
</div>
