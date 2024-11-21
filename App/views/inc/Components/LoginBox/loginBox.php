<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/LoginBox/loginBox.css?v=<?php echo time(); ?>">

<div class="signInContent">
    <center><h1 class="topic">LOGIN</h1></center>

    <div class="login-section">
        
        <form action="<?php echo URLROOT ?>/GuestPages/Login" method="POST" id="loginForm">

        <label>Email</label>
        <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
        <span class="form-invalid">
            <?php echo !empty($data['email_err']) ? $data['email_err'] : ''; ?>
        </span>

        <label>Password</label>
        <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($data['password'] ?? ''); ?>">
        <span class="form-invalid">
            <?php echo !empty($data['password_err']) ? $data['password_err'] : ''; ?>
        </span>
        <div class="checkbox-group">
            <label>
                <input type="checkbox"> Remember me
            </label>
            <label>
                <input type="checkbox"> Forgot password?
            </label>
        </div>

        <center><button type="submit" class="sign-in-btn">Sign In</button></center>
        <div class="tosign">Not a member? <a href="<?php echo URLROOT ?>/GuestPages/GuestSignUp">Signup Now</a></div>

        </form>
    </div>

    
</div>
