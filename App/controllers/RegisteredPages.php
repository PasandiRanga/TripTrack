<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'C:\xampp\htdocs\TripTrack\vendor\autoload.php';
    class RegisteredPages extends Controller {
        private $RegisteredpagesModel;
        
        public function __construct() {
            $this->RegisteredpagesModel = $this->model('M_RegisteredPages');
        }

        public function index() {
            echo "This is the index method";
        }

        public function home() {

            //$this->RegisteredpagesModel->updatePastBookings();
            // $this->RegisteredpagesModel->updatePastBookings();
            
            $schedule = $this->RegisteredpagesModel->getSchedule();
            $bus = $this->RegisteredpagesModel->getBusDetails();
            $route = $this->RegisteredpagesModel->getRoute();
            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                // 'distance' => $distance
                'route' => $route
            ];
            $this->view('pages/RegisteredUser/home' , $data);
        }

        public function bookings() {
            $bookingsDetails = $this->RegisteredpagesModel->getBookings($_SESSION['user_id']);
            $schedule = $this->RegisteredpagesModel->getSchedule();
            $bus = $this->RegisteredpagesModel->getBusDetails();
            $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
            $data =[
                'bookingsDetails' => $bookingsDetails,
                'schedule' => $schedule,
                'bus' => $bus,
                'user' => $user
            ];
            $this->view('pages/RegisteredUser/Bookings' , $data);
            
        }

        public function busLayout() {
            $schedule = $this->RegisteredpagesModel->getSchedule();
            $bus = $this->RegisteredpagesModel->getBusDetails();
            $distance = $this->RegisteredpagesModel->getDistance();
            $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
            $route = $this->RegisteredpagesModel->getRoute();

            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance,
                'user'=> $user,
                'route' => $route
            ];

            $this->view('inc/Components/BusLayout/BusLayout', $data);
        }

        
        public function contactUs() {
            $this->view('pages/RegisteredUser/contactus');
        }

        public function notification() {
            $this->view('pages/RegisteredUser/notifications');
        }

        public function profile() {
            $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
            $data =[
                'user' => $user
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

        public function seeTicket() {
            $this->view('pages/RegisteredUser/seeTicket');
        }

        public function signIn() {
            $this->view('pages/RegisteredUser/signIn');
        }

        public function Notify() {
            $this->view('pages/RegisteredUser/Notify');
        }

        public function LoginBox() {
            $this->view('inc/Components/LoginBox/loginBox');
        }

        // public function BusBooking() {
        //     $schedule = $this->RegisteredpagesModel->getSchedule();
        //     $bus = $this->RegisteredpagesModel->getBusDetails();
        //     $distance = $this->RegisteredpagesModel->getDistance();
        //     $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
        //     $data = [
        //         'schedule' => $schedule,
        //         'bus' => $bus,
        //         'distance' => $distance,
        //         'user' => $user,
     
        //     ];
        //     $this->view('pages/RegisteredUser/BusBooking', $data);
        // }

        // public function RegisteredReceipt() {
        //     $this->view('inc/Components/Receipt/RegisteredReceipt');
        // }

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
                    'current_email' => $_SESSION['user_email'], // Assume session stores ged-in user email
                ];
    
                if ($this->RegisteredpagesModel->updateProfile($data)) {
                    $_SESSION['user_email'] = $data['email'];
                    header('Location: ' . URLROOT . '/RegisteredPages/profile');
                } else {
                    header("Location: " . URLROOT . '/RegisteredPages/Profile');
                }
            } else {
                header("Location: " . URLROOT . '/RegisteredPages/Profile');
            }
        }

        public function cancelOnlineBooking() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo '<script> console.log("scheduleId: ", ' . json_encode($_POST['schedule_id']) . '); </script>';
                $scheduleId = $_POST['schedule_id']?? null;
                echo '<script> console.log("scheduleId: ", ' . json_encode($scheduleId) . '); </script>';
                $bookingId = $_POST['booking_id'] ?? null;
                $cancellation_fee = $_POST['cancellation_fee'] ?? null;
                echo '<script> console.log("cancellation_fee: ", ' . json_encode($cancellation_fee) . '); </script>';
                $refund_amount = $_POST['refund_amount'] ?? null;
                echo '<script> console.log("refund_amount: ", ' . json_encode($refund_amount) . '); </script>';
                
                $bankDetails = json_decode($_POST['bank_details_json'], true);
                echo '<script> console.log("bankDetails: ", ' . json_encode($bankDetails) . '); </script>';
             
        
                if ($bookingId && $this->RegisteredpagesModel->validateBooking($bookingId, $_SESSION['user_id'])) {
                    if ($this->RegisteredpagesModel->cancelBooking($bookingId,$scheduleId, $cancellation_fee, $refund_amount, $bankDetails)) {
                        
                        header("Location: " . URLROOT . "/RegisteredPages/newbookings");
                        exit;
                    } else {
                        header("Location: " . URLROOT . "/RegisteredPages/newbookings?error=Unable to cancel booking");
                        exit;
                    }
                } else {
                    header("Location: " . URLROOT . "/RegisteredPages/newbookings?error=Invalid booking ID");
                    exit;
                }
            } else {
                header("Location: " . URLROOT . "/RegisteredPages/newbookings");
                exit;
            }
        }

        public function slide() {
            $this->view('inc/Components/ImageSlide/imageSlide');
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
                    'userid' => $_POST['user_id'],
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
                    if ($this->RegisteredpagesModel->addSupportRequest($data)) {
                        // Redirect on success
                        $_SESSION['success_message'] = "Your support request has been submitted successfully.";
                        header('Location: ' . URLROOT . '/RegisteredPages/contactUs?status=success');
                        exit();
                    } else {
                        // Handle database error
                        $data['error_message'] = "Something went wrong. Please try again.";
                        $this->view('pages/RegisteredUser/contactus', $data);
                    }
                } else {
                    
                    // Reload view with errors
                    $this->view('pages/RegisteredUser/contactus', $data);
                }
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
                    // Save booking details
                    $this->RegisteredpagesModel->createBooking($bookingData);
                    
                    // Update schedule seat availability
                    $this->RegisteredpagesModel->updateScheduleSeats($bookingData['scheduleId'], explode(', ', $bookingData['selectedSeats']));

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

                    // Send booking confirmation email
                    $this->sendBookingEmail($bookingData);

                    // Load Receipt View
                    $this->view('inc/Components/Receipt/RegisteredReceipt', [
                        'bookingData' => $bookingData,
                        'qrText' => $qrText
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
            require_once APPROOT . '/libraries/phpqrcode/qrlib.php'; // Adjust path as needed

            $qrDir = APPROOT . "/public/qrcodes/";
            
            // Ensure QR code directory exists
            if (!file_exists($qrDir)) {
                mkdir($qrDir, 0777, true);
            }

            $filename = "REGqr_" . time() . ".png"; // Unique filename
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
                <div style="font-family: Arial, sans-serif; color: #333;">
                    <h2>Booking Confirmation</h2>
                    <p>Dear ' . htmlspecialchars($bookingData['name']) . ',</p>
                    <p>Thank you for booking with TripTrack. Here are your booking details:</p>
                    
                    <table style="border-collapse: collapse; width: 100%;">
                        <tr><td><strong>Name:</strong></td><td>' . htmlspecialchars($bookingData['name']) . '</td></tr>
                        <tr><td><strong>Email:</strong></td><td>' . htmlspecialchars($bookingData['email']) . '</td></tr>
                        <tr><td><strong>Contact:</strong></td><td>' . htmlspecialchars($bookingData['contact']) . '</td></tr>
                        <tr><td><strong>NIC:</strong></td><td>' . htmlspecialchars($bookingData['nic']) . '</td></tr>
                        <tr><td><strong>From:</strong></td><td>' . htmlspecialchars($bookingData['from']) . '</td></tr>
                        <tr><td><strong>To:</strong></td><td>' . htmlspecialchars($bookingData['to']) . '</td></tr>
                        <tr><td><strong>Bus ID:</strong></td><td>' . htmlspecialchars($bookingData['License_id']) . '</td></tr>
                        <tr><td><strong>Schedule ID:</strong></td><td>' . htmlspecialchars($bookingData['scheduleId']) . '</td></tr>
                        <tr><td><strong>Number of Seats:</strong></td><td>' . htmlspecialchars($bookingData['noOfSeats']) . '</td></tr>
                        <tr><td><strong>Seats:</strong></td><td>' . htmlspecialchars($bookingData['selectedSeats']) . '</td></tr>
                        <tr><td><strong>Total Price:</strong></td><td>Rs. ' . htmlspecialchars($bookingData['totalPrice']) . '</td></tr>
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

        public function getAllNotifications() {
            header('Content-Type: application/json'); // Ensure JSON response

            $notifications = $this->RegisteredpagesModel->getNotifications();
            
            echo json_encode([
                'success' => true,
                'notifications' => $notifications
            ]);
            exit;
        }

        public function newBookings() {
            $upcomingbookings = $this->RegisteredpagesModel->getUpcomingBookings($_SESSION['user_id']);  
            $pastbookings = $this->RegisteredpagesModel->getPastBookings($_SESSION['user_id']); 
            // echo '<script> console.log("Upcoming Bookings: ", ' . json_encode($upcomingbookings) . '); </script>';
            // echo '<script> console.log("Past Bookings: ", ' . json_encode($pastbookings) . '); </script>';
            $schedule = $this->RegisteredpagesModel->getSchedule();
            $bus = $this->RegisteredpagesModel->getBusDetails();

            $data = [
                'upcomingbookings' => $upcomingbookings,
                'pastbookings' => $pastbookings,
                'schedule' => $schedule,
                'bus' => $bus
            ];
            echo '<script> console.log("Data: ", ' . json_encode($data) . '); </script>';

            $this->view('pages/RegisteredUser/newBookings' , $data);
        }

        public function updateProfileImage() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'profile_image'=>$_FILES['profile_image'],
                    'profile_image_name'=>time().'_'.$_FILES['profile_image']['name'],


                    'profile_image_err'=>'',
                    'name_err' => ''
                ];

                // Validate the profile image
                if ($data['profile_image'] && $data['profile_image']['tmp_name']) {
                    if (!uploadImage($data['profile_image']['tmp_name'], $data['profile_image_name'], '/images/profileImages/')) {
                        $data['profile_image_err'] = 'Profile image uploading unsuccessful';
                    }
                } else {
                    $data['profile_image_name'] = './../../Public/images/profileImages/default.jpg'; // Replace with your actual default image filename, if applicable
                }
            }else {
                $data = [
                    'profile_image'=>'',
                    'profile_image_name'=>'',
                ];
            }
        }

    }  
?>
