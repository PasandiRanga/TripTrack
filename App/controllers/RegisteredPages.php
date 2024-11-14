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
            //call a view
            $this->view('pages/RegisteredUser/bookings');
            
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

        public function cancelBooking() {
            $this->view('pages/RegisteredUser/cancelBooking');
        }

        public function contactUs() {
            $this->view('pages/RegisteredUser/contactus');
        }

        public function notification() {
            $this->view('pages/RegisteredUser/notifications');
        }

        public function profile() {
            $this->view('pages/RegisteredUser/profile');
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
        

    }  
?>
