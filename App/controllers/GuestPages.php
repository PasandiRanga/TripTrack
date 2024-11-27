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
                    'profile_image'=>$_FILES['profile_image'],
                    'profile_image_name'=>time().'_'.$_FILES['profile_image']['name'],
                    'name' => trim($_POST['name']),
                    'number' => trim($_POST['number']),
                    'nic' => trim($_POST['nic']),
                    'address' => trim($_POST['address']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm' => trim($_POST['confirm']),

                    'profile_image_err'=>'',
                    'name_err' => '',
                    'number_err' => '',
                    'nic_err' => '',
                    'address_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_err' => ''
                ];

                //validate profile image and upload

                if ($data['profile_image'] && $data['profile_image']['tmp_name']) {
                    if (!uploadImage($data['profile_image']['tmp_name'], $data['profile_image_name'], '/images/profileImages/')) {
                        $data['profile_image_err'] = 'Profile image uploading unsuccessful';
                    }
                } else {
                    // Optional: you can set a default profile image or leave it null.
                    $data['profile_image_name'] = './../../Public/images/profileImages/default.jpg'; // Replace with your actual default image filename, if applicable
                }
        
                // Perform validation and check if all fields are filled
                if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter a name';
                }
        
                // Validate contact number
                if (empty($data['number'])) {
                    $data['number_err'] = 'Please enter a contact number'; // Check if the field is empty
                } elseif (!ctype_digit($data['number'])) {
                    $data['number_err'] = 'The contact number must contain only numbers'; // Check if it contains only numeric characters
                } elseif (strlen($data['number']) !== 10) {
                    $data['number_err'] = 'The contact number must be exactly 10 digits long'; // Check if it is exactly 10 digits
                } elseif ($data['number'][0] !== '0') {
                    $data['number_err'] = 'The contact number must start with 0'; // Check if it starts with 0
                }

        
                // Validate NIC
                if (empty($data['nic'])) {
                    $data['nic_err'] = 'Please enter a NIC';
                } elseif (!preg_match('/^\d{12}$/', $data['nic']) && !preg_match('/^\d{9}V$/', $data['nic'])) {
                    // Check if the NIC is either 12 digits or 11 digits followed by "V"
                    $data['nic_err'] = 'NIC must be exactly 12 digits or 9 digits followed by "V" at the end';
                }else {
                    // Check if NIC is already registered
                    if ($this->GuestpagesModel->findUserByNIC($data['nic'])) {
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
                    if ($this->GuestpagesModel->findUserByEmail($data['email'])) {
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
                } elseif (empty($data['confirm'])) {
                    // Check if confirm password is empty
                    $data['confirm_err'] = 'Please confirm the password';
                } elseif ($data['password'] != $data['confirm']) {
                    // Check if the password and confirm password match
                    $data['confirm_err'] = 'Passwords do not match';
                }

        
                // Register the user if no errors are present
                if (empty($data['name_err']) && empty($data['number_err']) && empty($data['nic_err']) &&
                    empty($data['address_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_err']) && empty($data['profile_image_err'])) {
        
                    // Hash the password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    // Debug output
                    var_dump($data['password']); // This should display a hashed string

        
                    // Register the user
                    if ($this->GuestpagesModel->register($data)) {
                        header('Location: ' . URLROOT . '/GuestPages/home' );
                        exit();  // Make sure no further code executes after the redirect
                    } else {
                        die('Something went wrong');  // Handle errors in registration
                    }
                } else {
                    // Reload view with errors
                    $this->view('inc/Components/SignUp/signUp', $data);
                }
            } else {
                // Initialize empty form data for GET request
                $data = [
                    'profile_image'=>'',
                    'profile_image_name'=>'',
                    'name' => '',
                    'number' => '',
                    'nic' => '',
                    'address' => '',
                    'email' => '',
                    'password' => '',
                    'confirm' => '',
                    'profile_image_err'=>'',
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

        public function Login() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // echo '<pre>';
                // print_r($_POST);
                // echo '</pre>';
        
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'email_err' => '',
                    'password_err' => '',
                    'show_pop' => true
                ];

                // echo '<pre>';
                // print_r($data);
                // echo '</pre>';
        
                // Validate email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter the email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email address';
                }
        
                // Validate password
                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter the password';
                }
        
                // Check for errors
                if (empty($data['email_err']) && empty($data['password_err'])) {

                    // echo '<pre>';
                    // print_r($data);
                    // echo '</pre>';

                    // Attempt to log the user in
                    $loginResult = $this->GuestpagesModel->login($data['email'], $data['password']);

                    // echo '<pre>';
                    // print_r($loginResult);
                    // echo '</pre>';
         

                    if (!empty($loginResult)) {
                        // Login successful
                        $loggedUser = $loginResult['user_data'];
                        $userTable = $loginResult['user_table'];

                        // echo '<pre>';
                        // print_r($loggedUser);
                        // print_r($userTable);
                        // echo '</pre>';
                        // Create user session based on the table
                        $this->createUserSession($loggedUser, $userTable);
                    } else {
                        $data['password_err'] = 'Invalid credentials';
                        // echo '<pre>';
                        // print_r($data);
                        // echo '<pre>';
                        $this->view('pages/GuestUser/home', $data);
                    }
                } else {
                    // Load view with errors
                    $this->view('pages/GuestUser/home', $data);
                }
            } else {
                // Initialize form
                $data = [
                    'email' => '',
                    'password' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'show_pop' => false
                ];
                $this->view('pages/GuestUser/home', $data);
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




        public function createUserSession($loggedUser, $userTable) {
                // echo '<pre>';
                // print_r($loggedUser);
                // print_r($userTable);
                // echo '</pre>';   
            // Set common session data
            $_SESSION['user_id'] = $userTable === 'customer' ? $loggedUser['User_id'] : $loggedUser['employee_id'];
            $_SESSION['user_type'] = $userTable;
            $_SESSION['user_email'] = $userTable === 'customer' ? $loggedUser['Email'] : $loggedUser['email'];
            $_SESSION['user_name'] = $userTable === 'customer' ? $loggedUser['Name'] : $loggedUser['name'];


            // Determine redirect path based on user type
            switch ($userTable) {
                case 'customer':
                    $_SESSION['user_role'] = 'RegisteredUser';
                    $_SESSION['user_profile_image']=$loggedUser['Profile_image'];
                    header('Location: ' . URLROOT . '/RegisteredPages/home');
                    break;

                case 'employee':
                    if($loggedUser['role'] === 'Conductor' ){
                        $_SESSION['user_role'] = $loggedUser['role'];
                        header('Location: ' . URLROOT . '/ConductorPages/home');
                        break;
                    }elseif($loggedUser['role'] === 'Driver'){
                        $_SESSION['user_role'] = $loggedUser['role'];
                        header('Location: ' . URLROOT . '/ConductorPages/home');
                        break;
                    }elseif($loggedUser['role'] == 'Admin'){
                        $_SESSION['user_role'] = $loggedUser['role'];
                        header('Location: ' . URLROOT . '/SuperAdminPages/home');
                        break;
                    }
                default:
                    // Default case if userTable is unexpected
                    header('Location: ' . URLROOT . '/GuestPages/login');
                    break;
            }

            exit();
        }

        
        public function logout(){
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_role']);
            unset($_SESSION['user_profile_image']);
            session_destroy();
            header('Location: ' . URLROOT . '/GuestPages/home');
        }

        public function isLoggedin(){
            if(isset($_SESSION['user_id'])){
                return true;
            }
            else{
                return false;
            }
        }  

        public function test() {
            $this->view('pages/GuestUser/test');
        }

        public function test1() {
            $this->view('pages/GuestUser/test1');
        }

        public function submitRequest() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
                // Collect form data
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'contactNo' => trim($_POST['phone']),
                    'message' => trim($_POST['message']),
                    'name_err' => '',
                    'email_err' => '',
                    'contactNo_err' => '',
                    'message_err' => ''     
                ];

                // Validate name
                if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter your name';
                }

                // Validate email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter your email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'Please enter a valid email format (e.g., abc@gmail.com)';
                }

                // Validate contact number
                if (empty($data['contactNo'])) {
                    $data['contactNo_err'] = 'Please enter your contact number';
                } elseif (!ctype_digit($data['contactNo'])) {
                    $data['contactNo_err'] = 'The contact number must contain only numbers';
                } elseif (strlen($data['contactNo']) !== 10) {
                    $data['contactNo_err'] = 'The contact number must be exactly 10 digits long';
                } elseif ($data['contactNo'][0] !== '0') {
                    $data['contactNo_err'] = 'The contact number must start with 0';
                }

                 // Validate message
                if (empty($data['message'])) {
                    $data['message_err'] = 'Please enter a message';
                }

                
    
                // Validate inputs
                if (empty($data['name_err']) && empty($data['email_err']) && empty($data['contactNo_err']) && empty($data['message_err'])) {
                    // Save to database using model
                    if ($this->GuestpagesModel->addSupportRequest($data)) {
                        // Redirect on success
                        $_SESSION['success_message'] = "Your support request has been submitted successfully.";
                        header('Location: ' . URLROOT . '/GuestPages/contact?status=success');
                        exit();
                    } else {
                        // Handle database error
                        $data['error_message'] = "Something went wrong. Please try again.";
                        $this->view('pages/GuestUser/contactus', $data);
                    }
                } else {
                    
                    // Reload view with errors
                    $this->view('pages/GuestUser/contactus', $data);
                }
            } else {
                // Redirect if accessed directly
                header('Location: ' . URLROOT . '/GuestPages/contact');
                exit();
            }
        }
    }  

?>
