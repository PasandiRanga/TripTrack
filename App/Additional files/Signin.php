<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>SignIn <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/Signin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Login Section -->
        <div class="login-section">
            <div class="logo">
            <img src="<?php echo URLROOT; ?>/public/images/logo2.png" alt="User Profile Picture"> 
            </div>
            <h1>Login to Your Account</h1>
            <p>Login using social networks</p>
            <div class="social-login">
                <button class="social-btn facebook">f</button>
                <button class="social-btn google">G+</button>
                <button class="social-btn linkedin">in</button>
            </div>
            <div class="separator">OR</div>
            <form>
                <input type="email" placeholder="Email" required>
                <input type="password" placeholder="Password" required>
                <button type="submit" class="sign-in-btn">Sign In</button>
            </form>
        </div>

        <!-- Signup Section -->
        <div class="signup-section">
            <h2>New Here?</h2>
            <p>"Sign up to find and book buses with ease and enjoy a hassle-free journey!"</p>
            <button class="sign-up-btn">Sign Up</button>
        </div>
    </div>

   <script>
   function validateForm() {
       var email = document.getElementById('email').value;
       var password = document.getElementById('password').value;
       if (email === "" || password === "") {
           alert("Both fields are required.");
           return false;
       }
       else {
        // Redirect to home.html after validation
        window.location.href = "home.html";
        return false; // Prevent form submission to allow for redirection
        }
   }
   </script>
    
</body>
</html>
