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
        redirect('mobilerider/dashboard');
    }

    // Dashboard action
    public function dashboard()
    {
        $role = 'mobile rider';
        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);

        $notes = $this->getNotes();

        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'advertisements' => $advertisements,
            'notes' => $notes,
        ];
        $this->view('mobilerider/v_dashboard', $data);
    }

    // Schedule action
    public function sites()
    {
        $data = [
            'title' => 'Sites',
            'pageTitle' => 'Assigned Sites'
        ];
        $this->view('mobilerider/v_sites', $data);
    }

    // Messages
    public function messages()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            redirect('mobilerider/dashboard');
            return;
        }
        
        $conversations = $this->mobileRiderModel->getConversations($user_id);
        $all_users = $this->mobileRiderModel->getAllUsers($user_id);
        $unread_count = $this->mobileRiderModel->getUnreadCount($user_id);
        
        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages',
            'conversations' => $conversations,
            'all_users' => $all_users,
            'unread_count' => $unread_count,
            'current_recipient_id' => isset($_GET['with']) ? $_GET['with'] : null
        ];
        
        $this->view('mobilerider/v_messages', $data);
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
            $this->mobileRiderModel->markAsRead($recipient_id, $sender_id);
            
            // Get messages
            $messages = $this->mobileRiderModel->getMessages($sender_id, $recipient_id);
            
            echo json_encode(['status' => 'success', 'messages' => $messages]);
        }
    }

    // Send a message (AJAX)
    public function sendMessage()
    {
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
            
            if ($this->mobileRiderModel->sendMessage($sender_id, $recipient_id, $message)) {
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
            
            $users = $this->mobileRiderModel->getAllUsers($user_id);
            echo json_encode(['status' => 'success', 'users' => $users]);
        }
    }

    // Get conversations (AJAX for updates)
    public function getConversations()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            
            if (!$user_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            $conversations = $this->mobileRiderModel->getConversations($user_id);
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
            
            $results = $this->mobileRiderModel->searchConversations($user_id, $search_term);
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
            
            if ($this->mobileRiderModel->deleteMessage($message_id, $user_id)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
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
        $this->view('mobilerider/v_incidents', $data);
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
                redirect('mobilerider/leaverequests');
                return;
            }
            
            if ($this->mobileRiderModel->addLeaveRequest($data)) {
                flash('leave_success', 'Leave request submitted successfully');
            } else {
                flash('leave_error', 'Something went wrong. Please try again');
            }
            
            redirect('mobilerider/leaverequests');
        } else {
            redirect('mobilerider/leaverequests');
        }
    }

    // Edit Leave Request
    public function editLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $mobilerider_id = $_SESSION['user_id'] ?? null;
            
            if (!$mobilerider_id) {
                flash('leave_error', 'User not authenticated');
                redirect('mobilerider/leaverequests');
                return;
            }
            
            $existingLeave = $this->mobileRiderModel->getLeaveRequestById($id);
            
            if (!$existingLeave || $existingLeave->mobilerider_id != $mobilerider_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('mobilerider/leaverequests');
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
            
            redirect('mobilerider/leaverequests');
        } else {
            redirect('mobilerider/leaverequests');
        }
    }

    // Delete Leave Request
    public function deleteLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mobilerider_id = $_SESSION['user_id'] ?? null;
            
            if (!$mobilerider_id) {
                flash('leave_error', 'User not authenticated');
                redirect('mobilerider/leaverequests');
                return;
            }
            
            $leave = $this->mobileRiderModel->getLeaveRequestById($id);
            
            if (!$leave || $leave->mobilerider_id != $mobilerider_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('mobilerider/leaverequests');
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
            
            redirect('mobilerider/leaverequests');
        } else {
            redirect('mobilerider/leaverequests');
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
                    header("Location: " . URL_ROOT . "/mobilerider/dashboard?notes=open");
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
                    header("Location: " . URL_ROOT . "/mobilerider/dashboard?notes=open");
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
        header("Location:" . URL_ROOT . "/mobilerider/dashboard?notes=open");
    }

    public function editNote()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $noteId = $_POST['noteId'];
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);

            if ($this->mobileRiderModel->updateNoteById($noteId, $title, $content)) {
                header("Location: " . URL_ROOT . "/mobilerider/dashboard?notes=open");
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
            redirect('mobilerider/incidents');
            return;
        }

        $incidentId = $_GET['id'];

        // Fetch the incident first (to access media files)
        $incident = $this->mobileRiderModel->getIncidentById($incidentId);

        if (!$incident) {
            flash('incident_message', 'Incident not found.', 'alert alert-danger');
            redirect('mobilerider/incidents');
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

        redirect('mobilerider/incidents');
    }

    public function updateIncident()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize input
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $incidentId = $_POST['incident_id'] ?? null;
            if (!$incidentId) {
                flash('incident_message', 'Invalid Incident ID.', 'alert alert-danger');
                redirect('mobilerider/incidents');
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

            redirect('mobilerider/incidents');
        } else {
            flash('incident_message', 'Invalid request method.', 'alert alert-danger');
            redirect('mobilerider/incidents');
        }
    }
}