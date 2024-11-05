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
            $this->view('pages/RegisteredUser/home');
        }

        public function bookings() {
            //call a view
            $this->view('pages/RegisteredUser/bookings');
            
        }

        public function busLayout() {
            $this->view('pages/RegisteredUser/Bus_layout');
        }

        public function cancelBooking() {
            $this->view('pages/RegisteredUser/cancelBooking');
        }

        public function contactUs() {
            $this->view('pages/RegisteredUser/contactUs');
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
        

    }  
?>
