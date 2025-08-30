<?php
class Client extends Controller {
    private $homeModel;

    public function __construct() {
        $this->homeModel = $this->model('M_client');
    }

    // Default action
    public function index() {
        
        
    }

    // dashboard
    public function dashboard() {
        $data = [
            'title' => 'Dashboard',
        ];
        $this->view('client/v_dashboard', $data);
    }

    //view officers
    public function officers() {
        $data = [
            'title' => 'View Officers',
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