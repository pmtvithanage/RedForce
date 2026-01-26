<?php
class MobileRider extends Controller
{
    private $mobileRiderModel;
    private $userModel;
    private $advertisementModel;

    public function __construct()
    {
        // Check if user is logged in and has mobile rider role
        requireAuth('mobile rider');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->mobileRiderModel = $this->model('M_mobilerider');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index()
    {
        redirect('MobileRider/dashboard');
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
        if ($userId) {
            $recentActivities = $this->mobileRiderModel->getRecentActivities($userId, 50);
        }

        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'advertisements' => $advertisements,
            'notes' => $notes,
            'recent_activities' => $recentActivities,
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
        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages'
        ];
        $this->view('mobilerider/v_messages', $data);
    }

    // Incidents
    public function incidents()
    {
        $incident_reports = $this->mobileRiderModel->getAllIncidents();
        $incident_stats = $this->mobileRiderModel->getAllIncidentStatistics();

        $data = [
            'title' => 'Incidents',
            'pageTitle' => 'Incident Reports',
            'incident_reports' => $incident_reports,
            'incident_stats' => $incident_stats,
        ];
        $this->view('mobilerider/incidents/v_incidents', $data);
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
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Handle multiple severity checkboxes (convert to string)
            $severity = isset($_POST['severity']) ? implode(',', $_POST['severity']) : null;

            // Handle file uploads (optional)
            $uploadedFiles = [];
            if (!empty($_FILES['media_files']['name'][0])) {
                $uploadDir = APP_ROOT . '/public/uploads/incidents/';

                // Create directory if not exists
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

            // Prepare data for model
            $data = [
                'user_id' => $_SESSION['user_id'],
                'officer_name' => $_POST['officer_name'],
                'officer_role' => $_POST['officer_role'],
                'property_site' => $_POST['property_site'],
                'incident_type' => $_POST['incident_type'],
                'incident_date' => $_POST['incident_date'],
                'incident_time' => $_POST['incident_time'],
                'incident_description' => $_POST['incident_description'],
                'action_taken' => $_POST['action_taken'],
                'severity' => $severity,
                'follow_up_id' => $_POST['follow_up_id'] ?? null,
                'media_files' => !empty($uploadedFiles) ? implode(',', $uploadedFiles) : null
            ];

            // Insert record
            if ($this->mobileRiderModel->addIncident($data)) {
                flash('incident_message', 'Incident Report submitted successfully!');
                redirect('MobileRider/incidents');
            } else {
                flash('incident_message', 'Error submitting report. Please try again.', 'alert alert-danger');
                $this->view('mobilerider/v_incidents', $data);
            }
        } else {
            $data = [
                'title' => 'Incidents',
            ];
            $this->view('mobilerider/v_incidents', $data);
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
}