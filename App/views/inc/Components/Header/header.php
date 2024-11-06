<?php
// Start the session to access session variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve the user role from the session, default to 'GuestUser' if not set
$userRole = isset($_SESSION['userRole']) ? $_SESSION['userRole'] : 'GuestUser';
?>

<!-- Link to the CSS file -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">

<nav class="header">
    <div class="abc">
        <div class="xyz">
            <ul>
                <li><img class="logo" src="<?php echo URLROOT; ?>/public/images/logo2.png" alt="Logo"></li>
                <li class="topic">
                    <?php
                    // Set the header topic based on the user role
                    if ($userRole === "Admin") {
                        echo "Admin Dashboard";
                    } elseif ($userRole === "RegisteredUser") {
                        echo "Trip Track - Registered User";
                    } else {
                        echo "Trip Track";
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="user-section">
        
<!---------------NOTIFICATION ICON------------------------------------------------->

        <div class="icon">
            <img src="<?php echo URLROOT; ?>/public/images/bell.png" alt="not"><span>3</span>
        </div>

        <div class="notifi-box" id="box">
			<h2>Notifications <span>3</span></h2>

			<div class="notifi-item">
				<div class="text">
				   <h4>Booking Confirmation</h4>
				   <p>August 12, 2024, 2:00 PM</p>
                   <span class="dropdown-arrow">&#9660;</span> <!-- Dropdown arrow on the right -->
			    </div> 
                <div class="notification-content">
                    <p>Your booking for bus route #45 from City A to City B on August 12, 2024, at 3:00 PM has been 
                    confirmed. Your seat number is 12A.</p>
                </div>
			</div>

			<div class="notifi-item">
				<div class="text">
				   <h4>Bus Delay Alert</h4>
				   <p>August 10, 2024, 11:45 AM</p>
                   <span class="dropdown-arrow">&#9660;</span>
			    </div> 
                <div class="notification-content">
                    <p>This is the description of Notification 2. Here you can provide details about the notification topic.</p>
                </div>
			</div>

			<div class="notifi-item">
				<div class="text">
				   <h4>Cancelation Confirmation</h4>
				   <p>August 9, 2024, 5:00 PM</p>
                   <span class="dropdown-arrow">&#9660;</span>
			    </div>
                <div class="notification-content">
                    <p>This is the description of Notification 3. Here you can provide details about the notification topic.</p>
                </div> 
			</div>
		</div>

        <script>
            // Function to toggle the visibility of notification content
            function toggleNotificationContent(event) {
                var content = event.target.closest('.notifi-item').querySelector('.notification-content');
                // Toggle the display of the notification content
                if (content.style.display === 'block') {
                    content.style.display = 'none';
                    event.target.innerHTML = '&#9660;'; // Change arrow to down when content is hidden
                } else {
                    content.style.display = 'block';
                    event.target.innerHTML = '&#9650;'; // Change arrow to up when content is visible
                }
            }

            // Attach event listeners to all dropdown arrows
            var arrows = document.querySelectorAll('.dropdown-arrow');
            arrows.forEach(function(arrow) {
                arrow.addEventListener('click', toggleNotificationContent);
            });

            // Grab the notification box and icon for toggling visibility
            var box = document.getElementById('box');
            var down = false;

            // Toggle the notification box visibility (dropdown box)
            function toggleNotifi() {
                if (down) {
                    box.style.height = '0px';
                    box.style.opacity = '0';
                    down = false;
                } else {
                    box.style.height = '510px'; // Adjust height for content
                    box.style.opacity = '1';
                    down = true;
                }
            }

            // Attach the toggle function to the bell icon
            document.querySelector('.icon').addEventListener('click', toggleNotifi);

            // Close the notification box when clicking anywhere outside the box
            document.addEventListener('click', function(event) {
                var isClickInsideBox = box.contains(event.target);
                var isClickOnBellIcon = document.querySelector('.icon').contains(event.target);
                
                // If the click is outside the box and not on the bell icon, close the notification box
                if (!isClickInsideBox && !isClickOnBellIcon && down) {
                    box.style.height = '0px';
                    box.style.opacity = '0';
                    down = false;
                }
            });

        </script>

<!---------------------------------------------------------------------------------------------------------------------->

        <?php if ($userRole === "Admin"): ?>
            <!-- Admin-specific content -->
            <div class="admin-profile-container">
                <img src="<?php echo isset($_SESSION['adminProfilePicUrl']) ? $_SESSION['adminProfilePicUrl'] : URLROOT . '/public/images/default-profile.png'; ?>" alt="Admin Profile Picture" class="profile-pic">
                <span class="admin-name"><?php echo isset($_SESSION['adminName']) ? $_SESSION['adminName'] : 'Admin'; ?></span>
            </div>

        <?php elseif ($userRole === "RegisteredUser"): ?>
            <!-- Registered User-specific content -->
            <div class="user-profile-container">
                <img src="<?php echo isset($_SESSION['profilePicUrl']) ? $_SESSION['profilePicUrl'] : URLROOT . '/public/images/default-profile.png'; ?>" alt="User Profile Picture" class="profile-pic">
                <span class="user-name"><?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : 'User'; ?></span>
            </div>

        <?php else: ?>
            <!-- Guest User content -->
            <div class="login-container">
                <button class="login-button" onclick="window.location.href='<?php echo URLROOT; ?>/login'">Login</button>
            </div>
        <?php endif; ?>
    </div>
</nav>
