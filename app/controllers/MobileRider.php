<?php
class MobileRider extends Controller {
    private $mobileRiderModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in and has mobile rider role
        requireAuth('mobile rider');
        $this->mobileRiderModel = $this->model('M_mobileRider');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('mobilerider/dashboard');
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