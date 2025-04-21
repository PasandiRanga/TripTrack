<?php
class M_SuperAdminPages {
    // Declare a variable to grant access to the database
    private $db;

    // Instantiate the database class when the script is called
    public function __construct() {
        $this->db = new Database();
    }

    // Fleet Management

    // Add a new bus
    public function addBus($data) {
        //echo var_dump($data);
        $this->db->query("INSERT INTO bus (License_id, routeNumber, start_location, destination, passengers, price, priceperkm) 
                           VALUES (:License_id, :routeNumber, :start_location, :destination, :passengers, :price, :priceperkm)");

        // Bind parameters
        $this->db->bind(':License_id', $data['License_id']);
        $this->db->bind(':routeNumber', $data['routeNumber']);
        $this->db->bind(':start_location', $data['start_location']);
        $this->db->bind(':destination', $data['destination']);
        $this->db->bind(':passengers', $data['passengers']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':priceperkm', $data['priceperkm']);

        // Execute the query and return the result
        return $this->db->execute();
    }

    // Retrieve all buses
    public function getBus() {
        $this->db->query('SELECT * FROM bus');
        return $this->db->resultSet();
    }

    // Retrieve a specific bus by License_id
    public function getBusByLicenseId($License_id) {
        $this->db->query('SELECT * FROM bus WHERE License_id = :License_id');
        $this->db->bind(':License_id', $License_id);
        return $this->db->single(); // Fetch a single row
    }

    // Delete a bus by License_id
    public function deleteBus($License_id) {
        // Query to delete the bus
        $this->db->query('DELETE FROM bus WHERE License_id = :License_id');
        $this->db->bind(':License_id', $License_id);
        
        // Check if execution was successful
        if ($this->db->execute()) {
            return true;
        } else {
            // Optionally, log the error
            error_log("Failed to delete bus with License_id: $License_id");
            return false;
        }
    }

    // Update bus details using License_id
    public function updateBus($data) {
        $this->db->query('UPDATE bus SET 
            routeNumber = :routeNumber,
            start_location = :start_location,
            destination = :destination,
            passengers = :passengers,
            price = :price,
            priceperkm = :priceperkm
            WHERE License_id = :License_id');

        // Bind parameters
        $this->db->bind(':routeNumber', $data['routeNumber']);
        //$this->db->bind(':route', $data['route']);
        //$this->db->bind(':busType', $data['busType']);
        //$this->db->bind(':stops', $data['stops']);
        $this->db->bind(':start_location', $data['start_location']);
        $this->db->bind(':destination', $data['destination']);
        //$this->db->bind(':rating', $data['rating']);
        $this->db->bind(':passengers', $data['passengers']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':priceperkm', $data['priceperkm']);
        $this->db->bind(':License_id', $data['License_id']);

        // Execute and return result
        return $this->db->execute();
    }

    // Search buses by License_id or Route Number
    public function searchFleet($searchQuery) {
        $sql = "SELECT * FROM bus WHERE LOWER(License_id) LIKE :searchQuery OR LOWER(routeNumber) LIKE :searchQuery";

        $this->db->query($sql);
        $this->db->bind(':searchQuery', '%' . strtolower($searchQuery) . '%');

        return $this->db->resultSet();
    }

    //Get the total count of buses
    public function getBusCount($searchTerm){
        
        if (empty($searchTerm)) {
            return ['total_buses' => 0]; // Ensure return value is always valid
        }
        
        $this->db->query("SELECT COUNT(*) AS total_buses FROM bus WHERE License_id LIKE :searchTerm OR routeNumber LIKE :searchTerm");
        $this->db->bind(':searchTerm', "%{$searchTerm}%");
        $result = $this->db->single();

        return $result ? $result : ['total_buses' => 0];

    }

    // Retrieve all fleet data
    public function getAllFleet() {
        $sql = "SELECT * FROM bus";
        $this->db->query($sql);

        return $this->db->resultSet();
    }

    public function getRouteDetails(){
        $this->db->query("SELECT routeNumber, route, price, priceperkm FROM routes");
        return $this->db->resultSet();
    }



//-----------------------------------------------------------------------------------------------------------------------------------
    // Bookings (unchanged)

//-----------------------------------------------------------------------------------------------------------------------------------

    public function getGuestBookings() {
        $this->db->query('SELECT * FROM guestbooking');
        return $this->db->resultSet();
    }

    public function getRegisterBookings() {
        $this->db->query('SELECT * FROM registeredbooking');
        return $this->db->resultSet();
    }

    public function getCancelOnlineBookings() {
        $this->db->query('SELECT * FROM cancelled_online_bookings');
        return $this->db->resultSet();
    }

    public function getCancelCashBookings() {
        $this->db->query('SELECT * FROM cancelled_cash_bookings');
        return $this->db->resultSet();
    }
//------------------------------------------------------------------------------------------------------------------------------------
    //Employee

//------------------------------------------------------------------------------------------------------------------------------------

    public function findUserByNIC($nic){
        $this->db->query('SELECT * FROM employee WHERE nic=:nic');
        $this->db->bind(":nic",$nic);

        $row = $this->db->single();

        if($this->db->rowCount()>0){
            return true;
        }
        else{
            return false;  
        }
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

    public function addemployee($data) {
        $this->db->query('INSERT INTO employee(name, address, contactNo, email, password, role, nic) VALUES(:name, :address, :contactNo, :email, :password, :role, :nic)');
        
        // Bind parameters
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contactNo', $data['contactNo']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':nic', $data['nic']);

        // Execute and return result
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getEmployee(){
        $this->db->query('SELECT * FROM employee');
        return $this->db->resultSet();
    }

    public function updateEmployee($data){
        $this->db->query('UPDATE employee SET name = :name, address = :address, contactNo = :contactNo, email = :email, nic = :nic WHERE employee_id = :employee_id');

        // Bind parameters
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contactNo', $data['contactNo']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':employee_id', $data['employee_id']);

        // Execute and return result
        if($this->db->execute()){
            return true;
        } else {
            error_log("Failed to update employee");
            return false;
        }
    }

    public function deleteEmployee($employee_id){
        $this->db->query('DELETE FROM employee WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $employee_id);

        if($this->db->execute()){
            return true;
        } else {
            error_log("Failed to delete employee");
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------
    //Schedule

//------------------------------------------------------------------------------------------------------------------------------------

    public function addschedule($data){
        $this->db->query('INSERT INTO schedule (License_id, date, departureTime, arrivalTime, duration, availableSeats, bookedSeats, direction, type) VALUES (:License_id, :date, :departureTime, :arrivalTime, :duration, :availableSeats, :bookedSeats, :direction, :type)');

        $this->db->bind(':License_id', $data['License_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':departureTime', $data['departureTime']);
        $this->db->bind(':arrivalTime', $data['arrivalTime']);
        $this->db->bind(':availableSeats', $data['availableSeats']);
        //$this->db->bind(':bookedSeats', $data['bookedSeats']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':direction', $data['direction']);
        $this->db->bind(':type', $data['type']);

        $bookedSeats = null; // Always NULL
        $this->db->bind(':bookedSeats', $bookedSeats, PDO::PARAM_NULL);


        if($this->db->execute()){
            return true;
        }

        else {
            error_log("Error: Failed to insert Schedule");
            return false;
        }
    }

    public function getschedule(){
        $this->db->query('SELECT * FROM schedule');
        return $this->db->resultSet();
    }

    public function getBusID(){
        $this->db->query("SELECT License_id, passengers FROM bus");
        return $this->db->resultSet();
    }

    public function updateSchedule($data) {

        $this->db->query('UPDATE schedule SET License_id = :License_id, date = :date, departureTime = :departureTime, arrivalTime = :arrivalTime, duration = :duration, direction = :direction, type = :type WHERE scheduleId = :scheduleId');


        // Bind parameters
        $this->db->bind(':License_id', $data['License_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':departureTime', $data['departureTime']);
        $this->db->bind(':arrivalTime', $data['arrivalTime']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':direction', $data['direction']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':scheduleId', $data['scheduleId']);

        // Execute and return result
        if($this->db->execute()){
            return true;
        } else {
            error_log("Failed to update schedule");
            return false;
        }
    }

    public function deleteSchedule($scheduleId){
        $this->db->query('DELETE FROM schedule WHERE scheduleId = :scheduleId');
        $this->db->bind(':scheduleId', $scheduleId);

        if($this->db->execute()){
            return true;
        } else {
            error_log("Failed to delete schedule");
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------
    //support requests

//------------------------------------------------------------------------------------------------------------------------------------
    public function getcontactsrequests(){
        $this->db->query('SELECT * FROM support_request');
        return $this->db->resultSet();
    }

   public function setRepliedStatus($requestId) {
        $this->db->query("UPDATE support_request SET replied = 'Yes' WHERE Request_id = :id");
        $this->db->bind(':id', $requestId);
        return $this->db->execute();
    }

//------------------------------------------------------------------------------------------------------------------------------------
    //Routes

//------------------------------------------------------------------------------------------------------------------------------------
    public function getRoutes(){
        $this->db->query('SELECT * FROM routes');
        return $this->db->resultSet();
    }

    public function addRoute($data){
        $this->db->query('INSERT INTO routes (routeNumber, route, stops, price, priceperkm) VALUES (:routeNumber, :route, :stops, :price, :priceperkm)');

        $this->db->bind(':routeNumber', $data['routeNumber']);
        $this->db->bind(':route', $data['route']);
        $this->db->bind(':stops', $data['stops']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':priceperkm', $data['priceperkm']);

        if($this->db->execute()){
            return true;
        }
        else{
            error_log("Error: Failed to insert assignment"); // Log error
            return false; // Failure
        }
    }

    public function updateRoute($data){
        $this->db->query('UPDATE routes SET routeNumber = :routeNumber, route = :route, stops = :stops, price = :price, priceperkm = :priceperkm WHERE routeNumber = :routeNumber');

        //bind parameters for the update route
        $this->db->bind(':routeNumber', $data['routeNumber']);
        $this->db->bind(':route', $data['route']);
        $this->db->bind(':stops', $data['stops']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':priceperkm', $data['priceperkm']);

        if($this->db->execute()){
            return true;
        }
        else {
            error_log("Error: Failed to update the route");
            return false;
        }

    }

    public function deleteRoute($routeNumber){
        $this->db->query('DELETE FROM routes WHERE routeNumber = :routeNumber');
        $this->db->bind(':routeNumber', $routeNumber);

        if($this->db->execute()){
            return true;
        } else {
            error_log("Failed to delete bus model error");
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------
    //Assigns

//------------------------------------------------------------------------------------------------------------------------------------
    public function getAssigns(){
        $this->db->query('SELECT * FROM assign');
        return $this->db->resultSet();
    }

    public function addAssigns($data) {
        try {

            error_log("Inserting Assign: " . print_r($data, true));

            date_default_timezone_set('Asia/Colombo');
            $currentDate = date("Y-m-d");
            $currentTime = date("H:i:s");

            $this->db->query('INSERT INTO assign (scheduleId, driver_name, driver_id, conductor_name, conductor_id, assign_time, assign_date) 
                            VALUES (:scheduleId, :driver_name, :driver_id, :conductor_name, :conductor_id, :assign_time, :assign_date)');

            $this->db->bind(':scheduleId', $data['scheduleId']);
            $this->db->bind(':driver_name', $data['driverName']);
            $this->db->bind(':driver_id', $data['driver_id']);
            $this->db->bind(':conductor_name', $data['conductorName']);
            $this->db->bind(':conductor_id', $data['conductor_id']);
            $this->db->bind(':assign_time', $currentTime);
            $this->db->bind(':assign_date', $currentDate);

            if ($this->db->execute()) {
                return true;
            } else {
                // Log error details
                error_log("Database error: Failed to execute query in addAssigns method.");
                return false;
            }
        } catch (Exception $e) {
            error_log("Exception in addAssigns: " . $e->getMessage());
            return false;
        }
    }


    public function getScheduleID(){
        $this->db->query("SELECT scheduleId FROM schedule");
        return $this->db->resultSet();
    }

    public function getAssignedSchedules(){
        $this->db->query("SELECT scheduleId FROM assign");
        return $this->db->resultSet();
    }

    public function getDriverName(){
        $this->db->query("SELECT employee_id,name FROM employee WHERE role='Driver'");
        return $this->db->resultSet();
    }

    public function getConductorName(){
        $this->db->query("SELECT employee_id,name FROM employee WHERE role='Conductor'");
        return $this->db->resultSet();
    }

    public function getAvailableDrivers() {
        $this->db->query("
            SELECT employee_id, name 
            FROM employee 
            WHERE role = 'Driver' 
            AND employee_id NOT IN (SELECT driver_id FROM assign)
        ");
        return $this->db->resultSet();
    }

    public function getAvailableConductors() {
        $this->db->query("
            SELECT employee_id, name 
            FROM employee 
            WHERE role = 'Conductor' 
            AND employee_id NOT IN (SELECT conductor_id FROM assign)
        ");
        return $this->db->resultSet();
    }


    // public function getDriverId(){
    //     $this->db->query("SELECT employee_id FROM employee WHERE role='Driver'");
    //     return $this->db->resultSet();
    // }

    // public function getConductorId(){
    //     $this->db->query("SELECT employee_id FROM employee WHERE role='Conductor'");
    //     return $this->db->resultSet();
    // }

    // public function getAssignedDrivers(){
    //     $this->db->query("SELECT driver_id FROM assign");
    //     return $this->db->resultSet();
    // }

    // public function getAssignedConductors(){
    //     $this->db->query("SELECT conductor_id FROM assign");
    //     return $this->db->resultSet();
    // }


    public function updateAssign($data) {
        date_default_timezone_set('Asia/Colombo'); // Set the timezone
        $currentDate = date("Y-m-d");  // Get current date
        $currentTime = date("H:i:s");  // Get current time

        // Debug log to verify data
        error_log("Model updateAssign Data: " . json_encode($data));

        // Update query
        $this->db->query('UPDATE assign SET driver_name = :driver_name, driver_id = :driver_id, conductor_name = :conductor_name, conductor_id = :conductor_id, assign_time = :assign_time, assign_date = :assign_date WHERE scheduleId = :scheduleId');

        // Bind parameters
        $this->db->bind(':scheduleId', $data['scheduleId']);
        $this->db->bind(':driver_name', $data['driverName']);
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':conductor_name', $data['conductorName']);
        $this->db->bind(':conductor_id', $data['conductor_id']);
        $this->db->bind(':assign_time', $currentTime);
        $this->db->bind(':assign_date', $currentDate);

        // Execute the query and return the result
        if ($this->db->execute()) {
            return true;
        } else {
            error_log("Failed to update assign");
            return false;
        }
    }

    public function deleteAssign($scheduleId){
        $this->db->query('DELETE FROM assign WHERE scheduleId = :scheduleId');
        $this->db->bind(':scheduleId', $scheduleId);

        if($this->db->execute()){
            return true;
        } else {
            error_log("Failed to delete assign");
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------
    //Profile

//------------------------------------------------------------------------------------------------------------------------------------

    public function getEmployeeDetails($employee_id) {
        $this->db->query('SELECT employee_id, name, address, contactNo, email, role, nic FROM employee WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $employee_id);
        return $this->db->single();
    }

//------------------------------------------------------------------------------------------------------------------------------------
    //Reviews

//------------------------------------------------------------------------------------------------------------------------------------
    public function getReviews() {
        $this->db->query('SELECT * FROM bus_reviews');
        return $this->db->resultSet();
    }

    public function replyreview($data) {
        
        $this->db->query('UPDATE bus_reviews SET reply = :reply, replied = :replied WHERE reviewId = :reviewId');
        $this->db->bind(':reply', $data['reply']);
        $this->db->bind(':replied', 'Yes');
        $this->db->bind(':reviewId', $data['reviewId']);

        if ($this->db->execute()) {
            return true;
        } else {
            error_log("Failed to update review reply");
            return false;
        }
    }
//------------------------------------------------------------------------------------------------------------------------------------
    //Notifications

//------------------------------------------------------------------------------------------------------------------------------------

    public function getBusDelays() {
        $this->db->query('SELECT * FROM bus_delay');
        return $this->db->resultSet();
    }

    public function sendNotification($data) {
        $this->db->query('
            INSERT INTO sendnotifications_employee (employee_id, employee_name, employee_type, title, message) 
            VALUES (:employee_id, :employee_name, :employee_type, :title, :message)
        ');

        // Bind parameters
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':employee_name', $data['employee_name']);
        $this->db->bind(':employee_type', $data['employee_type']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':message', $data['message']);


        // Execute and return result
        if ($this->db->execute()) {
            return true;
        } else {
            error_log("Failed to send notification");
            return false;
        }
    }

    public function getEmployee_notification(){
        $this->db->query("SELECT employee_id, name, role FROM employee WHERE role IN ('Driver', 'Conductor')");
        return $this->db->resultSet();
    }
//------------------------------------------------------------------------------------------------------------------------------------
    //Reports

//------------------------------------------------------------------------------------------------------------------------------------
    //total bookings
       //guest bookings
       //registered bookings
    

    public function getGuestBookingsReport($month) {
        $this->db->query("SELECT COUNT(*) as total FROM guestbooking WHERE DATE_FORMAT(booking_date, '%Y-%m') = :month");
        $this->db->bind(':month', $month);
        return $this->db->single()->total;
    }

    public function getRegisteredBookingsReport($month) {
        $this->db->query("SELECT COUNT(*) as total FROM registeredbooking WHERE DATE_FORMAT(booking_date, '%Y-%m') = :month");
        $this->db->bind(':month', $month);
        return $this->db->single()->total;
    }


    //booking cancellation
       //cancelled online bookings
       //cancelled cash bookings
    
    //total income
    //total registered customers
    //total employees in the system
        //total drivers in the system
        //total conductors in the system
        //total admins in the system
    //total routes
    //total buses in the system
    //total schedules in the system






//------------------------------------------------------------------------------------------------------------------------------------
    //boxex in the dashboard 

//------------------------------------------------------------------------------------------------------------------------------------


//------------------------------------------------------------------------------------------------------------------------------------
    //box 01

//------------------------------------------------------------------------------------------------------------------------------------
  
    public function getTotalIncome() {
        $this->db->query("
            SELECT 
                (SELECT IFNULL(SUM(total_price), 0) FROM registeredbooking) AS registered_income,
                (SELECT IFNULL(SUM(total_price), 0) FROM guestbooking) AS guest_income
        ");

        $result = $this->db->single(); // Fetch the single row
        return $result; // Returns ['registered_income' => X, 'guest_income' => Y]
    }
    
    public function getRegisteredIncome() {
        $this->db->query("SELECT SUM(total_price) AS registered_income FROM registeredbooking");
        $result = $this->db->single();  
        return $result['registered_income'] ?? 0; // Return 0 if no income found
    }

    public function getGuestIncome(){
        $this->db->query("SELECT SUM(total_price) AS guest_income FROM guestbooking");
        $result = $this->db->single();
        return $result['guest_income'] ?? 0; // Return 0 if no income found
    }

//------------------------------------------------------------------------------------------------------------------------------------
    //box 02

//------------------------------------------------------------------------------------------------------------------------------------
 
    public function getTotalCustomers() {
        $this->db->query("SELECT COUNT(User_id) AS total_customers FROM customer");
        $result = $this->db->single();
        return $result['total_customers'] ?? 0;
    }

//------------------------------------------------------------------------------------------------------------------------------------
    //box 03

//------------------------------------------------------------------------------------------------------------------------------------
 

    public function getTotalGuestBookings() {
        $this->db->query("SELECT COUNT(id) AS total_guests FROM guestbooking");
        $result = $this->db->single();
        return $result['total_guests'] ?? 0;
    }

    public function getTotalRegisteredBookings() {
        $this->db->query("SELECT COUNT(id) AS total_registered FROM registeredbooking");
        $result = $this->db->single();
        return $result['total_registered'] ?? 0;
    }

    public function getTotalBookings() {
        $this->db->query("
            SELECT 
                (SELECT COUNT(*) FROM registeredbooking) AS registered_bookings,
                (SELECT COUNT(*) FROM guestbooking) AS guest_bookings
        ");

        $result = $this->db->single(); // Fetch the single row
        return $result; // Returns ['registered_bookings' => X, 'guest_bookings' => Y]
    }

//------------------------------------------------------------------------------------------------------------------------------------
    //box 03

//------------------------------------------------------------------------------------------------------------------------------------
 
//------------------------------------------------------------------------------------------------------------------------------------
    //chart 02 booking-cancellation

//------------------------------------------------------------------------------------------------------------------------------------
 
public function getLast7DaysBookingCounts()
{
    $this->db->query("
        SELECT date_series.day,
               COALESCE(gb.count, 0) + COALESCE(rb.count, 0) AS bookings
        FROM (
            SELECT CURDATE() - INTERVAL n DAY AS day
            FROM (
                SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL
                SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
            ) AS days
        ) AS date_series
        LEFT JOIN (
            SELECT DATE(booking_date) AS day, COUNT(*) AS count
            FROM guestbooking
            WHERE booking_date >= CURDATE() - INTERVAL 6 DAY
            GROUP BY DATE(booking_date)
        ) AS gb ON gb.day = date_series.day
        LEFT JOIN (
            SELECT DATE(booking_date) AS day, COUNT(*) AS count
            FROM registeredbooking
            WHERE booking_date >= CURDATE() - INTERVAL 6 DAY
            GROUP BY DATE(booking_date)
        ) AS rb ON rb.day = date_series.day
        ORDER BY date_series.day ASC
    ");

    return $this->db->resultSet();
}


public function getLast7DaysCancellationCounts()
{
    $this->db->query("
        SELECT date_series.day,
               COALESCE(co.count, 0) + COALESCE(cc.count, 0) AS cancellations
        FROM (
            SELECT CURDATE() - INTERVAL n DAY AS day
            FROM (
                SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL
                SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
            ) AS days
        ) AS date_series
        LEFT JOIN (
            SELECT DATE(Booking_date) AS day, COUNT(*) AS count
            FROM cancelled_online_bookings
            WHERE Booking_date >= CURDATE() - INTERVAL 6 DAY
            GROUP BY DATE(Booking_date)
        ) AS co ON co.day = date_series.day
        LEFT JOIN (
            SELECT DATE(Booking_date) AS day, COUNT(*) AS count
            FROM cancelled_cash_bookings
            WHERE Booking_date >= CURDATE() - INTERVAL 6 DAY
            GROUP BY DATE(Booking_date)
        ) AS cc ON cc.day = date_series.day
        ORDER BY date_series.day ASC
    ");

    return $this->db->resultSet();
}

/*
-- Bookings (guestbooking + registeredbooking)
SELECT date_series.day,
       COALESCE(gb.count, 0) + COALESCE(rb.count, 0) AS bookings
FROM (
    SELECT CURDATE() - INTERVAL n DAY AS day
    FROM (
        SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL
        SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
    ) AS days
) AS date_series
LEFT JOIN (
    SELECT DATE(booking_date) AS day, COUNT(*) AS count
    FROM guestbooking
    WHERE booking_date >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(booking_date)
) AS gb ON gb.day = date_series.day
LEFT JOIN (
    SELECT DATE(booking_date) AS day, COUNT(*) AS count
    FROM registeredbooking
    WHERE booking_date >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(booking_date)
) AS rb ON rb.day = date_series.day;





//-- Cancellations (cancelled_online_bookings + cancelled_cash_bookings)
SELECT date_series.day,
       COALESCE(co.count, 0) + COALESCE(cc.count, 0) AS cancellations
FROM (
    SELECT CURDATE() - INTERVAL n DAY AS day
    FROM (
        SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL
        SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
    ) AS days
) AS date_series
LEFT JOIN (
    SELECT DATE(Booking_date) AS day, COUNT(*) AS count
    FROM cancelled_online_bookings
    WHERE Booking_date >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(Booking_date)
) AS co ON co.day = date_series.day
LEFT JOIN (
    SELECT DATE(Booking_date) AS day, COUNT(*) AS count
    FROM cancelled_cash_bookings
    WHERE Booking_date >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(Booking_date)
) AS cc ON cc.day = date_series.day; */

}
?>
