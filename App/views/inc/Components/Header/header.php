<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
   
</head>

<body>
    <?php

    // Assuming $data['currentController'] and $data['currentMethod'] are passed to this view
    $currentController = $data['currentController'] ?? '';
    // echo "Current controller is: " . $currentController;
    $currentMethod = $data['currentMethod'] ?? '';
    // echo "Current method is: " . $currentMethod;
    $userRole = $_SESSION['user_role'] ?? 'GuestUser';

    include_once 'notificationData.php';
    include_once 'c_notificationData.php';

    $profileImage = !empty($_SESSION['user_profile_image']) ? $_SESSION['user_profile_image'] : 'default.jpg';

    ?>

    <nav class="header">
        <div class="abc">
            <div class="xyz">
                <ul>
                    <li><img class="logo" src="<?php echo URLROOT; ?>/public/images/logo2.png" alt="Logo"></li>
                    <li class="topic">
                        <?php
                        if ($userRole === "Admin") {
                            echo "Admin Dashboard";
                        } elseif ($userRole === "RegisteredUser") {
                            echo "Trip Track";
                        } else {
                            echo "Trip Track";
                        }
                        ?>
                    </li>
                    <li class="navbar-container"><?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?></li>
                </ul>
            </div>
        </div>

        <div class="user-section">
            <?php if (in_array($userRole, ["RegisteredUser"])): ?>
                <div class="notiicon" onclick="toggleNotifi()">
                <i class="fa-solid fa-bell"></i><span class="badge"><?php echo count($notifications); ?></span>

                <div class="notifi-box" id="box">
                        <h2>Notifications <span><?php echo count($notifications); ?></span></h2>
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notifi-item">
                                <div class="close-icon" onclick="removeNotification(this)">&#10005;</div>
                                <div class="text">
                                    <h4><?php echo $notification["title"]; ?></h4>
                                    <p><?php echo $notification["date"]; ?></p>
                                    <span class="dropdown-arrow">&#9660;</span>
                                </div>
                                <div class="notification-content">
                                    <p><?php echo $notification["content"]; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            

            <?php if ($userRole === "RegisteredUser"): ?>
                <div class="<?php echo $userRole === "Admin" ? "admin-profile-container" : "user-profile-container"; ?>">
                <a href="<?php echo URLROOT; ?>/RegisteredPages/profile">
                    <div class="profile">
                        <div class="pic">
                            <img src="<?php echo URLROOT;?>/images/profileImages/<?php echo $_SESSION['user_profile_image'];?>" alt="Profile Picture" class="profile-pic">
                        </div>
                    </div>
                </a>
                </div>
            <?php else: ?>
                <div class="login-container">
                    <!-- Login Button -->
                    <button class="login-button" onclick="showSignInBox()">Login</button>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <div class="signInBox <?php echo isset($headerData['showPopup']) && $headerData['showPopup'] ? '' : 'hidden'; ?>" id="signInBox">
        <div class="signInBoxContent">
            <div class="close-btn" onclick="closeSignInBox()">×</div>
            <?php require APPROOT.'/views/inc/Components/LoginBox/loginBox.php'; ?>
        </div>
    </div>


    <script>


        function toggleNotifi(event) {
            event.stopPropagation();  // Prevent the click event from propagating to the document
            const box = document.getElementById('box');
            box.style.height = box.style.height === '510px' ? '0px' : '510px';
            box.style.opacity = box.style.opacity === '1' ? '0' : '1';
        }


        function toggleNotificationContent(event) {
            const content = event.target.closest('.notifi-item').querySelector('.notification-content');
            content.style.display = content.style.display === 'block' ? 'none' : 'block';
            event.target.innerHTML = content.style.display === 'block' ? '&#9650;' : '&#9660;';
        }

        // Show the login box
        document.addEventListener('DOMContentLoaded', function () {
            const showPopup = <?php echo isset($data['showPopup']) && $data['showPopup'] ? 'true' : 'false'; ?>;

            if (showPopup) {
                document.getElementById('signInBox').classList.remove('hidden');
            }
        });

        function showSignInBox() {
            document.getElementById('signInBox').classList.remove('hidden');
        }

        function closeSignInBox() {
            document.getElementById('signInBox').classList.add('hidden');
        }
        
        //Login form
        function validateForm(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (email && password) {
                alert("Form is valid!");
                closeSignInBox();
            } else {
                alert("Please fill in both email and password.");
            }
        }

        document.addEventListener('click', function(event) {
            const box = document.getElementById('box');
            const notiicon = document.querySelector('.notiicon')
            if (box && !box.contains(event.target) && !notiicon.contains(event.relatedTarget)) {
            box.style.height = '0px';
            box.style.opacity = '0';
    }
        });

        document.querySelectorAll('.dropdown-arrow').forEach(arrow => {
            arrow.addEventListener('click', toggleNotificationContent);
        });

        function removeNotification(element) {
            element.closest('.notifi-item').remove();
        }

     
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navbarItems = document.getElementById('navbar-items');

    menuToggle.addEventListener('click', function() {
        navbarItems.classList.toggle('active');
    });
});


    </script>
</body>

</html>