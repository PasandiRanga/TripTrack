<?php
    class Database {
        //Get the configurations from the config file
        private $host = DB_HOST;
        private $user = DB_USER;
        private $password = DB_PASSWORD;
        private $dbname = DB_NAME;
        
        private $dbh; //Database host
        private $statement; // any sort of a statement like insert, update, delete, select
        private $error; // to store the error

        public function __construct(){
            //specify the DSN (Data Source Name) and create the connection to the database using PDO (PHP Data Objects)
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

            //declare some options for the PDO
            //That will be used in PDO
            $options = array(
                PDO::ATTR_PERSISTENT => true, //persistent connection to the database
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //to throw exceptions
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

        //Prepared statement with query
        //We can execute any sort of a query using this prepared statement
        //Takes sql query as a parameter(string)
        public function query($sql)
        {
            //Prepare the statement
            $this->statement = $this->dbh->prepare($sql);
        }

        //Bind parameters
        public function bind($param , $value , $type=NULL){
            //check whether the type is null
            if(is_null($type)){
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
        public function result(){
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

        //checking the record count
        //If there are any rows in the database to return
        public function rowCount(){
            //return the row count
            return $this->statement->rowCount();
        }

    }

?>