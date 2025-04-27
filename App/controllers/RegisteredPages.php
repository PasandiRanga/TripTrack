<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'C:\xampp\htdocs\TripTrack\vendor\autoload.php';
    class RegisteredPages extends Controller {
        private $RegisteredpagesModel;
        private $NotificationModel;
        
        public function __construct() {
            $this->RegisteredpagesModel = $this->model('M_RegisteredPages');
            $this->NotificationModel = $this->model('M_Notification');
        }

        public function index() {
            echo "This is the index method";
        }

        public function home() {
            echo '<script>console.log("User id:", ' . json_encode($_SESSION['user_id']) . ');</script>';

            $schedule = $this->RegisteredpagesModel->getSchedule();
            $bus = $this->RegisteredpagesModel->getBusDetails();
            $route = $this->RegisteredpagesModel->getRoute();
            $notifications = $this->NotificationModel->getNewNotifications($_SESSION['user_id']);
            $averageRatings = [];   

            foreach ($schedule as $item) {
                $licenseId = $item['License_id'];
                $avg = $this->RegisteredpagesModel->getAverageRatings($licenseId);
                $averageRatings[$licenseId] = isset($avg['average_rate']) ? round($avg['average_rate'], 1) : 'No ratings';
            }

            echo '<script>console.log("Notifications in controller:", ' . json_encode($notifications) . ');</script>';

            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'route' => $route,
                'notifications' => $notifications,
                'averageRatings' => $averageRatings,
            ];

            $this->view('pages/RegisteredUser/home', $data);
        }

        public function slide() {
            $this->view('inc/Components/ImageSlide/imageSlide');
        }

        public function busLayout() {
            $schedule = $this->RegisteredpagesModel->getSchedule();
            $bus = $this->RegisteredpagesModel->getBusDetails();
            $distance = $this->RegisteredpagesModel->getDistance();
            $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
            $route = $this->RegisteredpagesModel->getRoute();
            $notifications = $this->NotificationModel->getNewNotifications($_SESSION['user_id']);
            $pastNotArrivedBookings = $this->RegisteredpagesModel->getPastNotArrivedBookings($_SESSION['user_id']);
            $averageRatings = [];  
            
            foreach ($schedule as $item) {
                $licenseId = $item['License_id'];
                $avg = $this->RegisteredpagesModel->getAverageRatings($licenseId);
                $averageRatings[$licenseId] = isset($avg['average_rate']) ? round($avg['average_rate'], 1) : 'No ratings';
            }

            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance,
                'user'=> $user,
                'route' => $route,
                'notifications' => $notifications,
                'pastNotArrivedBookings' => $pastNotArrivedBookings,
                'averageRatings' => $averageRatings,
            ];

            $this->view('inc/Components/BusLayout/BusLayout', $data);
        } 

        public function contactUs() {
            
            $notifications = $this->NotificationModel->getNewNotifications($_SESSION['user_id']);
            $data =[
                'notifications' => $notifications
            ];

            $this->view('pages/RegisteredUser/contactus' , $data);
        }

        public function profile() {
            $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
            $notifications = $this->NotificationModel->getNewNotifications($_SESSION['user_id']);

            $data =[
                'user' => $user,
                'notifications' => $notifications
            ]; 
            $this->view('pages/RegisteredUser/profile' , $data);
        }

        public function deleteAccount() {
            if ($this->RegisteredpagesModel->deleteAccount($_SESSION['user_id'])) {
                session_unset();
                session_destroy();
                header('Location: ' . URLROOT . '/GuestPages/home');
                exit();
            } else {
                die('Error: Unable to delete account. Please try again.');
            }
        }

        public function signIn() {
            $this->view('pages/RegisteredUser/signIn');
        }
     
        public function LoginBox() {
            $this->view('inc/Components/LoginBox/loginBox');
        }

        public function PaymentPortal(){
            $this->view('inc/Components/PaymentPortal/paymentPortal');
        }
        
        public function viewPopUp(){
            $this->view('inc/Components/ProfileForm/profileForm');

        }

        public function profileUpdate() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'contact_number' => trim($_POST['contact_number']),
                    'nic' => trim($_POST['nic']),
                    'address' => trim($_POST['address']),
                    'current_email' => $_SESSION['user_email'],
                    'name_err' => '',
                    'email_err' => '',
                    'contact_number_err' => '',
                    'nic_err' => '',
                    'address_err' => '',
                    'has_errors' => false
                ];
                
                // Validate name
                if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter name';
                    $data['has_errors'] = true;
                }
                
                // Validate email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter email';
                    $data['has_errors'] = true;
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email format (e.g., abc@gmail.com)';
                    $data['has_errors'] = true;
                } else {
                    // Only check for duplicate email if the email has changed from the current user's email
                    if ($data['email'] !== $data['current_email'] && $this->RegisteredpagesModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'This email is already registered';
                        $data['has_errors'] = true;
                    }
                }
                
                // Validate contact number
                if (empty($data['contact_number'])) {
                    $data['contact_number_err'] = 'Please enter contact number';
                    $data['has_errors'] = true;
                } elseif (!preg_match('/^\d{10}$/', $data['contact_number'])) {
                    $data['contact_number_err'] = 'Please enter a valid contact number';
                    $data['has_errors'] = true;
                }
                
                // Validate NIC
                if (empty($data['nic'])) {
                    $data['nic_err'] = 'Please enter a NIC';
                    $data['has_errors'] = true;
                } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                    // Check if the NIC is either 12 digits or 9 digits followed by "V"
                    $data['nic_err'] = 'NIC must be exactly 12 digits or 9 digits followed by "V" at the end';
                    $data['has_errors'] = true;
                } else {
                    // Get the user's current NIC from the database using their email
                    $currentUser = $this->RegisteredpagesModel->getUserByEmail($data['current_email']);
                    
                    // Only check for duplicate NIC if the NIC has changed from the current user's NIC
                    if ($data['nic'] !== $currentUser->NIC) {
                        // Check if another user has this NIC
                        if ($this->RegisteredpagesModel->isNICUsedByAnotherUser($data['nic'], $currentUser->User_id)) {
                            $data['nic_err'] = 'This NIC is already registered';
                            $data['has_errors'] = true;
                        }
                    }
                }
                
                // Validate address
                if (empty($data['address'])) {
                    $data['address_err'] = 'Please enter address';
                    $data['has_errors'] = true;
                }
                
                // If validation fails, store form data and errors in session and redirect back
                if ($data['has_errors']) {
                    $_SESSION['profile_data'] = $data;
                    header("Location: " . URLROOT . '/RegisteredPages/Profile');
                    exit();
                }
                
                // Validation passed - update profile
                if ($this->RegisteredpagesModel->updateProfile($data)) {
                    $_SESSION['user_email'] = $data['email'];
                    $_SESSION['success_message'] = 'Profile updated successfully';
                    header('Location: ' . URLROOT . '/RegisteredPages/profile');
                } else {
                    $_SESSION['error_message'] = 'Something went wrong updating your profile';
                    header("Location: " . URLROOT . '/RegisteredPages/Profile');
                }
            } else {
                header("Location: " . URLROOT . '/RegisteredPages/Profile');
            }
        }

        public function cancelBooking() {
                echo '<script>console.log("POST data received:", ' . json_encode($_POST) . ');</script>';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo '<script> console.log("Method is post"); </script>';
                $scheduleId = $_POST['schedule_id'];
                echo '<script> console.log("scheduleId: ", ' . json_encode($scheduleId) . '); </script>';
                $bookingId = $_POST['booking_id'] ?? null;
                $cancellation_fee = $_POST['cancellation_fee'] ?? null;
                echo '<script> console.log("cancellation_fee: ", ' . json_encode($cancellation_fee) . '); </script>';
                $refund_amount = $_POST['refund_amount'] ?? null;
                echo '<script> console.log("refund_amount: ", ' . json_encode($refund_amount) . '); </script>';

                $bankDetails =[];
                
                $bankDetails = json_decode($_POST['bank_details_json'], true) ?? [];
                
                if($bankDetails){
                    if ($bookingId && $this->RegisteredpagesModel->validateBooking($bookingId, $_SESSION['user_id'])) {
                        if ($this->RegisteredpagesModel->cancelOnlineBooking($bookingId,$scheduleId, $cancellation_fee, $refund_amount, $bankDetails)) {
                            
                            header("Location: " . URLROOT . "/RegisteredPages/cancellationReceipt?booking_id=$bookingId&cancellation_fee=$cancellation_fee&refund_amount=$refund_amount&bank_details_json=" . urlencode(json_encode($bankDetails)));
                            exit;
                        } else {
                            header("Location: " . URLROOT . "/RegisteredPages/newbookings?error=Unable to cancel booking");
                            exit;
                        }
                    } else {
                        header("Location: " . URLROOT . "/RegisteredPages/newbookings?error=Invalid booking ID");
                        exit;
                    }
                }else{
                    if ($bookingId && $this->RegisteredpagesModel->validateBooking($bookingId, $_SESSION['user_id'])){
                        echo '<script> console.log("Booking is valid"); </script>';

                        if ($this->RegisteredpagesModel->cancelCashBooking($bookingId,$scheduleId, $cancellation_fee, $refund_amount, $bankDetails)) {
                            header("Location: " . URLROOT . "/RegisteredPages/cancellationReceipt?booking_id=$bookingId&cancellation_fee=$cancellation_fee&refund_amount=$refund_amount&bank_details_json=" . urlencode(json_encode($bankDetails)));
                            exit;
                        } else {
                            header("Location: " . URLROOT . "/RegisteredPages/newbookings?error=Unable to cancel booking");
                            exit;
                        }
                    }else {
                        header("Location: " . URLROOT . "/RegisteredPages/newbookings?error=Invalid booking ID");
                        exit;
                    }

                }
            } else {
                header("Location: " . URLROOT . "/RegisteredPages/newbookings");
                exit;
            }
        }

        public function cancellationReceipt() {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $bookingId = $_GET['booking_id'] ?? null;
                $cancellation_fee = $_GET['cancellation_fee'] ?? null;
                $refund_amount = $_GET['refund_amount'] ?? null;
                $bankDetails = json_decode($_GET['bank_details_json'], true);
                echo("<script>console.log('Booking id2: $bookingId');</script>");
                echo("<script>console.log('CancellationFee2: $cancellation_fee');</script>");
                echo("<script>console.log('Refund amount2: $refund_amount');</script>");          

                $cancellationData = $this->RegisteredpagesModel->getCancellationDetails($bookingId, $_SESSION['user_id']);

                $this->sendcancellationEmail($cancellationData);

                if ($bookingId) {
                    $data = [
                        'booking_id' => $bookingId,
                        'cancellation_fee' => $cancellation_fee,
                        'refund_amount' => $refund_amount,
                        'bankDetails' => $bankDetails,
                        'cancellationData' => $cancellationData,
                    ];

                    $this->view('inc/Components/CancellationReceipt/cancellationReceipt', $data);
                } else {
                    header("Location: " . URLROOT . "/RegisteredPages/newbookings?error=Invalid booking ID");
                    exit;
                }
            } else {
                header("Location: " . URLROOT . "/RegisteredPages/newbookings");
                exit;
            }
        }

        public function getReviews() {
            $licenseId = $_GET['License_id'] ?? null;
            if ($licenseId) {
                $reviews = $this->RegisteredpagesModel->getReviewsByLicenseId($licenseId);
                echo json_encode(['success' => true, 'reviews' => $reviews]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid License ID']);
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
                    'userid' => $_SESSION['user_id'] ?? null
                ];
      
                $this->RegisteredpagesModel->addSupportRequest($data);
                // Redirect on success
                $_SESSION['success_message'] = "Your support request has been submitted successfully.";
                header('Location: ' . URLROOT . '/RegisteredPages/contactUs?status=success');
                exit();
            } else {
                header('Location: ' . URLROOT . '/RegisteredPages/contactUs');
                exit();
            }
        }

        public function filterBusByDate() {
            if (!isset($_GET['date'])) {
                return;
            }
            $scheduleData = $this->RegisteredpagesModel->getScheduleByDate($_GET['date']);
            $busData = $this->RegisteredpagesModel->getBusDetails();
            $routeData = $this->RegisteredpagesModel->getRoute();
            $data = [
                'schedule' => $scheduleData,
                'bus' => $busData,
                'route' => $routeData,
                'currentController' => 'RegisteredPages',
                'currentMethod' => 'home',
            ];

            require APPROOT . '/views/inc/Components/BusCard/busCardGenerator.php';
        }

        public function RegisteredReceipt() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $bookingData = [
                    'User_id' => $_SESSION['user_id'],
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

                    // Generate QR Code text
                    $qrText = "Booking Receipt\n";
                    $qrText .= "UserID: {$bookingData['User_id']}\n";
                    $qrText .= "Schedule ID: {$bookingData['scheduleId']}\n";
                    $qrText .= "Seats: {$bookingData['selectedSeats']}\n";
                    $qrText .= "Total Price: Rs. {$bookingData['totalPrice']}\n";
                    $qrText .= "Payment method: {$bookingData['paymentMethod']}\n";

                    // Generate QR Code and get its URL

                    $qrData = $this->generateQRCode("Booking Receipt\nUserID: {$bookingData['User_id']}\nSchedule ID: {$bookingData['scheduleId']}\nSeats: {$bookingData['selectedSeats']}\nTotal Price: Rs. {$bookingData['totalPrice']}\nPayment method: {$bookingData['paymentMethod']}");
            
                    // Add the QR code data to booking data
                    $bookingData['qrCodeUrl'] = $qrData['qrCodeUrl'];
                    $bookingData['qrCodeFilename'] = $qrData['qrCodeFilename'];

                    //If there are penalty fees inlcuded
                    if(isset($_POST['penaltyFee']) && $_POST['penaltyFee'] > 0 && isset($_SESSION['user_id']) ){
                        $penaltyFee = floatval($_POST['penaltyFee']);
                        $userId = $_SESSION['user_id'];

                        $this->RegisteredpagesModel->MarkPenaltyPaid($userId);

                        $this->RegisteredpagesModel->sendPenaltyPaidNotification($userId , $penaltyFee);
                    }


                    // Save booking details
                    $this->RegisteredpagesModel->createBooking($bookingData);
                    
                    // Update schedule seat availability
                    $this->RegisteredpagesModel->updateScheduleSeats($bookingData['scheduleId'], explode(', ', $bookingData['selectedSeats']));

                    $bookingID = $this->RegisteredpagesModel->getBookingID($bookingData['User_id'], $bookingData['scheduleId'], $bookingData['selectedSeatsJSON']);
                    $bookingId = $bookingID['id'];

                    // Send booking confirmation email
                    $this->sendBookingEmail($bookingData);


                    // Load Receipt View
                    $this->view('inc/Components/Receipt/RegisteredReceipt', [
                        'bookingData' => $bookingData,
                        'qrText' => $qrText,
                        'bookingID' => $bookingId
                    ]);

                } catch (Exception $e) {
                    error_log("Booking error: " . $e->getMessage());
                    header('Location: ' . URLROOT . '/RegisteredPages/home?error=booking_failed');
                    exit();
                }
            } else {
                header('Location: ' . URLROOT . '/RegisteredPages/home');
                exit();
            }
        }

        private function generateQRCode($qrText) {
            require_once APPROOT . '/libraries/phpqrcode/qrlib.php';

            $qrDir = APPROOT . "/public/qrcodes/";
            
            // Ensuring QR code directory exists
            if (!file_exists($qrDir)) {
                mkdir($qrDir, 0777, true);
            }

            //Asigning a unique file name
            $filename = "REGqr_" . time() . ".png"; 
            $filePath = $qrDir . $filename;

            // Generating the QR Code
            QRcode::png($qrText, $filePath, QR_ECLEVEL_L, 10);

            // Returning QR Code URL
            return [
                'qrCodeUrl' => URLROOT . "/public/qrcodes/" . $filename,
                'qrCodeFilename' => $filename // Pass the filename as well
            ];
        }

       private function sendBookingEmail($bookingData) {
            $mail = new PHPMailer(true);

            $bookingID = $this->RegisteredpagesModel->getBookingID($bookingData['User_id'], $bookingData['scheduleId'], $bookingData['selectedSeatsJSON']);

            if (is_string($bookingID) && strpos($bookingID, '{id:') !== false) {
                //Regex to extract just the ID value
                preg_match('/id: [\'"]([^\'"]+)[\'"]/', $bookingID, $matches);
                if (isset($matches[1])) {
                    $bookingID = $matches[1];
                }
            }
            
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
                
                // Attaching the QR code image as inline image
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
                    
                    <div style="text-align: center; margin: 20px 0;">
                        <h3 style="color: #2c3e50;">Your QR Code</h3>
                        <p>Scan the QR code below for your booking details:</p>
                        <img src="cid:qr_code_image" alt="QR Code" style="width: 200px; height: 200px; border: 1px solid #ddd; padding: 5px;"/>
                    </div>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
                        <p>We look forward to serving you.</p>
                        <p>Best regards,<br>
                        <b>TripTrack Team</b></p>
                    </div>
                    
                    <div style="margin-top: 20px; font-size: 12px; color: #777; text-align: center;">
                        <p>If you have any questions regarding your booking, please contact our customer service.</p>
                        <p>© ' . date('Y') . ' TripTrack. All rights reserved.</p>
                    </div>
                </div>';
                
                $mail->send();
            } catch (Exception $e) {
                error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
            }
        }

        public function sendCancellationEmail($cancellationData) {
            $mail = new PHPMailer(true);

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

                $userData = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
                $mail->addAddress($userData['Email'], $userData['Name']);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Your Booking Cancellation - TripTrack';

                // Determining if it's an online or cash booking based on payment method
                $isOnlineBooking = ($cancellationData['paymentMethod'] != 'Cash');
                
                $mail->Body = '
                <div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
                    <div style="text-align: center; background-color: #f8f8f8; padding: 10px; margin-bottom: 20px; border-radius: 3px;">
                        <h2 style="color: #2c3e50; margin: 0;">Booking Cancellation Confirmation</h2>
                    </div>
                    
                    <p>Dear ' . htmlspecialchars($userData['Name']) . ',</p>
                    <p>Your booking with TripTrack has been successfully cancelled. Here are the details of the cancelled booking:</p>
                    
                    <table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd; margin: 20px 0;">
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Booking ID:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['id']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Booking Date:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['Booking_date']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Booking Time:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['Booking_time']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>From:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['from_location']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>To:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['to_location']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Schedule ID:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['schedule_id']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Number of Seats:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['No_of_seats']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Seats:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['Seats']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Original Price:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">Rs. ' . htmlspecialchars($cancellationData['total_price']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Payment Method:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['paymentMethod']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Cancellation Fee:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">Rs. ' . htmlspecialchars($cancellationData['cancellation_fee']) . '</td></tr>';
                        
                // Showing refund amount only for online bookings
                if ($isOnlineBooking) {
                    $mail->Body .= '
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Refund Amount:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">Rs. ' . htmlspecialchars($cancellationData['refund_amount']) . '</td></tr>';
                }
                        
                $mail->Body .= '
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Cancellation Date:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['time_date']) . '</td></tr>';
                
                // Adding account details only for online bookings
                if ($isOnlineBooking) {
                    $mail->Body .= '
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Account Name:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['account_name']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Account Number:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['account_number']) . '</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Bank Name:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['bank_name']) . '</td></tr>
                        <tr style="background-color: #f2f2f2;"><td style="padding: 8px; border: 1px solid #ddd;"><strong>Branch Name:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($cancellationData['branch_name']) . '</td></tr>';
                }
                
                $mail->Body .= '    
                    </table>
                    
                    <div style="margin-top: 20px; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #2c3e50; border-radius: 3px;">';
                
                // Adding specific refund message for online bookings
                if ($isOnlineBooking) {
                    $mail->Body .= '<strong>Important Note:</strong> Your refund of Rs. ' . htmlspecialchars($cancellationData['refund_amount']) . ' will be deposited to your provided bank account within 2 to 3 business days.';
                } else {
                    $mail->Body .= 'Please note that as this was a cash booking, no refund will be processed through the system.';
                }
                
                $mail->Body .= '
                    </div>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
                        <p>If you have any questions regarding this cancellation or would like to make a new booking, please contact our customer service.</p>
                        <p>Thank you for choosing TripTrack.</p>
                        <p>Best regards,<br><b>TripTrack Team</b></p>
                    </div>
                    
                    <div style="margin-top: 20px; font-size: 12px; color: #777; text-align: center;">
                        <p>© ' . date('Y') . ' TripTrack. All rights reserved.</p>
                    </div>
                </div>';
                
                $mail->send();
                return true;
            } catch (Exception $e) {
                error_log("Cancellation email could not be sent. Error: {$mail->ErrorInfo}");
                return false;
            }
        }

        public function newBookings() {
            $upcomingbookings = $this->RegisteredpagesModel->getUpcomingBookings($_SESSION['user_id']);  
            $pastbookings = $this->RegisteredpagesModel->getPastBookings($_SESSION['user_id']); 
            $cancellations = $this->RegisteredpagesModel->getCancellations($_SESSION['user_id']);
            $upcomingschedule = $this->RegisteredpagesModel->getUpcomingSchedule();
            $pastschedule = $this->RegisteredpagesModel->getPastSchedule();
            $bus = $this->RegisteredpagesModel->getBusDetails();
            $notifications = $this->NotificationModel->getNewNotifications($_SESSION['user_id']);
            $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
            
            $data = [
                'upcomingbookings' => $upcomingbookings,
                'pastbookings' => $pastbookings,
                'upcomingSchedule' => $upcomingschedule,
                'bus' => $bus,
                'notifications' => $notifications,
                'pastSchedule' => $pastschedule,
                'user' => $user,
                'cancellations' => $cancellations
            ];
            // echo '<script> console.log("Data: ", ' . json_encode($data) . '); </script>';

            $this->view('pages/RegisteredUser/newBookings' , $data);
        }

        public function updateProfileImage() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Filter the POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                $userId = $_SESSION['user_id'];
                $data = [
                    'user' => $this->RegisteredpagesModel->findUserById($userId),
                    'profile_image_err' => ''
                ];
                
                // Check if an image was submitted
                if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                    $data['profile_image'] = $_FILES['profile_image'];
                    $data['profile_image_name'] = time() . '_' . $_FILES['profile_image']['name'];
                    
                    // Attempt to upload the image
                    if (uploadImage($data['profile_image']['tmp_name'], $data['profile_image_name'], '/images/profileImages/')) {
                        $imagePath = $data['profile_image_name'];
                        
                        // Update the database
                        if ($this->RegisteredpagesModel->updateProfileImage($userId, $imagePath)) {
                            // Update session and redirect
                            $_SESSION['user_profile_image'] = $imagePath;
                            // Remove the flash() function call
                            redirect('RegisteredPages/profile');
                        } else {
                            $data['profile_image_err'] = 'Failed to update image in database';
                        }
                    } else {
                        $data['profile_image_err'] = 'Profile image upload failed';
                    }
                } else {
                    // No image was selected or there was an upload error
                    $data['profile_image_err'] = 'Please select an image file';
                }
                
                // If we get here, there was an error - load the profile page with error messages
                $this->view('RegisteredUser/profile', $data);
            } else {
                redirect('RegisteredPages/profile');
            }
        }

        /* For all noticiation page */
        public function allNotifications()
        {
            // Geting all notifications for the current user
            $allnotifications = $this->NotificationModel->getAllUserNotifications($_SESSION['user_id']);
            $notifications = $this->NotificationModel->getNewNotifications($_SESSION['user_id']);
            // echo '<script> console.log("Notifications: ", ' . json_encode($allnotifications) . '); </script>';

            $data = [
                'currentController' => 'RegisteredPages',
                'currentMethod' => 'allNotifications',
                'title' => 'All Notifications',
                'allnotifications' => $allnotifications,
                'notifications' => $notifications
            ];
            // echo '<script> console.log("Data: ", ' . json_encode($data) . '); </script>';

            
            $this->view('pages/RegisteredUser/allNotifications', $data);
        }

        /* For the notification icon */
        public function getAllNotifications()
        {
            // Checking if it's an AJAX request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                redirect('pages/error');
            }
            
            $notifications = $this->NotificationModel->getNewNotifications($_SESSION['user_id']);
            
            // Returning the JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'notifications' => $notifications
            ]);
        }

        /* Mark notifications as seen notification icon*/
        public function markNotificationsAsSeen()
        {
            // Checking if it's an AJAX request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                redirect('pages/error');
            }
            
            $success = $this->NotificationModel->markNotificationsAsSeen($_SESSION['user_id']);
            
            // Returning JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success
            ]);
        }

        /* Update notification read status in notification icon*/
        public function toggleReadStatus() {
            // Checking if it's an AJAX request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                redirect('pages/error');
            }
            
            // Get the POST data
            $input = json_decode(file_get_contents('php://input'), true);
            $notificationId = $input['notification_id'] ?? null;
            $isRead = $input['is_read'] ?? false;
            
            if (!$notificationId) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Notification ID is required'
                ]);
                //Ensuring nothing else it outputted
                exit();
            }
            
            $success = $this->NotificationModel->updateReadStatus($notificationId, $isRead, $_SESSION['user_id']);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success
            ]);
            exit(); // Add this to ensure nothing else is output
        }

        public function deleteNotification(){
            // Turn off output buffering
            ob_start();
            
            try {
                // Checking if it's an AJAX request
                if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                    throw new Exception('Invalid request method');
                }
                
                // Get POST data
                $input = json_decode(file_get_contents('php://input'), true);
                $notificationId = $input['notification_id'] ?? null;
                // echo '<script>console.log("Notification id"' .json_encode($notificationId) . ');</script>';

                if (!$notificationId) {
                    throw new Exception('Notification ID is required');
                }
                
                $success = $this->NotificationModel->deleteNotification($notificationId, $_SESSION['user_id']);
                // echo '<script>console.log(' . json_encode($success) . ');</script>';
    
                // Clear any output that might have happened
                ob_clean();
                
                // Returning the JSON response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => $success
                ]);

            } catch (Exception $e) {
                // Clear any output
                ob_clean();
                
                // Returning an error JSON
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }

        /* Dismiss notification in notification icon*/
        public function dismissNotification()
        {
            // Checking if it's an AJAX request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                redirect('pages/error');
            }
            
            // Get POST data
            $input = json_decode(file_get_contents('php://input'), true);
            $notificationId = $input['notification_id'] ?? null;
            
            if (!$notificationId) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Notification ID is required'
                ]);
                return;
            }
            
            $success = $this->NotificationModel->dismissNotification($notificationId, $_SESSION['user_id']);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success
            ]);
        }

        public function markAllAsRead() {
            ob_start();
            try {
                if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                    throw new Exception('Invalid request method');
                }
                
                $input = json_decode(file_get_contents('php://input'), true);
                
                // Checking if ids array exists
                if (!isset($input['ids']) || !is_array($input['ids'])) {
                    throw new Exception('Invalid input format');
                }
                
                $success = true;
                foreach ($input['ids'] as $notiID) {
                    $result = $this->NotificationModel->updateReadStatusOfAll($notiID, $_SESSION['user_id']);
                    if (!$result) {
                        $success = false;
                    }
                }
                
                // Clear any output that might have happened
                ob_clean();
                
                // Return JSON response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => $success
                ]);
            } catch (Exception $e) {
                // Clear any output
                ob_clean();
                
                // Return error JSON
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
        
        public function addReviews() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                echo '<script>console.log("Post data: ' . json_encode($_POST) . '");</script>';

                $data = [
                'user_id' => $_POST['user_id'],
                'license_id' => $_POST['license_id'],
                'rate' => $_POST['rating'],
                'review' => trim($_POST['opinion']),
                'date' => gmdate('Y-m-d', time() + 19800), 
                'time' => gmdate('H:i:s', time() + 19800)  
            ];
            
            if ($this->RegisteredpagesModel->addReview($data)) {
                // redirecting to previous or success page
                redirect('RegisteredPages/newBookings');
            } else {
                die('Something went wrong');
            }
            } else {
                redirect('RegisteredPages/newBookings');
            }
        }


    }  
?>
