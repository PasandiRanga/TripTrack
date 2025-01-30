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

        public function validateBooking($bookingId, $userId) {
            $this->db->query("SELECT * FROM registeredbooking WHERE id = :bookingId AND User_id = :userId");
            $this->db->bind(':bookingId', $bookingId);
            $this->db->bind(':userId', $userId);
            return $this->db->single() ? true : false;
        }

        public function cancelBooking($bookingId, $scheduleId) {
            try {
                // Start a transaction
                $this->db->beginTransaction();
                
                //Get the seats in the booking
                $this->db->query("SELECT Seats FROM registeredbooking WHERE id = :bookingId");
                $this->db->bind(':bookingId', $bookingId);
                $seats = $this->db->single();
                if (!$seats) {
                    throw new Exception("Booking not found");
                }

                //Get the booked seats for schedule
                $this->db->query("SELECT bookedSeats FROM schedule WHERE scheduleId = :scheduleId");
                $this->db->bind(':scheduleId', $scheduleId);
                $bookedSeats = $this->db->single();
                if (!$bookedSeats) {
                    throw new Exception("Schedule not found");
                }

                 // Convert the comma-separated strings into arrays
                $seatsArray = explode(',', $seats['Seats']); // Convert booked seats into an array
                $bookedSeatsArray = explode(',', $bookedSeats['bookedSeats']); // Convert bookedSeats into an array

                // Remove the seats to be removed from the booked seats array
                $bookedSeatsArray = array_diff($bookedSeatsArray, $seatsArray);               

                // Convert the arrays back to comma-separated strings
                $bookedSeats = implode(',', $bookedSeatsArray); // Convert bookedSeats array back to string

                //Update the booked seat
                $this->db->query("UPDATE schedule SET bookedSeats = :bookedSeats WHERE scheduleId = :scheduleId");
                $this->db->bind(':bookedSeats', $bookedSeats);
                $this->db->bind(':scheduleId', $scheduleId);
                $this->db->execute();

                //Delete the booking
                $this->db->query("DELETE FROM registeredbooking WHERE id = :bookingId");
                $this->db->bind(':bookingId', $bookingId);
                $this->db->execute();

                // Commit the transaction
                $this->db->endTransaction();
                return true;
        
            } catch (Exception $e) {
                // Rollback the transaction in case of an error
                $this->db->rollBack();
                $_SESSION['error'] = $e->getMessage();
                error_log($e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return false;
            }
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

        public function getBookings($userId){
            try {
                // If you need all columns, this is fine
                $this->db->query('SELECT * FROM registeredbooking WHERE User_id = :userId');
                $this->db->bind(':userId', $userId);
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

        public function deleteAccount($userID) {
            // Prepare the DELETE query
            $this->db->query('DELETE FROM customer WHERE User_id = :userId');
            $this->db->bind(':userId', $userID);
        
            // Execute the query
            $this->db->execute();
        
            // Check if any rows were affected
            if ($this->db->rowCount() > 0) {
                return true; // Account deleted successfully
            } else {
                return false; // No rows affected (user ID might not exist)
            }
        }
        

        public function updateProfile($data) {
            // Update profile where the current email matches
            $this->db->query("UPDATE customer 
                              SET Name = :name, Email = :email, Contact_number = :contact_number, NIC = :nic, Address = :address 
                              WHERE Email = :current_email");
        
            // Bind parameters
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':contact_number', $data['contact_number']);
            $this->db->bind(':nic', $data['nic']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':current_email', $data['current_email']); // Use the current email for the condition
        
            // Execute and check success
            return $this->db->execute();
        }

        public function getReviewsByLicenseId($licenseId) {
            // Join BUS_reviews with customer table to fetch user details along with reviews
            $this->db->query("
                SELECT 
                    c.User_id,
                    c.Name,  
                    r.review 
                FROM 
                    bus_reviews r
                INNER JOIN 
                    customer c 
                ON 
                    r.User_id = c.User_id
                WHERE 
                    r.License_id = :License_id
            ");
            $this->db->bind(':License_id', $licenseId);
            return $this->db->resultSet();
        }


        public function addSupportRequest($data) {
            $this->db->query('INSERT INTO support_request (name, email, contactNo, message , User_id) VALUES (:name, :email, :contactNo, :message ,:User_id)');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':contactNo', $data['contactNo']);
            $this->db->bind(':message', $data['message']);
            $this->db->bind(':User_id', $data['userid']);
    
            // Execute the statement
            return $this->db->execute();
        }

        public function getRoute(){
            try {
                // If you need all columns, this is fine
                $this->db->query('SELECT * FROM routes');
                return $this->db->resultSet();
            } catch (Exception $e) {
                // Log or handle error
                error_log("Error fetching route details: " . $e->getMessage());
                // Log to console
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return []; // Return an empty array on error
            }
        }
        
        public function getScheduleByDate($date){
                $this->db->query('SELECT * FROM schedule WHERE date = :date');
                $this->db->bind(':date', $date);
                return $this->db->resultSet();
            }
        
    }
?>