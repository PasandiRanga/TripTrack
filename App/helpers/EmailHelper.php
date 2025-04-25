<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class EmailHelper {
    public static function sendOTP($email, $otp) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_EMAIL;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;

            // Recipients
            $mail->setFrom(SMTP_EMAIL, 'TripTrack');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification OTP';
            $mail->Body = "Your OTP for email verification is: <b>{$otp}</b>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // public static function sendBookingReceipt($bookingData) {
    //     $mail = new PHPMailer(true);

    //     try {
    //         // Server settings
    //         $mail->isSMTP();
    //         $mail->Host = SMTP_HOST;
    //         $mail->SMTPAuth = true;
    //         $mail->Username = SMTP_EMAIL;
    //         $mail->Password = SMTP_PASSWORD;
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //         $mail->Port = SMTP_PORT;

    //         // Recipients
    //         $mail->setFrom(SMTP_EMAIL, 'TripTrack');
    //         $mail->addAddress($bookingData['email'], $bookingData['name']);

    //         // Content
    //         $mail->isHTML(true);
    //         $mail->Subject = 'Your Booking Receipt - TripTrack';
            
    //         // Start output buffering
    //         ob_start();
            
    //         // Extract booking data to make it available to the template
    //         extract($bookingData);
            
    //         // Convert selectedSeats array to string if necessary
    //         if (is_array($selectedSeats)) {
    //             $selectedSeats = implode(', ', $selectedSeats);
    //         }
            
    //         // Include the email template
    //         include 'views/inc/Components/bookingReceipt.php';
            
    //         // Get the buffered content
    //         $emailBody = ob_get_clean();

    //         $mail->Body = $emailBody;

    //         $mail->send();
    //         return true;
    //     } catch (Exception $e) {
    //         error_log("Failed to send booking receipt email: " . $e->getMessage());
    //         return false;
    //     }
    // }
}

?>