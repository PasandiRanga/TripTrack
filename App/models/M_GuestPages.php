<?php
    //Class name should be the same as the file name of the model
    class M_GuestPages {
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
    }
?>