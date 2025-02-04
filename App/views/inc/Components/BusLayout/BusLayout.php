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
                    array_map('trim', explode(',', $bookedSeatsString)) : [];
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
                    break;
                }
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

        <div class="hero-container">
            <br/>
            <?php require APPROOT . '/views/inc/Components/Header/header.php'; ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
   
            <div class="layout-container">
                 <?php require APPROOT . '/views/inc/Components/busLayout/seatLayout.php'; ?>
    
                <div class="map-container">
                    <?php require APPROOT . '/views/inc/Components/busLayout/map.php'; ?>
                </div>

                <div class="all-container"> 
                    <!-- Bus info and review -->
                    <?php require APPROOT . '/views/inc/Components/busLayout/busInforAndReviews.php'; ?>
                    <!-- Booking form -->
                    <div class="booking-form">
                        <?php require APPROOT . '/views/inc/Components/busLayout/bookingForm.php'; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php 
            $selectedBusJSON = json_encode($selectedBus);
            $distanceDataJSON = json_encode($distanceData);
            echo "<script>
                    const selectedBus = $selectedBusJSON;
                    const distanceData = $distanceDataJSON;
                    const leastPrice = $leastPrice;
                </script>";
        ?>

<script>
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
        const price =selectedBus.price;
        let pricePerSeat = 0;

        //From middle to destination
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
            
        //From start to destination
        } else if (destination === to.trim() && startLocation === from.trim()) {

            // Full journey price
            pricePerSeat = price;
        
        //From middle to middle 
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
        
        //From start to middle
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

    
</script>



</body>
</html>