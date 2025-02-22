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
            route = :route,
            start_location = :start_location,
            destination = :destination,
            passengers = :passengers,
            price = :price,
            priceperkm = :priceperkm
            WHERE License_id = :License_id');

        // Bind parameters
        $this->db->bind(':routeNumber', $data['routeNumber']);
        $this->db->bind(':route', $data['route']);
        //$this->db->bind(':busType', $data['busType']);
        $this->db->bind(':stops', $data['stops']);
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

    public function addemployee($data){
        $this->db->query('INSERT INTO employee(name,address,contactNo,email,password,role,nic) VALUES(:name,:address,:contactNo,:email,:password,:role,:nic)');
        $this->db->bind(':name',$data['name']);
        $this->db->bind(':address',$data['address']);
        $this->db->bind(':nic',$data['nic']);
        $this->db->bind(':contactNo',$data['contactNo']);
        $this->db->bind(':email',$data['email']);
        $this->db->bind(':password',$data['password']);
        $this->db->bind(':role',$data['role']);
        $this->db->bind(':nic',$data['nic']);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    public function getEmployee(){
        $this->db->query('SELECT * FROM employee');
        return $this->db->resultSet();
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

//------------------------------------------------------------------------------------------------------------------------------------
    //support requests

//------------------------------------------------------------------------------------------------------------------------------------
    public function getcontactsrequests(){
        $this->db->query('SELECT * FROM support_request');
        return $this->db->resultSet();
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

            date_default_timezone_set('Asia/Colombo');
            $currentDate = date("Y-m-d");  // Get current date
            $currentTime = date("H:i:s");  // Get current time

            $this->db->query('INSERT INTO assign (scheduleId, driver_id, conductor_id, assign_time, assign_date) 
                            VALUES (:scheduleId, :driver_id, :conductor_id, :assign_time, :assign_date)');

            $this->db->bind(':scheduleId', $data['scheduleId']);
            $this->db->bind(':driver_id', $data['driver_id']);
            $this->db->bind(':conductor_id', $data['conductor_id']);
            $this->db->bind(':assign_time', $currentTime);
            $this->db->bind(':assign_date', $currentDate);

            if ($this->db->execute()) {
                return true; // Success
            } else {
                error_log("Database error: Failed to insert assignment"); // Log error
                return false; // Failure
            }
        } catch (Exception $e) {
            error_log("Exception in addAssigns: " . $e->getMessage()); // Log exception
            return false;
        }
    }

    public function getScheduleID(){
        $this->db->query("SELECT scheduleId FROM schedule");
        return $this->db->resultSet();
    }

    public function getDriverID(){
        $this->db->query("SELECT employee_id FROM employee WHERE role='Driver'");
        return $this->db->resultSet();
    }

    public function getConductorID(){
        $this->db->query("SELECT employee_id FROM employee WHERE role='Conductor'");
        return $this->db->resultSet();
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
    //boxex in the dashboard 

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

    public function getTotalCustomers() {
        $this->db->query("SELECT COUNT(User_id) AS total_customers FROM customer");
        $result = $this->db->single();
        return $result['total_customers'] ?? 0;
    }

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
}
?>
