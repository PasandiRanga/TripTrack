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
                'currentMethod' => 'home', // Adjust as needed
                'userRole' => $userRole
            ];
        ?>

    <div class="payment-container">
    <form id="paymentForm" action="<?php echo URLROOT; ?>/<?php echo $userRole === 'RegisteredUser' ? 'RegisteredPages/registeredReceipt' : 'GuestPages/GuestReceipt'; ?>" method="post" onsubmit="return validateBookingForm()">
            <h2>Payment Details</h2>
            
            <!-- Hidden inputs from previous form -->
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
            
            <div class="form-group">
                <label for="cardName">Cardholder Name</label>
                <input type="text" id="cardName" name="cardName" required>
            </div>
            
            <div class="form-group">
                <label for="cardNumber">Card Number</label>
                <input type="text" id="cardNumber" name="cardNumber" maxlength="19" required>
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
                       value="<?php echo htmlspecialchars($_POST['totalPrice']); ?>" 
                       readonly required>
            </div>
            
            <button type="submit" class="btn-submit">Confirm Payment</button>
        </form>
    </div>

    <script src="paymentPortal.js"></script>
</body>
</html>