<?php
    class Controller {
        // To load the model file and instantiate the model class in the controller
        public function model($model) {
            require_once '../app/models/'.$model.'.php';
            
            // Instantiate the model and pass it to the controller member variable
            return new $model;
        }
    
        // To load the corresponding view file and pass data
        public function view($view, $data = []) {
            // You can merge URL parameters into the data
            $urlParams = $_GET;  // Get all parameters from the URL
            $data = array_merge($data, $urlParams); // Merge them with any existing data
    
            // If the view exists, load it
            if (file_exists('../app/views/'.$view.'.php')) {
                require_once '../app/views/'.$view.'.php';
            } else {
                // View does not exist
                die('View does not exist');
            }
        }
    }
    

?>