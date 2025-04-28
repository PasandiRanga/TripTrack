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

            $formUserRole = ($_SESSION['user_role'] ?? 'GuestUser');
            echo("<script>console.log('User Role: $formUserRole');</script>");

            if ($formUserRole === 'GuestUser') {
                $userRole = 'GuestUser';
                $currentController = 'GuestPages';
            } elseif ($formUserRole === 'RegisteredUser') {
                $userRole = 'RegisteredUser';
                $currentController = 'RegisteredPages';
            } else {
                $userRole = 'GuestUser'; 
                $currentController = 'GuestPages';
            }

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
            <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($bookingId); ?>">
            <input type="hidden" name="cancellation_fee" value="<?php echo htmlspecialchars($cancellationFee); ?>">
            <input type="hidden" name="refund_amount" value="<?php echo htmlspecialchars($refundAmount); ?>">
            <input type="hidden" name="schedule_id" value="<?php echo htmlspecialchars($scheduleId); ?>">
        <?php else: ?>
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
                <div id="cardNameError" class="error-message">Please enter the cardholder name.</div>

            </div>
            
            <div class="form-group card-input-container">
                <label for="cardNumber">Card Number</label>
                <input type="text" id="cardNumber" name="cardNumber" maxlength="19" required>
                <div id="cardNumberError" class="error-message">Card number must be 16 digits and start with 4 or 5.</div>

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
                    <div id="expiryError" class="error-message">Please enter a valid expiry date (MM/YY) in the future.</div>
                </div>
                
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" maxlength="3" required>
                    <div id="cvvError" class="error-message">CVV must be 3 digits.</div>
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

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardNumberInput = document.getElementById('cardNumber');
            const expiryInput = document.getElementById('expiry');
            const cvvInput = document.getElementById('cvv');
            const cardNameInput = document.getElementById('cardName');
            
            const visaIcon = document.querySelector('.card-icon.visa');
            const mastercardIcon = document.querySelector('.card-icon.mastercard');
            const amexIcon = document.querySelector('.card-icon.amex');
            const form = document.getElementById('paymentForm');

            const cardNameError = document.getElementById('cardNameError');
            const cardNumberError = document.getElementById('cardNumberError');
            const expiryError = document.getElementById('expiryError');
            const cvvError = document.getElementById('cvvError');

            function showError(input, errorElement, show) {
                if (show) {
                    input.classList.add('invalid');
                    errorElement.classList.add('visible');
                } else {
                    input.classList.remove('invalid');
                    errorElement.classList.remove('visible');
                }
            }

            cardNumberInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                
                if (value.length > 0 && !['4', '5'].includes(value.charAt(0))) {
                    value = '';
                    showError(cardNumberInput, cardNumberError, true);
                } else {
                    showError(cardNumberInput, cardNumberError, false);
                }
                
                if (value.length > 16) {
                    value = value.slice(0, 16);
                }
                
                const formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');
                this.value = formattedValue;
                
                visaIcon.style.opacity = '0.3';
                mastercardIcon.style.opacity = '0.3';
                amexIcon.style.opacity = '0.3';
                
                if (value.charAt(0) === '4') {
                    visaIcon.style.opacity = '1';
                    document.getElementById('visa').checked = true;
                } else if (value.charAt(0) === '5') {
                    mastercardIcon.style.opacity = '1';
                    document.getElementById('mastercard').checked = true;
                }
                
                if (value.length > 0 && value.length !== 16) {
                    showError(cardNumberInput, cardNumberError, true);
                }
            });

            cardNumberInput.addEventListener('blur', function() {
                const value = this.value.replace(/\s/g, '');
                const isValid = (value.length === 16) && (['4', '5'].includes(value.charAt(0)));
                showError(cardNumberInput, cardNumberError, !isValid);
            });

            expiryInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                
                if (value.length > 0) {
                    let month = value.substring(0, 2);
                    if (month.length === 1 && parseInt(month) > 1) {
                        month = '0' + month;
                    } else if (parseInt(month) > 12) {
                        month = '12';
                    }
                    
                    if (value.length > 2) {
                        this.value = month + '/' + value.substring(2, 4);
                    } else {
                        this.value = month;
                    }
                }
            });

            expiryInput.addEventListener('blur', function() {
                const expiryValue = this.value;
                let isValid = true;
                
                if (expiryValue.length === 5) {
                    const [expMonth, expYear] = expiryValue.split('/');
                    const currentDate = new Date();
                    const currentYear = currentDate.getFullYear() % 100;
                    const currentMonth = currentDate.getMonth() + 1;
                    
                    const expMonthNum = parseInt(expMonth);
                    const expYearNum = parseInt(expYear);
                    
                    if (expYearNum < currentYear || (expYearNum === currentYear && expMonthNum < currentMonth)) {
                        isValid = false;
                    }
                } else {
                    isValid = false;
                }
                
                showError(expiryInput, expiryError, !isValid);
            });

            cvvInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '').substring(0, 3);
            });

            cvvInput.addEventListener('blur', function() {
                const isValid = this.value.length === 3;
                showError(cvvInput, cvvError, !isValid);
            });

            cardNameInput.addEventListener('blur', function() {
                const isValid = this.value.trim() !== '';
                showError(cardNameInput, cardNameError, !isValid);
            });

            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                const cardName = cardNameInput.value.trim();
                if (cardName === '') {
                    showError(cardNameInput, cardNameError, true);
                    isValid = false;
                }
                
                const cardNumber = cardNumberInput.value.replace(/\s/g, '');
                if (cardNumber.length !== 16 || !['4', '5'].includes(cardNumber.charAt(0))) {
                    showError(cardNumberInput, cardNumberError, true);
                    isValid = false;
                }
                
                const expiryValue = expiryInput.value;
                let expiryValid = true;
                
                if (expiryValue.length === 5) {
                    const [expMonth, expYear] = expiryValue.split('/');
                    const currentDate = new Date();
                    const currentYear = currentDate.getFullYear() % 100; 
                    const currentMonth = currentDate.getMonth() + 1; 
                    
                    const expMonthNum = parseInt(expMonth);
                    const expYearNum = parseInt(expYear);
                    
                    if (expYearNum < currentYear || (expYearNum === currentYear && expMonthNum < currentMonth)) {
                        expiryValid = false;
                    }
                } else {
                    expiryValid = false;
                }
                
                if (!expiryValid) {
                    showError(expiryInput, expiryError, true);
                    isValid = false;
                }
                
                if (cvvInput.value.length !== 3) {
                    showError(cvvInput, cvvError, true);
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>