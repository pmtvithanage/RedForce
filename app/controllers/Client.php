<?php
class Client extends Controller {
    private $clientModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in and has client role
        requireAuth('client');
        $this->clientModel = $this->model('M_client');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('client/dashboard');
    }

    // dashboard
    public function dashboard() {
        // Sample data - replace with actual database queries
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard'
        ];
        
        $this->view('client/v_dashboard', $data);
    }

    //view officers (guards)
    public function officers() {
        // Sample data - replace with actual database queries
        $data = [
            'title' => 'View Officers',
            'pageTitle' => 'View Guards'
        ];
        
        $this->view('client/v_officers', $data);
    }

    //requests
    public function requests() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        // Initialize variables
        $data = [
            'title' => 'Requests',
            'pageTitle' => 'Service Requests',
            'showSuccessMessage' => false,
            'errorMessage' => '',
            'previousRequests' => [],
            'showHistory' => isset($_GET['show_history'])
        ];

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
            // Get current date
            $currentDate = date('Y-m-d');
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            
            // Validate dates
            if ($startDate < $currentDate) {
                $data['errorMessage'] = 'Starting date cannot be in the past. Please select today or a future date.';
            } elseif ($endDate < $currentDate) {
                $data['errorMessage'] = 'Ending date cannot be in the past. Please select today or a future date.';
            } elseif ($endDate < $startDate) {
                $data['errorMessage'] = 'Ending date cannot be before starting date.';
            } else {
                // Prepare data for database
                $requestData = [
                    'client_id' => $_SESSION['user_id'],
                    'event_name' => trim($_POST['eventName']),
                    'event_description' => trim($_POST['eventDescription']),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'start_time' => $_POST['startTime'],
                    'end_time' => $_POST['endTime'],
                    'location' => trim($_POST['location']),
                    'number_of_guards' => $_POST['numberOfGuards'],  // Changed from guardCount
                    'comments' => trim($_POST['comments'])
                ];

                // Save to database (removed guard type validation)
                if ($this->clientModel->createServiceRequest($requestData)) {
                    $data['showSuccessMessage'] = true;
                    // Clear POST data to prevent resubmission
                    $_POST = [];
                } else {
                    $data['errorMessage'] = 'Failed to submit request. Please try again.';
                }
            }
        }

        // Handle delete request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_request'])) {
            $request_id = $_POST['request_id'];
            
            if ($this->clientModel->deleteServiceRequest($request_id, $_SESSION['user_id'])) {
                // Redirect back to history view with success message
                redirect('client/requests?show_history=1&deleted=1');
            } else {
                $data['errorMessage'] = 'Failed to delete request. Please try again.';
            }
        }

        // Get client's previous requests for history popup
        $data['previousRequests'] = $this->clientModel->getClientServiceRequests($_SESSION['user_id']);
        $data['todayDate'] = date('Y-m-d');

        // Load view
        $this->view('client/v_requests', $data);
    }

    //view history
    public function history() {
        $data = [
            'title' => 'View History',
            'pageTitle' => 'Service History'
        ];
        $this->view('client/v_history', $data);
    }

    //profile
    public function profile() {
        $data = [
            'title' => 'Profile',
            'pageTitle' => 'My Profile'
        ];
        $this->view('client/v_profile', $data); 
    }

}