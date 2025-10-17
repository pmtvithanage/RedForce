<?php
class Caretaker extends Controller {
    private $caretakerModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in and has care taker role
        requireAuth('caretaker');
        $this->caretakerModel = $this->model('M_caretaker');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('caretaker/dashboard');
    }

    // dashboard
    public function dashboard() {
        $data = [
            'title' => 'Dashboard',
        ];
        $this->view('caretaker/v_dashboard', $data);
    }

    // Messages
    public function messages() {
        $data = [
            'title' => 'Messages',
        ];
        $this->view('caretaker/v_messages', $data);
    }

    //Leave Requests
    public function leaverequests() {
        $data = [
            'title' => 'Leave Requests',
        ];
        $this->view('caretaker/v_leaverequests', $data);  
    }

    //Profile
    public function profile() {
        $data = [
            'title' => 'Profile',
        ];
        $this->view('caretaker/v_profile', $data);
    }


}