<?php
class Supervisor extends Controller {
    private $supervisorModel;
    private $userModel;
    private $advertisementModel;

    public function __construct() {
        // Check if user is logged in and has supervisor role
        requireAuth('supervisor');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->supervisorModel = $this->model('M_supervisor');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('supervisor/dashboard');
    }

    // dashboard
    public function dashboard() {
        $role = 'supervisor';
        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);

        $data = [
            'title' => 'Dashboard',
            'advertisements' => $advertisements
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