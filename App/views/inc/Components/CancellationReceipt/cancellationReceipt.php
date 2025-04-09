<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['RegisteredUser'])
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/CancellationReceipt/CancellationReceipt.css?v=<?php echo time(); ?>">

    <title>Booking Cancellation Receipt</title>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>
<?php
    $bookingId = $data['booking_id'];
    $cancellationFee = $data['cancellation_fee'];
    $refundAmount = $data['refund_amount'];
    $bankDetails = $data['bankDetails'] ?? [];
    $cancellationData = $data['cancellationData'];
    echo("<script>console.log('Booking id: $bookingId');</script>");
    echo("<script>console.log('Cancellatio Fee: $cancellationFee');</script>");
    echo("<script>console.log('Refund Amount: $refundAmount');</script>");
   
?>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>Booking Cancellation Receipt</h1>
            <!-- <p>Receipt #: CR-2025-04-07-001</p> -->
            <p>Date: <?php echo $cancellationData['time_date']; ?></p>
        </div>
        
        <div class="receipt-body">
            <div class="detail-row">
                <span class="detail-label">Booking ID:</span>
                <span class="detail-value"><?php echo $cancellationData['id'];?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Route:</span>
                <span class="detail-value"><?php echo $cancellationData['from_location']; ?> to <?php echo $cancellationData['to_location']?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Original Booking Date:</span>
                <span class="detail-value"><?php echo $cancellationData['Booking_date'];?></span>
            </div>
            <!-- <div class="detail-row">
                <span class="detail-label">Cancellation Date:</span>
                <span class="detail-value">April 7, 2025</span>
            </div> -->
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value"><?php echo $cancellationData['paymentMethod'];?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Booking Price:</span>
                <span class="detail-value"><?php echo $cancellationData['total_price']?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Cancellation Fee :</span>
                <span class="detail-value amount-highlight fee-amount"><?php echo $cancellationData['cancellation_fee']?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Refund Amount:</span>
                <span class="detail-value amount-highlight refund-amount"><?php echo $cancellationData['refund_amount']?></span>
            </div>
        </div>
        
        <?php if($cancellationData['paymentMethod'] === 'Online'): ?>

            <div class="bank-details">
                <h3>Refund Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Account Holder Name:</span>
                    <span class="detail-value"><?php echo $cancellationData['account_name']?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Bank Name:</span>
                    <span class="detail-value"><?php echo $cancellationData['bank_name']?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account Number:</span>
                    <span class="detail-value"><?php echo $cancellationData['account_number']?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Branch:</span>
                    <span class="detail-value"><?php echo $cancellationData['branch_name']?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Refund Status:</span>
                    <span class="detail-value">Processing (3-5 business days)</span>
                </div>
            </div>

        <?php endif; ?>     
        
        <div class="policy-section">
            <h3>Cancellation Policy</h3>
            <ul>
                <li>Cancellation 1 or more days before departure: 10% cancellation fee</li>
                <li>Cancellation within 24 hours of departure: 50% cancellation fee</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Thank you for using our service. For any inquiries regarding your cancellation or refund, please contact our customer support.</p>
            <p>Email: support@triptrack.com | Phone: +94 11 234 5678</p>
        </div>
    </div>
    
    <button class="download-btn" onclick="downloadPDF()">Download Receipt</button>
    <a href="#back" class="back-button" onClick="window.location.href='<?php echo URLROOT; ?>/RegisteredPages/home'">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>

    <script>
        function downloadPDF() {
            var downloadButton = document.querySelector('.download-btn');
            downloadButton.style.display = 'none';
            
            var element = document.querySelector('.receipt'); // Changed from getElementById('receipt-content')
            html2pdf().from(element).save('Booking_Receipt.pdf');
            
            downloadButton.style.display = 'block';
        }
    </script>

</body>
</html>