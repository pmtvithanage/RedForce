<?php
class Client extends Controller {
    private $clientModel;
    private $userModel;
    private $notificationModel;

    public function __construct() {
        // Check if user is logged in and has client role
        requireAuth('client');
        $this->clientModel = $this->model('M_client');
        $this->userModel = $this->model('M_users');
        $this->notificationModel = $this->model('M_notifications');
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

    public function notifications() {
        // TODO: Fetch notifications from database
        $notifications = $this->notificationModel->getNotifications($_SESSION['user_id']);
        
        $data = [
            'title' => 'Notifications',
            'pageTitle' => 'Notifications',
            'role' => 'client',
            'notifications' => $notifications
        ];
        $this->view('components/notifications', $data);
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
            // Calculate end date and total price based on number of months
            $startDate = $_POST['start_date'];
            $numMonths = isset($_POST['num_months']) ? (int)$_POST['num_months'] : 1;
            $monthlyPrice = isset($_POST['monthly_price']) ? (int)$_POST['monthly_price'] : (int)$_POST['package_price'];
            
            // Calculate end date (start date + num_months)
            $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $numMonths . ' months'));
            
            // Calculate total price
            $totalPrice = $monthlyPrice * $numMonths;
            
            // Handle image upload if present
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imageName = time() . '_' . $_FILES['image']['name'];
                if (!uploadImage($_FILES['image']['tmp_name'], $imageName, '/uploads/siteImages/')) {
                    $imageName = null; // Reset if upload failed
                }
            }
            
            $requestData = [
                'client_id' => $_SESSION['user_id'],
                'package_name' => $_POST['package_name'],
                'site_name' => trim($_POST['site_name']),
                'district' => trim($_POST['district'] ?? ''),
                'city' => trim($_POST['city']),
                'site_address' => trim($_POST['site_address']),
                'latitude' => $_POST['latitude'] ?? null,
                'longitude' => $_POST['longitude'] ?? null,
                'phone_number' => trim($_POST['phone_number'] ?? ''),
                'image_name' => $imageName,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'number_of_guards' => $_POST['number_of_guards'],
                'day_guards' => $_POST['day_guards'] ?? null,
                'night_guards' => $_POST['night_guards'] ?? null,
                'package_price' => $totalPrice,
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

    // ==================== EQUIPMENT REQUESTS METHODS ====================

    // View all equipment requests from caretakers
    public function equipmentRequests() {
        // Get filters
        $filters = [];
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (isset($_GET['priority']) && !empty($_GET['priority'])) {
            $filters['priority'] = $_GET['priority'];
        }
        if (isset($_GET['caretaker_id']) && !empty($_GET['caretaker_id'])) {
            $filters['caretaker_id'] = $_GET['caretaker_id'];
        }
        if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }

        // Get client ID
        $client_id = $_SESSION['user_id'];

        // Get data
        $requests = $this->clientModel->getAllEquipmentRequests($client_id, $filters);
        $stats = $this->clientModel->getEquipmentRequestStats($client_id);
        $caretakers = $this->clientModel->getCaretakersForClient($client_id);

        $data = [
            'title' => 'Equipment Requests',
            'pageTitle' => 'Equipment Requests Management',
            'requests' => $requests,
            'stats' => $stats,
            'caretakers' => $caretakers,
            'filters' => $filters
        ];

        $this->view('client/v_equipment_requests', $data);
    }

    // View single equipment request details for review
    public function reviewEquipmentRequest($id) {
        $client_id = $_SESSION['user_id'];
        $request = $this->clientModel->getEquipmentRequestDetails($id, $client_id);

        if (!$request) {
            flash('equipment_error', 'Request not found', 'alert alert-danger');
            redirect('client/equipmentRequests');
            return;
        }

        $data = [
            'title' => 'Equipment Requests',
            'pageTitle' => 'Review Equipment Request',
            'request' => $request
        ];

        $this->view('client/v_review_equipment', $data);
    }

    // Approve equipment request
    public function approveEquipmentRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Prepare data
            $data = [
                'id' => $id,
                'client_notes' => trim($_POST['client_notes'] ?? ''),
                'client_id' => $_SESSION['user_id']
            ];

            // Approve
            if ($this->clientModel->approveEquipmentRequest($data)) {
                flash('equipment_message', 'Equipment request approved successfully', 'alert alert-success');
            } else {
                flash('equipment_error', 'Failed to approve request', 'alert alert-danger');
            }

            redirect('client/equipmentRequests');
        } else {
            redirect('client/equipmentRequests');
        }
    }

    // Reject equipment request
    public function rejectEquipmentRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validate
            if (empty($_POST['client_notes'])) {
                flash('equipment_error', 'Please provide a reason for rejection', 'alert alert-danger');
                redirect('client/reviewEquipmentRequest/' . $id);
                return;
            }

            // Prepare data
            $data = [
                'id' => $id,
                'client_notes' => trim($_POST['client_notes']),
                'client_id' => $_SESSION['user_id']
            ];

            // Reject
            if ($this->clientModel->rejectEquipmentRequest($data)) {
                flash('equipment_message', 'Equipment request rejected', 'alert alert-info');
            } else {
                flash('equipment_error', 'Failed to reject request', 'alert alert-danger');
            }

            redirect('client/equipmentRequests');
        } else {
            redirect('client/equipmentRequests');
        }
    }
}
