<?php
class SuperAdminPages extends Controller {
    // Variable to hold the model
    private $SuperAdminModel;

    public function __construct() {
        // Call the model method and assign it to the SuperAdminModel variable
        $this->SuperAdminModel = $this->model('M_SuperAdminPages'); // Adjust the model name as per your implementation
    }

    public function home() {
            $income = $this->SuperAdminModel->getTotalIncome();
            $totalcustomers = $this->SuperAdminModel->getTotalCustomers();
            $total_guests = $this->SuperAdminModel->getTotalGuestBookings();
            $total_registered = $this->SuperAdminModel->getTotalRegisteredBookings();
            $total_bookings = $total_guests + $total_registered;
            // Calculate the total income
            $totalIncome = $income['registered_income'] + $income['guest_income'];

            // Pass the data to the view or return as JSON (API)
            $data = [
                'registered_income' => $income['registered_income'],
                'guest_income' => $income['guest_income'],
                'total_income' => $totalIncome,
                'total_customers' => $totalcustomers,
                'total_guests' => $total_guests,
                'total_registered' => $total_registered,
                'total_bookings' => $total_bookings
            ];



        $this->view('pages/SuperAdmin/Dashboard',$data);
    }

    public function fleet() {
        $bus = $this->SuperAdminModel->getBus();
        $data = [
            'bus' => $bus
        ];
        $this->view('pages/SuperAdmin/Fleet', $data);
    }

    public function AddFleet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=UTF-8');

            $inputData = json_decode(file_get_contents("php://input"), true);

            error_log("Input Data: " . json_encode($inputData)); // Log the input data for debugging

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
                http_response_code(400);
                exit();
            }

            // Collect data into an array
            $data = [
                'License_id' => trim($inputData['License_id'] ?? ''),
                'routeNumber' => trim($inputData['routeNumber'] ?? ''),
                'start_location' => trim($inputData['start_location'] ?? ''),
                'destination' => trim($inputData['destination'] ?? ''),
                'passengers' => trim($inputData['passengers'] ?? ''),
                'price' => trim($inputData['price'] ?? ''),
                'priceperkm' => trim($inputData['priceperkm'] ?? '')
            ];

            // Validate required fields
            if (empty($data['License_id']) || empty($data['routeNumber']) || empty($data['start_location']) || empty($data['destination']) || empty($data['passengers']) || empty($data['price']) || empty($data['priceperkm'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

            // Call the model method to add the bus
            if ($this->SuperAdminModel->addBus($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Bus added successfully.']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database Error: Cannot add bus.']);
                http_response_code(500);
                exit();
            }
        } else {
            $route = $this->SuperAdminModel->getRouteDetails();

            $data = [
                'route' => $route
            ];
            // Load the view if not a POST request
            $this->view('pages/SuperAdmin/Addfleet', $data);
        }
    }

    public function deleteBus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decode the JSON input
            $data = json_decode(file_get_contents('php://input'), true);

            if (!empty($data['License_id'])) {
                $licence_id = $data['License_id'];

                // Delete the bus
                if ($this->SuperAdminModel->deleteBus($licence_id)) {
                    echo json_encode(['status' => 'success', 'message' => 'Bus deleted successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error deleting the bus']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'License ID is required']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }

    public function updateBus() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            header('Content-Type: application/json');
            // Decode the JSON input
            $inputData = json_decode(file_get_contents('php://input'), true);

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
                http_response_code(400);
                exit();
            }

            // Prepare the data array
            $data = [
                'License_id' => trim($inputData['License_id'] ?? ''),
                'routeNumber' => trim($inputData['routeNumber'] ?? ''),
                'start_location' => trim($inputData['start_location'] ?? ''),
                'destination' => trim($inputData['destination'] ?? ''),
                'passengers' => trim($inputData['passengers'] ?? ''),
                'price' => trim($inputData['price'] ?? ''),
                'priceperkm' => trim($inputData['priceperkm'] ?? '')
            ];

            // Validate required fields
            if (empty($data['License_id']) || empty($data['routeNumber']) || empty($data['start_location']) || empty($data['destination']) || empty($data['passengers']) || empty($data['price']) || empty($data['priceperkm'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

            // Call the model method to update the bus
            if ($this->SuperAdminModel->updateBus($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Bus updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating the bus.']);
                http_response_code(500);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            http_response_code(405);
        }
    }

    public function searchFleet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decode the incoming JSON payload
            $data = json_decode(file_get_contents('php://input'), true);
            $searchQuery = $data['searchQuery'] ?? '';

            // Log the search query for debugging
            error_log("Search Query Received: " . $searchQuery);

            // Perform the search using the model
            $results = $this->SuperAdminModel->searchFleet($searchQuery);

            if ($results) {
                echo json_encode(['status' => 'success', 'data' => $results]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No results found.']);
            }
        } else {
            // Handle invalid request methods
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }

    public function getTotalBuses() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get search term from request
            $data = json_decode(file_get_contents('php://input'), true);
            $searchTerm = $data['searchTerm'] ?? '';
            //$searchTerm = trim($_POST['searchTerm']);

            error_log("Search Query Received: " . $searchTerm);
            // Load the Model
            $result = $this->SuperAdminModel->getBusCount($searchTerm);

            // Ensure the result is not null
            //$totalBuses = isset($result['total_buses']) ? $result['total_buses'] : 0;


            if ($result) {
                echo json_encode(['status' => 'success', 'data' => $result]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No results found.']);
            }
            // Send JSON response instead of loading a view
            /*
            echo json_encode([
                'status' => 'success',
                'searchTerm' => $searchTerm,
                'total_buses' => $totalBuses
            ]);
            exit; */
        } else {
            // If not a POST request, return an error
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            exit;
        }
    }



    public function getAllFleet() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Fetch all fleet data from the model
            $results = $this->SuperAdminModel->getAllFleet();

            if ($results) {
                echo json_encode(['status' => 'success', 'data' => $results]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No fleet data found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }

    // public function updatefleet() {
    //     // Retrieve the license ID from the GET request
    //     $licence_id = isset($_GET['License_id']) ? $_GET['License_id'] : null;

    //     if ($licence_id) {
    //         // Fetch the bus details using the model
    //         $busDetails = $this->SuperAdminModel->getBusByLicenseId($licence_id);

    //         // Pass the details to the view
    //         if ($busDetails) {
    //             $this->view('pages/SuperAdmin/Updatefleet', ['busDetails' => $busDetails]);
    //         } else {
    //             die("Bus not found.");
    //         }
    //     } else {
    //         die("License ID not provided.");
    //     }
    // }

//----------------------------------------------------------------------------------------------------------------------
                                    //bookings
//---------------------------------------------------------------------------------------------------------------------- 


    public function bookings() {
        $guestbookings = $this->SuperAdminModel->getGuestBookings();
        $registerbookings = $this->SuperAdminModel->getRegisterBookings();
        $cancel_online_bookings = $this->SuperAdminModel->getCancelOnlineBookings();
        $cancel_cash_bookings = $this->SuperAdminModel->getCancelCashBookings();
        $data = [
            'book' => $guestbookings,
            'book1' => $registerbookings,
            'cancel_online_bookings' => $cancel_online_bookings,
            'cancel_cash_bookings' => $cancel_cash_bookings
        ];
        $this->view('pages/SuperAdmin/Bookings',$data);

    }

    public function reports() {
        $this->view('pages/SuperAdmin/Reports');
    }
//----------------------------------------------------------------------------------------------------------------------
                                    //reviews
//---------------------------------------------------------------------------------------------------------------------- 

    public function reviews() {
        $reviews = $this->SuperAdminModel->getReviews();
        $data = [
            'reviews' => $reviews
        ];
        $this->view('pages/SuperAdmin/Reviews',$data);
    }

    public function replyreview() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=UTF-8');

            $inputData = json_decode(file_get_contents("php://input"), true);

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
                http_response_code(400);
                exit();
            }

            $data = [
                'reviewId' => trim($inputData['reviewId'] ?? ''),
                'reply' => trim($inputData['reply'] ?? '')
            ];

            if (empty($data['reviewId']) || empty($data['reply'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

            if ($this->SuperAdminModel->replyreview($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Reply added successfully.']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database Error: Cannot add reply.']);
                http_response_code(500);
                exit();
            }
        } else {
            // Handle GET request or other methods
            http_response_code(405); // Method Not Allowed
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            exit();
        }
    }

    public function replyreviews() {
        $this->view('pages/SuperAdmin/ReplyReviews');
    }
//----------------------------------------------------------------------------------------------------------------------
                                    //Schedule
//---------------------------------------------------------------------------------------------------------------------- 

    public function schedule() {
        $schedule = $this->SuperAdminModel->getschedule();
        $data = [
            'schedule' => $schedule
        ];
        $this->view('pages/SuperAdmin/Schedule',$data);
    }

    public function addschedule() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            header('Content-Type: application/json; charset=UTF-8');

            $inputData = json_decode(file_get_contents("php://input"), true);

            if(!$inputData){
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
                http_response_code(400);
                exit();
            }

            $data = [
                //'scheduleId' => trim($inputData['scheduleId'] ?? ''),
                'License_id' => trim($inputData['License_id'] ?? ''),
                'date' => trim($inputData['date'] ?? ''),
                'departureTime' => trim($inputData['departureTime'] ?? ''),
                'arrivalTime' => trim($inputData['arrivalTime'] ?? ''),
                'duration' => trim($inputData['duration'] ?? ''),
                'availableSeats' => trim($inputData['availableSeats'] ?? ''),
                'bookedSeats' => (empty($inputData['bookedSeats']) ? null : trim($inputData['bookedSeats'])),
                'direction' => trim($inputData['direction'] ?? ''),
                'type' => trim($inputData['type'] ?? '')
            ];

            if (empty($data['License_id']) || empty($data['date']) || empty($data['departureTime']) || empty($data['arrivalTime']) || empty($data['availableSeats']) || empty($data['direction']) || empty($data['type'])){
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

            if($this->SuperAdminModel->addschedule($data)){
                echo json_encode(['status' => 'success', 'message' => 'Schedule added successfully.']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database Error cannot add schedule']);
                http_response_code(500);
                exit();
            }
        } else {
            $bus = $this->SuperAdminModel->getBusID();

            $data = [
                'bus' => $bus
            ];

            $this->view('pages/SuperAdmin/Addschedule',$data);
        }
        
    }

    public function updateSchedule() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputData = json_decode(file_get_contents('php://input'), true);

            if (!$inputData) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
            http_response_code(400);
            exit();
            }

            $data = [
                'scheduleId' => trim($inputData['scheduleId'] ?? ''),
                'License_id' => trim($inputData['License_id'] ?? ''),
                'date' => trim($inputData['date'] ?? ''),
                'departureTime' => trim($inputData['departureTime'] ?? ''),
                'arrivalTime' => trim($inputData['arrivalTime'] ?? ''),
                'duration' => trim($inputData['duration'] ?? ''),
                'direction' => trim($inputData['direction'] ?? ''),
                'type' => trim($inputData['type'] ?? '')
            ];


            if (empty($data['License_id']) || empty($data['date']) || empty($data['departureTime']) || empty($data['arrivalTime']) || empty($data['direction']) || empty($data['type'])) {

            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            http_response_code(400);
            exit();
            }

            // Call the model method to update the schedule
            if ($this->SuperAdminModel->updateSchedule($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Schedule updated successfully.']);
            } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating the schedule.']);
            http_response_code(500);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            http_response_code(405);
        }
    }

    public function deleteSchedule() {
        header('Content-Type: application/json');

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'),true);

            if(!empty($data['scheduleId'])){
                $scheduleId = $data['scheduleId'];

                if($this->SuperAdminModel->deleteSchedule($scheduleId)){
                    echo json_encode(['status' => 'success', 'message' => 'Schedule deleted successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error deleting the Schedule']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ScheduleId is required']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }
//----------------------------------------------------------------------------------------------------------------------
                                    //Leave Requests
//---------------------------------------------------------------------------------------------------------------------- 

    public function leaverequests() {
        $this->view('pages/SuperAdmin/LeaveRequests');
    }

//----------------------------------------------------------------------------------------------------------------------
                                    //Notifications
//---------------------------------------------------------------------------------------------------------------------- 

    public function notifications() {

        $delays = $this->SuperAdminModel->getBusDelays();
        $data = [
            'delays' => $delays
        ];
        $this->view('pages/SuperAdmin/Notifications', $data);
    }

    public function sendnotifications() {
        $this->view('pages/SuperAdmin/Sendnotifications');
    }


//----------------------------------------------------------------------------------------------------------------------
                                    //Employees
//---------------------------------------------------------------------------------------------------------------------- 

    public function addemployees(){
        $this->view('pages/SuperAdmin/addemployees');
    }

    public function addemp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=UTF-8');

            $inputData = json_decode(file_get_contents("php://input"), true);

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
                http_response_code(400);
                exit();
            }

            $data = [
                'name' => trim($inputData['name'] ?? ''),
                'nic' => trim($inputData['nic'] ?? ''),
                'address' => trim($inputData['address'] ?? ''),
                'contactNo' => trim($inputData['contactNo'] ?? ''),
                'email' => trim($inputData['email'] ?? ''),
                'password' => trim($inputData['password'] ?? ''),
                'role' => trim($inputData['role'] ?? ''),

                'name_err' => '',
                'contactNo_err' => '',
                'nic_err' => '',
                'address_err' => '',
                'email_err' => '',
                'password_err' => '',
                'role_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter a name';
            }

            // Validate contact number
            if (empty($data['contactNo'])) {
                $data['contactNo_err'] = 'Please enter a contact number';
            } elseif (!ctype_digit($data['contactNo'])) {
                $data['contactNo_err'] = 'The contact number must contain only numbers';
            } elseif (strlen($data['contactNo']) !== 10) {
                $data['contactNo_err'] = 'The contact number must be exactly 10 digits long';
            } elseif ($data['contactNo'][0] !== '0') {
                $data['contactNo_err'] = 'The contact number must start with 0';
            }

            // Validate NIC
            if (empty($data['nic'])) {
                $data['nic_err'] = 'Please enter a NIC';
            } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                $data['nic_err'] = 'NIC must be exactly 12 digits or 9 digits followed by "V" at the end';
            } else {
                if ($this->SuperAdminModel->findUserByNIC($data['nic'])) {
                    $data['nic_err'] = 'This NIC is already registered';
                }
            }

            // Validate address
            if (empty($data['address'])) {
                $data['address_err'] = 'Please enter an address';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email format (e.g., abc@gmail.com)';
            } else {
                if ($this->SuperAdminModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'This email is already registered';
                }
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            } elseif (strlen($data['password']) < 8) {
                $data['password_err'] = 'Password must be at least 8 characters long';
            } elseif (!preg_match('/[A-Z]/', $data['password'])) {
                $data['password_err'] = 'Password must contain at least one uppercase letter';
            } elseif (!preg_match('/[a-z]/', $data['password'])) {
                $data['password_err'] = 'Password must contain at least one lowercase letter';
            } elseif (!preg_match('/\d/', $data['password'])) {
                $data['password_err'] = 'Password must contain at least one number';
            } elseif (!preg_match('/[\W_]/', $data['password'])) {
                $data['password_err'] = 'Password must contain at least one special character';
            }

            // Register the user if no errors are present
            if (empty($data['name_err']) && empty($data['contactNo_err']) && empty($data['nic_err']) &&
                empty($data['address_err']) && empty($data['email_err']) && empty($data['password_err'])) {

                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->SuperAdminModel->addemployee($data)) {
                    echo json_encode(['status' => 'success', 'message' => 'Employee added successfully.']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error adding employee.']);
                    http_response_code(500);
                    exit();
                }
            } else {
                echo json_encode(['status' => 'error', 'errors' => $data]);
                http_response_code(400);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            http_response_code(405);
        }
    }

    public function updateEmployee(){

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decode the JSON input
            $inputData = json_decode(file_get_contents('php://input'), true);

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
                http_response_code(400);
                exit();
            }

            // Prepare the data array
            $data = [
                'employee_id' => trim($inputData['employee_id'] ?? ''),
                'name' => trim($inputData['name'] ?? ''),
                'nic' => trim($inputData['nic'] ?? ''),
                'address' => trim($inputData['address'] ?? ''),
                'contactNo' => trim($inputData['contactNo'] ?? ''),
                'email' => trim($inputData['email'] ?? ''),
                'name_err' => '',
                'nic_err' => '',
                'address_err' => '',
                'contactNo_err' => '',
                'email_err' => '',
            ];

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter a name.';
            }

            // Validate NIC
            if (empty($data['nic'])) {
                $data['nic_err'] = 'Please enter a NIC.';
            } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                $data['nic_err'] = 'NIC must be 12 digits or 9 digits followed by "V".';
            }

            // Validate address
            if (empty($data['address'])) {
                $data['address_err'] = 'Please enter an address.';
            }

            // Validate contact number
            if (empty($data['contactNo'])) {
                $data['contactNo_err'] = 'Please enter a contact number.';
            } elseif (!ctype_digit($data['contactNo']) || strlen($data['contactNo']) !== 10 || $data['contactNo'][0] !== '0') {
                $data['contactNo_err'] = 'Contact number must be 10 digits and start with 0.';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email.';
            }

            // Check for errors
            if (empty($data['name_err']) && empty($data['nic_err']) && empty($data['address_err']) && empty($data['contactNo_err']) && empty($data['email_err'])) {
                // Update the employee in the database
                if ($this->SuperAdminModel->updateEmployee($data)) {
                    echo json_encode(['status' => 'success', 'message' => 'Employee updated successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error updating the employee.']);
                    http_response_code(500);
                }
            } else {
                // Return validation errors
                echo json_encode(['status' => 'error', 'errors' => $data]);
                http_response_code(400);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            http_response_code(405);
        }
    }

    public function deleteEmployee(){
        header('Content-Type: application/json');

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'),true);

            if(!empty($data['employee_id'])){
                $employee = $data['employee_id'];

                if($this->SuperAdminModel->deleteEmployee($employee)){
                    echo json_encode(['status' => 'success', 'message' => 'Employee deleted Successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error Deleting the Employee']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'EmployeeId is required']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }


    public function employees() {
        $emps = $this->SuperAdminModel->getemployee();
        $data = [
            'emp' => $emps
        ];
        $this->view('pages/SuperAdmin/Employees',$data);
    }

    public function replyleaves() {
        $this->view('pages/SuperAdmin/ReplyLeaves');
    }



//----------------------------------------------------------------------------------------------------------------------
                                    //Assigns
//---------------------------------------------------------------------------------------------------------------------- 

    public function assigns() {
        $assign = $this->SuperAdminModel->getAssigns();
        $data =  [
            'assign' => $assign
        ];
        $this->view('pages/SuperAdmin/Assigns',$data);
    }

    public function addassigns() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Set header to return JSON response
            header('Content-Type: application/json');

            // Get raw POST data and decode JSON
            $inputData = json_decode(file_get_contents("php://input"), true);

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
                http_response_code(400);
                exit();
            }

            $data = [
                'scheduleId'   => trim($inputData['scheduleId'] ?? ''),
                'driverName'    => trim($inputData['driverName'] ?? ''),
                'conductorName' => trim($inputData['conductorName'] ?? ''),
                'driver_id' => trim($inputData['driverId'] ?? ''),
                'conductor_id' => trim($inputData['conductorId'] ?? '')
            ];

            // Print the data for debugging
            error_log("Assign Data: " . json_encode($data));

            // Validate required fields
            if (empty($data['scheduleId']) || empty($data['driverName']) || empty($data['conductorName']) || empty($data['driver_id']) || empty($data['conductor_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required controller.']);
                http_response_code(400);
                exit();
            }

            // Insert into DB
            if ($this->SuperAdminModel->addAssigns($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Assign added successfully.']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database error. Could not add assign.']);
                http_response_code(500);
                exit();
            }
        } else {
                $scheduleId = $_GET['scheduleId'] ?? '';
                $driverName = $_GET['driver_name'] ?? '';
                $conductorName = $_GET['conductor_name'] ?? '';
                $driver_id = $_GET['driver_id'] ?? '';
                $conductor_id = $_GET['conductor_id'] ?? '';
                $isUpdate = !empty($scheduleId);
            //Fetch all schedules
            $allSchedules = $this->SuperAdminModel->getScheduleID();

            //fetch assigned schedules
            $assignedSchedules = $this->SuperAdminModel->getAssignedSchedules();

            //filter schedlues to execute already assigned ones
            $availableSchedules = array_filter($allSchedules, function($schedule) use ($assignedSchedules) {
                return !in_array($schedule['scheduleId'], array_column($assignedSchedules, 'scheduleId'));
            });

            //$allDrivers = $this->SuperAdminModel->getDriverId();
            //$assignedDrivers = $this->SuperAdminModel->getAssignedDrivers();

            //filter the available drivers to execute already assigned ones
            $availablesDrivers = $this->SuperAdminModel->getAvailableDrivers();

            //$allConductors = $this->SuperAdminModel->getConductorId();
            //$assignedConductors = $this->SuperAdminModel->getAssignedConductors();

            //filter the available conductors to execute already assigned ones
            $availableConductors = $this->SuperAdminModel->getAvailableConductors();
            // Fetch schedule, driver, and conductor data
            $schedules = $availableSchedules;
            $drivers = $availablesDrivers;
            $conductors = $availableConductors;

            $data = [
                'schedules' => $schedules,
                'drivers' => $drivers,
                'conductors' => $conductors,
                'scheduleId' => $scheduleId,
                'driverName' => $driverName,
                'conductorName' => $conductorName,
                'driver_id' => $driver_id,
                'conductor_id' => $conductor_id,
                'isUpdate' => $isUpdate
            ];

            $this->view('pages/SuperAdmin/Addassigns', $data);
            
        }
    }

    public function updateAssign() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputData = json_decode(file_get_contents('php://input'), true);

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
                http_response_code(400);
                exit();
            }

            $data = [
                'scheduleId'   => trim($inputData['scheduleId'] ?? ''),
                'driverName'    => trim($inputData['driverName'] ?? ''),
                'conductorName' => trim($inputData['conductorName'] ?? ''),
                'driver_id' => trim($inputData['driverId'] ?? ''),
                'conductor_id' => trim($inputData['conductorId'] ?? '')
            ];

            // Debug log to verify data
            error_log("Controller updateAssign Data: " . json_encode($data));

            // Validate required fields
            if (empty($data['scheduleId']) || empty($data['driverName']) || empty($data['conductorName']) || empty($data['driver_id']) || empty($data['conductor_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

            // Call the model method to update the assign
            if ($this->SuperAdminModel->updateAssign($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Assign updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating the assign.']);
                http_response_code(500);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            http_response_code(405);
        }
    }

    public function deleteAssign() {
        header('Content-Type: application/json');

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'),true);

            if(!empty($data['scheduleId'])){
                $scheduleId = $data['scheduleId'];

                if($this->SuperAdminModel->deleteAssign($scheduleId)){
                    echo json_encode(['status' => 'success', 'message' => 'Assign deleted successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error deleting the Assign']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ScheduleId is required']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }



//----------------------------------------------------------------------------------------------------------------------
                                    //Routes
//---------------------------------------------------------------------------------------------------------------------- 

    public function routes(){
        $routes = $this->SuperAdminModel->getRoutes();
        $data = [
            'routes' => $routes
        ];
        $this->view('pages/SuperAdmin/Routes',$data);
    }

    public function addroute(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            header('Content-Type: application/json');

            $inputData = json_decode(file_get_contents("php://input"), true);

            if(!$inputData){
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
                http_response_code(400);
                exit();
            }

            $data = [
                'routeNumber' => trim($inputData['routeNumber'] ?? ''),
                'route' => trim($inputData['route'] ?? ''),
                'stops' => trim($inputData['stops'] ?? ''),
                'price' => trim($inputData['price'] ?? ''),
                'priceperkm' => trim($inputData['priceperkm'] ?? ''),
            ];

            if(empty($data['routeNumber']) || empty($data['route']) || empty($data['stops']) || empty($data['price']) || empty($data['priceperkm'])){

                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

            if($this->SuperAdminModel->addRoute($data)){
                echo json_encode(['status' => 'success', 'message' => 'Route Added sccessfully']);
                exit();
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Error Occured adding new Route']);
                exit();
            }
        } else {
            $this->view('pages/SuperAdmin/Addroutes');
        }
    }

    public function updateRoute(){
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $inputData = json_decode(file_get_contents('php://input'), true);

            if(!$inputData){
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
                http_response_code(400);
                exit();
            }

            $data = [
                'routeNumber' => trim($inputData['routeNumber'] ?? ''),
                'route' => trim($inputData['route'] ?? ''),
                'stops' => trim($inputData['stops'] ?? ''),
                'price' => trim($inputData['price'] ?? ''),
                'priceperkm' => trim($inputData['priceperkm' ?? ''])
            ];

                    // Validate required fields
            if (empty($data['routeNumber']) || empty($data['route']) || empty($data['stops']) || empty($data['price']) || empty($data['priceperkm'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }
        // Call the model method to update the route
                if ($this->SuperAdminModel->updateRoute($data)) {
                    echo json_encode(['status' => 'success', 'message' => 'Route updated successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error updating the route.']);
                    http_response_code(500);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
                http_response_code(405);
            }
        }

    public function deleteRoute() {
        header('Content-Type: application/json');

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'),true);

            if(!empty($data['routeNumber'])){
                $routeNumber = $data['routeNumber'];

                if($this->SuperAdminModel->deleteRoute($routeNumber)){
                    echo json_encode(['status' => 'success', 'message' => 'Route deleted successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error deleting the Route']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Route Number is required']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }
//----------------------------------------------------------------------------------------------------------------------
                                    //support requests
//---------------------------------------------------------------------------------------------------------------------- 

    public function contacts() {
        $contact = $this->SuperAdminModel->getcontactsrequests();
        $data = [
            'contact' => $contact
        ];
        $this->view('pages/SuperAdmin/Contacts',$data);
    }

    public function markReplied()
    {
        // Make sure it's a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get raw JSON input
            $input = json_decode(file_get_contents('php://input'), true);

            if (isset($input['request_id'])) {
                $requestId = $input['request_id'];

                // Update the record
                if ($this->SuperAdminModel->setRepliedStatus($requestId)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Database update failed']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Invalid input']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        }
    }


//----------------------------------------------------------------------------------------------------------------------
                                    //Profile
//---------------------------------------------------------------------------------------------------------------------- 

    public function profile() {
        // Start the session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Assuming you have a session variable storing the current employee ID
        $currentEmpId = $_SESSION['employee_id'] ?? null;
         
        if ($currentEmpId) {
            $profile = $this->SuperAdminModel->getEmployeeDetails($currentEmpId);
            $data = [
                'profile' => $profile
            ];

            $this->view('pages/SuperAdmin/Profile', $data);
        } else {
            // Handle the case where the employee ID is not available in the session
            //die("Employee ID not found in session.");
            $this->view('pages/SuperAdmin/Profile');
        }
    }


//------------------------------------------------------------------------------------------------------------------------------------
    //boxex in the dashboard 

//------------------------------------------------------------------------------------------------------------------------------------

}
?>
