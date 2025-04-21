<h2>Book Your Seat</h2>
    <form id="bookingForm"  method="post" >
        <input type="hidden" name="License_id" value="<?php echo htmlspecialchars($selectedBus['License_id']); ?>">
        <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($selectedSchedule['scheduleId']); ?>">

        <!-- Name and email  -->
        <div class="form-group">
            <!-- Input name  -->
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Name'])) ? htmlspecialchars($userData['Name']) : ''; ?>" required>
                <div id="NameError" class="error-message">Name can only contain characters.</div>

            </div>
            <!-- Input email  -->
            <div>
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Email'])) ? htmlspecialchars($userData['Email']) : ''; ?>" required>
                <div id="EmailError" class="error-message">Please enter a valid email address</div>

            </div>
        </div>

        <!-- Contact number and NIC  -->
        <div class="form-group">
            <!-- Input contact number  -->
            <div>
                <label for="contact">Contact No:</label>
                <input type="text" id="contact" name="contact" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Contact_number'])) ? htmlspecialchars($userData['Contact_number']) : ''; ?>" required>
                <div id="ContactError" class="error-message">Please enter valid contact number</div>

            </div>
            <!-- Input NIC number  -->
            <div>
                <label for="nic">NIC No:</label>
                <input type="text" id="nic" name="nic" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['NIC'])) ? htmlspecialchars($userData['NIC']) : ''; ?>" required>
                <div id="ContactError" class="error-message">Please enter valid NIC number</div>
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
            

        <!-- Number of seats and selected seats  -->
        <div class="form-group">
            <!-- Number of seats input  -->
            <div>
                <label for="noOfseats">Number of seats:</label>
                <input type="number" id="noOfseats" name="noOfseats" min="1" step="1" value="0" readonly required>
            </div>
            <!-- Selected seats input  -->
            <div>
                <label for="selectedSeats">Selected seats:</label>
                <input type="text" id="selectedSeats" name="selectedSeats" required>
            </div>
        </div>
            
            <!-- Payment method  -->
            <div class="form-group-inline">
                <label>Payment method:</label>
                <?php if ($userRole === 'RegisteredUser'): ?>
                    <input type="radio" name="paymentMethod" value="Cash" required> Cash
                <?php endif; ?>
                <input type="radio" name="paymentMethod" value="Online" required> Online
            </div>

            <input type="hidden" name="pricePerSeat" value="<?php echo htmlspecialchars($pricePerSeat); ?>">
            <input type="hidden" name="totalPrice" id="totalPriceInput" value="0" >
            
            <p><strong>Price per Seat:</strong>&nbsp;&nbsp; Rs.<span id="pricePerSeat" ><?php echo htmlspecialchars($pricePerSeat); ?></span></p>
            <p><strong>Total Price:</strong> &nbsp;&nbsp;Rs. <span id="total-price">0</span></p>
               
            <button type="submit" id="checkoutButton" class="checkout-button" disabled>Proceed to Checkout</button>
            
            <?php
                // At the end of your bookingForm.php file, after the checkout button

                // Initialize penalty fee
                $penaltyFee = 0;

                // Check if the user has past bookings where they didn't arrive
                if ($userRole === 'RegisteredUser' && !empty($pastNotArrivedBookings)) {
                    foreach ($pastNotArrivedBookings as $booking) {
                        if (isset($booking['penalty_fee'])) {
                            $penaltyFee += floatval($booking['penalty_fee']);
                        }
                    }
                    
                    // Only show the penalty message if there actually is a penalty
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
                        
                        <style>
                            .penalty-notice {
                                background-color: #fff3cd;
                                color: #856404;
                                padding: 12px;
                                margin: 15px 0;
                                border-radius: 4px;
                                border-left: 4px solid #ffeeba;
                            }
                        </style>
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
    const emailInput = document.getElementById('email');
    const contactInput = document.getElementById('contact');
    const nicInput = document.getElementById('nic');
    const selectedSeatsInput = document.getElementById('selectedSeats');
    const noOfSeatsInput = document.getElementById('noOfseats');
    const checkoutButton = document.getElementById('checkoutButton');
    const fromSelect = document.getElementById('from');
    const toSelect = document.getElementById('to');

    // Get existing error elements or create new ones
    const nameError = document.getElementById('NameError');
    const emailError = document.getElementById('EmailError');
    const contactError = document.getElementById('ContactError');
    const nicError = document.getElementById('nicError') || createErrorElement('nic');
    const locationError = createErrorElement('from');
    
    // Ensure all error messages are initially hidden
    document.querySelectorAll('.error-message').forEach(error => {
        error.style.display = 'none';
    });

    // Create error message elements for fields that don't have them
    function createErrorElement(fieldId) {
        const field = document.getElementById(fieldId);
        const existingError = document.getElementById(fieldId + 'Error');
        
        if (existingError) {
            existingError.style.display = 'none';
            return existingError;
        }
        
        const errorDiv = document.createElement('div');
        errorDiv.id = fieldId + 'Error';
        errorDiv.className = 'error-message';
        errorDiv.style.display = 'none';
        
        if (field && field.parentNode) {
            field.parentNode.appendChild(errorDiv);
        }
        return errorDiv;
    }
    
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
        
        if (from === to && from !== '' && to !== '') {
            showError(fromSelect, locationError, 'Departure and arrival locations cannot be the same', true);
            return false;
        } else {
            showError(fromSelect, locationError, '', false);
            return true;
        }
    }

    function validateSeats() {
        return selectedSeatsInput.value.trim() !== '' && parseInt(noOfSeatsInput.value) > 0;
    }

    function validatePaymentMethod() {
        const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
        return Array.from(paymentMethods).some(radio => radio.checked);
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
    fromSelect.addEventListener('change', validateLocations);
    toSelect.addEventListener('change', validateLocations);

    // Event listener for payment method selection
    const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', validateForm);
    });

    // Add event listener to the form for handling input changes
    bookingForm.addEventListener('input', validateForm);
    
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
        
        if (!checkoutButton.disabled) {
            checkoutButton.style.opacity = '1';
        } else {
            checkoutButton.style.opacity = '0.6';
        }
    }

    // Run validation on page load to set initial button state
    validateForm();
    
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
        
        const from = fromSelect.value;
        const to = toSelect.value;
        const selectedSeats = selectedSeatsInput.value;
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

        // Perform full validation
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isContactValid = validateContact();
        const isNICValid = validateNIC();
        const areLocationsValid = validateLocations();
        
        // Check if all validations pass
        if (!isNameValid || !isEmailValid || !isContactValid || !isNICValid || !areLocationsValid) {
            return false;
        }

        if (from === to) {
            alert("The 'From' and 'To' locations cannot be the same.");
            return false;
        }

        if (!selectedSeats) {
            alert("Please select at least one seat.");
            return false;
        }

        if (!paymentMethod) {
            alert("Please select a payment method.");
            return false;
        }

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