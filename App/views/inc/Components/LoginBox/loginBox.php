<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/LoginBox/loginBox.css?v=<?php echo time(); ?>">

<div class="signInContent">
    <div class="login-section">
        <img src="<?php echo URLROOT; ?>/public/images/logo2.png" alt="Logo" class="logo">
        <h2>Login to Your Account</h2>
        <p>Login using social networks</p>
        <div class="social-icons">
            <button class="social-btn fb">f</button>
            <button class="social-btn google">G+</button>
            <button class="social-btn linkedin">in</button>
        </div>
        <form id="loginForm" onsubmit="validateForm(event)">
            <label>Email</label>
            <input id="email" type="email" placeholder="Email" required>
            <label>Password</label>
            <input id="password" type="password" placeholder="Password"  required>
            <button type="submit" class="sign-in-btn">Sign In</button>
        </form>
    </div>

    <div class="signup-section">
        <h3>New Here?</h3>
        <p>"Sign up to find and book buses with ease and enjoy a hassle-free journey!"</p>
        <button class="sign-up-btn" onclick="fillForm()">Sign Up</button>
    </div>
</div>
