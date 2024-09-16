<?php
    class Pages extends Controller {
        //so that it will inherit all the functionalities of the Controller class
        public function __construct() {
            // echo "This is the pages controller<br/>";
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
    }  
?>
