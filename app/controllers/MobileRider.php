<?php
class MobileRider extends Controller
{
    private $mobileRiderModel;
    private $userModel;
    private $advertisementModel;
    private $messageModel;
    private $notificationModel;

    public function __construct()
    {
        // Check if user is logged in and has mobile rider role
        requireAuth('mobile rider');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->mobileRiderModel = $this->model('M_mobilerider');
        $this->userModel = $this->model('M_users');
        $this->messageModel = $this->model('M_message');
        $this->notificationModel = $this->model('M_notifications');
    }

    // Default action - redirect to dashboard
    public function index()
    {
        redirect('MobileRider/dashboard');
    }

    public function notifications() {
        // TODO: Fetch notifications from database
        $notifications = $this->notificationModel->getNotifications($_SESSION['user_id']);
        
        $data = [
            'title' => 'Notifications',
            'pageTitle' => 'Notifications',
            'role' => 'mobile rider',
            'notifications' => $notifications
        ];
        $this->view('components/notifications', $data);
    }

    // Dashboard action
    public function dashboard()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $role = 'mobile rider';
        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);

        $notes = $this->getNotes();
        
        // Get recent activities for current user
        $recentActivities = [];
        $stats = [
            'total_sites' => 0,
            'completed_visits' => 0,
            'incidents' => 0,
            'avg_response_time' => 'N/A'
        ];
        
        if ($userId) {
            $recentActivities = $this->mobileRiderModel->getRecentActivities($userId, 50);
            
            // Get route assigned to this mobile rider
            $route = $this->mobileRiderModel->getRouteByUserId($userId);
            
            if ($route) {
                // Get total sites in route
                $routeSites = $this->mobileRiderModel->getRouteSites($route->id);
                $stats['total_sites'] = count($routeSites);
                
                // Get site visit stats
                $siteStats = $this->mobileRiderModel->getSiteVisitStats($route->id);
                $stats['completed_visits'] = $siteStats->sites_visited ?? 0;
                
                // Calculate average response time for incidents
                $stats['avg_response_time'] = $this->mobileRiderModel->getAverageResponseTime($userId);
            }
            
            // Get incidents count for this user
            $incidents = $this->mobileRiderModel->getIncidentsByUserId($userId);
            $stats['incidents'] = count($incidents);
        }

        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'advertisements' => $advertisements,
            'notes' => $notes,
            'recent_activities' => $recentActivities,
            'stats' => $stats
        ];
        $this->view('mobilerider/dashboard/v_dashboard', $data);
    }

    // Schedule action
    public function sites()
    {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            flash('msg', 'Session expired. Please login again.', 'alert-danger');
            redirect('users/login');
            return;
        }

        // Get mobile rider record using the view
        $mobileRider = $this->mobileRiderModel->getMobileRiderByUserId($userId);
        
        if (!$mobileRider) {
            // Mobile rider record doesn't exist yet - show message
            $data = [
                'title' => 'Sites',
                'pageTitle' => 'Assigned Sites',
                'route' => null,
                'routeSites' => [],
                'total_sites' => 0,
                'sites_visited' => 0,
                'error_message' => 'Your mobile rider profile has not been set up yet. Please contact the administrator.'
            ];
            $this->view('mobilerider/sites/v_sites', $data);
            return;
        }

        // Get route assigned to this user (routes.assigned_rider_id = user_id)
        $route = $this->mobileRiderModel->getRouteByUserId($userId);
        
        // Get sites assigned to the route
        $routeSites = [];
        $siteStats = ['total_sites' => 0, 'sites_visited' => 0];
        
        if ($route) {
            $routeSites = $this->mobileRiderModel->getRouteSites($route->id);
            
            // Check which sites have been visited today
            foreach ($routeSites as $site) {
                $site->visited_today = $this->mobileRiderModel->isSiteVisitedToday($site->id, $userId);
            }
            
            $siteStats = $this->mobileRiderModel->getSiteVisitStats($route->id);
        }

        $data = [
            'title' => 'Sites',
            'pageTitle' => 'Assigned Sites',
            'route' => $route,
            'routeSites' => $routeSites,
            'total_sites' => $siteStats->total_sites ?? 0,
            'sites_visited' => $siteStats->sites_visited ?? 0
        ];
        $this->view('mobilerider/sites/v_sites', $data);
    }

    // Mark site as visited (AJAX endpoint)
    public function markSiteVisited()
    {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User not authenticated']);
            return;
        }

        // Get data from POST request
        $input = json_decode(file_get_contents('php://input'), true);
        $siteId = $input['site_id'] ?? null;
        $officerAttendanceSatisfactory = $input['officer_attendance_satisfactory'] ?? 0;
        $officerActivities = $input['officer_activities'] ?? null;
        $siteCondition = $input['site_condition'] ?? 'Good';
        $issuesFound = $input['issues_found'] ?? null;
        $notes = $input['notes'] ?? null;

        // Validate required fields
        if (!$siteId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Site ID is required']);
            return;
        }

        if (empty($officerActivities)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Officer activities description is required']);
            return;
        }

        // Prepare visit data
        $visitData = [
            'site_id' => $siteId,
            'user_id' => $userId,
            'officer_attendance_satisfactory' => $officerAttendanceSatisfactory,
            'officer_activities' => $officerActivities,
            'site_condition' => $siteCondition,
            'issues_found' => $issuesFound,
            'notes' => $notes
        ];

        // Mark site as visited
        $result = $this->mobileRiderModel->markSiteAsVisited($visitData);
        
        // If successful, log the activity
        if ($result['success']) {
            // Get site details for activity log
            $routeSites = $this->mobileRiderModel->getRouteSites($this->mobileRiderModel->getRouteByUserId($userId)->id ?? 0);
            $siteName = 'Site';
            foreach ($routeSites as $site) {
                if ($site->id == $siteId) {
                    $siteName = $site->site_name;
                    break;
                }
            }
            
            // Log activity
            $title = "Site Visit Completed";
            $description = "Marked " . $siteName . " as visited - Condition: " . $siteCondition;
            $type = "visit";
            $this->mobileRiderModel->insertRecentActivity($title, $description, $type, $userId);
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    // Messages
    public function messages()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            redirect('mobilerider/dashboard');
            return;
        }
        
        $conversations = $this->messageModel->getConversations($user_id);
        // Use specialized method for mobile riders - only admins and supervisors of route sites
        $all_users = $this->messageModel->getAllUsersForMobileRider($user_id);
        $unread_count = $this->messageModel->getUnreadCount($user_id);
        
        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages',
            'conversations' => $conversations,
            'all_users' => $all_users,
            'unread_count' => $unread_count,
            'current_recipient_id' => isset($_GET['with']) ? $_GET['with'] : null
        ];
        
        $this->view('mobilerider/messages/v_messages', $data);
    }

    // Load messages with a specific user (AJAX)
    public function loadMessages()
    {
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

    // Mark messages as seen (AJAX)
    public function markAsSeen()
    {
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

    // Send a message (AJAX)
    public function sendMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            error_log("MobileRider sendMessage called");
            
            $sender_id = $_SESSION['user_id'] ?? null;
            $recipient_id = $_POST['recipient_id'] ?? null;
            $message = trim($_POST['message'] ?? '');
            
            error_log("Sender: $sender_id, Recipient: $recipient_id, Message: $message");
            
            if (!$sender_id || !$recipient_id || empty($message)) {
                error_log("Validation failed - missing data");
                echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
                return;
            }
            
            // Sanitize message
            $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
            
            error_log("Attempting to send message via model");
            
            try {
                $result = $this->messageModel->sendMessage($sender_id, $recipient_id, $message);
                error_log("Model sendMessage result: " . ($result ? 'true' : 'false'));
                
                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => $message,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    error_log("sendMessage returned false");
                    echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
                }
            } catch (Exception $e) {
                error_log("Exception in sendMessage: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
            }
        }
    }

    // Get all available users (AJAX)
    public function getAllUsers()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            
            if (!$user_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            // Use specialized method for mobile riders
            $users = $this->messageModel->getAllUsersForMobileRider($user_id);
            echo json_encode(['status' => 'success', 'users' => $users]);
        }
    }

    // Get all conversations (AJAX)
    public function getConversations()
    {
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

    // Search conversations (AJAX)
    public function searchMessages()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            $search_term = trim($_POST['search'] ?? '');
            
            if (!$user_id || empty($search_term)) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            $results = $this->messageModel->searchConversations($user_id, $search_term);
            echo json_encode(['status' => 'success', 'results' => $results]);
        }
    }

    // Delete a message (AJAX)
    public function deleteMessage()
    {
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

    // Get user online status (AJAX)
    public function getUserStatus()
    {
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

    // Update a message (AJAX)
    public function updateMessage()
    {
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

    // Update user last seen (AJAX)
    public function updateLastSeen()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            
            if ($user_id) {
                $this->userModel->updateLastSeen($user_id);
            }
        }
    }

    // Set user offline (AJAX)
    public function setOffline()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            
            if ($user_id) {
                $this->userModel->setUserOffline($user_id);
            }
        }
    }

    // Incidents
    public function incidents()
    {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            flash('msg', 'Session expired. Please login again.', 'alert-danger');
            redirect('users/login');
            return;
        }
        
        // Get incidents for this rider
        $incidents = $this->mobileRiderModel->getIncidentsByUserId($userId);
        
        // Calculate statistics
        $stats = [
            'total_incidents' => count($incidents),
            'pending_incidents' => 0,
            'inprogress_incidents' => 0,
            'resolved_incidents' => 0
        ];
        
        foreach ($incidents as $incident) {
            $status = strtolower($incident->status ?? 'pending');
            if ($status === 'pending') {
                $stats['pending_incidents']++;
            } elseif ($status === 'in progress') {
                $stats['inprogress_incidents']++;
            } elseif ($status === 'resolved' || $status === 'closed') {
                $stats['resolved_incidents']++;
            }
        }
        
        $data = array_merge([
            'title' => 'Incidents',
            'pageTitle' => 'Incident Reports',
            'incidents' => $incidents
        ], $stats);
        
        $this->view('mobilerider/incidents/v_incidents', $data);
    }

    public function createIncident()
    {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            flash('msg', 'Session expired. Please login again.', 'alert-danger');
            redirect('users/login');
            return;
        }
        
        // Get route and sites for this rider
        $route = $this->mobileRiderModel->getRouteByUserId($userId);
        $sites = [];
        
        if ($route) {
            $sites = $this->mobileRiderModel->getRouteSites($route->id);
        }
        
        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize input
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // Initialize data array
            $data = [
                'title' => 'Incidents',
                'pageTitle' => 'Report New Incident',
                'sites' => $sites,
                // Form values
                'incident_type' => trim($_POST['incident_type'] ?? ''),
                'site_id' => trim($_POST['site_id'] ?? ''),
                'incident_date' => trim($_POST['incident_date'] ?? ''),
                'incident_time' => trim($_POST['incident_time'] ?? ''),
                'priority' => trim($_POST['priority'] ?? 'Medium'),
                'description' => trim($_POST['description'] ?? ''),
                'actions_taken' => trim($_POST['actions_taken'] ?? ''),
                'people_involved' => trim($_POST['people_involved'] ?? ''),
                'latitude' => trim($_POST['latitude'] ?? ''),
                'longitude' => trim($_POST['longitude'] ?? ''),
                // Form errors
                'incident_type_err' => '',
                'site_id_err' => '',
                'incident_date_err' => '',
                'incident_time_err' => '',
                'priority_err' => '',
                'description_err' => '',
            ];
            
            // Validation
            if (empty($data['incident_type'])) {
                $data['incident_type_err'] = 'Please select an incident type';
            }
            
            if (empty($data['site_id'])) {
                $data['site_id_err'] = 'Please select a site';
            }
            
            if (empty($data['incident_date'])) {
                $data['incident_date_err'] = 'Please select incident date';
            }
            
            if (empty($data['incident_time'])) {
                $data['incident_time_err'] = 'Please select incident time';
            }
            
            if (empty($data['description'])) {
                $data['description_err'] = 'Please provide a description';
            }
            
            // Handle file uploads
            $uploadedFiles = [];
            if (!empty($_FILES['evidence_files']['name'][0])) {
                $uploadDir = 'uploads/evidence/';
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                
                foreach ($_FILES['evidence_files']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['evidence_files']['error'][$key] == 0) {
                        $fileType = $_FILES['evidence_files']['type'][$key];
                        $fileSize = $_FILES['evidence_files']['size'][$key];
                        
                        // Validate file type and size
                        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
                            $fileName = uniqid() . '_' . basename($_FILES['evidence_files']['name'][$key]);
                            $targetFile = $uploadDir . $fileName;
                            
                            if (move_uploaded_file($tmp_name, $targetFile)) {
                                $uploadedFiles[] = $fileName;
                            }
                        }
                    }
                }
            }
            
            // Check if there are no validation errors
            if (empty($data['incident_type_err']) && empty($data['site_id_err']) && 
                empty($data['incident_date_err']) && empty($data['incident_time_err']) && 
                empty($data['description_err'])) {
                
                // Get user details
                $user = $this->userModel->getUserById($userId);
                
                // Prepare incident data
                $incidentData = [
                    'user_id' => $userId,
                    'officer_name' => $user->name ?? 'Unknown',
                    'officer_role' => 'mobile rider',
                    'site_id' => $data['site_id'],
                    'property_site' => $data['site_id'], // For backward compatibility
                    'incident_type' => $data['incident_type'],
                    'incident_date' => $data['incident_date'],
                    'incident_time' => $data['incident_time'],
                    'incident_description' => $data['description'],
                    'action_taken' => $data['actions_taken'],
                    'severity' => $data['priority'], // Map priority to severity for backward compatibility
                    'priority' => $data['priority'],
                    'people_involved' => $data['people_involved'],
                    'additional_details' => null,
                    'media_files' => !empty($uploadedFiles) ? implode(',', $uploadedFiles) : null,
                    'latitude' => !empty($data['latitude']) ? $data['latitude'] : null,
                    'longitude' => !empty($data['longitude']) ? $data['longitude'] : null,
                    'status' => 'Pending'
                ];
                
                // Add incident to database
                if ($this->mobileRiderModel->addIncident($incidentData)) {
                    // Log activity
                    $this->mobileRiderModel->logActivity([
                        'user_id' => $userId,
                        'activity_type' => 'incident_report',
                        'activity_titel' => 'New Incident Reported',
                        'activity_details' => "Reported incident: {$data['incident_type']} at site ID {$data['site_id']}"
                    ]);
                    
                    flash('incident_message', 'Incident reported successfully!', 'alert alert-success');
                    redirect('MobileRider/incidents');
                } else {
                    flash('incident_message', 'Error submitting incident report. Please try again.', 'alert alert-danger');
                }
            }
            
            // Load view with errors
            $this->view('mobilerider/incidents/v_create_Incident', $data);
        } else {
            // GET request - show form
            $data = [
                'title' => 'Incidents',
                'pageTitle' => 'Report New Incident',
                'sites' => $sites,
                // Form error fields
                'incident_type_err' => '',
                'site_id_err' => '',
                'incident_date_err' => '',
                'incident_time_err' => '',
                'priority_err' => '',
                'description_err' => '',
                // Form values
                'incident_type' => '',
                'site_id' => '',
                'incident_date' => date('Y-m-d'),
                'incident_time' => date('H:i'),
                'priority' => 'Medium',
                'description' => '',
                'actions_taken' => '',
                'people_involved' => '',
                'latitude' => '',
                'longitude' => ''
            ];
            $this->view('mobilerider/incidents/v_create_Incident', $data);
        }
    }

    public function viewIncident($id = null)
    {
        // Get incident ID from parameter or query string
        $incidentId = $id ?? $_GET['id'] ?? null;
        
        if (!$incidentId) {
            flash('incident_message', 'Invalid incident ID.', 'alert alert-danger');
            redirect('MobileRider/incidents');
            return;
        }
        
        // Get incident details
        $incident = $this->mobileRiderModel->getIncidentById($incidentId);
        
        if (!$incident) {
            flash('incident_message', 'Incident not found.', 'alert alert-danger');
            redirect('MobileRider/incidents');
            return;
        }
        
        // Verify this incident is from a site in the mobile rider's route
        $userId = $_SESSION['user_id'] ?? null;
        $route = $this->mobileRiderModel->getRouteByUserId($userId);
        
        if ($route) {
            $routeSites = $this->mobileRiderModel->getRouteSites($route->id);
            $routeSiteIds = array_column($routeSites, 'id');
            
            if (!in_array($incident->site_id, $routeSiteIds)) {
                flash('incident_message', 'Unauthorized access.', 'alert alert-danger');
                redirect('MobileRider/incidents');
                return;
            }
        } else {
            // If no route assigned, only allow viewing own incidents
            if ($incident->user_id != $userId) {
                flash('incident_message', 'Unauthorized access.', 'alert alert-danger');
                redirect('MobileRider/incidents');
                return;
            }
        }
        
        // Get reviews for this incident
        $reviews = $this->mobileRiderModel->getIncidentReviews($incidentId);
        
        $data = [
            'title' => 'Incidents',
            'pageTitle' => 'Incident Details',
            'incident' => $incident,
            'reviews' => $reviews
        ];
        $this->view('mobilerider/incidents/v_view_incident', $data);
    }
    
    // Leave Requests
    public function leaverequests()
    {
        $mobilerider_id = $_SESSION['user_id'] ?? null;
        
        $leaveRequests = [];
        if ($mobilerider_id) {
            $leaveRequests = $this->mobileRiderModel->getLeaveRequests($mobilerider_id);
        }
        
        $data = [
            'title' => 'Leave Requests',
            'pageTitle' => 'Leave Requests',
            'leaveRequests' => $leaveRequests
        ];
        $this->view('mobilerider/v_leaverequests', $data);
    }

    // Add Leave Request
    public function addLeave() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $mobilerider_id = $_SESSION['user_id'] ?? null;
            
            if (!$mobilerider_id) {
                flash('leave_error', 'User not authenticated');
                redirect('mobilerider/leaverequests');
                return;
            }
            
            // Handle file upload
            $proof_file = null;
            if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] == 0) {
                $upload_dir = 'uploads/leaverequest/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['proof_file']['name'], PATHINFO_EXTENSION);
                $file_name = 'leave_' . $mobilerider_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['proof_file']['tmp_name'], $upload_path)) {
                    $proof_file = $upload_dir . $file_name;
                }
            }
            
            // Convert date format from DD/MM/YYYY to YYYY-MM-DD
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            
            if (strpos($start_date, '/') !== false) {
                $start_parts = explode('/', $start_date);
                if (count($start_parts) == 3) {
                    $start_date = $start_parts[2] . '-' . $start_parts[1] . '-' . $start_parts[0];
                }
            }
            
            if (strpos($end_date, '/') !== false) {
                $end_parts = explode('/', $end_date);
                if (count($end_parts) == 3) {
                    $end_date = $end_parts[2] . '-' . $end_parts[1] . '-' . $end_parts[0];
                }
            }
            
            $data = [
                'mobilerider_id' => $mobilerider_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            if (empty($data['leave_type']) || empty($data['reason']) || empty($data['start_date']) || empty($data['end_date'])) {
                flash('leave_error', 'Please fill all required fields');
                redirect('MobileRider/leaverequests');
                return;
            }
            
            if ($this->mobileRiderModel->addLeaveRequest($data)) {
                flash('leave_success', 'Leave request submitted successfully');
            } else {
                flash('leave_error', 'Something went wrong. Please try again');
            }
            
            redirect('MobileRider/leaverequests');
        } else {
            redirect('MobileRider/leaverequests');
        }
    }

    // Edit Leave Request
    public function editLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $mobilerider_id = $_SESSION['user_id'] ?? null;
            
            if (!$mobilerider_id) {
                flash('leave_error', 'User not authenticated');
                redirect('MobileRider/leaverequests');
                return;
            }
            
            $existingLeave = $this->mobileRiderModel->getLeaveRequestById($id);
            
            if (!$existingLeave || $existingLeave->mobilerider_id != $mobilerider_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('MobileRider/leaverequests');
                return;
            }
            
            // Handle file upload
            $proof_file = $existingLeave->proof_file;
            if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] == 0) {
                $upload_dir = 'uploads/leaverequest/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['proof_file']['name'], PATHINFO_EXTENSION);
                $file_name = 'leave_' . $mobilerider_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['proof_file']['tmp_name'], $upload_path)) {
                    if ($existingLeave->proof_file && file_exists($existingLeave->proof_file)) {
                        unlink($existingLeave->proof_file);
                    }
                    $proof_file = $upload_dir . $file_name;
                }
            }
            
            // Convert date format
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            
            if (strpos($start_date, '/') !== false) {
                $start_parts = explode('/', $start_date);
                if (count($start_parts) == 3) {
                    $start_date = $start_parts[2] . '-' . $start_parts[1] . '-' . $start_parts[0];
                }
            }
            
            if (strpos($end_date, '/') !== false) {
                $end_parts = explode('/', $end_date);
                if (count($end_parts) == 3) {
                    $end_date = $end_parts[2] . '-' . $end_parts[1] . '-' . $end_parts[0];
                }
            }
            
            $data = [
                'id' => $id,
                'mobilerider_id' => $mobilerider_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            if ($this->mobileRiderModel->updateLeaveRequest($data)) {
                flash('leave_success', 'Leave request updated successfully');
            } else {
                flash('leave_error', 'Failed to update leave request');
            }
            
            redirect('MobileRider/leaverequests');
        } else {
            redirect('MobileRider/leaverequests');
        }
    }

    // Delete Leave Request
    public function deleteLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mobilerider_id = $_SESSION['user_id'] ?? null;
            
            if (!$mobilerider_id) {
                flash('leave_error', 'User not authenticated');
                redirect('MobileRider/leaverequests');
                return;
            }
            
            $leave = $this->mobileRiderModel->getLeaveRequestById($id);
            
            if (!$leave || $leave->mobilerider_id != $mobilerider_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('MobileRider/leaverequests');
                return;
            }
            
            // Delete file if exists
            if ($leave->proof_file && file_exists($leave->proof_file)) {
                unlink($leave->proof_file);
            }
            
            if ($this->mobileRiderModel->deleteLeaveRequest($id, $mobilerider_id)) {
                flash('leave_success', 'Leave request deleted successfully');
            } else {
                flash('leave_error', 'Failed to delete leave request');
            }
            
            redirect('MobileRider/leaverequests');
        } else {
            redirect('MobileRider/leaverequests');
        }
    }
    
    // Profile
    public function profile()
    {
        $data = [
            'title' => 'Profile',
            'pageTitle' => 'My Profile'
        ];
        $this->view('mobilerider/v_profile', $data);
    }

    public function addNote()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $noteId = isset($_POST['noteId']) && !empty($_POST['noteId']) ? $_POST['noteId'] : null;

            // Check if it's an edit or new note
            if ($noteId) {
                // Edit existing note
                if ($this->mobileRiderModel->updateNoteById($noteId, $title, $content)) {
                    header("Location: " . URL_ROOT . "/MobileRider/dashboard?notes=open");
                    exit;
                } else {
                    echo 'Update failed';
                }
            } else {
                // Add new note
                $data = [
                    'title' => $title,
                    'content' => $content,
                    'userID' => $_SESSION['user_userID'],
                ];

                if ($this->mobileRiderModel->addNote($data)) {
                    header("Location: " . URL_ROOT . "/MobileRider/dashboard?notes=open");
                    exit;
                } else {
                    echo 'Add failed';
                }
            }
        }
    }

    public function getNotes()
    {
        $notes = $this->mobileRiderModel->getAllNotes();
        return $notes;
    }

    public function deleteNote()
    {
        $id = $_GET['id'];
        $this->mobileRiderModel->deleteNoteById($id);
        header("Location:" . URL_ROOT . "/MobileRider/dashboard?notes=open");
    }

    public function editNote()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $noteId = $_POST['noteId'];
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);

            if ($this->mobileRiderModel->updateNoteById($noteId, $title, $content)) {
                header("Location: " . URL_ROOT . "/MobileRider/dashboard?notes=open");
                exit;
            } else {
                echo 'Update failed';
            }
        }
    }

    public function addIncident()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Clean incoming data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $userId = $_SESSION['user_id'] ?? null;
            
            if (!$userId) {
                flash('msg', 'Session expired. Please login again.', 'alert-danger');
                redirect('users/login');
                return;
            }
            
            // Initialize data array with form values
            $data = [
                'title' => 'Report Incident',
                'pageTitle' => 'Report New Incident',
                'incident_type' => trim($_POST['incident_type'] ?? ''),
                'site_id' => trim($_POST['site_id'] ?? ''),
                'incident_date' => trim($_POST['incident_date'] ?? ''),
                'incident_time' => trim($_POST['incident_time'] ?? ''),
                'priority' => trim($_POST['priority'] ?? 'Medium'),
                'description' => trim($_POST['description'] ?? ''),
                'actions_taken' => trim($_POST['actions_taken'] ?? ''),
                'people_involved' => trim($_POST['people_involved'] ?? ''),
                'latitude' => trim($_POST['latitude'] ?? ''),
                'longitude' => trim($_POST['longitude'] ?? ''),
                // Error fields
                'incident_type_err' => '',
                'site_id_err' => '',
                'incident_date_err' => '',
                'incident_time_err' => '',
                'description_err' => '',
            ];
            
            // Validate inputs
            if (empty($data['incident_type'])) {
                $data['incident_type_err'] = 'Please select an incident type';
            }
            
            if (empty($data['site_id'])) {
                $data['site_id_err'] = 'Please select a site';
            }
            
            if (empty($data['incident_date'])) {
                $data['incident_date_err'] = 'Please select incident date';
            }
            
            if (empty($data['incident_time'])) {
                $data['incident_time_err'] = 'Please select incident time';
            }
            
            if (empty($data['description'])) {
                $data['description_err'] = 'Please provide incident description';
            }
            
            // Handle file uploads (optional)
            $uploadedFiles = [];
            if (!empty($_FILES['evidence_files']['name'][0])) {
                $uploadDir = APP_ROOT . '/../public/uploads/incidents/';
                
                // Create directory if not exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                foreach ($_FILES['evidence_files']['tmp_name'] as $key => $tmp_name) {
                    if (!empty($tmp_name)) {
                        $fileName = time() . '_' . basename($_FILES['evidence_files']['name'][$key]);
                        $targetFile = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($tmp_name, $targetFile)) {
                            $uploadedFiles[] = $fileName;
                        }
                    }
                }
            }
            
            // Check for errors
            if (empty($data['incident_type_err']) && empty($data['site_id_err']) && 
                empty($data['incident_date_err']) && empty($data['incident_time_err']) && 
                empty($data['description_err'])) {
                
                // No errors - prepare data for insertion
                $incidentData = [
                    'user_id' => $userId,
                    'site_id' => $data['site_id'],
                    'incident_type' => $data['incident_type'],
                    'incident_date' => $data['incident_date'],
                    'incident_time' => $data['incident_time'],
                    'priority' => $data['priority'],
                    'description' => $data['description'],
                    'actions_taken' => $data['actions_taken'],
                    'people_involved' => $data['people_involved'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'evidence_files' => !empty($uploadedFiles) ? implode(',', $uploadedFiles) : null
                ];
                
                // Insert into database
                if ($this->mobileRiderModel->createIncidentReport($incidentData)) {
                    flash('incident_message', 'Incident report submitted successfully!', 'alert-success');
                    redirect('MobileRider/incidents');
                } else {
                    flash('incident_message', 'Error submitting report. Please try again.', 'alert-danger');
                    
                    // Get sites for form reload
                    $route = $this->mobileRiderModel->getRouteByUserId($userId);
                    $data['sites'] = [];
                    if ($route) {
                        $data['sites'] = $this->mobileRiderModel->getRouteSites($route->id);
                    }
                    
                    $this->view('mobilerider/incidents/v_create_Incident', $data);
                }
            } else {
                // Validation errors - reload form with data
                
                // Get sites for form
                $route = $this->mobileRiderModel->getRouteByUserId($userId);
                $data['sites'] = [];
                if ($route) {
                    $data['sites'] = $this->mobileRiderModel->getRouteSites($route->id);
                }
                
                $this->view('mobilerider/incidents/v_create_Incident', $data);
            }
        } else {
            // GET request - redirect to create form
            redirect('MobileRider/createIncident');
        }
    }

    public function deleteIncident()
    {
        // Validate ID
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            flash('incident_message', 'Invalid incident ID.', 'alert alert-danger');
            redirect('MobileRider/incidents');
            return;
        }

        $incidentId = $_GET['id'];

        // Fetch the incident first (to access media files)
        $incident = $this->mobileRiderModel->getIncidentById($incidentId);

        if (!$incident) {
            flash('incident_message', 'Incident not found.', 'alert alert-danger');
            redirect('MobileRider/incidents');
            return;
        }

        // Delete uploaded files if they exist
        if (!empty($incident->media_files)) {
            $files = explode(',', $incident->media_files);
            $uploadDir = APP_ROOT . '/public/uploads/incidents/';

            foreach ($files as $file) {
                $filePath = $uploadDir . trim($file);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        // Delete incident record
        if ($this->mobileRiderModel->deleteIncidentById($incidentId)) {
            flash('incident_message', 'Incident deleted successfully!', 'alert alert-success');
        } else {
            flash('incident_message', 'Error deleting incident.', 'alert alert-danger');
        }

        redirect('MobileRider/incidents');
    }

    public function updateIncident()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize input
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $incidentId = $_POST['incident_id'] ?? null;
            if (!$incidentId) {
                flash('incident_message', 'Invalid Incident ID.', 'alert alert-danger');
                redirect('MobileRider/incidents');
                return;
            }

            // Handle multiple severity checkboxes
            $severity = isset($_POST['severity']) ? implode(',', $_POST['severity']) : null;

            // Handle new file uploads
            $uploadedFiles = [];
            if (!empty($_FILES['media_files']['name'][0])) {
                $uploadDir = APP_ROOT . '/public/uploads/incidents/';

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['media_files']['tmp_name'] as $key => $tmp_name) {
                    $fileName = basename($_FILES['media_files']['name'][$key]);
                    $targetFile = $uploadDir . $fileName;

                    if (move_uploaded_file($tmp_name, $targetFile)) {
                        $uploadedFiles[] = $fileName;
                    }
                }
            }

            // Fetch existing incident to preserve old media if not replaced
            $existingIncident = $this->mobileRiderModel->getIncidentById($incidentId);
            $mediaFiles = !empty($uploadedFiles)
                ? implode(',', $uploadedFiles)
                : ($existingIncident->media_files ?? null);

            // Prepare updated data
            $data = [
                'id' => $incidentId,
                'property_site' => $_POST['property_site'],
                'incident_type' => $_POST['incident_type'],
                'incident_description' => $_POST['incident_description'],
                'action_taken' => $_POST['action_taken'],
                'severity' => $severity,
                'media_files' => $mediaFiles
            ];

            // Call model to update
            if ($this->mobileRiderModel->updateIncident($data)) {
                flash('incident_message', 'Incident updated successfully!', 'alert alert-success');
            } else {
                flash('incident_message', 'Error updating incident.', 'alert alert-danger');
            }

            redirect('MobileRider/incidents');
        } else {
            flash('incident_message', 'Invalid request method.', 'alert alert-danger');
            redirect('MobileRider/incidents');
        }
    }
    
    public function addIncidentReview()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize input
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $userId = $_SESSION['user_id'] ?? null;
            $incidentId = $_POST['incident_id'] ?? null;
            
            if (!$userId) {
                flash('incident_message', 'Session expired. Please login again.', 'alert alert-danger');
                redirect('users/login');
                return;
            }
            
            if (!$incidentId) {
                flash('incident_message', 'Invalid incident ID.', 'alert alert-danger');
                redirect('MobileRider/incidents');
                return;
            }
            
            // Validate input
            if (empty($_POST['review_title']) || empty($_POST['review_details'])) {
                flash('incident_message', 'Review title and details are required.', 'alert alert-danger');
                redirect('MobileRider/viewIncident/' . $incidentId);
                return;
            }
            
            // Get user details
            $user = $this->userModel->getUserById($userId);
            
            // Prepare review data
            $reviewData = [
                'incident_id' => $incidentId,
                'user_id' => $userId,
                'reviewer_name' => $user->name ?? 'Unknown',
                'review_type' => $_POST['review_type'] ?? 'Comment',
                'review_title' => trim($_POST['review_title']),
                'review_details' => trim($_POST['review_details'])
            ];
            
            // Add review to database
            if ($this->mobileRiderModel->addIncidentReview($reviewData)) {
                // Log activity
                $this->mobileRiderModel->logActivity([
                    'user_id' => $userId,
                    'activity_type' => 'incident_review',
                    'activity_titel' => 'Added Review to Incident',
                    'activity_details' => "Added review to incident #{$incidentId}: {$reviewData['review_title']}"
                ]);
                
                flash('incident_message', 'Review added successfully!', 'alert alert-success');
            } else {
                flash('incident_message', 'Error adding review. Please try again.', 'alert alert-danger');
            }
            
            redirect('MobileRider/viewIncident/' . $incidentId);
        } else {
            redirect('MobileRider/incidents');
        }
    }
}
