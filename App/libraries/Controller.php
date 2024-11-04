<?php
    class Controller {
        //To load the model file and instantiate the model class in the controller 
        public function model( $model ) {
            require_once '../app/models/'.$model.'.php';
            
            //Instantiate the model and pass it to the controller member variable
            return new $model;

        }

        //To load the corressponding view file
        public function view( $view , $data = [] ) {
            //if the view exists, load it
            if(file_exists('../app/views/'.$view.'.php')) {
                require_once '../app/views/'.$view.'.php';
            } else {
                //View does not exist
                die('View does not exist');
            }
        }
    
    }

?>