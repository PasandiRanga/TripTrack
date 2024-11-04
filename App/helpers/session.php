<?php
session_start();

// Check if a user role is already set, if not default to 'GuestUser'
if (!isset($_SESSION['userRole'])) {
    $_SESSION['userRole'] = 'GuestUser';  // Default to GuestUser
}

$userRole = $_SESSION['userRole'];

// Simulate setting session data based on different user roles
switch ($userRole) {
    case 'Admin':
        $_SESSION['adminName'] = "John Doe";
        $_SESSION['adminProfilePicUrl'] = URLROOT . '/public/images/admin-profile.png';
        break;

    case 'RegisteredUser':
        $_SESSION['userName'] = "Jane Smith";
        $_SESSION['profilePicUrl'] = URLROOT . '/public/images/user-profile.png';
        break;

    case 'SuperAdmin':
        $_SESSION['superAdminName'] = "Emily Clark";
        $_SESSION['superAdminProfilePicUrl'] = URLROOT . '/public/images/superadmin-profile.png';
        $_SESSION['permissions'] = ['manageUsers', 'viewReports', 'modifySettings'];  // Example permissions
        break;

    case 'Moderator':
        $_SESSION['moderatorName'] = "Michael Brown";
        $_SESSION['moderatorProfilePicUrl'] = URLROOT . '/public/images/moderator-profile.png';
        $_SESSION['permissions'] = ['reviewContent', 'banUsers'];
        break;

    case 'GuestUser':
    default:
        $_SESSION['guestName'] = "Guest";
        break;
}

// The $userRole can be used in your application to render different views or elements.
?>
