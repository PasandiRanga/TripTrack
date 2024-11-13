 <?php
    class GuestPages extends Controller {
        //so that it will inherit all the functionalities of the Controller class
        private $GuestpagesModel;
        
        public function __construct() {
            //Call the model method and assign it to the pagesModel variable
            //Instantiated model inside the controller so that we can use the database
            $this->GuestpagesModel = $this->model('M_GuestPages');
        }

        public function index() {
            echo "This is the index method";
        }

        public function about() {
            //call a view
            $this->view('pages/GuestUser/aboutus');
            
        }

        public function home() {
            $schedule = $this->GuestpagesModel->getSchedule();
            
            // Retrieve bus details
            $bus = $this->GuestpagesModel->getBusDetails();

            $distance = $this->GuestpagesModel->getDistance();
            
            // Combine the schedule and bus details into a single data array
            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance
            ];

            // Call the home view with schedule data
            $this->view('pages/GuestUser/home', $data);
        }


        public function contact() {
            $this->view('pages/GuestUser/contactus');
        }
        public function busLayout() {
            $schedule = $this->GuestpagesModel->getSchedule();
            
            // Retrieve bus details
            $bus = $this->GuestpagesModel->getBusDetails();

            $distance = $this->GuestpagesModel->getDistance();
            
            // Combine the schedule and bus details into a single data array
            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance
            ];
            // var_dump($schedule); // To check if schedule data is loaded
            // var_dump($bus);

            $this->view('inc/Components/BusLayout/BusLayout', $data);
        }
        public function GuestReceipt() {
            $this->view('inc/Components/Receipt/GuestReceipt');
        }

        public function GuestSignUp() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
                // Initialize form data with user input and error placeholders
                $data = [
                    'name' => trim($_POST['name']),
                    'number' => trim($_POST['number']),
                    'nic' => trim($_POST['nic']),
                    'address' => trim($_POST['address']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm' => trim($_POST['confirm']),
                    'name_err' => '',
                    'number_err' => '',
                    'nic_err' => '',
                    'address_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_err' => ''
                ];
        
                // Perform validation and check if all fields are filled
                if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter a name';
                }
        
                if (empty($data['number'])) {
                    $data['number_err'] = 'Please enter a contact number';
                }
        
                if (empty($data['nic'])) {
                    $data['nic_err'] = 'Please enter a NIC';
                }
        
                if (empty($data['address'])) {
                    $data['address_err'] = 'Please enter an address';
                }
        
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter an email';
                } else {
                    // Check if email is already registered
                    if ($this->GuestpagesModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'This email is already registered';
                    }
                }
        
                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter a password';
                } else if (empty($data['confirm'])) {
                    $data['confirm_err'] = 'Please confirm the password';
                } else if ($data['password'] != $data['confirm']) {
                    $data['confirm_err'] = 'Passwords do not match';
                }
        
                // Register the user if no errors are present
                if (empty($data['name_err']) && empty($data['number_err']) && empty($data['nic_err']) &&
                    empty($data['address_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_err'])) {
        
                    // Hash the password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    // Debug output
                    var_dump($data['password']); // This should display a hashed string

        
                    // Register the user
                    if ($this->GuestpagesModel->register($data)) {
                        die('User is registered');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    // Reload view with errors
                    $this->view('inc/Components/SignUp/signUp', $data);
                }
            } else {
                // Initialize empty form data for GET request
                $data = [
                    'name' => '',
                    'number' => '',
                    'nic' => '',
                    'address' => '',
                    'email' => '',
                    'password' => '',
                    'confirm' => '',
                    'name_err' => '',
                    'number_err' => '',
                    'nic_err' => '',
                    'address_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_err' => '',
                ];
                // Load the sign-up form view
                $this->view('inc/Components/SignUp/signUp', $data);
            }
        }
        

        public function BusBooking() {
            // Retrieve the schedule from the model
            $schedule = $this->GuestpagesModel->getSchedule();
            
            // Retrieve bus details
            $bus = $this->GuestpagesModel->getBusDetails();

            $distance = $this->GuestpagesModel->getDistance();
            
            // Combine the schedule and bus details into a single data array
            $data = [
                'schedule' => $schedule,
                'bus' => $bus,
                'distance' => $distance
            ];
            
            // Pass the combined data array to the view
            $this->view('pages/GuestUser/BusBooking', $data);
        }
        
        
        

    }  
?>
