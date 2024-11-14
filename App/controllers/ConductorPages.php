<?php
    class ConductorPages extends Controller {
        //so that it will inherit all the functionalities of the Controller class
        private $ConductorpagesModel;
        
        public function __construct() {
            //Call the model method and assign it to the pagesModel variable
            $this->ConductorpagesModel = $this->model('M_ConductorPages');
        }

        public function index() {
            echo "This is the index method";
        }

        public function informDelays() {
            $this->view('pages/Conductor/InformDelays');
        }

        public function notifications() {
            $this->view('pages/Conductor/Notifications');
        }

        public function requestLeave() {
            $this->view('pages/Conductor/RequestLeave');
        }

        public function home() {
            $this->view('pages/Conductor/home');
        }

        public function viewAssigns() {
            $this->view('pages/Conductor/ViewAssigns');
        }

        public function scanQRcode() {
            $this->view('pages/Conductor/ScanQRcode');
        }

        public function profile() {
            $this->view('pages/Conductor/Profile');
        }

    }
?>