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

        //Find user by email
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

        //Get schedule by date
        public function getScheduleByDate($date){
            $this->db->query('SELECT * FROM schedule WHERE date = :date');
            $this->db->bind(':date', $date);
            return $this->db->resultSet();
        }

        //Get the schedule from the database
        public function getSchedule(){
            try {
                $this->db->query('SELECT * FROM schedule');
                return $this->db->resultSet();
            } catch (Exception $e) {
                error_log("Error fetching schedule: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return []; 
            }
        }

            
        //login the user
        public function login($email, $password) {
            $userTables = [
                'customer' => 'Email',
                'employee' => 'email',
            ];

            foreach ($userTables as $table => $field) {
                $this->db->query("SELECT * FROM {$table} WHERE {$field} = :identifier");
                $this->db->bind(':identifier', $email);
                $row = $this->db->single();

                if($row){
                    if($table==='customer'){
                        $hashed_password = $row['Password'];
                    }else{
                        $hashed_password = $row['password'];
                    }

                        
                    if (password_verify($password, $hashed_password)) {
                        return [
                            'user_data' => $row,
                            'user_table' => $table
                        ];
                    }
                }
            }
            return false;
        }
        
        //Get the bus details from the database
        public function getBusDetails(){
            try {
                $this->db->query('SELECT * FROM bus');
                return $this->db->resultSet();
            } catch (Exception $e) {
                error_log("Error fetching bus details: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return []; 
            }
        }
      
        //Get the bus details from the database
        public function getDistance(){
            try {
                $this->db->query('SELECT * FROM distancefromstart');
                return $this->db->resultSet();
            } catch (Exception $e) {
                error_log("Error fetching bus details: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return []; 
            }
        }

        //Get the route details from the database
        public function getRoute(){
            try {
                $this->db->query('SELECT * FROM routes');
                return $this->db->resultSet();
            } catch (Exception $e) {
                error_log("Error fetching route details: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return [];
            }
        }

        //Find the user by nic
        public function findUserByNIC($nic){
            $this->db->query('SELECT * FROM customer WHERE NIC=:nic');
            $this->db->bind(":nic",$nic);
            $row = $this->db->single();
            if($this->db->rowCount()>0){
                return true;
            }
            else{
                return false;  
            }
        }

        //Add support request
        public function addSupportRequest($data) {
            $this->db->query('INSERT INTO support_request (name, email, contactNo, message) VALUES (:name, :email, :contactNo, :message)');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':contactNo', $data['contactNo']);
            $this->db->bind(':message', $data['message']);
    
            return $this->db->execute();
        }

        //Insert guest booking data
        public function createBooking($bookingData) {
            $this->db->query("SELECT * FROM GuestBooking WHERE schedule_id = :scheduleId AND selectedSeats = :selectedSeats");
            $this->db->bind(':userId', $bookingData['User_id']);
            $this->db->bind(':scheduleId', $bookingData['scheduleId']);
            $this->db->bind(':selectedSeats', implode(',', $bookingData['selectedSeats']));

            $existingBooking = $this->db->single();

            if ($existingBooking) {
                return; // Do not insert duplicate booking
            }

            date_default_timezone_set('Asia/Colombo');
            $currentDate = date('Y-m-d'); 
            $currentTime = date('H:i:s'); 



            $this->db->query("INSERT INTO GuestBooking (name, email, contact, nic, from_location, to_location, 
                                number_of_seats, selected_seats, total_price, paymentMethod, schedule_id, booking_date, booking_time)
                            VALUES (:name, :email, :contact, :nic, :fromLocation, :toLocation, 
                                :noOfSeats, :selectedSeats, :totalPrice, :paymentMethod, :scheduleId, :bookingDate, :bookingTime)");

            $this->db->bind(':name', $bookingData['name']);
            $this->db->bind(':email', $bookingData['email']);
            $this->db->bind(':contact', $bookingData['contact']);
            $this->db->bind(':nic', $bookingData['nic']);
            $this->db->bind(':fromLocation', $bookingData['from']);
            $this->db->bind(':toLocation', $bookingData['to']);
            $this->db->bind(':noOfSeats', $bookingData['noOfSeats']);
            $this->db->bind(':selectedSeats', $bookingData['selectedSeatsJSON']);
            $this->db->bind(':totalPrice', $bookingData['totalPrice']);
            $this->db->bind(':paymentMethod', $bookingData['paymentMethod']);
            $this->db->bind(':scheduleId', $bookingData['scheduleId']);
            $this->db->bind(':bookingDate', $currentDate); 
            $this->db->bind(':bookingTime', $currentTime); 

            return $this->db->execute();
        }


        //Update the booked seats in the schedule
        public function updateScheduleSeats($scheduleId, $selectedSeats) {
            // Fetch current booked seats and available seats
            $this->db->query("SELECT bookedSeats, availableSeats FROM schedule WHERE scheduleId = :scheduleId");
            $this->db->bind(':scheduleId', $scheduleId);
            $scheduleData = $this->db->single();

            $currentBookedSeats = $scheduleData['bookedSeats'];
            $availableSeats = (int)$scheduleData['availableSeats'];

            // Convert booked seats to array
            $currentBookedSeatsArray = $currentBookedSeats ? explode(',', $currentBookedSeats) : [];
            $selectedSeatsArray = is_array($selectedSeats) ? $selectedSeats : explode(',', $selectedSeats);

            // Merge and get unique booked seats
            $updatedBookedSeatsArray = array_unique(array_merge($currentBookedSeatsArray, $selectedSeatsArray));
            $updatedBookedSeats = implode(',', $updatedBookedSeatsArray);

            // Calculate new available seats count
            $newAvailableSeats = max(0, $availableSeats - count($selectedSeatsArray));

            // Update schedule table
            $this->db->query("UPDATE schedule SET bookedSeats = :updatedBookedSeats, availableSeats = :newAvailableSeats WHERE scheduleId = :scheduleId");
            $this->db->bind(':updatedBookedSeats', $updatedBookedSeats);
            $this->db->bind(':newAvailableSeats', $newAvailableSeats);
            $this->db->bind(':scheduleId', $scheduleId);

            return $this->db->execute();
        }

        
    }
?>