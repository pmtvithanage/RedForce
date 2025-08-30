<?php
class Caretaker extends Controller {
    private $homeModel;

    public function __construct() {
        $this->homeModel = $this->model('M_caretaker');
    }

    // Default action
    public function index() {
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