<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancellation Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .receipt {
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .receipt-header h1 {
            color: #2d3748;
            margin-bottom: 5px;
        }
        .receipt-header p {
            color: #718096;
            margin: 0;
        }
        .receipt-body {
            margin-bottom: 30px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-label {
            font-weight: bold;
            color: #4a5568;
        }
        .detail-value {
            text-align: right;
        }
        .amount-highlight {
            font-weight: bold;
        }
        .fee-amount {
            color: #e53e3e;
        }
        .refund-amount {
            color: #38a169;
        }
        .policy-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .policy-section h3 {
            margin-top: 0;
            color: #2d3748;
        }
        .bank-details {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>Booking Cancellation Receipt</h1>
            <p>Receipt #: CR-2025-04-07-001</p>
            <p>Date: April 7, 2025</p>
        </div>
        
        <div class="receipt-body">
            <div class="detail-row">
                <span class="detail-label">Booking ID:</span>
                <span class="detail-value">BOK123456</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Route:</span>
                <span class="detail-value">Colombo to Kandy</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Original Booking Date:</span>
                <span class="detail-value">April 10, 2025</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Cancellation Date:</span>
                <span class="detail-value">April 7, 2025</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">Online</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Booking Price:</span>
                <span class="detail-value">LKR 5,000.00</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Cancellation Fee (10%):</span>
                <span class="detail-value amount-highlight fee-amount">LKR 500.00</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Refund Amount:</span>
                <span class="detail-value amount-highlight refund-amount">LKR 4,500.00</span>
            </div>
        </div>
        
        <div class="bank-details">
            <h3>Refund Details</h3>
            <div class="detail-row">
                <span class="detail-label">Account Holder Name:</span>
                <span class="detail-value">John Doe</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Bank Name:</span>
                <span class="detail-value">Commercial Bank</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Account Number:</span>
                <span class="detail-value">XXXX-XXXX-1234</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Branch:</span>
                <span class="detail-value">Colombo Main</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Refund Status:</span>
                <span class="detail-value">Processing (3-5 business days)</span>
            </div>
        </div>
        
        <div class="policy-section">
            <h3>Cancellation Policy</h3>
            <ul>
                <li>Cancellation 1 or more days before departure: 10% cancellation fee</li>
                <li>Cancellation within 24 hours of departure: 50% cancellation fee</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Thank you for using our service. For any inquiries regarding your cancellation or refund, please contact our customer support.</p>
            <p>Email: support@example.com | Phone: +94 11 234 5678</p>
        </div>
    </div>
</body>
</html>