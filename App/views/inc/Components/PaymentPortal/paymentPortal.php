<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Portal</title>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/PaymentPortal/paymentPortal.css?v=<?php echo time(); ?>">
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
                'currentMethod' => 'PaymentProtal', 
                'userRole' => $userRole
            ];
        ?>
        <?php
            $isCancellation = isset($_GET['bookingId']) && isset($_GET['cancellationFee']);

            if ($isCancellation) {
                $bookingId = htmlspecialchars($_GET['bookingId']);
                $cancellationFee = htmlspecialchars($_GET['cancellationFee']);
                $refundAmount = htmlspecialchars($_GET['refundAmount'] ?? '');
                $scheduleId = htmlspecialchars($_GET['scheduleId'] ?? '');
                echo("<script>console.log('Booking id: $bookingId');</script>");
                echo("<script>console.log('CancellationFee: $cancellationFee');</script>");
                echo("<script>console.log('Refund amount: $refundAmount');</script>");
                echo("<script>console.log('Schedule id: $scheduleId');</script>");

                $formAction = URLROOT . "/RegisteredPages/cancelBooking";
            } else {
                $formAction = URLROOT . "/" . ($userRole === 'RegisteredUser' ? 'RegisteredPages/registeredReceipt' : 'GuestPages/GuestReceipt');
            }
        ?>


    <div class="payment-container">
    <form id="paymentForm" action="<?php echo $formAction; ?>" method="POST">
        <h2>Payment Details</h2>

        <?php if ($isCancellation): ?>
            <!-- Cancellation Hidden Inputs -->
            <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($bookingId); ?>">
            <input type="hidden" name="cancellation_fee" value="<?php echo htmlspecialchars($cancellationFee); ?>">
            <input type="hidden" name="refund_amount" value="<?php echo htmlspecialchars($refundAmount); ?>">
            <input type="hidden" name="schedule_id" value="<?php echo htmlspecialchars($scheduleId); ?>">
        <?php else: ?>
            <!-- Regular Booking Hidden Inputs -->
            <input type="hidden" name="License_id" value="<?php echo htmlspecialchars($_POST['License_id']); ?>">
            <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($_POST['scheduleId']); ?>">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($_POST['name']); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email']); ?>">
            <input type="hidden" name="contact" value="<?php echo htmlspecialchars($_POST['contact']); ?>">
            <input type="hidden" name="nic" value="<?php echo htmlspecialchars($_POST['nic']); ?>">
            <input type="hidden" name="from" value="<?php echo htmlspecialchars($_POST['from']); ?>">
            <input type="hidden" name="to" value="<?php echo htmlspecialchars($_POST['to']); ?>">
            <input type="hidden" name="noOfseats" value="<?php echo htmlspecialchars($_POST['noOfseats']); ?>">
            <input type="hidden" name="selectedSeats" value="<?php echo htmlspecialchars($_POST['selectedSeats']); ?>">
            <input type="hidden" name="paymentMethod" value="<?php echo htmlspecialchars($_POST['paymentMethod']); ?>">
            <input type="hidden" name="totalPrice" value="<?php echo htmlspecialchars($_POST['totalPrice']); ?>">
            <input type="hidden" name="penaltyFee" value="<?php echo isset($_POST['penaltyFee']) ? htmlspecialchars($_POST['penaltyFee']) : null; ?>">
        <?php endif; ?>

            <div class="form-group">
                <label for="cardName">Cardholder Name</label>
                <input type="text" id="cardName" name="cardName" required>
            </div>
            
            <div class="form-group card-input-container">
                <label for="cardNumber">Card Number</label>
                <input type="text" id="cardNumber" name="cardNumber" maxlength="19" required>
                <div class="card-icons">
                    <div class="card-icon visa"></div>
                    <div class="card-icon mastercard"></div>
                    <div class="card-icon amex"></div>
                </div>
            </div>
            
            <div class="card-details">
                <div class="form-group">
                    <label for="expiry">Expiry Date</label>
                    <input type="text" id="expiry" name="expiry" placeholder="MM/YY" maxlength="5" required>
                </div>
                
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" maxlength="3" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="amount">Payment Amount ($)</label>
                <input type="number" id="amount" name="amount" 
                    value="<?php echo $isCancellation ? $cancellationFee : htmlspecialchars($_POST['totalPrice']); ?>" 
                    readonly required>
            </div>

            
            <button type="submit" class="btn-submit">Confirm Payment</button>

            <div class="secure-payment">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                </svg>
                Secure Payment Protected
            </div>
        </form>
    </div>

    <script src="paymentPortal.js"></script>
</body>
</html>