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
        $this->view('pages/SuperAdmin/Fleet',$data);
    }
    public function AddFleet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            // Collect data into an array
            $data = [
                'bus_id' => trim($_POST['bus_id']),
                'licence_id' => trim($_POST['licence_id']),
                'route_no' => trim($_POST['route_no']),
                'route' => trim($_POST['route']),
                'bus_type' => trim($_POST['bus_type']),
                'stops' => trim($_POST['stops']),
                'starts' => trim($_POST['starts']),
                'destination' => trim($_POST['destination']),
                'ratings' => trim($_POST['ratings']),
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Decode the JSON input
            $data = json_decode(file_get_contents('php://input'), true);
    
            if (!empty($data['busId'])) {
                $busId = $data['busId'];
    
                // Delete the bus
                if ($this->SuperAdminModel->deleteBus($busId)) {
                    echo json_encode(['status' => 'success', 'message' => 'Bus deleted successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error deleting the bus']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Bus ID is required']);
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
                'busId' => $_POST['bus_id'],
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
    
    // In your Controller (e.g., SuperAdminController.php)

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
    

    


//----------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function bookings() {
        $guestbookings = $this->SuperAdminModel->getGuestBookings();
        $registerbookings = $this->SuperAdminModel->getRegisterBookings();
        $data = [
            'book' => $guestbookings,
            'book1' => $registerbookings
        ];
        $this->view('pages/SuperAdmin/Bookings',$data);

    }

    public function updatefleet() {
        // Retrieve the bus ID from the GET request
        $busId = isset($_GET['busId']) ? $_GET['busId'] : null;
    
        if ($busId) {
            // Fetch the bus details using the model
            $busDetails = $this->SuperAdminModel->getBusById($busId);
    
            // Pass the details to the view
            if ($busDetails) {
                $this->view('pages/SuperAdmin/Updatefleet', ['busDetails' => $busDetails]);
            } else {
                die("Bus not found.");
            }
        } else {
            die("Bus ID not provided.");
        }
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

    public function users() {
        $this->view('pages/SuperAdmin/Users');
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
