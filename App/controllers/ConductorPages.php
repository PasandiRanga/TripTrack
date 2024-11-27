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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Collect data into an array
                $data = [
                    'routeNo' => trim($_POST['routeNo']),
                    'busNo' => trim($_POST['busNo']),
                    'busRoute' => trim($_POST['busRoute']),
                    'time' => trim($_POST['time']),
                    'newTime' => trim($_POST['newTime']),
                    'reason' => trim($_POST['reason']),
                ];

                $this->ConductorpagesModel->addDelays($data);

                // Call the model method to add the bus
                if ($this->ConductorpagesModel->addDelays($data)) {
                    // Redirect to the fleet page on success
                    header("Location: " . URLROOT . "/ConductorPages/home");
                } else {
                    die("Error: Unable to add the delay.");
                }
            } else {
                
                $this->view('pages/Conductor/InformDelays');
            }

        }

        public function notifications() {
            $this->view('pages/Conductor/Notifications');
        }

        public function requestLeave() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Collect data into an array
                $data = [
                    'employeeId' => trim($_POST['employeeId']),
                    'from_date' => trim($_POST['from_date']),
                    'to_date' => trim($_POST['to_date']),
                    'noOfDays' => trim($_POST['noOfDays']),
                    'reason' => trim($_POST['reason']),
                ];

                $this->ConductorpagesModel->addLeaves($data);

                // Call the model method to add the bus
                if ($this->ConductorpagesModel->addLeaves($data)) {
                    // Redirect to the fleet page on success
                    header("Location: " . URLROOT . "/ConductorPages/home");
                } else {
                    die("Error: Unable to add the leave request.");
                }
            } else {
                
                $this->view('pages/Conductor/RequestLeave');
            }
        }

        public function home() {
            /*$employee_id = $_SESSION['employee_id'];

            $schedules = $this->ConductorpagesModel->getScheduleByEmployeeId($employee_id);

            if (!$schedules || empty($schedules)) {
                die('No schedules found for this employee.');
            }

            $schedule_id = $schedules[0]['schedule_id'];

            $scheduleDetails = $this->ConductorpagesModel->getScheduleDetailsById($schedule_id);

            $data = [
                'scheduleDetails' => $scheduleDetails
            ];*/

            /*if (!$scheduleDetails) {
                die('No schedule found for the given ID');
            }

            $this->view('pages/Conductor/home', $scheduleDetails);*/

            $this->view('pages/Conductor/home');
        }

        public function viewAssigns() {
            $this->view('pages/Conductor/ViewAssigns');
        }

        public function scanQRcode() {
            $this->view('pages/Conductor/ScanQRcode');
        }

        public function profile() {
            $employee = $this->ConductorpagesModel->findEmployeeById($_SESSION['user_id']);

            $data = [
                'employee' => $employee
            ];

            /*echo '<pre>';
            print_r($_SESSION);
            echo '</pre>';
            exit();*/

            $this->view('pages/Conductor/Profile', $data);

        }

        public function viewLeaveRequests() {
            $leaveRequest = $this->ConductorpagesModel->getLeaveRequests($_SESSION['user_id']);

            $data = [
                'leaveRequest' => $leaveRequest
            ];

            /*echo "<pre>";
            print_r($data['leaveRequest']);
            echo "</pre>";
            exit();*/

            $this->view('pages/Conductor/ViewLeaveRequests', $data);
        }

    }
?>