<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">
</head>

<body>
    <?php
    // Assuming $data['currentController'] and $data['currentMethod'] are passed to this view
    $currentController = $data['currentController'] ?? '';
    // echo "Current controller is: " . $currentController;
    $currentMethod = $data['currentMethod'] ?? '';
    // echo "Current method is: " . $currentMethod;
    $userRole = $data['userRole'] ?? '';

    include_once 'notificationData.php';

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
                </ul>
            </div>
        </div>

        <div class="user-section">
            <?php if (in_array($userRole, ["Admin", "RegisteredUser", "Employee"])): ?>
                <div class="icon" onclick="toggleNotifi()">
                    <img src="<?php echo URLROOT; ?>/public/images/bell.png" alt="not"><span>3</span>
                </div>

                <div class="notifi-box" id="box">
                    <h2>Notifications <span><?php echo count($notifications); ?></span></h2>

                    <?php 
                    foreach ($notifications as $notification): ?>
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
            <?php endif; ?>

            <?php if ($userRole === "Admin" || $userRole === "RegisteredUser"): ?>
                <div class="<?php echo $userRole === "Admin" ? "admin-profile-container" : "user-profile-container"; ?>">
                <a href="<?php echo URLROOT; ?>/RegisteredPages/profile">
                    <img src="profile.jpg" alt="Profile Picture" class="profile-pic">
                </a>
                </div>

            <?php else: ?>
                <div class="login-container">
                    <button class="login-button" onclick="showSignInBox()">Login</button>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <div id="signInBox" class="signInBox hidden">
        <div class="signInContent">
            <div class="login-section">
                <img src="<?php echo URLROOT; ?>/public/images/logo2.png" alt="Logo" class="logo">
                <h2>Login to Your Account</h2>
                <p>Login using social networks</p>
                <div class="social-icons">
                    <button class="social-btn fb">f</button>
                    <button class="social-btn google">G+</button>
                    <button class="social-btn linkedin">in</button>
                </div>
                <form id="loginForm" onsubmit="validateForm(event)">
                    <label>Email</label>
                    <input id="email" type="email" placeholder="Email" required>
                    <label>Password</label>
                    <input id="password" type="password" placeholder="Password" required>
                    <button type="submit" class="sign-in-btn">Sign In</button>
                </form>
            </div>
            <div class="signup-section">
                <div class="close-btn" onclick="closeSignInBox()">×</div>
                <h3>New Here?</h3>
                <p>"Sign up to find and book buses with ease and enjoy a hassle-free journey!"</p>
                <button class="sign-up-btn">Sign Up</button>
            </div>
        </div>
    </div>

    <script>
        function toggleNotifi() {
            const box = document.getElementById('box');
            box.style.height = box.style.height === '510px' ? '0px' : '510px';
            box.style.opacity = box.style.opacity === '1' ? '0' : '1';
        }

        function toggleNotificationContent(event) {
            const content = event.target.closest('.notifi-item').querySelector('.notification-content');
            content.style.display = content.style.display === 'block' ? 'none' : 'block';
            event.target.innerHTML = content.style.display === 'block' ? '&#9650;' : '&#9660;';
        }

        function showSignInBox() {
            document.getElementById('signInBox').classList.remove('hidden');
        }

        function closeSignInBox() {
            document.getElementById('signInBox').classList.add('hidden');
        }

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
            if (!box.contains(event.target) && !event.target.closest('.icon')) {
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
    </script>
</body>

</html>
