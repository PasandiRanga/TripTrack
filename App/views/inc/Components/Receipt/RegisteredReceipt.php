<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Receipt</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Receipt/receipt.css?v=<?php echo time(); ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>
<body>
    <div id="receipt-content" class="receipt-container">
        <h1>Booking Receipt</h1>
        
        <div class="details">
            <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($data['bookingID']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($data['bookingData']['name']); ?></p>
            <p><strong>User ID:</strong><?php echo htmlspecialchars($_SESSION['user_id']) ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($data['bookingData']['email']); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($data['bookingData']['contact']); ?></p>
            <p><strong>NIC:</strong> <?php echo htmlspecialchars($data['bookingData']['nic']); ?></p>
            <p><strong>From:</strong> <?php echo htmlspecialchars($data['bookingData']['from']); ?></p>
            <p><strong>To:</strong> <?php echo htmlspecialchars($data['bookingData']['to']); ?></p>
            <p><strong>Bus ID:</strong> <?php echo htmlspecialchars($data['bookingData']['License_id']); ?></p>
            <p><strong>Schedule ID:</strong> <?php echo htmlspecialchars($data['bookingData']['scheduleId']); ?></p>
            <p><strong>Number of Seats:</strong> <?php echo htmlspecialchars($data['bookingData']['noOfSeats']); ?></p>
            <p><strong>Seats:</strong> <?php echo htmlspecialchars(implode(', ', (array)$data['bookingData']['selectedSeats'])); ?></p>
            <p><strong>Total Price:</strong> Rs. <?php echo htmlspecialchars($data['bookingData']['totalPrice']); ?></p>
            <p><strong>Payment method :</strong><?php echo htmlspecialchars($data['bookingData']['paymentMethod']);?></p>
        </div>
        
        <div class="qr-code">
            <h2>Your QR Code</h2>
            <div class="qr-box" id="qrcode"></div>
        </div>
        <br/><br/>
        <button class="download no-print" onclick="downloadPDF()">Download PDF</button>
        <a href="#back" class="back-button" onClick="window.location.href='<?php echo URLROOT; ?>/RegisteredPages/home'">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <script>
        // Generate QR code
        var qrText = <?php echo json_encode($data['qrText']); ?>;
        new QRCode(document.getElementById("qrcode"), {
            text: qrText,
            width: 400,
            height: 400
        });

        // PDF download function
        function downloadPDF() {
            var downloadButton = document.querySelector('.download');
            downloadButton.style.display = 'none';
            
            var element = document.getElementById('receipt-content');
            html2pdf().from(element).save('Booking_Receipt.pdf');
            
            downloadButton.style.display = 'inline-block';
        }

    </script>
</body>
</html>