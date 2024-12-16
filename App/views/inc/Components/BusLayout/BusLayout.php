<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Layout</title>
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
    $userID = $_SESSION['user_id'] ?? null;
    // Retrieve user role from session or set to a default value
    $userRole = $_SESSION['user_role'] ?? 'RegisteredUser';

    $userData = $data['user'] ?? [];

    $scheduleData = $data['schedule'] ?? [];
    $busData = $data['bus'] ?? [];
    $distanceData = $data['distance'] ?? [];
    // Console log the distanceData array
    if (is_array($distanceData)) {
        echo "<script>console.log('Distance Data:', " . json_encode($distanceData) . ");</script>";
    } else {
        echo "<script>console.log('Distance Data is not an array');</script>";
    }
    // Retrieve the user role from the form submission or session
    $formUserRole = ($_SESSION['user_role'] ?? 'GuestUser');
    echo("<script>console.log('User Role: $formUserRole');</script>");

    // Set zuserRole and currentController based on the form data or session
    if ($formUserRole === 'GuestUser') {
        $userRole = 'GuestUser';
        $currentController = 'GuestPages';
    } elseif ($formUserRole === 'RegisteredUser') {
        $userRole = 'RegisteredUser';
        $currentController = 'RegisteredPages';
    } else {
        $userRole = 'GuestUser'; // Default to GuestUser if no valid role is provided
        $currentController = 'GuestPages';
    }

    // Pass data to the template
    $data = [
        'currentController' => $currentController,
        'currentMethod' => 'home', // Adjust as needed
        'userRole' => $userRole
    ];
?>

<div class="hero-container">
    <br/>
    <?php require APPROOT . '/views/inc/Components/Header/header.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <div class="layout-container">
        <!-- Seat Layout -->
        <?php
            include 'seatData.php';
        
            // Retrieve data from POST
            $License_id = $_GET['License_id'] ?? null;
            $scheduleId = $_GET['scheduleId'] ?? null;
           
            // Find the selected bus and schedule to get booked seats
            $selectedBus = null;
            $bookedSeats = [];
            $pricePerSeat = 0;
            $busLayout = [];
            $leastPrice = 0;

            // Find the selected bus and schedule to get booked seats
            foreach ($scheduleData as $schedule) {
                if ($schedule['scheduleId'] === $scheduleId) {
                    $bookedSeats = array_map('trim', explode(',', $schedule['bookedSeats']));
                    break;
                }
            }

            // Find the respective bus and see the bus type
            foreach($busData as $bus) {
                if ($bus['License_id'] === $License_id) {
                    $selectedBus = $bus;
                    echo "<script>console.log('Selected bus:', " . json_encode($selectedBus) . ");</script>";
                    $busType = $bus['passengers'];
                    $leastPrice = $bus['priceperkm'];
                    // echo($leastPrice);
                    break;
                }
            }

            // Calculate the price per seat based on the distance
            

            // egt the respective seat layout
            foreach ($seatData as $layout) {
                if($layout['seatType'] === $busType) {
                    $busLayout = $layout['seats'];
                    // echo '<pre>'; print_r($busLayout); echo '</pre>';
                    break;
                }
            }

            // show the seat layout
            echo '<div class="box right-box">';
            foreach ($busLayout as $row) {
                echo '<div class="button-container">';
                foreach ($row as $seat) {
                    if ($seat === '') {
                        echo '<button class="disable"></button>'; // Disabled seat (empty spaces)
                    } elseif (in_array($seat, $bookedSeats)) {
                        // Booked seat: non-clickable and styled differently
                        echo '<button class="number-button booked" disabled>' . htmlspecialchars($seat) . '</button>';
                    } else {
                        // Available seat
                        echo '<button class="number-button">' . htmlspecialchars($seat) . '</button>';
                    }
                }
                echo '</div>';
            }
            echo '</div>';
        
        ?>

        <?php
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
        ?>

    <div class="all-container"> 
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
                    <button onclick="openReviewsModal('<?php echo htmlspecialchars($selectedBus['License_id']); ?>')">View reviews</button>
                </div>
        </div>

        <!-- model for revies -->
   <div id="reviewsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeReviewsModal()">&times;</span>
            <h2>Bus Reviews</h2>
            <div id="reviewsContainer">
                <!-- Reviews will be dynamically loaded here -->
            </div>
        </div>
    </div>

    <script>

    console.log('Script loading...');

    function openReviewsModal(licenseId) {
        console.log(licenseId);
        const modal = document.getElementById('reviewsModal');
        const reviewsContainer = document.getElementById('reviewsContainer');
        modal.style.display = 'block';

        // Clear previous reviews
        reviewsContainer.innerHTML = '<p>Loading reviews...</p>';

        // Fetch reviews dynamically via AJAX
        fetch('<?php echo URLROOT; ?>/RegisteredPages/getReviews?License_id=' + licenseId)
        // console.log (licenseId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.reviews);
                    const reviewsHtml = data.reviews.map(review => `
                        <div class="review-item">
                            <div class="review-name">${review.Name}</div>
                            <div class="review-text">${review.review}</div>
                        </div>
                    `).join('');
                    reviewsContainer.innerHTML = reviewsHtml || '<p>No reviews available for this bus.</p>';
                } else {
                    reviewsContainer.innerHTML = '<p>Error loading reviews. Please try again later.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching reviews:', error);
                reviewsContainer.innerHTML = '<p>Error fetching reviews. Please try again later.</p>';
            });
    }

    function closeReviewsModal() {
        const modal = document.getElementById('reviewsModal');
        modal.style.display = 'none';
    }
    </script>

        <div class="booking-form">
        <h2>Book Your Seat</h2>
        <form id="bookingForm" action="<?php echo URLROOT; ?>/<?php echo $userRole === 'RegisteredUser' ? 'RegisteredPages/registeredReceipt' : 'GuestPages/guestReceipt'; ?>" method="post" onsubmit="return validateBookingForm()">
        <input type="hidden" name="License_id" value="<?php echo htmlspecialchars($selectedBus['License_id']); ?>">
        <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($selectedSchedule['scheduleId']); ?>">

            <div class="form-group">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" 
                        value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Name'])) ? htmlspecialchars($userData['Name']) : ''; ?>" 
                        required>
                </div>
                <div>
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" 
                        value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Email'])) ? htmlspecialchars($userData['Email']) : ''; ?>" 
                        required>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="contact">Contact No:</label>
                    <input type="text" id="contact" name="contact" 
                        value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Contact_number'])) ? htmlspecialchars($userData['Contact_number']) : ''; ?>" 
                        required>
                </div>
                <div>
                    <label for="nic">NIC No:</label>
                    <input type="text" id="nic" name="nic" 
                        value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['NIC'])) ? htmlspecialchars($userData['NIC']) : ''; ?>" 
                        required>
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
                    <input type="number" id="noOfseats" name="noOfseats" min="1" step="1" value="0" readonly required>
                </div>
                <div>
                    <label for="selectedSeats">Selected seats:</label>
                    <input type="text" id="selectedSeats" name="selectedSeats" required>
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
            <p><strong>Price per Seat:</strong>&nbsp;&nbsp; Rs. <?php echo htmlspecialchars($pricePerSeat); ?></p>
            <p><strong>Total Price:</strong> &nbsp;&nbsp;Rs. <span id="total-price">0</span></p>
            
                
                <button type="submit" id="checkoutButton" class="checkout-button" disabled>Proceed to Checkout</button>
            
            <!-- <button type="submit">Proceed</button> -->
        </form>
        <br>
    </div>
    </div>

    </div>
</div>




<style>
    /* Base seat styling */
    .number-button {
        background-image: url('<?php echo URLROOT; ?>/public/images/seat.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        width: 50px;
        height: 50px;
        color: transparent;
        font-size: 0;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    /* Selected seat styling */
    .number-button.selected {
        background-color: #28a745; /* Green tint for selected seats */
        border: 2px solid #1e7e34;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        position: relative;
    }

    /* Booked/disabled seat styling */
    .number-button.booked {
        background-color: #dc3545; /* Red tint for booked seats */
        opacity: 0.7;
        cursor: not-allowed;
        position: relative;
    }

    /* Empty space styling */
    .disable {
        background: transparent;
        border: none;
        cursor: default;
        width: 50px;
        height: 50px;
    }

    /* Optional: Add tooltips for seat status */
    .number-button.booked::after {
        content: 'Booked';
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        background: #000;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .number-button.booked:hover::after {
        opacity: 1;
    }

    /* Hover effects for available seats */
    .number-button:not(.booked):hover {
        transform: scale(1.1);
        border: 2px solid #007bff;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const numberButtons = document.querySelectorAll('.number-button:not(.booked)');
        const selectedSeatsInput = document.getElementById('selectedSeats');
        const noOfSeatsInput = document.getElementById('noOfseats');
        const totalPriceDisplay = document.getElementById('total-price');
        const checkoutButton = document.getElementById('checkoutButton');
        const pricePerSeat = <?php echo $pricePerSeat; ?>;
        const bookingForm = document.getElementById('bookingForm');
        
        let selectedSeats = [];

        numberButtons.forEach(button => {
            button.addEventListener('click', function() {
                const seatNumber = this.textContent;
                
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
                } else {
                    this.classList.add('selected');
                    selectedSeats.push(seatNumber);
                }

                // Update form inputs and displays
                selectedSeatsInput.value = selectedSeats.join(', ');
                noOfSeatsInput.value = selectedSeats.length;
                totalPriceDisplay.textContent = (selectedSeats.length * pricePerSeat).toFixed(2);

                // Enable/disable checkout button based on seat selection and form validation
                validateFormFields();
            });
        });

        // Add input event listeners to all required form fields
        const requiredFields = bookingForm.querySelectorAll('input[required], select[required]');
        requiredFields.forEach(field => {
            field.addEventListener('input', validateFormFields);
        });

        // Validate radio buttons
        const paymentRadios = bookingForm.querySelectorAll('input[name="paymentMethod"]');
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', validateFormFields);
        });

        function validateFormFields() {
            const allFieldsFilled = Array.from(requiredFields).every(field => field.value.trim() !== '');
            const seatsSelected = selectedSeats.length > 0;
            const paymentSelected = Array.from(paymentRadios).some(radio => radio.checked);
            
            checkoutButton.disabled = !(allFieldsFilled && seatsSelected && paymentSelected);
        }
    });

    function validateBookingForm() {
        // Get form values
        const from = document.getElementById("from").value;
        const to = document.getElementById("to").value;
        const selectedSeats = document.getElementById("selectedSeats").value;
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
        
        // Validate locations
        if (from === to) {
            alert("The 'From' and 'To' locations cannot be the same.");
            return false;
        }

        // Validate seat selection
        if (!selectedSeats) {
            alert("Please select at least one seat.");
            return false;
        }

        // Validate payment method
        if (!paymentMethod) {
            alert("Please select a payment method.");
            return false;
        }

        return true;
    }
</script>


</body>
</html>