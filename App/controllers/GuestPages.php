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
                    $this->GuestpagesModel->createBooking($bookingData);
                    
                    $this->GuestpagesModel->updateScheduleSeats($bookingData['scheduleId'], explode(', ', $bookingData['selectedSeats']));

                    $qrText = "Booking Receipt\n";
                    $qrText .= "Schedule ID: {$bookingData['scheduleId']}\n";
                    $qrText .= "Seats: {$bookingData['selectedSeats']}\n";
                    $qrText .= "Total Price: Rs. {$bookingData['totalPrice']}\n";
                    $qrText .= "Payment method: {$bookingData['paymentMethod']}\n";


                    $qrData = $this->generateQRCode("Booking Receipt\nSchedule ID: {$bookingData['scheduleId']}\nSeats: {$bookingData['selectedSeats']}\nTotal Price: Rs. {$bookingData['totalPrice']}\nPayment method: {$bookingData['paymentMethod']}");
            
                    $bookingData['qrCodeUrl'] = $qrData['qrCodeUrl'];
                    $bookingData['qrCodeFilename'] = $qrData['qrCodeFilename'];

                    $bookingID = $this->GuestpagesModel->getBookingID($bookingData['email'], $bookingData['scheduleId'], $bookingData['selectedSeatsJSON']);
                    $bookingId = $bookingID['id'];

                    $this->sendBookingEmail($bookingData);

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
            require_once APPROOT . '/libraries/phpqrcode/qrlib.php'; 

            $qrDir = APPROOT . "/public/qrcodes/";
            
            if (!file_exists($qrDir)) {
                mkdir($qrDir, 0777, true);
            }

            $filename = "qr_" . time() . ".png"; 
            $filePath = $qrDir . $filename;

            QRcode::png($qrText, $filePath, QR_ECLEVEL_L, 10);

            return [
                'qrCodeUrl' => URLROOT . "/public/qrcodes/" . $filename,
                'qrCodeFilename' => $filename 
            ];
        }

        private function sendBookingEmail($bookingData) {
            $mail = new PHPMailer(true);

            echo '<script>console.log("Inside send email");</script>';

            $bookingID = $this->GuestpagesModel->getBookingID($bookingData['email'], $bookingData['scheduleId'], $bookingData['selectedSeatsJSON']);

            try {
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_EMAIL;
                $mail->Password   = SMTP_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = SMTP_PORT;

                $mail->setFrom(SMTP_EMAIL, 'TripTrack');
                $mail->addAddress($bookingData['email'], $bookingData['name']);

                $qrCodePath = APPROOT . "/public/qrcodes/" . $bookingData['qrCodeFilename']; 
                $mail->addEmbeddedImage($qrCodePath, 'qr_code_image', 'qr_code.png', 'base64', 'image/png');

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
                    'confirm_err' => '',
                    'otp_err' => ''

                ];

                // Check OTP if it's provided
                $enteredOTP = $_POST['entered_otp'] ?? '';
                
                if (!empty($enteredOTP)) {
                    $storedOTP = $_SESSION['email_otp'] ?? '';
                    $otpTime = $_SESSION['email_otp_time'] ?? 0;
                    
                    if ($enteredOTP !== $storedOTP || (time() - $otpTime) > 900) {
                        $data['otp_err'] = 'Invalid or expired OTP';
                        return $this->view('inc/Components/SignUp/signUp', $data);
                    }
                    
                    // OTP is valid, proceed with registration
                    unset($_SESSION['email_otp']);
                    unset($_SESSION['email_otp_time']);
                }

                if ($data['profile_image'] && $data['profile_image']['tmp_name']) {
                    if (!uploadImage($data['profile_image']['tmp_name'], $data['profile_image_name'], '/images/profileImages/')) {
                        $data['profile_image_err'] = 'Profile image uploading unsuccessful';
                    }
                } else {
                    $data['profile_image_name'] = './../../Public/images/profileImages/default.jpg'; 
                    $data['profile_image_name'] = './../../Public/images/profileImages/default.jpg'; 
                }

                if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter a name';
                }

                if (empty($data['number'])) {
                    $data['number_err'] = 'Please enter a contact number'; 
                    $data['number_err'] = 'Please enter a contact number'; 
                } elseif (!ctype_digit($data['number'])) {
                    $data['number_err'] = 'The contact number must contain only numbers'; 
                    $data['number_err'] = 'The contact number must contain only numbers'; 
                } elseif (strlen($data['number']) !== 10) {
                    $data['number_err'] = 'The contact number must be exactly 10 digits long'; 
                    $data['number_err'] = 'The contact number must be exactly 10 digits long'; 
                } elseif ($data['number'][0] !== '0') {
                    $data['number_err'] = 'The contact number must start with 0'; 
                    $data['number_err'] = 'The contact number must start with 0'; 
                }

                if (empty($data['nic'])) {
                    $data['nic_err'] = 'Please enter a NIC';
                } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                    $data['nic_err'] = 'NIC must be exactly 12 digits or 9 digits followed by "V" at the end';
                }else {
                    if ($this->GuestpagesModel->findUserByNIC($data['nic'])) {
                        $data['nic_err'] = 'This NIC is already registered';
                    }
                }

                if (empty($data['address'])) {
                    $data['address_err'] = 'Please enter an address';
                }

                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter an email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email format (e.g., abc@gmail.com)';
                } else {
                    if ($this->GuestpagesModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'This email is already registered';
                    }
                }

                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter a password';
                } elseif (strlen($data['password']) < 8) {
                    $data['password_err'] = 'Password must be at least 8 characters long';
                } elseif (!preg_match('/[A-Z]/', $data['password'])) {
                    $data['password_err'] = 'Password must contain at least one uppercase letter';
                } elseif (!preg_match('/[a-z]/', $data['password'])) {
                    $data['password_err'] = 'Password must contain at least one lowercase letter';
                } elseif (!preg_match('/\d/', $data['password'])) {
                    $data['password_err'] = 'Password must contain at least one number';
                } elseif (!preg_match('/[\W_]/', $data['password'])) {
                    $data['password_err'] = 'Password must contain at least one special character';
                } elseif (empty($data['confirm'])) {
                    $data['confirm_err'] = 'Please confirm the password';
                } elseif ($data['password'] != $data['confirm']) {
                    $data['confirm_err'] = 'Passwords do not match';
                }

                if (empty($data['name_err']) && empty($data['number_err']) && empty($data['nic_err']) && empty($data['address_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_err']) && empty($data['profile_image_err'])) {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    if (empty($enteredOTP)) {
                        return $this->view('inc/Components/SignUp/signUp', $data);
                    }
            
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
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter the email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email address';
                }
                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter the password';
                }
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
                        header('Location: ' . URLROOT . '/ConductorPages/newhome');
                        break;
                    }elseif($loggedUser['role'] === 'Driver'){
                        $_SESSION['user_profile_image']=$loggedUser['profile_pic'];
                        $_SESSION['user_role'] = $loggedUser['role'];
                        header('Location: ' . URLROOT . '/ConductorPages/newhome');
                        break;
                    }elseif($loggedUser['role'] == 'Admin'){
                        $_SESSION['user_role'] = $loggedUser['role'];
                        header('Location: ' . URLROOT . '/SuperAdminPages/home');
                        break;
                    }
                default:
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
                     
                ];
                
                $this->GuestpagesModel->addSupportRequest($data);
                $_SESSION['success_message'] = "Your support request has been submitted successfully.";
                header('Location: ' . URLROOT . '/GuestPages/contact?status=success');
                exit();       
            } else {
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

            header('Content-Type: application/json');
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
                
                $data = [
                    'email' => trim($_POST['email']),
                    'email_err' => '',
                    'success' => false,
                    'message' => ''
                ];
                
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email';
                } elseif (!$this->GuestpagesModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'No account found with that email address';
                }
                
                if (empty($data['email_err'])) {
                    try {

                        $token = bin2hex(random_bytes(32));
                        
                        $hashed_token = password_hash($token, PASSWORD_DEFAULT);
                        
                        $expiry = date('Y-m-d H:i:s', time() + 3600);
                        
                        if ($this->GuestpagesModel->setPasswordResetToken($data['email'], $hashed_token, $expiry)) {
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
                    }
                }

                ob_end_clean();
                
                echo json_encode($data);
                ob_end_flush();

                return;

            } else {

                ob_end_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid request method'
                ]);
                return;
            }
        }

        public function resetPassword($token = null, $email = null) {
            if ($token === null || $email === null) {
                $this->flash('password_reset', 'Invalid password reset link', 'alert alert-danger');
                redirect('GuestPages/home');
            }
            
            $email = urldecode($email);
            
            $tokenData = $this->GuestpagesModel->checkResetToken($email);
            
            if (!$tokenData) {
                $this->flash('password_reset', 'Invalid or expired password reset link', 'alert alert-danger');
                redirect('GuestPages/home');
                return;
            }
            
            if (!password_verify($token, $tokenData['token'])) {
                $this->flash('password_reset', 'Invalid password reset link', 'alert alert-danger');
                redirect('GuestPages/home');
                return;
            }
            
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
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
                
                $data = [
                    'token' => trim($_POST['token']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                echo json_encode($_POST['email']);
            
                
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                    
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
