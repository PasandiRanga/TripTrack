<?php
    class M_RegisteredPages {
        private $db;
        public function __construct(){
            $this->db = new Database();
        }

        public function validateBooking($bookingId, $userId) {
            $this->db->query("SELECT * FROM registeredbooking WHERE id = :bookingId AND User_id = :userId");
            $this->db->bind(':bookingId', $bookingId);
            $this->db->bind(':userId', $userId);
            return $this->db->single() ? true : false;
        }

        public function cancelOnlineBooking($bookingId, $scheduleId , $cancellation_fee, $refund_amount, $bankDetails) {
            // echo '<script> console.log("scheduleId2: ", ' . json_encode($scheduleId) . '); </script>';
            // echo '<script> console.log("Bank Details: ", ' . json_encode($bankDetails) . '); </script>';  
                 
            try {
                $this->db->beginTransaction();
                
                $this->db->query("SELECT selected_seats FROM registeredbooking WHERE id = :bookingId");
                $this->db->bind(':bookingId', $bookingId);
                $seats = $this->db->single();
                echo '<script> console.log("seats: ", ' . json_encode($seats) . '); </script>';
             
               
                if (!$seats) {
                    throw new Exception("Booking not found");
                }

                $this->db->query("SELECT bookedSeats FROM schedule WHERE scheduleId = :scheduleId");
                $this->db->bind(':scheduleId', $scheduleId);
                $bookedSeats = $this->db->single();
                if (!$bookedSeats) {
                    throw new Exception("Schedule not found");
                }
                echo '<script> console.log("Booked seats of the schedule: ", ' . json_encode($bookedSeats) . '); </script>';


                $seatsString = $seats['selected_seats'];
                $seatsString = str_replace('"', '', $seatsString); // Remove any quote characters
                $seatsArray = array_map('trim', explode(',', $seatsString));
                echo '<script> console.log("Seats Arrray: ", ' . json_encode($seatsArray) . '); </script>';

                $bookedSeatsArray = explode(',', $bookedSeats['bookedSeats']); // Convert bookedSeats into an array

                $bookedSeatsArray = array_values(array_diff($bookedSeatsArray, $seatsArray));

                $bookedSeats = implode(',', $bookedSeatsArray); // Convert bookedSeats array back to string
                echo '<script> console.log("Booked seats of the schedule after cancellation: ", ' . json_encode($bookedSeats) . '); </script>';


                $this->db->query("UPDATE schedule SET bookedSeats = :bookedSeats WHERE scheduleId = :scheduleId");
                $this->db->bind(':bookedSeats', $bookedSeats);
                $this->db->bind(':scheduleId', $scheduleId);
                $this->db->execute();

                $this->db->query("SELECT * FROM registeredbooking WHERE id = :bookingId");
                $this->db->bind(':bookingId', $bookingId);
                $booking = $this->db->single();
                if (!$booking) {
                    throw new Exception("Booking not found");
                }


                $this->db->query("INSERT INTO cancelled_online_bookings (id,Booking_date, Booking_time, No_of_seats, Seats, User_id, schedule_id, from_location, to_location, total_price, paymentMethod , booking_status , time_date , cancellation_fee , refund_amount, account_name , account_number , bank_name , branch_name)
                VALUES(:id, :Booking_date, :Booking_time, :No_of_seats, :Seats, :User_id, :schedule_id, :from_location, :to_location, :total_price, :paymentMethod , 'Cancelled', NOW(), :cancellation_fee , :refund_amount , :account_name , :account_number , :bank_name , :branch_name);");
                $this->db->bind(':id', $booking['id']);
                $this->db->bind(':Booking_date', $booking['booking_date']);
                $this->db->bind(':Booking_time', $booking['booking_time']);
                $this->db->bind(':No_of_seats', $booking['number_of_seats']);
                $this->db->bind(':Seats', $booking['selected_seats']);
                $this->db->bind(':User_id', $booking['User_id']);
                $this->db->bind(':schedule_id', $booking['schedule_id']);
                $this->db->bind(':from_location', $booking['from_location']);
                $this->db->bind(':to_location', $booking['to_location']);
                $this->db->bind(':total_price', $booking['total_price']);
                $this->db->bind(':paymentMethod', $booking['paymentMethod']);
                $this->db->bind(':cancellation_fee', $cancellation_fee);
                $this->db->bind(':refund_amount', $refund_amount);
                $this->db->bind(':account_name', $bankDetails['accountName']);
                $this->db->bind(':account_number', $bankDetails['accountNumber']);
                $this->db->bind(':bank_name', $bankDetails['bankName']);
                $this->db->bind(':branch_name', $bankDetails['branch']);
                $this->db->execute();

                $this->db->query("DELETE FROM registeredbooking WHERE id = :bookingId");
                $this->db->bind(':bookingId', $bookingId);
                $this->db->execute();

                $this->db->endTransaction();
                return true;
        
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = $e->getMessage();
                error_log($e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return false;
            }
        }

        public function cancelCashBooking($bookingId, $scheduleId , $cancellation_fee, $refund_amount){
            try {
                $this->db->beginTransaction();
                
                $this->db->query("SELECT selected_seats FROM registeredbooking WHERE id = :bookingId");
                $this->db->bind(':bookingId', $bookingId);
                $seats = $this->db->single();
                echo '<script> console.log("seats: ", ' . json_encode($seats) . '); </script>';
               
                
               
                if (!$seats) {
                    throw new Exception("Booking not found");
                }

                $this->db->query("SELECT bookedSeats FROM schedule WHERE scheduleId = :scheduleId");
                $this->db->bind(':scheduleId', $scheduleId);
                $bookedSeats = $this->db->single();
                if (!$bookedSeats) {
                    throw new Exception("Schedule not found");
                }
                echo '<script> console.log("Booked seats of the schedule: ", ' . json_encode($bookedSeats) . '); </script>';

            


                $seatsString = $seats['selected_seats'];
                $seatsString = str_replace('"', '', $seatsString); // Remove any quote characters
                $seatsArray = array_map('trim', explode(',', $seatsString));
                echo '<script> console.log("Seats Arrray: ", ' . json_encode($seatsArray) . '); </script>';

                $bookedSeatsArray = explode(',', $bookedSeats['bookedSeats']); // Convert bookedSeats into an array

                $bookedSeatsArray = array_values(array_diff($bookedSeatsArray, $seatsArray));

                $bookedSeats = implode(',', $bookedSeatsArray); // Convert bookedSeats array back to string
                echo '<script> console.log("Booked seats of the schedule after cancellation: ", ' . json_encode($bookedSeats) . '); </script>';



                $this->db->query("UPDATE schedule SET bookedSeats = :bookedSeats WHERE scheduleId = :scheduleId");
                $this->db->bind(':bookedSeats', $bookedSeats);
                $this->db->bind(':scheduleId', $scheduleId);
                $this->db->execute();

                $this->db->query("SELECT * FROM registeredbooking WHERE id = :bookingId");
                $this->db->bind(':bookingId', $bookingId);
                $booking = $this->db->single();
                if (!$booking) {
                    throw new Exception("Booking not found");
                }

                 $this->db->query("INSERT INTO cancelled_cash_bookings (id,Booking_date, Booking_time, No_of_seats, Seats, User_id, schedule_id, from_location, to_location, total_price, paymentMethod , booking_status , time_date , cancellation_fee , refund_amount)
                VALUES(:id, :Booking_date, :Booking_time, :No_of_seats, :Seats, :User_id, :schedule_id, :from_location, :to_location, :total_price, :paymentMethod , 'Cancelled', NOW(), :cancellation_fee , :refund_amount );");
                $this->db->bind(':id', $booking['id']);
                $this->db->bind(':Booking_date', $booking['booking_date']);
                $this->db->bind(':Booking_time', $booking['booking_time']);
                $this->db->bind(':No_of_seats', $booking['number_of_seats']);
                $this->db->bind(':Seats', $booking['selected_seats']);
                $this->db->bind(':User_id', $booking['User_id']);
                $this->db->bind(':schedule_id', $booking['schedule_id']);
                $this->db->bind(':from_location', $booking['from_location']);
                $this->db->bind(':to_location', $booking['to_location']);
                $this->db->bind(':total_price', $booking['total_price']);
                $this->db->bind(':paymentMethod', $booking['paymentMethod']);
                $this->db->bind(':cancellation_fee', $cancellation_fee);
                $this->db->bind(':refund_amount', $refund_amount);
                $this->db->execute();

                $this->db->query("DELETE FROM registeredbooking WHERE id = :bookingId");
                $this->db->bind(':bookingId', $bookingId);
                $this->db->execute();

                $this->db->endTransaction();
                
                return true;

            }catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = $e->getMessage();
                error_log($e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return false;
            }
        }

        public function getCancellationDetails($bookingId, $userId) {
            $this->db->query("SELECT * FROM cancelled_online_bookings WHERE id = :bookingId AND User_id = :userId");
            $this->db->bind(':bookingId', $bookingId);
            $this->db->bind(':userId', $userId);
            $onlineBooking = $this->db->single();
            
            // echo '<script> console.log("Cancellation Online data from Db", ' . json_encode($onlineBooking) . '); </script>';
            

            if ($onlineBooking) {
                return $onlineBooking;
            }

            $this->db->query("SELECT * FROM cancelled_cash_bookings WHERE id = :bookingId AND User_id = :userId");
            $this->db->bind(':bookingId', $bookingId);
            $this->db->bind(':userId', $userId);
            $cashBooking = $this->db->single();

            echo '<script> console.log("Cancellation cash data from Db", ' . json_encode($cashBooking) . '); </script>';
            return $cashBooking;
            
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

        public function getRegBookings($userId){
            try {
                $this->db->query('SELECT * FROM registeredbooking WHERE User_id = :userId ');
                return $this->db->resultSet();
            } catch (Exception $e) {
                error_log("Error fetching schedule: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return [];
            }
        }

        public function getUpcomingSchedule(){
            try {
                $this->db->query('SELECT * FROM schedule');
                return $this->db->resultSet();
            } catch (Exception $e) {
                error_log("Error fetching schedule: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return [];
            }
        }

        public function getPastSchedule(){
            try {
                $this->db->query('SELECT * FROM past_schedules');
                return $this->db->resultSet();
            } catch (Exception $e) {
                error_log("Error fetching schedule: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return [];
            }
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

        public function getUpcomingBookings($userId) {
            try {
                $this->db->query(
                    'SELECT * FROM registeredbooking 
                    WHERE User_id = :userId 
                    AND schedule_id IN (
                        SELECT schedule_id 
                        FROM schedule 
                        WHERE CONCAT(date, " ", departureTime) > NOW()
                    )
                    ORDER BY id ASC'
                );
                $this->db->bind(':userId', $userId);
                return $this->db->resultSet();
            } catch (Exception $e) {
                error_log("Error fetching upcoming booking details: " . $e->getMessage());
                echo "<script>console.error(" . json_encode($e->getMessage()) . ");</script>";
                return [];
            }
        }


        public function getPastBookings($userId){
            try {
                $this->db->query('SELECT * FROM pastregbooking WHERE User_id = :userId');
                $this->db->bind(':userId', $userId);
                return $this->db->resultSet();
            } catch (Exception $e) {
                error_log("Error fetching booking details: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return [];
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

        //This for find a single person
        public function getUserByEmail($email) {
            $this->db->query('SELECT * FROM customer WHERE Email = :email');
            $this->db->bind(':email', $email);
            
            return $this->db->single();
        }

        public function isNICUsedByAnotherUser($nic, $currentUserId) {
            $this->db->query('SELECT * FROM customer WHERE NIC = :nic AND User_id != :user_id');
            $this->db->bind(':nic', $nic);
            $this->db->bind(':user_id', $currentUserId);
            
            $this->db->execute();
            
            // If any rows are returned, the NIC is used by another user
            return $this->db->rowCount() > 0;
        }

        public function findUserByNIC($nic) {
            $this->db->query('SELECT * FROM customer WHERE NIC = :nic');
            $this->db->bind(':nic', $nic);
            
            $row = $this->db->single();
            
            // Check if row exists
            if ($this->db->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function deleteAccount($userID) {
            $this->db->query('DELETE FROM customer WHERE User_id = :userId');
            $this->db->bind(':userId', $userID);
        
            $this->db->execute();
        
            if ($this->db->rowCount() > 0) {
                return true; 
            } else {
                return false; 
            }
        }
        

        public function updateProfile($data) {
            $this->db->query("UPDATE customer 
                              SET Name = :name, Email = :email, Contact_number = :contact_number, NIC = :nic, Address = :address 
                              WHERE Email = :current_email");
        
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':contact_number', $data['contact_number']);
            $this->db->bind(':nic', $data['nic']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':current_email', $data['current_email']); // Use the current email for the condition
        
            return $this->db->execute();
        }

        public function getReviewsByLicenseId($licenseId) {
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
    
            return $this->db->execute();
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
        
        public function getScheduleByDate($date){
                $this->db->query('SELECT * FROM schedule WHERE date = :date');
                $this->db->bind(':date', $date);
                return $this->db->resultSet();
        }

        public function createBooking($bookingData) {
            $this->db->query("SELECT * FROM RegisteredBooking WHERE User_id = :userId AND selected_seats = :selectedSeats");
            $this->db->bind(':userId', $bookingData['User_id']);
            $selectedSeats = is_array($bookingData['selectedSeats']) ? $bookingData['selectedSeats'] : explode(',', $bookingData['selectedSeats']);
            $this->db->bind(':selectedSeats', implode(',', $selectedSeats));
            $existingBooking = $this->db->resultSet();


            $this->db->query("SELECT * FROM schedule WHERE scheduleId = :scheduleId");
            $this->db->bind(':scheduleId', $bookingData['scheduleId']);
            $schedule = $this->db->single();
            
            if ($existingBooking) {
                exit(); 
            }else{
                date_default_timezone_set('Asia/Colombo');
                $currentDate = date('Y-m-d'); 
                $currentTime = date('H:i:s'); 

                $this->db->query("INSERT INTO RegisteredBooking (booking_date, booking_time,scheduleDate, departureTime,number_of_seats, selected_seats, User_id, schedule_id, from_location, to_location, total_price, paymentMethod , booking_status , qrcode_path) 
                                        VALUES (:bookingDate, :bookingTime, :scheduleDate, :departureTime, :noOfSeats, :selectedSeats, :userId, :scheduleId, :fromLocation, :toLocation, :totalPrice, :paymentMethod , :booking_status, :qrcode_path);");
                $this->db->bind(':bookingDate', $currentDate); 
                $this->db->bind(':bookingTime', $currentTime); 
                $this->db->bind(':scheduleDate', $schedule['date']);
                $this->db->bind(':departureTime', $schedule['departureTime']);
                $this->db->bind(':noOfSeats', $bookingData['noOfSeats']);
                $this->db->bind(':selectedSeats', $bookingData['selectedSeatsJSON']);
                $this->db->bind(':userId', $bookingData['User_id']);                      
                $this->db->bind(':scheduleId', $bookingData['scheduleId']);
                $this->db->bind(':fromLocation', $bookingData['from']);
                $this->db->bind(':toLocation', $bookingData['to']);
                $this->db->bind(':totalPrice', $bookingData['totalPrice']);
                $this->db->bind(':paymentMethod' , $bookingData['paymentMethod']);
                $this->db->bind(":booking_status" , "Pending");
                $this->db->bind(":qrcode_path" , $bookingData['qrCodeFilename']);
                return $this->db->execute();
            }
        }
        
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
        public function getNotifications() {
            try {
                $this->db->query('SELECT * FROM notification ORDER BY time DESC');
                $result = $this->db->resultSet();
                
                if (empty($result)) {
                    error_log("Query executed but returned no results.");
                } else {
                    error_log("Query executed successfully. Data: " . print_r($result, true));
                }

                return $result;
            } catch (Exception $e) {
                error_log("Error fetching notification details: " . $e->getMessage());
                return [];
            }
        }

        public function updateProfileImage($userId, $imagePath) {
            $this->db->query('UPDATE customer SET Profile_image = :image WHERE User_id = :id');
            $this->db->bind(':image', $imagePath);
            $this->db->bind(':id', $userId);

            return $this->db->execute();
        }

        public function getPastNotArrivedBookings($userId){
            try{
                $this->db->query('SELECT * FROM pastregbooking WHERE User_id = :userId AND booking_status = "Not Arrived" AND PenaltyPaid = "Not Paid"');
                $this->db->bind(':userId', $userId);
                return $this->db->resultSet();
            }catch (Exception $e) {
                error_log("Error fetching booking details: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return [];
            }
        }

        public function markPenaltyPaid($userId){
            try{
                $this->db->query('UPDATE pastregbooking SET PenaltyPaid = "Paid" WHERE User_id = :userId AND booking_status = "Not Arrived"AND PenaltyPaid = "Not Paid"');
                $this->db->bind(':userId', $userId);
                return $this->db->execute();
            }catch (Exception $e) {
                error_log("Error updating: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return [];
            }
        }

        public function sendPenaltyPaidNotification($userId , $penaltyFee){
            try{
                $title = "Penalty Fee Paid";
                $message = "Thank you for paying the penalty fee of $penaltyFee.";
                $link = NULL;
                $createdAt = date("Y-m-d H:i:s");

                // Step 4: Insert notification for each user
                $this->db->query("INSERT INTO notifications (user_id, title, message, link, is_read, is_seen, is_deleted, created_at, is_dismissed)
                                VALUES (:user_id, :title, :message, :link, 0, 0, 0, :created_at, 0)");
                $this->db->bind(':user_id', $userId);
                $this->db->bind(':title', $title);
                $this->db->bind(':message', $message);
                $this->db->bind(':link', $link);
                $this->db->bind(':created_at', $createdAt);
                $this->db->execute();
            }catch  (Exception $e){
                error_log("Error Inserting: " . $e->getMessage());
                echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                return [];
            }
        }
        
        public function addReview($data) {
            $this->db->query("INSERT INTO ratings (User_id, License_id, rate, review, Date, Time) 
                            VALUES (:user_id, :license_id, :rate, :review, :date, :time)");

            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':license_id', $data['license_id']);
            $this->db->bind(':rate', $data['rate']);
            $this->db->bind(':review', $data['review']);
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':time', $data['time']);

            return $this->db->execute();
        }

        public function getAverageRatings($licenseId){
            $this->db->query('SELECT License_id,AVG(rate) as average_rate FROM ratings WHERE License_id = :licenseId');
            $this->db->bind(':licenseId',$licenseId);
            return $this->db->single();
        }

        public function getCancellations($userID){
            $this->db->query("SELECT * FROM cancelled_cash_bookings WHERE :userid = User_id" );
            $this->db->bind(':userid',$userID);

            $cancellations = $this->db->resultSet();

            $this->db->query("SELECT * FROM cancelled_online_bookings WHERE :userid = User_id");
            $this->db->bind(':userid' , $userID);

            $onlineCancellations = $this->db->resultSet();
            $cancellations = array_merge($cancellations, $onlineCancellations);

            return $cancellations;
            
        }

        public function getBookingID($userID , $scheduleID , $selectedSeats){
            $this->db->query("SELECT id FROM registeredbooking WHERE :userid = User_id AND :scheduleid = schedule_id AND :selectedSeats = selected_seats");
            $this->db->bind(':userid' , $userID);
            $this->db->bind('scheduleid' , $scheduleID);
            $this->db->bind(':selectedSeats' , $selectedSeats);
            return $this->db->single();
        }


    }

?>