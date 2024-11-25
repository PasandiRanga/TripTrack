<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <!-- <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/test.css?v=<?php echo time(); ?>"> -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/contactUs.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
</head>
<body>

    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'RegisteredUser';
    ?>

    <?php
    $data = [
        'currentController' => 'GuestPages', // Adjust this based on your controller
        'currentMethod' => 'contact', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <div class="hero-container">
        <br/>
        <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
      

        <div class="Contactcontainer">
      <!--<span class="big-circle"></span>-->
      <div class="form">
        <div class="contact-info">
          <h3 class="title">Let's get in touch</h3>
          <p class="description">
          "Whether it’s feedback, inquiries, or support, we value every passenger’s journey.
           Contact us today, and let TripTrack make your travel easier and more enjoyable!"
          </p>

          <div class="info">
            <div class="information">
              <img src="<?php echo URLROOT; ?>/Public/images/location.png" class="icon" alt="" />
              <p>123 Main Street, Suite 400
              City, State, ZIP Code</p>
            </div>
            <div class="information">
              <img src="<?php echo URLROOT; ?>/Public/images/email.png" class="icon" alt="" />
              <p>info@example.com</p>
            </div>
            <div class="information">
              <img src="<?php echo URLROOT; ?>/Public/images/phone.png" class="icon" alt="" />
              <p>Phone: +1 (123) 456-7890</p>
            </div>
          </div>

          <div class="social-media">
            <p>Connect with us :</p>
            <div class="social-icons">
              <a href="#">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="contact-form">

          <form action="index.html" autocomplete="off">
            <h3 class="title">Contact us</h3>
            <div class="input-container">
              <input type="text" name="name" class="input" />
              <label for="">Username</label>
              <span>Username</span>
            </div>
            <div class="input-container">
              <input type="mail" name="email" class="input" />
              <label for="">Email</label>
              <span>Email</span>
            </div>
            <div class="input-container">
              <input type="tel" name="phone" class="input" />
              <label for="">Phone</label>
              <span>Phone</span>
            </div>
            <div class="input-container textarea">
              <textarea name="message" class="input"></textarea>
              <label for="">Message</label>
              <span>Message</span>
            </div>
            <input type="submit" value="Send" class="btn" />
          </form>
        </div>
      </div>
    </div>
      
       <div class="footerContainer">
<?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>
</div>
 
    </div>
     
    <script>
    const inputs = document.querySelectorAll(".input");

    function focusFunc() {
    let parent = this.parentNode;
    parent.classList.add("focus");
    }

    function blurFunc() {
    let parent = this.parentNode;
    if (this.value == "") {
        parent.classList.remove("focus");
    }
    }

    inputs.forEach((input) => {
    input.addEventListener("focus", focusFunc);
    input.addEventListener("blur", blurFunc);
    });

    
</script>

   

    
</body>
</html>
