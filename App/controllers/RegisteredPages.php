<?php
    class RegisteredPages extends Controller {
        //so that it will inherit all the functionalities of the Controller class
        private $RegisteredpagesModel;
        
        public function __construct() {
            //Call the model method and assign it to the pagesModel variable
            $this->RegisteredpagesModel = $this->model('M_RegisteredPages');
        }

        /*
        public function index() {
            echo "This is the index method";
        }*/

        public function Bookings() {
            //call a view
            $this->view('pages/Registered User/Bookings');
            
        }

        /*
        public function home() {
            $this->view('pages/GuestUser/home');
        }*/
        

    }  
?>
