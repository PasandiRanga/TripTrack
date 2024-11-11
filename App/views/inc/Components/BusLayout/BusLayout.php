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
    // Retrieve the user role from the form submission or session
    $formUserRole = $_POST['userRole'] ?? ($_SESSION['userRole'] ?? 'GuestUser');

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
    <?php require APPROOT . '/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT . '/views/inc/Components/NavBar/navbar.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <div class="layout-container">
        <!-- Seat Layout -->
        <?php
        include 'seatData.php';
        include APPROOT . '/views/inc/Components/BusCard/scheduleData.php';

        //Retrieve data from POST
        $busId = $_POST['busId'] ?? null;
        $scheduleId = $_POST['scheduleId'] ?? null;
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $contact = $_POST['contact'] ?? '';
        $nic = $_POST['nic'] ?? '';
        $destination = $_POST['destination'] ?? '';
        $noOfSeats = $_POST['noOfseats'] ?? ''; //Number of seats limit
        $paymentMethod = $_POST['paymentMethod'] ?? '';
        $receiveTicket = $_POST['receiveTicket'] ?? [];

        // Find the selected bus and schedule to get booked seats
        $selectedBus = $busData[$busId];
        $bookedSeats =[];
        // Set price per seat (replace with actual price retrieval logic if needed)
        $pricePerSeat = $busSchedules[$busId]['schedule'][$scheduleId]['price'] ?? 0;

        foreach ($busSchedules as $bus) {
            if ($bus['busId'] === $busId) {
                foreach ($bus['schedule'] as $schedule) {
                    if ($schedule['scheduleId'] === $scheduleId) {
                        $bookedSeats = $schedule['bookedSeats'];
                        $pricePerSeat = (int) filter_var($schedule['price'], FILTER_SANITIZE_NUMBER_INT); // Sanitize to extract integer price
                        break;
                    }
                }
                break;
            }
        }

        //Add the data-seat-limit attrubute to the container
        echo '<div class="box right-box" data-seat-limit="' . htmlspecialchars($noOfSeats) . '">';
        foreach ($selectedBus['seats'] as $row) {
            echo '<div class="button-container">';
            foreach ($row as $seat) {
                if ($seat === '') {
                    echo '<button class="disable"></button>'; // Disabled seat
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
            <!-- <p><strong>Bus ID:</strong> <?php echo htmlspecialchars($busId); ?></p>
            <p><strong>Schedule ID:</strong> <?php echo htmlspecialchars($scheduleId); ?></p> -->
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($contact); ?></p>
            <p><strong>NIC:</strong> <?php echo htmlspecialchars($nic); ?></p>
            <p><strong>Destination:</strong> <?php echo htmlspecialchars($destination); ?></p>
            <p><strong>Number of Seats:</strong> <?php echo htmlspecialchars($noOfSeats); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($paymentMethod); ?></p>
            <p><strong>Receive Ticket Options:</strong> <?php echo htmlspecialchars(implode(', ', $receiveTicket)); ?></p>
        </div>

        <!-- Selected Seats and Payment -->
        <div class="payment-container">
            <!-- <h2>Seat Selection & Payment</h2> -->
            <p><strong>Selected Seats:</strong> <span id="selected-seats"></span></p>
            <p><strong>Price per Seat:</strong> Rs. <?php echo htmlspecialchars($pricePerSeat); ?></p>
            <p><strong>Total Price:</strong> Rs. <span id="total-price">0</span></p>
            
            <form action="" method="POST" id="checkout-form">
                <!-- Existing form fields -->
                <input type="hidden" name="busId" value="<?php echo htmlspecialchars($busId); ?>">
                <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($scheduleId); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="contact" value="<?php echo htmlspecialchars($contact); ?>">
                <input type="hidden" name="nic" value="<?php echo htmlspecialchars($nic); ?>">
                <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
                <input type="hidden" name="noOfseats" value="<?php echo htmlspecialchars($noOfSeats); ?>">
                <input type="hidden" name="pricePerSeat" value="<?php echo htmlspecialchars($pricePerSeat); ?>">
                <input type="hidden" name="totalPrice" value="<?php echo htmlspecialchars($pricePerSeat * $noOfSeats); ?>">
                <input type="hidden" name="selectedSeats" id="selected-seats-input">
                
                <!-- Other form fields and payment options as before -->
                <p><strong>Payment Method:</strong></p>
                <label>
                    <input type="radio" name="paymentMethod" value="credit" checked> Credit Card
                </label>
                <label>
                    <input type="radio" name="paymentMethod" value="debit"> Debit Card
                </label>
                <label>
                    <input type="radio" name="paymentMethod" value="cash"> Cash
                </label>
                <label>
                    <input type="radio" name="paymentMethod" value="online"> Online
                </label>
                <br><br>

                <!-- Checkout Button -->
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

            if (selectedSeats < seatLimit) {
                selectedSeats++;
                selectedSeatNumbers.push(seatNumber);
                button.classList.add("selected");

                selectedSeatsElement.textContent = selectedSeatNumbers.join(", ");
                totalPriceElement.textContent = selectedSeats * pricePerSeat;

                if (selectedSeats === seatLimit) {
                    checkoutButton.disabled = false;
                }
            }
        });
    });

    checkoutButton.addEventListener("click", function() {
        // Set the selected seat numbers in the form input
        document.getElementById("selected-seats-input").value = selectedSeatNumbers.join(", ");
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
