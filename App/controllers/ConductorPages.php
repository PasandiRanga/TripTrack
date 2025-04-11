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
            // Make sure it's a POST request
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get the raw JSON input
                $json = file_get_contents("php://input");
        
                // Decode it
                $data = json_decode($json, true);
        
                // Extract values
                $scheduleId = $data['schedule_id'] ?? null;
                $seats = $data['seats'] ?? null;
        
                // Basic validation
                if (!$scheduleId || !$seats) {
                    http_response_code(400); // Bad Request
                    echo json_encode(['message' => 'Missing schedule ID or seats.']);
                    return;
                }
        
                // Try to find the booking in registered bookings first
                $booking = $this->ConductorpagesModel->getRegisteredBooking($scheduleId, $seats);
                
                // If not found in registered, check guest bookings
                if (!$booking) {
                    $booking = $this->ConductorpagesModel->getGuestBooking($scheduleId, $seats);
                }

                if ($booking) {
                    // Try to insert into past bookings
                    $this->ConductorpagesModel->insertPastBooking($booking);

                    // Send success response
                    echo json_encode(['message' => 'Booking recorded successfully.']);
                }else{
                     // Booking not found
                    http_response_code(404); // Not Found
                    echo json_encode(['message' => 'No booking found for this schedule and seats.']);
                }

            } else {
                // Handle non-POST request
                http_response_code(405); // Method Not Allowed
                echo json_encode(['message' => 'Method not allowed.']);
            }

            $this->view('pages/Conductor/ScanQRcode');
        }

        /*public function scanQRcode() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate and sanitize input data
                $scheduleId = isset($_POST['schedule_id']) ? trim($_POST['schedule_id']) : null;
                $seatsRaw = isset($_POST['seats']) ? trim($_POST['seats']) : null;
                
                if (!$scheduleId || !$seatsRaw) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Missing required data']);
                    exit();
                }
        
                // Log what was received for debugging
                file_put_contents('debug_scan.log', "Received: Schedule=$scheduleId, Seats=$seatsRaw\n", FILE_APPEND);
                
                // Clean and normalize seats format
                $seatsRaw = str_replace('"', '', $seatsRaw); // Remove any quotes
                $seatsArray = explode(',', $seatsRaw);
                $seatsArray = array_map('trim', $seatsArray); // Clean each seat value
                
                // Try to find the booking in registered bookings first
                $booking = $this->ConductorpagesModel->getRegisteredBooking($scheduleId, $seatsArray);
                
                // If not found in registered, check guest bookings
                if (!$booking) {
                    $booking = $this->ConductorpagesModel->getGuestBooking($scheduleId, $seatsArray);
                }
                
                header('Content-Type: application/json');
                
                if ($booking) {
                    // Try to insert into past bookings
                    $result = $this->ConductorpagesModel->insertPastBooking($booking);
                    
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Booking successfully logged to past bookings.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => '❌ Failed to log booking to past bookings.']);
                    }
                } else {
                    // Determine why no booking was found
                    $registeredScheduleMatch = $this->ConductorpagesModel->checkScheduleExistsInRegistered($scheduleId);
                    $guestScheduleMatch = $this->ConductorpagesModel->checkScheduleExistsInGuest($scheduleId);
        
                    if (!$registeredScheduleMatch && !$guestScheduleMatch) {
                        $errorMsg = '❌ No booking found: Schedule ID does not exist in either table.';
                    } else {
                        $errorMsg = '❌ No booking found: Seat number(s) do not match for the given Schedule ID.';
                        // Log the seats that were being searched for
                        file_put_contents('debug_scan.log', "Schedule exists but seats not found: " . implode(',', $seatsArray) . "\n", FILE_APPEND);
                    }
                    
                    echo json_encode(['success' => false, 'message' => $errorMsg]);
                }
                
                exit(); // Stop execution here - don't load the view after POST
            }
            
            // Only load the view on GET request
            $this->view('pages/Conductor/ScanQRcode');
        }*/

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