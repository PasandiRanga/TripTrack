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

        public function contact() {
            $this->view('pages/GuestUser/contactus');
        }

        public function BusBooking() {
            $busId = isset($_GET['busId']) ? $_GET['busId'] : null;
            
            if ($busId === null) {
                echo "Bus ID is missing!";
                exit;
            }
        
            $data = ['busId' => $busId];
            
            $this->view('pages/GuestUser/BusBooking', $data);
        }
        
        

    }  
?>
