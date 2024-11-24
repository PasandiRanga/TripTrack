<?php
    class M_ConductorPages {
        //Declare a variable to grant access to the database
        private $db;

        //whenever the script is called we need to instantiate the data base class

        public function __construct(){
            //Instantiate the database class
            $this->db = new Database();

            //We should connect this model with the corressponding controller

        }

        public function getScheduleByEmployeeId($employee_id) {
            $this->db->query("SELECT schedule_id FROM schedule WHERE conductor_id = :employee_id OR driver_id = :employee_id");

            $this->db->bind(':employee_id', $employee_id);

            return $this->db->resultSet();
        }

        public function getScheduleDetailsById($schedule_id) {
            $this->db->query("SELECT License_id, date, departureTime FROM schedule WHERE schedule_id = :schedule_id");

            $this->db->bind(':schedule_id', $schedule_id);

            return $this->db->single();
        }

        public function findEmployeeById($employee_id){
            $this->db->query('SELECT * FROM employee WHERE employee_id=:employee_id');

            $this->db->bind(":employee_id",$employee_id);

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