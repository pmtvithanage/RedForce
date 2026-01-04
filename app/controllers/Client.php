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
                    // Instead of popup, set flash message
                    $_SESSION['success_message'] = 'Your service request has been submitted successfully!';
                    header('Location: ' . URL_ROOT . '/client/requests');
                    exit;
                } else {
                    $_SESSION['error_message'] = 'Failed to submit request. Please try again.';
                }
            }
        }
        
        // Get flash messages
        $data['showSuccessMessage'] = isset($_SESSION['success_message']);
        $data['errorMessage'] = $_SESSION['error_message'] ?? '';
        
        // Clear flash messages
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

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

    public function requestHistory() {
        $clientId = $_SESSION['user_id'];
        $requests = $this->clientModel->getClientServiceRequests($clientId);
        
        $data = [
            'title' => 'Request History',
            'pageTitle' => 'Request History',
            'requests' => $requests
        ];
        
        $this->view('Client/requests/v_history', $data);
    }

    public function deleteRequest($requestId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $clientId = $_SESSION['user_id'];
            
            if ($this->clientModel->deleteServiceRequest($requestId, $clientId)) {
                $_SESSION['success_message'] = 'Request deleted successfully.';
            } else {
                $_SESSION['error_message'] = 'Failed to delete request.';
            }
        }
        
        header('Location: ' . URL_ROOT . '/client/requestHistory');
        exit;
    }

    public function messages($conversationId = null) {
        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages',
            'conversationId' => $conversationId
        ];
        
        $this->view('Client/dashboard/v_messages', $data);
    }

    public function incidents() {
        $data = [
            'title' => 'Incident Reports',
            'pageTitle' => 'Incident Reports'
        ];
        
        $this->view('Client/dashboard/v_incidents', $data);
    }

    public function rateOfficer($officerId = null) {
        // Redirect to officers page if no officer ID provided
        if (!$officerId) {
            redirect('client/officers');
        }

        $data = [
            'title' => 'Rate Officer',
            'pageTitle' => 'Rate Officer',
            'officerId' => $officerId
        ];
        
        $this->view('Client/officers/v_rate', $data);
    }

    // Package Pages
    public function basicPackage() {
        $data = [
            'title' => 'Basic Package',
            'pageTitle' => 'Basic Package'
        ];
        $this->view('Client/requests/v_basic', $data);
    }

    public function budgetPackage() {
        $data = [
            'title' => 'Budget Package',
            'pageTitle' => 'Budget Package'
        ];
        $this->view('Client/requests/v_budget', $data);
    }

    public function vigilantPackage() {
        $data = [
            'title' => 'Vigilant Package',
            'pageTitle' => 'Vigilant Package'
        ];
        $this->view('Client/requests/v_vigilant', $data);
    }

    public function proPackage() {
        $data = [
            'title' => 'Pro Package',
            'pageTitle' => 'Pro Package'
        ];
        $this->view('Client/requests/v_pro', $data);
    }

    public function ultraPackage() {
        $data = [
            'title' => 'Ultra Package',
            'pageTitle' => 'Ultra Package'
        ];
        $this->view('Client/requests/v_ultra', $data);
    }

    public function customPackage() {
        $data = [
            'title' => 'Custom Package',
            'pageTitle' => 'Custom Package'
        ];
        $this->view('Client/requests/v_custom', $data);
    }

    // Handle package request submission
    public function submitPackageRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestData = [
                'client_id' => $_SESSION['user_id'],
                'package_name' => $_POST['package_name'],
                'site_address' => trim($_POST['site_address']),
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'number_of_guards' => $_POST['number_of_guards'],
                'day_guards' => $_POST['day_guards'] ?? null,
                'night_guards' => $_POST['night_guards'] ?? null,
                'package_price' => $_POST['package_price'],
                'comments' => trim($_POST['comments'] ?? '')
            ];

            if ($this->clientModel->createPackageRequest($requestData)) {
                flash('package_success', 'Package request submitted successfully!', 'alert-success');
            } else {
                flash('package_error', 'Failed to submit request. Please try again.', 'alert-danger');
            }
        }
        redirect('client/requests');
    }

    public function packageHistory() {
        $requests = $this->clientModel->getClientPackageRequests($_SESSION['user_id']);
        $data = ['title' => 'Request History', 'pageTitle' => 'Package Request History', 'requests' => $requests];
        $this->view('Client/requests/v_package_history', $data);
    }

    public function deletePackageRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->clientModel->deletePackageRequest($id, $_SESSION['user_id'])) {
                flash('package_success', 'Request deleted successfully', 'alert-success');
            } else {
                flash('package_error', 'Failed to delete request', 'alert-danger');
            }
        }
        redirect('client/packageHistory');
    }
}
