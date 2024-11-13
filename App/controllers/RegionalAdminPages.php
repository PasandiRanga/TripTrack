<?php
class RegionalAdminPages extends Controller {
    // Variable to hold the model
    private $RegionalAdminModel;

    public function __construct() {
        // Call the model method and assign it to the SuperAdminModel variable
        $this->RegionalAdminModel = $this->model('M_RegionalAdminPages'); // Adjust the model name as per your implementation
    }

    public function home() {
        $this->view('pages/RegionalAdmin/Home');
    }

    public function support() {
        $this->view('pages/RegionalAdmin/Support');
    }

    public function viewbookings() {
        $this->view('pages/RegionalAdmin/ViewBookings');
    }

    public function schedule() {
        $this->view('pages/RegionalAdmin/Schedule');
    }

    public function notifications() {
        $this->view('pages/RegionalAdmin/Notifications');
    }

}