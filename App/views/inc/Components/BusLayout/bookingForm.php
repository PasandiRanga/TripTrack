<h2>Book Your Seat</h2>
<form id="bookingForm" method="post" class="booking-form">
    <input type="hidden" name="License_id" value="<?php echo htmlspecialchars($selectedBus['License_id']); ?>">
    <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($selectedSchedule['scheduleId']); ?>">

    <!-- Name and email -->
    <div class="form-group">
        <!-- Input name -->
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Name'])) ? htmlspecialchars($userData['Name']) : ''; ?>" required>
            <div id="NameError" class="error-message">Name can only contain characters.</div>
        </div>
        <!-- Input email -->
        <div>
            <label for="Bookingemail">E-mail:</label>
            <input type="email" id="Bookingemail" name="email" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Email'])) ? htmlspecialchars($userData['Email']) : ''; ?>" required>
            <div id="EmailError" class="error-message">Please enter a valid email address</div>
        </div>
    </div>

    <!-- Contact number and NIC -->
    <div class="form-group">
        <!-- Input contact number -->
        <div>
            <label for="contact">Contact No:</label>
            <input type="text" id="contact" name="contact" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Contact_number'])) ? htmlspecialchars($userData['Contact_number']) : ''; ?>" required placeholder="10 digits">
            <div id="ContactError" class="error-message">Please enter valid contact number</div>
        </div>
        <!-- Input NIC number -->
        <div>
            <label for="nic">NIC No:</label>
            <input type="text" id="nic" name="nic" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['NIC'])) ? htmlspecialchars($userData['NIC']) : ''; ?>" required placeholder="9 digits + v or 12 digits">
            <div id="nicError" class="error-message">Please enter valid NIC number</div>
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
            <div id="fromError" class="error-message">Please select a departure location</div>
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
            <div id="toError" class="error-message">Please select an arrival location</div>
        </div>
    </div>

    <!-- Number of seats and selected seats -->
    <div class="form-group">
        <!-- Number of seats input -->
        <div>
            <label for="noOfseats">Number of seats:</label>
            <input type="number" id="noOfseats" name="noOfseats" min="1" step="1" value="0" readonly required>
            <div id="noOfseatsError" class="error-message">Please select at least one seat</div>
        </div>
        <!-- Selected seats input -->
        <div>
            <label for="selectedSeats">Selected seats:</label>
            <input type="text" id="selectedSeats" name="selectedSeats" readonly required>
            <div id="selectedSeatsError" class="error-message">Please select your seats</div>
        </div>
    </div>
        
    <!-- Payment method -->
    <div class="form-group-inline">
        <label>Payment method:</label>
        <?php if ($userRole === 'RegisteredUser'): ?>
            <input type="radio" id="cashPayment" name="paymentMethod" value="Cash" required> <label for="cashPayment" style="display: inline;">Cash</label>
        <?php endif; ?>
        <input type="radio" id="onlinePayment" name="paymentMethod" value="Online" required> <label for="onlinePayment" style="display: inline;">Online</label>
        <div id="paymentMethodError" class="error-message">Please select a payment method</div>
    </div>

    <input type="hidden" name="pricePerSeat" value="<?php echo htmlspecialchars($pricePerSeat); ?>">
    <input type="hidden" name="totalPrice" id="totalPriceInput" value="0">
    
    <div class="price-info">
        <p><strong>Price per Seat:</strong> Rs. <span id="pricePerSeat"><?php echo htmlspecialchars($pricePerSeat); ?></span></p>
        <p><strong>Total Price:</strong> Rs. <span id="total-price">0</span></p>
    </div>
       
    <button type="submit" id="checkoutButton" class="checkout-button" disabled>Proceed to Checkout</button>
    
    <?php
        // Penalty fee notification
        $penaltyFee = 0;

        if ($userRole === 'RegisteredUser' && !empty($pastNotArrivedBookings)) {
            foreach ($pastNotArrivedBookings as $booking) {
                if (isset($booking['penalty_fee'])) {
                    $penaltyFee += floatval($booking['penalty_fee']);
                }
            }
            
            if ($penaltyFee > 0) {
                ?>
                <div class="penalty-notice">
                    <strong>Notice:</strong> You have a penalty fee of Rs. <?php echo number_format($penaltyFee, 2); ?> 
                    for previous bookings where you did not arrive. This amount will be added to your total.
                </div>
                
                <script>
                    // Update the total price calculation to include the penalty fee
                    document.addEventListener('DOMContentLoaded', function() {
                        const penaltyFee = <?php echo $penaltyFee; ?>;
                        const originalUpdatePrice = updatePrice;
                        
                        // Override the updatePrice function to include penalty fee
                        window.updatePrice = function(from, to) {
                            // Call the original function
                            originalUpdatePrice(from, to);
                            
                            // Add penalty fee to total
                            const pricePerSeatElement = document.getElementById('pricePerSeat');
                            const totalPriceElement = document.getElementById('total-price');
                            const totalPriceInput = document.getElementById('totalPriceInput');
                            const noOfSeats = parseInt(document.getElementById('noOfseats').value) || 0;
                            
                            const pricePerSeat = parseFloat(pricePerSeatElement.textContent);
                            const seatTotal = pricePerSeat * noOfSeats;
                            const grandTotal = seatTotal + penaltyFee;
                            
                            // Update the display and hidden input
                            totalPriceElement.textContent = grandTotal.toFixed(2);
                            totalPriceInput.value = grandTotal.toFixed(2);
                        };
                        
                        // Add hidden input for penalty fee
                        const penaltyInput = document.createElement('input');
                        penaltyInput.type = 'hidden';
                        penaltyInput.name = 'penaltyFee';
                        penaltyInput.value = penaltyFee;
                        document.getElementById('bookingForm').appendChild(penaltyInput);
                        
                        // Trigger price update if from and to are already selected
                        const from = document.getElementById('from').value;
                        const to = document.getElementById('to').value;
                        if (from && to) {
                            updatePrice(from, to);
                        }
                    });
                </script>
                <?php
            }
        }
    ?>
</form>
<br>

<div class="confirmBox hidden" id="confirmBox">
    <div class="confirmBoxContent">
        <h1>Are You Sure?</h1>
        <h4>Login to access Cash Payments and Booking Cancellations</h4>
        <p>
            <button id="yes" onclick="confirmAction()">Proceed Without Login</button>
            <button id="no" onclick="closeConfirmBoxandLogin()">Log In</button>
        </p>
        <div class="close-btn" onclick="closeConfirmBox()">×</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const userRole = '<?php echo $userRole; ?>';
    let selectedPaymentMethod = ''; 
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('Bookingemail');
    const contactInput = document.getElementById('contact');
    const nicInput = document.getElementById('nic');
    const selectedSeatsInput = document.getElementById('selectedSeats');
    const noOfSeatsInput = document.getElementById('noOfseats');
    const checkoutButton = document.getElementById('checkoutButton');
    const fromSelect = document.getElementById('from');
    const toSelect = document.getElementById('to');
    const numofseats = document.getElementById('noOfseats')

    // Get all error elements
    const nameError = document.getElementById('NameError');
    const emailError = document.getElementById('EmailError');
    const contactError = document.getElementById('ContactError');
    const nicError = document.getElementById('nicError');
    const fromError = document.getElementById('fromError');
    const toError = document.getElementById('toError');
    const noOfSeatsError = document.getElementById('noOfseatsError');
    const selectedSeatsError = document.getElementById('selectedSeatsError');
    const paymentMethodError = document.getElementById('paymentMethodError');
    
    // Ensure all error messages are initially hidden
    document.querySelectorAll('.error-message').forEach(error => {
        error.style.display = 'none';
    });

    // Function to show/hide error message
    function showError(inputElement, errorElement, message, isError) {
        if (isError) {
            inputElement.classList.add('input-error');
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        } else {
            inputElement.classList.remove('input-error');
            errorElement.style.display = 'none';
        }
    }

    // Validation functions
    function validateName() {
        const name = nameInput.value.trim();
        const nameRegex = /^[a-zA-Z\s]+$/;
        
        if (name === '') {
            showError(nameInput, nameError, 'Name is required', true);
            return false;
        } else if (!nameRegex.test(name)) {
            showError(nameInput, nameError, 'Name should only contain letters and spaces', true);
            return false;
        } else {
            showError(nameInput, nameError, '', false);
            return true;
        }
    }

    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email === '') {
            showError(emailInput, emailError, 'Email is required', true);
            return false;
        } else if (!emailRegex.test(email)) {
            showError(emailInput, emailError, 'Please enter a valid email address', true);
            return false;
        } else {
            showError(emailInput, emailError, '', false);
            return true;
        }
    }

    function validateContact() {
        const contact = contactInput.value.trim();
        const contactRegex = /^\d{10}$/;
        
        if (contact === '') {
            showError(contactInput, contactError, 'Contact number is required', true);
            return false;
        } else if (!contactRegex.test(contact)) {
            showError(contactInput, contactError, 'Contact number must be exactly 10 digits', true);
            return false;
        } else {
            showError(contactInput, contactError, '', false);
            return true;
        }
    }

    function validateNIC() {
        const nic = nicInput.value.trim();
        // NIC regex for either 9 digits followed by 'v' or 'V', or exactly 12 digits
        const nicRegex = /^(\d{9}[vV]|\d{12})$/;
        
        if (nic === '') {
            showError(nicInput, nicError, 'NIC number is required', true);
            return false;
        } else if (!nicRegex.test(nic)) {
            showError(nicInput, nicError, 'NIC must be either 9 digits followed by v or 12 digits', true);
            return false;
        } else {
            showError(nicInput, nicError, '', false);
            return true;
        }
    }

    function validateLocations() {
        const from = fromSelect.value;
        const to = toSelect.value;
        
        if (from === '') {
            showError(fromSelect, fromError, 'Please select a departure location', true);
            return false;
        } else {
            showError(fromSelect, fromError, '', false);
        }
        
        if (to === '') {
            showError(toSelect, toError, 'Please select an arrival location', true);
            return false;
        } else {
            showError(toSelect, toError, '', false);
        }
        
        if (from === to && from !== '') {
            showError(fromSelect, fromError, 'Departure and arrival locations cannot be the same', true);
            return false;
        }
        
        return true;
    }

    function validateSeats() {
        const seatsSelected = selectedSeatsInput.value.trim() !== '';
        const seatCount = parseInt(noOfSeatsInput.value) > 0;
        
        if (!seatsSelected) {
            showError(selectedSeatsInput, selectedSeatsError, 'Please select your seats', true);
        } else {
            showError(selectedSeatsInput, selectedSeatsError, '', false);
        }
        
        if (!seatCount) {
            showError(noOfSeatsInput, noOfSeatsError, 'Please select at least one seat', true);
        } else {
            showError(noOfSeatsInput, noOfSeatsError, '', false);
        }
        
        return seatsSelected && seatCount;
    }

    function validatePaymentMethod() {
        const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
        const isSelected = Array.from(paymentMethods).some(radio => radio.checked);
        
        if (!isSelected) {
            paymentMethodError.style.display = 'block';
        } else {
            paymentMethodError.style.display = 'none';
        }
        
        return isSelected;
    }

    // Format input as it's being typed
    contactInput.addEventListener('input', function() {
        // Remove non-digit characters and limit to 10 digits
        this.value = this.value.replace(/\D/g, '').substring(0, 10);
        validateContact();
    });

    nicInput.addEventListener('input', function() {
        // Allow only digits and 'v' or 'V', limit to appropriate length
        let value = this.value.replace(/[^0-9vV]/g, '');
        
        // If last character is 'v' or 'V', ensure it's at position 10
        if (/[vV]/.test(value.charAt(value.length - 1)) && value.length > 10) {
            const digits = value.replace(/[vV]/g, '');
            value = digits.substring(0, 9) + value.charAt(value.length - 1);
        } else if (!/[vV]/.test(value) && value.length > 12) {
            value = value.substring(0, 12);
        }
        
        this.value = value;
        validateNIC();
    });

    // Add event listeners for validation
    nameInput.addEventListener('input', validateName);
    emailInput.addEventListener('input', validateEmail);
    fromSelect.addEventListener('change', function() {
        validateLocations();
        // validateForm();
    });
    toSelect.addEventListener('change', function() {
        validateLocations();
        // validateForm();
    });

    numofseats.addEventListener('change', function() {
        validateSeats();
    });

    // Event listener for payment method selection
    const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            validatePaymentMethod();
            validateForm();
        });
    });

    // Add event listener to the form for handling input changes
    // bookingForm.addEventListener('input', validateForm);
    
    // Function to validate the entire form
    function validateForm() {
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isContactValid = validateContact();
        const isNICValid = validateNIC();
        const areLocationsValid = validateLocations();
        const areSeatsValid = validateSeats();
        const isPaymentMethodValid = validatePaymentMethod();
        
        // Enable checkout button only if all validations pass
        checkoutButton.disabled = !(
            isNameValid && 
            isEmailValid && 
            isContactValid && 
            isNICValid && 
            areLocationsValid && 
            areSeatsValid && 
            isPaymentMethodValid
        );
        
        checkoutButton.style.opacity = checkoutButton.disabled ? '0.6' : '1';
    }

    // Run validation on page load to set initial button state
    // validateForm();
    
    // Handle payment method logic
    function updateFormAction(paymentMethod) {
        if (paymentMethod === 'Cash') {
            if (userRole === 'RegisteredUser') {
                bookingForm.action = '<?php echo URLROOT; ?>/RegisteredPages/RegisteredReceipt';
                return true;
            } else {
                selectedPaymentMethod = paymentMethod;
                document.getElementById("confirmBox").classList.remove("hidden");
                return false;
            }
        } else if (paymentMethod === 'Online') {
            if (userRole === 'RegisteredUser') {
                bookingForm.action = '<?php echo URLROOT; ?>/RegisteredPages/paymentPortal';
                return true;
            } else {
                selectedPaymentMethod = paymentMethod;
                document.getElementById("confirmBox").classList.remove("hidden");
                return false;
            }
        }
    }

    function processBooking(paymentMethod) {
        bookingForm.action = '<?php echo URLROOT; ?>/GuestPages/paymentPortal';
        bookingForm.submit();
    }

    // Window functions for confirm box
    window.confirmAction = function() {
        document.getElementById("confirmBox").classList.add("hidden");
        processBooking(selectedPaymentMethod);
    };

    window.closeConfirmBoxandLogin = function() {
        document.getElementById("confirmBox").classList.add("hidden");
        if (document.getElementById("signInBox")) {
            document.getElementById("signInBox").classList.remove("hidden");
        }
    };

    window.closeConfirmBox = function() {
        document.getElementById("confirmBox").classList.add("hidden");
    };

    // Show the login box on page load if required
    const showPopup = <?php echo isset($data['showPopup']) && $data['showPopup'] ? 'true' : 'false'; ?>;
    if (showPopup && document.getElementById('signInBox')) {
        document.getElementById('signInBox').classList.remove('hidden');
    }

    // Update form submission handling
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Perform full validation
        const isValid = validateName() && 
                        validateEmail() && 
                        validateContact() && 
                        validateNIC() && 
                        validateLocations() && 
                        validateSeats() && 
                        validatePaymentMethod();
        
        if (!isValid) {
            return false;
        }

        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
        
        // If updateFormAction returns true, submit the form directly
        // Otherwise, the confirmBox will be shown
        if (updateFormAction(paymentMethod.value)) {
            bookingForm.submit();
        }
    });

    // Initialize price updating function (if not defined elsewhere)
    if (typeof updatePrice !== 'function') {
        window.updatePrice = function(from, to) {
            const pricePerSeatElement = document.getElementById('pricePerSeat');
            const totalPriceElement = document.getElementById('total-price');
            const totalPriceInput = document.getElementById('totalPriceInput');
            const noOfSeats = parseInt(document.getElementById('noOfseats').value) || 0;
            
            const pricePerSeat = parseFloat(pricePerSeatElement.textContent);
            const total = pricePerSeat * noOfSeats;
            
            totalPriceElement.textContent = total.toFixed(2);
            totalPriceInput.value = total.toFixed(2);
        };
    }
});
</script>