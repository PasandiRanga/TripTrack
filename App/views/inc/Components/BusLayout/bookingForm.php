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
            </div>
            <!-- Input email  -->
            <div>
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Email'])) ? htmlspecialchars($userData['Email']) : ''; ?>" required>
            </div>
        </div>

        <!-- Contact number and NIC  -->
        <div class="form-group">
            <!-- Input contact number  -->
            <div>
                <label for="contact">Contact No:</label>
                <input type="text" id="contact" name="contact" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Contact_number'])) ? htmlspecialchars($userData['Contact_number']) : ''; ?>" required>
            </div>
            <!-- Input NIC number  -->
            <div>
                <label for="nic">NIC No:</label>
                <input type="text" id="nic" name="nic" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['NIC'])) ? htmlspecialchars($userData['NIC']) : ''; ?>" required>
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
                <input type="radio" name="paymentMethod" value="Cash" required> Cash
                <input type="radio" name="paymentMethod" value="Online" required> Online
            </div>

            <input type="hidden" name="pricePerSeat" value="<?php echo htmlspecialchars($pricePerSeat); ?>">
            <input type="hidden" name="totalPrice" id="totalPriceInput" value="0" >
            
            <p><strong>Price per Seat:</strong>&nbsp;&nbsp; Rs.<span id="pricePerSeat" ><?php echo htmlspecialchars($pricePerSeat); ?></span></p>
            <p><strong>Total Price:</strong> &nbsp;&nbsp;Rs. <span id="total-price">0</span></p>
               
            <button type="submit" id="checkoutButton" class="checkout-button" disabled>Proceed to Checkout</button>  
            
    </form>
    <br>

    <div class="confirmBox hidden" id="confirmBox">
        <div class="confirmBoxContent">
            <h1>Are You Sure?</h1>
            <h4>You won't be able to cancel the booking later!</h4>
            <p>
                <button id="yes" onclick="confirmAction()">Proceed Without Login</button>
                <button id="no" onclick="closeConfirmBox()">Log In</button>
            </p>
            <div class="close-btn" onclick="confirmAction()">×</div>
        </div>
    </div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const userRole = '<?php echo $userRole; ?>';
    let selectedPaymentMethod = ''; // Store selected payment method

    // Show the login box on page load if required
    const showPopup = <?php echo isset($data['showPopup']) && $data['showPopup'] ? 'true' : 'false'; ?>;
    if (showPopup) {
        document.getElementById('signInBox').classList.remove('hidden');
    }

    // Function to show sign-in box
    function showSignInBox() {
        document.getElementById('signInBox').classList.remove('hidden');
    }

    // Function to close sign-in box
    function closeSignInBox() {
        document.getElementById('signInBox').classList.add('hidden');
    }

    // Function to confirm action when proceeding without login or closing the box
    window.confirmAction = function() {
        document.getElementById("confirmBox").classList.add("hidden");
        processBooking(selectedPaymentMethod);
    };

    // Function to close confirm box and show login box
    window.closeConfirmBox = function() {
        document.getElementById("confirmBox").classList.add("hidden");
        document.getElementById("signInBox").classList.remove("hidden");
    };

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
        if(paymentMethod === 'Cash'){
            bookingForm.action = '<?php echo URLROOT; ?>/GuestPages/GuestReceipt';
            bookingForm.submit();
        }else if (paymentMethod === 'Online'){
            bookingForm.action = '<?php echo URLROOT; ?>/GuestPages/paymentPortal';
            bookingForm.submit();
        }
    }

    // Update form submission handling
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        const from = document.getElementById("from").value;
        const to = document.getElementById("to").value;
        const selectedSeats = document.getElementById("selectedSeats").value;
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

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

        // Add the payment method as a hidden field
        let paymentInput = document.createElement("input");
        paymentInput.type = "hidden";
        paymentInput.name = "paymentMethod";
        paymentInput.value = paymentMethod.value;
        bookingForm.appendChild(paymentInput);

        // If updateFormAction returns true, submit the form directly
        // Otherwise, the confirmBox will be shown
        if (updateFormAction(paymentMethod.value)) {
            bookingForm.submit();
        }
    });
});

</script>
    