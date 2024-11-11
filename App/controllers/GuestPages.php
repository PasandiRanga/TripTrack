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
            $schedule = $this->GuestpagesModel->getSchedule();
            // Retrieve bus details
            $bus = $this->GuestpagesModel->getBusDetails();
            // Combine the schedule and bus details into a single data array
            $data = [
                'schedule' => $schedule,
                'bus' => $bus
            ];

            // Call the home view with schedule data
            $this->view('pages/GuestUser/home', $data);
        }


        public function contact() {
            $this->view('pages/GuestUser/contactus');
        }
        public function busLayout() {
            $schedule = $this->GuestpagesModel->getSchedule();
            $data =[
                'schedule' => $schedule
            ];
            $this->view('inc/Components/BusLayout/BusLayout', $data);
        }
        public function GuestReceipt() {
            $this->view('inc/Components/Receipt/GuestReceipt');
        }

        public function BusBooking() {
            // Retrieve the schedule from the model
            $schedule = $this->GuestpagesModel->getSchedule();
            
            // Retrieve bus details
            $bus = $this->GuestpagesModel->getBusDetails();
            
            // Combine the schedule and bus details into a single data array
            $data = [
                'schedule' => $schedule,
                'bus' => $bus
            ];
            
            // Pass the combined data array to the view
            $this->view('pages/GuestUser/BusBooking', $data);
        }
        
        
        

    }  
?>
