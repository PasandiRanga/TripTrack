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

        public function informDelays(){
            $schedules = $this->ConductorpagesModel->getSchedulesByEmployeeId($_SESSION['user_id']);
            $buses = $this->ConductorpagesModel->getBusByScheduleID($schedules);
            $data = [
                'schedules' => $schedules,
                'buses' => $buses
            ];

            echo '<script>console.log("Data :", ' . json_encode($data) . ');</script>';

            $this->view('pages/Conductor/InformDelays', $data);
        }

        public function addInformDelays() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            echo '<script>console.log(' . json_encode($_POST) .');</script>';
            
            // Collect data into an array
            $data = [
                'scheduleID' => trim($_POST['scheduleID']),
                'time' => trim($_POST['time']),
                'newTime' => trim($_POST['newTime']),
                'reason' => trim($_POST['reason']),
                'userID' => trim($_SESSION['user_id'])
            ];

            if ($this->ConductorpagesModel->addDelays($data)) {
                header("Location: " . URLROOT . "/ConductorPages/viewDelays");
            } else {
                echo '<script>alert("Failed to add delay. Please try again.");</script>';
                header("Location: " . URLROOT . "/ConductorPages/informDelays");
            }
            } else {
            $this->view('pages/Conductor/InformDelays');
            }
        }

        public function notifications() {
            $this->view('pages/Conductor/Notifications');
        }

        public function viewDelays(){
            $data = $this->ConductorpagesModel->getDelays();
 
            $this->view('pages/Conductor/ViewDelays', $data);
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
            
            $this->view('pages/Conductor/ScanQRcode');
        }

        
        public function processScannedQR() {
            // Make sure the request is POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                try {
                    // Get the raw POST body
                    $input = file_get_contents("php://input");
                    $data = json_decode($input, true); // Decode JSON to associative array

                    // Check if the required data exists
                    if (!isset($data['schedule_id']) || !isset($data['seats'])) {
                        http_response_code(400);
                        echo json_encode(['message' => 'Missing required data']);
                        return;
                    }

                    // Extract the schedule_id and seats
                    $scheduleId = $data['schedule_id'];
                    $seats = $data['seats'];

                    $seatsString = '"' . implode(', ', $seats) . '"';

                    //error_log("booking details: " . $seatsString);

                    $response = [
                        'message' => 'Data received',
                        'schedule_id' => $scheduleId,
                        'seats' => $seatsString
                    ];

                    $acceptedSeats = $this->ConductorpagesModel->updateAcceptedSeats($seats, $scheduleId);

                    //error_log("accepted seats: " . $acceptedSeats);
                    error_log("accepted seats: " . print_r($acceptedSeats, true));

                    $result = false;

                    // Attempt to get booking from guest bookings
                    $booking = $this->ConductorpagesModel->getGuestBooking($scheduleId, $seatsString);
                    error_log("booking details: " . print_r($booking, true));
                    if($booking) {
                        $result = $this->ConductorpagesModel->insertPastGuestBooking($booking);
                        error_log("made it past the insert: ");
                    }else {
                        // If not found in guest bookings, try registered bookings
                        $booking = $this->ConductorpagesModel->getRegisteredBooking($scheduleId, $seatsString);
                        if ($booking) {
                            $result = $this->ConductorpagesModel->insertPastRegBooking($booking);
                        }
                    }

                    if($booking) {
                        if ($result) {
                            $response['message'] = 'Booking verified and recorded successfully';
                        } else {
                            http_response_code(500);
                            $response['status'] = 'error';
                            $response['message'] = 'Failed to record booking';
                        }
                    } else {
                        http_response_code(404);
                        $response['status'] = 'error';
                        $response['message'] = 'Booking not found with the provided details';
                    }
                    echo json_encode($response);

                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Server error: ' . $e->getMessage()]);
                }
            } else {
                http_response_code(405); // Method Not Allowed
                echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed']);
            }
        }
        
        public function busLayout() {
            $scheduleData = $this->ConductorpagesModel->getSchedule();
            $busData = $this->ConductorpagesModel->getBusDetails();

            $data = [
                'scheduleData' => $scheduleData,
                'busData' => $busData
            ];

            $this->view('pages/Conductor/busLayout', $data);
        }

        public function home() {
            $assignDetails = $this->ConductorpagesModel->getAssignDetailsByEmployeeId($_SESSION['user_id']);

            echo '<script> console.log("assign details: ", ' . json_encode($assignDetails) . '); </script>';

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

        // public function viewDelays() {
        //     $data = $this->ConductorpagesModel->getDelays();

        //     $this->view('pages/Conductor/ViewDelays', $data);
        // }

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

        public function profile() {
            $data = $this->ConductorpagesModel->findEmployeeById($_SESSION['user_id']);
            //$notifications = $this->NotificationModel->getNewNotifications($_SESSION['user_id']);
            //error_log("profile details: " . print_r($data, true));
            
            $this->view('pages/Conductor/profile' , $data);
        }
    }
?>