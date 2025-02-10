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
}

?>