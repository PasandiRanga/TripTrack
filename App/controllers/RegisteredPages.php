<?php
    class RegisteredPages extends Controller {
        //so that it will inherit all the functionalities of the Controller class
        private $RegisteredpagesModel;
        
        public function __construct() {
            //Call the model method and assign it to the pagesModel variable
            $this->RegisteredpagesModel = $this->model('M_RegisteredPages');
        }

        public function index() {
            echo "This is the index method";
        }

        
        public function home() {
            $schedule = $this->RegisteredpagesModel->getSchedule();
            
            // Retrieve bus details
            $bus = $this->RegisteredpagesModel->getBusDetails();

            $route = $this->RegisteredpagesModel->getRoute();

            // $distance = $this->RegisteredpagesModel->getDistance();
            
            // Combine the schedule and bus details into a single data array
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
            // echo "<pre>";
            // var_dump($bookingsDetails);
            // echo "</pre>";

            // var_dump($bookingsDetails);
            $schedule = $this->RegisteredpagesModel->getSchedule();

            $bus = $this->RegisteredpagesModel->getBusDetails();

            $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
        //     var_dump($user);
            // var_dump($schedule);
            $data =[
                'bookingsDetails' => $bookingsDetails,
                'schedule' => $schedule,
                'bus' => $bus,
                'user' => $user
            ];
            // var_dump($data);

            //call a view
            $this->view('pages/RegisteredUser/Bookings' , $data);
            
        }

        public function busLayout() {
            $schedule = $this->RegisteredpagesModel->getSchedule();
            
            // Retrieve bus details
            $bus = $this->RegisteredpagesModel->getBusDetails();

            $distance = $this->RegisteredpagesModel->getDistance();

            $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);

            $route = $this->RegisteredpagesModel->getRoute();

            
            
            // Combine the schedule and bus details into a single data array
            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance,
                'user'=> $user,
                'route' => $route
            ];
            // var_dump($schedule); // To check if schedule data is loaded
            // var_dump($bus);

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
                // Clear the session data to log the user out
                session_unset();
                session_destroy();
        
                // Redirect to the GuestPages home
                header('Location: ' . URLROOT . '/GuestPages/home');
                exit();
            } else {
                // Handle the error (e.g., showing an error message or logging)
                die('Error: Unable to delete account. Please try again.');
            }
        }
               

        // public function searchBus() {
        //     $this->view('pages/RegisteredUser/searchbus');
        // }

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

        public function BusBooking() {
            $schedule = $this->RegisteredpagesModel->getSchedule();
            
            // Retrieve bus details
            $bus = $this->RegisteredpagesModel->getBusDetails();

            $distance = $this->RegisteredpagesModel->getDistance();

            $user = $this->RegisteredpagesModel->findUserById($_SESSION['user_id']);
            // var_dump($user);

     
            
            // Combine the schedule and bus details into a single data array
            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance,
                'user' => $user,
     
            ];
            
            $this->view('pages/RegisteredUser/BusBooking', $data);
        }

        public function RegisteredReceipt() {
            $this->view('inc/Components/Receipt/RegisteredReceipt');
        }
        
        public function viewPopUp(){
            $this->view('inc/Components/ProfileForm/profileForm');

        }

        public function profileUpdate() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize POST data
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

                    // Update session email after a successful update
                    $_SESSION['user_email'] = $data['email'];

                    // Redirect with success message
                    header('Location: ' . URLROOT . '/RegisteredPages/profile');
                    // flash('profile_update_success', 'Profile updated successfully!');
                } else {
                    // Redirect with error message
                    header("Location: " . URLROOT . '/RegisteredPages/Profile');
                    // flash('profile_update_error', 'Something went wrong. Please try again.');
                }
            } else {
                // Load default view if accessed incorrectly
                header("Location: " . URLROOT . '/RegisteredPages/Profile');
            }
        }

        public function cancelBooking() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Ensure the booking ID is provided
                $scheduleId = $_POST['schedule_id']?? null;
                $bookingId = $_POST['booking_id'] ?? null;
                // $seats = $_POST['seats'] ?? null;
                var_dump($scheduleId);
                var_dump($bookingId);
                
        
                if ($bookingId && $this->RegisteredpagesModel->validateBooking($bookingId, $_SESSION['user_id'])) {
                    // Attempt to cancel the booking
                    if ($this->RegisteredpagesModel->cancelBooking($bookingId,$scheduleId)) {
                        // Redirect or show success message
                        header("Location: " . URLROOT . "/RegisteredPages/bookings");
                        exit;
                    } else {
                        // Redirect with error message
                        header("Location: " . URLROOT . "/RegisteredPages/bookings?error=Unable to cancel booking");
                        exit;
                    }
                } else {
                    // Redirect with validation error
                    header("Location: " . URLROOT . "/RegisteredPages/bookings?error=Invalid booking ID");
                    exit;
                }
            } else {
                // Redirect if accessed without POST
                header("Location: " . URLROOT . "/RegisteredPages/bookings");
                exit;
            }
        }

        public function slide() {
            $this->view('inc/Components/ImageSlide/imageSlide');
        }

        public function getReviews() {
            $licenseId = $_GET['License_id'] ?? null;
        
            if ($licenseId) {
                // Fetch reviews from the database
                $reviews = $this->RegisteredpagesModel->getReviewsByLicenseId($licenseId);
                echo json_encode(['success' => true, 'reviews' => $reviews]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid License ID']);
            }
        }

        public function submitRequest() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
                // Collect form data
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
                // Redirect if accessed directly
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


        
        
    

    }  
?>
