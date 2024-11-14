<?php
    //Class name should be the same as the file name of the model
    class M_RegisteredPages {
        //Declare a variable to grant access to the database
        private $db;

        //whenever the script is called we need to instantiate the data base class in Database.php
        public function __construct(){
            //Instantiate the database class
            $this->db = new Database();
            //we need to connect the controller with the model as well so that we can use the database 
            //In this case controller is GuestPages.php so whenever GuestPages.php is constructed M_GuestPages.php will also be constructed
            //You can do this in controller class statement model
            //We should connect this model with the corressponding controller

        }

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

        public function findUserById($userId){
            $this->db->query('SELECT * FROM customer WHERE User_id=:userId');
            $this->db->bind("userId",$userId);

            $row = $this->db->single();

            if($this->db->rowCount()>0){
                error_log(print_r($row, true));
                return $row;
            }
            else{
                return false;  
            }
        }
    }
?>