<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Layout</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/GuestBusBooking.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header Placeholder -->
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'GuestUser'); ?>;
        localStorage.setItem('userRole', userRole);
    </script>

    <?php
    $data = [
        'currentController' => 'GuestPages', // Adjust this based on your controller
        'currentMethod' => 'home' // Adjust this based on the method
    ];
    ?>

    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

    <div class="container">
        <div class="left-column">
            <div class="box left-box">
                <div class="abc">
                    <div class="left-content">
                        <div class="route">Colombo >>> Kurunegala</div>
                        <div class="detail">
                            29th August - 6.00AM<br />
                            Route number: 87<br />
                            Bus number: ht76543
                        </div>
                    </div>
                    <div class="right-content">
                        <div class="service">Average</div>
                        <div class="extra">Normal service - non-AC<br />
                        Available seats: 49/54</div>
                    </div>
                </div>

                <div class="container">
                    <figure class="image-caption-container">
                        <img src="./../../Images/bus.jpg">
                        <div class="caption">
                            Colombo For Bus stand<br/>7.45AM
                        </div>
                    </figure>
                    <hr>
                    <figure class="image-caption-container">
                        <img src="./../../Images/Map.jpg">
                        <div class="caption">
                            Shripura<br/>3.45PM
                        </div>
                    </figure>
                </div>
                
                <div class="container">
                    <button class="more">View bus stops and times</button>
                    <button class="more">View rating and reviews</button>
                    <div style="font-weight: bold; font-size: 30px;">LKR 1400</div>
                </div>
            </div>

            <br/>

            <div class="box left-box" style="background-color:#C5F2E9;">
                <div class="form-container">
                    <form id="bookingForm">
                        <label for="seats">Seats Layout:</label>
                        <input type="text" id="seats" name="seats" required placeholder="Please select the seats">
                        
                        <label for="total">Seats:</label>
                        <input type="text" id="total" name="total" required placeholder="Total">
            
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
            
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" required>
            
                        <label for="contact">Contact No:</label>
                        <input type="tel" id="contact" name="contact" pattern="[0-9]{10}" required>
            
                        <label for="nic">NIC No:</label>
                        <input type="text" id="nic" name="nic" required>
            
                        <label for="destination">Destination:</label>
                        <input type="text" id="destination" name="destination" required>
            
                        <label for="payment">Payment method:</label>
                        <div class="payment-options">
                            <label><input type="radio" name="payment" value="Cash"> Cash</label>
                            <label><input type="radio" name="payment" value="Online"> Online</label>
                        </div>
            
                        <label for="ticket">Receive ticket via:</label>
                        <div class="ticket-options">
                            <label><input type="radio" name="ticket" value="Cash"> Cash</label>
                            <label><input type="radio" name="ticket" value="Online"> Online</label>
                        </div>
                        
                        <button type="submit" class="proceedButton">Proceed</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="right-column" id="right-column">
            <div class="box right-box">
                <!-- Seat Layout -->
                <?php include './../../inc/Components/BusLayout/BusLayout.php'; ?>
                
                <!-- Display Bus Details -->
                <h2>Available Buses</h2>
                <?php foreach ($busDetails as $bus): ?>
                    <div class="bus-detail">
                        <h3><?php echo htmlspecialchars($bus['route']); ?></h3>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($bus['busType']); ?></p>
                        <p><strong>Departure:</strong> <?php echo htmlspecialchars($bus['departure']); ?></p>
                        <p><strong>Arrival:</strong> <?php echo htmlspecialchars($bus['arrival']); ?></p>
                        <p><strong>Duration:</strong> <?php echo htmlspecialchars($bus['duration']); ?></p>
                        <p><strong>Stops:</strong> <?php echo htmlspecialchars(implode(', ', $bus['stops'])); ?></p>
                        <p><strong>Rating:</strong> <?php echo htmlspecialchars($bus['rating']); ?></p>
                        <p><strong>Passengers:</strong> <?php echo htmlspecialchars($bus['passengers']); ?></p>
                        <p><strong>Price:</strong> <?php echo htmlspecialchars($bus['price']); ?></p>
                        <p><strong>Cheapest:</strong> <?php echo $bus['cheapest'] ? 'Yes' : 'No'; ?></p>
                        <hr>
                    </div>
                <?php endforeach; ?>
            </div>

            <script>
                document.getElementById('seats').addEventListener('click', function() {
                    document.getElementById('right-column').style.display = 'block';
                });
            </script>
        </div>
    </div>
</body>
</html>
