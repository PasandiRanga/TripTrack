<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/OTP/otp.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f4f4f4;
        }

        .otp-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .otp-box h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .otp-box p {
            font-size: 1rem;
            color: #777;
            margin-bottom: 20px;
        }

        .otpverify input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #43cea2;
            border-radius: 5px;
            text-align: center;
        }

        .verify-button {
            width: 100%;
            background: linear-gradient(135deg, #43cea2, #185a9d);
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 15px;
        }

        .verify-button:hover {
            background: linear-gradient(135deg, #185a9d, #43cea2);
        }

        .resend-otp p {
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .resend-otp a {
            color: #185a9d;
            font-weight: bold;
            text-decoration: none;
        }

        .resend-otp a:hover {
            text-decoration: underline;
        }

        .form-invalid {
            color: red;
            font-size: 0.875rem;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="otp-container zoom-in">
        <div class="otp-box">
            <h2>Verify Your Email</h2>
            <p>A 6-digit OTP has been sent to your email. Please enter it below to verify your account.</p>
            
            <form action="<?php echo URLROOT ?>/GuestPages/VerifyOTP" method="POST">
                <div class="otpverify">
                    <input type="text" name="otp" id="otp" maxlength="6" placeholder="Enter OTP" required>
                    <span class="form-invalid"><?php echo isset($data['otp_err']) ? $data['otp_err'] : ''; ?></span>
                </div>
                <button type="submit" class="verify-button">Verify</button>
            </form>
            
            <div class="resend-otp">
                <p>Didn't receive an OTP? <a href="<?php echo URLROOT ?>/GuestPages/ResendOTP">Resend OTP</a></p>
            </div>
        </div>
    </div>
</body>
</html>
