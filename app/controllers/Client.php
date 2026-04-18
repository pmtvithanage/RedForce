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

    /**
     * Policy: minimum 1 supervisor per 5 officers (rounded up), and 0 when no officers.
     */
    private function calculateRequiredSupervisors($officerCount) {
        $officers = max(0, (int)$officerCount);
        if ($officers === 0) {
            return 0;
        }
        return (int)ceil($officers / 5);
    }

    /**
     * Enforce supervisor policy for both new-site and existing-site requests.
     */
    private function applySupervisorPolicy(array $requestData, $mode) {
        $officers = (int)($requestData['number_of_officers'] ?? 0);
        $supervisors = (int)($requestData['number_of_supervisors'] ?? 0);

        if ($mode === 'existing' && !empty($requestData['site_id'])) {
            $current = $this->clientModel->getActiveSitePersonnelCounts((int)$requestData['site_id'], (int)$requestData['client_id']);
            if (!$current) {
                throw new Exception('Selected site was not found for this client.');
            }
            $currentOfficers = (int)($current->number_of_officers ?? 0);
            $currentSupervisors = (int)($current->number_of_supervisors ?? 0);

            $targetOfficers = max(0, $currentOfficers + $officers);
            $requiredTargetSupervisors = $this->calculateRequiredSupervisors($targetOfficers);
            $requiredSupervisorChange = $requiredTargetSupervisors - $currentSupervisors;

            // Auto-adjust to satisfy policy if the submitted change is insufficient.
            if (($currentSupervisors + $supervisors) < $requiredTargetSupervisors) {
                $supervisors = $requiredSupervisorChange;
            }

            $requestData['number_of_supervisors'] = $supervisors;
            return $requestData;
        }

        // New site: requested personnel are the target totals.
        $requiredSupervisors = $this->calculateRequiredSupervisors($officers);
        if ($supervisors < $requiredSupervisors) {
            $supervisors = $requiredSupervisors;
        }
        $requestData['number_of_supervisors'] = $supervisors;

        return $requestData;
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
        
        // Get all chart data for 2x2 grid
        $charts = [
            'sitesOfficers' => $chartModel->getClientSitesWithOfficers($client_id),
            'incidentStatus' => $chartModel->getClientIncidentStatusPie($client_id),
            'paymentHistory' => $chartModel->getClientPaymentHistory($client_id),
            'nextPaymentBySite' => $chartModel->getClientNextPaymentBySite($client_id)
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

        // Get client's existing ratings for officers in this site
        $clientOfficerRatings = $this->clientModel->getClientSiteOfficerRatings($client_id, $site_id);

        $ratingSuccess = $_SESSION['site_rating_success'] ?? '';
        $ratingError = $_SESSION['site_rating_error'] ?? '';
        unset($_SESSION['site_rating_success'], $_SESSION['site_rating_error']);

        $data = [
            'title' => 'Site Details',
            'pageTitle' => $site->site_name,
            'site' => $site,
            'assigned_officers' => $assignedOfficers,
            'assigned_supervisors' => $assignedSupervisors,
            'assigned_caretakers' => $assignedCaretakers,
            'client_officer_ratings' => $clientOfficerRatings,
            'rating_success' => $ratingSuccess,
            'rating_error' => $ratingError
        ];
        
        $this->view('client/sites/v_site_details', $data);
    }

    public function saveOfficerRating() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/sites');
            return;
        }

        $clientId = $_SESSION['user_id'] ?? null;
        $siteId = (int)($_POST['site_id'] ?? 0);
        $officerUserId = (int)($_POST['officer_user_id'] ?? 0);
        $ratingValue = (int)($_POST['rating_value'] ?? 0);
        $ratingDate = trim((string)($_POST['rating_date'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        if (!$clientId || $siteId <= 0 || $officerUserId <= 0) {
            $_SESSION['site_rating_error'] = 'Invalid request for officer rating.';
            redirect('client/sites');
            return;
        }

        $redirectPath = 'client/viewSite/' . $siteId;

        $dateObj = DateTime::createFromFormat('Y-m-d', $ratingDate);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $ratingDate) {
            $_SESSION['site_rating_error'] = 'Invalid rating date.';
            redirect($redirectPath);
            return;
        }

        if ($ratingValue < 1 || $ratingValue > 5) {
            $_SESSION['site_rating_error'] = 'Rating must be between 1 and 5.';
            redirect($redirectPath);
            return;
        }

        if (!$this->clientModel->canClientRateOfficerInSite($clientId, $siteId, $officerUserId, $ratingDate)) {
            $_SESSION['site_rating_error'] = 'You can only rate premise officers assigned to your own site on that date.';
            redirect($redirectPath);
            return;
        }

        if ($this->clientModel->saveClientOfficerRating($clientId, $siteId, $officerUserId, $ratingDate, $ratingValue, $description)) {
            $_SESSION['site_rating_success'] = 'Officer rating saved successfully.';
        } else {
            $_SESSION['site_rating_error'] = 'Failed to save officer rating.';
        }

        redirect($redirectPath);
    }

    public function deleteOfficerRating() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/sites');
            return;
        }

        $clientId = $_SESSION['user_id'] ?? null;
        $siteId = (int)($_POST['site_id'] ?? 0);
        $officerUserId = (int)($_POST['officer_user_id'] ?? 0);
        $ratingDate = trim((string)($_POST['rating_date'] ?? ''));

        if (!$clientId || $siteId <= 0 || $officerUserId <= 0) {
            $_SESSION['site_rating_error'] = 'Invalid delete request for officer rating.';
            redirect('client/sites');
            return;
        }

        $redirectPath = 'client/viewSite/' . $siteId;

        $dateObj = DateTime::createFromFormat('Y-m-d', $ratingDate);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $ratingDate) {
            $_SESSION['site_rating_error'] = 'Invalid rating date.';
            redirect($redirectPath);
            return;
        }

        if ($this->clientModel->deleteClientOfficerRating($clientId, $siteId, $officerUserId, $ratingDate)) {
            $_SESSION['site_rating_success'] = 'Officer rating deleted successfully.';
        } else {
            $_SESSION['site_rating_error'] = 'Failed to delete officer rating.';
        }

        redirect($redirectPath);
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
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $mode = $_POST['mode'] ?? 'new';
                
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
                    'city' => trim($_POST['city'] ?? ''),
                    'site_address' => trim($_POST['site_address'] ?? ''),
                    'latitude' => $_POST['latitude'] ?? null,
                    'longitude' => $_POST['longitude'] ?? null,
                    'phone_number' => trim($_POST['phone_number'] ?? ''),
                    'image_name' => $imageName,
                    'start_date' => date('Y-m-d', strtotime('first day of next month')),
                    'end_date' => date('Y-m-d', strtotime('last day of next month')),
                    'number_of_officers' => (int)($_POST['number_of_officers'] ?? 0),
                    'number_of_supervisors' => (int)($_POST['number_of_supervisors'] ?? 0),
                    'number_of_caretakers' => (int)($_POST['number_of_caretakers'] ?? 0),
                    'package_price' => (float)($_POST['package_price'] ?? 0),
                    'site_id' => ($mode === 'existing') ? (int)$_POST['site_id'] : null,
                    'comments' => ($mode === 'new') ? 'Newly added site' : null,
                    'status' => 'Pending'
                ];

                // Enforce supervisor-per-officer policy before saving the request.
                $requestData = $this->applySupervisorPolicy($requestData, $mode);

                // Always calculate server-side price from package unit rates after policy adjustments.
                $packageModel = $this->model('M_package');
                $pricing = $packageModel->getPackagePricingByName($requestData['package_name']);
                $requestData['package_price'] = round(
                    ((int)$requestData['number_of_officers'] * (float)$pricing['price_per_officer']) +
                    ((int)$requestData['number_of_supervisors'] * (float)$pricing['price_per_supervisor']) +
                    ((int)$requestData['number_of_caretakers'] * (float)$pricing['price_per_caretaker']),
                    2
                );

                // If this is for an existing site, delete any existing pending requests for that site first
                if ($mode === 'existing' && isset($_POST['site_name'])) {
                    $siteName = trim($_POST['site_name']);
                    $clientId = $_SESSION['user_id'];
                    // Delete existing pending requests for this site by site name
                    $this->clientModel->deletePendingRequestsForSite($siteName, $clientId);
                }

                if ($this->clientModel->createPackageRequest($requestData)) {
                    echo json_encode(['success' => true, 'message' => 'Package request submitted successfully!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to submit request. Please try again.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
        exit;
    }

    public function packageHistory() {
        $requests = $this->clientModel->getClientPackageRequests($_SESSION['user_id']);
        $data = ['title' => 'Request History', 'pageTitle' => 'Package Request History', 'requests' => $requests];
        $this->view('client/requests/v_package_history', $data);
    }

    public function deletePackageRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $client_id = $_SESSION['user_id'];
            $success = $this->clientModel->deletePackageRequest($id, $client_id);
            
            // Check if this is an AJAX request (expects JSON response)
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            $expectsJson = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
            
            if ($isAjax || $expectsJson) {
                header('Content-Type: application/json');
                if ($success) {
                    echo json_encode(['success' => true, 'message' => 'Package request deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Cannot delete this request. It has already been paid for or approved.']);
                }
                exit;
            }
            
            // Regular form submission - use flash messages and redirect
            if ($success) {
                flash('package_success', 'Request deleted successfully', 'alert-success');
            } else {
                flash('package_error', 'Failed to delete request', 'alert-danger');
            }
        }
        redirect('client/packageHistory');
    }

    public function getPendingRequestsForSite($siteName) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $client_id = $_SESSION['user_id'];
        
        // Get pending requests for this specific site by site name
        $requests = $this->clientModel->getPendingRequestsForSite($siteName, $client_id);
        
        echo json_encode([
            'success' => true,
            'requests' => $requests
        ]);
        exit;
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

    // View payments history
    public function payments() {
        // Prevent caching to ensure fresh data
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // Handle error parameters from payment redirects
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 'payment_failed':
                    flash('payment_error', 'Payment was unsuccessful. Please try again.');
                    break;
                case 'payment_init_failed':
                    flash('payment_error', 'Failed to initiate payment. Please try again.');
                    break;
            }
            // Redirect to remove query parameter
            redirect('client/payments');
        }
        
        $client_id = $_SESSION['user_id'];
        
        // Load payment model
        $paymentModel = $this->model('M_payment');
        
        // Load client model for active sites and pending requests
        $clientModel = $this->model('M_client');
        
        // Get all payments for this client
        $payments = $paymentModel->getClientPayments($client_id);
        
        // Get active sites with package information
        $activeSites = $clientModel->getActiveSitesWithPackages($client_id);
        
        // Get pending package requests
        $pendingRequests = $clientModel->getPendingPackageRequests($client_id);
        
        // Get comprehensive payment statistics from database
        $paymentStats = $paymentModel->getPaymentStats($client_id);
        
        $data = [
            'title' => 'Payments',
            'pageTitle' => 'Payment History',
            'payments' => $payments,
            'active_sites' => $activeSites,
            'pending_requests' => $pendingRequests,
            'total_paid' => $paymentStats->total_paid ?? 0,
            'paid_count' => $paymentStats->paid_count ?? 0,
            'pending_count' => $paymentStats->pending_count ?? 0,
            'overdue_count' => $paymentStats->overdue_count ?? 0,
            'pending_amount' => $paymentStats->pending_amount ?? 0,
            'overdue_amount' => $paymentStats->overdue_amount ?? 0,
            'total_payments' => $paymentStats->total_payments ?? 0
        ];
        
        $this->view('client/history/v_payments', $data);
    }

    // View individual payment details
    public function viewPayment($payment_id = null) {
        if (!$payment_id) {
            redirect('client/payments');
        }

        $client_id = $_SESSION['user_id'];
        $paymentModel = $this->model('M_payment');
        
        // Get payment details
        $payment = $paymentModel->getPaymentDetails($payment_id, $client_id);
        
        if (!$payment) {
            flash('payment_error', 'Payment not found or access denied', 'alert alert-danger');
            redirect('client/payments');
        }

        $data = [
            'title' => 'Payment Details',
            'pageTitle' => 'Payment Details',
            'payment' => $payment
        ];
        
        $this->view('client/history/v_payment_details', $data);
    }

    // Download receipt
    public function downloadReceipt($payment_id = null) {
        if (!$payment_id) {
            redirect('client/payments');
        }

        $client_id = $_SESSION['user_id'];
        $paymentModel = $this->model('M_payment');
        
        // Get payment details
        $payment = $paymentModel->getPaymentDetails($payment_id, $client_id);
        
        if (!$payment || $payment->status !== 'paid') {
            flash('payment_error', 'Receipt not available', 'alert alert-danger');
            redirect('client/payments');
        }

        // Generate and download receipt (PDF)
        // This is a placeholder - implement actual PDF generation
        flash('payment_success', 'Receipt download initiated', 'alert alert-success');
        redirect('client/payments');
    }

  // ======================================================================== //
// =======================      profile       ====================== //
// ======================================================================== //
    public function profile() {
        $data = [
            'title' => 'Profile',
            'pageTitle' => 'My Profile',
            'client' => $this->clientModel->getclientById($_SESSION['user_userID'])
        ];
        $this->view('client/profile/v_profile', $data); 
    }


     public function editProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $client = $this->clientModel->getclientById($_SESSION['user_userID']);
            
            $data = [
                'title' => 'Profile',
                'pageTitle' => 'Edit Profile',
                'client' => $client,
                'name' => $this->sanitizeInput($_POST['name'] ?? ''),
                'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
                'current_password' => $_POST['current_password'] ?? '',
                'new_password' => $_POST['new_password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => '',
                'image_err' => ''
            ];

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email address';
            }

            // Validate phone number
            if (empty($data['phone_number'])) {
                $data['phone_number_err'] = 'Please enter your phone number';
            } elseif (!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])) {
                $data['phone_number_err'] = 'Please enter a valid phone number (10-15 digits)';
            }

            // Handle password change if fields are filled
            if (!empty($data['new_password']) || !empty($data['confirm_password'])) {
                // All password fields must be filled if updating password
                if (empty($data['current_password'])) {
                    $data['current_password_err'] = 'Please enter your current password';
                    flash('msg', 'Please enter your current password', 'alert-danger');
                } elseif (!password_verify($data['current_password'], $client->password)) {
                    $data['current_password_err'] = 'Current password is incorrect';
                    flash('msg', 'Current password is incorrect', 'alert-danger');
                }

                if (empty($data['new_password'])) {
                    $data['new_password_err'] = 'Please enter a new password';
                    flash('msg', 'Please enter a new password', 'alert-danger');
                } elseif (strlen($data['new_password']) < 6) {
                    $data['new_password_err'] = 'Password must be at least 6 characters';
                    flash('msg', 'Password must be at least 6 characters', 'alert-danger');
                }

                if (empty($data['confirm_password'])) {
                    $data['confirm_password_err'] = 'Please confirm your password';
                    flash('msg', 'Please confirm your password', 'alert-danger');
                } elseif ($data['new_password'] !== $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                    flash('msg', 'Passwords do not match', 'alert-danger');
                }
            }

            // Handle profile image upload
            $profileImageName = $client->profile_image;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
                $file = $_FILES['profile_image'];
                $allowed = ['image/jpeg', 'image/jpg', 'image/png'];
                
                if (!in_array($file['type'], $allowed)) {
                    $data['image_err'] = 'Only JPEG and PNG images are allowed';
                } elseif ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
                    $data['image_err'] = 'Image size must be less than 5MB';
                } else {
                    // Generate unique filename
                    $profileImageName = uniqid() . '_' . basename($file['name']);
                    $uploadPath = PUB_ROOT . '/uploads/applicantPhotos/' . $profileImageName;
                    
                    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        $data['image_err'] = 'Failed to upload image';
                        $profileImageName = $client->profile_image; // Revert to old image
                    } else {
                        // Delete old image if it exists
                        $oldImagePath = PUB_ROOT . '/uploads/applicantPhotos/' . $client->profile_image;
                        if (file_exists($oldImagePath) && $client->profile_image !== 'default.png') {
                            unlink($oldImagePath);
                        }
                    }
                }
            }

            // If no errors, update profile
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && 
                empty($data['current_password_err']) && empty($data['new_password_err']) && 
                empty($data['confirm_password_err']) && empty($data['image_err'])) {
                
                $updateData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone_number' => $data['phone_number'],
                    'profile_image' => $profileImageName
                ];

                // Add password to update if it's being changed
                if (!empty($data['new_password'])) {
                    $updateData['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                    
                }

                if ($this->clientModel->updateClientProfile($_SESSION['user_id'], $updateData)) {
                    flash('msg', 'Profile updated successfully', 'alert-success');
                    redirect('Client/profile');
                } else {
                    flash('msg', 'Failed to update profile', 'alert-danger');
                    $data['client'] = $this->clientModel->getClientById($_SESSION['user_userID']);
                    $this->view('client/profile/v_editProfile', $data);
                }
            } else {
                $data['client'] = $this->clientModel->getClientById($_SESSION['user_userID']);
                flash('msg', 'Please fix the errors in the form', 'alert-danger');
                $this->view('client/profile/v_editProfile', $data);
            }
        } else {
            $client = $this->clientModel->getClientById($_SESSION['user_userID']);
            $data = [
                'title' => 'Profile',
                'pageTitle' => 'Edit Profile',
                'client' => $client,
                'name' => $client->name ?? '',
                'email' => $client->email ?? '',
                'phone_number' => $client->phone_number ?? '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => '',
                'image_err' => ''
            ];
            $this->view('client/profile/v_editProfile', $data);
        }
    }

    // ---------------------------------------For all--------------------------------------//

    /**
     * Sanitize input data
     * Replacement for FILTER_SANITIZE_STRING
     */
    private function sanitizeInput($input) {
        $input = trim($input ?? '');
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Remove or encode potentially dangerous characters
        $input = strip_tags($input);
        return $input;
    }
}
