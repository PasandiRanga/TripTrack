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

    $scheduleData = $data['schedule'] ?? [];
    $busData = $data['bus'] ?? [];
    $distanceData = $data['distance'] ?? [];

    // Retrieve the user role from the form submission or session
    $formUserRole = ($_SESSION['user_role'] ?? 'GuestUser');
    echo("<script>console.log('User Role: $formUserRole');</script>");

    // Set `userRole` and `currentController` based on the form data or session
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
       
        echo '<script>';
        echo 'console.log(' . json_encode($_POST) . ')';
        echo '</script>';
        // Retrieve data from POST
        $License_id = $_POST['License_id'] ?? null;
        $scheduleId = $_POST['scheduleId'] ?? null;
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $contact = $_POST['contact'] ?? '';
        $nic = $_POST['nic'] ?? '';
        $from = $_POST['from'] ?? '';
        $to = $_POST['to'] ?? '';
        $noOfSeats = $_POST['noOfseats'] ?? ''; // Number of seats limit
        $paymentMethod = $_POST['paymentMethod'] ?? '';
        $receiveTicket = $_POST['receiveTicket'] ?? [];

        // Find the selected bus and schedule to get booked seats
        $selectedBus = null;
        $bookedSeats = [];
        $pricePerSeat = 0;
        $busLayout = [];
        // $leastPrice = 0;
 

        foreach ($scheduleData as $schedule) {
            if ($schedule['scheduleId'] === $scheduleId) {
                $bookedSeats = array_map('trim', explode(',', $schedule['bookedSeats']));
                break;
            }
        }

        foreach($busData as $bus) {
            if ($bus['License_id'] === $License_id) {
                $selectedBus = $bus;
                // echo($selectedBus['License_id']);
                // echo '<pre>'; print_r($selectedBus); echo '</pre>';
                $busType = $bus['passengers'];
                $leastPrice = $bus['priceperkm'];
                // echo($leastPrice);
                // echo($busType);
                break;
            }
        }
        if($selectedBus['destination'] == trim($to) && $selectedBus['start_location'] !=  trim($from)) {
            // Get the distance between the two cities
            $Sdistance = 0;
            $Tdistance = 0;
            $Fdistance = 0;
            foreach ($distanceData as $dist) {
                // echo($dist['start']);
                // echo($selectedBus['start_location']);
                // echo($dist['location']);
                // echo($from);
                // echo "<br>";
                if($dist['start'] == $selectedBus['start_location'] && trim($dist['location']) == trim($from)) {
                    $Tdistance = $dist['distance'];
                    // echo("t$Tdistance");
                    break;
                }
            }

            foreach($distanceData as $dist) {
                // echo($dist['start']);
                // echo($selectedBus['start_location']);
                // echo($dist['location']);
                // echo($selectedBus['destination']);
                // echo($dist['distance']);
                // echo "<br>";
                if($dist['start'] == $selectedBus['start_location'] && trim($dist['location']) == trim($selectedBus['destination'])) {
                    $Sdistance = $dist['distance'];
                    // echo("s$Sdistance");
                    break;
                }
            }

            $Fdistance = $Sdistance - $Tdistance;

            $pricePerSeat = $leastPrice * $Fdistance;

        }else if($selectedBus['destination'] == trim($to) && $selectedBus['start_location'] ==  trim($from)) {
            $pricePerSeat = $selectedBus['price'];

        }else if($selectedBus['destination'] != trim($to) && $selectedBus['start_location'] != trim($from)){
            $Sdistance = 0;
            $Tdistance = 0;
            $Fdistance = 0;

            foreach($distanceData as $dist){
                if($selectedBus['start_location'] == $dist['start'] && $dist['location'] == trim($to)){
                    $Sdistance = $dist['distance'];
                    break;
                }
            }
            foreach($distanceData as $dist){
                if($selectedBus['start_location'] == $dist['start'] && $dist['location'] == trim($from)){
                    $Tdistance = $dist['distance'];
                    break;
                }
            }

            $Fdistance = $Sdistance - $Tdistance;

            $pricePerSeat = $leastPrice * $Fdistance;
        }else if($selectedBus['start_location'] == trim($from) && $selectedBus['destination'] != trim($to)){
            foreach($distanceData as $dist){
                if($selectedBus['start_location'] == $dist['start'] && $dist['location'] == trim($to)){
                    $Fdistance = $dist['distance'];
                }
            }

            $pricePerSeat = $leastPrice * $Fdistance;
        }


            // Get the bus data for seat layout
            foreach ($seatData as $layout) {
                if($layout['seatType'] === $busType) {
                    $busLayout = $layout['seats'];
                    // echo '<pre>'; print_r($busLayout); echo '</pre>';
                    break;
                }
            }
        
        // echo '<pre>'; print_r($busLayout); echo '</pre>';


        // Add the data-seat-limit attribute to the container
        echo '<div class="box right-box" data-seat-limit="' . htmlspecialchars($noOfSeats) . '">';
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

    <div>   <!-- Booking Details -->
        <div class="details-container">
            <h2>Booking Details</h2>
            <p><strong>Name:</strong> &nbsp;&nbsp;<?php echo htmlspecialchars($name); ?></p>
            <p><strong>Email:</strong> &nbsp; &nbsp;<?php echo htmlspecialchars($email); ?></p>
            <p><strong>Contact No:</strong> &nbsp;&nbsp; <?php echo htmlspecialchars($contact); ?></p>
            <p><strong>NIC:</strong> &nbsp; &nbsp;<?php echo htmlspecialchars($nic); ?></p>
            <p><strong>From:</strong> &nbsp;&nbsp; <?php echo htmlspecialchars($from); ?></p>
            <p><strong>To:</strong> &nbsp;&nbsp; <?php echo htmlspecialchars($to); ?></p>
            <p><strong>Number of Seats:</strong> &nbsp;&nbsp; <?php echo htmlspecialchars($noOfSeats); ?></p>
            <p><strong>Payment Method:</strong> &nbsp;&nbsp; <?php echo htmlspecialchars($paymentMethod); ?></p>
            <p><strong>Receive ticket option:</strong> &nbsp;&nbsp;<?php echo htmlspecialchars(implode(', ', $receiveTicket)); ?></p>
        </div>

        <!-- Selected Seats and Payment -->
        <div class="payment-container">
            <p><strong>Selected Seats:</strong> &nbsp;&nbsp;<span id="selected-seats"></span></p>
            <p><strong>Price per Seat:</strong>&nbsp;&nbsp; Rs. <?php echo htmlspecialchars($pricePerSeat); ?></p>
            <p><strong>Total Price:</strong> &nbsp;&nbsp;Rs. <span id="total-price">0</span></p>
            
            <form action="" method="POST" id="checkout-form" onsubmit="console.log('Form data:', new FormData(this));">
                <input type="hidden" name="License_id" value="<?php echo htmlspecialchars($License_id); ?>">
                <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($scheduleId); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="contact" value="<?php echo htmlspecialchars($contact); ?>">
                <input type="hidden" name="nic" value="<?php echo htmlspecialchars($nic); ?>">
                <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
                <input type="hidden" name="to" value="<?php echo htmlspecialchars($to); ?>">
                <input type="hidden" name="noOfseats" value="<?php echo htmlspecialchars($noOfSeats); ?>">
                <input type="hidden" name="pricePerSeat" value="<?php echo htmlspecialchars($pricePerSeat); ?>">
                <input type="hidden" name="totalPrice" value="<?php echo htmlspecialchars($pricePerSeat * $noOfSeats); ?>">
                <input type="hidden" name="selectedSeats" id="selected-seats-input">
                
                <button type="submit" class="checkout-button" disabled>Proceed to Checkout</button>
            </form>
        </div>
    </div>

    </div>
</div>

<script>
// JavaScript to handle seat selection limit and form action based on user role
document.addEventListener("DOMContentLoaded", function() {
    const seatLimit = parseInt(document.querySelector(".box.right-box").getAttribute("data-seat-limit"));
    const pricePerSeat = <?php echo json_encode($pricePerSeat); ?>;
    let selectedSeats = 0;
    let selectedSeatNumbers = [];

    const selectedSeatsElement = document.getElementById("selected-seats");
    const totalPriceElement = document.getElementById("total-price");
    const checkoutButton = document.querySelector(".checkout-button");
    const checkoutForm = document.getElementById("checkout-form");

    // Adjust form action based on user role
    const userRole = "<?php echo $userRole; ?>";
    console.log(userRole);
    checkoutForm.action = userRole === 'RegisteredUser' ? 'RegisteredReceipt' : 'GuestReceipt';

    const buttons = document.querySelectorAll(".number-button:not(.booked):not(.disable)");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            const seatNumber = button.textContent;

            if (button.classList.contains("selected")) {
                // Deselect the seat
                selectedSeats--;
                selectedSeatNumbers = selectedSeatNumbers.filter(seat => seat !== seatNumber);
                button.classList.remove("selected");

                // Update the display
                selectedSeatsElement.textContent = selectedSeatNumbers.join(", ");
                totalPriceElement.textContent = selectedSeats * pricePerSeat;

                if (selectedSeats < seatLimit) {
                    checkoutButton.disabled = true;
                }
            } else if (selectedSeats < seatLimit) {
                // Select the seat
                selectedSeats++;
                selectedSeatNumbers.push(seatNumber);
                button.classList.add("selected");

                // Update the display
                selectedSeatsElement.textContent = selectedSeatNumbers.join(", ");
                totalPriceElement.textContent = selectedSeats * pricePerSeat;

                if (selectedSeats === seatLimit) {
                    checkoutButton.disabled = false;
                }
            }
        });
    });

    checkoutButton.addEventListener("click", function() {
        document.getElementById("selected-seats-input").value = selectedSeatNumbers.join(", ");
        console.log("Selected Seats:", selectedSeatNumbers.join(", "));
    });
});
</script>

<style>
    /* Additional styling for selected seats */
    .number-button.selected {
        background-color: rgba(0, 85, 105, 0.7);
        color: #fff;
    }
</style>

</body>
</html>
