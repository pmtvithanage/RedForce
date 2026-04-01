<?php
class Admin extends Controller {
    private $adminModel;
    private $userModel;
    private $homeModel;
    private $chartModel;
    private $messageModel;
    private $premiseOfficerModel;
    private $packageModel;

    private $notificationModel;
    private $db;
    

    public function __construct() {
        requireAuth('admin');
        $this->adminModel = $this->model('M_admin');
        $this->userModel = $this->model('M_users');
        $this->homeModel = $this->model('M_home');
        $this->messageModel = $this->model('M_message');
        $this->premiseOfficerModel = $this->model('M_premiseofficer');
        $this->packageModel = $this->model('M_package');
        $this->notificationModel = $this->model('M_notifications');
        $this->db = new Database();
        // Load route helper
        require_once APP_ROOT . '/helpers/route_helper.php';
        
        // Try to load chart model
        $chartModel = $this->model('ChartDataModel');
        if ($chartModel) {
            $this->chartModel = $chartModel;
        }
    }

    /**
     * Get user model based on role
     * Helper method for leave request processing
     */
    private function getUserModel($role) {
        switch (strtolower($role)) {
            case 'caretaker':
                return $this->model('M_caretaker');
            case 'supervisor':
                return $this->model('M_supervisor');
            case 'mobile rider':
                return $this->model('M_mobilerider');
            case 'premise officer':
                return $this->model('M_premiseofficer');
            default:
                return null;
        }
    }

    public function index() {
        redirect('admin/dashboard');
    }

    public function notifications() {
        // TODO: Fetch notifications from database
        $notifications = $this->notificationModel->getNotifications($_SESSION['user_id']);
        
        $data = [
            'title' => 'Notifications',
            'pageTitle' => 'Notifications',
            'role' => 'admin',
            'notifications' => $notifications
        ];
        $this->view('components/notifications', $data);
    }

    public function dashboard() {
        $pendingLeaves = $this->adminModel->getPendingLeaveRequests();
        $leaveStats = $this->adminModel->getLeaveRequestStats();
        $recentActivities = $this->adminModel->getRecentActivities(100);
        
        // Initialize chart data
        $userRoleChart = [
            'labels' => [],
            'data' => [],
            'colors' => []
        ];
        
        // Only try to get chart data if model exists
        if ($this->chartModel) {
            $userRoleChart = $this->chartModel->getUserRolePieChart();
        }
    
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Admin Dashboard',
            'pendingLeaves' => $pendingLeaves,
            'leaveStats' => $leaveStats,
            'recent_activities' => $recentActivities,
            'userRoleChart' => $userRoleChart
        ];
        
        $this->view('admin/dashboard/v_dashboard', $data);
    }

    public function messages(){
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            redirect('admin/dashboard');
            return;
        }
        
        $conversations = $this->messageModel->getConversations($user_id);
        // Admin can message all users
        $all_users = $this->messageModel->getAllUsers($user_id);
        $unread_count = $this->messageModel->getUnreadCount($user_id);
        
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Messages',
            'conversations' => $conversations,
            'all_users' => $all_users,
            'unread_count' => $unread_count,
            'current_recipient_id' => isset($_GET['with']) ? $_GET['with'] : null
        ];
        $this->view('admin/dashboard/v_messages', $data);
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
            
            $users = $this->messageModel->getAllUsers($user_id);
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

    public function pendings(){
        $leaveRequests = $this->adminModel->getAllLeaveRequests();
        $leaveStats = $this->adminModel->getLeaveRequestStats();
        
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Leave Requests',
            'leaveRequests' => $leaveRequests,
            'leaveStats' => $leaveStats
        ];
        $this->view('admin/dashboard/v_pendings', $data);
    }

    public function assign(){
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Officer Assignment'
        ];
        $this->view('admin/dashboard/v_assign', $data);
    }

    public function alerts(){
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Send Alerts'
        ];
        $this->view('admin/dashboard/v_alerts', $data);
    }

// ======================================================================== //
// =======================      Admin Officers       ====================== //
// ======================================================================== //

    public function officers() {
        $officers = $this->adminModel->getAllPO();
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Manage Officers',
            'officer' => $officers
    ];
        $this->view('admin/officers/v_officers', $data);
    }
    public function mobileriders() {
        $officers = $this->adminModel->getAllMR();
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Manage Officers',
            'officer' => $officers
    ];
        $this->view('admin/officers/v_mobileriders', $data);
    }
    public function caretakers() {
        $officers = $this->adminModel->getAllCT();
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Manage Officers',
            'officer' => $officers
    ];
        $this->view('admin/officers/v_caretakers', $data);
    }

    public function officer_profile($id){
        $officer = $this->adminModel->getPOById($id);
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Officer Profile',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_officer_profile', $data);
    }
    public function mobile_rider_profile($id){
        $officer = $this->adminModel->getMRById($id);
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Officer Profile',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_mobilerider_profile', $data);
    }
    public function care_taker_profile($id){
        $officer = $this->adminModel->getCTById($id);
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Officer Profile',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_caretaker_profile', $data);
    }

    public function viewOfficerCalendar($officerId) {
        // Get officer details
        $officer = $this->adminModel->getPOById($officerId);
        
        // Get officer assignments
        $assignments = $this->premiseOfficerModel->getActiveAssignments($officerId);
        
        // Get approved leave dates
        $leaveDates = $this->premiseOfficerModel->getApprovedLeaveDates($officerId);
        
        $data = [
            'title' => 'Officer Calendar',
            'pageTitle' => 'Officer Schedule',
            'officer' => $officer,
            'assignments' => $assignments,
            'leaveDates' => $leaveDates
        ];
        
        $this->view('admin/officers/v_officer_calendar', $data);
    }

    public function getOfficerShiftDetails() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'] ?? null;
            $officerId = $_POST['officer_id'] ?? null;
            
            if (!$date || !$officerId) {
                echo json_encode(['success' => false, 'message' => 'Missing date or officer ID']);
                return;
            }
            
            // Get shift details for the date
            $shiftDetails = $this->premiseOfficerModel->getShiftDetailsForDate($officerId, $date);
            
            // Get leave details if any
            $leaveDetails = $this->premiseOfficerModel->getLeaveForDate($officerId, $date);
            
            if ($leaveDetails) {
                echo json_encode([
                    'success' => true,
                    'isLeave' => true,
                    'leave_type' => $leaveDetails->leave_type,
                    'reason' => $leaveDetails->reason
                ]);
            } elseif ($shiftDetails) {
                echo json_encode([
                    'success' => true,
                    'isLeave' => false,
                    'shift_type' => $shiftDetails->shift_type,
                    'location' => $shiftDetails->site_name,
                    'address' => $shiftDetails->address . ', ' . $shiftDetails->city,
                    'time' => $shiftDetails->shift_type === 'day' ? '6:00 AM - 6:00 PM' : '6:00 PM - 6:00 AM',
                    'client' => $shiftDetails->contact_person_name ?? 'N/A',
                    'notes' => $shiftDetails->notes ?? 'No additional notes'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No shift details found']);
            }
        }
    }

    public function porecruitment() {
        $exists = $this->adminModel->getJobApplication('po');
        if($exists) {
            $this->edit_job_application($exists,'po');
        }
        else{
            $this->add_job_application('po');
        }
    }
    public function mrrecruitment() {
        $exists = $this->adminModel->getJobApplication('mr');
        if($exists) {
            $this->edit_job_application($exists,'mr');
        }
        else{
            $this->add_job_application('mr');
        }
    }
    public function ctrecruitment() {
        $exists = $this->adminModel->getJobApplication('ct');
        if($exists) {
            $this->edit_job_application($exists,'ct');
        }
        else{
            $this->add_job_application('ct');
        }
    }

    public function add_job_application($role) {

        if($role == 'po') $role_name = "Premise Officer";
        elseif($role == 'mr') $role_name = "Mobile Rider";
        elseif($role == 'ct') $role_name = "Care Taker";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => 'Officers',
                'pageTitle' => 'Add Job Application',

                'description' => $this->sanitizeInput($_POST['description'] ?? ''),
                'qualifications' => $this->sanitizeInput($_POST['qualifications'] ?? ''),
                'due_date' => $this->sanitizeInput($_POST['due_date'] ?? ''),
                'completed' => ''

            ];
            $this->adminModel->insertJobApplication($data, $role);
            flash('msg', 'Job Application Added Successfully', 'alert-success');

            
            // Validate form
            if(!empty($data['description']) && !empty($data['qualifications']) && !empty($data['due_date'])) {
                $data['completed'] = 'true';
                
                $title = "Job Application Created";
                $description = "New " . $role_name . " job application created with due date: " . $data['due_date'];
                $type = "update";
                $this->adminModel->insertRecentActivity($title, $description,$type);


                $this->view('admin/officers/v_'.$role.'_recruitment', $data);
            }
            else{
                $data['completed'] = 'false';

                $title = "Job Application Created";
                $description = "New " . $role_name . " job application created with due date: " . $data['due_date'] ."(Not Completed)";
                $type = "alert";
                $this->adminModel->insertRecentActivity($title, $description,$type);

                $this->view('admin/officers/v_'.$role.'_recruitment', $data);

            }
            
        }
        else {
            $data = [
                'title' => 'Officers',
                'pageTitle' => 'Add Job Application',

                'description' => '',
                'qualifications' => '',
                'due_date' => '',
                

            ];
            $this->view('admin/officers/v_'.$role.'_recruitment', $data);

        }

        
    }
    public function edit_job_application($exists,$role) {
        if($role == 'po') $role_name = "Premise Officer";
        elseif($role == 'mr') $role_name = "Mobile Rider";
        elseif($role == 'ct') $role_name = "Care Taker";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => 'Officers',
                'pageTitle' => 'Add Job Application',

                'description' => $this->sanitizeInput($_POST['description'] ?? ''),
                'qualifications' => $this->sanitizeInput($_POST['qualifications'] ?? ''),
                'due_date' => $this->sanitizeInput($_POST['due_date'] ?? ''),
                'completed' => '',
                'status' => $exists->status

            ];

            // Validate form
            if(!empty($data['description']) && !empty($data['qualifications']) && !empty($data['due_date'])) {
                $data['completed'] = 'true';
                
                // Check if due date has passed
                $dueDate = date('Y-m-d', strtotime($data['due_date']));
                $today = date('Y-m-d');
                if ($dueDate < $today) {
                    // If due date has passed, close the status
                    $this->adminModel->changeStatus($role, 'closed');
                }

                $title = "Job Application Updated";
                $description = "Updated " . $role_name . " job application created with due date: " . $data['due_date'];
                $type = "update";
                $this->adminModel->insertRecentActivity($title, $description,$type);

                flash('msg', 'Job Application Updated Successfully', 'alert-success');
                $this->view('admin/officers/v_'.$role.'_recruitment', $data);
            }
            else {
                
                $this->adminModel->changeStatus($role,'closed');
                $data['completed'] = 'false';

                $title = "Job Application Updated";
                $description = "Updated " . $role_name . " job application created with due date: " . $data['due_date'] ." (Not Completed)";
                $type = "alert";
                $this->adminModel->insertRecentActivity($title, $description,$type);

                flash('msg', 'Job Application Updated Successfully', 'alert-success');
                $this->view('admin/officers/v_'.$role.'_recruitment', $data);
            }
            $this->adminModel->editJobApplication($data, $role);
            
        }
        else {
            $data = [
                'title' => 'Officers',
                'pageTitle' => 'Add Job Application',

                'description' => $exists->description,
                'qualifications' =>  $exists->qualifications,
                'due_date' => $exists->due_date,
                'completed' => $exists->completed,
                'status' => $exists->status
                

            ];
            $this->view('admin/officers/v_'.$role.'_recruitment', $data);

        }
    }
    public function changeStatus($role,$status) {
        $this->adminModel->changeStatus($role,$status);
        $data = $this->adminModel->getJobApplication($role);
        
        // Add activity log
        $role_name = $this->getRoleName($role);
        $title = "Job Application Status Changed";
        $description = $role_name . " job application status changed to: " . $status;
        $type = $status == 'open' ? "message" : "leave";
        $this->adminModel->insertRecentActivity($title, $description, $type);
        
        flash('msg', 'Job Application Status Updated Successfully', 'alert-success');
        $this->view('admin/officers/v_'.$role.'_recruitment', $data);
    }
    
    private function getRoleName($role) {
        switch($role) {
            case 'po': return "Premise Officer";
            case 'mr': return "Mobile Rider";
            case 'ct': return "Care Taker";
            default: return "Officer";
        }
    }
    
    public function delete_job_application($role) {
        if($role == 'po') $role_name = "Premise Officer";
        elseif($role == 'mr') $role_name = "Mobile Rider";
        elseif($role == 'ct') $role_name = "Care Taker";

        $this->adminModel->deleteJobApplication($role);

        $title = "Job Application Removed";
        $description = "The " . $role_name . " job application was removed.";
        $type = "alert";
        $this->adminModel->insertRecentActivity($title, $description,$type);

        flash('msg', 'Job Application Deleted Successfully', 'alert-success');
        redirect('admin/'.$role.'recruitment');
    }


    public function pending_officer_applications($type) {
        if($type == 'po' || $type == 'ct' || $type == 'mr') {
            $officer = $this->homeModel->getPendingOfficerApplications($type);
        } else {
            $officer = $this->homeModel->getAllPendingOfficerApplications();
        }
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Pending Officer Applications',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_pending_officer_applications', $data);
    }
    public function accepted_officer_applications($type) {
       
        if($type == 'po' || $type == 'ct' || $type == 'mr') {
            $officer = $this->homeModel->getApprovedOfficerApplications($type);
        } else {
            $officer = $this->homeModel->getAllApprovedOfficerApplications();
        }
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Approved Officer Applications',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_accepted_officer_applications', $data);
    }
    public function rejected_officer_applications($type) {
        if($type == 'po' || $type == 'ct' || $type == 'mr') {
            $officer = $this->homeModel->getRejectedOfficerApplications($type);
        } else {
            $officer = $this->homeModel->getAllRejectedOfficerApplications();
        }
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Rejected Officer Applications',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_rejected_officer_applications', $data);
    }
    public function accept_officer_applications($id,$role) {
        if($role == 'po') $role_name = "Premise Officer";
        elseif($role == 'mr') $role_name = "Mobile Rider";
        elseif($role == 'ct') $role_name = "Care Taker";
        
        // Get the logged-in admin ID (you need to adjust this based on your auth system)
        $adminId = $_SESSION['user_id'] ?? 1; // Default to 1 if session not set
        
        $result = $this->adminModel->acceptOfficerApplication($id, $adminId, $role);
        
        if ($result && isset($result['success']) && $result['success']) {
            // Get officer details for email
            $officer = $this->homeModel->getApplicationById($id);
            
            // Add activity log
            $title = "Officer Application Accepted";
            $description = $role_name . " application #" . $id . " was approved";
            $type = "registration";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            // Get numeric user ID for notification
            $this->db->query("SELECT id FROM Users WHERE userID = :userID");
            $this->db->bind(':userID', $result['userID']);
            $userRow = $this->db->single();
            $numericUserId = $userRow ? $userRow->id : null;
            
            if ($numericUserId) {
                // Send notification to officer
                $this->notificationModel->insertNotification(
                    $numericUserId,
                    'success',
                    'Application Approved',
                    'Congratulations! Your ' . $role_name . ' application has been approved. Welcome to RED FORCE!',
                    '/' . strtolower(str_replace(' ', '', $role_name)) . '/dashboard',
                    'check_circle',
                    $adminId
                );
            }
            
            // Send welcome email with credentials
            if ($officer && isset($result['userID']) && isset($result['tempPassword'])) {
                $emailVars = [
                    'site_name' => SITE_NAME,
                    'officer_name' => $officer->name ?? 'Officer',
                    'login_id' => $result['userID'],
                    'temp_password' => $result['tempPassword'],
                    'role_name' => $role_name,
                    'login_url' => URL_ROOT . '/users/login'
                ];
                
                $emailResult = send_templated_email(
                    $officer->email,
                    'welcome_officer',
                    $emailVars,
                    'Welcome to ' . SITE_NAME . ' - Officer Account Created'
                );
                
                if (!$emailResult['success']) {
                    error_log('Failed to send welcome email to officer: ' . $emailResult['message']);
                }
            }
            
            flash('msg', 'Officer application accepted successfully and welcome email sent', 'alert-success');
            redirect('admin/pending_officer_applications/all');
        } else {
            $errorMsg = isset($result['message']) ? $result['message'] : 'Failed to accept officer application';
            flash('msg', $errorMsg, 'alert-danger');
            redirect('admin/pending_officer_applications/all');
        }
    }
    public function reject_officer_applications($id) {
        if ($this->adminModel->rejectOfficerApplication($id)) {
            // Add activity log
            $title = "Officer Application Rejected";
            $description = "Officer application #" . $id . " was rejected";
            $type = "incident";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            // Send notification to officer
            $adminId = $_SESSION['user_id'] ?? 1;
            $this->notificationModel->insertNotification(
                $id,
                'error',
                'Application Rejected',
                'Your officer application has been rejected. Please contact HR for more information.',
                '/users/login',
                'cancel',
                $adminId
            );
            
            flash('msg', 'Officer application rejected successfully', 'alert-success');
            redirect('admin/pending_officer_applications/all');
        } else {
            flash('officer_message', 'Failed to reject officer application', 'alert-danger');
            redirect('admin/pending_officer_applications/all');
        }
    }
    public function deleteOfficerApplication($id){
        if ($this->adminModel->deleteOfficerApplication($id)) {
            // Add activity log
            $title = "Officer Application Deleted";
            $description = "Officer application #" . $id . " was permanently deleted";
            $type = "alert";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            flash('msg', 'Officer application deleted successfully', 'alert-success');
            redirect('admin/rejected_officer_applications/all');
        } else {
            flash('officer_message', 'Failed to reject officer application', 'alert-danger');
            redirect('admin/rejected_officer_applications/all');
        }
    }
// ======================================================================== //
// =======================      Admin Clients       ====================== //
// ======================================================================== //

    public function clients() {
        // Get pending service requests count for notification badge
        $requestStats = $this->adminModel->getServiceRequestStats();

        $stats = $this->adminModel->getClientStatistics();
        $pendingCount = $requestStats->pending ?? 0;

        $clients = $this->adminModel->getAllClients();
        
        // Get staff counts for each client
        foreach ($clients as $client) {
            $staffCounts = $this->adminModel->getClientStaffCounts($client->id);
            $client->officers_count = $staffCounts->officers_count ?? 0;
            $client->supervisors_count = $staffCounts->supervisors_count ?? 0;
            $client->caretakers_count = $staffCounts->caretakers_count ?? 0;
        }
        
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Manage Clients',
            'pendingRequestsCount' => $pendingCount,
            'clients' => $clients,
            'stats' => $stats
        ];
        $this->view('admin/clients/v_clients', $data);  
    }

    public function addclients(){
        $clients = $this->homeModel->getPendingRequest();
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Clients',
            'clients' => $clients
        ];

        $this->view('admin/clients/v_requests-pending', $data);
    }
    public function acceptClient($clientId) {
    // Get the logged-in admin ID (you need to adjust this based on your auth system)
    $adminId = $_SESSION['user_id'] ?? 1; // Default to 1 if session not set
    
    $result = $this->adminModel->acceptClient($clientId, $adminId);
    
    if ($result && isset($result['success']) && $result['success']) {
        // Get client details for email
        $this->db->query("SELECT * FROM client_requests WHERE id = :id");
        $this->db->bind(':id', $clientId);
        $clientRequest = $this->db->single();
        
        // Add activity log
        $title = "Client Accepted";
        $description = "Client #" . $clientId . " registration was approved";
        $type = "registration";
        $this->adminModel->insertRecentActivity($title, $description, $type);
        
        // Send notification to client using numeric ID
        if (isset($result['id']) && $result['id']) {
            $this->notificationModel->insertNotification(
                $result['id'],
                'success',
                'Registration Approved',
                'Your registration has been approved. Welcome to RED FORCE!',
                '/client/dashboard',
                'check_circle',
                $adminId
            );
        }
        
        // Send welcome email with credentials
        if ($clientRequest && isset($result['new_user_id']) && isset($result['temp_password'])) {
            $emailVars = [
                'site_name' => SITE_NAME,
                'client_name' => $clientRequest->company_name ?? 'Client',
                'login_id' => $result['new_user_id'],
                'temp_password' => $result['temp_password'],
                'login_url' => URL_ROOT . '/users/login'
            ];
            
            $emailResult = send_templated_email(
                $result['email'],
                'welcome_client',
                $emailVars,
                'Welcome to ' . SITE_NAME . ' - Client Account Created'
            );
            
            if (!$emailResult['success']) {
                error_log('Failed to send welcome email to client: ' . $emailResult['message']);
            }
        }
        
        flash('msg', 'Client accepted successfully and welcome email sent', 'alert-success');
        redirect('admin/addclients');
    } else {
        flash('client_message', 'Failed to accept client', 'alert-danger');
        redirect('admin/addclients');
    }
}
    public function rejectClient($clientId){
        if($this->adminModel->rejectClient($clientId)){
            // Add activity log
            $title = "Client Rejected";
            $description = "Client #" . $clientId . " registration was rejected";
            $type = "incident";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            // Send notification to client
            $adminId = $_SESSION['user_id'] ?? 1;
            $this->notificationModel->insertNotification(
                $clientId,
                'error',
                'Registration Rejected',
                'Your registration has been rejected. Please contact support for more information.',
                '/client/dashboard',
                'cancel',
                $adminId
            );
            
            flash('msg', 'Client rejected successfully', 'alert-success');
            redirect('admin/addclients');
        } else {
            flash('client_message', 'Failed to reject client', 'alert-danger');
            redirect('admin/addclients');
        }
    }
    public function deleterequest($clientId){
        $client =  $this->adminModel->getClientById($clientId); // NOT WORKING DELETE IMAGE FILE 🥲
        
        $imagePath = PUB_ROOT . '/uploads/clientLogos/' . $client->client_profile;
        deleteImage($imagePath);
        if($this->adminModel->deleteRequest($clientId)){
            // Add activity log
            $title = "Client Request Deleted";
            $description = "Client request #" . $clientId . " was permanently deleted";
            $type = "alert";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            flash('msg', 'Client request deleted successfully', 'alert-success');
            redirect('admin/rejected');
        } else {
            flash('client_message', 'Failed to delete client request', 'alert-danger');
            redirect('admin/rejected');
        }
    }
    public function accepted(){
        $clients = $this->homeModel->getApprovedRequest();
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Clients',
            'clients' => $clients
        ];

        $this->view('admin/clients/v_requests-accepted', $data);
    }

    public function rejected(){
        $clients = $this->homeModel->getRejectedRequest();
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Clients',
            'clients' => $clients
        ];

        $this->view('admin/clients/v_requests-rejected', $data);
    }

    

     public function clientprofile($Id){
        // $Id is Users.id, convert to Clients.id
        $clientsTableId = $this->adminModel->getClientsTableId($Id);
        $client = $this->adminModel->getClientById($clientsTableId);
        $sites = $this->adminModel->getSiteByClientId($clientsTableId);
        $data = [
            
            'title' => 'Clients',
            'pageTitle' => $client->name . ' Profile',
            'client' => $client
            ,'sites' => $sites
        ];
        $this->view('admin/clients/v_clientProfile', $data);
    }

    public function addsite($Id){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $data = [
                'client_id' => $Id, // Use the parameter from URL
                'title' => 'Clients',
                'pageTitle' => 'Add Site',

                'image' => $_FILES['image'],
                'image_name' => time(). '_' . $_FILES['image']['name'],

                'site_name' => $this->sanitizeInput($_POST['site_name'] ?? ''),
                'site_address' => $this->sanitizeInput($_POST['site_address'] ?? ''),
                'district' => $this->sanitizeInput($_POST['district'] ?? ''),
                'site_city' => $this->sanitizeInput($_POST['city'] ?? ''),
                'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
                'latitude' => $this->sanitizeInput($_POST['latitude'] ?? ''),
                'longitude' => $this->sanitizeInput($_POST['longitude'] ?? ''),

                'image_err' => '',
                'site_name_err' => '',
                'site_address_err' => '',
                'district_err' => '',
                'site_city_err' => '',
                'phone_number_err' => '',
            ];

            // Validate form
            if(empty($data['image']['name'])){
                $data['image_err'] = 'Please upload an image';
            } elseif($data['image']['size'] > 0){
                if(uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/siteImages/')){
                    // Image uploaded successfully
                } else {
                    $data['image_err'] = 'Failed to upload image';
                }
            }

            if(empty($data['site_name'])){
                $data['site_name_err'] = 'Please enter site name';
            }

            if(empty($data['site_address'])){
                $data['site_address_err'] = 'Please enter site address';
            }

            if(empty($data['district'])){
                $data['district_err'] = 'Please enter district';
            }

            if(empty($data['site_city'])){
                $data['site_city_err'] = 'Please enter site city';
            }

            if(empty($data['phone_number'])){
                $data['phone_number_err'] = 'Please enter phone number';
            } elseif(!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])){
                $data['phone_number_err'] = 'Please enter a valid phone number (10-15 digits)';
            }

            // Make sure there are no errors
            if(empty($data['image_err']) && 
            empty($data['site_name_err']) && 
            empty($data['site_address_err']) && 
            empty($data['district_err']) && 
            empty($data['site_city_err']) && 
            empty($data['phone_number_err'])){

                // Insert site and get the new site ID
            $siteId = $this->adminModel->addSite($data);
                
                if($siteId){
                    // Add activity log
                    $title = "New Site Added";
                    $description = "Site '" . $data['site_name'] . "' added for client ID: " . $Id;
                    $type = "shift";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    
                    // Notify the client about the new site
                    $notificationTitle = "New Site Added";
                    $notificationMessage = "A new site '" . $data['site_name'] . "' has been added to your account.";
                    $notificationLink = "client/viewsite/" . $siteId;
                    $this->notificationModel->addNotification(
                        $Id, // client's user_id
                        'info',
                        $notificationTitle,
                        $notificationMessage,
                        $notificationLink,
                        'business',
                        $_SESSION['user_id'] // admin's user_id as the sender
                    );
                    
                    flash('msg', 'Site added successfully', 'alert-success');
                    redirect('admin/viewsites/'.$siteId); // Redirect properly
                } else {
                    flash('msg', 'Failed to add site', 'alert-danger');
                    $this->view('admin/clients/v_addSite',$data);
                }
            } else {
                $this->view('admin/clients/v_addSite',$data);
            }

        } else {
            $data = [
                'client_id' => $Id, // Add client_id here too
                'title' => 'Clients',
                'pageTitle' => 'Add Site',

                'image' => '', 
                'image_name' => '',

                'site_name' => '',
                'site_address' => '',
                'district' => '',
                'site_city' => '',
                'phone_number' => '',
                'latitude' => '',
                'longitude' => '',

                'image_err' => '',
                'site_name_err' => '',
                'site_address_err' => '',
                'district_err' => '',
                'site_city_err' => '',
                'phone_number_err' => '',
            ];
            
            $this->view('admin/clients/v_addSite', $data);
        }
    }

    public function viewsites($site_id){
        $site = $this->adminModel->getSiteById($site_id);

        if (!$site) {
            flash('msg', 'Site not found', 'alert-danger');
            redirect('admin/clients');
            return;
        }

        $clients = $this->adminModel->getClientById($site->client_id);
        if (!$clients) {
            // If Clients record not found, the sites.client_id may actually be a Users.id
            $user = $this->userModel->getUserById($site->client_id);
            if ($user) {
                $clients = (object) [
                    'name' => $user->name,
                    'id' => $user->id,
                    'client_profile' => $user->profile_image ?? '',
                    'phone_number' => $user->phone_number ?? '',
                    'email' => $user->email ?? '',
                    'contact_person_name' => $user->contact_person_name ?? ''
                ];
            } else {
                // Generic fallback
                $clients = (object) ['name' => 'Unknown Client', 'id' => $site->client_id, 'client_profile' => ''];
            }
        } else {
            // Ensure client_profile exists (from Users.profile_image)
            if (empty($clients->client_profile)) {
                $user = $this->userModel->getUserById($clients->user_id ?? $clients->id);
                $clients->client_profile = $user->profile_image ?? '';
            }
        }
        $assignedOfficers = $this->adminModel->getAssignedOfficers($site_id);              
        $packageRequest = $this->adminModel->getPackageRequestBySiteId($site_id);

        $assignedCaretakers = $this->adminModel->getAssignedCaretakers($site_id);
        
        $data = [
            'title' => 'Clients',
            'pageTitle' => $clients->name . ' - ' . $site->site_name,
            'site' => $site,
            'client' => $clients,
            'assigned_officers' => $assignedOfficers,
            'package_request' => $packageRequest,
            'assigned_caretakers' => $assignedCaretakers
        ];
        $this->view('admin/clients/v_viewsites', $data);
    }
public function editSite($site_id){
    // First get the existing site data
    $existingSite = $this->adminModel->getSiteById($site_id);
    
    if(!$existingSite) {
        flash('msg', 'Site not found', 'alert-danger');
        redirect('admin/clients');
        return;
    }
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $data = [
            'site_id' => $site_id, // Important: include site_id for update
            'client_id' => $existingSite->client_id, // Use existing client_id
            'title' => 'Clients',
            'pageTitle' => 'Edit Site',

            'image' => $_FILES['image'],
            'image_name' => time(). '_' . $_FILES['image']['name'],
            'current_image' => $existingSite->image, // Store current image

            'site_name' => $this->sanitizeInput($_POST['site_name'] ?? ''),
            'site_address' => $this->sanitizeInput($_POST['site_address'] ?? ''),
            'site_city' => $this->sanitizeInput($_POST['site_city'] ?? ''),
            'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
            'latitude' => $this->sanitizeInput($_POST['latitude'] ?? ''),
            'longitude' => $this->sanitizeInput($_POST['longitude'] ?? ''),

            'image_err' => '',
            'site_name_err' => '',
            'site_address_err' => '',
            'site_city_err' => '',
            'phone_number_err' => '',
        ];

        // Validation
        if(empty($data['site_name'])){
            $data['site_name_err'] = 'Please enter site name';
        }

        if(empty($data['site_address'])){
            $data['site_address_err'] = 'Please enter site address';
        }

        if(empty($data['site_city'])){
            $data['site_city_err'] = 'Please enter site city';
        }

        if(empty($data['phone_number'])){
            $data['phone_number_err'] = 'Please enter phone number';
        } elseif(!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])){
            $data['phone_number_err'] = 'Please enter a valid phone number (10-15 digits)';
        }

        // Handle image upload (optional for edit)
        // Check if new image was uploaded
        if($data['image']['size'] > 0){
            if(uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/siteImages/')){
                // Image uploaded successfully
                // Delete old image if it exists
                if(!empty($existingSite->image)) {
                    $oldImagePath = PUB_ROOT . '/uploads/siteImages/' . $existingSite->image;
                    if(file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
            } else {
                $data['image_err'] = 'Failed to upload image';
            }
        } else {
            // Keep the current image
            $data['image_name'] = $existingSite->image;
        }

        // Check for errors
        if(empty($data['site_name_err']) && 
           empty($data['site_address_err']) && 
           empty($data['site_city_err']) && 
           empty($data['phone_number_err']) &&
           empty($data['image_err'])) {

            // Update site - use editSite method in model
            if($this->adminModel->updateSite($data)){
                // Add activity log
                $title = "Site Updated";
                $description = "Site '" . $data['site_name'] . "' (ID: " . $site_id . ") was updated";
                $type = "update";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                flash('msg', 'Site updated successfully', 'alert-success');
                redirect('admin/viewsites/'.$site_id);
            } else {
                flash('msg', 'Failed to update site', 'alert-danger');
                $this->view('admin/clients/v_editSite', $data);
            }
        } else {
            $this->view('admin/clients/v_editSite', $data);
        }

    } else {
        // Load existing data into form - FIX FIELD NAMES HERE
        $data = [
            'site_id' => $site_id,
            'client_id' => $existingSite->client_id,
            'title' => 'Clients',
            'pageTitle' => 'Edit Site',

            'image' => '', 
            'image_name' => $existingSite->image,
            'current_image' => $existingSite->image,

            'site_name' => $existingSite->site_name,
            'site_address' => $existingSite->address, // Changed from address
            'site_city' => $existingSite->city,       // Changed from city
            'phone_number' => $existingSite->phone_number,
            'latitude' => $existingSite->latitude ?? '',
            'longitude' => $existingSite->longitude ?? '',

            'image_err' => '',
            'site_name_err' => '',
            'site_address_err' => '',
            'site_city_err' => '',
            'phone_number_err' => '',
        ];
        
        $this->view('admin/clients/v_editSite', $data);
    }
}
    public function deleteSite($siteId){
    
    // First get the site to get client_id before deleting
    $site = $this->adminModel->getSiteById($siteId);
    
    if(!$site) {
        flash('msg', 'Site not found', 'alert-danger');
        redirect('admin/sites');
        return;
    }
    
    $clientId = $site->client_id;
    $imagePath = PUB_ROOT . '/uploads/siteImages/' . $site->image;
    deleteImage($imagePath);
    
    if($this->adminModel->deleteSite($siteId)){
        // Add activity log
        $title = "Site Deleted";
        $description = "Site '" . $site->site_name . "' (ID: " . $siteId . ") was deleted";
        $type = "alert";
        $this->adminModel->insertRecentActivity($title, $description, $type);
        
        flash('msg', 'Site deleted successfully', 'alert-success');
        redirect('admin/clientprofile/' . $clientId);
    } else {
        flash('msg', 'Failed to delete site', 'alert-danger');
        redirect('admin/clientprofile/' . $clientId);
    }
}
    

    public function editassignment(){
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Edit Assignment'
        ];
        $this->view('admin/clients/v_editAssignment', $data);
    }

    public function adddutypoint(){
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Duty Point'
        ];
        $this->view('admin/clients/v_addDutyPoint', $data);
    }

    public function clientRequests() {
        // Handle approve/reject actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Handle package request approval
            if (isset($_POST['approve_package_request'])) {
                $requestId = $_POST['request_id'];
                $adminId = $_SESSION['user_id'];
                $notes = trim($_POST['admin_notes'] ?? '');
                
                // Approve the package request and create site
                $result = $this->adminModel->approvePackageRequest($requestId, $adminId, $notes);
                
                if ($result && is_numeric($result)) {
                    // Site created successfully, redirect to it
                    flash('request_success', 'Package request approved and site created successfully', 'alert-success');
                    redirect('admin/viewsites/' . $result);
                    exit();
                } elseif ($result) {
                    // Approved but site creation failed
                    flash('request_success', 'Package request approved', 'alert-success');
                    redirect('admin/clientRequests');
                    exit();
                } else {
                    // Approval failed
                    flash('request_error', 'Failed to approve package request', 'alert-danger');
                    redirect('admin/clientRequests');
                    exit();
                }
            }
            
            // Handle package request rejection
            if (isset($_POST['reject_package_request'])) {
                $requestId = $_POST['request_id'];
                $adminId = $_SESSION['user_id'];
                $reason = trim($_POST['rejection_reason'] ?? '');
                
                if ($this->adminModel->rejectPackageRequest($requestId, $adminId, $reason)) {
                    flash('request_success', 'Package request rejected', 'alert-success');
                } else {
                    flash('request_error', 'Failed to reject package request', 'alert-danger');
                }
                redirect('admin/clientRequests');
                exit();
            }
            
            // Handle old service request approval
            if (isset($_POST['approve_request'])) {
                $requestId = $_POST['request_id'];
                if ($this->adminModel->updateServiceRequestStatus($requestId, 'Approved')) {
                    $title = "Service Request Approved";
                    $description = "Service request #" . $requestId . " was approved";
                    $type = "update";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    flash('request_success', 'Service request approved successfully');
                } else {
                    flash('request_error', 'Failed to approve service request');
                }
                redirect('admin/clientRequests');
                exit();
            }
            
            // Handle old service request rejection
            if (isset($_POST['reject_request'])) {
                $requestId = $_POST['request_id'];
                if ($this->adminModel->updateServiceRequestStatus($requestId, 'Rejected')) {
                    $title = "Service Request Rejected";
                    $description = "Service request #" . $requestId . " was rejected";
                    $type = "alert";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    flash('request_success', 'Service request rejected');
                } else {
                    flash('request_error', 'Failed to reject service request');
                }
                redirect('admin/clientRequests');
                exit();
            }
        }

        // Get all service requests (old system)
        $serviceRequests = $this->adminModel->getAllServiceRequests();
        $requestStats = $this->adminModel->getServiceRequestStats();
        
        // Get all package requests (new system)
        $packageRequests = $this->adminModel->getAllPackageRequests();
        $packageStats = $this->adminModel->getPackageRequestStats();

        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Client Service Requests',
            'serviceRequests' => $serviceRequests,
            'requestStats' => $requestStats,
            'packageRequests' => $packageRequests,
            'packageStats' => $packageStats
        ];
        
        $this->view('admin/v_client_requests', $data);
    }

    // Review package request - create draft site and redirect to assignment
    public function reviewPackageRequest($requestId) {
        if (!$requestId) {
            flash('request_error', 'Invalid request');
            redirect('admin/clientRequests');
            return;
        }

        // Get package request
        $packageRequest = $this->adminModel->getPackageRequestById($requestId);
        
        if (!$packageRequest) {
            flash('request_error', 'Package request not found or payment is not completed yet');
            redirect('admin/clientRequests');
            return;
        }

        // Check if draft site already exists
        if ($packageRequest->draft_site_id) {
            // Draft already exists, redirect to it
            redirect('admin/viewsites/' . $packageRequest->draft_site_id);
            return;
        }

        // Create draft site
        $draftSiteId = $this->adminModel->createDraftSite($packageRequest);
        
        if (!$draftSiteId) {
            flash('request_error', 'Failed to create draft site');
            redirect('admin/clientRequests');
            return;
        }

        // Link draft site to package request
        $this->adminModel->linkDraftSiteToRequest($requestId, $draftSiteId);

        // Redirect to viewsites page for officer assignment
        flash('site_success', 'Draft site created. Assign exactly ' . $packageRequest->number_of_guards . ' officer(s) to continue.');
        redirect('admin/viewsites/' . $draftSiteId);
    }

    // Approve draft site - finalize and approve package request
    public function approveDraftSite($siteId) {
        $site = $this->adminModel->getSiteById($siteId);
        
        if (!$site || $site->is_draft != 1) {
            flash('request_error', 'Invalid draft site');
            redirect('admin/clientRequests');
            return;
        }

        // Get package request
        $packageRequest = $this->adminModel->getPackageRequestBySiteId($siteId);
        
        if (!$packageRequest) {
            flash('request_error', 'Package request not found');
            redirect('admin/clientRequests');
            return;
        }

        // Verify correct number of regular officers assigned (excluding supervisors).
        $assignedOfficers = $this->adminModel->getAssignedRegularOfficerCount($siteId);
        $requiredOfficers = (int)$packageRequest->number_of_guards;

        if ($assignedOfficers != $requiredOfficers) {
            flash('site_error', 'You must assign exactly ' . $requiredOfficers . ' officer(s). Currently assigned: ' . $assignedOfficers);
            redirect('admin/viewsites/' . $siteId);
            return;
        }

        // Policy: minimum 1 supervisor per 5 officers (rounded up).
        $requiredSupervisors = $requiredOfficers > 0 ? (int)ceil($requiredOfficers / 5) : 0;
        $assignedSupervisors = $this->adminModel->getAssignedSupervisorCount($siteId);

        if ($assignedSupervisors < $requiredSupervisors) {
            flash('site_error', 'Supervisor requirement not met. Required: ' . $requiredSupervisors . ', currently assigned: ' . $assignedSupervisors . '.');
            redirect('admin/viewsites/' . $siteId);
            return;
        }

        // Finalize the draft site (make it official)
        if ($this->adminModel->finalizeDraftSite($siteId)) {
            // Update package request to Approved
            $this->adminModel->approvePackageRequestFinal($packageRequest->id, $_SESSION['user_id']);
            
            flash('request_success', 'Package request approved and site created successfully');
            redirect('admin/viewsites/' . $siteId);
        } else {
            flash('request_error', 'Failed to approve site');
            redirect('admin/viewsites/' . $siteId);
        }
    }

    // Reject draft site - delete draft and reject package request
    public function rejectDraftSite($siteId) {
        $site = $this->adminModel->getSiteById($siteId);
        
        if (!$site || $site->is_draft != 1) {
            flash('request_error', 'Invalid draft site');
            redirect('admin/clientRequests');
            return;
        }

        // Get package request
        $packageRequest = $this->adminModel->getPackageRequestBySiteId($siteId);
        
        if (!$packageRequest) {
            flash('request_error', 'Package request not found');
            redirect('admin/clientRequests');
            return;
        }

        // Delete draft site
        if ($this->adminModel->deleteDraftSite($siteId)) {
            // Update package request to Rejected
            $this->adminModel->rejectPackageRequestFinal($packageRequest->id, $_SESSION['user_id']);
            
            flash('request_success', 'Package request rejected and draft site deleted');
            redirect('admin/clientRequests');
        } else {
            flash('request_error', 'Failed to reject request');
            redirect('admin/clientRequests');
        }
    }

    // View all available packages
    public function viewPackages() {
        // Fetch all packages from database (including inactive ones for admin)
        $packages = $this->packageModel->getAllPackagesForAdmin();
        
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Available Security Packages',
            'packages' => $packages
        ];
        
        $this->view('admin/clients/v_packages', $data);
    }

    // Create new package
    public function createPackage() {
        // Get Custom Package pricing to pass to the view
        $customPricing = $this->packageModel->getCustomPackagePricing();
        
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Create New Security Package',
            'customPricing' => $customPricing
        ];
        
        $this->view('admin/clients/v_create_packages', $data);
    }

    // Save new package
    public function savePackage() {
        // Check if POST request
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('admin/viewPackages');
        }

        // Load image upload helper
        require_once APP_ROOT . '/helpers/image_upload_helper.php';

        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Init data
        $data = [
            'package_name' => trim($_POST['package_name']),
            'description' => trim($_POST['description'] ?? ''),
            'number_of_officers' => intval($_POST['number_of_officers']),
            'number_of_supervisors' => intval($_POST['number_of_supervisors'] ?? 0),
            'number_of_caretakers' => intval($_POST['number_of_caretakers'] ?? 0),
            'package_price' => floatval($_POST['package_price']),
            'price_per_officer' => floatval($_POST['price_per_officer'] ?? 0),
            'price_per_supervisor' => floatval($_POST['price_per_supervisor'] ?? 0),
            'price_per_caretaker' => floatval($_POST['price_per_caretaker'] ?? 0),
            'background_image' => null,
            'status' => 'Active',
            'created_by' => $_SESSION['user_id'],
            'package_name_err' => '',
            'number_of_officers_err' => '',
            'package_price_err' => '',
            'image_err' => ''
        ];

        // Auto-calculate price for non-custom packages
        if (strcasecmp($data['package_name'], 'Custom Package') !== 0) {
            // Get Custom Package pricing and calculate total
            $calculatedPrice = $this->packageModel->calculatePackagePrice(
                $data['number_of_officers'],
                $data['number_of_supervisors'],
                $data['number_of_caretakers']
            );
            $data['package_price'] = $calculatedPrice;
            
            // Get Custom Package unit prices for storing
            $customPricing = $this->packageModel->getCustomPackagePricing();
            $data['price_per_officer'] = $customPricing['price_per_officer'];
            $data['price_per_supervisor'] = $customPricing['price_per_supervisor'];
            $data['price_per_caretaker'] = $customPricing['price_per_caretaker'];
        }

        // Validate package name
        if (empty($data['package_name'])) {
            $data['package_name_err'] = 'Please enter a package name';
        } elseif ($this->packageModel->packageNameExists($data['package_name'])) {
            $data['package_name_err'] = 'Package name already exists. Please choose a different name';
        }

        // Validate number of officers
        if ($data['number_of_officers'] < 0) {
            $data['number_of_officers_err'] = 'Number of officers cannot be negative';
        }

        // Validate package price
        if ($data['package_price'] < 0) {
            $data['package_price_err'] = 'Package price cannot be negative';
        }

        // Handle image upload
        if (!empty($_FILES['package_image']['name'])) {
            $uploadDir = 'uploads/packages/';
            
            // Create directory if it doesn't exist
            if (!file_exists(PUB_ROOT . '/' . $uploadDir)) {
                mkdir(PUB_ROOT . '/' . $uploadDir, 0777, true);
            }

            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $maxFileSize = 5 * 1024 * 1024; // 5MB

            $fileType = $_FILES['package_image']['type'];
            $fileSize = $_FILES['package_image']['size'];
            $tmpName = $_FILES['package_image']['tmp_name'];

            // Validate file type and size
            if (!in_array($fileType, $allowedTypes)) {
                $data['image_err'] = 'Invalid file type. Only JPG, PNG, and GIF images are allowed';
            } elseif ($fileSize > $maxFileSize) {
                $data['image_err'] = 'File size exceeds 5MB limit';
            } else {
                // Generate unique filename
                $extension = pathinfo($_FILES['package_image']['name'], PATHINFO_EXTENSION);
                $fileName = 'package_' . uniqid() . '.' . $extension;
                
                // Upload image using helper
                if (uploadImage($tmpName, $fileName, '/' . $uploadDir)) {
                    $data['background_image'] = $fileName;
                } else {
                    $data['image_err'] = 'Failed to upload image';
                }
            }
        }

        // Make sure no errors
        if (empty($data['package_name_err']) && empty($data['number_of_officers_err']) && 
            empty($data['package_price_err']) && empty($data['image_err'])) {
            
            // Create package
            if ($this->packageModel->createPackage($data)) {
                // Add activity log
                $title = "Package Created";
                $description = "New security package '" . $data['package_name'] . "' created with price: LKR " . number_format($data['package_price'], 2);
                $type = "update";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                flash('msg', 'Package Created Successfully', 'alert-success');
                redirect('admin/viewPackages');
            } else {
                flash('msg', 'Failed to Create Package', 'alert-danger');
                redirect('admin/viewPackages');
            }
        } else {
            // Load view with errors
            $viewData = [
                'title' => 'Clients',
                'pageTitle' => 'Create New Security Package',
                'data' => $data
            ];
            $this->view('admin/clients/v_create_packages', $viewData);
        }
    }

    // Edit package
    public function editPackage($id) {
        // Get package by ID
        $package = $this->packageModel->getPackageById($id);
        
        if (!$package) {
            flash('msg', 'Package Not Found', 'alert-danger');
            redirect('admin/viewPackages');
        }
        
        // Get Custom Package pricing to pass to the view
        $customPricing = $this->packageModel->getCustomPackagePricing();
        
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Edit Security Package',
            'package' => $package,
            'customPricing' => $customPricing
        ];
        
        $this->view('admin/clients/v_edit_package', $data);
    }

    // Update package
    public function updatePackage() {
        // Check if POST request
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('admin/viewPackages');
        }

        // Load image upload helper
        require_once APP_ROOT . '/helpers/image_upload_helper.php';

        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Init data
        $data = [
            'id' => intval($_POST['package_id']),
            'package_name' => trim($_POST['package_name']),
            'description' => trim($_POST['description'] ?? ''),
            'number_of_officers' => intval($_POST['number_of_officers']),
            'number_of_supervisors' => intval($_POST['number_of_supervisors'] ?? 0),
            'number_of_caretakers' => intval($_POST['number_of_caretakers'] ?? 0),
            'package_price' => floatval($_POST['package_price']),
            'price_per_officer' => floatval($_POST['price_per_officer'] ?? 0),
            'price_per_supervisor' => floatval($_POST['price_per_supervisor'] ?? 0),
            'price_per_caretaker' => floatval($_POST['price_per_caretaker'] ?? 0),
            'background_image' => $_POST['current_image'] ?? null,
            'status' => 'Active',
            'package_name_err' => '',
            'number_of_officers_err' => '',
            'package_price_err' => '',
            'image_err' => ''
        ];

        // Auto-calculate price for non-custom packages
        if (strcasecmp($data['package_name'], 'Custom Package') !== 0) {
            // Get Custom Package pricing and calculate total
            $calculatedPrice = $this->packageModel->calculatePackagePrice(
                $data['number_of_officers'],
                $data['number_of_supervisors'],
                $data['number_of_caretakers']
            );
            $data['package_price'] = $calculatedPrice;
            
            // Get Custom Package unit prices for storing
            $customPricing = $this->packageModel->getCustomPackagePricing();
            $data['price_per_officer'] = $customPricing['price_per_officer'];
            $data['price_per_supervisor'] = $customPricing['price_per_supervisor'];
            $data['price_per_caretaker'] = $customPricing['price_per_caretaker'];
        }

        // Validate package name
        if (empty($data['package_name'])) {
            $data['package_name_err'] = 'Please enter a package name';
        } elseif ($this->packageModel->packageNameExists($data['package_name'], $data['id'])) {
            $data['package_name_err'] = 'Package name already exists. Please choose a different name';
        }

        // Validate number of officers
        if ($data['number_of_officers'] < 0) {
            $data['number_of_officers_err'] = 'Number of officers cannot be negative';
        }

        // Validate package price
        if ($data['package_price'] < 0) {
            $data['package_price_err'] = 'Package price cannot be negative';
        }

        // Handle image upload
        if (!empty($_FILES['package_image']['name'])) {
            $uploadDir = 'uploads/packages/';
            
            // Create directory if it doesn't exist
            if (!file_exists(PUB_ROOT . '/' . $uploadDir)) {
                mkdir(PUB_ROOT . '/' . $uploadDir, 0777, true);
            }

            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $maxFileSize = 5 * 1024 * 1024; // 5MB

            $fileType = $_FILES['package_image']['type'];
            $fileSize = $_FILES['package_image']['size'];
            $tmpName = $_FILES['package_image']['tmp_name'];

            // Validate file type and size
            if (!in_array($fileType, $allowedTypes)) {
                $data['image_err'] = 'Invalid file type. Only JPG, PNG, and GIF images are allowed';
            } elseif ($fileSize > $maxFileSize) {
                $data['image_err'] = 'File size exceeds 5MB limit';
            } else {
                // Generate unique filename
                $extension = pathinfo($_FILES['package_image']['name'], PATHINFO_EXTENSION);
                $fileName = 'package_' . uniqid() . '.' . $extension;
                
                // Delete old image if exists
                if (!empty($data['background_image'])) {
                    $oldImagePath = PUB_ROOT . '/' . $uploadDir . $data['background_image'];
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
                
                // Upload new image using helper
                if (uploadImage($tmpName, $fileName, '/' . $uploadDir)) {
                    $data['background_image'] = $fileName;
                } else {
                    $data['image_err'] = 'Failed to upload image';
                }
            }
        }

        // Make sure no errors
        if (empty($data['package_name_err']) && empty($data['number_of_officers_err']) && 
            empty($data['package_price_err']) && empty($data['image_err'])) {
            
            // Update package
            if ($this->packageModel->updatePackage($data)) {
                // If Custom Package was updated, recalculate all other package prices
                if (strcasecmp($data['package_name'], 'Custom Package') === 0) {
                    $updatedCount = $this->packageModel->updateAllPackagePrices();
                    
                    // Add activity log for Custom Package update
                    $title = "Custom Package Updated";
                    $description = "Custom Package unit prices updated. " . $updatedCount . " package(s) automatically recalculated.";
                    $type = "update";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    
                    flash('msg', 'Custom Package Updated Successfully! ' . $updatedCount . ' package(s) automatically recalculated.', 'alert-success');
                } else {
                    // Add activity log for regular package
                    $title = "Package Updated";
                    $description = "Security package '" . $data['package_name'] . "' (ID: " . $data['id'] . ") was updated";
                    $type = "update";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    
                    flash('msg', 'Package Updated Successfully', 'alert-success');
                }
                
                redirect('admin/viewPackages');
            } else {
                flash('msg', 'Failed to Update Package', 'alert-danger');
                redirect('admin/viewPackages');
            }
        } else {
            // Get package data for the view
            $package = $this->packageModel->getPackageById($data['id']);
            
            // Load view with errors
            $viewData = [
                'title' => 'Clients',
                'pageTitle' => 'Edit Security Package',
                'package' => $package,
                'data' => $data
            ];
            $this->view('admin/clients/v_edit_package', $viewData);
        }
    }

    // Delete package
    public function deletePackage($id) {
        // Load image upload helper
        require_once APP_ROOT . '/helpers/image_upload_helper.php';
        
        // Get package by ID
        $package = $this->packageModel->getPackageById($id);
        
        if (!$package) {
            flash('msg', 'Package Not Found', 'alert-danger');
            redirect('admin/viewPackages');
        }
        
        // Check if it's a default package (cannot be deleted)
        if (isset($package->is_default) && $package->is_default == 1) {
            flash('msg', 'Cannot Delete Default Package. Default packages are system-protected and can only be edited.', 'alert-danger');
            redirect('admin/viewPackages');
            return;
        }
        
        // Delete package image if exists
        if (!empty($package->background_image)) {
            $imagePath = PUB_ROOT . '/uploads/packages/' . $package->background_image;
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }
        
        // Delete package from database
        if ($this->packageModel->deletePackage($id)) {
            // Add activity log
            $title = "Package Deleted";
            $description = "Security package '" . $package->package_name . "' (ID: " . $id . ") was deleted";
            $type = "alert";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            flash('msg', 'Package Deleted Successfully', 'alert-success');
        } else {
            flash('msg', 'Failed to Delete Package', 'alert-danger');
        }
        
        redirect('admin/viewPackages');
    }

    /**
     * Toggle package status (Active/Inactive)
     */
    public function togglePackageStatus($id, $newStatus) {
        // Validate status
        if (!in_array($newStatus, ['Active', 'Inactive'])) {
            flash('msg', 'Invalid Status', 'alert-danger');
            redirect('admin/viewPackages');
            return;
        }
        
        // Get package by ID
        $package = $this->packageModel->getPackageById($id);
        
        if (!$package) {
            flash('msg', 'Package Not Found', 'alert-danger');
            redirect('admin/viewPackages');
            return;
        }
        
        // Update package status
        if ($this->packageModel->updatePackageStatus($id, $newStatus)) {
            // Add activity log
            $statusText = $newStatus === 'Active' ? 'activated' : 'deactivated';
            $title = "Package Status Changed";
            $description = "Security package '" . $package->package_name . "' was " . $statusText;
            $type = "update";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            $message = $newStatus === 'Active' 
                ? 'Package Activated Successfully. It is now available for clients.' 
                : 'Package Deactivated Successfully. It is no longer available for clients.';
            flash('msg', $message, 'alert-success');
        } else {
            flash('msg', 'Failed to Update Package Status', 'alert-danger');
        }
        
        redirect('admin/viewPackages');
    }

// ======================================================================== //
// =======================      Admin Scheduling       ====================== //
// ======================================================================== //

    public function scheduling() {
        $data = [
            'title' => 'Scheduling',
            'pageTitle' => 'Manage Scheduling'
        ];
        $this->view('admin/v_scheduling', $data);
    }

// ======================================================================== //
// =======================      Admin Routes       ====================== //
// ======================================================================== //

    public function routes() {
        $routes = $this->adminModel->getAllRoutes();
        $allSites = $this->adminModel->getAllSites();
        $allMobileRiders = $this->adminModel->getAllMR();
        
        // Filter unassigned sites
        $unassignedSites = [];
        foreach ($allSites as $site) {
            // Check if site is not assigned to any route
            $isAssigned = $this->adminModel->isSiteAssignedToRoute($site->id);
            if (!$isAssigned) {
                $unassignedSites[] = $site;
            }
        }
        
        // Add matching routes to each unassigned site
        $sitesWithMatches = getSitesWithMatchingRoutes($unassignedSites, $routes);
        
        // Calculate stats
        $totalRiders = count($allMobileRiders);
        $totalRoutes = count($routes);
        $totalSites = count($allSites);
        $unassignedSitesCount = count($unassignedSites);
        
        $data = [
            'title' => 'Routes',
            'pageTitle' => 'Manage Routings',
            'routes' => $routes,
            'sites' => $sitesWithMatches,
            'totalRiders' => $totalRiders,
            'totalRoutes' => $totalRoutes,
            'totalSites' => $totalSites,
            'unassignedSitesCount' => $unassignedSitesCount
        ];
        $this->view('admin/routes/v_routes', $data);
    }

    public function addroute() {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $data = [
                'route_name' => trim($_POST['route_name']),
                'description' => trim($_POST['description']),
                'location' => trim($_POST['location']),
            ];

            // Validation
            if(empty($data['route_name'])){
                $data['route_name_err'] = 'Please enter route name';
            }

            if(empty($data['route_name_err'])){
                $route_id = $this->adminModel->generateRouteId();
                $created_by = $_SESSION['user_id'];

                $routeData = [
                    'id' => $route_id,
                    'route_name' => $data['route_name'],
                    'description' => $data['description'],
                    'location' => $data['location'],
                    'status' => 'Active',
                    'created_by' => $created_by
                ];

                if($this->adminModel->insertRoute($routeData)){
                    // Add activity log
                    $title = "Route Created";
                    $description = "New route '" . $data['route_name'] . "' (ID: " . $route_id . ") was created";
                    $type = "success";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    
                    flash('msg', 'Route added successfully', 'alert-success');
                    redirect('admin/routes');
                } else {
                    flash('msg', 'Failed to add route', 'alert-danger');
                }
            } else {
                // Get sites for the form - but now no sites
                $this->view('admin/routes/v_addRoute', $data);
            }
        } else {
            $data = [
                'title' => 'Routes',
                'pageTitle' => 'Add Route',
                'route_name' => '',
                'description' => '',
                'location' => '',
                'route_name_err' => ''
            ];

            $this->view('admin/routes/v_addRoute', $data);
        }
    }

    public function viewroute($id) {
        $route = $this->adminModel->getRouteById($id);
        $routeSites = $this->adminModel->getRouteSites($id);
        
        if (!$route) {
            flash('msg', 'Route not found', 'alert-danger');
            redirect('admin/routes');
            return;
        }
        
        // Get creator information
        $creator = $this->adminModel->getUserByID($route->created_by);
        
        // Get available mobile riders (excluding already assigned ones)
        $availableMobileRiders = $this->adminModel->getAvailableMR($id);
        $assignedRider = $this->adminModel->getRouteRider($id);
        
        $data = [
            'title' => 'Routes',
            'pageTitle' => 'View Route: ' . htmlspecialchars($route->route_name),
            'route' => $route,
            'routeSites' => $routeSites,
            'creator' => $creator,
            'mobileRiders' => $availableMobileRiders,
            'assignedRider' => $assignedRider
        ];
        $this->view('admin/routes/v_viewRoute', $data);
    }

    public function updateRouteLocation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeId = trim($_POST['route_id']);
            $location = trim($_POST['location']);

            // Validate input
            if (empty($routeId) || empty($location)) {
                echo json_encode(['success' => false, 'message' => 'Route ID and location are required']);
                return;
            }

            // Validate JSON format
            $coordinates = json_decode($location, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'message' => 'Invalid location data format']);
                return;
            }

            // Validate coordinates array
            if (!is_array($coordinates) || count($coordinates) < 3) {
                echo json_encode(['success' => false, 'message' => 'Location must contain at least 3 coordinate points']);
                return;
            }

            // Update route location in database
            $result = $this->adminModel->updateRouteLocation($routeId, $location);

            if ($result) {
                // Get route name for activity log
                $route = $this->adminModel->getRouteById($routeId);
                $routeName = $route ? $route->route_name : 'Route #' . $routeId;
                
                // Add activity log
                $title = "Route Location Updated";
                $description = "Location area for route '" . $routeName . "' was updated";
                $type = "info";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                echo json_encode(['success' => true, 'message' => 'Route location updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update route location']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function deleteRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeId = trim($_POST['route_id']);

            // Validate input
            if (empty($routeId)) {
                echo json_encode(['success' => false, 'message' => 'Route ID is required']);
                return;
            }

            // Get route name before deletion for activity log
            $route = $this->adminModel->getRouteById($routeId);
            $routeName = $route ? $route->route_name : 'Route #' . $routeId;
            
            // Delete route
            $result = $this->adminModel->deleteRoute($routeId);

            if ($result) {
                // Add activity log
                $title = "Route Deleted";
                $description = "Route '" . $routeName . "' (ID: " . $routeId . ") was permanently deleted";
                $type = "alert";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                echo json_encode(['success' => true, 'message' => 'Route deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete route']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    public function assignSiteToRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $siteId = isset($input['site_id']) ? trim($input['site_id']) : '';
            $routeId = isset($input['route_id']) ? trim($input['route_id']) : '';

            // Validate input
            if (empty($siteId) || empty($routeId)) {
                echo json_encode(['success' => false, 'message' => 'Site ID and Route ID are required']);
                return;
            }

            // Assign site to route
            $result = $this->adminModel->assignSiteToRoute($siteId, $routeId);

            if ($result) {
                // Get route and site names for activity log
                $route = $this->adminModel->getRouteById($routeId);
                $site = $this->adminModel->getSiteById($siteId);
                $routeName = $route ? $route->route_name : 'Route #' . $routeId;
                $siteName = $site ? $site->site_name : 'Site #' . $siteId;
                
                // Add activity log
                $title = "Site Assigned to Route";
                $description = "Site '" . $siteName . "' was assigned to route '" . $routeName . "'";
                $type = "success";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                echo json_encode(['success' => true, 'message' => 'Site assigned to route successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Site is already assigned to a route or assignment failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    public function assignRiderToRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $riderId = isset($input['rider_id']) ? trim($input['rider_id']) : '';
            $routeId = isset($input['route_id']) ? trim($input['route_id']) : '';

            // Validate input
            if (empty($riderId) || empty($routeId)) {
                echo json_encode(['success' => false, 'message' => 'Rider ID and Route ID are required', 'debug' => ['rider_id' => $riderId, 'route_id' => $routeId]]);
                return;
            }

            // Get route and rider names for activity log
            $route = $this->adminModel->getRouteById($routeId);
            $rider = $this->adminModel->getUserByID($riderId);
            
            if (!$route) {
                echo json_encode(['success' => false, 'message' => 'Route not found', 'debug' => ['route_id' => $routeId]]);
                return;
            }
            
            if (!$rider) {
                echo json_encode(['success' => false, 'message' => 'Rider not found', 'debug' => ['rider_id' => $riderId]]);
                return;
            }
            
            $routeName = $route->route_name;
            $riderName = $rider->name;

            // Assign rider to route
            $result = $this->adminModel->assignRiderToRoute($riderId, $routeId);

            if ($result) {
                // Add activity log
                $title = "Mobile Rider Assigned to Route";
                $description = "Mobile rider '" . $riderName . "' was assigned to route '" . $routeName . "'";
                $type = "success";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                echo json_encode(['success' => true, 'message' => 'Mobile rider assigned to route successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database update failed', 'debug' => ['rider_id' => $riderId, 'route_id' => $routeId]]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

// ======================================================================== //
// =======================      Admin Salary       ====================== //
// ======================================================================== //

    public function salary() {
        $data = [
            'title' => 'Salary',
            'pageTitle' => 'Manage Salary'];
        $this->view('admin/v_salary', $data);
    }

// ======================================================================== //
// =======================      Admin client payments       ================ //
// ======================================================================== //

    public function clients_payments() {
        // Load payment model
        $paymentModel = $this->model('M_payment');
        
        // Get all payments with client and site information
        $payments = $this->adminModel->getAllClientsPayments();
        
        // Calculate statistics
        $stats = $this->adminModel->getAllPaymentsStats();
        
        $data = [
            'title' => 'Salary',
            'pageTitle' => 'Clients Payments',
            'payments' => $payments,
            'stats' => $stats
        ];
        
        $this->view('admin/clients_payments/v_clients_payments', $data);
    }

    /**
     * View payment details
     */
    public function viewPaymentDetails($payment_id) {
        $payment = $this->adminModel->getPaymentDetailsById($payment_id);
        
        if (!$payment) {
            flash('payment_error', 'Payment not found');
            redirect('admin/clients_payments');
        }
        
        $data = [
            'title' => 'Payment Details',
            'pageTitle' => 'Payment Details',
            'payment' => $payment
        ];
        
        $this->view('admin/clients_payments/v_payment_details', $data);
    }

    /**
     * Edit payment
     */
    public function editPayment($payment_id) {
        $payment = $this->adminModel->getPaymentDetailsById($payment_id);
        
        if (!$payment) {
            flash('payment_error', 'Payment not found');
            redirect('admin/clients_payments');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'amount' => trim($_POST['amount']),
                'status' => trim($_POST['status']),
                'payment_date' => trim($_POST['payment_date']),
                'due_date' => trim($_POST['due_date']),
                'payment_method' => trim($_POST['payment_method'] ?? ''),
                'transaction_reference' => trim($_POST['transaction_reference'] ?? ''),
                'description' => trim($_POST['description'] ?? '')
            ];
            
            if ($this->adminModel->updatePayment($payment_id, $data)) {
                flash('payment_success', 'Payment updated successfully');
                redirect('admin/clients_payments');
            } else {
                flash('payment_error', 'Failed to update payment');
            }
        }
        
        $data = [
            'title' => 'Edit Payment',
            'pageTitle' => 'Edit Payment',
            'payment' => $payment
        ];
        
        $this->view('admin/clients_payments/v_edit_payment', $data);
    }

    /**
     * Download payment receipt
     */
    public function downloadPaymentReceipt($payment_id) {
        $payment = $this->adminModel->getPaymentDetailsById($payment_id);
        
        if (!$payment || $payment->status !== 'paid') {
            flash('payment_error', 'Receipt not available');
            redirect('admin/clients_payments');
        }
        
        // Generate PDF receipt (you'll need to implement PDF generation)
        // For now, redirect back with a message
        flash('payment_error', 'PDF generation not yet implemented');
        redirect('admin/clients_payments');
    }

    /**
     * Export payments to CSV
     */
    public function exportPayments() {
        $payments = $this->adminModel->getAllClientsPayments();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="client_payments_' . date('Y-m-d') . '.csv"');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, [
            'Invoice Number',
            'Client Name',
            'Client Email',
            'Site Name',
            'Amount',
            'Payment Date',
            'Due Date',
            'Status',
            'Payment Method',
            'Transaction Reference',
            'Description'
        ]);
        
        // Add data rows
        foreach ($payments as $payment) {
            fputcsv($output, [
                $payment->invoice_number,
                $payment->client_name ?? 'N/A',
                $payment->client_email ?? 'N/A',
                $payment->site_name ?? 'N/A',
                number_format($payment->amount, 2),
                $payment->payment_date ? date('Y-m-d', strtotime($payment->payment_date)) : '',
                $payment->due_date ? date('Y-m-d', strtotime($payment->due_date)) : '',
                $payment->status,
                $payment->payment_method ?? '',
                $payment->transaction_reference ?? '',
                $payment->description ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }


// ======================================================================== //
// =======================      Admin officer leave requests      ================ //
// ======================================================================== //
    // View leave request details
    public function viewLeaveRequest($id) {
    $leaveRequest = $this->adminModel->getLeaveRequestById($id);
    
    if (!$leaveRequest) {
        flash('leave_error', 'Leave request not found');
        redirect('admin/dashboard');
    }
    
    $data = [
        'title' => 'Dashboard',
        'pageTitle' => 'Leave Request Details',
        'leaveRequest' => $leaveRequest
    ];
    $this->view('admin/dashboard/v_leave_details', $data);
}

// Approve leave request
public function approveLeave($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $admin_id = $_SESSION['user_id'];
        
        if ($this->adminModel->approveLeaveRequest($id, $admin_id)) {
            // Get leave request details for notification
            $leaveRequest = $this->adminModel->getLeaveRequestById($id);
            
            if ($leaveRequest) {
                // Send notification to employee
                $this->notificationModel->insertNotification(
                    $leaveRequest->officer_id,
                    'leave',
                    'Leave Request Approved',
                    'Your leave request from ' . $leaveRequest->start_date . ' to ' . $leaveRequest->end_date . ' has been approved.',
                    '/supervisor/leaverequests',
                    'check_circle',
                    $admin_id
                );
            }
            
            flash('leave_success', 'Leave request approved successfully');
        } else {
            flash('leave_error', 'Failed to approve leave request');
        }
        
        redirect('admin/dashboard');
    }
}


// Reject leave request
public function rejectLeave($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $admin_id = $_SESSION['user_id'];
        $reason = trim($_POST['reason'] ?? '');
        
        if (empty($reason)) {
            flash('leave_error', 'Please provide a reason for rejection');
            redirect('admin/viewLeaveRequest/' . $id);
            return;
        }
        
        if ($this->adminModel->rejectLeaveRequest($id, $admin_id, $reason)) {
            // Get leave request details for notification
            $leaveRequest = $this->adminModel->getLeaveRequestById($id);
            
            if ($leaveRequest) {
                // Send notification to employee
                $this->notificationModel->insertNotification(
                    $leaveRequest->officer_id,
                    'alert',
                    'Leave Request Rejected',
                    'Your leave request has been rejected. Reason: ' . $reason,
                    '/supervisor/leaverequests',
                    'cancel',
                    $admin_id
                );
            }
            
            flash('leave_success', 'Leave request rejected');
        } else {
            flash('leave_error', 'Failed to reject leave request');
        }
        
        redirect('admin/dashboard');
    }
}

// Approve leave request (GET method for modal)
public function approveLeaveRequest($id) {
    if (!isset($_SESSION['user_id'])) {
        flash('leave_error', 'Unauthorized access');
        redirect('admin/dashboard');
        return;
    }
    
    $admin_id = $_SESSION['user_id'];
    
    if ($this->adminModel->approveLeaveRequest($id, $admin_id)) {
        // Get leave request details for notification
        $leaveRequest = $this->adminModel->getLeaveRequestById($id);
        
        if ($leaveRequest) {
            // Determine the correct officer_id and role based on request type
            $officer_id = null;
            $role = '';
            $redirectUrl = '';
            
            if (!empty($leaveRequest->caretaker_id)) {
                $officer_id = $leaveRequest->caretaker_id;
                $role = 'Caretaker';
                $redirectUrl = '/caretaker/leaverequests';
            } elseif (!empty($leaveRequest->supervisor_id)) {
                $officer_id = $leaveRequest->supervisor_id;
                $role = 'Supervisor';
                $redirectUrl = '/supervisor/leaverequests';
            } elseif (!empty($leaveRequest->mobilerider_id)) {
                $officer_id = $leaveRequest->mobilerider_id;
                $role = 'Mobile Rider';
                $redirectUrl = '/MobileRider/leaverequests';
            } elseif (!empty($leaveRequest->premiseofficer_id)) {
                $officer_id = $leaveRequest->premiseofficer_id;
                $role = 'Premise Officer';
                $redirectUrl = '/premiseOfficer/leaverequests';
            }
            
            // Send notification to employee
            if ($officer_id) {
                try {
                    $this->notificationModel->insertNotification(
                        $officer_id,
                        'success',
                        'Leave Request Approved',
                        'Your ' . $leaveRequest->leave_type . ' leave request from ' . $leaveRequest->start_date . ' to ' . $leaveRequest->end_date . ' has been approved.',
                        URL_ROOT . $redirectUrl,
                        'check_circle',
                        $admin_id
                    );
                    
                    // Log recent activity for the user
                    $userModel = $this->getUserModel($role);
                    if ($userModel && method_exists($userModel, 'insertRecentActivity')) {
                        $userModel->insertRecentActivity(
                            $officer_id,
                            'Leave Request Approved',
                            "Your {$leaveRequest->leave_type} leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date} was approved",
                            'leave_approved'
                        );
                    }
                    
                    // Log recent activity for admin
                    $user = $this->userModel->getUserById($officer_id);
                    $userName = $user->name ?? 'User';
                    $this->adminModel->insertRecentActivity(
                        "Leave Request Approved",
                        "Approved {$userName}'s ({$role}) {$leaveRequest->leave_type} leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date}",
                        'leave_approval',
                        $admin_id
                    );
                } catch (Exception $e) {
                    error_log("Error in leave approval notifications: " . $e->getMessage());
                }
            }
        }
        
        flash('leave_success', 'Leave request approved successfully');
    } else {
        flash('leave_error', 'Failed to approve leave request');
    }
    
    redirect('admin/pendings');
}

// Reject leave request (GET method for modal)
public function rejectLeaveRequest($id) {
    if (!isset($_SESSION['user_id'])) {
        flash('leave_error', 'Unauthorized access');
        redirect('admin/dashboard');
        return;
    }
    
    $admin_id = $_SESSION['user_id'];
    $reason = trim($_GET['reason'] ?? '');
    
    if (empty($reason)) {
        flash('leave_error', 'Please provide a reason for rejection');
        redirect('admin/pendings');
        return;
    }
    
    if ($this->adminModel->rejectLeaveRequest($id, $admin_id, $reason)) {
        // Get leave request details for notification
        $leaveRequest = $this->adminModel->getLeaveRequestById($id);
        
        if ($leaveRequest) {
            // Determine the correct officer_id and role based on request type
            $officer_id = null;
            $role = '';
            $redirectUrl = '';
            
            if (!empty($leaveRequest->caretaker_id)) {
                $officer_id = $leaveRequest->caretaker_id;
                $role = 'Caretaker';
                $redirectUrl = '/caretaker/leaverequests';
            } elseif (!empty($leaveRequest->supervisor_id)) {
                $officer_id = $leaveRequest->supervisor_id;
                $role = 'Supervisor';
                $redirectUrl = '/supervisor/leaverequests';
            } elseif (!empty($leaveRequest->mobilerider_id)) {
                $officer_id = $leaveRequest->mobilerider_id;
                $role = 'Mobile Rider';
                $redirectUrl = '/MobileRider/leaverequests';
            } elseif (!empty($leaveRequest->premiseofficer_id)) {
                $officer_id = $leaveRequest->premiseofficer_id;
                $role = 'Premise Officer';
                $redirectUrl = '/premiseOfficer/leaverequests';
            }
            
            // Send notification to employee
            if ($officer_id) {
                try {
                    $this->notificationModel->insertNotification(
                        $officer_id,
                        'warning',
                        'Leave Request Rejected',
                        'Your ' . $leaveRequest->leave_type . ' leave request from ' . $leaveRequest->start_date . ' to ' . $leaveRequest->end_date . ' was rejected. Reason: ' . $reason,
                        URL_ROOT . $redirectUrl,
                        'cancel',
                        $admin_id
                    );
                    
                    // Log recent activity for the user
                    $userModel = $this->getUserModel($role);
                    if ($userModel && method_exists($userModel, 'insertRecentActivity')) {
                        $userModel->insertRecentActivity(
                            $officer_id,
                            'Leave Request Rejected',
                            "Your {$leaveRequest->leave_type} leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date} was rejected",
                            'leave_rejected'
                        );
                    }
                    
                    // Log recent activity for admin
                    $user = $this->userModel->getUserById($officer_id);
                    $userName = $user->name ?? 'User';
                    $this->adminModel->insertRecentActivity(
                        "Leave Request Rejected",
                        "Rejected {$userName}'s ({$role}) {$leaveRequest->leave_type} leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date}. Reason: {$reason}",
                        'leave_rejection',
                        $admin_id
                    );
                } catch (Exception $e) {
                    error_log("Error in leave rejection notifications: " . $e->getMessage());
                }
            }
        }
        
        flash('leave_success', 'Leave request rejected');
    } else {
        flash('leave_error', 'Failed to reject leave request');
    }
    
    redirect('admin/pendings');
}

// ======================================================================== //
// =======================      Admin Advertisements       ====================== //
// ======================================================================== //

    public function advertisements() {
        $advertisements = $this->adminModel->getAdvertisements();
        $data = [
            'title' => 'Advertisements',
            'pageTitle' => 'Manage Advertisements',
            'advertisements' => $advertisements
        ];
        $this->view('admin/advertisements/v_advertisements', $data);
    }

        public function createAdvertisement() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission
            $data = [
                'title' => 'Advertisements',
                'pageTitle' => 'Create an Advertisement',
                'title_value' => trim($_POST['title'] ?? ''),
                'description_value' => trim($_POST['description'] ?? ''),
                'target_roles' => isset($_POST['target_roles']) ? $_POST['target_roles'] : [],
                'title_err' => '',
                'description_err' => '',
                'image_err' => '',
                'roles_err' => ''
            ];
            
            // Validate title
            if (empty($data['title_value'])) {
                $data['title_err'] = 'Please enter an advertisement title';
            }
            
            // Validate description
            if (empty($data['description_value'])) {
                $data['description_err'] = 'Please enter a description';
            }
            
            // Validate target roles
            if (empty($data['target_roles'])) {
                $data['roles_err'] = 'Please select at least one target role';
            }
            
            // Validate image upload
            if (!isset($_FILES['image']) || $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
                $data['image_err'] = 'Please upload an image';
            } elseif ($_FILES['image']['error'] != UPLOAD_ERR_OK) {
                $data['image_err'] = 'Error uploading image';
            } else {
                // Validate image file type
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $file_type = $_FILES['image']['type'];
                
                if (!in_array($file_type, $allowed_types)) {
                    $data['image_err'] = 'Only JPG, PNG, and GIF images are allowed';
                }
            }
            
            // If no errors, process the advertisement
            if (empty($data['title_err']) && empty($data['description_err']) && empty($data['image_err']) && empty($data['roles_err'])) {
                // Upload image
                $upload_dir = '/uploads/advertisements/';
                
                // Generate unique filename
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $unique_filename = 'ad_' . time() . '_' . uniqid() . '.' . $file_extension;
                
                // Upload the image
                if (uploadImage($_FILES['image']['tmp_name'], $unique_filename, $upload_dir)) {
                    // Image path to store in database
                    $image_path = $upload_dir . $unique_filename;
                    
                    // Convert roles array to comma-separated string
                    $target_roles = implode(',', $data['target_roles']);
                    
                    // Prepare data for model
                    $advertisementData = [
                        'title' => $data['title_value'],
                        'description' => $data['description_value'],
                        'image_path' => $image_path,
                        'target_roles' => $target_roles,
                        'created_by' => $_SESSION['user_id'],
                        'status' => 'active'
                    ];
                    
                    // Create advertisement in database
                    if ($this->adminModel->createAdvertisement($advertisementData)) {
                        flash('msg', 'Advertisement created successfully', 'alert-success');
                        redirect('admin/advertisements');
                    } else {
                        $data['image_err'] = 'Failed to create advertisement';
                        $this->view('admin/advertisements/v_create_advertisement', $data);
                    }
                } else {
                    $data['image_err'] = 'Failed to upload image';
                    $this->view('admin/advertisements/v_create_advertisement', $data);
                }
            } else {
                // Show form with errors
                $this->view('admin/advertisements/v_create_advertisement', $data);
            }
        } else {
            // Show empty form
            $data = [
                'title' => 'Advertisements',
                'pageTitle' => 'Create an Advertisement',
                'title_value' => '',
                'description_value' => '',
                'title_err' => '',
                'description_err' => '',
                'image_err' => '',
                'roles_err' => ''
            ];
            $this->view('admin/advertisements/v_create_advertisement', $data);
        }
    }

    public function viewAdvertisement($id) {
        // Get advertisement details
        $advertisement = $this->adminModel->getAdvertisementById($id);

        if (!$advertisement) {
            flash('msg', 'Advertisement not found', 'alert-danger');
            redirect('admin/advertisements');
            return;
        }

        $data = [
            'title' => 'Advertisements',
            'pageTitle' => 'View Advertisement',
            'advertisement' => $advertisement
        ];

        $this->view('admin/advertisements/v_view_advertisment', $data);
    }

    public function toggleAdvertisementStatus($id) {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('admin/advertisements');
            return;
        }

        // Set JSON response header
        header('Content-Type: application/json');

        // Validate ID
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid advertisement ID']);
            return;
        }

        // Toggle status in database
        if ($this->adminModel->toggleAdvertisementStatus($id)) {
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
    }

    public function deleteAdvertisement($id) {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('admin/advertisements');
            return;
        }

        // Set JSON response header
        header('Content-Type: application/json');

        // Validate ID
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid advertisement ID']);
            return;
        }

        // Get advertisement details to delete image file
        $ad = $this->adminModel->getAdvertisementById($id);

        // Delete from database
        if ($this->adminModel->deleteAdvertisement($id)) {
            // Try to delete the image file if it exists
            if ($ad && !empty($ad->image_path)) {
                $file_path = $_SERVER['DOCUMENT_ROOT'] . $ad->image_path;
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }
            echo json_encode(['success' => true, 'message' => 'Advertisement deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete advertisement']);
        }
    }

    public function editAdvertisement($id) {
        // Get advertisement details
        $advertisement = $this->adminModel->getAdvertisementById($id);

        if (!$advertisement) {
            flash('msg', 'Advertisement not found', 'alert-danger');
            redirect('admin/advertisements');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission
            $data = [
                'id' => $id,
                'title' => 'Advertisements',
                'pageTitle' => 'Edit Advertisement',
                'advertisement' => $advertisement,
                'title_value' => trim($_POST['title'] ?? ''),
                'description_value' => trim($_POST['description'] ?? ''),
                'target_roles' => isset($_POST['target_roles']) ? $_POST['target_roles'] : [],
                'title_err' => '',
                'description_err' => '',
                'image_err' => '',
                'roles_err' => ''
            ];
            
            // Validate title
            if (empty($data['title_value'])) {
                $data['title_err'] = 'Please enter an advertisement title';
            }
            
            // Validate description
            if (empty($data['description_value'])) {
                $data['description_err'] = 'Please enter a description';
            }
            
            // Validate target roles
            if (empty($data['target_roles'])) {
                $data['roles_err'] = 'Please select at least one target role';
            }
            
            // Handle image upload (optional for edit)
            $image_path = $advertisement->image_path; // Keep existing image by default
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
                // New image uploaded
                if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    // Validate image file type
                    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    $file_type = $_FILES['image']['type'];
                    
                    if (!in_array($file_type, $allowed_types)) {
                        $data['image_err'] = 'Only JPG, PNG, and GIF images are allowed';
                    } else {
                        // Upload new image
                        $upload_dir = '/uploads/advertisements/';
                        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $unique_filename = 'ad_' . time() . '_' . uniqid() . '.' . $file_extension;
                        
                        if (uploadImage($_FILES['image']['tmp_name'], $unique_filename, $upload_dir)) {
                            // Delete old image
                            if (!empty($advertisement->image_path)) {
                                $old_file = $_SERVER['DOCUMENT_ROOT'] . $advertisement->image_path;
                                if (file_exists($old_file)) {
                                    @unlink($old_file);
                                }
                            }
                            $image_path = $upload_dir . $unique_filename;
                        } else {
                            $data['image_err'] = 'Failed to upload image';
                        }
                    }
                } else {
                    $data['image_err'] = 'Error uploading image';
                }
            }
            
            // If no errors, update the advertisement
            if (empty($data['title_err']) && empty($data['description_err']) && empty($data['image_err']) && empty($data['roles_err'])) {
                // Convert roles array to JSON
                $target_roles = json_encode($data['target_roles']);
                
                // Prepare data for model
                $updateData = [
                    'title' => $data['title_value'],
                    'description' => $data['description_value'],
                    'image_path' => $image_path,
                    'target_roles' => $target_roles
                ];
                
                // Update in database
                if ($this->adminModel->updateAdvertisement($id, $updateData)) {
                    flash('msg', 'Advertisement updated successfully', 'alert-success');
                    redirect('admin/advertisements');
                } else {
                    $data['image_err'] = 'Failed to update advertisement';
                    $this->view('admin/advertisements/v_edit_advertisement', $data);
                }
            } else {
                // Show form with errors
                $this->view('admin/advertisements/v_edit_advertisement', $data);
            }
        } else {
            // Show edit form
            $data = [
                'id' => $id,
                'title' => 'Advertisements',
                'pageTitle' => 'Edit Advertisement',
                'advertisement' => $advertisement,
                'title_value' => $advertisement->title,
                'description_value' => $advertisement->description,
                'target_roles' => json_decode($advertisement->target_roles) ?: explode(',', $advertisement->target_roles),
                'title_err' => '',
                'description_err' => '',
                'image_err' => '',
                'roles_err' => ''
            ];
            $this->view('admin/advertisements/v_edit_advertisement', $data);
        }
    }

    
// ======================================================================== //
// =======================      Admin incidents        ====================== //
// ======================================================================== //
    public function incidents() {
        // Fetch all incidents from database
        $incidents = $this->adminModel->getAllIncidents();
        $stats = $this->adminModel->getIncidentStats();
        
        $data = [
            'title' => 'Incidents',
            'pageTitle' => 'Incidents Dashboard',
            'incidents' => $incidents,
            'stats' => $stats
        ];
        $this->view('admin/incidents/v_incidents', $data);
    }

    public function incidentReports() {
        // Get comprehensive incident analytics data
        $chartData = $this->chartModel->getIncidentAnalytics();
        $stats = $this->adminModel->getIncidentStats();
        $recentIncidents = $this->adminModel->getRecentIncidents(1000);
        
        $data = [
            'title' => 'Incident Reports & Analytics',
            'pageTitle' => 'Incident Reports & Analytics',
            'chartData' => $chartData,
            'stats' => $stats,
            'recentIncidents' => $recentIncidents
        ];
        $this->view('admin/reports/v_incidents', $data);
    }

    public function getIncidentChartData() {
        // AJAX endpoint for chart data
        header('Content-Type: application/json');
        $chartData = $this->chartModel->getIncidentAnalytics();
        echo json_encode($chartData);
    }
    
    public function viewIncident($id) {
        // Get incident details
        $incident = $this->adminModel->getIncidentById($id);
        
        if (!$incident) {
            flash('incident_message', 'Incident not found', 'alert-danger');
            redirect('admin/incidents');
            return;
        }
        
        // Get incident reviews
        $reviews = $this->adminModel->getIncidentReviews($id);
        
        $data = [
            'title' => 'Incidents',
            'pageTitle' => 'Incident Details',
            'incident' => $incident,
            'reviews' => $reviews
        ];
        
        $this->view('admin/incidents/v_view_incidents', $data);
    }
    
    public function addIncidentReview() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('admin/incidents');
            return;
        }
        
        // Get form data (PDO prepared statements handle SQL injection)
        $incidentId = trim($_POST['incident_id'] ?? '');
        $reviewTitle = trim($_POST['review_title'] ?? '');
        $reviewType = trim($_POST['review_type'] ?? '');
        $reviewDetails = trim($_POST['review_details'] ?? '');
        $userId = $_SESSION['user_id'] ?? null;
        $userName = $_SESSION['user_name'] ?? 'Admin';
        
        // Validate required fields
        if (empty($incidentId) || empty($reviewTitle) || empty($reviewDetails) || empty($userId)) {
            flash('incident_message', 'Please fill all required fields', 'alert-danger');
            redirect('admin/viewIncident/' . $incidentId);
            return;
        }
        
        // Add review using admin model
        $result = $this->adminModel->addIncidentReview(
            $incidentId,
            $userId,
            $userName,
            $reviewTitle,
            $reviewType,
            $reviewDetails
        );
        
        if ($result) {
            // Update incident status to In Progress
            $statusUpdated = $this->adminModel->updateIncidentStatus($incidentId, 'In Progress');
            
            if (!$statusUpdated) {
                error_log("Failed to update incident status for incident ID: " . $incidentId);
            }
            
            // Log activity
            $this->adminModel->insertRecentActivity(
                'Incident Review Added',
                'Admin added a review to incident #' . $incidentId,
                'Incident',
                $userId
            );
            
            // Send notifications to related users
            $reviewData = [
                'review_title' => $reviewTitle,
                'review_type' => $reviewType,
                'review_details' => $reviewDetails
            ];
            $this->sendIncidentReviewNotifications($userId, $incidentId, $reviewData);
            
            flash('incident_message', 'Review added successfully and status updated to In Progress', 'alert-success');
        } else {
            flash('incident_message', 'Failed to add review. Please try again.', 'alert-danger');
        }
        
        redirect('admin/viewIncident/' . $incidentId);
    }
    
    public function resolveIncident() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('admin/incidents');
            return;
        }
        
        // Get form data
        $incidentId = trim($_POST['incident_id'] ?? '');
        $resolutionTitle = trim($_POST['resolution_title'] ?? '');
        $resolutionDetails = trim($_POST['resolution_details'] ?? '');
        $actionsTaken = trim($_POST['actions_taken'] ?? '');
        $userId = $_SESSION['user_id'] ?? null;
        $userName = $_SESSION['user_name'] ?? 'Admin';
        
        // Validate required fields
        if (empty($incidentId) || empty($resolutionTitle) || empty($resolutionDetails) || empty($userId)) {
            flash('incident_message', 'Please fill all required fields', 'alert-danger');
            redirect('admin/viewIncident/' . $incidentId);
            return;
        }
        
        // Add resolution as a review
        $reviewResult = $this->adminModel->addIncidentReview(
            $incidentId,
            $userId,
            $userName,
            $resolutionTitle,
            'Action',
            $resolutionDetails
        );
        
        // Update incident status to Resolved
        $statusUpdated = $this->adminModel->updateIncidentStatus($incidentId, 'Resolved');
        
        // Update action_taken field if provided
        if (!empty($actionsTaken)) {
            $this->adminModel->updateIncidentActionsTaken($incidentId, $actionsTaken);
        }
        
        if ($reviewResult && $statusUpdated) {
            // Log activity
            $this->adminModel->insertRecentActivity(
                'Incident Resolved',
                'Admin marked incident #' . $incidentId . ' as resolved',
                'Incident',
                $userId
            );
            
            flash('incident_message', 'Incident marked as resolved successfully', 'alert-success');
        } else {
            flash('incident_message', 'Failed to resolve incident. Please try again.', 'alert-danger');
        }
        
        redirect('admin/viewIncident/' . $incidentId);
    }

// ======================================================================== //
// =======================      Admin Reports        ====================== //
// ======================================================================== //
    public function reports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Admin Reports'
        ];
        $this->view('admin/reports/v_reports', $data);  
    }

    public function attendencereports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Atendence Reports'
        ];
        $this->view('admin/reports/v_attendence', $data);  
    }

    public function paymentsreports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Client Payment Reports'
        ];
        $this->view('admin/reports/v_clientpayments', $data);  
    }

    public function requestsreports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Client Requests Reports'
        ];
        $this->view('admin/reports/v_clientrequests', $data);  
    }

    public function incidentsreports() {
        // Get comprehensive incident analytics data
        $chartData = $this->chartModel->getIncidentAnalytics();
        $stats = $this->adminModel->getIncidentStats();
        
        // Get recent incidents for the report
        $incidents = $this->adminModel->getAllIncidents();
        
        // Get all sites for filter dropdown
        $sites = $this->adminModel->getAllSites();
        
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Incidents Reports',
            'chartData' => $chartData,
            'stats' => $stats,
            'incidents' => $incidents,
            'sites' => $sites
        ];
        $this->view('admin/reports/v_incidents', $data);  
    }

    public function sitereports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Site Reports'
        ];
        $this->view('admin/reports/v_site', $data);  
    }

    public function performancereports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Officer Performance Reports'
        ];
        $this->view('admin/reports/v_performance', $data);  
    }

// ======================================================================== //
// =======================      Admin Settings       ====================== //
// ======================================================================== //
    public function settings() {
        $data = [
            'title' => 'Settings',
            'pageTitle' => 'System Settings',
            'admins' => $this->adminModel->getAdmins()
        ];
        $this->view('admin/v_settings', $data);
    }

// ======================================================================== //
// =======================      Admin Panal       ====================== //
// ======================================================================== //
    public function admins() {
        if(isset($_SESSION['user_userID']) && $_SESSION['user_userID'] == 'ADMIN001'){
            $data = [
                'title' => 'Admins',
                'pageTitle' => 'Admin Panal',
                'admins' => $this->adminModel->getAllAdmins()
            ];
            $this->view('admin/admins/v_admins', $data);
        }
    }

    public function addadmin(){
        if(isset($_SESSION['user_userID']) && $_SESSION['user_userID'] == 'ADMIN001'){
        if(($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'){
            $data = [
                
                'title' => 'Admins',
                'pageTitle' => 'Create Admin',

                'image' => $_FILES['image'],
                'image_name' => time(). '_' . $_FILES['image']['name'],

                'name' => $this->sanitizeInput($_POST['name'] ?? ''),
                'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),

                'image_err' => '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
            ];

            // Validate form
            if(empty($data['image']['name'])){
                $data['image_err'] = 'Please upload an image';
            } elseif($data['image']['size'] > 0){
                if(uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/image/')){
                    // Image uploaded successfully
                } else {
                    $data['image_err'] = 'Failed to upload image';
                }
            }

            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }

            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }

            if(empty($data['phone_number'])){
                $data['phone_number_err'] = 'Please enter phone number';
            } elseif(!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])){
                $data['phone_number_err'] = 'Please enter a valid phone number (10-15 digits)';
            }

            // Make sure there are no errors
            if(empty($data['image_err']) && 
            empty($data['name_err']) && 
            empty($data['email_err']) && 
            empty($data['phone_number_err'])){

                // Insert admin and get the result
                $result = $this->adminModel->addAdmin($data);
                
                if($result && isset($result['success']) && $result['success']){
                    // Add activity log
                    $title = "New Admin Added";
                    $description = "Admin '" . $data['name'] . "' added";
                    $type = "shift";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    
                    // Send welcome email with credentials
                    if (isset($result['userID']) && isset($result['tempPassword'])) {
                        $emailVars = [
                            'site_name' => SITE_NAME,
                            'admin_name' => $result['name'],
                            'login_id' => $result['userID'],
                            'temp_password' => $result['tempPassword'],
                            'login_url' => URL_ROOT . '/users/login'
                        ];
                        
                        $emailResult = send_templated_email(
                            $result['email'],
                            'welcome_admin',
                            $emailVars,
                            'Welcome to ' . SITE_NAME . ' - Administrator Account Created'
                        );
                        
                        if (!$emailResult['success']) {
                            error_log('Failed to send welcome email to admin: ' . $emailResult['message']);
                        }
                    }
                    
                    flash('msg', 'Admin added successfully and welcome email sent', 'alert-success');
                    redirect('admin/admins/'.$result['id']); // Redirect properly
                } else {
                    flash('msg', 'Failed to add admin', 'alert-danger');
                    $this->view('admin/admins/v_create_admin',$data);
                }
            } else {
                $this->view('admin/admins/v_create_admin',$data);
            }

        } else {
            $data = [
                
                'title' => 'Admins',
                'pageTitle' => 'Add Admin',

                'image' => '', 
                'image_name' => '',

                'name' => '',
                'email' => '',
                'phone_number' => '',

                'image_err' => '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
            ];
            
            $this->view('admin/admins/v_create_admin', $data);
        }
    }
    }


    public function profile() {
        $data = [
            'title' => 'Profile',
            'pageTitle' => 'Admin Profile',
            'admin' => $this->adminModel->getAdmin($_SESSION['user_userID'])
        ];
        $this->view('admin/admins/v_profile', $data);
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

    // AJAX endpoint to get available officers
    public function getAvailableOfficers() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $filters = [
            'site_id' => $input['site_id'] ?? null,
            'city' => $input['city'] ?? '',
            'district' => $input['district'] ?? '',
            'district_filter' => $input['district_filter'] ?? 'same-district',
            'city_filter' => $input['city_filter'] ?? 'same-city',
            'availability' => $input['availability'] ?? 'available',
            'status' => $input['status'] ?? 'Active'
        ];

        $officers = $this->adminModel->getAvailableOfficers($filters);
        
        echo json_encode(['officers' => $officers]);
    }

    // AJAX endpoint to assign officer to site
    public function assignOfficerToSite() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $siteId = $input['site_id'] ?? null;
        $officerId = $input['officer_id'] ?? null;
        $shiftType = $input['shift_type'] ?? 'Full Time';
        $assignmentEnd = $input['assignment_end'] ?? null;
        $assignedBy = $_SESSION['user_id'] ?? null;

        if (!$siteId || !$officerId || !$assignedBy) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        $result = $this->adminModel->assignOfficerToSite($siteId, $officerId, $assignedBy, $shiftType, $assignmentEnd);
        
        // Send notification to officer if assignment was successful
        if ($result['success']) {
            $site = $this->adminModel->getSiteById($siteId);
            $siteName = $site ? $site->site_name : 'a site';
            
            $this->notificationModel->insertNotification(
                $officerId,
                'assignment',
                'New Site Assignment',
                'You have been assigned to ' . $siteName . ' (' . $shiftType . ').',
                '/premiseofficer/dashboard',
                'location_on',
                $assignedBy
            );
        }
        echo json_encode($result);
    }

    // AJAX endpoint to unassign officer from site
    public function unassignOfficerFromSite() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $assignmentId = $input['assignment_id'] ?? null;

        if (!$assignmentId) {
            echo json_encode(['success' => false, 'message' => 'Missing assignment ID']);
            return;
        }

        $result = $this->adminModel->unassignOfficerFromSite($assignmentId);
        echo json_encode($result);
    }

    public function unassignCaretakerFromSite() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $assignmentId = $input['assignment_id'] ?? null;

        if (!$assignmentId) {
            echo json_encode(['success' => false, 'message' => 'Missing assignment ID']);
            return;
        }

        $result = $this->adminModel->unassignCaretakerFromSite($assignmentId);
        echo json_encode($result);
    }

    // AJAX endpoint to update officer field (rank or employment status)
    public function updateOfficerField() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $officerId = $input['officer_id'] ?? null;
        $field = $input['field'] ?? null;
        $value = $input['value'] ?? null;
        $role = $input['role'] ?? null;

        // Debug logging
        error_log("Update Officer Field - Officer ID: $officerId, Field: $field, Value: $value, Role: $role");

        if (!$officerId || !$field || !$value || !$role) {
            echo json_encode([
                'success' => false, 
                'message' => 'Missing required parameters',
                'debug' => [
                    'officer_id' => $officerId,
                    'field' => $field,
                    'value' => $value,
                    'role' => $role
                ]
            ]);
            return;
        }

        // Validate field name
        $allowedFields = ['rank', 'employment_status'];
        if (!in_array($field, $allowedFields)) {
            echo json_encode(['success' => false, 'message' => 'Invalid field: ' . $field]);
            return;
        }

        try {
            $result = $this->adminModel->updateOfficerField($officerId, $field, $value, $role);
            
            if ($result) {
                // Log the activity
                $title = ucfirst(str_replace('_', ' ', $field)) . ' Updated';
                $description = "Officer ID: $officerId - $field changed to: $value";
                $this->adminModel->insertRecentActivity($title, $description, 'officer');
                
                // Send notification to officer for rank updates
                if ($field === 'rank') {
                    // Convert role name to role code if needed
                    $roleCode = $role;
                    $roleLower = strtolower($role);
                    if ($roleLower === 'premise officer' || $roleLower === 'premiseofficer') {
                        $roleCode = 'po';
                    } elseif ($roleLower === 'mobile rider' || $roleLower === 'mobilerider') {
                        $roleCode = 'mr';
                    } elseif ($roleLower === 'care taker' || $roleLower === 'caretaker') {
                        $roleCode = 'ct';
                    }
                    
                    // Get officer details for notification based on role
                    $officer = null;
                    
                    switch($roleCode) {
                        case 'po':
                            $officer = $this->adminModel->getPOById($officerId);
                            $roleName = 'premiseofficer';
                            
                            // Fallback: If not found in premise_officers table, get from Users table
                            if (!$officer) {
                                $this->db->query("SELECT userID FROM Users WHERE id = :id AND role = 'Premise Officer'");
                                $this->db->bind(':id', $officerId);
                                $userRecord = $this->db->single();
                                
                                if ($userRecord) {
                                    // Try to find in premise_officers by userID
                                    $this->db->query("SELECT * FROM premise_officers WHERE userID = :userID");
                                    $this->db->bind(':userID', $userRecord->userID);
                                    $officer = $this->db->single();
                                    
                                    // If still not found, create minimal object for notification
                                    if (!$officer) {
                                        $officer = new stdClass();
                                        $officer->userID = $userRecord->userID;
                                    }
                                }
                            }
                            break;
                        case 'mr':
                            $officer = $this->adminModel->getMRById($officerId);
                            $roleName = 'mobilerider';
                            
                            // Fallback: If not found in mobile_riders table, get from Users table
                            if (!$officer) {
                                $this->db->query("SELECT userID FROM Users WHERE id = :id AND role = 'Mobile Rider'");
                                $this->db->bind(':id', $officerId);
                                $userRecord = $this->db->single();
                                
                                if ($userRecord) {
                                    $this->db->query("SELECT * FROM mobile_riders WHERE userID = :userID");
                                    $this->db->bind(':userID', $userRecord->userID);
                                    $officer = $this->db->single();
                                    
                                    if (!$officer) {
                                        $officer = new stdClass();
                                        $officer->userID = $userRecord->userID;
                                    }
                                }
                            }
                            break;
                        case 'ct':
                            $officer = $this->adminModel->getCTById($officerId);
                            $roleName = 'caretaker';
                            
                            // Fallback: If not found in care_takers table, get from Users table
                            if (!$officer) {
                                $this->db->query("SELECT userID FROM Users WHERE id = :id AND role = 'Care Taker'");
                                $this->db->bind(':id', $officerId);
                                $userRecord = $this->db->single();
                                
                                if ($userRecord) {
                                    $this->db->query("SELECT * FROM care_takers WHERE userID = :userID");
                                    $this->db->bind(':userID', $userRecord->userID);
                                    $officer = $this->db->single();
                                    
                                    if (!$officer) {
                                        $officer = new stdClass();
                                        $officer->userID = $userRecord->userID;
                                    }
                                }
                            }
                            break;
                    }
                    
                    if ($officer) {
                        // Get numeric user ID for notification
                        $this->db->query("SELECT id FROM Users WHERE userID = :userID");
                        $this->db->bind(':userID', $officer->userID);
                        $userRow = $this->db->single();
                        $numericUserId = $userRow ? $userRow->id : null;
                        
                        if ($numericUserId) {
                            // Send notification to officer about rank update
                            $this->notificationModel->insertNotification(
                                $numericUserId,
                                'info',
                                'Rank Updated',
                                'Your rank has been updated to: ' . $value . '. Please check your profile for more details.',
                                '/' . $roleName . '/dashboard',
                                'star',
                                $_SESSION['user_id'] ?? 1
                            );
                        }
                    }
                }
                
                echo json_encode(['success' => true, 'message' => 'Updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database update failed - no rows affected']);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Exception: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    // AJAX endpoint to get available supervisors (premise officers with rank = Supervisor)
    public function getAvailableSupervisors() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $filters = [
            'site_id' => $input['site_id'] ?? null,
            'city' => $input['city'] ?? '',
            'district' => $input['district'] ?? '',
            'district_filter' => $input['district_filter'] ?? 'same-district',
            'city_filter' => $input['city_filter'] ?? 'same-city',
            'availability' => $input['availability'] ?? 'available'
        ];

        $supervisors = $this->adminModel->getAvailableSupervisors($filters);
        
        echo json_encode(['supervisors' => $supervisors]);
    }

    // AJAX endpoint to assign supervisor to site
    public function assignSupervisorToSite() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $siteId = $input['site_id'] ?? null;
        $supervisorId = $input['supervisor_id'] ?? null;
        $assignedBy = $_SESSION['user_id'] ?? null;

        if (!$siteId || !$supervisorId || !$assignedBy) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        $result = $this->adminModel->assignSupervisorToSite($siteId, $supervisorId, $assignedBy);
        
        // Send notification to supervisor if assignment was successful
        if ($result['success']) {
            $site = $this->adminModel->getSiteById($siteId);
            $siteName = $site ? $site->site_name : 'a site';
            
            $this->notificationModel->insertNotification(
                $supervisorId,
                'assignment',
                'New Site Assignment',
                'You have been assigned as supervisor to ' . $siteName . '.',
                '/supervisor/dashboard',
                'location_on',
                $assignedBy
            );
        }
        
        echo json_encode($result);
    }

    // AJAX endpoint to get available caretakers
    public function getAvailableCaretakers() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $filters = [
            'site_id' => $input['site_id'] ?? null,
            'city' => $input['city'] ?? '',
            'district' => $input['district'] ?? '',
            'district_filter' => $input['district_filter'] ?? 'same-district',
            'city_filter' => $input['city_filter'] ?? 'same-city',
            'availability' => $input['availability'] ?? 'available'
        ];

        $caretakers = $this->adminModel->getAvailableCaretakers($filters);
        
        echo json_encode(['caretakers' => $caretakers]);
    }

    // AJAX endpoint to assign caretaker to site
    public function assignCaretakerToSite() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $siteId = $input['site_id'] ?? null;
        $caretakerId = $input['caretaker_id'] ?? null;
        $assignedBy = $_SESSION['user_id'] ?? null;

        if (!$siteId || !$caretakerId || !$assignedBy) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        $result = $this->adminModel->assignCaretakerToSite($siteId, $caretakerId, $assignedBy);
        
        // Send notification to caretaker if assignment was successful
        if ($result['success']) {
            $site = $this->adminModel->getSiteById($siteId);
            $siteName = $site ? $site->site_name : 'a site';
            
            $this->notificationModel->insertNotification(
                $caretakerId,
                'assignment',
                'New Site Assignment',
                'You have been assigned as caretaker to ' . $siteName . '.',
                '/caretaker/dashboard',
                'location_on',
                $assignedBy
            );
        }
        
        echo json_encode($result);
    }

    // Update Admin (AJAX)
    public function updateAdmin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            if(!isset($_SESSION['user_userID']) || $_SESSION['user_userID'] != 'ADMIN001'){
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
                return;
            }
            
            $admin_id = $_POST['admin_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone_number = trim($_POST['phone_number'] ?? '');
            
            if (!$admin_id || empty($name) || empty($email) || empty($phone_number)) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
                return;
            }
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
                return;
            }
            
            // Validate phone number
            if (!preg_match('/^[0-9]{10,15}$/', $phone_number)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid phone number (10-15 digits required)']);
                return;
            }
            
            $data = [
                'admin_id' => $admin_id,
                'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
                'phone_number' => htmlspecialchars($phone_number, ENT_QUOTES, 'UTF-8')
            ];
            
            if ($this->adminModel->updateAdmin($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Admin updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update admin']);
            }
        }
    }

    // Delete Admin (AJAX)
    public function deleteAdmin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            if(!isset($_SESSION['user_userID']) || $_SESSION['user_userID'] != 'ADMIN001'){
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
                return;
            }
            
            $admin_id = $_POST['admin_id'] ?? null;
            
            if (!$admin_id) {
                echo json_encode(['status' => 'error', 'message' => 'Admin ID is required']);
                return;
            }
            
            // Prevent deleting own account
            $current_user_id = $_SESSION['user_id'] ?? null;
            if ($admin_id == $current_user_id) {
                echo json_encode(['status' => 'error', 'message' => 'You cannot delete your own account']);
                return;
            }
            
            // Prevent deleting system administrator
            $admin = $this->adminModel->getAdminById($admin_id);
            if ($admin && $admin->userID == 'ADMIN001') {
                echo json_encode(['status' => 'error', 'message' => 'Cannot delete system administrator']);
                return;
            }
            
            if ($this->adminModel->deleteAdmin($admin_id)) {
                echo json_encode(['status' => 'success', 'message' => 'Admin deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete admin']);
            }
        }
    }

    // Update Profile Phone (AJAX)
    public function updateProfilePhone() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            $phone_number = trim($_POST['phone_number'] ?? '');
            
            if (!$user_id || empty($phone_number)) {
                echo json_encode(['status' => 'error', 'message' => 'Phone number is required']);
                return;
            }
            
            // Validate phone number
            if (!preg_match('/^[0-9]{10,15}$/', $phone_number)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid phone number (10-15 digits required)']);
                return;
            }
            
            if ($this->adminModel->updateProfilePhone($user_id, $phone_number)) {
                echo json_encode(['status' => 'success', 'message' => 'Phone number updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update phone number']);
            }
        }
    }

    // Update Profile Email (AJAX)
    public function updateProfileEmail() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            $email = trim($_POST['email'] ?? '');
            
            if (!$user_id || empty($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Email is required']);
                return;
            }
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
                return;
            }
            
            if ($this->adminModel->updateProfileEmail($user_id, $email)) {
                echo json_encode(['status' => 'success', 'message' => 'Email updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update email']);
            }
        }
    }

    // Update Profile Password (AJAX)
    public function updateProfilePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            
            if (!$user_id || empty($current_password) || empty($new_password)) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
                return;
            }
            
            // Validate new password length
            if (strlen($new_password) < 6) {
                echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters long']);
                return;
            }
            
            // Verify current password
            $user = $this->adminModel->getUserByID($user_id);
            if (!$user || !password_verify($current_password, $user->password)) {
                echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
                return;
            }
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            if ($this->adminModel->updateProfilePassword($user_id, $hashed_password)) {
                echo json_encode(['status' => 'success', 'message' => 'Password changed successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to change password']);
            }
        }
    }

    // Update Profile Image (AJAX)
    public function updateProfileImage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            
            if (!$user_id) {
                echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
                return;
            }
            
            if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['status' => 'error', 'message' => 'Please select an image']);
                return;
            }
            
            $image = $_FILES['profile_image'];
            $image_name = time() . '_' . $image['name'];
            
            // Validate image
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowed_types)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid image type. Only JPG, PNG, and GIF allowed']);
                return;
            }
            
            if ($image['size'] > 5000000) { // 5MB
                echo json_encode(['status' => 'error', 'message' => 'Image size too large. Maximum 5MB allowed']);
                return;
            }
            
            // Upload image
            if (uploadImage($image['tmp_name'], $image_name, '/uploads/image/')) {
                if ($this->adminModel->updateProfileImage($user_id, $image_name)) {
                    echo json_encode(['status' => 'success', 'message' => 'Profile picture updated successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update profile picture']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
            }
        }
    }

    /**
     * Send notifications when an admin adds an incident review
     * For Admin: Notify all other admins + supervisor + mobile riders that are related
     */
    private function sendIncidentReviewNotifications($reviewerId, $incidentId, $reviewData) {
        try {
            // Get incident details
            $incident = $this->adminModel->getIncidentById($incidentId);
            
            if (!$incident) {
                error_log("Incident not found: {$incidentId}");
                return;
            }
            
            // Get reviewer details
            $reviewer = $this->userModel->getUserById($reviewerId);
            $reviewerName = $reviewer->name ?? 'An admin';
            
            // Prepare notification details
            $notificationTitle = "New Review on Incident #{$incidentId}";
            $notificationMessage = "{$reviewerName} (Admin) added a review: \"{$reviewData['review_title']}\" on incident #{$incidentId}";
            $notificationType = 'info';
            $notificationIcon = 'comment';
            
            // Collect all users to notify (use array to avoid duplicates)
            $usersToNotify = [];
            
            // ADMIN adds review: Notify all other admins + supervisor + mobile riders
            
            // 1. Notify all other admins
            try {
                $admins = $this->adminModel->getAllAdmins();
                
                if ($admins && is_array($admins)) {
                    foreach ($admins as $admin) {
                        if (isset($admin->id) && $admin->id != $reviewerId) {
                            $usersToNotify[$admin->id] = [
                                'link' => URL_ROOT . '/admin/incidents'
                            ];
                        }
                    }
                }
            } catch (Exception $e) {
                error_log("Error getting admins for notification: " . $e->getMessage());
            }
            
            // 2. Notify the supervisor who reported the incident (if exists and not the reviewer)
            if (isset($incident->user_id) && $incident->user_id != $reviewerId) {
                $usersToNotify[$incident->user_id] = [
                    'link' => URL_ROOT . '/supervisor/viewIncident/' . $incidentId
                ];
                
                // 3. Also notify mobile riders assigned to that supervisor's site
                if (isset($incident->site_id)) {
                    try {
                        $supervisorModel = $this->model('M_supervisor');
                        $mobileRiders = $supervisorModel->getSiteMobileRiders($incident->user_id);
                        
                        if ($mobileRiders && is_array($mobileRiders)) {
                            foreach ($mobileRiders as $rider) {
                                if (isset($rider->user_id) && $rider->user_id != $reviewerId) {
                                    $usersToNotify[$rider->user_id] = [
                                        'link' => URL_ROOT . '/MobileRider/incidents'
                                    ];
                                }
                            }
                        }
                    } catch (Exception $e) {
                        error_log("Error getting mobile riders for notification: " . $e->getMessage());
                    }
                }
            }
            
            // Send notifications to all collected users
            $sentCount = 0;
            foreach ($usersToNotify as $userId => $data) {
                try {
                    $result = $this->notificationModel->insertNotification(
                        $userId,
                        $notificationType,
                        $notificationTitle,
                        $notificationMessage,
                        $data['link'],
                        $notificationIcon,
                        $reviewerId
                    );
                    if ($result) {
                        $sentCount++;
                    }
                } catch (Exception $e) {
                    error_log("Error sending notification to user {$userId}: " . $e->getMessage());
                }
            }
            
            error_log("Sent {$sentCount} notifications for incident review #{$incidentId} by admin");
            
        } catch (Exception $e) {
            error_log("Error in sendIncidentReviewNotifications: " . $e->getMessage());
        }
    }
}



//For create folders 

//.  sudo chown -R daemon:daemon /Applications/XAMPP/xamppfiles/htdocs/RedForce/public/uploads/

//. # If the clientLogos directory doesn't exist yet, create it with proper permissions
//. sudo mkdir -p /Applications/XAMPP/xamppfiles/htdocs/RedForce/public/uploads/siteImages 
//. sudo chown -R daemon:daemon /Applications/XAMPP/xamppfiles/htdocs/RedForce/public/uploads/siteImages 
//. sudo chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/RedForce/public/uploads/siteImages 