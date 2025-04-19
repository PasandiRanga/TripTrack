<!-- <?php
// Ensure all booking details are passed through
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs (similar to previous implementation)
    function processPayment($cardName, $cardNumber, $expiry, $cvv, $amount) {
        // Basic validation 
        if (empty($cardName) || empty($cardNumber) || empty($expiry) || empty($cvv) || empty($amount)) {
            return ['status' => 'error', 'message' => 'All fields are required'];
        }
        
        // Card number validation (Luhn algorithm)
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        $sum = 0;
        $isEven = false;
        
        for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
            $digit = (int)$cardNumber[$i];
            
            if ($isEven) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
            $isEven = !$isEven;
        }
        
        if ($sum % 10 !== 0) {
            return ['status' => 'error', 'message' => 'Invalid card number'];
        }
        
        // In a real scenario, you would integrate with a payment gateway
        return ['status' => 'success'];
    }
    
    // Process payment
    $result = processPayment(
        $_POST['cardName'], 
        $_POST['cardNumber'], 
        $_POST['expiry'], 
        $_POST['cvv'], 
        $_POST['amount']
    );
    
    // If payment successful, redirect to receipt
    if ($result['status'] === 'success') {
        // Prepare all booking details to pass to receipt
        $bookingDetails = $_POST;
        
        // Start session to pass details (alternative to hidden inputs)
        session_start();
        $_SESSION['bookingDetails'] = $bookingDetails;
        
        // Redirect to guest receipt page
        header('Location: GuestReceipt.php');
        exit();
    } else {
        // Handle payment failure
        echo "Payment Failed: " . $result['message'];
    }
}
?> -->