<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'C:\xampp\htdocs\TripTrack\vendor\autoload.php';

    class GuestPages extends Controller {
        private $GuestpagesModel;
        
        public function __construct() {
            $this->GuestpagesModel = $this->model('M_GuestPages');
        }

        public function index() {
            echo "This is the index method";
        }

        public function about() {
            $this->view('pages/GuestUser/aboutus');
            
        }

        public function home() {
            $schedule = $this->GuestpagesModel->getSchedule();
            $bus = $this->GuestpagesModel->getBusDetails();
            $distance = $this->GuestpagesModel->getDistance();
            $route = $this->GuestpagesModel->getRoute();
            $averageRatings = [];   

            foreach ($schedule as $item) {
                $licenseId = $item['License_id'];
                $avg = $this->GuestpagesModel->getAverageRatings($licenseId);
                $averageRatings[$licenseId] = isset($avg['average_rate']) ? round($avg['average_rate'], 1) : 'No ratings';
            }

            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance,
                'route' => $route,
                'averageRatings' => $averageRatings,
            ];
            $this->view('pages/GuestUser/home', $data);
        }

        public function contact() {
            $this->view('pages/GuestUser/contactus');
        }
        
        public function busLayout() {
            $schedule = $this->GuestpagesModel->getSchedule();
            $bus = $this->GuestpagesModel->getBusDetails();
            $distance = $this->GuestpagesModel->getDistance();
            $route = $this->GuestpagesModel->getRoute();
            $distance = $this->GuestpagesModel->getDistance();
            $averageRatings = []; 
            
            foreach ($schedule as $item) {
                $licenseId = $item['License_id'];
                $avg = $this->GuestpagesModel->getAverageRatings($licenseId);
                $averageRatings[$licenseId] = isset($avg['average_rate']) ? round($avg['average_rate'], 1) : 'No ratings';
            }

            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance,
                'route' => $route,
                'averageRatings' => $averageRatings,
            ];
            $this->view('inc/Components/BusLayout/BusLayout', $data);
        }

        //Receipt for booking 
        public function GuestReceipt() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $bookingData = [
                    'License_id' => $_POST['License_id'] ?? 'Unknown Bus',
                    'scheduleId' => $_POST['scheduleId'] ?? 'Unknown Schedule',
                    'name' => $_POST['name'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'contact' => $_POST['contact'] ?? '',
                    'nic' => $_POST['nic'] ?? '',
                    'from' => $_POST['from'] ?? '',
                    'to' => $_POST['to'] ?? '',
                    'noOfSeats' => $_POST['noOfseats'] ?? '0',
                    'totalPrice' => $_POST['totalPrice'] ?? '0',
                    'selectedSeats' => $_POST['selectedSeats'] ?? [],
                    'paymentMethod' => $_POST['paymentMethod'] ?? [],
                    'selectedSeatsJSON' => json_encode($_POST['selectedSeats'] ?? []),
                    'qrCodeUrl' => ''
                ];
            

                try {
                    // Save booking details
                    $this->GuestpagesModel->createBooking($bookingData);
                    
                    // Update schedule seat availability
                    $this->GuestpagesModel->updateScheduleSeats($bookingData['scheduleId'], explode(', ', $bookingData['selectedSeats']));

                    // Generate QR Code text
                    $qrText = "Booking Receipt\n";
                    $qrText .= "Schedule ID: {$bookingData['scheduleId']}\n";
                    $qrText .= "Seats: {$bookingData['selectedSeats']}\n";
                    $qrText .= "Total Price: Rs. {$bookingData['totalPrice']}\n";
                    $qrText .= "Payment method: {$bookingData['paymentMethod']}\n";

                    // Generate QR Code and get its URL

                    $qrData = $this->generateQRCode("Booking Receipt\nSchedule ID: {$bookingData['scheduleId']}\nSeats: {$bookingData['selectedSeats']}\nTotal Price: Rs. {$bookingData['totalPrice']}\nPayment method: {$bookingData['paymentMethod']}");
            
                    // Add the QR code data to booking data
                    $bookingData['qrCodeUrl'] = $qrData['qrCodeUrl'];
                    $bookingData['qrCodeFilename'] = $qrData['qrCodeFilename'];

                    $bookingID = $this->GuestpagesModel->getBookingID($bookingData['email'], $bookingData['scheduleId'], $bookingData['selectedSeatsJSON']);
                    $bookingId = $bookingID['id'];

                    // Send booking confirmation email
                    $this->sendBookingEmail($bookingData);

                    // Load Receipt View
                    $this->view('inc/Components/Receipt/GuestReceipt', [
                        'bookingData' => $bookingData,
                        'qrText' => $qrText,
                        'bookingID' => $bookingId
                    ]);

                } catch (Exception $e) {
                    error_log("Booking error: " . $e->getMessage());
                    header('Location: ' . URLROOT . '/GuestPages/home?error=booking_failed');
                    exit();
                }
            } else {
                header('Location: ' . URLROOT . '/GuestPages/home');
                exit();
            }
        }

        private function generateQRCode($qrText) {
            require_once APPROOT . '/libraries/phpqrcode/qrlib.php'; // Adjust path as needed

            $qrDir = APPROOT . "/public/qrcodes/";
            
            // Ensure QR code directory exists
            if (!file_exists($qrDir)) {
                mkdir($qrDir, 0777, true);
            }

            $filename = "qr_" . time() . ".png"; // Unique filename
            $filePath = $qrDir . $filename;

            // Generate QR Code
            QRcode::png($qrText, $filePath, QR_ECLEVEL_L, 10);

            // Return QR Code URL
            return [
                'qrCodeUrl' => URLROOT . "/public/qrcodes/" . $filename,
                'qrCodeFilename' => $filename // Pass the filename as well
            ];
        }

        private function sendBookingEmail($bookingData) {
            $mail = new PHPMailer(true);

            echo '<script>console.log("Inside send email");</script>';

            $bookingID = $this->GuestpagesModel->getBookingID($bookingData['email'], $bookingData['scheduleId'], $bookingData['selectedSeatsJSON']);
         

            try {
                // SMTP Configuration using defined constants
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_EMAIL;
                $mail->Password   = SMTP_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = SMTP_PORT;

                // Email Headers
                $mail->setFrom(SMTP_EMAIL, 'TripTrack');
                $mail->addAddress($bookingData['email'], $bookingData['name']);

                // Attach the QR code image as inline image
                $qrCodePath = APPROOT . "/public/qrcodes/" . $bookingData['qrCodeFilename']; // Ensure you pass the filename too
                $mail->addEmbeddedImage($qrCodePath, 'qr_code_image', 'qr_code.png', 'base64', 'image/png');

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Your Booking Confirmation - TripTrack';
                $mail->Body = '
                <div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
                    <div style="text-align: center; background-color: #f8f8f8; padding: 10px; margin-bottom: 20px; border-radius: 3px;">
                        <h2 style="color: #2c3e50; margin: 0;">Booking Confirmation</h2>
                    </div>
                    <p>Dear ' . htmlspecialchars($bookingData['name']) . ',</p>
                    <p>Thank you for booking with TripTrack. Here are your booking details:</p>
                    
                    <table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd; margin: 20px 0;">
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Booking ID:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingID['id']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Name:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['name']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Email:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['email']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Contact:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['contact']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>NIC:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['nic']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>From:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['from']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>To:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['to']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Bus ID:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['License_id']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Schedule ID:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['scheduleId']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Number of Seats:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['noOfSeats']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Seats:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($bookingData['selectedSeats']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Total Price:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">Rs. ' . htmlspecialchars($bookingData['totalPrice']) . '</td></tr>
                    </table>

                    <h3>Your QR Code</h3>
                    <p>Scan the QR code below for your booking details:</p>
                    <img src="cid:qr_code_image" alt="QR Code" style="width: 200px; height: 200px;"/>


                    <p>We look forward to serving you.</p>
                    <p>Best regards,<br>TripTrack Team</p>
                </div>';

                $mail->send();
            } catch (Exception $e) {
                error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
            }
        }

        public function PaymentPortal(){
            $this->view('inc/Components/PaymentPortal/paymentPortal');
        }

        public function sendOTP() {
            header('Content-Type: application/json');
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
                return;
            }
            try {
                $email = isset($_POST['email']) ? $_POST['email'] : '';
                if (empty($email)) {
                    echo json_encode(['status' => 'error', 'message' => 'Email is required']);
                    return;
                }
                $otp = sprintf("%06d", mt_rand(100000, 999999));

                $_SESSION['email_otp'] = $otp;
                $_SESSION['email_otp_time'] = time();

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = SMTP_HOST; 
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_EMAIL; 
                $mail->Password = SMTP_PASSWORD; 
                $mail->SMTPSecure = 'tls'; 
                $mail->Port = SMTP_PORT;

                $mail->setFrom(SMTP_EMAIL, 'TripTrack OTP');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Your OTP code is: <strong>$otp</strong>. It will expire in 5 minutes.";

                if ($mail->send()) {
                    echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP']);
                }

            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Mail error: ' . $mail->ErrorInfo]);
            }
            exit;
        }

        public function GuestSignUp() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $enteredOTP = $_POST['entered_otp'] ?? '';
                $storedOTP = $_SESSION['email_otp'] ?? '';
                $otpTime = $_SESSION['email_otp_time'] ?? 0;
                
                if ($enteredOTP !== $storedOTP || (time() - $otpTime) > 900) {
                    $data['otp_err'] = 'Invalid or expired OTP';
                    return $this->view('GuestPages/home', $data);
                }
                
                unset($_SESSION['email_otp']);
                unset($_SESSION['email_otp_time']);
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
                $data = [
                    'profile_image'=>$_FILES['profile_image'],
                    'profile_image_name'=>time().'_'.$_FILES['profile_image']['name'],
                    'name' => trim($_POST['name']),
                    'number' => trim($_POST['number']),
                    'nic' => trim($_POST['nic']),
                    'address' => trim($_POST['address']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm' => trim($_POST['confirm']),

                    'profile_image_err'=>'',
                    'name_err' => '',
                    'number_err' => '',
                    'nic_err' => '',
                    'address_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_err' => ''
                ];
                // Validate the profile image
                if ($data['profile_image'] && $data['profile_image']['tmp_name']) {
                    if (!uploadImage($data['profile_image']['tmp_name'], $data['profile_image_name'], '/images/profileImages/')) {
                        $data['profile_image_err'] = 'Profile image uploading unsuccessful';
                    }
                } else {
                    $data['profile_image_name'] = './../../Public/images/profileImages/default.jpg'; // Replace with your actual default image filename, if applicable
                }
                // Validate the name
                if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter a name';
                }
                // Validate the contact number
                if (empty($data['number'])) {
                    $data['number_err'] = 'Please enter a contact number'; // Check if the field is empty
                } elseif (!ctype_digit($data['number'])) {
                    $data['number_err'] = 'The contact number must contain only numbers'; // Check if it contains only numeric characters
                } elseif (strlen($data['number']) !== 10) {
                    $data['number_err'] = 'The contact number must be exactly 10 digits long'; // Check if it is exactly 10 digits
                } elseif ($data['number'][0] !== '0') {
                    $data['number_err'] = 'The contact number must start with 0'; // Check if it starts with 0
                }
                // Validate the NIC
                if (empty($data['nic'])) {
                    $data['nic_err'] = 'Please enter a NIC';
                } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                    // Check if the NIC is either 12 digits or 11 digits followed by "V"
                    $data['nic_err'] = 'NIC must be exactly 12 digits or 9 digits followed by "V" at the end';
                }else {
                    if ($this->GuestpagesModel->findUserByNIC($data['nic'])) {
                        $data['nic_err'] = 'This NIC is already registered';
                    }
                }
                //Validate the Address
                if (empty($data['address'])) {
                    $data['address_err'] = 'Please enter an address';
                }
                // Validate Email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter an email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email format (e.g., abc@gmail.com)';
                } else {
                    if ($this->GuestpagesModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'This email is already registered';
                    }
                }

                // Validate password
                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter a password';
                } elseif (strlen($data['password']) < 8) {
                    // Check if the password is at least 8 characters long
                    $data['password_err'] = 'Password must be at least 8 characters long';
                } elseif (!preg_match('/[A-Z]/', $data['password'])) {
                    // Check if the password contains at least one uppercase letter
                    $data['password_err'] = 'Password must contain at least one uppercase letter';
                } elseif (!preg_match('/[a-z]/', $data['password'])) {
                    // Check if the password contains at least one lowercase letter
                    $data['password_err'] = 'Password must contain at least one lowercase letter';
                } elseif (!preg_match('/\d/', $data['password'])) {
                    // Check if the password contains at least one number
                    $data['password_err'] = 'Password must contain at least one number';
                } elseif (!preg_match('/[\W_]/', $data['password'])) {
                    // Check if the password contains at least one special character (symbol)
                    $data['password_err'] = 'Password must contain at least one special character';
                } elseif (empty($data['confirm'])) {
                    // Check if confirm password is empty
                    $data['confirm_err'] = 'Please confirm the password';
                } elseif ($data['password'] != $data['confirm']) {
                    // Check if the password and confirm password match
                    $data['confirm_err'] = 'Passwords do not match';
                }

                // Register the user if no errors are present
                if (empty($data['name_err']) && empty($data['number_err']) && empty($data['nic_err']) && empty($data['address_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_err']) && empty($data['profile_image_err'])) {
                    // Hash the password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    // Register the user
                    if ($this->GuestpagesModel->register($data)) {
                        header('Location: ' . URLROOT . '/GuestPages/home' );
                        exit();  
                    } else {
                        die('Something went wrong'); 
                    }
                } else {
                    $this->view('inc/Components/SignUp/signUp', $data);
                }
            } else {
                $data = [
                    'profile_image'=>'',
                    'profile_image_name'=>'',
                    'name' => '',
                    'number' => '',
                    'nic' => '',
                    'address' => '',
                    'email' => '',
                    'password' => '',
                    'confirm' => '',
                    'profile_image_err'=>'',
                    'name_err' => '',
                    'number_err' => '',
                    'nic_err' => '',
                    'address_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_err' => '',
                ];
                $this->view('inc/Components/SignUp/signUp', $data);
            }
        }

        public function Login() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'email_err' => '',
                    'password_err' => '',
                    'show_pop' => true
                ];
                // Validate email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter the email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email address';
                }
                // Validate password
                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter the password';
                }
                // Check for errors
                if (empty($data['email_err']) && empty($data['password_err'])) {
                    $loginResult = $this->GuestpagesModel->login($data['email'], $data['password']);
                    if (!empty($loginResult)) {
                        $loggedUser = $loginResult['user_data'];
                        $userTable = $loginResult['user_table'];
                        $this->createUserSession($loggedUser, $userTable);
                    } else {
                        $data['password_err'] = 'Invalid credentials';
                        $this->view('pages/GuestUser/home', $data);
                    }
                } else {
                    $this->view('pages/GuestUser/home', $data);
                }
            } else {
                $data = [
                    'email' => '',
                    'password' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'show_pop' => false
                ];
                $this->view('pages/GuestUser/home', $data);
            }
        }
        
        public function BusBooking() {
            $schedule = $this->GuestpagesModel->getSchedule();
            $bus = $this->GuestpagesModel->getBusDetails();
            $distance = $this->GuestpagesModel->getDistance();
            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance
            ];
            $this->view('pages/GuestUser/BusBooking', $data);
        }

        public function createUserSession($loggedUser, $userTable) {
            $_SESSION['user_id'] = $userTable === 'customer' ? $loggedUser['User_id'] : $loggedUser['employee_id'];
            $_SESSION['user_type'] = $userTable;
            $_SESSION['user_email'] = $userTable === 'customer' ? $loggedUser['Email'] : $loggedUser['email'];
            $_SESSION['user_name'] = $userTable === 'customer' ? $loggedUser['Name'] : $loggedUser['name'];
            // Determine redirect path based on user type
            switch ($userTable) {
                case 'customer':
                    $_SESSION['user_role'] = 'RegisteredUser';
                    $_SESSION['user_profile_image']=$loggedUser['Profile_image'];
                    header('Location: ' . URLROOT . '/RegisteredPages/home');
                    break;

                case 'employee':
                    if($loggedUser['role'] === 'Conductor' ){
                        $_SESSION['user_profile_image']=$loggedUser['Profile_pic'];
                        $_SESSION['user_role'] = $loggedUser['role'];
                        header('Location: ' . URLROOT . '/ConductorPages/home');
                        break;
                    }elseif($loggedUser['role'] === 'Driver'){
                        $_SESSION['user_profile_image']=$loggedUser['profile_pic'];
                        $_SESSION['user_role'] = $loggedUser['role'];
                        header('Location: ' . URLROOT . '/ConductorPages/home');
                        break;
                    }elseif($loggedUser['role'] == 'Admin'){
                        $_SESSION['user_role'] = $loggedUser['role'];
                        header('Location: ' . URLROOT . '/SuperAdminPages/home');
                        break;
                    }
                default:
                    // Default case if userTable is unexpected
                    header('Location: ' . URLROOT . '/GuestPages/login');
                    break;
            }

            exit();
        }

        public function logout(){
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_role']);
            unset($_SESSION['user_profile_image']);
            session_destroy();
            header('Location: ' . URLROOT . '/GuestPages/home');
        }

        public function isLoggedin(){
            if(isset($_SESSION['user_id'])){
                return true;
            }
            else{
                return false;
            }
        }  

        public function submitRequest() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'contactNo' => trim($_POST['phone']),
                    'message' => trim($_POST['message']),
                    'name_err' => '',
                    'email_err' => '',
                    'contactNo_err' => '',
                    'message_err' => ''     
                ];
                // Validate name
                if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter your name';
                }
                // Validate email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter your email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email format (e.g., abc@gmail.com)';
                }
                // Validate contact number
                if (empty($data['contactNo'])) {
                    $data['contactNo_err'] = 'Please enter your contact number';
                } elseif (!ctype_digit($data['contactNo'])) {
                    $data['contactNo_err'] = 'The contact number must contain only numbers';
                } elseif (strlen($data['contactNo']) !== 10) {
                    $data['contactNo_err'] = 'The contact number must be exactly 10 digits long';
                } elseif ($data['contactNo'][0] !== '0') {
                    $data['contactNo_err'] = 'The contact number must start with 0';
                }
                // Validate message
                if (empty($data['message'])) {
                    $data['message_err'] = 'Please enter a message';
                }
                // Validate inputs
                if (empty($data['name_err']) && empty($data['email_err']) && empty($data['contactNo_err']) && empty($data['message_err'])) {
                    // Save to database using model
                    if ($this->GuestpagesModel->addSupportRequest($data)) {
                        // Redirect on success
                        $_SESSION['success_message'] = "Your support request has been submitted successfully.";
                        header('Location: ' . URLROOT . '/GuestPages/contact?status=success');
                        exit();
                    } else {
                        // Handle database error
                        $data['error_message'] = "Something went wrong. Please try again.";
                        $this->view('pages/GuestUser/contactus', $data);
                    }
                } else {
                    // Reload view with errors
                    $this->view('pages/GuestUser/contactus', $data);
                }
            } else {
                // Redirect if accessed directly
                header('Location: ' . URLROOT . '/GuestPages/contact');
                exit();
            }
        }

        public function calculatePrice(){
            $distance = $this->GuestpagesModel->getDistance();
            $bus = $this->GuestpagesModel->getBusDetails();
            $data = [
                'distance' => $distance,
                'bus' => $bus
            ];
            $this->view('inc/Components/BusLayout/calculatePrice');
        }

        public function filterBusByDate() {
            if (!isset($_GET['date'])) {
                return;
            }
            $scheduleData = $this->GuestpagesModel->getScheduleByDate($_GET['date']);
            $busData = $this->GuestpagesModel->getBusDetails();
            $routeData = $this->GuestpagesModel->getRoute();

            $data = [
                'schedule' => $scheduleData,
                'bus' => $busData,
                'route' => $routeData,
                'currentController' => 'GuestPages',
                'currentMethod' => 'home',
            ];

            require APPROOT . '/views/inc/Components/BusCard/busCardGenerator.php';
        }

        public function forgotPassword() {
            $data = [
                'email' => '',
                'email_err' => ''
            ];
            
            $this->view('pages/GuestUser/forgotPassword', $data);
        }

        function flash($name = '', $message = '', $class = 'alert alert-success') {
            if (!empty($name)) {
                if (!empty($message) && empty($_SESSION[$name])) {
                    if (!empty($_SESSION[$name . '_class'])) {
                        unset($_SESSION[$name . '_class']);
                    }
                    if (!empty($_SESSION[$name])) {
                        unset($_SESSION[$name]);
                    }
                    $_SESSION[$name] = $message;
                    $_SESSION[$name . '_class'] = $class;
                } elseif (empty($message) && !empty($_SESSION[$name])) {
                    $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
                    echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
                    unset($_SESSION[$name]);
                    unset($_SESSION[$name . '_class']);
                }
            }
        }

        public function sendPasswordResetEmail($email, $resetLink) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_EMAIL;
                $mail->Password   = SMTP_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = SMTP_PORT;

                $mail->setFrom(SMTP_EMAIL, 'TripTrack');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'TripTrack - Password Reset Request';

                $mail->Body = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                    <h2 style="color: #2c3e50;">Password Reset Request</h2>
                    <p>Hi,</p>
                    <p>We received a request to reset your password for your TripTrack account.</p>
                    <p>Please click the button below to reset your password:</p>
                    <p style="text-align: center;">
                        <a href="' . $resetLink . '" style="background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Reset Password</a>
                    </p>
                    <p>This link will expire in 1 hour. If you didn’t request this, please ignore this email.</p>
                    <br>
                    <p>Regards,<br><strong>TripTrack Team</strong></p>
                </div>';

                $mail->send();
                return true;
            } catch (Exception $e) {
                error_log("Password reset email failed: " . $mail->ErrorInfo);
                return false;
            }
        }



        public function processForgotPassword() {

            ob_start();

            // Set header for JSON response
            header('Content-Type: application/json');
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
                
                // Init data
                $data = [
                    'email' => trim($_POST['email']),
                    'email_err' => '',
                    'success' => false,
                    'message' => ''
                ];
                
                // Validate Email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email';
                } elseif (!$this->GuestpagesModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'No account found with that email address';
                }
                
                // Make sure errors are empty
                if (empty($data['email_err'])) {
                    try {

                        // echo '<script>console.log("Found customer");</script>';
                
                        // Generate token
                        $token = bin2hex(random_bytes(32));
                        
                        // Hash token before storing
                        $hashed_token = password_hash($token, PASSWORD_DEFAULT);
                        
                        // Set expiry time (1 hour from now)
                        $expiry = date('Y-m-d H:i:s', time() + 3600);
                        
                        // Save token to database
                        if ($this->GuestpagesModel->setPasswordResetToken($data['email'], $hashed_token, $expiry)) {
                            // Send email with reset link
                            $reset_link = URLROOT . '/GuestPages/resetPassword/' . $token . '/' . urlencode($data['email']);
                            
                            $to = $data['email'];
                            $subject = "Password Reset Request";
                            $message = "Hello,\n\nYou have requested to reset your password. Please click the link below to reset your password:\n\n";
                            $message .= $reset_link . "\n\n";
                            $message .= "This link will expire in 1 hour.\n\n";
                            $message .= "If you didn't request this, please ignore this email.\n\n";
                            $message .= "Regards,\nYour Website Team";
                            $headers = "From: noreply@yourwebsite.com";
                            
                            $sent = $this->sendPasswordResetEmail($data['email'], $reset_link);

                            if ($sent) {
                                $data['success'] = true;
                                $data['message'] = 'Password reset link has been sent to your email';
                            } else {
                                $data['message'] = 'Failed to send password reset email. Please try again.';
                            }

                            
                        } else {
                            $data['message'] = 'Something went wrong with the database operation. Please try again.';
                        }
                    } catch (Exception $e) {
                        $data['message'] = 'System error. Please try again later.';
                        error_log($e->getMessage());
                        // You might want to log the error: error_log($e->getMessage());
                    }
                }

                ob_end_clean();
                
                // Return JSON response
                echo json_encode($data);
                ob_end_flush();

                return;

            } else {

                ob_end_clean();
                // Return error JSON for non-POST requests
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid request method'
                ]);
                return;
            }
        }

        


        // Reset password form
        public function resetPassword($token = null, $email = null) {
            if ($token === null || $email === null) {
                $this->flash('password_reset', 'Invalid password reset link', 'alert alert-danger');
                redirect('GuestPages/home');
            }
            
            $email = urldecode($email);
            
            // Check if token is valid and not expired
            $tokenData = $this->GuestpagesModel->checkResetToken($email);
            
            if (!$tokenData) {
                $this->flash('password_reset', 'Invalid or expired password reset link', 'alert alert-danger');
                redirect('GuestPages/home');
                return;
            }
            
            // Verify token
            if (!password_verify($token, $tokenData['token'])) {
                $this->flash('password_reset', 'Invalid password reset link', 'alert alert-danger');
                redirect('GuestPages/home');
                return;
            }
            
            // Check if token is expired
            if (strtotime($tokenData['expiry']) < time()) {
                $this->flash('password_reset', 'Your password reset link has expired', 'alert alert-danger');
                redirect('GuestPages/home');
                return;
            }
            
            $data = [
                'token' => $token,
                'email' => $email,
                'password' => '',
                'confirm_password' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            
            $this->view('pages/GuestUser/resetPassword', $data);
        }


        public function processResetPassword() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
                
                // Init data
                $data = [
                    'token' => trim($_POST['token']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                echo json_encode($_POST['email']);
            
                
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                    
                // Update password and clear reset token
                    if ($this->GuestpagesModel->resetPassword($data['email'], $data['password'])) {
                        $this->flash('password_reset', 'Your password has been reset successfully');
                        redirect('GuestPages/home');
                    } else {
                        $this->flash('password_reset', 'Something went wrong. Please try again.', 'alert alert-danger');
                        $this->view('GuestPages/resetPassword', $data);
                    }
                
            } else {
                redirect('GuestPages/resetPassword');
            }
        }

    }  

?>
