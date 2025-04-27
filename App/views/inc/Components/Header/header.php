<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Navigation</title>
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/notification.css?v=<?php echo time(); ?>">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    </head>

    <body>
        <?php
            $currentController = $data['currentController'] ?? '';
            $currentMethod = $data['currentMethod'] ?? '';
            $userRole = $_SESSION['user_role'] ?? 'GuestUser';
            $profileImage = !empty($_SESSION['user_profile_image']) ? $_SESSION['user_profile_image'] : 'default.jpg';
        ?>

        <nav class="headers">
            <div class="abc">
                <div class="xyz">
                    <ul>
                        <li><img class="logo" src="<?php echo URLROOT; ?>/public/images/logoicon.png" alt="Logo"></li>
                        <li class="topic">
                            <?php
                            if ($userRole === "RegisteredUser") {
                                echo '<img src="' . URLROOT . '/public/images/logoname.png" alt="Trip Track Logo" class="header-logo">';
                            } else {
                                echo '<img src="' . URLROOT . '/public/images/logoname.png" alt="Trip Track Logo" class="header-logo">';
                            }?>
                        </li>
                        <li class="navbarContainer"><?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?></li>
                    </ul>
                </div>
            </div>

            <div class="user-section">
                <?php if (in_array($userRole, ["RegisteredUser"])): ?>
                    <div class="notiicon">
                        <div class="notification-container">
                            <div class="bell-icon" id="bell-icon">
                                <i class="fas fa-bell"></i>
                                <span class="badge" id="notification-count"><?php echo count(array_filter($notifications, function($n) { return !$n['is_read']; })); ?></span>                        
                            </div>
                            <div class="notification-box" id="notification-box">
                                <h3>Notifications</h3>
                                <div class="notification-list" id="notification-list">
                                    <?php if (!empty($notifications)): ?>
                                        <?php foreach ($notifications as $notification): ?>
                                            <div class="notifi-item <?php echo $notification['is_read'] ? 'read' : 'unread'; ?>" data-notification-id="<?php echo $notification['id']; ?>">                                            
                                                <div class="notification-header">
                                                    <div class="notification-title"><?php echo $notification['title']; ?></div>
                                                    <div class="notification-time"><?php echo $notification['created_at']; ?></div>
                                                    <div class="notification-controls">
                                                        <span class="dropdown-arrow">&#9660;</span>
                                                        <br/>
                                                        <span class="close-icon">&#10005;</span>
                                                    </div>
                                                </div>
                                                <div class="notification-content">
                                                    <p><?php echo $notification['message']; ?></p>
                                                    <div class="notification-actions">
                                                        <button class="mark-read-btn" data-id="<?php echo $notification['id']; ?>">Mark as <?php echo $notification['is_read'] ? 'unread' : 'read'; ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="no-notifications">No notifications available.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="view-all-container">
                                    <a href="<?php echo URLROOT; ?>/RegisteredPages/allNotifications" class="view-all-btn">View All Notifications</a>
                                </div>
                            </div>
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
        <script src="<?php echo URLROOT; ?>/public/js/notification.js"></script>

        <script>

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