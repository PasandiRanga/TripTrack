<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/buttons/button.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/searchBar/searchBar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/RotateText/rotateText.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusCard/busCard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegionalAdmin/Support.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
<script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Admin'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['userRole'] ?? 'Admin';
    ?>

    <?php
    $data = [
        'currentController' => 'RegionalAdminPages', // Adjust this based on your controller
        'currentMethod' => 'support', // Adjust this based on the method
        'userRole' => $userRole
    ];
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>


    <div class="hero-container">
        <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
        <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>
        
        <div class="background"></div>

        <div class="text-container">
            <?php require APPROOT . '/views/inc/Components/RotateText/rotateText.php'; ?>
        </div>

        <img class="name-image" src="<?php echo URLROOT; ?>/public/images/BlackName.png" alt="Name Image" />

    </div>
    <div class="body-section">
    <div class="body-section">
        <div class="messages-table-container">
            <h2>Customer Messages</h2>
            <table class="messages-table">
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Define an array of customer messages
                    $customerMessages = [
                        ['refID' => 101, 'name' => 'John Doe', 'email' => 'johndoe@example.com', 'message' => 'Hello, I need help with my account.'],
                        ['refID' => 102, 'name' => 'Jane Smith', 'email' => 'janesmith@example.com', 'message' => 'I would like to inquire about your services.'],
                        ['refID' => 103, 'name' => 'Sam Wilson', 'email' => 'samwilson@example.com', 'message' => 'Can you help me with a billing issue?'],
                        ['refID' => 104, 'name' => 'Emily Brown', 'email' => 'emilybrown@example.com', 'message' => 'I am facing a technical issue.']
                    ];

                    // Loop through the array and display each message in a table row
                    foreach ($customerMessages as $message) {
                        echo "<tr>
                                <td>{$message['refID']}</td>
                                <td>{$message['name']}</td>
                                <td><a href='mailto:{$message['email']}?subject=Reply to Your Message'>{$message['email']}</a></td>
                                <td>{$message['message']}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

        <div class="footer-container">
            <?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>
        </div>
    </div>

    
</body>
</html>