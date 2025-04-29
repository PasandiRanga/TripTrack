<?php
    function authCheck($allowedRoles = []) {
        if (isset($_SESSION['user_role'])) {
            $userRole = $_SESSION['user_role'];

            if (in_array($userRole, $allowedRoles)) {
                return true; 
            }
        }

        header("Location: " . URLROOT . "/GuestPages/home");
        exit();
    }
?>