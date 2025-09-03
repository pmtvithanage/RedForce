<?php
class Client extends Controller {
    private $clientModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in and has client role
        requireAuth('client');
        $this->clientModel = $this->model('M_client');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('client/dashboard');
    }

    // dashboard
    public function dashboard() {
        // Sample data - replace with actual database queries
        $data = [
            'title' => 'Dashboard',
        ];
        
        $this->view('client/v_dashboard', $data);
    }

    // Add this method to your existing Client.php controller

//view officers (guards)
public function officers() {
    // Sample data - replace with actual database queries
    $data = [
        'title' => 'View Guards',
    ];
    
    $this->view('client/v_officers', $data);
}

    //requests
    public function requests() {
        $data = [
            'title' => 'Requests',
        ];
        $this->view('client/v_requests', $data);
    }

    //view history
    public function history() {
        $data = [
            'title' => 'View History',
        ];
        $this->view('client/v_history', $data);
    }

    //profile
    public function profile() {
        $data = [
            'title' => 'Profile',
        ];
        $this->view('client/v_profile', $data); 
    }

}