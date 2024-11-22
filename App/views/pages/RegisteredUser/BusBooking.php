<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Booking</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/GuestBusBooking.css?v=<?php echo time(); ?>">
    <!-- <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/BusLayout/BusLayout.css?v=<?php echo time(); ?>"> -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">

</head>
<body>
    
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    $userID = $_SESSION['user_id'] ?? null;
   // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['user_role'] ?? 'RegisteredUser';
    $scheduleData = $data['schedule'] ?? [];
    $busData = $data['bus'] ?? [];
    $distanceData = $data['distance'] ?? [];
    $userData = $data['user'] ?? [];
    
    $data = [
        'currentController' => 'RegisteredPages',
        'currentMethod' => 'home',
        'userRole' => $userRole
    ];
    ?>

    <script>
        var scheduleData = <?php echo json_encode($scheduleData); ?>;
        console.log("Schedule Data: ", scheduleData);  
        var userData = <?php echo json_encode($userData); ?>;
        console.log("User Data:", userData);
    </script>

    <div class="hero-container">
        <br/>
        <?php require APPROOT . '/views/inc/Components/Header/header.php'; ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    

        <?php
        $License_id = $_GET['License_id'] ?? null;
        $scheduleId = $_GET['scheduleId'] ?? null;
        $selectedBus = null;
        $selectedSchedule = null;
        // $seats = $_GET['busType'];

        // Find the selected bus
        foreach ($busData as $bus) {
            if ($bus['License_id'] == $License_id) {
                $selectedBus = $bus;
                break;
            }
        }

        $busStops = [];
        if (isset($selectedBus['stops']) && !empty($selectedBus['stops'])) {
            // Convert the stops text into an array by splitting it at commas
            $busStops = explode(',', $selectedBus['stops']);
        } else {
            $busStops = ["No stops available"];
        }

        
        // Find the selected schedule for the bus
        if ($selectedBus) {
            foreach ($scheduleData as $schedule) {
                if ($schedule['License_id'] == $License_id && $schedule['scheduleId'] == $scheduleId) {
                    $selectedSchedule = $schedule;
                    break;
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
                <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($selectedBus['License_id']); ?></p>
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

        
        
       
    </div>

    <!-- Modal for Bus Layout
    <div id="busLayoutModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="busLayoutContent"></div>  Container to load busLayout.php 
            <input type="hidden" id="selectedSeats" name="selectedSeats">
        </div>
    </div> -->

    <div class="booking-form">
        <h2>Book Your Seat</h2>
        <form id="bookingForm" action="<?php echo URLROOT; ?>/RegisteredPages/busLayout" method="post" onsubmit="return validateForm()">
        <input type="hidden" name="License_id" value="<?php echo htmlspecialchars($selectedBus['License_id']); ?>">
        <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($selectedSchedule['scheduleId']); ?>">

            <div class="form-group">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userData['Name']); ?>" required>
                </div>
                <div>
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['Email']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="contact">Contact No:</label>
                    <input type="text" id="contact" name="contact" value = "<?php echo htmlspecialchars($userData['Contact_number']); ?>" required>
                </div>
                <div>
                    <label for="nic">NIC No:</label>
                    <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($userData['NIC']); ?> "required>
                </div>
            </div>

            <div class="form-group">
                <!-- 'From' Dropdown (Departure) -->
                <div>
                    <label for="from">From:</label>
                    <select id="from" name="from" required>
                        <?php 
                        if (!empty($busStops) && is_array($busStops)) {
                            // Loop through each stop in the busStops array and create an option for it
                            foreach ($busStops as $stop) {
                                echo "<option value=\"" . htmlspecialchars($stop) . "\">" . htmlspecialchars($stop) . "</option>";
                            }
                        } else {
                            // If no stops are available, show a default option
                            echo "<option value=\"\">No stops available</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- 'To' Dropdown (Arrival) -->
                <div>
                    <label for="to">To:</label>
                    <select id="to" name="to" required>
                        <?php 
                        if (!empty($busStops) && is_array($busStops)) {
                            // Skip the first element (departure) and loop through the rest of the bus stops
                            array_shift($busStops); // Remove the first element
                            foreach ($busStops as $stop) {
                                echo "<option value=\"" . htmlspecialchars($stop) . "\">" . htmlspecialchars($stop) . "</option>";
                            }
                        } else {
                            // If no stops are available, show a default option
                            echo "<option value=\"\">No stops available</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <script>
                function validateForm() {
                    var from = document.getElementById("from").value;
                    var to = document.getElementById("to").value;

                    if (from === to) {
                        alert("The 'From' and 'To' locations cannot be the same.");
                        return false; // Prevent form submission
                    }
                    return true; // Allow form submission
                }
            </script>
            <div class="form-group">
                <div>
                    <label for="noOfseats">Number of seats:</label>
                    <input type="number" id="noOfseats" name="noOfseats" min="1" step="1" value="1" required>
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
