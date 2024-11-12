 <?php
    class GuestPages extends Controller {
        //so that it will inherit all the functionalities of the Controller class
        private $GuestpagesModel;
        
        public function __construct() {
            //Call the model method and assign it to the pagesModel variable
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
            $this->view('pages/GuestUser/home');
        }

        public function contact() {
            $this->view('pages/GuestUser/contactus');
        }
        public function busLayout() {
            $this->view('inc/Components/BusLayout/BusLayout');
        }
        public function GuestReceipt() {
            $this->view('inc/Components/Receipt/GuestReceipt');
        }

        public function GuestSignUp() {
            if($_SERVER['REQUEST_METHOD']=='POST'){
                //Form is submitting
                //validate the data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                //Input data
                $data=[
                    'name'=>trim($_POST['name']),
                    'number'=>trim($_POST['number']),
                    'nic'=>trim($_POST['nic']),
                    'address'=>trim($_POST['address']),
                    'email'=>trim($_POST['email']),
                    'password'=>trim($_POST['password']),
                    'confirm'=>trim($_POST['confirm']),
     
                    'name_err'=>'',
                    'number_err'=>'',
                    'nic_err'=>'',
                    'address_err'=>'',
                    'email_err'=>'',
                    'password_err'=>'',
                    'confirm_err'=>'',
                ];
                //validate each input

                //validate name
                if(empty($data['name'])){
                    $data['name_err']='Please enter a name';
                }

                //validate number
                if(empty($data['number'])){
                    $data['number_err']='Please enter a contact number';
                }

                //validate nic
                if(empty($data['nic'])){
                    $data['nic_err']='Please enter a NIC';
                }

                //validate address
                if(empty($data['address'])){
                    $data['address_err']='Please enter a address';
                }
 
                //validate email
                if(empty($data['email'])){
                    $data['email_err']='Please enter a email';
                }
                else{
                    //check email is already registered or not
                    if( $this->GuestpagesModel->findUserByEmail($data['email'])){
                        $data['email_err']='This email is already registered';

                    }
                }

                //validate password
                if(empty($data['password'])){
                    $data['password_err']='Please enter a password';
                }
                else if(empty($data['confirm'])){
                    $data['confirm_err']='Please confirm the password';
                }
                else{
                    if($data['password'] != $data['confirm']){
                        $data['confirm_err']='Password are not matching';
                    }
                }

                //validation is completed and no error,the register the user
                if(empty($data['name_err']) && empty($data['number_err']) && empty($data['address_err']) && empty($data['nic_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_err'])){
                    //Hash the password
                    $data['password']=password_hash($data['password'],PASSWORD_DEFAULT);

                    //Register User
                    if($this->GuestpagesModel->register($data)){
                        die('User is registered');
                    }
                    else{
                        die('Somthing went wrong');
                    }
                }
                else{
                    //Load view
                    $this->view('inc/Components/SignUp/signUp');
                }
            }
            else{
                //initial form
                $data=[
                    'name'=>'',
                    'number'=>'',
                    'nic'=>'',
                    'address'=>'',
                    'email'=>'',
                    'password'=>'',
                    'confirm'=>'',
     
                    'name_err'=>'',
                    'number_err'=>'',
                    'nic_err'=>'',
                    'address_err'=>'',
                    'email_err'=>'',
                    'password_err'=>'',
                    'confirm_err'=>'',
                ];
            }
            //Load view
            $this->view('inc/Components/SignUp/signUp');
        }

        public function BusBooking() {
            $busId = isset($_GET['busId']) ? $_GET['busId'] : null;
            
            if ($busId === null) {
                echo "Bus ID is missing!";
                exit;
            }
        
            $data = ['busId' => $busId];
            
            $this->view('pages/GuestUser/BusBooking', $data);
        }
        
        

    }  
?>
