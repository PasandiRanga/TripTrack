<h2>Book Your Seat</h2>
    <form id="bookingForm" action="<?php echo URLROOT; ?>/<?php echo $userRole === 'RegisteredUser' ? 'RegisteredPages/paymentPortal' : 'GuestPages/paymentPortal'; ?>" method="post" onsubmit="return validateBookingForm()">
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

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const userRole = '<?php echo $userRole; ?>'; // Get user role from PHP
    
    // Function to update form action based on payment method
    function updateFormAction(paymentMethod) {
        if (paymentMethod === 'Cash') {
            // For cash payments, route to receipt pages
            if (userRole === 'RegisteredUser') {
                bookingForm.action = '<?php echo URLROOT; ?>/RegisteredPages/RegisteredReceipt';
            } else {
                bookingForm.action = '<?php echo URLROOT; ?>/GuestPages/GuestReceipt';
            }
        } else if (paymentMethod === 'Online') {
            // For online payments, route to payment portal
            if (userRole === 'RegisteredUser') {
                bookingForm.action = '<?php echo URLROOT; ?>/RegisteredPages/paymentPortal';
            } else {
                bookingForm.action = '<?php echo URLROOT; ?>/GuestPages/paymentPortal';
            }
        }
    }

    // Add event listeners to payment method radio buttons
    const paymentMethodInputs = document.querySelectorAll('input[name="paymentMethod"]');
    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateFormAction(this.value);
        });
    });

    // Update validateBookingForm to include form action check
    window.validateBookingForm = function() {
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

        // Update form action one final time before submission
        updateFormAction(paymentMethod.value);
        return true;
    };
});
</script>
    