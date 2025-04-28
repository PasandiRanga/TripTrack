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
            $allnotifications = $this->ConductorpagesModel->getAllNotifications($_SESSION['user_id']);
            $newnotifications = $this->ConductorpagesModel->getNewNotifications($_SESSION['user_id']);

            $data = [
                'currentController' => 'ConductorPages',
                'currentMethod' => 'notifications',
                'title' => 'All Notifications',
                'allnotifications' => $allnotifications,
                'notifications' => $newnotifications
            ];

            $this->view('pages/Conductor/Notifications', $data);
        }

        /* Update notification read status in notification icon*/
        public function toggleReadStatus() {
            // Check if it's an AJAX request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                redirect('pages/error');
            }
            
            // Get POST data
            $input = json_decode(file_get_contents('php://input'), true);
            $notificationId = $input['notification_id'] ?? null;
            $isRead = $input['is_read'] ?? false;
            
            if (!$notificationId) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Notification ID is required'
                ]);
                exit(); // Add this to ensure nothing else is output
            }
            
            $success = $this->ConductorpagesModel->updateReadStatus($notificationId, $isRead, $_SESSION['user_id']);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success
            ]);
            exit(); // Add this to ensure nothing else is output
        }

        public function deleteNotification(){
            // Turn off output buffering

            ob_start();
            
            try {
                // Check if it's an AJAX request
                if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                    echo '<script>console.log("Not POST");</script>';
                    throw new Exception('Invalid request method');

                }
                
                // Get POST data
                $input = json_decode(file_get_contents('php://input'), true);
                $notificationId = $input['notification_id'] ?? null;
                // echo '<script>console.log("Notification id"' .json_encode($notificationId) . ');</script>';

                
                if (!$notificationId) {
                    throw new Exception('Notification ID is required');
                }
                
                $success = $this->ConductorpagesModel->deleteNotification($notificationId, $_SESSION['user_id']);
                echo '<script>console.log(' . json_encode($success) . ');</script>';
        

        
                // Clear any output that might have happened
                ob_clean();
                
                // Return JSON response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => $success
                ]);

            } catch (Exception $e) {
                // Clear any output
                ob_clean();
                
                // Return error JSON
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }

        public function markAllAsRead() {
            ob_start();
            try {
                if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                    throw new Exception('Invalid request method');
                }
                
                $input = json_decode(file_get_contents('php://input'), true);
                
                // Check if ids array exists
                if (!isset($input['ids']) || !is_array($input['ids'])) {
                    throw new Exception('Invalid input format');
                }
                
                $success = true;
                foreach ($input['ids'] as $notiID) {
                    $result = $this->ConductorpagesModel->updateReadStatusOfAll($notiID, $_SESSION['user_id']);
                    if (!$result) {
                        $success = false;
                    }
                }
                
                // Clear any output that might have happened
                ob_clean();
                
                // Return JSON response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => $success
                ]);
            } catch (Exception $e) {
                // Clear any output
                ob_clean();
                
                // Return error JSON
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
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
                    error_log("Step 1: Received POST request");
                    // Get the raw POST body
                    $input = file_get_contents("php://input");
                    $data = json_decode($input, true); // Decode JSON to associative array

                    error_log("Step 3: JSON decoded successfully: " . print_r($data, true));

                    // Check if the required data exists
                    if (!isset($data['schedule_id']) || !isset($data['seats'])) {
                        http_response_code(400);
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Missing required data'
                        ]);
                        return;
                    }

                    // Extract the schedule_id and seats
                    $scheduleId = $data['schedule_id'];
                    $seats = $data['seats'];

                    if (empty($scheduleId) || empty($seats) || !is_array($seats)) {
                        http_response_code(400);
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Invalid schedule ID or seats data'
                        ]);
                        return;
                    }

                    $seatsString = '"' . implode(', ', $seats) . '"';
                    error_log("Step 6: schedule_id = $scheduleId, seatsString = $seatsString");

                    // Check if seats are already accepted
                    $isAlreadyAccepted = $this->ConductorpagesModel->checkAcceptedOrNot($seats, $scheduleId);
                    error_log("Step 7: checkAcceptedOrNot result: " . var_export($isAlreadyAccepted, true));

                    if ($isAlreadyAccepted) {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'These seats are already accepted.'
                        ]);
                        return;
                    }

                    // Update seats to accepted
                    $acceptedSeats = $this->ConductorpagesModel->updateAcceptedSeats($seats, $scheduleId);
                    error_log("Step 8: updateAcceptedSeats result: " . var_export($acceptedSeats, true));

                    // Attempt to get booking from guest bookings first
                    $booking = $this->ConductorpagesModel->getGuestBooking($scheduleId, $seatsString);

                    $result = false;

                    if ($booking) {
                        $result = $this->ConductorpagesModel->insertPastGuestBooking($booking);
                    } else {
                        // If not found in guest bookings, try registered bookings
                        $booking = $this->ConductorpagesModel->getRegisteredBooking($scheduleId, $seatsString);
                        if ($booking) {
                            $result = $this->ConductorpagesModel->insertPastRegBooking($booking);
                        }
                    }

                    if ($booking) {
                        if ($result) {
                            echo json_encode([
                                'status' => 'success',
                                'message' => 'Booking verified and recorded successfully'
                            ]);
                        } else {
                            http_response_code(500);
                            echo json_encode([
                                'status' => 'error',
                                'message' => 'Failed to record booking'
                            ]);
                        }
                    } else {
                        http_response_code(404);
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Booking not found with the provided details'
                        ]);
                    }

                } catch (Exception $e) {
                    error_log("Step X: Exception occurred: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Server error: ' . $e->getMessage()
                    ]);
                }
            } else {
                http_response_code(405); // Method Not Allowed
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Only POST requests are allowed'
                ]);
            }
        }

        public function acceptBookingForm() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                try{
                    // Sanitize and retrieve POST data
                    $bookingId = trim($_POST['bookingId'] ?? '');
                    $nic = trim($_POST['nic'] ?? '');
                

                    if (str_starts_with($bookingId, 'R')) {
                        // Call registered booking check
                        $bookingData = $this->ConductorpagesModel->checkRegBooking($bookingId, $nic);
                    } elseif (str_starts_with($bookingId, 'G')) {
                        // Call guest booking check
                        $bookingData = $this->ConductorpagesModel->checkGuestBooking($bookingId, $nic);
                    } else {
                        $bookingData = null;
                    }

                    $seatsString = $bookingData['selected_seats'];
                    $scheduleId = $bookingData['schedule_id'];

                    $seats = array_map('trim', explode(',', trim($seatsString, '"')));

                    $acceptedSeats = $this->ConductorpagesModel->updateAcceptedSeats($seats, $scheduleId);

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
                            $response['status'] = 'success';
                            $response['message'] = 'Booking verified and recorded successfully';
                            $response['bookingData'] = $bookingData;
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
                $this->view('pages/Conductor/acceptBookingForm');
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

        /*public function home() {
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
            exit; // Stop execution to only see this output

            $data = ['schedule' => $schedule];
            $this->view('pages/Conductor/home', $data);
        }*/

        // public function viewDelays() {
        //     $data = $this->ConductorpagesModel->getDelays();

        //     $this->view('pages/Conductor/ViewDelays', $data);
        // }

        public function newHome() {
            $upcomingschedule = $this->ConductorpagesModel->getUpcomingSchedule($_SESSION['user_id']);
            $pastschedule = $this->ConductorpagesModel->getPastSchedule($_SESSION['user_id']);
            $totalSchedules = $this->ConductorpagesModel->getTotalSchedules($_SESSION['user_id']);
            $latestNotification = $this->ConductorpagesModel->getLatestNotification($_SESSION['user_id']);


            $data = [
                'upcomingSchedule' => $upcomingschedule,
                'pastSchedule' => $pastschedule,
                'totalSchedules' => $totalSchedules,
                'latestNotification' => $latestNotification
            ];
            echo '<script> console.log("Data: ", ' . json_encode($data) . '); </script>';

            $this->view('pages/Conductor/newHome' , $data);
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

        public function profile() {
            $data = $this->ConductorpagesModel->findEmployeeById($_SESSION['user_id']);
            //$notifications = $this->NotificationModel->getNewNotifications($_SESSION['user_id']);
            error_log("user  id: " . print_r($_SESSION['user_id'], true));
            error_log("profile details: " . print_r($data, true));
            
            $this->view('pages/Conductor/profile' , $data);
        }

        public function updateProfileImage() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                $data = [
                    'profile_image' => $_FILES['profile_image'],
                    'profile_image_name' => time() . '_' . $_FILES['profile_image']['name'],
                    'profile_image_err' => ''
                ];

                $userId = $_SESSION['user_id']; 

                // Check and upload image
                if ($data['profile_image'] && $data['profile_image']['tmp_name']) {
                    if (uploadImage($data['profile_image']['tmp_name'], $data['profile_image_name'], '/images/profileImages/')) {
                        $imagePath = $data['profile_image_name']; // Save only the filename or relative path

                        // Calling the model to update image path in DB
                        if ($this->ConductorpagesModel->updateProfileImage($userId, $imagePath)) {
                            // Updating session profile image
                            $_SESSION['user_profile_image'] = $imagePath;

                            // Redirecting to profile with success message
                            redirect('ConductorPages/profile');
                        } else {
                            $data['profile_image_err'] = 'Failed to update image in database';
                        }

                    } else {
                        $data['profile_image_err'] = 'Profile image upload failed';
                    }
                } else {
                    $data['profile_image_err'] = 'No image selected';
                }

                // Reloading profile with error if any
                $data['user'] = $this->ConductorpagesModel->findEmployeeById($userId);
                $this->view('Conductor/profile', $data);

            } else {
                redirect('Conductor/profile');
            }
        }

        public function profileUpdate() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'contact_number' => trim($_POST['contact_number']),
                    'nic' => trim($_POST['nic']),
                    'address' => trim($_POST['address']),
                    'current_email' => $_SESSION['user_email'],
                    'name_err' => '',
                    'email_err' => '',
                    'contact_number_err' => '',
                    'nic_err' => '',
                    'address_err' => '',
                    'has_errors' => false
                ];
                
                // Validate name
                if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter name';
                    $data['has_errors'] = true;
                }
                
                // Validate email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter email';
                    $data['has_errors'] = true;
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email format (e.g., abc@gmail.com)';
                    $data['has_errors'] = true;
                } else {
                    // Only check for duplicate email if the email has changed from the current user's email
                    if ($data['email'] !== $data['current_email'] && $this->ConductorpagesModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'This email is already registered';
                        $data['has_errors'] = true;
                    }
                }
                
                // Validate contact number
                if (empty($data['contact_number'])) {
                    $data['contact_number_err'] = 'Please enter contact number';
                    $data['has_errors'] = true;
                } elseif (!preg_match('/^\d{10}$/', $data['contact_number'])) {
                    $data['contact_number_err'] = 'Please enter a valid contact number';
                    $data['has_errors'] = true;
                }
                
                // Validate NIC
                if (empty($data['nic'])) {
                    $data['nic_err'] = 'Please enter a NIC';
                    $data['has_errors'] = true;
                } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                    // Check if the NIC is either 12 digits or 9 digits followed by "V"
                    $data['nic_err'] = 'NIC must be exactly 12 digits or 9 digits followed by "V" at the end';
                    $data['has_errors'] = true;
                } else {
                    // Get the user's current NIC from the database using their email
                    $currentUser = $this->ConductorpagesModel->findUserByEmail($data['current_email']);
                    
                    // Only check for duplicate NIC if the NIC has changed from the current user's NIC
                    if ($data['nic'] !== $currentUser->NIC) {
                        // Check if another user has this NIC
                        if ($this->ConductorpagesModel->isNICUsedByAnotherUser($data['nic'], $currentUser->User_id)) {
                            $data['nic_err'] = 'This NIC is already registered';
                            $data['has_errors'] = true;
                        }
                    }
                }
                
                // Validate address
                if (empty($data['address'])) {
                    $data['address_err'] = 'Please enter address';
                    $data['has_errors'] = true;
                }
                
                // If validation fails, store form data and errors in session and redirect back
                if ($data['has_errors']) {
                    $_SESSION['profile_data'] = $data;
                    header("Location: " . URLROOT . '/ConductorPages/Profile');
                    exit();
                }
                
                // Validation passed - update profile
                if ($this->ConductorpagesModel->updateProfile($data)) {
                    $_SESSION['user_email'] = $data['email'];
                    $_SESSION['success_message'] = 'Profile updated successfully';
                    header('Location: ' . URLROOT . '/ConductorPages/profile');
                } else {
                    $_SESSION['error_message'] = 'Something went wrong updating your profile';
                    header("Location: " . URLROOT . '/ConductorPages/Profile');
                }
            } else {
                header("Location: " . URLROOT . '/ConductorPages/Profile');
            }
        }
    }
?>