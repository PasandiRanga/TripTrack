<?php
    //Class name should be the same as the file name of the model
    class M_GuestPages {
        //Declare a variable to grant access to the database
        private $db;

        //whenever the script is called we need to instantiate the data base class
        public function __construct(){
            //Instantiate the database class
            $this->db = new Database();
        }

            //We should connect this model with the corressponding controller

            //Register the user
            public function register($data){
                $this->db->query('INSERT INTO customer(Name,Email,NIC,Address,Contact_number,Password,Profile_image) VALUES(:name,:email,:nic,:address,:number,:password,:profile_image)');
                $this->db->bind(':name',$data['name']);
                $this->db->bind(':email',$data['email']);
                $this->db->bind(':nic',$data['nic']);
                $this->db->bind(':address',$data['address']);
                $this->db->bind(':number',$data['number']);
                $this->db->bind(':password',$data['password']);
                $this->db->bind(':profile_image',$data['profile_image_name']);

                if($this->db->execute()){
                    return true;
                }
                else{
                    return false;
                }
            }

            public function findUserByEmail($email){
                $this->db->query('SELECT * FROM customer WHERE Email=:email');
                $this->db->bind(":email",$email);

                $row = $this->db->single();

                if($this->db->rowCount()>0){
                    return true;
                }
                else{
                    return false;  
                }
            }

            //login the user
            public function login($emailOrUsername, $password) {
                // Define tables and their respective username/email fields
                $userTables = [
                    'customer' => 'Email',
                    'System_Admin' => 'Email',
                    'Conductor' => 'Employee_username',
                    'Driver' => 'Employee_username',
                ];
            
                foreach ($userTables as $table => $field) {
                    // Query each table for the provided email/username
                    $this->db->query("SELECT * FROM {$table} WHERE {$field} = :identifier");
                    $this->db->bind(':identifier', $emailOrUsername);
            
                    $row = $this->db->single();
            
                    if ($row && isset($row['Password'])) {
                        $hashed_password = $row['Password'];
            
                        if (password_verify($password, $hashed_password)) {
                            // Add the user type to the result for differentiation
                            return [
                                'user_data' => $row,
                                'user_table' => $table
                            ];
                        }
                    }
                }
            
                // If no match is found in any table
                return false;
            }
            


            //we need to connect the controller with the model as well so that we can use the database 
            //In this case controller is GuestPages.php so whenever GuestPages.php is constructed M_GuestPages.php will also be constructed
            //You can do this in controller class statement model
            //We should connect this model with the corressponding controller

        

        //Get the schedule from the database
        public function getSchedule(){
            try {
                // If you need all columns, this is fine
                $this->db->query('SELECT * FROM schedule');
                return $this->db->resultSet();
            } catch (Exception $e) {
                // Log or handle error
                error_log("Error fetching schedule: " . $e->getMessage());
                // Log to console
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return []; // Return an empty array on error
            }
        }
      
        public function getBusDetails(){
            try {
                // If you need all columns, this is fine
                $this->db->query('SELECT * FROM bus');
                return $this->db->resultSet();
            } catch (Exception $e) {
                // Log or handle error
                error_log("Error fetching bus details: " . $e->getMessage());
                // Log to console
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return []; // Return an empty array on error
            }
        }
      
        public function getDistance(){
            try {
                // If you need all columns, this is fine
                $this->db->query('SELECT * FROM distancefromstart');
                return $this->db->resultSet();
            } catch (Exception $e) {
                // Log or handle error
                error_log("Error fetching bus details: " . $e->getMessage());
                // Log to console
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return []; // Return an empty array on error
            }
        }

        
    }
?>