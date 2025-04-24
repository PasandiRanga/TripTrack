<h2>Book Your Seat</h2>
<form id="bookingForm" method="post" class="booking-form" 
      data-user-role="<?php echo $userRole; ?>" 
      data-urlroot="<?php echo URLROOT; ?>"
      data-penalty-fee="<?php echo ($userRole === 'RegisteredUser' && !empty($pastNotArrivedBookings)) ? array_sum(array_column($pastNotArrivedBookings, 'penalty_fee')) : 0; ?>"
      data-show-popup="<?php echo isset($data['showPopup']) && $data['showPopup'] ? 'true' : 'false'; ?>">
    <input type="hidden" name="License_id" value="<?php echo htmlspecialchars($selectedBus['License_id']); ?>">
    <input type="hidden" name="scheduleId" value="<?php echo htmlspecialchars($selectedSchedule['scheduleId']); ?>">
    <input type="hidden" name="penaltyFee" id="penaltyFeeInput" value="<?php echo ($userRole === 'RegisteredUser' && !empty($pastNotArrivedBookings)) ? array_sum(array_column($pastNotArrivedBookings, 'penalty_fee')) : 0; ?>">

    <!-- Name and email -->
    <div class="form-group">
        <!-- Input name -->
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo ($userRole === 'RegisteredUser' && isset($userData['Name'])) ? htmlspecialchars($userData['Name']) : ''; ?>" required>
            <div id="NameError" class="error-message"></div>
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
            <input type="radio" id="cashPayment" name="paymentMethod" value="Cash"> <label for="cashPayment" style="display: inline;">Cash</label>
        <?php endif; ?>
        <input type="radio" id="onlinePayment" name="paymentMethod" value="Online"> <label for="onlinePayment" style="display: inline;">Online</label>
        <div id="paymentMethodError" class="error-message">Please select a payment method</div>
    </div>

    <input type="hidden" name="pricePerSeat" value="<?php echo htmlspecialchars($pricePerSeat); ?>">
    <input type="hidden" name="totalPrice" id="totalPriceInput" value="0">
    
    <div class="price-info">
        <p><strong>Price per Seat:</strong> Rs. <span id="pricePerSeat"><?php echo htmlspecialchars($pricePerSeat); ?></span></p>
        <p><strong>Total Price:</strong> Rs. <span id="total-price">0</span></p>
    </div>
       
    <button type="submit" id="checkoutButton" class="checkout-button" >Proceed to Checkout</button>
    
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

<!-- Include the external JavaScript file -->
