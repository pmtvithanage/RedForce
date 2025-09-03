<?php
class Admin extends Controller {
    private $adminModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in and has admin role
        requireAuth('admin');
        
        $this->adminModel = $this->model('M_admin');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('admin/dashboard');
    }

    // dashboard
    public function dashboard() {
        $data = [
            'title' => 'Dashboard',
        ];
        $this->view('admin/v_dashboard', $data);
    }

    // officers
    public function officers() {
        $data = [
            'title' => 'Officers',
        ];
        $this->view('admin/v_officers', $data);
    }

    //clients
    public function clients() {
        $data = [
            'title' => 'Clients',
        ];
        $this->view('admin/v_clients', $data);  
    }

    // scheduling
    public function scheduling() {
        $data = [
            'title' => 'Scheduling',
        ];
        $this->view('admin/v_scheduling', $data);
    }

    //salary
    public function salary() {
        $data = [
            'title' => 'Salary',
        ];
        $this->view('admin/v_salary', $data);
    }

    //advertisements
    public function advertisements() {
        $data = [
            'title' => 'Advertisements',
        ];
        $this->view('admin/v_advertisements', $data);
    }

    // incidents
    public function incidents() {
        $data = [
            'title' => 'Incidents',
        ];
        $this->view('admin/v_incidents', $data);
    }

    // reports
    public function reports() {
        $data = [
            'title' => 'Reports',
        ];
        $this->view('admin/v_reports', $data);  
    }

    // settings
    public function settings() {
        $data = [
            'title' => 'Settings',
        ];
        $this->view('admin/v_settings', $data);
    }



}