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

    public function addemployees(){
        $this->view('pages/SuperAdmin/addemployees');
    }

    public function addemp(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // echo "<script>console.log(" . json_encode($_POST) . ");</script>";
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';
        
            $data = [
                'name' => trim($_POST['name']),
                'nic' => trim($_POST['nic']),
                'address' => trim($_POST['address']),
                'contactNo' => trim($_POST['contactNo']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'role' => trim($_POST['role']),

                'name_err' => '',
                'contactNo_err' => '',
                'nic_err' => '',
                'address_err' => '',
                'email_err' => '',
                'password_err' => '',
                'role_err' => ''
            ];

            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';
         
        

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter a name';
            }
    
            // Validate contact number
            if (empty($data['contactNo'])) {
                $data['contactNo_err'] = 'Please enter a contact number'; // Check if the field is empty
            } elseif (!ctype_digit($data['contactNo'])) {
                $data['contactNo_err'] = 'The contact number must contain only numbers'; // Check if it contains only numeric characters
            } elseif (strlen($data['contactNo']) !== 10) {
                $data['contactNo_err'] = 'The contact number must be exactly 10 digits long'; // Check if it is exactly 10 digits
            } elseif ($data['contactNo'][0] !== '0') {
                $data['contactNo_err'] = 'The contact number must start with 0'; // Check if it starts with 0
            }

    
            // Validate NIC
            if (empty($data['nic'])) {
                $data['nic_err'] = 'Please enter a NIC';
            } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                // Check if the NIC is either 12 digits or 11 digits followed by "V"
                $data['nic_err'] = 'NIC must be exactly 12 digits or 9 digits followed by "V" at the end';
            }else {
                // Check if NIC is already registered
                if ($this->SuperAdminModel->findUserByNIC($data['nic'])) {
                    $data['nic_err'] = 'This NIC is already registered';
                }
            }


            //Validate the Address
            if (empty($data['address'])) {
                $data['address_err'] = 'Please enter an address';
            }
    

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email format (e.g., abc@gmail.com)';
            } else {
                // Check if email is already registered
                if ($this->SuperAdminModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'This email is already registered';
                }
            }

    
            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            } elseif (strlen($data['password']) < 8) {
                // Check if the password is at least 8 characters long
                $data['password_err'] = 'Password must be at least 8 characters long';
            } elseif (!preg_match('/[A-Z]/', $data['password'])) {
                // Check if the password contains at least one uppercase letter
                $data['password_err'] = 'Password must contain at least one uppercase letter';
            } elseif (!preg_match('/[a-z]/', $data['password'])) {
                // Check if the password contains at least one lowercase letter
                $data['password_err'] = 'Password must contain at least one lowercase letter';
            } elseif (!preg_match('/\d/', $data['password'])) {
                // Check if the password contains at least one number
                $data['password_err'] = 'Password must contain at least one number';
            } elseif (!preg_match('/[\W_]/', $data['password'])) {
                // Check if the password contains at least one special character (symbol)
                $data['password_err'] = 'Password must contain at least one special character';
            }

            // echo '<pre>';
            // print_r($data);
            // print_r((empty($data['name_err']) && empty($data['contactNo_err']) && empty($data['nic_err']) &&
            // empty($data['address_err']) && empty($data['email_err']) && empty($data['password_err'])));
            // echo '</pre>';
              

            // Register the user if no errors are present
            if (empty($data['name_err']) && empty($data['contactNo_err']) && empty($data['nic_err']) &&
            empty($data['address_err']) && empty($data['email_err']) && empty($data['password_err'])) {

                // echo '<pre>';
                // print_r($data);
                // echo '</pre>';
                
                

                // Hash the password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // echo '<pre>';
                // print_r($data);
                // echo '</pre>';
                
                // Debug output
                var_dump($data['password']); // This should display a hashed string


                // Register the user
                if ($this->SuperAdminModel->addemployee($data)) {
                    // echo '<pre>';
                    // print_r($this->SuperAdminModel->addemployee($data));
                    // echo '</pre>';
                    $_SESSION['success_message'] = 'Employee added successfully!';
                    header('Location: ' . URLROOT . '/SuperAdminPages/employees' );
                    exit();  // Make sure no further code executes after the redirect
                } else {
                    // echo '<pre>';
                    // print_r($this->SuperAdminModel->addemployee($data));
                    // echo '</pre>';
                    // exit();
                    die('Something went wrong');  // Handle errors in registration
                }
            } else {

                header('Location: ' . URLROOT . '/SuperAdminPages/employees');
            }

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

    public function assigns() {
        $this->view('pages/SuperAdmin/Assigns');
    }

    public function addassigns() {
        $this->view('pages/SuperAdmin/Addassigns');
    }
}
?>
