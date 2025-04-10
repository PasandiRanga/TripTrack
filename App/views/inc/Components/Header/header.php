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


    //include_once 'notificationData.php';
    // include_once 'c_notificationData.php';


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
                <div class="notification-container">
                        <div class="bell-icon" id="bell-icon">
                            <i class="fas fa-bell"></i>
                            <span class="badge" id="notification-count"><?php echo count($notifications); ?></span>
                        </div>
                        <div class="notification-box" id="notification-box">
                            <h3>Notifications</h3>
                            <div class="notification-list" id="notification-list">
                                <?php if (!empty($notifications)): ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <div class="notifi-item" data-notification-id="<?php echo $notification['id']; ?>">
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
                                                    <button class="mark-read-btn" data-id="<?php echo $notification['id']; ?>">
                                                        Mark as <?php echo $notification['is_read'] ? 'unread' : 'read'; ?>
                                                    </button>
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
    <script src="<?php echo URLROOT; ?>/public/js/notification.js"></script>

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

    // document.addEventListener('DOMContentLoaded', function() {
    //     const notificationIcon = document.querySelector('notiicon');
    //     const notificationBox = document.getElementById('box');
    //     let isOpen = false;

    //     notificationIcon.addEventListener('click', function(event) {
    //         event.stopPropagation();
    //         isOpen = !isOpen;

    //         if (isOpen) {
    //             // Fetch notifications when clicking the bell icon
    //             fetchNotifications();
    //         }

    //         notificationBox.style.height = isOpen ? '510px' : '0px';
    //         notificationBox.style.opacity = isOpen ? '1' : '0';
    //     });

    //     function fetchNotifications() {
    //         fetch('<?php echo URLROOT; ?>/RegisteredPages/getAllNotifications')
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.success) {
    //                     updateNotificationsUI(data.notifications);
    //                     console.log(data.notifications);
    //                 } else {
    //                     console.error("Failed to fetch notifications.");
    //                 }
    //             })
    //             .catch(error => console.error('Error fetching notifications:', error));
    //     }

    //     function updateNotificationsUI(notifications) {
    //         const notificationContainer = document.getElementById('box');
    //         notificationContainer.innerHTML = '';

    //         if (notifications.length === 0) {
    //             notificationContainer.innerHTML = '<p>No notifications available.</p>';
    //         } else {
    //             notifications.forEach(notification => {
    //                 const notificationItem = document.createElement('div');
    //                 notificationItem.classList.add('notifi-item');
    //                 notificationItem.innerHTML = `
    //                     <div class="close-icon" onclick="removeNotification(this)">&#10005;</div>
    //                     <div class="text">
    //                         <h4>${notification.title}</h4>
    //                         <p>${notification.time}</p>
    //                         <div class="dropdown-arrow">&#9660;</div>
    //                     </div>
    //                     <div class="notification-content" style="display: none;">
    //                         <p>${notification.notification}</p>
    //                     </div>
    //                 `;
    //                 notificationContainer.appendChild(notificationItem);
    //             });
    //         }
    //     }
    // });


//     if($userRole == 'RegisteredUser'){
//         document.addEventListener('click', function(event) {
//             if (!notificationIcon.contains(event.target) && !notificationBox.contains(event.target) && isOpen) {
//                 isOpen = false;
//                 notificationBox.style.height = '0px';
//                 notificationBox.style.opacity = '0';
//             }
//         });
//     }

//     // Handle notification content toggles
//     document.querySelectorAll('.dropdown-arrow').forEach(arrow => {
//         arrow.addEventListener('click', function(event) {
//             event.stopPropagation();
//             const content = this.closest('.notifi-item').querySelector('.notification-content');
//             const isContentVisible = content.style.display === 'block';
            
//             content.style.display = isContentVisible ? 'none' : 'block';
//             this.innerHTML = isContentVisible ? '&#9660;' : '&#9650;';
//         });
//     });

//     <?php if (!empty($notifications)): ?>
//         console.log("Notifications array: ", <?php echo json_encode($notifications); ?>);
//     <?php else: ?>
//         console.log("Empty");
//     <?php endif; ?>

//     // Handle notification removal
//     document.querySelectorAll('.close-icon').forEach(icon => {
//         icon.addEventListener('click', function(event) {
//             event.stopPropagation();

//             // Remove the notification item
//             this.closest('.notifi-item').remove();

//             // Update notification count
//             const count = document.querySelectorAll('.notifi-item').length;
            
//             // Update the notification count inside the bell icon and notifi-box
//             document.querySelectorAll('.badge').forEach(badge => {
//                 badge.textContent = count;
//             });
// Z
//         });
//     });


    
    </script>
</body>

</html>