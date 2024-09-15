<?php
// Start the session to access session variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve the user role from the session, default to 'GuestUser' if not set
$userRole = isset($_SESSION['userRole']) ? $_SESSION['userRole'] : 'GuestUser';
?>

<nav class="header">
    <div class="abc">
        <div class="xyz">
            <ul>
                <li><img class="logo" src="<?php echo URLROOT; ?>/public/images/logo.png" alt="Logo"></li>
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
