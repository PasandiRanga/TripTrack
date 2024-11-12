<?php
    class M_GuestPages {
        //Declare a variable to grant access to the database
        private $db;

        //whenever the script is called we need to instantiate the data base class

        public function __construct(){
            //Instantiate the database class
            $this->db = new Database();
        }

            //We should connect this model with the corressponding controller

            //Register the user
            public function register($data){
                $this->db->query('INSERT INTO customer(Name,Email,NIC,Address,Contact_number,Password) VALUES(:name,:email,:nic,:address,:number,:password)');
                $this->db->bind(':name',$data['name']);
                $this->db->bind(':email',$data['email']);
                $this->db->bind(':nic',$data['nic']);
                $this->db->bind(':address',$data['address']);
                $this->db->bind(':number',$data['number']);
                $this->db->bind(':password',$data['password']);

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
    }
?>