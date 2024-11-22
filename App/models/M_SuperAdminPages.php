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
        $this->db->query("INSERT INTO bus (License_id, routeNumber, route, busType, stops, start_location, destination, rating, passengers, price, priceperkm) 
                           VALUES (:licence_id, :route_no, :route, :bus_type, :stops, :starts, :destination, :ratings, :passengers, :price, :price_per_km)");

        // Bind parameters
        $this->db->bind(':licence_id', $data['licence_id']);
        $this->db->bind(':route_no', $data['route_no']);
        $this->db->bind(':route', $data['route']);
        $this->db->bind(':bus_type', $data['bus_type']);
        $this->db->bind(':stops', $data['stops']);
        $this->db->bind(':starts', $data['starts']);
        $this->db->bind(':destination', $data['destination']);
        $this->db->bind(':ratings', $data['ratings']);
        $this->db->bind(':passengers', $data['passengers']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':price_per_km', $data['price_per_km']);

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
            busType = :busType,
            stops = :stops,
            start_location = :start_location,
            destination = :destination,
            rating = :rating,
            passengers = :passengers,
            price = :price,
            priceperkm = :priceperkm
            WHERE License_id = :License_id');

        // Bind parameters
        $this->db->bind(':routeNumber', $data['routeNumber']);
        $this->db->bind(':route', $data['route']);
        $this->db->bind(':busType', $data['busType']);
        $this->db->bind(':stops', $data['stops']);
        $this->db->bind(':start_location', $data['start_location']);
        $this->db->bind(':destination', $data['destination']);
        $this->db->bind(':rating', $data['rating']);
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

    // Retrieve all fleet data
    public function getAllFleet() {
        $sql = "SELECT * FROM bus";
        $this->db->query($sql);

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

    public function addDriver($data) {
        $this->db->query('INSERT INTO driver (Employee_name, Employee_username, Nic, Address, Contact_no, Password) VALUES (:name, :username, :nic, :address, :contact_no, :password)');
        $this->db->bind(':name', $data['employeeName']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contact_no', $data['contactNo']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_BCRYPT));

        return $this->db->execute();
    }

    public function addConductor($data) {
        $this->db->query('INSERT INTO conductor (Employee_name, Employee_username, Nic, Address, Contact_no, Password) VALUES (:name, :username, :nic, :address, :contact_no, :password)');
        $this->db->bind(':name', $data['employeeName']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contact_no', $data['contactNo']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_BCRYPT));
    
        return $this->db->execute();
    }

    public function addAdmin($data) {
        $this->db->query('INSERT INTO system_admin (Admin_name, Email, Nic, Address, Contact_no, Region, Password) VALUES (:name, :email, :nic, :address, :contact_no, :region, :password)');
        $this->db->bind(':name', $data['adminName']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contact_no', $data['contactNo']);
        $this->db->bind(':region', $data['region']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_BCRYPT));
    
        return $this->db->execute();
    }
    
    

}
?>
