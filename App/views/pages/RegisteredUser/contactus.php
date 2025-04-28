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
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
        $userRole = $_SESSION['userRole'] ?? 'RegisteredUser';
        $notifications = $data['notifications'] ?? [];
    ?>

    <?php
        $postdata = $data;
        $data['currentController'] = 'RegisteredPages';
        $data['currentMethod'] = 'contactUs';
        $data['userRole'] = $userRole;
    ?> 

    <?php
        if (isset($_SESSION['success_message'])):
    ?>
       
    <?php
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
            <div class="form-form">
                <div class="contact-info">
                    <h3 class="title">Let's get in touch</h3>
                    <p class="description">
                    "Whether it's feedback, inquiries, or support, we value every passenger's journey.
                    Contact us today, and let TripTrack make your travel easier and more enjoyable!"
                    </p>

                    <div class="info">
                        <div class="information">
                            <i class="fas fa-map-marker-alt icon-custom"></i>
                            <p>&nbsp;&nbsp;123 Main Street, Suite 400
                            City, State, ZIP Code</p>
                        </div>
                        <div class="information">
                            <i class="fas fa-envelope icon-custom"></i>
                            <p>&nbsp;&nbsp;info@example.com</p>
                        </div>
                        <div class="information">
                            <i class="fas fa-phone-alt icon-custom"></i>
                            <p>&nbsp;&nbsp;Phone: +1 (123) 456-7890</p>
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

                <div class="successBox hidden" id="successBox">
                    <div class="successBoxContent">
                        <div class="close-btn" onclick="closesuccessBox()">×</div>
                        <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
                        <h1>Got It! 📩</h1>
                        <h4>Your request is in. We'll be in touch by email soon!</h4>
                    </div>
                </div>

                <div class="contact-form">
                    <form action="<?php echo URLROOT; ?>/RegisteredPages/submitRequest" method="POST" enctype="multipart/form-data" id="contactForm">
                        <h3 class="title">Contact us</h3>
                        <p class="description">We'd love to hear from you. Please fill out this form and we'll get in touch shortly.</p>

                        <div class="input-container">
                            <label for="name">Name</label>
                            <input type="name" name="name" id="name" class="name" value="<?php echo isset($postdata['name']) ? $postdata['name'] : ''; ?>" />
                            <div id="NameError" class="error-message">Name can only contain characters.</div>
                        </div>
                        <div class="input-container">
                            <label for="email">Email</label>
                            <input type="mail" name="email" id="ContactEmail" class="input" value="<?php echo isset($postdata['email']) ? $postdata['email'] : ''; ?>" />
                            <div id="EmailError" class="error-message">Enter a valid email</div>
                        </div>
                        <div class="input-container">
                            <label for="tel">Phone</label>
                            <input type="tel" name="phone" id="contact" class="input" value="<?php echo isset($postdata['contactNo_err']) ? $postdata['contactNo_err'] : ''; ?>"  />
                            <div id="ContactError" class="error-message">Enter a valid contact number</div>
                        </div>
                        <div class="input-container textarea">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="input"><?php echo isset($postdata['message_err']) ? $postdata['message_err'] : ''; ?></textarea>
                            <div id="MessageError" class="error-message">Message is required</div>
                        </div>
                        <input type="submit" id="submitBtn" value="Send" class="btn" />
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
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('ContactEmail');
        const contactInput = document.getElementById('contact');
        const messageInput = document.getElementById('message');
        const nameError = document.getElementById('NameError');
        const emailError = document.getElementById('EmailError');
        const contactError = document.getElementById('ContactError');
        const messageError = document.getElementById('MessageError');
        const contactForm = document.getElementById('contactForm');

        document.querySelectorAll('.error-message').forEach(error => {
            error.style.display = 'none';
        });

        function showsuccessBox() {
            document.getElementById("successBox").classList.remove("hidden");
        }

        function closeConfirmBox() {
            document.getElementById("successBox").classList.add("hidden");
        }

        contactForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            if (validateForm()) {
                showsuccessBox();
                
                setTimeout(function() {
                    contactForm.submit();
                }, 4000);
            }
        });

        function validateForm() {
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isContactValid = validateContact();
            const isMessageValid = validateMessage();
            
            return isNameValid && isEmailValid && isContactValid && isMessageValid;
        }

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

        function showError(inputElement, errorElement, message, isError) {
            if (isError) {
                inputElement.classList.add('input-error');
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            } else {
                inputElement.classList.remove('input-error');
                errorElement.style.display = 'none';
            }
        }

        function validateName() {
            const name = nameInput.value.trim();
            const nameRegex = /^[a-zA-Z\s]+$/;
            
            if (name === '') {
                showError(nameInput, nameError, 'Name is required', true);
                return false;
            } else if (!nameRegex.test(name)) {
                showError(nameInput, nameError, 'Name should only contain letters and spaces', true);
                return false;
            } else {
                showError(nameInput, nameError, '', false);
                return true;
            }
        }

        function validateEmail() {
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email === '') {
                showError(emailInput, emailError, 'Email is required', true);
                return false;
            } else if (!emailRegex.test(email)) {
                showError(emailInput, emailError, 'Please enter a valid email address', true);
                return false;
            } else {
                showError(emailInput, emailError, '', false);
                return true;
            }
        }

        function validateContact() {
            const contact = contactInput.value.trim();
            const contactRegex = /^\d{10}$/;
            
            if (contact === '') {
                showError(contactInput, contactError, 'Contact number is required', true);
                return false;
            } else if (!contactRegex.test(contact)) {
                showError(contactInput, contactError, 'Please enter a valid 10-digit contact number', true);
                return false;
            } else {
                showError(contactInput, contactError, '', false);
                return true;
            }
        }

        function validateMessage(){
            const message = messageInput.value.trim();
            if(message === ''){
                showError(messageInput, messageError, 'Message is required', true);
                return false;
            } else {
                showError(messageInput, messageError, '', false);
                return true;
            }
        }

        contactInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 10);
            validateContact();
        });

        nameInput.addEventListener('input', validateName);
        emailInput.addEventListener('input', validateEmail);
        messageInput.addEventListener('input', validateMessage);

        <?php if (isset($_SESSION['success_message'])): ?>
            showConfirmBox();
        <?php endif; ?>
    </script>
</body>
</html>