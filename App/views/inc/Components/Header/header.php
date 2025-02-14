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

    $notifications = $data['notifications'] ?? []; // Ensure the variable exists

    //include_once 'notificationData.php';
    //include_once 'c_notificationData.php';

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
                    <li class="navbarContainer"><?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?></li>
                </ul>
            </div>
        </div>

        <div class="user-section">
            <?php if (in_array($userRole, ["RegisteredUser"])): ?>
                <!-- Update the notification button HTML -->
                <div class="notiicon">
                    <i class="fa-solid fa-bell"></i>
                    <span class="badge"><?php echo !empty($notifications) ? count($notifications) : '0'; ?></span>
                    <div class="notifi-box" id="box">
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notifi-item">
                                <div class="close-icon" onclick="removeNotification(this)">&#10005;</div>
                                <div class="text">
                                    <h4><?php echo htmlspecialchars($notification['Title']); ?></h4>
                                    <p><?php echo htmlspecialchars($notification['Time']); ?></p>
                                    <div class="dropdown-arrow">&#9660;</div>
                                </div>
                                <div class="notification-content">
                                    <p><?php echo htmlspecialchars($notification['Content']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No notifications available.</p>
                    <?php endif; ?>
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

    
    //navbar
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navbarItems = document.getElementById('navbar-items');

            menuToggle.addEventListener('click', function() {
                navbarItems.classList.toggle('active');
            });
        });

    document.addEventListener('DOMContentLoaded', function() {
    const notificationIcon = document.querySelector('.notiicon');
    const notificationBox = document.getElementById('box');
    let isOpen = false;

    // Toggle notification box when clicking the icon
    notificationIcon.addEventListener('click', function(event) {
        event.stopPropagation();
        isOpen = !isOpen;
        
        notificationBox.style.height = isOpen ? '510px' : '0px';
        notificationBox.style.opacity = isOpen ? '1' : '0';
    });

    // Close notification box when clicking outside
    document.addEventListener('click', function(event) {
        if (!notificationIcon.contains(event.target) && !notificationBox.contains(event.target) && isOpen) {
            isOpen = false;
            notificationBox.style.height = '0px';
            notificationBox.style.opacity = '0';
        }
    });

    // Handle notification content toggles
    document.querySelectorAll('.dropdown-arrow').forEach(arrow => {
        arrow.addEventListener('click', function(event) {
            event.stopPropagation();
            const content = this.closest('.notifi-item').querySelector('.notification-content');
            const isContentVisible = content.style.display === 'block';
            
            content.style.display = isContentVisible ? 'none' : 'block';
            this.innerHTML = isContentVisible ? '&#9660;' : '&#9650;';
        });
    });

    <?php if (!empty($notifications)): ?>
        console.log("Notifications array: ", <?php echo json_encode($notifications); ?>);
    <?php else: ?>
        console.log("Empty");
    <?php endif; ?>

    // Handle notification removal
    document.querySelectorAll('.close-icon').forEach(icon => {
        icon.addEventListener('click', function(event) {
            event.stopPropagation();

            // Remove the notification item
            this.closest('.notifi-item').remove();

            // Update notification count
            const count = document.querySelectorAll('.notifi-item').length;
            
            // Update the notification count inside the bell icon and notifi-box
            document.querySelectorAll('.badge').forEach(badge => {
                badge.textContent = count;
            });
Z
        });
    });

});
    
    </script>
</body>

</html>