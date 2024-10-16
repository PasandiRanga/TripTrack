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
      <div class="left">
        <h1 class="caption">TRIP TRACK</h1>
        <h1 class="tagline">Travel <span>WITH US  </span><i class="fa-solid fa-map-pin" style="color: #e74757;"></i></h1>
        <img src="./../../images/s1.png" alt="Travel with us" class="bus">
      </div>

      <div class="signin-box">
          <h2>Sign in</h2>
          <form id="signin-form" onsubmit="return validateForm()">
              <label for="email">Email :</label>
              <input type="email" id="email" name="email" required>
              
              <label for="password">Password :</label>
              <input type="password" id="password" name="password" required>
              
              <div class="signup-link">
                  <span>New to TripTrack? </span><a href="#">Sign up</a>
              </div>
              
              <button type="submit">Sign In</button>
              
              <div class="google-signin">
                  <button type="button">
                      <img src="./../../images/google-logo.png" alt="Google logo">
                      Continue with Google
                  </button>
              </div>
          </form>
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
