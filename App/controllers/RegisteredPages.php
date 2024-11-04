<?php
    class RegisteredPages extends Controller {
        //so that it will inherit all the functionalities of the Controller class
        private $RegisteredpagesModel;
        
        public function __construct() {
            //Call the model method and assign it to the pagesModel variable
            $this->RegisteredpagesModel = $this->model('M_RegisteredPages');
        }

        
        public function Home() {
            $this->view('pages/RegisteredUser/Home');
        }

        public function Bookings() {
            //call a view
            $this->view('pages/RegisteredUser/Bookings');
            
        }

        public function BusLayout() {
            $this->view('pages/RegisteredUser/Bus_layout');
        }

        public function CancelBooking() {
            $this->view('pages/RegisteredUser/CancelBooking');
        }

        public function contactus() {
            $this->view('pages/RegisteredUser/contactus');
        }

        public function Notification() {
            $this->view('pages/RegisteredUser/Notifications');
        }

        public function Profile() {
            $this->view('pages/RegisteredUser/profile');
        }

        public function SearchBus() {
            $this->view('pages/RegisteredUser/searchbus');
        }

        public function SeeTicket() {
            $this->view('pages/RegisteredUser/seeTicket');
        }

        public function SignIn() {
            $this->view('pages/RegisteredUser/Signin');
        }
        

    }  
?>
