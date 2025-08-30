<?php
class MobileRider extends Controller {
    private $homeModel;

    public function __construct() {
        // Load the model
        $this->homeModel = $this->model('M_mobilerider');
    }

    // Default action
    public function index() {
        // Currently empty → could redirect to dashboard or load a default view
    }

    // Dashboard action
    public function dashboard() {
        $data = [
            'title' => 'Dashboard',
        ];
        $this->view('mobilerider/v_dashboard', $data);
    }

    // Schedule action
    public function sites() {
        $data = [
            'title' => 'Sites',
        ];
        $this->view('mobilerider/v_sites', $data);
    }
    // Messages
    public function messages() {
        $data = [
            'title' => 'Messages',
        ];
        $this->view('mobilerider/v_messages', $data);
    }
    // Incidents
    public function incidents() {
        $data = [
            'title' => 'Incidents',
        ];
        $this->view('mobilerider/v_incidents', $data);
    }
    // Leave Requests
    public function leaverequests() {
        $data = [
            'title' => 'Leave Requests',
        ];
        $this->view('mobilerider/v_leaverequests', $data);  
    }
    // Profile
    public function profile() {
        $data = [
            'title' => 'Profile',
        ];
        $this->view('mobilerider/v_profile', $data);
    }
}