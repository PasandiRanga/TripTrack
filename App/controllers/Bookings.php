<?php
    class Bookings extends Controller {
        public function __construct() {
            echo 'This is the Bookings controller';
        }

        public function create(){
            $data = [];

            $$this->view('inc/Components/BusLayout/BusLayout', $data);
        }
    }