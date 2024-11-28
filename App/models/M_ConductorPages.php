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

        public function findEmployeeById($userId){
            $this->db->query('SELECT * FROM employee WHERE employee_id=:userId');

            $this->db->bind(":userId",$userId);

            $row = $this->db->single();

            if($this->db->rowCount()>0){
                error_log(print_r($row, true));
                return $row;
            }
            else{
                return false;  
            }
        }

        public function addDelays($data) {
            $this->db->query("INSERT INTO bus_delay (route_no, License_id, bus_route, dep_time, new_dep_time, reason)
                                VALUES (:routeNo, :busNo, :busRoute, :time, :newTime, :reason)");

            $this->db->bind(':routeNo', $data['routeNo']);
            $this->db->bind(':busNo', $data['busNo']);
            $this->db->bind(':busRoute', $data['busRoute']);
            $this->db->bind(':time', $data['time']);
            $this->db->bind(':newTime', $data['newTime']);
            $this->db->bind(':reason', $data['reason']);

            return $this->db->execute();
            
        }

        public function addLeaves($data) {
            $this->db->query("INSERT INTO employee_leave (employee_id, from_date, to_date, no_of_days, reason)
                                VALUES (:employeeId, :from_date, :to_date, :noOfDays, :reason)");

            $this->db->bind(':employeeId', $data['employeeId']);
            $this->db->bind(':from_date', $data['from_date']);
            $this->db->bind(':to_date', $data['to_date']);
            $this->db->bind(':noOfDays', $data['noOfDays']);
            $this->db->bind(':reason', $data['reason']);

            return $this->db->execute();
            
        }

        public function updateLeaves($data) {
            $this->db->query("UPDATE employee_leave SET
                employee_id = :employeeId,
                from_date = :from_date,
                to_date = :to_date,
                no_of_days = :noOfDays,
                reason = :reason
                where leave_id = :leave_id");

            $this->db->bind(':employeeId', $data['employeeId']);
            $this->db->bind(':leave_id', $data['leave_id']);
            $this->db->bind(':from_date', $data['from_date']);
            $this->db->bind(':to_date', $data['to_date']);
            $this->db->bind(':noOfDays', $data['noOfDays']);
            $this->db->bind(':reason', $data['reason']);

            return $this->db->execute();
            
        }

        public function getLeaveRequests($userId) {
            $this->db->query('SELECT * FROM employee_leave WHERE employee_id=:userId');

            $this->db->bind(":userId",$userId);

            return $this->db->resultSet();
        }

        public function getLeaveRequest($leave_id) {
            $this->db->query('SELECT * FROM employee_leave WHERE leave_id=:leave_id');

            $this->db->bind(":leave_id",$leave_id);

            return $this->db->resultSet();
        }

        public function deleteLeave($leave_id) {
            // Prepare the query
            $this->db->query('DELETE FROM employee_leave WHERE leave_id = :leave_id');
        
            // Bind the leave_id parameter
            $this->db->bind(':leave_id', $leave_id);
        
            // Execute the query and return true if successful, false otherwise
            return $this->db->execute();
        }
        
    }
?>