<?php
    class Core {
        //Things related to routing
        //URL format: /controller/method/params

        protected $currentController = 'Pages';
        protected $currentMethod = 'index'; //basic method
        protected $params = [];

        //In OOP after the class is specified we need to instantiate the constructor
        public function __construct() {
            // print_r($this->getURL());
            $url = $this->getURL();

            //check whether the controller exists or not
            if(file_exists('../app/controllers/'.ucwords($url[0]).'.php')) {
                //If exists, set as controller
                $this->currentController = ucwords($url[0]);
                //Unset 0 index. unset the coroller in the URL
                unset($url[0]);

                //Call the controller
                require_once '../app/controllers/'.$this->currentController.'.php';

                //After the controller is called, instantiate the controller class
                $this->currentController = new $this->currentController();
                //Initially currentController holds a string value, but after the above line it will hold an object of the specific class.

                //Check whether the method is specified in the url
                if(isset($url[1])) {
                    //check if method exists in the controller
                    if(method_exists($this->currentController, $url[1])) //which class , which method {
                    {
                        $this->currentMethod = $url[1];
                        //Unset 1 index. unset the method in the URL
                        unset($url[1]);
                    }
                }

                //Get parameter list
                $this->params = $url ? array_values($url) : [];

                //Call method and pass the parameter list to the method of the controller class 
                call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

            }
        }

        public function getURL(){
            //check whether the URL is specified or not
            if(isset($_GET['url'])){
                // Trim the url from the right side
                $url = rtrim($_GET['url'], '/');
                //Filter the URL since it can have unwanted characters/symbols
                $url = filter_var($url, FILTER_SANITIZE_URL);
                //Breaking the URL into an array
                $url = explode('/', $url);
                //This will be an associative array that will contain the controller, method and params
                //ex : Array ( [0] => pages [1] => GuestUser [2] => home.html )
                return $url;
            }
        }
    }
?>