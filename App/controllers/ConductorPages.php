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

                header("Location: " . URLROOT . "/ConductorPages/viewDelays");

                // Call the model method to add the bus
                /*if ($this->ConductorpagesModel->addDelays($data)) {
                    // Redirect to the fleet page on success
                    header("Location: " . URLROOT . "/ConductorPages/viewDelays");
                } else {
                    die("Error: Unable to add the delay.");

                <?php
                echo '<pre>';
                var_dump($data);
                echo '</pre>';
                exit();
                ?>

                }*/
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
                    'status' => trim($_POST['status']),
                ];

                // print_r($data);
                // exit();

                $this->ConductorpagesModel->addLeaves($data);

                header("Location: " . URLROOT . "/ConductorPages/viewLeaveRequests");

                // Call the model method to add the bus
                /*if ($this->ConductorpagesModel->addLeaves($data)) {
                    // Redirect to the fleet page on success
                    ;
                } else {
                    die("Error: Unable to add the leave request.");
                }*/
            } else {
                
                $this->view('pages/Conductor/RequestLeave');
            }
        }

        

        public function viewAssigns() {
            $this->view('pages/Conductor/ViewAssigns');
        }

        public function scanQRcode() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $scheduleId = $_POST['schedule_id'];
                $seats = $_POST['seats'];

                //$bookingModel = $this->model('M_ConductorPages');

                $booking = $this->ConductorpagesModel->getRegisteredBooking($scheduleId, $seats);

                if (!$booking) {
                    $booking = $this->ConductorpagesModel->getGuestBooking($scheduleId, $seats);
                }

                if ($booking) {
                    $this->ConductorpagesModel->insertPastBooking($booking);
                    echo json_encode(['success' => true, 'message' => 'Booking logged to past bookings.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Booking not found.']);
                }
            } else {
                http_response_code(405);
                echo 'Method Not Allowed';
            }

            $this->view('pages/Conductor/ScanQRcode');
        }

        public function profile() {
            $employee = $this->ConductorpagesModel->findEmployeeById($_SESSION['user_id']);

            $data = [
                'employee' => $employee
            ];

        

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

        public function home() {
            $assignDetails = $this->ConductorpagesModel->getAssignDetailsByEmployeeId($_SESSION['user_id']);

            if (empty($assignDetails)) {
                return [];
            }

            $scheduleId = array_column($assignDetails, 'scheduleId');

            $scheduleData = $this->ConductorpagesModel->getLicenseIdByScheduleId($scheduleId);
            if (empty($scheduleData)) {
                return [];
            }

            $licenseId = array_column($scheduleData, 'License_id');

            $busDetails = $this->ConductorpagesModel->getBusDetailsByLicenseId($licenseId);
            if (empty($busDetails)) {
                return []; // No bus details found
            }

            $schedule = [];
            foreach ($assignDetails as $assign) {
                foreach ($scheduleData as $scheduleItem) {
                    if ($assign['scheduleId'] == $scheduleItem['scheduleId']) {
                        foreach ($busDetails as $bus) {
                            if ($scheduleItem['License_id'] == $bus['License_id']) {
                                $schedule[] = [
                                    'departureTime' => $scheduleItem['departureTime'],
                                    'date' => $scheduleItem['date'],
                                    'arrivalTime' => $scheduleItem['arrivalTime'],
                                    'availableSeats' => $scheduleItem['availableSeats'],
                                    'bookedSeats' => $scheduleItem['bookedSeats'],
                                    'type' => $scheduleItem['type'],
                                    'routeNumber' => $bus['routeNumber'],
                                    'start_location' => $bus['start_location'],
                                    'destination' => $bus['destination'],
                                    'License_id' => $bus['License_id'],
                                    'price' => $bus['price'],
                                    'priceperkm' => $bus['priceperkm']
                                ];
                            }
                        }
                    }
                }
            }
            /*echo '<pre>';
            print_r($schedule); // Check the final processed schedule data
            echo '</pre>';
            exit; // Stop execution to only see this output*/

            $data = ['schedule' => $schedule];
            $this->view('pages/Conductor/home', $data);
        }

        public function viewDelays() {
            $delays = $this->ConductorpagesModel->getDelays($_SESSION['user_id']);

            $data = [
                'delay' => $delays
            ];

            

            $this->view('pages/Conductor/ViewDelays', $data);
        }

        public function updateLeaveRequests() {

            $leave_id = filter_input(INPUT_GET, 'leave_id', FILTER_SANITIZE_STRING);

            if (!$leave_id) {
                die("Invalid or missing Leave ID.");
            }

            // Fetch the leave details using the model
            $leaveDetails = $this->ConductorpagesModel->getLeaveRequest($leave_id);

            if ($leaveDetails && isset($leaveDetails[0])) {
                // Extract the first (and only) record
                $leaveDetails = $leaveDetails[0];

                    /*echo "<pre>";
                    print_r($leaveDetails);
                    echo "</pre>";
                    exit();*/

                    // Pass the details to the view
                $this->view('pages/Conductor/UpdateLeaveRequests', ['leaveDetails' => $leaveDetails]);
            } else {
                die("Leave Request not found.");
            }
        }

        public function updateLeave() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize input
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Collect data into an array
                $data = [
                    'leave_id' => trim($_POST['leave_id']),
                    'employeeId' => trim($_POST['employeeId']),
                    'from_date' => trim($_POST['from_date']),
                    'to_date' => trim($_POST['to_date']),
                    'noOfDays' => trim($_POST['noOfDays']),
                    'reason' => trim($_POST['reason']),
                ];

                $this->ConductorpagesModel->updateLeaves($data);

                header("Location: " . URLROOT . "/ConductorPages/viewLeaveRequests");

                // Call the model method to add the bus
                /*if ($this->ConductorpagesModel->addLeaves($data)) {
                    // Redirect to the fleet page on success
                    header("Location: " . URLROOT . "/ConductorPages/viewLeaveRequests");
                } else {
                    die("Error: Unable to update the leave request.");
                }*/
            /*} else {
                
                $this->view('pages/Conductor/UpdateLeave');
            }*/
            }
        }

        public function deleteRequest() {
            // Ensure the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Decode the JSON input
                $data = json_decode(file_get_contents('php://input'), true);
        
                if (!empty($data['leave_id'])) {
                    $leave_id = $data['leave_id'];
        
                    // Call the model method to delete the leave request
                    if ($this->ConductorpagesModel->deleteLeave($leave_id)) {
                        // Respond with success
                        echo json_encode(['status' => 'success', 'message' => 'Leave request deleted successfully']);
                    } else {
                        // Respond with error
                        echo json_encode(['status' => 'error', 'message' => 'Error deleting leave request']);
                    }
                } else {
                    // Missing leave_id in request
                    echo json_encode(['status' => 'error', 'message' => 'Leave ID is required']);
                }
            } else {
                // Invalid request method
                echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            }
        }

        public function busLayOut() {
            $this->view('pages/Conductor/busLayout');
        }
        

    }
?>