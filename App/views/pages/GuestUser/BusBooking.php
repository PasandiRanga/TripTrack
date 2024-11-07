<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Booking</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/GuestBusBooking.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusLayout/BusLayout.css?v=<?php echo time(); ?>">
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
    require_once APPROOT . '/views/inc/Components/BusCard/busData.php';
    $busId = $_GET['busId'] ?? null;
    $selectedBus = null;

    foreach ($busDetails as $bus) {
        if ($bus['busId'] == $busId) {
            $selectedBus = $bus;
            break;
        }
    }

    if ($selectedBus) {
    ?>

    <div class="bus-info">
        <h2><?php echo htmlspecialchars($selectedBus['route']); ?></h2>
        <p><strong>Date :</strong> <?php echo htmlspecialchars($selectedBus['busNumber']); ?></p>
        <p><strong>Route Number:</strong> <?php echo htmlspecialchars($selectedBus['busNumber']); ?></p>
        <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($selectedBus['busId']); ?></p>
        <!-- <p><strong>Departure:</strong> <?php echo htmlspecialchars($selectedBus['departure']); ?></p> -->
        <!-- <p><strong>Arrival:</strong> <?php echo htmlspecialchars($selectedBus['arrival']); ?></p> -->

        

        <div class="info-details">
            <div class="info-item">
                <h3><?php echo htmlspecialchars($selectedBus['departure']); ?></h3>
                <p>Departure</p>
            </div>
            <div class="info-item">
                <h3><?php echo htmlspecialchars($selectedBus['arrival']); ?></h3>
                <p>Arrival</p>
            </div>
        </div>

        <p class="highlight">Price: LKR <?php echo htmlspecialchars($selectedBus['price']); ?></p>
        <p class="highlight">Rating: <?php echo htmlspecialchars($selectedBus['rating']); ?></p>
        <p><strong>Available Seats:</strong> <?php echo 38 - intval($selectedBus['passengers']); ?></p>

        <div class="view-button">
            <button>View bus stops and times</button>
            <button>View ratings and reviews</button>
        </div>
    </div>
    </div>

    <div class="seat-layout">
        <?php require_once APPROOT . '/views/inc/Components/BusLayout/BusLayout.php'; ?>
    </div>

    <div class="booking-form">
        <h3>Book Your Seat</h3>
        <form action="processBooking.php" method="post">
            <input type="hidden" name="busId" value="<?php echo htmlspecialchars($selectedBus['busId']); ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="contact">Contact No:</label>
            <input type="text" id="contact" name="contact" required><br>

            <label for="nic">NIC No:</label>
            <input type="text" id="nic" name="nic" required><br>

            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" required><br>

            <label>Payment method:</label>
            <input type="radio" name="paymentMethod" value="Cash" required> Cash
            <input type="radio" name="paymentMethod" value="Online" required> Online<br>

            <label>Receive ticket via:</label>
            <input type="radio" name="receiveTicket" value="Cash" required> Cash
            <input type="radio" name="receiveTicket" value="Online" required> Online<br>

            <button type="submit">Proceed</button>
        </form>
    </div>

    <?php
    } else {
        echo "<p>Bus not found.</p>";
    }
    ?>

</body>
</html>
