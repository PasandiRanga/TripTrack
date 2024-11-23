<?php
class SuperAdminPages extends Controller {
    // Variable to hold the model
    private $SuperAdminModel;

    public function __construct() {
        // Call the model method and assign it to the SuperAdminModel variable
        $this->SuperAdminModel = $this->model('M_SuperAdminPages'); // Adjust the model name as per your implementation
    }

    public function home() {
        $this->view('pages/SuperAdmin/Dashboard');
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
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Collect data into an array
            $data = [
                'licence_id' => trim($_POST['licence_id']),
                'route_no' => trim($_POST['route_no']),
                'route' => trim($_POST['route']),
                'bus_type' => trim($_POST['bus_type']),
                'stops' => trim($_POST['stops']),
                'starts' => trim($_POST['starts']),
                'destination' => trim($_POST['destination']),
                'passengers' => trim($_POST['passengers']),
                'price' => trim($_POST['price']),
                'price_per_km' => trim($_POST['price_per_km'])
            ];

            // Call the model method to add the bus
            if ($this->SuperAdminModel->addBus($data)) {
                // Redirect to the fleet page on success
                header("Location: " . URLROOT . "/SuperAdminPages/fleet");
            } else {
                die("Error: Unable to add the bus.");
            }
        } else {
            // Load the view if not a POST request
            $this->view('pages/SuperAdmin/Addfleet');
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
            // Sanitize input
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Prepare the data array
            $data = [
                'License_id' => $_POST['License_id'],
                'routeNumber' => $_POST['routeNumber'],
                'route' => $_POST['route'],
                'busType' => $_POST['busType'],
                'stops' => $_POST['stops'],
                'start_location' => $_POST['start_location'],
                'destination' => $_POST['destination'],
                'rating' => $_POST['rating'],
                'passengers' => $_POST['passengers'],
                'price' => $_POST['price'],
                'priceperkm' => $_POST['priceperkm']
            ];

            // Call the model method to update the bus
            if ($this->SuperAdminModel->updateBus($data)) {
                header("Location: " . URLROOT . "/SuperAdminPages/fleet");
                exit;
            } else {
                die("Error: Unable to update the bus.");
            }
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

    public function updatefleet() {
        // Retrieve the license ID from the GET request
        $licence_id = isset($_GET['License_id']) ? $_GET['License_id'] : null;

        if ($licence_id) {
            // Fetch the bus details using the model
            $busDetails = $this->SuperAdminModel->getBusByLicenseId($licence_id);

            // Pass the details to the view
            if ($busDetails) {
                $this->view('pages/SuperAdmin/Updatefleet', ['busDetails' => $busDetails]);
            } else {
                die("Bus not found.");
            }
        } else {
            die("License ID not provided.");
        }
    }

//----------------------------------------------------------------------------------------------------------------------
                                    //bookings
//---------------------------------------------------------------------------------------------------------------------- 


    public function bookings() {
        $guestbookings = $this->SuperAdminModel->getGuestBookings();
        $registerbookings = $this->SuperAdminModel->getRegisterBookings();
        $data = [
            'book' => $guestbookings,
            'book1' => $registerbookings
        ];
        $this->view('pages/SuperAdmin/Bookings',$data);

    }

    public function reports() {
        $this->view('pages/SuperAdmin/Reports');
    }

    public function reviews() {
        $this->view('pages/SuperAdmin/Reviews');
    }

    public function schedule() {
        $this->view('pages/SuperAdmin/Schedule');
    }

    public function leaverequests() {
        $this->view('pages/SuperAdmin/LeaveRequests');
    }

    public function notifications() {
        $this->view('pages/SuperAdmin/Notifications');
    }


//----------------------------------------------------------------------------------------------------------------------
                                    //Employees
//---------------------------------------------------------------------------------------------------------------------- 

public function addemployees() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Get the user type
        $userType = trim($_POST['userType']);

        // Initialize data array with common fields
       /*
        $data = [
            'name' => ($userType === 'admin') ? trim($_POST['adminName']) : trim($_POST['employeeName']),
            'nic' => trim($_POST['nic']),
            'address' => trim($_POST['address']),
            'contactNo' => trim($_POST['contactNo']),
            'password' => password_hash(trim($_POST['password']), PASSWORD_BCRYPT),
        ];
        */
        // Add additional fields based on user type
        if ($userType === 'driver' || $userType === 'conductor') {
            // Driver/Conductor specific fields
            $data = [
                'name' => trim($_POST['employeeName']),
                'username' => trim($_POST['username']),
                'nic' => trim($_POST['nic']),
                'address' => trim($_POST['address']),
                'contactNo' => trim($_POST['contactNo']),
                'password' => password_hash(trim($_POST['password']), PASSWORD_BCRYPT),
            ];
        } elseif ($userType === 'admin') {
            // Admin specific fields
            $data = [
                'name' => trim($_POST['adminName']),
                'email' =>  trim($_POST['email']),
                'nic' => trim($_POST['nic']),
                'address' => trim($_POST['address']),
                'contactNo' => trim($_POST['contactNo']),
                'region' => trim($_POST['region']),
                'password' => password_hash(trim($_POST['password']), PASSWORD_BCRYPT),
            ];
        } else {
            // Invalid user type
            die("Error: Invalid user type.");
        }

        // Validation: Ensure all required fields are provided
        if (empty($data['name']) || empty($data['username']) || empty($data['nic']) || empty($data['address']) || empty($data['contactNo']) || empty($data['password'])) {
            die("Error: All fields are required.");
        }

        // Process the form based on user type
        $result = false;
        if ($userType === 'driver') {
            // Driver-specific insertion
            $result = $this->SuperAdminModel->addDriver($data);
        } elseif ($userType === 'conductor') {
            // Conductor-specific insertion
            $result = $this->SuperAdminModel->addConductor($data);
        } elseif ($userType === 'admin') {
            // Admin-specific insertion
            $result = $this->SuperAdminModel->addAdmin($data);
        }

        // Check if the user was added successfully
        if ($result) {
            header("Location: " . URLROOT . "/SuperAdminPages/employees");
            exit();
        } else {
            // Log the error for debugging
            error_log("Error: Unable to add the employee.");
            die("Error: Unable to add the employee.");
        }
    } else {
        // Load the view if not a POST request
        $this->view('pages/SuperAdmin/Addemployees');
    }
}


    public function employees() {
        $this->view('pages/SuperAdmin/Employees');
    }

    public function replyleaves() {
        $this->view('pages/SuperAdmin/ReplyLeaves');
    }

    public function replyreviews() {
        $this->view('pages/SuperAdmin/ReplyReviews');
    }

    public function addschedule() {
        $this->view('pages/SuperAdmin/Addschedule');
    }
}
?>
