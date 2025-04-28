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

        public function getAssignDetailsByEmployeeId($userId) {
            $this->db->query("SELECT scheduleId FROM assign WHERE conductor_id = :userId OR driver_id = :userId");
            
            $this->db->bind(':userId', $userId);
            
            return $this->db->resultSet();
        }

        public function getLicenseIdByScheduleId($scheduleId) {
            if (!is_array($scheduleId)) {
                $scheduleId = [$scheduleId]; // Convert single value to an array
            }

            if (empty($scheduleId)) {
                return [];
            }

            $placeholders = implode(',', array_fill(0, count($scheduleId), '?'));

            $this->db->query("SELECT scheduleId, License_id, date, departureTime, arrivalTime, availableSeats, bookedSeats, type FROM schedule WHERE scheduleId IN ($placeholders)");
            
            foreach ($scheduleId as $index => $id) {
                $this->db->bind(($index + 1), $id, PDO::PARAM_INT); // Bind each scheduleId dynamically
            }

            return $this->db->resultSet();
        }

        public function getBusDetailsByLicenseId($License_id) {
            if (empty($License_id)) {
                return [];
            }

            $placeholders = implode(',', array_fill(0, count($License_id), '?'));

            $this->db->query("SELECT License_id, routeNumber, start_location, destination, price, priceperkm FROM bus WHERE License_id IN ($placeholders)");
            
            foreach ($License_id as $index => $id) {
                $this->db->bind(($index + 1), $id); // Bind each License_id dynamically
            }

            return $this->db->resultSet();
        }


        public function getScheduleDetailsById($schedule_id) {
            $this->db->query("SELECT License_id, date, assign_time FROM schedule WHERE schedule_id = :schedule_id");

            $this->db->bind(':schedule_id', $schedule_id);

            return $this->db->single();
        }

        public function getUpcomingSchedule($userId) {
            //Get schedule IDs assigned to this user
            $this->db->query("SELECT scheduleId FROM assign WHERE conductor_id = :userId OR driver_id = :userId");
            $this->db->bind(':userId', $userId);
            $scheduleIdRows = $this->db->resultSet();
            $scheduleIds = array_column($scheduleIdRows, 'scheduleId');

            if (empty($scheduleIds)) {
                return [];
            }

            //Build placeholders and bind schedule IDs
            $placeholders = implode(',', array_fill(0, count($scheduleIds), '?'));

            //Query schedule + bus data via JOIN
            $this->db->query("
                SELECT s.scheduleId, s.License_id, s.date, s.departureTime, s.arrivalTime, s.availableSeats, s.bookedSeats, s.type, b.start_location, b.destination, b.routeNumber, b.price, b.priceperkm
                FROM schedule s
                JOIN bus b ON s.License_id = b.License_id
                WHERE s.scheduleId IN ($placeholders)
            ");

            foreach ($scheduleIds as $index => $id) {
                $this->db->bind($index + 1, $id); // Bind positionally: ? placeholders
            }

            //Fetch and return the joined data
            return $this->db->resultSet();
        }

        public function getPastSchedule($userId) {
            //Get schedule IDs assigned to this user
            $this->db->query("SELECT scheduleId FROM past_assign WHERE driver_id = :userId OR conductor_id = :userId");
            $this->db->bind(':userId', $userId);
            $scheduleIdRows = $this->db->resultSet();
            $scheduleIds = array_column($scheduleIdRows, 'scheduleId');

            if (empty($scheduleIds)) {
                return [];
            }

            //Build placeholders and bind schedule IDs
            $placeholders = implode(',', array_fill(0, count($scheduleIds), '?'));

            //Query schedule + bus data via JOIN
            $this->db->query("
                SELECT s.scheduleId, s.License_id, s.date, s.departureTime, s.arrivalTime, s.availableSeats, s.bookedSeats, s.type, b.start_location, b.destination, b.routeNumber, b.price, b.priceperkm
                FROM past_schedules s
                JOIN bus b ON s.License_id = b.License_id
                WHERE s.scheduleId IN ($placeholders)
            ");

            foreach ($scheduleIds as $index => $id) {
                $this->db->bind($index + 1, $id); // Bind positionally: ? placeholders
            }

            //Fetch and return the joined data
            return $this->db->resultSet();
        }

        public function getTotalSchedules($userId) {
            $this->db->query("SELECT monthly_count FROM assign Where conductor_id = :userId OR driver_id = :userId");
            $this->db->bind(':userId', $userId);

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
            try {
                // Begin transaction
                $this->db->beginTransaction();

                // Step 1: Insert delay into bus_delay table
                $this->db->query("INSERT INTO bus_delay (schedule_id, employee_id, dep_time, new_dep_time, reason)
                                VALUES (:scheduleID, :employee_id, :time, :newTime, :reason)");
                $this->db->bind(':scheduleID', $data['scheduleID']);
                $this->db->bind(':employee_id', $data['userID']);
                $this->db->bind(':time', $data['time']);
                $this->db->bind(':newTime', $data['newTime']);
                $this->db->bind(':reason', $data['reason']);
                $result = $this->db->execute();

                echo '<script>console.log(' . json_encode($result) . ');</script>';

                if (!$result) {
                    $this->db->rollBack();
                    return false;
                }

                echo '<script>console.log(' . json_encode($result) . ');</script>';

                // Step 2: Get user IDs affected by this schedule
                $this->db->query("SELECT User_id FROM registeredbooking WHERE schedule_id = :scheduleID");
                $this->db->bind(':scheduleID', $data['scheduleID']);
                $userRows = array_unique($this->db->resultSet(), SORT_REGULAR);

                echo '<script>console.log(' . json_encode($userRows) . ');</script>';
            
                // Step 3: Get bus and schedule details
                $this->db->query("SELECT License_id, departureTime FROM schedule WHERE scheduleId = :scheduleID");
                $this->db->bind(':scheduleID', $data['scheduleID']);
                $scheduleDetails = $this->db->single();

                echo '<script>console.log(' . json_encode($scheduleDetails) . ');</script>';

                if (!$scheduleDetails) {
                    return false;
                }

                $this->db->query("SELECT start_location, destination FROM bus WHERE License_id = :licenseID");
                $this->db->bind(':licenseID', $scheduleDetails['License_id']);
                $busDetails = $this->db->single();

                echo '<script>console.log(' . json_encode($busDetails) . ');</script>';
        
                if (!$busDetails) {
                    return false;
                }

                $details = (object) array_merge((array) $scheduleDetails, (array) $busDetails);

                echo '<script>console.log(' . json_encode($details) . ');</script>';
                
                if (!$details) {
                    $this->db->rollBack();
                    return false;
                }

                $start = $details->start_location;
                $destination = $details->destination;
                $originalTime = $details->departure_time;
                $newTime = $data['newTime'];

                $title = "Bus Delay Notification";
                $message = "We regret to inform you that the bus from $start to $destination scheduled at $originalTime will be delayed. It will now depart at $newTime.";
                $link = NULL;
                $createdAt = date("Y-m-d H:i:s");

                // Step 4: Insert notification for each user
                foreach ($userRows as $user) {
                    $this->db->query("INSERT INTO notifications (user_id, title, message, link, is_read, is_seen, is_deleted, created_at, is_dismissed)
                                    VALUES (:user_id, :title, :message, :link, 0, 0, 0, :created_at, 0)");
                    $this->db->bind(':user_id', $user['User_id']);
                    $this->db->bind(':title', $title);
                    $this->db->bind(':message', $message);
                    $this->db->bind(':link', $link);
                    $this->db->bind(':created_at', $createdAt);
                    $this->db->execute();
                }

                // Commit if everything went well
                $this->db->endTransaction();
                return true;

            } catch (Exception $e) {
                // Rollback on error
                $this->db->rollBack();
                error_log("Add Delay Error: " . $e->getMessage());
                return false;
            }
        }


        public function getBusesForDelays($delays){

        }

        public function getBusByScheduleID($schedules) {
            // echo '<script> console.log("schedules: ", ' . json_encode($schedules) . '); </script>';
            
            $buses = [];
            
            foreach($schedules as $scheduleWrapper) {
                // echo '<script> console.log("schedule: ", ' . json_encode($scheduleWrapper) . '); </script>';
                
                // Get the actual schedule object from the wrapper array (at index 0)
                $schedule = $scheduleWrapper[0];
                
                // Now we can safely access the License_id
                if (is_object($schedule)) {
                    $licenseId = $schedule->License_id;
                } else {
                    $licenseId = $schedule['License_id'];
                }
                
                // echo '<script> console.log("License id: ", ' . json_encode($licenseId) . '); </script>';
                
                $this->db->query('SELECT * FROM bus WHERE :licenseId = License_id');
                $this->db->bind(':licenseId', $licenseId);
                $result = $this->db->resultSet();
                
                if (!empty($result)) {
                    $buses = array_merge($buses, $result);
                    $buses = array_unique($buses, SORT_REGULAR); // Remove duplicate entries
                }
            }
            
            return $buses;
        }
        
        public function addLeaves($data) {
            
            $this->db->query("INSERT INTO employee_leave (employee_id, from_date, to_date, no_of_days, reason , status)
                                VALUES (:employeeId, :from_date, :to_date, :noOfDays, :reason , :status)");

            $this->db->bind(':employeeId', $data['employeeId']);
            $this->db->bind(':from_date', $data['from_date']);
            $this->db->bind(':to_date', $data['to_date']);
            $this->db->bind(':noOfDays', $data['noOfDays']);
            $this->db->bind(':reason', $data['reason']);
            $this->db->bind(':status', $data['status']);
            /*$this->db->bind(':status', 'Yet to approve');*/

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

        public function getDelays() {
            $this->db->query('SELECT * FROM bus_delay WHERE employee_id = :emp_id');
            $this->db->bind("emp_id", $_SESSION['user_id']);
            $delays = $this->db->resultSet();

            $schedules = [];
            $buses = [];

            foreach ($delays as $delay) {
                // Get schedule
                $this->db->query('SELECT * FROM schedule WHERE scheduleID = :scheduleID');
                $this->db->bind(":scheduleID", $delay['schedule_id']);
                $schedule = $this->db->single();

                if ($schedule) {
                    $schedules[] = $schedule;

                    // Get bus info from License_id
                    $this->db->query('SELECT * FROM bus WHERE License_id = :licenseID');
                    $this->db->bind(':licenseID', $schedule['License_id']);
                    $bus = $this->db->single();

                    if ($bus) {
                        $buses[] = $bus;
                    }
                }
            }

            return [
                'delays' => $delays,
                'schedules' => $schedules,
                'buses' => $buses
            ];
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

        
        public function getGuestBooking($scheduleId, $seats) {
            $this->db->query("SELECT * FROM guestbooking WHERE schedule_id = :scheduleId AND selected_seats = :seats");

            $this->db->bind(':scheduleId', $scheduleId);
            $this->db->bind(':seats', $seats);

            return $this->db->single();
        }

        public function getRegisteredBooking($scheduleId, $seats) {
            $this->db->query('SELECT * FROM registeredbooking WHERE schedule_id = :scheduleId AND selected_seats = :seats');

            $this->db->bind(':scheduleId', $scheduleId);
            $this->db->bind(':seats', $seats);

            return $this->db->single();
        }

        public function insertPastRegBooking($bookingData) {
            
            $this->db->query("INSERT INTO pastregbooking (id, Booking_date, Booking_time, scheduleDate, No_of_seats, Seats, User_id, schedule_id, from_location, to_location, total_price, paymentMethod , booking_status) 
                             VALUES (:id, :booking_date, :booking_time, :scheduleDate, :no_of_seats, :seats, :userid ,:schedule_id, :from_location, :to_location, :total_price, :paymentMethod , :bookingStatus)");
        
            $this->db->bind(':id', $bookingData['id']);
            $this->db->bind(':booking_date', $bookingData['booking_date']);
            $this->db->bind(':booking_time', $bookingData['booking_time']);
            $this->db->bind(':scheduleDate', $bookingData['scheduleDate']);
            $this->db->bind(':no_of_seats', $bookingData['number_of_seats']);
            $this->db->bind(':seats', $bookingData['selected_seats']);
            $this->db->bind(':userid' , $bookingData['User_id']);
            $this->db->bind(':schedule_id', $bookingData['schedule_id']);
            $this->db->bind(':from_location', $bookingData['from_location']);
            $this->db->bind(':to_location', $bookingData['to_location']);
            $this->db->bind(':total_price', $bookingData['total_price']);
            $this->db->bind(':paymentMethod', $bookingData['paymentMethod'] ?? 'Cash');
            $this->db->bind(':bookingStatus', 'Arrived');
            $this->db->execute();

            $this->db->query("DELETE FROM registeredbooking WHERE id = :id");
            $this->db->bind(':id', $bookingData['id']);
      
            return $this->db->execute();
        }

        public function insertPastGuestBooking($bookingData) {
            error_log("📌 insertPastGuestBooking() was called");
            error_log("booking details at model: " . print_r($bookingData, true));
            
            $this->db->query("INSERT INTO pastguestbooking (id, name, email, contact, nic, from_location, to_location, number_of_seats, selected_seats, total_price, schedule_id, paymentMethod, booking_date, booking_time, booking_status) 
                             VALUES (:id, :name, :email, :contact, :nic, :from_location, :to_location, :number_of_seats, :selected_seats, :total_price, :schedule_id, :paymentMethod, :booking_date, :booking_time, :booking_status)");
        
            $this->db->bind(':id', $bookingData['id']);
            $this->db->bind(':name', $bookingData['name']);
            $this->db->bind(':email', $bookingData['email']);
            $this->db->bind(':contact', $bookingData['contact']);
            $this->db->bind(':nic', $bookingData['nic']);
            $this->db->bind(':from_location', $bookingData['from_location']);
            $this->db->bind(':to_location', $bookingData['to_location']);
            $this->db->bind(':number_of_seats', $bookingData['number_of_seats']);
            $this->db->bind(':selected_seats', $bookingData['selected_seats']);
            $this->db->bind(':total_price', $bookingData['total_price']);
            $this->db->bind(':schedule_id', $bookingData['schedule_id']);
            $this->db->bind(':paymentMethod', $bookingData['paymentMethod'] ?? 'Cash');
            $this->db->bind(':booking_date', $bookingData['booking_date']);
            $this->db->bind(':booking_time', $bookingData['booking_time']);
            $this->db->bind(':booking_status', 'Arrived');
            $this->db->execute();

            $this->db->query("DELETE FROM guestbooking WHERE id = :id");
            $this->db->bind(':id', $bookingData['id']);
      
            return $this->db->execute();
        }

        public function getSchedulesByEmployeeId($userID){
            $this->db->query('SELECT * FROM assign WHERE :userID = conductor_id OR :userID=driver_id');
            $this->db->bind(':userID', $userID);
            $assigns = $this->db->resultSet();

            $schedules = [];

            foreach($assigns as $assign) {
                // Assuming scheduleId is the column name in the assign table
                $scheduleId = $assign['scheduleId']; // Adjust this based on your actual column name
                
                $this->db->query('SELECT * FROM schedule WHERE scheduleId = :schedule_id');
                $this->db->bind(':schedule_id', $scheduleId);
                $schedules[] = $this->db->resultSet();
            }

            return $schedules ?? []; // Return an empty array if no schedules found
            
        }

        public function getSchedule() {
            $this->db->query('SELECT * FROM schedule');

            return $this->db->resultSet();

        }

        public function getBusDetails() {
            $this->db->query('SELECT * FROM bus');

            return $this->db->resultSet();
        }

        public function getScheduleDate($schedule_id) {
            $this->db->query('SELECT date FROM schedule WHERE scheduleId = :schedule_id');
            $this->db->bind(':schedule_id', $schedule_id);
            //$this->db->execute();

            return $this->db->single();
        }

        public function checkAcceptedOrNot($seats, $schedule_id) {
            
            // Prepare and execute query
            $this->db->query('SELECT acceptedSeats FROM schedule WHERE scheduleId = :schedule_id');
            $this->db->bind(':schedule_id', $schedule_id);
            $this->db->execute();

            // Get single result
            $row = $this->db->single();

            if (!$row) {
                return false;
            }

            $acceptedSeatsText = $row['acceptedSeats'];

            if (empty($acceptedSeatsText)) {
                return false;
            }

            $acceptedSeats = array_map('trim', explode(',', $acceptedSeatsText));

            // Check if any seat from $seats_to_check is already accepted
            foreach ($seats as $seat) {
                if (in_array($seat, $acceptedSeats)) {
                    return true; // found a matching seat
                }
            }

            return false; // no matching seats, all free
        }

        public function updateAcceptedSeats($seats, $schedule_id) {
            $this->db->query('SELECT acceptedSeats FROM schedule WHERE scheduleId = :schedule_id');
            $this->db->bind(':schedule_id', $schedule_id);
            $this->db->execute();

            $row = $this->db->single();
            $currentSeats = $row['acceptedSeats'] ?? '';

            $currentSeatsArray = array_filter(array_map('trim', explode(',', $currentSeats)));
            $newSeatsArray = array_filter(array_map('trim', $seats));

            $mergedSeats = array_unique(array_merge($currentSeatsArray, $newSeatsArray));
            sort($mergedSeats, SORT_NUMERIC);

            $updatedSeats = implode(',', $mergedSeats);

            $this->db->query('UPDATE schedule SET acceptedSeats = :updatedSeats WHERE scheduleId = :schedule_id');
            $this->db->bind(':schedule_id', $schedule_id);
            $this->db->bind(':updatedSeats', $updatedSeats);
            $this->db->execute();

            return $updatedSeats;
        }

        public function checkGuestBooking($booking_id, $nic) {
            $this->db->query('SELECT * FROM guestbooking WHERE id = :booking_id AND nic = :nic');
            $this->db->bind(':booking_id', $booking_id);
            $this->db->bind(':nic', $nic);
            $this->db->execute();

            return $this->db->single();
        }

        public function checkRegBooking($booking_id, $nic) {
            $this->db->query('SELECT * FROM registeredbooking WHERE id = :booking_id');
            $this->db->bind(':booking_id', $booking_id);
            //$this->db->bind(':nic', $nic);
            $this->db->execute();

            return $this->db->single();
        }

        public function getAllNotifications($userId) {
            $this->db->query('SELECT * FROM sendnotifications_employee WHERE employee_id = :user_id AND is_deleted=0 ORDER BY created_at DESC');
            $this->db->bind(':user_id', $userId);

            $results = $this->db->resultSet();

            return $results;
        }

        public function getNewNotifications($userId) {
            $this->db->query('SELECT * FROM sendnotifications_employee WHERE employee_id = :user_id AND is_dismissed=0 AND is_read = 0 AND is_deleted=0 ORDER BY created_at DESC');
            $this->db->bind(':user_id', $userId);

            $results = $this->db->resultSet();

            return $results;
        }

        public function updateReadStatus($notificationId, $isRead, $userId) {
            $this->db->query('UPDATE sendnotifications_employee SET is_read = :is_read WHERE id = :id AND employee_id = :user_id');
            $this->db->bind(':is_read', $isRead ? 1 : 0);
            $this->db->bind(':id', $notificationId);
            $this->db->bind(':user_id', $userId);
            
            return $this->db->execute();
        }

        public function deleteNotification($notificationId,$userId) {
            $this->db->query('UPDATE sendnotifications_employee SET is_deleted =1 WHERE id=:id AND employee_id = :user_id');
            $this->db->bind(':id', $notificationId);
            $this->db->bind(':user_id', $userId);
            return $this->db->execute();    
        }

        public function updateReadStatusOfAll($notiID , $userID){
            $this->db->query('UPDATE sendnotifications_employee SET is_read = :is_read WHERE id = :id AND employee_id = :user_id');
            $this->db->bind(':is_read', 1);
            $this->db->bind(':id', $notiID);
            $this->db->bind(':user_id', $userID);
            return $this->db->execute();
        }

        public function getLatestNotification($userId) {
            $this->db->query('SELECT *FROM sendnotifications_employee WHERE employee_id = :userId ORDER BY created_at DESC LIMIT 1');
            $this->db->bind(':userId', $userId);

            return $this->db->single();
        }

        public function updateProfileImage($userId, $imagePath) {
            $this->db->query('UPDATE employee SET Profile_pic = :image WHERE employee_id = :id');
            $this->db->bind(':image', $imagePath);
            $this->db->bind(':id', $userId);

            return $this->db->execute();
        }

        public function findUserByEmail($email){
            $this->db->query('SELECT * FROM employee WHERE email=:email');
            $this->db->bind(":email",$email);
            $row = $this->db->single();
            if($this->db->rowCount()>0){
                return true;
            }
            else{
                return false;  
            }
        }

        public function isNICUsedByAnotherUser($nic, $currentUserId) {
            $this->db->query('SELECT * FROM employee WHERE nic = :nic AND employee_id != :user_id');
            $this->db->bind(':nic', $nic);
            $this->db->bind(':user_id', $currentUserId);
            
            $this->db->execute();
            // If any rows are returned, the NIC is used by another user
            return $this->db->rowCount() > 0;
        }

        public function updateProfile($data) {
            $this->db->query("UPDATE employee 
                              SET Name = :name, email = :email, contactNo = :contact_number, nic = :nic, address = :address 
                              WHERE email = :current_email");
        
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':contact_number', $data['contact_number']);
            $this->db->bind(':nic', $data['nic']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':current_email', $data['current_email']); // Use the current email for the condition
        
            return $this->db->execute();
        }
    }
?>