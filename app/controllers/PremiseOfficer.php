<?php
class PremiseOfficer extends Controller {
    private $homeModel;

    public function __construct() {
        // Load the model
        $this->homeModel = $this->model('M_premiseofficer');
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