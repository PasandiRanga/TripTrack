<?php
    class M_GuestPages {
        private $db;

        public function __construct(){
            $this->db = new Database();
        }

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

        public function getScheduleByDate($date){
            $this->db->query('SELECT * FROM schedule WHERE date = :date');
            $this->db->bind(':date', $date);
            return $this->db->resultSet();
        }

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

        public function addSupportRequest($data) {
            $this->db->query('INSERT INTO support_request (name, email, contactNo, message) VALUES (:name, :email, :contactNo, :message)');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':contactNo', $data['contactNo']);
            $this->db->bind(':message', $data['message']);
    
            return $this->db->execute();
        }

        public function createBooking($bookingData) {
            $this->db->query("SELECT * FROM guestbooking WHERE schedule_id = :scheduleId AND selected_seats = :selectedSeats");
            $this->db->bind(':scheduleId', $bookingData['scheduleId']);
            echo '<script>console.log(' .json_encode($bookingData['selectedSeats']) . ');</script>';
            echo '<script>console.log(' .json_encode($bookingData['selectedSeatsJSON']) . ');</script>';
            $this->db->bind(':selectedSeats', $bookingData['selectedSeatsJSON']);

            $existingBooking = $this->db->single();
            echo '<script>console.log(' .json_encode($existingBooking) . ');</script>';
            if ($existingBooking) {
                return; 
            }

            date_default_timezone_set('Asia/Colombo');
            $currentDate = date('Y-m-d'); 
            $currentTime = date('H:i:s'); 


            $this->db->query("INSERT INTO guestbooking (name, email, contact, nic, from_location, to_location, 
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

        public function updateScheduleSeats($scheduleId, $selectedSeats) {
            $this->db->query("SELECT bookedSeats, availableSeats FROM schedule WHERE scheduleId = :scheduleId");
            $this->db->bind(':scheduleId', $scheduleId);
            $scheduleData = $this->db->single();

            $currentBookedSeats = $scheduleData['bookedSeats'];
            $availableSeats = (int)$scheduleData['availableSeats'];

            $currentBookedSeatsArray = $currentBookedSeats ? explode(',', $currentBookedSeats) : [];
            $selectedSeatsArray = is_array($selectedSeats) ? $selectedSeats : explode(',', $selectedSeats);

            $updatedBookedSeatsArray = array_unique(array_merge($currentBookedSeatsArray, $selectedSeatsArray));
            $updatedBookedSeats = implode(',', $updatedBookedSeatsArray);

            $newAvailableSeats = max(0, $availableSeats - count($selectedSeatsArray));

            $this->db->query("UPDATE schedule SET bookedSeats = :updatedBookedSeats, availableSeats = :newAvailableSeats WHERE scheduleId = :scheduleId");
            $this->db->bind(':updatedBookedSeats', $updatedBookedSeats);
            $this->db->bind(':newAvailableSeats', $newAvailableSeats);
            $this->db->bind(':scheduleId', $scheduleId);

            return $this->db->execute();
        }

        public function setPasswordResetToken($email, $token, $expiry) {
            try {
                $this->db->query('SELECT * FROM password_resets WHERE email = :email');
                $this->db->bind(':email', $email);
                $existing = $this->db->single();
                
                if ($this->db->rowCount() > 0) {
                    $this->db->query('UPDATE password_resets SET token = :token, expiry = :expiry WHERE email = :email');
                } else {
                    $this->db->query('INSERT INTO password_resets (email, token, expiry) VALUES (:email, :token, :expiry)');
                }
                
                $this->db->bind(':email', $email);
                $this->db->bind(':token', $token);
                $this->db->bind(':expiry', $expiry);
                
                if ($this->db->execute()) {
                        return true;
                    } else {
                        return false;
                    }
            } catch (Exception $e) {
                error_log('Error setting password reset token: ' . $e->getMessage());
                return false;
            }
        }

        public function checkResetToken($email) {
            $this->db->query('SELECT * FROM password_resets WHERE email = :email');
            $this->db->bind(':email', $email);
            $row = $this->db->single();
                
            if ($this->db->rowCount() > 0) {
                return $row;
            } else {
                return false;
            }
        }

        public function resetPassword($email, $password) {
            try {
                $this->db->beginTransaction();
                    
                $this->db->query('UPDATE customer SET Password = :password WHERE Email = :email');
                $this->db->bind(':password', $password);
                $this->db->bind(':email', $email);
                if($this->db->execute()){
                    echo "Password changed";   
                }
                 
                $this->db->query('DELETE FROM password_resets WHERE email = :email');
                $this->db->bind(':email', $email);
                if($this->db->execute()){
                    echo "Reset deleted";        
                }
                    
                $this->db->endTransaction();
                return true;

            } catch (Exception $e) {
                $this->db->rollBack();
                error_log('Error resetting password: ' . $e->getMessage());
                return false;
            }
        }

        public function getAverageRatings($licenseId){
            $this->db->query('SELECT License_id,AVG(rate) as average_rate FROM ratings WHERE License_id = :licenseId');
            $this->db->bind(':licenseId',$licenseId);
            return $this->db->single();
        }

        public function getBookingID($email , $scheduleID , $selectedSeats){
            $this->db->query("SELECT id FROM guestbooking WHERE :email = email AND :scheduleid = schedule_id AND :selectedSeats = selected_seats");
            $this->db->bind(':email' , $email);
            $this->db->bind('scheduleid' , $scheduleID);
            $this->db->bind(':selectedSeats' , $selectedSeats);
            return $this->db->single();
        }
    }
?>