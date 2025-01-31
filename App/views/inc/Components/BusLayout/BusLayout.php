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
    $routeData = $data['route'] ?? [];
    $distanceData = $data['distance'] ?? [];

    

    
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
    <!-- Add this after the seat layout div -->
   
    <div class="layout-container">
        <!-- Seat Layout -->
        <?php
            include 'seatData.php';
        
            // Retrieve data from POST
            $License_id = $_GET['Licenseid'] ?? null;
            $scheduleId = $_GET['scheduleId'] ?? null;
            echo "<script>console.log('Licenseid :', " . json_encode($License_id) . ");</script>";
            echo "<script>console.log('Scheduleid :', " . json_encode($scheduleId) . ");</script>";

           
            // Find the selected bus and schedule to get booked seats
            $selectedBus = null;
            $bookedSeats = [];
            $pricePerSeat = 0;
            $busLayout = [];
        

            // Find the selected bus and schedule to get booked seats
            foreach ($scheduleData as $schedule) {
                if ($schedule['scheduleId'] === $scheduleId) {
                    echo "<script>console.log('Selected schedule:', " . json_encode($schedule) . ");</script>";
                    $bookedSeatsString = trim($schedule['bookedSeats']);
                    $bookedSeats = !empty($bookedSeatsString) ? 
                        array_map('trim', explode(',', $bookedSeatsString)) : 
                        [];
                    $pricePerSeat = $schedule['price'];
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
                    break;
                }
            }

            //Find the respective route and stops
            $busStops = [];
            foreach ($routeData as $route) {
                if ($route['routeNumber'] === $selectedBus['routeNumber']) {
                    $busStops = explode(',', $route['stops']);
                    break;
                }
            }

            
           
            
    

            

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
                    } elseif (in_array(trim($seat), $bookedSeats)) {
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

       
    
    <!-- Add this after the seat layout div -->
<div class="map-container">
    <h3>Bus Route Map - Route <?php echo htmlspecialchars($selectedBus['routeNumber']); ?></h3>
    <iframe
        id="googleMap"
        width="100%"
        height="450"
        style="border:0"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen>
    </iframe>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const busStops = <?php echo json_encode(array_map('trim', $busStops)); ?>;
    const routeNo = <?php echo json_encode($selectedBus['route_no']); ?>;
    
    try {
        // Create a more specific search query focusing on Sri Lanka
        const fromLocation = encodeURIComponent(busStops[0] + ', Sri Lanka');
        const toLocation = encodeURIComponent(busStops[busStops.length - 1] + ', Sri Lanka');
        
        // Use directions instead of search to show the route
        const simpleRouteUrl = `https://maps.google.com/maps?`
            + `saddr=${fromLocation}`
            + `&daddr=${toLocation}`
            + `&t=m` // Map type: m = normal map
            + `&z=9` // Higher zoom level (closer view)
            + `&output=embed`
            + `&ie=UTF8`
            + `&ll=7.8731,80.7718` // Coordinates for Sri Lanka's center
            + `&spn=3.0,3.0`; // Viewport span

        // Set the iframe src with error handling
        const mapFrame = document.getElementById('googleMap');
        mapFrame.onerror = function() {
            mapFrame.parentElement.innerHTML = '<p>Unable to load map. Please try again later.</p>';
        };
        mapFrame.src = simpleRouteUrl;
    } catch (error) {
        console.error('Error loading map:', error);
        document.getElementById('googleMap').parentElement.innerHTML = 
            '<p>Unable to load map. Please try again later.</p>';
    }
});
</script>

<style>
.map-container {
    width: 100%;
    max-width: 800px;
    margin: 20px auto;
    padding: 10px;
}

#googleMap {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 4px;
}
</style>

    <div class="all-container"> 
        <div class="bus-info">
            <div class="route-container">
                <h2><?php echo $selectedBus['start_location']; ?> - <?php echo $selectedBus['destination']; ?></h2>
                <p class="date"><?php echo htmlspecialchars($selectedSchedule['date']); ?></p>
            </div>
            <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($selectedBus['License_id']); ?></p>
            <p><strong>Route Number:</strong> <?php echo htmlspecialchars($selectedBus['routeNumber']); ?></p>
            <p><strong>Available Seats:</strong> <?php echo htmlspecialchars($selectedSchedule['availableSeats']); ?></p>
            <div class="rating">
                <?php
                    $rating = 0.0;
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
                    <p class="highlight">Rs. <?php echo htmlspecialchars($selectedBus['price']); ?></p>
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
                <?php
                // Assign the selected value to $from when the form is submitted
                $from = $_POST['from'] ?? null; // Ensure to handle the case where 'from' is not set
                ?>

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

            <!-- <div class="form-group-inline">
                <label>Receive ticket via:</label>
                <input type="checkbox" name="receiveTicket[]" value="Email"> Email
                <input type="checkbox" name="receiveTicket[]" value="SMS"> SMS
            </div> -->

            <!-- Add hidden input fields for price information -->
            <input type="hidden" name="pricePerSeat" value="<?php echo htmlspecialchars($pricePerSeat); ?>">
            <input type="hidden" name="totalPrice" id="totalPriceInput" value="0" >
            
            <p><strong>Price per Seat:</strong>&nbsp;&nbsp; Rs.<span id="pricePerSeat" ><?php echo htmlspecialchars($pricePerSeat); ?></span></p>
            <p><strong>Total Price:</strong> &nbsp;&nbsp;Rs. <span id="total-price">0</span></p>
            
                
            <button type="submit" id="checkoutButton" class="checkout-button" disabled>Proceed to Checkout</button>
            
            <!-- <button type="submit">Proceed</button> -->
        </form>
        <br>
    </div>
    </div>

    </div>
</div>

<?php $selectedBusJSON = json_encode($selectedBus);
$distanceDataJSON = json_encode($distanceData);
echo "<script>
    const selectedBus = $selectedBusJSON;
    const distanceData = $distanceDataJSON;
    const leastPrice = $leastPrice;
</script>";
?>

<script>

    // Update event listeners for dropdowns
    document.getElementById('from').addEventListener('change', function() {
        const from = this.value;
        const to = document.getElementById('to').value;
        updatePrice(from, to);
    });

    document.getElementById('to').addEventListener('change', function() {
        const from = document.getElementById('from').value;
        const to = this.value;
        updatePrice(from, to);
    });

    function updatePrice(from, to) {
        if (!from || !to) return;
        
        // Get the start location and destination from the selected bus
        const startLocation = selectedBus.start_location;
        const destination = selectedBus.destination;
        let pricePerSeat = 0;
        
        if (destination === to.trim() && startLocation !== from.trim()) {
            // Full journey minus distance from start to boarding point
            let totalDistance = 0;
            let boardingDistance = 0;
            
            // Find total route distance
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === destination) {
                    totalDistance = parseFloat(route.distance);
                    break;
                }
            }
            
            // Find boarding point distance
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === from.trim()) {
                    boardingDistance = parseFloat(route.distance);
                    break;
                }
            }
            
            const finalDistance = totalDistance - boardingDistance;
            pricePerSeat = leastPrice * finalDistance;
            
        } else if (destination === to.trim() && startLocation === from.trim()) {
            // Full journey price
            pricePerSeat = parseFloat(selectedBus.price);
            
        } else if (destination !== to.trim() && startLocation !== from.trim()) {
            // Partial journey between two intermediate stops
            let toDistance = 0;
            let fromDistance = 0;
            
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === to.trim()) {
                    toDistance = parseFloat(route.distance);
                }
                if (route.start === startLocation && route.location.trim() === from.trim()) {
                    fromDistance = parseFloat(route.distance);
                }
            }
            
            const finalDistance = toDistance - fromDistance;
            pricePerSeat = leastPrice * finalDistance;
            
        } else if (startLocation === from.trim() && destination !== to.trim()) {
            // Journey from start to intermediate stop
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === to.trim()) {
                    const finalDistance = parseFloat(route.distance);
                    pricePerSeat = leastPrice * finalDistance;
                    break;
                }
            }
        }

        // Ensure price is not negative
        pricePerSeat = Math.max(0, pricePerSeat);
        
        // Update the display elements
        const pricePerSeatElement = document.getElementById('pricePerSeat');
        const totalPriceElement = document.getElementById('total-price');
        const noOfSeats = parseInt(document.getElementById('noOfseats').value) || 0;
        
        pricePerSeatElement.textContent = pricePerSeat.toFixed(2);
        totalPriceElement.textContent = (pricePerSeat * noOfSeats).toFixed(2);
        
        // Update hidden input
        document.getElementById('totalPriceInput').value = (pricePerSeat * noOfSeats).toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize price displays to zero
        const pricePerSeatElement = document.getElementById('pricePerSeat');
        const totalPriceElement = document.getElementById('total-price');
        const totalPriceInput = document.getElementById('totalPriceInput');
        
        // Set initial values to zero
        pricePerSeatElement.textContent = '0.00';
        totalPriceElement.textContent = '0.00';
        totalPriceInput.value = '0.00';

        const numberButtons = document.querySelectorAll('.number-button:not(.booked)');
        const selectedSeatsInput = document.getElementById('selectedSeats');
        const noOfSeatsInput = document.getElementById('noOfseats');
        const checkoutButton = document.getElementById('checkoutButton');
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

                // Update form inputs
                selectedSeatsInput.value = selectedSeats.join(', ');
                noOfSeatsInput.value = selectedSeats.length;

                // Get current 'from' and 'to' values and recalculate price
                const from = document.getElementById('from').value;
                const to = document.getElementById('to').value;
                
                // Only update price if both from and to are selected
                if (from && to) {
                    updatePrice(from, to);
                }

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