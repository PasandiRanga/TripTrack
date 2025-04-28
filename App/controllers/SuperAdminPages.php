<?php
class SuperAdminPages extends Controller {
    
    private $SuperAdminModel;

    public function __construct() {
    
        $this->SuperAdminModel = $this->model('M_SuperAdminPages'); 
    }

    public function home() {
            $hasNewDelays = $this->SuperAdminModel->hasUnviewedDelays();
            $totalcustomers = $this->SuperAdminModel->getRegisteredCustomersReport();
            $totalbookings = (int) $this->SuperAdminModel->getTotalMonthlyBookings();
            
            $totalbookingsIncome = $this->SuperAdminModel->getTotalBookingsIncome();
            $totalcancellationIncome = $this->SuperAdminModel->getTotalRefunds();
            $totalcancellationfees = $this->SuperAdminModel->getTotalCancellationFees();
            
            $totalincome = $totalbookingsIncome - $totalcancellationIncome + $totalcancellationfees;

            
            $totalSchedules = $this->SuperAdminModel->getTotalSchedules();
            
            $chartbookings =  $this->SuperAdminModel->getLast7DaysBookingCounts();
            $chartcancellations = $this->SuperAdminModel->getLast7DaysCancellationCounts();

            
            $routes = $this->SuperAdminModel->getTopRoutesIncome();
            
            $data = [
                'total_income' => $totalincome,
                'total_bookings_income' => $totalbookingsIncome,
                'total_cancellation_income' => $totalcancellationIncome,
                'total_cancellation_fees' => $totalcancellationfees,
                'total_customers' => $totalcustomers,
                'total_bookings' => $totalbookings,
                'total_schedules' => $totalSchedules,
                'chartbookings' => $chartbookings,
                'chartcancellations' => $chartcancellations,
                'routes' => $routes,
                'hasNewDelays' => $hasNewDelays,
            ];



        $this->view('pages/SuperAdmin/Dashboard',$data);
    }

    public function fleet() {
        $bus = $this->SuperAdminModel->getBus();
        $scheduledbuses = $this->SuperAdminModel->getScheduledBusID();

        $scheduledLicenseIDs = array_column($scheduledbuses, 'License_id');

        $freeBuses = array_filter($bus, function($b) use ($scheduledLicenseIDs) {
            return !in_array($b['License_id'], $scheduledLicenseIDs);
        });
        $data = [
            'bus' => $bus,
            'scheduledbuses' => $scheduledbuses,
            'freeBuses' => $freeBuses
        ];
        $this->view('pages/SuperAdmin/Fleet', $data);
    }

    public function AddFleet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=UTF-8');

            $inputData = json_decode(file_get_contents("php://input"), true);

            error_log("Input Data: " . json_encode($inputData));

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
                http_response_code(400);
                exit();
            }

            
            $data = [
                'License_id' => trim($inputData['License_id'] ?? ''),
                'routeNumber' => trim($inputData['routeNumber'] ?? ''),
                'start_location' => trim($inputData['start_location'] ?? ''),
                'destination' => trim($inputData['destination'] ?? ''),
                'passengers' => trim($inputData['passengers'] ?? ''),
                'price' => trim($inputData['price'] ?? ''),
                'priceperkm' => trim($inputData['priceperkm'] ?? '')
            ];

            
            if (empty($data['License_id']) || empty($data['routeNumber']) || empty($data['start_location']) || empty($data['destination']) || empty($data['passengers']) || empty($data['price']) || empty($data['priceperkm'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

             
            if (!preg_match('/^[A-Z]{2}-\d{4}$/', $data['License_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'License ID must be in the format XX-1234 (two capital letters, a dash, and four digits).']);
                http_response_code(400);
                exit();
            }

           
            if ($this->SuperAdminModel->isLicenseIdExists($data['License_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'License ID already exists.']);
                http_response_code(409);
                exit();
            }

        
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
        
            $this->view('pages/SuperAdmin/Addfleet', $data);
        }
    }

    public function deleteBus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $data = json_decode(file_get_contents('php://input'), true);

            if (!empty($data['License_id'])) {
                $licence_id = $data['License_id'];

                
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
            
            $inputData = json_decode(file_get_contents('php://input'), true);

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
                http_response_code(400);
                exit();
            }

            
            $data = [
                'License_id' => trim($inputData['License_id'] ?? ''),
                'routeNumber' => trim($inputData['routeNumber'] ?? ''),
                'start_location' => trim($inputData['start_location'] ?? ''),
                'destination' => trim($inputData['destination'] ?? ''),
                'passengers' => trim($inputData['passengers'] ?? ''),
                'price' => trim($inputData['price'] ?? ''),
                'priceperkm' => trim($inputData['priceperkm'] ?? '')
            ];

            
            if (empty($data['License_id']) || empty($data['routeNumber']) || empty($data['start_location']) || empty($data['destination']) || empty($data['passengers']) || empty($data['price']) || empty($data['priceperkm'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

            
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
            
            $data = json_decode(file_get_contents('php://input'), true);
            $searchQuery = $data['searchQuery'] ?? '';

            
            error_log("Search Query Received: " . $searchQuery);

            
            $results = $this->SuperAdminModel->searchFleet($searchQuery);

            if ($results) {
                echo json_encode(['status' => 'success', 'data' => $results]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No results found.']);
            }
        } else {
            
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }

    public function getTotalBuses() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $data = json_decode(file_get_contents('php://input'), true);
            $searchTerm = $data['searchTerm'] ?? '';
            

            error_log("Search Query Received: " . $searchTerm);
            
            $result = $this->SuperAdminModel->getBusCount($searchTerm);

            if ($result) {
                echo json_encode(['status' => 'success', 'data' => $result]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No results found.']);
            }
           
        } else {
        
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            exit;
        }
    }



    public function getAllFleet() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            
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

    




    public function bookings() {
        $guestbookings = $this->SuperAdminModel->getPastGuestBookings();
        $registerbookings = $this->SuperAdminModel->getPastRegisterBookings();
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

    $data = [
        'totalBookings' => $this->SuperAdminModel->getTotalBookingsReport(),
        'guestBookings' => $this->SuperAdminModel->getGuestBookingsReport(),
        'registeredBookings' => $this->SuperAdminModel->getRegisteredBookingsReport(),
        'totalCancellations' => $this->SuperAdminModel->getTotalCancellationsReport(),
        'onlineCancellations' => $this->SuperAdminModel->getOnlineCancellationsReport(),
        'cashCancellations' => $this->SuperAdminModel->getCashCancellationsReport(),
        'totalGuestIncome' => $this->SuperAdminModel->getGuestBookingIncomeReport(),
        'totalRegisteredIncome' => $this->SuperAdminModel->getRegisteredBookingIncomeReport(),
        'totalBookingIncome' => $this->SuperAdminModel->getTotalBookingsIncomeReport(),
        'registeredCustomers' => $this->SuperAdminModel->getRegisteredCustomersReport(),
        'totalEmployees' => $this->SuperAdminModel->getTotalEmployeesReport(),
        'totalDrivers' => $this->SuperAdminModel->getTotalDriversReport(),
        'totalConductors' => $this->SuperAdminModel->getTotalConductorsReport(),
        'totalAdmins' => $this->SuperAdminModel->getTotalAdminsReport(),
        'totalRoutes' => $this->SuperAdminModel->getTotalRoutesReport(),
        'totalBuses' => $this->SuperAdminModel->getTotalBusesReport(),
        'totalSchedules' => $this->SuperAdminModel->getTotalSchedulesReport(),
        'totalRefunds' => $this->SuperAdminModel->getTotalRefundsReport(),
        'totalCancellationFees' => $this->SuperAdminModel->getTotalCancellationFeesReport(),

    ];

    
    $this->view('pages/SuperAdmin/Reports', $data);
}






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
                'rating_id' => trim($inputData['rating_id'] ?? ''),
                'reply' => trim($inputData['reply'] ?? '')
            ];

            if (empty($data['rating_id']) || empty($data['reply'])) {
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
            
            http_response_code(405); 
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            exit();
        }
    }

    public function replyreviews() {
        $this->view('pages/SuperAdmin/ReplyReviews');
    }




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
            $availableBuses = $this->SuperAdminModel->getAvailableBuses();
            $data = [
                'bus' => $bus,
                'availableBuses' => $availableBuses
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



    public function leaverequests() {
        $this->view('pages/SuperAdmin/LeaveRequests');
    }




    public function notifications() {
        $delays = $this->SuperAdminModel->getBusDelays();
        $data = [
            'delays' => $delays
        ];
        $this->view('pages/SuperAdmin/Notifications', $data);
    }

   

    public function markDelays() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $delayId = $_POST['delay_id'];

            $this->SuperAdminModel->markDelayAsViewed($delayId);

            
            header("Location: " . URLROOT . "/SuperAdminPages/notifications");
            exit;
        }
    }


    public function sendnotifications() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=UTF-8');

            $inputData = json_decode(file_get_contents("php://input"), true);

            
            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
                http_response_code(400);
                exit();
            }

            
            $data = [
                'employee_id'    => trim($inputData['employee_id'] ?? ''),
                'employee_name'  => trim($inputData['employee_name'] ?? ''),
                'employee_type'  => trim($inputData['employee_role'] ?? ''),
                'title'          => trim($inputData['title'] ?? ''),
                'message'        => trim($inputData['message'] ?? ''),
            ];

            
            if (
                empty($data['employee_id']) ||
                empty($data['employee_name']) ||
                empty($data['employee_type']) ||
                empty($data['title']) ||
                empty($data['message'])
            ) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

            
            if ($this->SuperAdminModel->sendNotification($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Notification sent successfully.']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database Error: Cannot send notification.']);
                http_response_code(500);
                exit();
            }

        } else {

            
            $employees = $this->SuperAdminModel->getEmployee_notification();

            $data = [
                'employees' => $employees
            ];

            $this->view('pages/SuperAdmin/Sendnotifications', $data);
        }
    }





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

            
            if (empty($data['contactNo'])) {
                $data['contactNo_err'] = 'Please enter a contact number';
            } elseif (!ctype_digit($data['contactNo'])) {
                $data['contactNo_err'] = 'The contact number must contain only numbers';
            } elseif (strlen($data['contactNo']) !== 10) {
                $data['contactNo_err'] = 'The contact number must be exactly 10 digits long';
            } elseif ($data['contactNo'][0] !== '0') {
                $data['contactNo_err'] = 'The contact number must start with 0';
            }

        
            if (empty($data['nic'])) {
                $data['nic_err'] = 'Please enter a NIC';
            } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                $data['nic_err'] = 'NIC must be exactly 12 digits or 9 digits followed by "V" at the end';
            } else {
                if ($this->SuperAdminModel->findUserByNIC($data['nic'])) {
                    $data['nic_err'] = 'This NIC is already registered';
                }
            }

    
            if (empty($data['address'])) {
                $data['address_err'] = 'Please enter an address';
            }

        
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email format (e.g., abc@gmail.com)';
            } else {
                if ($this->SuperAdminModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'This email is already registered';
                }
            }

        
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
        
            $inputData = json_decode(file_get_contents('php://input'), true);

            if (!$inputData) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
                http_response_code(400);
                exit();
            }

        
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

        
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter a name.';
            }


            if (empty($data['nic'])) {
                $data['nic_err'] = 'Please enter a NIC.';
            } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                $data['nic_err'] = 'NIC must be 12 digits or 9 digits followed by "V".';
            }

        
            if (empty($data['address'])) {
                $data['address_err'] = 'Please enter an address.';
            }

        
            if (empty($data['contactNo'])) {
                $data['contactNo_err'] = 'Please enter a contact number.';
            } elseif (!ctype_digit($data['contactNo']) || strlen($data['contactNo']) !== 10 || $data['contactNo'][0] !== '0') {
                $data['contactNo_err'] = 'Contact number must be 10 digits and start with 0.';
            }

    
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email.';
            }

        
            if (empty($data['name_err']) && empty($data['nic_err']) && empty($data['address_err']) && empty($data['contactNo_err']) && empty($data['email_err'])) {
            
                if ($this->SuperAdminModel->updateEmployee($data)) {
                    echo json_encode(['status' => 'success', 'message' => 'Employee updated successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error updating the employee.']);
                    http_response_code(500);
                }
            } else {
            
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
        $emps = $this->SuperAdminModel->getEmployee();
        $data = [
            'emp' => $emps
        ];
        $this->view('pages/SuperAdmin/Employees',$data);
    }

    public function replyleaves() {
        $this->view('pages/SuperAdmin/ReplyLeaves');
    }





    public function assigns() {
        $assign = $this->SuperAdminModel->getAssigns();
        $data =  [
            'assign' => $assign
        ];
        $this->view('pages/SuperAdmin/Assigns',$data);
    }

    public function addassigns() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            header('Content-Type: application/json');

            
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

            
            error_log("Assign Data: " . json_encode($data));

            
            if (empty($data['scheduleId']) || empty($data['driverName']) || empty($data['conductorName']) || empty($data['driver_id']) || empty($data['conductor_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required controller.']);
                http_response_code(400);
                exit();
            }

            
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
            
            $allSchedules = $this->SuperAdminModel->getScheduleID();

            
            $assignedSchedules = $this->SuperAdminModel->getAssignedSchedules();

            
            $availableSchedules = array_filter($allSchedules, function($schedule) use ($assignedSchedules) {
                return !in_array($schedule['scheduleId'], array_column($assignedSchedules, 'scheduleId'));
            });

           
            $availablesDrivers = $this->SuperAdminModel->getAvailableDrivers();


            $availableConductors = $this->SuperAdminModel->getAvailableConductors();
            
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

            
            error_log("Controller updateAssign Data: " . json_encode($data));

            
            if (empty($data['scheduleId']) || empty($data['driverName']) || empty($data['conductorName']) || empty($data['driver_id']) || empty($data['conductor_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }

            
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

            
            if (empty($data['routeNumber']) || empty($data['route']) || empty($data['stops']) || empty($data['price']) || empty($data['priceperkm'])) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                http_response_code(400);
                exit();
            }
    
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




    public function contacts() {
        $contact = $this->SuperAdminModel->getcontactsrequests();
        $data = [
            'contact' => $contact
        ];
        $this->view('pages/SuperAdmin/Contacts',$data);
    }

    public function markReplied()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $input = json_decode(file_get_contents('php://input'), true);

            if (isset($input['request_id'])) {
                $requestId = $input['request_id'];

                
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







    public function profile() {
    
        $profile = $this->SuperAdminModel->findEmployeeById($_SESSION['user_id']);

            $this->view('pages/SuperAdmin/Profile', $profile);
    }

    public function updateProfileImage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $file = $_FILES['profile_image'];

            if ($file['error'] === 0 && in_array($file['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
                $imageName = uniqid() . '_' . $file['name'];
                move_uploaded_file($file['tmp_name'], APPROOT . "/../public/images/profileImages/" . $imageName);

                
                $_SESSION['user_profile_image'] = $imageName;
                $this->SuperAdminModel->updateProfileImage($_SESSION['user_id'], $imageName);

                redirect('SuperAdminPages/profile');
            } else {
                die('Invalid image upload');
            }
        }
    }




}
?>
