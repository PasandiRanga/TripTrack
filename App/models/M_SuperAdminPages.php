<?php
    class M_SuperAdminPages {
        //Declare a variable to grant access to the database
        private $db;

        //whenever the script is called we need to instantiate the data base class

        public function __construct(){
            //Instantiate the database class
            $this->db = new Database();

            //We should connect this model with the corressponding controller
        }

        //Fleet
        
        public function addBus($data) {
            // Prepare the SQL query to insert bus details
            $this->db->query("INSERT INTO bus (busId, License_id, routeNumber, route, busType, stops, start_location, destination, rating, passengers, price, priceperkm) 
                               VALUES (:bus_id, :licence_id, :route_no, :route, :bus_type, :stops, :starts, :destination, :ratings, :passengers, :price, :price_per_km)");
        
            // Bind parameters
            $this->db->bind(':bus_id', $data['bus_id']);
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
        

        public function getBus(){
            $this->db->query('SELECT * FROM bus');

            return $this->db->resultSet();

        }

        public function getBusById($busId) {
            $this->db->query('SELECT * FROM bus WHERE busId = :busId');
            $this->db->bind(':busId', $busId);
        
            return $this->db->single(); // Fetch a single row
        }
        
        

        public function deleteBus($busId) {
            $this->db->query('DELETE FROM bus WHERE busId = :busId');
            $this->db->bind(':busId', $busId);
            return $this->db->execute();
        }
        
        public function updateBus($data) {
            $this->db->query('UPDATE bus SET 
                License_id = :License_id,
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
                WHERE busId = :busId');
            
            // Bind parameters
            $this->db->bind(':License_id', $data['License_id']);
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
            $this->db->bind(':busId', $data['busId']);
        
            // Execute and return result
            return $this->db->execute();
        }
        

        //search bus with license_id and routeNumber

        public function searchFleet($searchQuery) {
            // Prepare the SQL query to search for License ID or Route Number
            $sql = "SELECT * FROM bus WHERE LOWER(License_id) LIKE :searchQuery OR LOWER(routeNumber) LIKE :searchQuery";

            // Prepare the query
            $this->db->query($sql);

            // Bind the search query parameter (with wildcards for partial matching)
            $this->db->bind(':searchQuery', '%' . strtolower($searchQuery) . '%');

            // Execute the query and return the results
            return $this->db->resultSet();
        }

        public function getAllFleet() {
            $sql = "SELECT * FROM bus"; // Query to fetch all buses
            $this->db->query($sql);
        
            $results = $this->db->resultSet();
            return $results;
        }
        

 // -------------------------------------------------------------------------------------------------------------------------------------------------------------
        //Bookings        

        public function getGuestBookings(){
            $this->db->query('SELECT * FROM guestbooking');

            return $this->db->resultSet();
        }

        public function getRegisterBookings(){
            $this->db->query('SELECT * FROM registeredbooking');

            return $this->db->resultSet();
        }
    }
?>