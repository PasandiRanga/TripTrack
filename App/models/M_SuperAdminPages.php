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

        public function getBus(){
            $this->db->query('SELECT * FROM bus');

            return $this->db->resultSet();

        }
    }
?>