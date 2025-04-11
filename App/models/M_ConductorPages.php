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
            $this->db->query('SELECT * FROM bus_delay');

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

        public function getRegisteredBooking($scheduleId, $seats) {
            $this->db->query("SELECT * FROM registeredbooking WHERE schedule_id = :schedule_id AND selected_seats = :seats");
            $this->db->bind(':schedule_id', $scheduleId);
            return $this->db->single();

            // Format the incoming seats as an array
            $scannedSeats = is_array($seats) ? $seats : explode(',', $seats);
            
            // Loop through each booking to find matching seats
            foreach ($bookings as $booking) {
                // In your database, seats are stored like "1,2" or "3,4,5"
                $bookedSeats = explode(',', $booking->selected_seats);
                $allSeatsMatch = true;
                
                // Check if all scanned seats are in this booking
                foreach ($scannedSeats as $seat) {
                    $seat = trim($seat);
                    if (!in_array($seat, array_map('trim', $bookedSeats))) {
                        $allSeatsMatch = false;
                        break;
                    }
                }
                
                if ($allSeatsMatch) {
                    return $booking;
                }
            }
            
            return false;
        }
        
        public function getGuestBooking($scheduleId, $seats) {
            // In guestbooking table, the field is also named "selected_seats"
            $this->db->query("SELECT * FROM guestbooking WHERE schedule_id = :schedule_id");
            $this->db->bind(':schedule_id', $scheduleId);
            $bookings = $this->db->resultSet();
            
            // Format the incoming seats
            $scannedSeats = is_array($seats) ? $seats : explode(',', $seats);
            
            foreach ($bookings as $booking) {
                // In your database, guest seats are stored like "1, 2, 3" with quotes
                // We need to remove quotes and then split
                $seatString = str_replace('"', '', $booking->selected_seats);
                $bookedSeats = explode(',', $seatString);
                $allSeatsMatch = true;
                
                foreach ($scannedSeats as $seat) {
                    $seat = trim($seat);
                    if (!in_array($seat, array_map('trim', $bookedSeats))) {
                        $allSeatsMatch = false;
                        break;
                    }
                }
                
                if ($allSeatsMatch) {
                    return $booking;
                }
            }
            
            return false;
        }

        public function insertPastBooking($bookingData) {
            // The pastbooking table uses "Seats" (capital S) from your screenshot
            $this->db->query("INSERT INTO pastregbooking (id, Booking_date, Booking_time, No_of_seats, Seats, schedule_id, from_location, to_location, total_price, paymentMethod) 
                             VALUES (:id, :booking_date, :booking_time, :no_of_seats, :seats, :schedule_id, :from_location, :to_location, :total_price, :paymentMethod)");
        
            // Make sure seat format matches past bookings (with quotes)
            $seats = '"' . (is_array($bookingData->selected_seats) ? implode(', ', $bookingData->selected_seats) : $bookingData->selected_seats) . '"';
        
            $this->db->bind(':id', $bookingData->id);
            $this->db->bind(':Booking_date', $bookingData->booking_date);
            $this->db->bind(':Booking_time', $bookingData->booking_time);
            $this->db->bind(':No_of_seats', $bookingData->number_of_seats);
            $this->db->bind(':Seats', $seats);
            //$this->db->bind(':user_id', $bookingData->User_id);
            $this->db->bind(':schedule_id', $bookingData->schedule_id);
            $this->db->bind(':from_location', $bookingData->from_location);
            $this->db->bind(':to_location', $bookingData->to_location);
            $this->db->bind(':total_price', $bookingData->total_price);
            $this->db->bind(':paymentMethod', $bookingData->paymentMethod ?? 'Cash');
        
            return $this->db->execute();
        }

        // Check if schedule ID exists in registered bookings
        public function checkScheduleExistsInRegistered($scheduleId) {
            $this->db->query("SELECT COUNT(*) as count FROM registeredbooking WHERE schedule_id = :schedule_id");
            $this->db->bind(':schedule_id', $scheduleId);
            $result = $this->db->single();
            return $result->count > 0;
        }

        // Check if schedule ID exists in guest bookings
        public function checkScheduleExistsInGuest($scheduleId) {
            $this->db->query("SELECT COUNT(*) as count FROM guestbooking WHERE schedule_id = :schedule_id");
            $this->db->bind(':schedule_id', $scheduleId);
            $result = $this->db->single();
            return $result->count > 0;
        }

    }
?>