<?php
    //This is the database class that will be used to connect to the database and perform any sort of a query to the database using PDO 
    class Database {
        //Get the configurations from the config file
        private $host = DB_HOST;
        private $user = DB_USER;
        private $password = DB_PASSWORD;
        private $dbname = DB_NAME;
        
        //Declare the database handler and the statement
        private $dbh; //Database host
        private $statement; // any sort of a statement like insert, update, delete, select
        private $error; // to store the error (exception handler in PDO) 

        public function __construct(){
            //specify the DSN (Data Source Name) and create the connection to the database using PDO (PHP Data Objects)
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
            //host as local host and dbname as triptrack

            //declare some options for the PDO
            //That will be used in PDO
            $options = array(
                PDO::ATTR_PERSISTENT => true, //persistent connection to the database
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //to throw exceptions
                //if there is an error in the database connection easily catch the error
            );

            //Instantiate a new PDO instance
            try{
                //Create a new PDO instance to dbh variable
                $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
            }catch(PDOException $e){
                //Store the exception message in the error variable
                $this->error = $e->getMessage();
                //echo the error message
                echo $this->error;
            }
        }

        //Prepared statement with query(it can be any sort of a query) 
        //We can execute any sort of a query using this prepared statement
        //Takes sql query as a parameter(string)
        public function query($sql)
        {
            //set the statement to the database handler
            //Prepare the statement
            $this->statement = $this->dbh->prepare($sql);
        }

        //Bind parameters: Bind values to the statement means we can bind the values to the query that we have written in the query function 
        //Take the parameter then value as the second parameter and the type of the value as the third parameter default is null
        public function bind($param , $value , $type=NULL){
            //check whether the type is null
            if(is_null($type)){
                //If type is not specified
                //PDO must itself declare a type for the statement
                switch(true) {
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                        break;
                }
            }

            //Bind the value to the statement
            $this->statement->bindValue($param, $value, $type);

        }

        //Execute the prepared statement
        public function execute(){
            return $this->statement->execute();
        }

        //Return can be a single record or multiple records
        //If we want to get multiple records, we can use fetchAll()
        //Get multiple records as the result
        public function resultSet(){
            //call the execution function
            $this->execute();
            //return the result as an associative array (PDO)
            return $this->statement->fetchAll(PDO::FETCH_ASSOC);
        }

        //Get a single record
        public function single(){
            //call the execution function
            $this->execute();
            //return the result as an associative array (PDO)
            return $this->statement->fetch(PDO::FETCH_ASSOC);
        }

        //To check there are results to retrieve 
        //checking the record count
        //If there are any rows in the database to return
        public function rowCount(){
            //return the row count
            return $this->statement->rowCount();
        }

    }

?>