<?php
class Supervisor extends Controller {
    private $homeModel;

    public function __construct() {
        $this->homeModel = $this->model('M_supervisor');
    }

    // Default action
    public function index() {
          
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