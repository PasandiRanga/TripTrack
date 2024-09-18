<?php
    class GuestPages extends Controller {
        //so that it will inherit all the functionalities of the Controller class
        private $GuestpagesModel;
        
        public function __construct() {
            //Call the model method and assign it to the pagesModel variable
            $this->GuestpagesModel = $this->model('M_GuestPages');
        }

        public function index() {
            echo "This is the index method";
        }

        public function about() {
            //call a view
            $this->view('pages/GuestUser/aboutus');
            
        }

        public function home() {
            $this->view('pages/GuestUser/home');
        }

        public function Booking() {
            // Retrieve the 'busId' from the URL parameters
            $busId = isset($_GET['busId']) ? $_GET['busId'] : null;
            
            // Pass the busId to the view if necessary
            $data = ['busId' => $busId];
            
            $this->view('pages/GuestUser/BusBooking', $data);
        }
        

    }  
?>
