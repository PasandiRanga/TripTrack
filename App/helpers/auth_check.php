<?php
    function authCheck($allowedRoles = []) {
        // Check if the user is logged in and has a roleID
        if (isset($_SESSION['user_role'])) {
            $userRole = $_SESSION['user_role'];

            // Check if the user's role is in the allowed roles
            if (in_array($userRole, $allowedRoles)) {
                return true; // Allow access
            }
        }

        // Redirect to login or error page if access is denied
        header("Location: " . URLROOT . "/GuestPages/home");
        exit();
    }
?>