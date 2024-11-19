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

            $distance = $this->RegisteredpagesModel->getDistance();
            
            // Combine the schedule and bus details into a single data array
            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance
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

            
            
            // Combine the schedule and bus details into a single data array
            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance
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

        public function deleteAccount(){
            if($this->RegisteredpagesModel->deleteAccount($_SESSION['user_id'])) {
                $this->view('pages/GuestPages/home'); 
            } else {
                // Handle error if needed, like showing a message
            }
        }        

        public function searchBus() {
            $this->view('pages/RegisteredUser/searchbus');
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
                    'current_email' => $_SESSION['user_email'], // Assume session stores logged-in user email
                ];
    
                if ($this->RegisteredpagesModel->updateProfile($data)) {

                    // Update session email after a successful update
                    $_SESSION['user_email'] = $data['email'];

                    // Redirect with success message
                    header("Location: " . URLROOT . "/profile");
                    // flash('profile_update_success', 'Profile updated successfully!');
                } else {
                    // Redirect with error message
                    header("Location: " . URLROOT . "/profile");
                    // flash('profile_update_error', 'Something went wrong. Please try again.');
                }
            } else {
                // Load default view if accessed incorrectly
                header("Location: " . URLROOT . "/profile");
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
    

    }  
?>
