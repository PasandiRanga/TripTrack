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

}
?>
