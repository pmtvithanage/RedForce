<?php
class PremiseOfficer extends Controller {
    private $premiseOfficerModel;
    private $userModel;
    private $advertisementModel;

    public function __construct() {
        // Check if user is logged in and has premise officer role
        requireAuth('premise officer');

        $this->advertisementModel = $this->model('M_advertisements');
        $this->premiseOfficerModel = $this->model('M_premiseofficer');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('premiseofficer/dashboard');
    }

    // Dashboard action
    public function dashboard() {
        // Fetch advertisements based on role dynamically
        $role = 'premise officer';
        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);

        $data = [
            'title' => 'Dashboard',
            'advertisements' => $advertisements
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

    // Profile action
    public function profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $data = [
            'title' => 'Profile'
        ];

        // Load view
        $this->view('premiseofficer/v_profile', $data);
    }
}
?>