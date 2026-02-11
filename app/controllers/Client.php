<?php
class Client extends Controller {
    private $clientModel;
    private $userModel;
    private $notificationModel;
    private $messageModel;

    public function __construct() {
        // Check if user is logged in and has client role
        requireAuth('client');
        $this->clientModel = $this->model('M_client');
        $this->userModel = $this->model('M_users');
        $this->notificationModel = $this->model('M_notifications');
        $this->messageModel = $this->model('M_message');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('client/dashboard');
    }

    // dashboard
    public function dashboard() {
        $client_id = $_SESSION['user_id'];
        
        // Load chart data model
        $chartModel = $this->model('ChartDataModel');
        
        // Get all chart data
        $charts = [
            'severityChart' => $chartModel->getClientIncidentsBySeverity($client_id),
            'monthlyTrend' => $chartModel->getClientMonthlyIncidentTrend($client_id),
            'siteIncidents' => $chartModel->getClientIncidentsBySite($client_id),
            'typeChart' => $chartModel->getClientIncidentsByType($client_id),
            'statusChart' => $chartModel->getClientIncidentsByStatus($client_id)
        ];
        
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'charts' => $charts
        ];
        
        $this->view('client/v_dashboard', $data);
    }

    // View all sites for the client
    public function sites() {
        $client_id = $_SESSION['user_id'];
        
        // Get all sites for this client
        $sites = $this->clientModel->getClientSites($client_id);
        
        $data = [
            'title' => 'Sites',
            'pageTitle' => 'My Sites',
            'sites' => $sites
        ];
        
        $this->view('client/sites/v_sites', $data);
    }

    // Get sites for AJAX request
    public function getSites() {
        header('Content-Type: application/json');
        $client_id = $_SESSION['user_id'];
        
        // Get all sites for this client
        $sites = $this->clientModel->getClientSites($client_id);
        
        echo json_encode(['sites' => $sites]);
    }

    // View individual site details
    public function viewSite($site_id = null) {
        if (!$site_id) {
            redirect('client/sites');
        }

        $client_id = $_SESSION['user_id'];
        
        // Get site details
        $site = $this->clientModel->getSiteDetails($site_id, $client_id);
        
        if (!$site) {
            flash('site_error', 'Site not found or access denied', 'alert alert-danger');
            redirect('client/sites');
        }

        // Get assigned officers for this site
        $assignedOfficers = $this->clientModel->getSiteOfficers($site_id);
        
        // Get assigned supervisors for this site
        $assignedSupervisors = $this->clientModel->getSiteSupervisors($site_id);
        
        // Get assigned caretakers for this site
        $assignedCaretakers = $this->clientModel->getSiteCaretakers($site_id);

        $data = [
            'title' => 'Site Details',
            'pageTitle' => $site->site_name,
            'site' => $site,
            'assigned_officers' => $assignedOfficers,
            'assigned_supervisors' => $assignedSupervisors,
            'assigned_caretakers' => $assignedCaretakers
        ];
        
        $this->view('client/sites/v_site_details', $data);
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
        $user_id = $_SESSION['user_id'];
        
        // Get conversations
        $conversations = $this->messageModel->getConversations($user_id);
        
        // Get all users the client can message (admins + supervisors of client's sites)
        $users = $this->messageModel->getAllUsersForClient($user_id);
        
        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages',
            'conversations' => $conversations,
            'all_users' => $users,
            'current_recipient_id' => $conversationId
        ];
        
        $this->view('client/messages/v_messages', $data);
    }

    // ==================== MESSAGING API METHODS ====================

    public function getConversations() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            
            if (!$user_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            $conversations = $this->messageModel->getConversations($user_id);
            echo json_encode(['status' => 'success', 'conversations' => $conversations]);
        }
    }

    public function loadMessages() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $sender_id = $_SESSION['user_id'] ?? null;
            $recipient_id = $_POST['recipient_id'] ?? null;
            
            if (!$sender_id || !$recipient_id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid user']);
                return;
            }
            
            // Mark messages as read
            $this->messageModel->markAsRead($recipient_id, $sender_id);
            
            // Get messages
            $messages = $this->messageModel->getMessages($sender_id, $recipient_id);
            echo json_encode(['status' => 'success', 'messages' => $messages]);
        }
    }

    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $sender_id = $_SESSION['user_id'] ?? null;
            $recipient_id = $_POST['recipient_id'] ?? null;
            $message = trim($_POST['message'] ?? '');
            
            if (!$sender_id || !$recipient_id || empty($message)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
                return;
            }
            
            // Sanitize message
            $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
            
            if ($this->messageModel->sendMessage($sender_id, $recipient_id, $message)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => $message,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
            }
        }
    }

    public function markAsSeen() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $sender_id = $_SESSION['user_id'] ?? null;
            $recipient_id = $_POST['recipient_id'] ?? null;
            
            if (!$sender_id || !$recipient_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            // Mark messages as seen
            $this->messageModel->markAsSeen($sender_id, $recipient_id);
            
            echo json_encode(['status' => 'success']);
        }
    }

    public function deleteMessage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            $message_id = $_POST['message_id'] ?? null;
            
            if (!$user_id || !$message_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            if ($this->messageModel->deleteMessage($message_id, $user_id)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function updateMessage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            $message_id = $_POST['message_id'] ?? null;
            $message = $_POST['message'] ?? null;
            
            if (!$user_id || !$message_id || !$message) {
                echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
                return;
            }
            
            if ($this->messageModel->updateMessage($message_id, $user_id, $message)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update message']);
            }
        }
    }

    public function getUserStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_POST['user_id'] ?? null;
            
            if (!$user_id) {
                echo json_encode(['status' => 'error', 'message' => 'User ID required']);
                return;
            }
            
            $userStatus = $this->userModel->getUserOnlineStatus($user_id);
            
            if ($userStatus) {
                echo json_encode([
                    'status' => 'success',
                    'is_online' => $userStatus->is_online ?? false,
                    'last_seen' => $userStatus->last_seen ?? null
                ]);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'is_online' => false,
                    'last_seen' => null
                ]);
            }
        }
    }

    public function updateLastSeen() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            
            if ($user_id) {
                $this->userModel->updateLastSeen($user_id);
            }
        }
    }

    public function setOffline() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            
            if ($user_id) {
                $this->userModel->setUserOffline($user_id);
            }
        }
    }

    // ==================== END MESSAGING API METHODS ====================

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

    // Package Pages
    public function basicPackage() {
        $data = [
            'title' => 'Basic Package',
            'pageTitle' => 'Basic Package'
        ];
        $this->view('client/requests/v_basic', $data);
    }

    public function budgetPackage() {
        $data = [
            'title' => 'Budget Package',
            'pageTitle' => 'Budget Package'
        ];
        $this->view('client/requests/v_budget', $data);
    }

    public function vigilantPackage() {
        $data = [
            'title' => 'Vigilant Package',
            'pageTitle' => 'Vigilant Package'
        ];
        $this->view('client/requests/v_vigilant', $data);
    }

    public function proPackage() {
        $data = [
            'title' => 'Pro Package',
            'pageTitle' => 'Pro Package'
        ];
        $this->view('client/requests/v_pro', $data);
    }

    public function ultraPackage() {
        $data = [
            'title' => 'Ultra Package',
            'pageTitle' => 'Ultra Package'
        ];
        $this->view('client/requests/v_ultra', $data);
    }

    public function customPackage() {
        $data = [
            'title' => 'Custom Package',
            'pageTitle' => 'Custom Package'
        ];
        $this->view('client/requests/v_custom', $data);
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
        $this->view('client/requests/v_package_history', $data);
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
