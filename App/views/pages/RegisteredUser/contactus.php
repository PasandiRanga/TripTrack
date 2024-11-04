<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>ContactUs <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/contactus.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
<script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole); // Ensure this is set before the header loads
    </script>
    <!-- Header and Navbar -->
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

    <!-- Link the external JavaScript files -->
    <script src="./../../Component/Header/header.js"></script>
    <script src="./../../Component/NavBar/navbar.js"></script>

    <div class="container">
        <div class="left">
            <h1 class="caption">TRIP TRACK</h1>
            <h2>Contact Us</h3>
            <h3>Address:</h3>123 Main Street, Suite 400 (City,State, ZIP Code)
            <h3>Phone:</h3> +1 (123) 456-7890
            <h3>Email:</h3>info@example.com<br/><br/>

            <h3><i class="fa-brands fa-facebook-f fa-2xl"></i>
            <a href="facebook.com/yourpage">FaceBook</a></h3>
            <br/>
            <h3><i class="fa-brands fa-twitter fa-2xl"></i>
            <a href="twitter.com/yourpage">Twitter</a></h3>
                 
        </div>

        <div class="right">
            <h2 class="abc">Contact Us</h2>
            <form id="ContactUs" onsubmit="return validateForm()">
                
                <label for="name">Name :</label>
                <input type="name" id="name" name="name" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="textarea">Message :</label>
                <textarea id="textarea" name="textarea" required></textarea>
                <br/><br/>
                
                <button type="submit">Send</button>
                
                
            </form>
        </div>

    </div>

    <!-- Script to import header and navbar -->
    <script>
        // Import header
        fetch('./../../Component/HeaderRU.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });

        // Import navbar
        fetch('./../../Component/navbarRU.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('navbar').innerHTML = data;

            // After the navbar is loaded, add the event listeners
            const navbarItems = document.querySelectorAll('.navbar-item');

            // Add click event listener to each navbar item
            navbarItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove the 'selected' class from all items
                    navbarItems.forEach(nav => nav.classList.remove('selected'));

                    // Add the 'selected' class to the clicked item
                    this.classList.add('selected');
                });
            });
        })
        .catch(error => console.error('Error loading navbar:', error));
    </script>
    <script src="./../app.js"></script>
    <script>
        function validateForm() {
            var email = document.getElementById('email').value;
            var name = document.getElementById('name').value;
            var name = document.getElementById('textarea').value;

            if (email === "" || name === "" || textarea ==="") {
                alert("Both fields are required.");
                return false;
            }
            return true;
        }
        </script>

</body>
</html>