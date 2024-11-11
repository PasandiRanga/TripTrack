<?php
    class GuestPages extends Controller {
        //so that it will inherit all the functionalities of the Controller class
        private $GuestpagesModel;
        
        public function __construct() {
            //Call the model method and assign it to the pagesModel variable
            //Instantiated model inside the controller so that we can use the database
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
            // Retrieve the schedule from the model
            $schedule = $this->GuestpagesModel->getSchedule();

            // Prepare the data array to pass to the view
            $scheduleData = [
                'schedule' => $schedule
            ];

            // Call the home view with schedule data
            $this->view('pages/GuestUser/home', $scheduleData);
        }


        public function contact() {
            $this->view('pages/GuestUser/contactus');
        }
        public function busLayout() {
            $this->view('inc/Components/BusLayout/BusLayout');
        }
        public function GuestReceipt() {
            $this->view('inc/Components/Receipt/GuestReceipt');
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
