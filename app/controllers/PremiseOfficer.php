<?php
class PremiseOfficer extends Controller {
    private $premiseOfficerModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in and has premise officer role
        requireAuth('premise officer');
        $this->premiseOfficerModel = $this->model('M_premiseofficer');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('premiseofficer/dashboard');
    }

    // Dashboard action
    public function dashboard() {
        $data = [
            'title' => 'Dashboard',
        ];
        $this->view('premiseofficer/v_dashboard', $data);
    }

    // Schedule action
    public function schedule() {
        $data = [
            'title' => 'Schedule',
        ];
        $this->view('premiseofficer/v_schedule', $data);
    }
    // Requests action
    public function requests() {
        $data = [
            'title' => 'Requests',
        ];
        $this->view('premiseofficer/v_requests', $data);
    }
}