<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Emails/bookingReceipt.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h1>Booking Confirmation</h1>
        </div>
        
        <div class="details">
            <p><strong>Dear <?php echo htmlspecialchars($name); ?>,</strong></p>
            <p>Thank you for booking with TripTrack. Here are your booking details:</p>
            
            <p><strong>Booking Details:</strong></p>
            <p>Email: <?php echo htmlspecialchars($email); ?></p>
            <p>Contact: <?php echo htmlspecialchars($contact); ?></p>
            <p>NIC: <?php echo htmlspecialchars($nic); ?></p>
            <p>From: <?php echo htmlspecialchars($from); ?></p>
            <p>To: <?php echo htmlspecialchars($to); ?></p>
            <p>Bus ID: <?php echo htmlspecialchars($License_id); ?></p>
            <p>Schedule ID: <?php echo htmlspecialchars($scheduleId); ?></p>
            <p>Number of Seats: <?php echo htmlspecialchars($noOfSeats); ?></p>
            <p>Selected Seats: <?php echo htmlspecialchars(implode(', ', $selectedSeats)); ?></p>
            <p>Total Price: Rs. <?php echo htmlspecialchars($totalPrice); ?></p>
        </div>
        
        <div class="footer">
            <p>For any queries, please contact our support team.</p>
            <p>Thank you for choosing TripTrack!</p>
        </div>
    </div>
</body>
</html>
