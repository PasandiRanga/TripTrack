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
        require_once APPROOT . '/views/inc/Components/BusCard/scheduleData.php';
        $busId = $_GET['busId'] ?? null;
        $scheduleId = $_GET['scheduleId'] ?? null;
        $selectedBus = null;
        $selectedSchedule = null;
        $seats = $_GET['busType'];

        // Find the selected bus
        foreach ($busDetails as $bus) {
            if ($bus['busId'] == $busId) {
                $selectedBus = $bus;
                break;
            }
        }

        // Find the selected schedule for the bus
        if ($selectedBus) {
            foreach ($busSchedules as $busSchedule) {
                if ($busSchedule['busId'] == $busId) {
                    foreach ($busSchedule['schedule'] as $schedule) {
                        if ($schedule['scheduleId'] == $scheduleId) {
                            $selectedSchedule = $schedule;
                            break;
                        }
                    }
                }
            }
        }

        if ($selectedBus && $selectedSchedule) {
        ?>
        <div class="bus-info">
                <div class="route-container">
                    <h2><?php echo htmlspecialchars($selectedBus['route']); ?></h2>
                    <p class="date"><?php echo htmlspecialchars($selectedSchedule['date']); ?></p>
                </div>
                <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($selectedBus['busNumber']); ?></p>
                <p><strong>Route Number:</strong> <?php echo htmlspecialchars($selectedBus['routeNumber']); ?></p>
                <p><strong>Available Seats:</strong> <?php echo htmlspecialchars($selectedSchedule['availableSeats']); ?></p>
                <div class="rating">
                    <?php
                    $rating = $selectedBus['rating']; // Assuming `rating` is a number like 4, 4.5, etc.
                    $fullStars = floor($rating); // Full stars based on integer part of rating
                    $halfStar = $rating - $fullStars >= 0.5; // Check if there's a half star
                    $maxStars = 5; // Total number of stars

                    // Render full stars
                    for ($i = 0; $i < $fullStars; $i++) {
                        echo '<i class="fas fa-star filled-star"></i>';
                    }

                    // Render half star if applicable
                    if ($halfStar) {
                        echo '<i class="fas fa-star-half-alt filled-star"></i>';
                    }

                    // Render empty stars
                    for ($i = $fullStars + $halfStar; $i < $maxStars; $i++) {
                        echo '<i class="far fa-star empty-star"></i>';
                    }
                    ?>
                    <span><?php echo number_format($rating, 1); ?></span>
                </div>
            
                <!--Arrival and departure times-->
                <div class="info-details">
                    <div class="info-item">
                        <div>
                            <h3><?php echo htmlspecialchars($selectedSchedule['departureTime']); ?></h3>
                            <p>Departure</p>
                        </div>
                        <span class="icon-time">
                            <i class="fas fa-bus"></i>
                        </span> <!-- Bus icon -->
                        
                    </div>

                    <!-- Flex container to place the dotted line in the same row as departure and arrival -->
                    <div class="time-container">
                        <hr class="dotted-line">
                    </div>

                    <div class="info-item">
                        <span class="icon-time"><i class="fas fa-map-marker-alt"></i></span> <!-- Location icon -->
                        <div>
                            <h3><?php echo htmlspecialchars($selectedSchedule['arrivalTime']); ?></h3>
                            <p>Arrival</p>
                        </div>
                    </div>
                </div>

                <!--Price-->
                <div class="price-container">
                    <p class="highlight"><?php echo htmlspecialchars($selectedSchedule['price']); ?></p>
                </div>
                
                <!--buttons-->
                <div class="view-button">
                    <button>View bus stops and times</button>
                    <button>View ratings and reviews</button>
                </div>
        </div>
        <div class="seat-layout">
            <?php require_once APPROOT . '/views/inc/Components/BusLayout/BusLayout.php'; ?>
        </div>
    </div>
    <div class="booking-form">
        <h2>Book Your Seat</h2>
        <form action="processBooking.php" method="post">
            <input type="hidden" name="busId" value="<?php echo htmlspecialchars($selectedBus['busId']); ?>">
            <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($selectedSchedule['scheduleId']); ?>">

            <div class="form-group">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="contact">Contact No:</label>
                    <input type="text" id="contact" name="contact" required>
                </div>
                <div>
                    <label for="nic">NIC No:</label>
                    <input type="text" id="nic" name="nic" required>
                </div>
            </div>
            
            <div class="form-group">
                <div>
                    <label for="destination">Destination:</label>
                    <input type="text" id="destination" name="destination" required><br>
                </div>
                <div>
                    <label for="destination">Number of seats:</label>
                    <input type="text" id="noOfseats" name="noOfseats" required><br>
                </div>
                
            </div>

            <div class="form-group-inline">
                <label>Payment method:</label>
                <input type="radio" name="paymentMethod" value="Cash" required> Cash
                <input type="radio" name="paymentMethod" value="Online" required> Online
            </div>

            <div class="form-group-inline">
                <label>Receive ticket via:</label>
                <input type="checkbox" name="receiveTicket[]" value="Email"> Email
                <input type="checkbox" name="receiveTicket[]" value="SMS"> SMS
            </div>
            <br>


            <button type="submit">Proceed</button>
        </form>
        <br>
    </div>
    <br><br>

   


    <?php
    } else {
        echo "<p>Bus or schedule not found.</p>";
    }
    ?>

</body>
</html>
