<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Layout</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusLayout.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
</head>
<body>

<script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'GuestUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    $userRole = $_SESSION['userRole'] ?? 'GuestUser';
    $data = [
        'currentController' => 'GuestPages',
        'currentMethod' => 'home',
        'userRole' => $userRole
    ];
    ?>

    <div class="hero-container">
        <?php require APPROOT . '/views/inc/Components/Header/header.php'; ?>
        <?php require APPROOT . '/views/inc/Components/NavBar/navbar.php'; ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    
        <?php
        // Include the seat data
        include 'seatData.php';

        // Select the bus you want to display, for example busId = 1 for the 56-seater bus
        $busId = 1; // Change this ID to select the bus dynamically (e.g., from a URL parameter)

        // Get the selected bus data
        $selectedBus = $busData[$busId];

        // Display the seat layout
        echo '<div class="box right-box">';
        foreach ($selectedBus['seats'] as $row) {
            echo '<div class="button-container">';
            foreach ($row as $seat) {
                if ($seat === '') {
                    echo '<button class="disable"></button>'; // Disabled seat
                } else {
                    echo '<button class="number-button">' . htmlspecialchars($seat) . '</button>'; // Available seat
                }
            }
            echo '</div><br/>';
        }
        echo '</div>';

        echo"This is bus layout";
        ?>
    </div>


</body>
</html>
