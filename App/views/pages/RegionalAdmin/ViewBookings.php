<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home and Booking Records</title>
    
    <!-- Font Awesome and Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Component and Page Styles -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/buttons/button.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/searchBar/searchBar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/RotateText/rotateText.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusCard/busCard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegionalAdmin/ViewBookings.css?v=<?php echo time(); ?>">

    <!-- Pass user role to JavaScript -->
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'Admin'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

</head>
<body>
    <!-- Retrieve User Role and Current Page Data -->
    <?php
    $userRole = $_SESSION['userRole'] ?? 'Admin';
    $data = [
        'currentController' => 'RegionalAdminPages',
        'currentMethod' => 'viewbookings',
        'userRole' => $userRole
    ];
    ?>

    <!-- Load Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <!-- Hero Section with Header and NavBar -->
    <div class="hero-container">
        <?php require APPROOT . '/views/inc/Components/Header/header.php'; ?>
        <?php require APPROOT . '/views/inc/Components/NavBar/navbar.php'; ?>

        <div class="background"></div>

        <!-- Rotating Text and Main Image -->
        <div class="text-container">
            <?php require APPROOT . '/views/inc/Components/RotateText/rotateText.php'; ?>
        </div>
        <img class="name-image" src="<?php echo URLROOT; ?>/public/images/BlackName.png" alt="Name Image" />
    </div>

    <!-- Booking Records Section -->
    <div class="body-section">
        <h1>Booking Records</h1>
        
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Booking Date</th>
                    <th>Booking Time</th>
                    <th>No. of Seats</th>
                    <th>Amount</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody id="booking-tbody">
                <?php
                $bookingData = [
                    ['1001', '2024-11-05', '14:30', 2, '$40', 'Confirmed'],
                    ['1002', '2024-11-06', '09:00', 1, '$20', 'Pending'],
                    ['1003', '2024-11-07', '11:00', 3, '$60', 'Confirmed']
                ];

                foreach ($bookingData as $booking) {
                    echo "<tr onclick='selectRow(this)'>";
                    foreach ($booking as $item) {
                        echo "<td>$item</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
    <div class="footer-container">
        <?php require APPROOT . '/views/inc/Components/Footer/footer.php'; ?>
    </div>

    <!-- JavaScript Functions -->
    <script>
        // Navigate Back to Previous Page
        function goBack() {
            window.history.back();
        }

        // Select Row in Table
        function selectRow(row) {
            const previouslySelectedRow = document.querySelector(".booking-table tr.selected");
            if (previouslySelectedRow) {
                previouslySelectedRow.classList.remove("selected");
            }
            row.classList.add("selected");
        }
    </script>
</body>
</html>
