<?php
class Supervisor extends Controller {
    private $supervisorModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in and has supervisor role
        requireAuth('supervisor');
        $this->supervisorModel = $this->model('M_supervisor');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('supervisor/dashboard');
    }

    // dashboard
    public function dashboard() {
        $data = [
            'title' => 'Dashboard',
        ];
        $this->view('supervisor/v_dashboard', $data);
    }

    // officers
    public function officers() {
        $data = [
            'title' => 'Officers',
        ];
        $this->view('supervisor/v_officers', $data);
    }
    // Messages
    public function messages() {
        $data = [
            'title' => 'Messages',
        ];
        $this->view('supervisor/v_messages', $data);
    }

    //Leave Requests
    public function leave_requests() {
        $data = [
            'title' => 'Leave Requests',
        ];
        $this->view('supervisor/v_leaverequests', $data);  
    }

    //Profile
    public function profile() {
        $data = [
            'title' => 'Profile',
        ];
        $this->view('supervisor/v_profile', $data);
    }


}