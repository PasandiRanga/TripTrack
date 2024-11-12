<?php
class SuperAdminPages extends Controller {
    // Variable to hold the model
    private $SuperAdminModel;

    public function __construct() {
        // Call the model method and assign it to the SuperAdminModel variable
        $this->SuperAdminModel = $this->model('M_SuperAdminPages'); // Adjust the model name as per your implementation
    }

    public function home() {
        $this->view('pages/SuperAdmin/Dashboard');
    }

    public function fleet() {
        $this->view('pages/SuperAdmin/Fleet');
    }
    public function AddFleet() {
        $this->view('pages/SuperAdmin/Fleet/Addfleet');
    }

    public function bookings() {
        $this->view('pages/SuperAdmin/Bookings');
    }

    public function reports() {
        $this->view('pages/SuperAdmin/Reports');
    }

    public function reviews() {
        $this->view('pages/SuperAdmin/Reviews');
    }

    public function schedule() {
        $this->view('pages/SuperAdmin/Schedule');
    }

    public function leave_requests() {
        $this->view('pages/SuperAdmin/Leave_Requests');
    }

    public function notifications() {
        $this->view('pages/SuperAdmin/Notifications');
    }

    public function users() {
        $this->view('pages/SuperAdmin/Users');
    }

}
?>
