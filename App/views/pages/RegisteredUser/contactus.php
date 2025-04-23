<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['RegisteredUser']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/contactUs.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
</head>
<body>

    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredtUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'RegisteredUser';
    ?>

    <?php

    $notifications =  $data['notifications'] ?? [];

    $postdata = $data;
    
        $data['currentController'] = 'RegisteredPages';
        $data['currentMethod'] = 'allNotifications';
        $data['userRole'] = $userRole;
    ?> 

    <?php
        // Display success message if it exists
        if (isset($_SESSION['success_message'])):
    ?>
        <script>
            alert("<?php echo $_SESSION['success_message']; ?>");
        </script>
    <?php
        // Clear the success message after displaying it
        unset($_SESSION['success_message']);
        endif;
    ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <div class="hero-container">
        <br/>
        <div class="header-container">
            <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
        </div>
      

        <div class="Contactcontainer">
      <!--<span class="big-circle"></span>-->
      <div class="form-form">
        <div class="contact-info">
          <h3 class="title">Let's get in touch</h3>
          <p class="description">
          "Whether it’s feedback, inquiries, or support, we value every passenger’s journey.
           Contact us today, and let TripTrack make your travel easier and more enjoyable!"
          </p>

          <div class="info">
            <div class="information">
              <i class="fas fa-map-marker-alt icon-custom"></i>
              <p>123 Main Street, Suite 400
              City, State, ZIP Code</p>
            </div>
            <div class="information">
              <i class="fas fa-envelope icon-custom"></i>
              <p>info@example.com</p>
            </div>
            <div class="information">
              <i class="fas fa-phone-alt icon-custom"></i>
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

          <form action="<?php echo URLROOT; ?>/RegisteredPages/submitRequest" method="POST" enctype="multipart/form-data">
            <h3 class="title">Contact us</h3>
            <p class="description">We’d love to hear from you. Please fill out this form and we’ll get in touch shortly.</>

            <div class="input-container">
              <input type="text" name="name" id="name" class="name" value="<?php echo isset($postdata['name']) ? $postdata['name'] : ''; ?>" />
              <label for="">Name</label>
              <span>Name</span>
              <p class="invalid"><?php echo isset($postdata['name_err']) ? $postdata['name_err'] : ''; ?></p>
            </div>
            <div class="input-container">
              <input type="mail" name="email" id="email" class="input" value="<?php echo isset($postdata['email']) ? $postdata['email'] : ''; ?>" />
              <label for="">Email</label>
              <span>Email</span>
              <p class="invalid"><?php echo isset($postdata['email_err']) ? $postdata['email_err'] : ''; ?></p>

            </div>
            <div class="input-container">
              <input type="tel" name="phone" class="input" value="<?php echo isset($postdata['contactNo_err']) ? $postdata['contactNo_err'] : ''; ?>"  />
              <label for="">Phone</label>
              <span>Phone</span>
              <p class="invalid"><?php echo isset($postdata['contactNo_err']) ? $postdata['contactNo_err'] : ''; ?></p>

            </div>
            <div class="input-container textarea">
              <textarea name="message" class="input" value="<?php echo isset($postdata['message_err']) ? $postdata['message_err'] : ''; ?>"></textarea>
              <label for="">Message</label>
              <span>Message</span>
              <p class="invalid"><?php echo isset($postdata['message_err']) ? $postdata['message_err'] : ''; ?></p>

            </div>
            <input type="submit" value="Send" class="btn" />
          </form>
        </div>
      </div>
    </div>
 </div>
<div class="footerContainer">
  <?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>
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
