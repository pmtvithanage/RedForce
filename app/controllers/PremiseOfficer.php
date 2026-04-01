<?php
class PremiseOfficer extends Controller {
    private $premiseOfficerModel;
    private $userModel;
    private $advertisementModel;
    private $notificationModel;
    private $leaveRequestModel;
    private $messageModel;

    public function __construct() {
        // Check if user is logged in and has premise officer role
        //requireAuth('premise officer');

        $this->advertisementModel = $this->model('M_advertisements');
        $this->premiseOfficerModel = $this->model('M_premiseofficer');
        $this->userModel = $this->model('M_users');
        $this->notificationModel = $this->model('M_notifications');
        $this->leaveRequestModel = $this->model('M_leaveRequests');
        $this->messageModel = $this->model('M_message');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('premiseOfficer/dashboard');
    }

    // Dashboard action
    public function dashboard() {
        $premiseofficer_id = $_SESSION['user_id'];
        
        // Get dashboard stats
        $activeAssignments = $this->premiseOfficerModel->getActiveAssignments($premiseofficer_id);
        $leaveStats = $this->premiseOfficerModel->getLeaveStats($premiseofficer_id);
        $unreadNotifications = $this->notificationModel->getUnreadCount($premiseofficer_id);
        
        $stats = [
            'sites' => count($activeAssignments),
            'leaves' => $leaveStats->pending_requests ?? 0,
            'shifts' => count($activeAssignments),
            'notifications' => $unreadNotifications
        ];
        
        // Get recent activities
        $recent_activities = $this->premiseOfficerModel->getRecentActivities($premiseofficer_id, 5);
        
        // Get upcoming shifts (use active assignments, limit to 5)
        $upcoming_shifts = array_slice($activeAssignments, 0, 5);
        foreach ($upcoming_shifts as $shift) {
            $shift->site_address = $shift->address . ', ' . $shift->city;
        }
        
        // Get recent leave requests
        $leave_requests = $this->premiseOfficerModel->getRecentLeaveRequests($premiseofficer_id, 5);
        
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'recent_activities' => $recent_activities,
            'upcoming_shifts' => $upcoming_shifts,
            'leave_requests' => $leave_requests
        ];

        $this->view('premiseofficer/v_dashboard', $data);
    }

    // Schedule action
    public function schedule() {
        $premiseofficer_id = $_SESSION['user_id'] ?? null;
        
        $assignments = [];
        $leaveDates = [];
        
        if ($premiseofficer_id) {
            $assignments = $this->premiseOfficerModel->getActiveAssignments($premiseofficer_id);
            $leaveDates = $this->premiseOfficerModel->getApprovedLeaveDates($premiseofficer_id);
            
            // Debug logging
            error_log('Schedule - Officer ID: ' . $premiseofficer_id);
            error_log('Schedule - Assignments count: ' . count($assignments));
            error_log('Schedule - Assignments data: ' . json_encode($assignments));
            error_log('Schedule - Leave dates count: ' . count($leaveDates));
        }
        
        $data = [
            'title' => 'Schedule',
            'pageTitle' => 'My Schedule',
            'assignments' => $assignments,
            'leaveDates' => $leaveDates
        ];
        $this->view('premiseofficer/schedule/v_schedule', $data);
    }
    
    // AJAX endpoint to get shift details for a specific date
    public function getShiftDetails() {
        // Clear any output buffers and start fresh
        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        
        header('Content-Type: application/json');
        
        // Log the request for debugging
        error_log('getShiftDetails called');
        error_log('REQUEST_METHOD: ' . $_SERVER['REQUEST_METHOD']);
        error_log('POST data: ' . print_r($_POST, true));
        error_log('SESSION user_id: ' . ($_SESSION['user_id'] ?? 'not set'));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = json_encode(['success' => false, 'message' => 'Invalid request method']);
            ob_end_clean();
            echo $response;
            exit;
        }
        
        $premiseofficer_id = $_SESSION['user_id'] ?? null;
        $date = $_POST['date'] ?? null;
        
        if (!$premiseofficer_id || !$date) {
            error_log('Missing parameters - premiseofficer_id: ' . ($premiseofficer_id ?? 'null') . ', date: ' . ($date ?? 'null'));
            $response = json_encode(['success' => false, 'message' => 'Missing required parameters - ID: ' . ($premiseofficer_id ? 'OK' : 'MISSING') . ', Date: ' . ($date ? 'OK' : 'MISSING')]);
            ob_end_clean();
            echo $response;
            exit;
        }
        
        error_log('Fetching shift details for officer ' . $premiseofficer_id . ' on date ' . $date);
        
        // Get shift details
        $shift = $this->premiseOfficerModel->getShiftDetailsForDate($premiseofficer_id, $date);
        
        error_log('Shift query result: ' . ($shift ? 'Found' : 'Not found'));
        if ($shift) {
            error_log('Shift details: ' . print_r($shift, true));
        }
        
        // Check if it's a leave day using the model method
        $leaveInfo = $this->premiseOfficerModel->getLeaveForDate($premiseofficer_id, $date);
        
        if ($leaveInfo) {
            $response = json_encode([
                'success' => true,
                'type' => 'leave',
                'leave_type' => $leaveInfo->leave_type,
                'reason' => $leaveInfo->reason
            ]);
            ob_end_clean();
            echo $response;
            exit;
        }
        
        if ($shift) {
            // Determine shift time based on shift type
            $shiftTime = '';
            switch ($shift->shift_type) {
                case 'Day':
                    $shiftTime = '08:00 AM - 06:00 PM';
                    break;
                case 'Night':
                    $shiftTime = '06:00 PM - 06:00 AM';
                    break;
                case 'Full Time':
                    $shiftTime = '08:00 AM - 06:00 PM';
                    break;
                case 'Flexible':
                    $shiftTime = 'Flexible Hours';
                    break;
                default:
                    $shiftTime = 'Not Specified';
            }
            
            $response = json_encode([
                'success' => true,
                'type' => 'shift',
                'location' => $shift->site_name,
                'address' => $shift->address . ', ' . $shift->city,
                'time' => $shiftTime,
                'shift_type' => $shift->shift_type,
                'client' => $shift->contact_person_name ?? '',
                'notes' => $shift->notes ?? 'No additional notes'
            ]);
            ob_end_clean();
            echo $response;
            exit;
        } else {
            $response = json_encode([
                'success' => true,
                'type' => 'no_shift'
            ]);
            ob_end_clean();
            echo $response;
            exit;
        }
    }

    // Leave Requests
    public function leaverequests() {
        $premiseofficer_id = $_SESSION['user_id'] ?? null;
        
        if (!$premiseofficer_id) {
            redirect('login');
        }

        $leaveRequests = $this->leaveRequestModel->getLeaveRequestsByUser($premiseofficer_id);
        $stats = $this->leaveRequestModel->getLeaveStats($premiseofficer_id);
        
        $data = [
            'title' => 'Leave Requests',
            'pageTitle' => 'Leave Requests',
            'leaveRequests' => $leaveRequests,
            'stats' => $stats
        ];
        $this->view('premiseofficer/leaverequests/v_leaverequests', $data);
    }

    // Create Leave Request
    public function createLeaveRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission
            $data = [
                'title' => 'Leave Requests',
                'pageTitle' => 'Create Leave Request',
                'leave_type_value' => trim($_POST['leave_type'] ?? ''),
                'reason_value' => trim($_POST['reason'] ?? ''),
                'start_date_value' => trim($_POST['start_date'] ?? ''),
                'end_date_value' => trim($_POST['end_date'] ?? ''),
                'leave_type_err' => '',
                'reason_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'proof_file_err' => ''
            ];
            
            // Validate leave type
            if (empty($data['leave_type_value'])) {
                $data['leave_type_err'] = 'Please select a leave type';
            }
            
            // Validate reason
            if (empty($data['reason_value'])) {
                $data['reason_err'] = 'Please enter a reason for leave';
            }
            
            // Validate start date
            if (empty($data['start_date_value'])) {
                $data['start_date_err'] = 'Please select a start date';
            }
            
            // Validate end date
            if (empty($data['end_date_value'])) {
                $data['end_date_err'] = 'Please select an end date';
            } elseif (!empty($data['start_date_value']) && strtotime($data['end_date_value']) < strtotime($data['start_date_value'])) {
                $data['end_date_err'] = 'End date must be after start date';
            }
            
            // Handle proof file upload (optional)
            $proof_file_path = null;
            if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] == UPLOAD_ERR_OK) {
                // Validate file type
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
                $file_type = $_FILES['proof_file']['type'];
                
                if (!in_array($file_type, $allowed_types)) {
                    $data['proof_file_err'] = 'Only JPG, PNG, GIF, and PDF files are allowed';
                } else {
                    // Upload file
                    $upload_dir = '/uploads/leave_proofs/';
                    $file_extension = pathinfo($_FILES['proof_file']['name'], PATHINFO_EXTENSION);
                    $unique_filename = 'proof_' . time() . '_' . uniqid() . '.' . $file_extension;
                    
                    if (uploadImage($_FILES['proof_file']['tmp_name'], $unique_filename, $upload_dir)) {
                        $proof_file_path = $upload_dir . $unique_filename;
                    } else {
                        $data['proof_file_err'] = 'Failed to upload proof file';
                    }
                }
            }
            
            // If no errors, create leave request
            if (empty($data['leave_type_err']) && empty($data['reason_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['proof_file_err'])) {
                $leaveRequestData = [
                    'premiseofficer_id' => $_SESSION['user_id'],
                    'leave_type' => $data['leave_type_value'],
                    'reason' => $data['reason_value'],
                    'start_date' => $data['start_date_value'],
                    'end_date' => $data['end_date_value'],
                    'proof_file' => $proof_file_path,
                    'status' => 'Pending'
                ];
                
                if ($this->leaveRequestModel->createLeaveRequest($leaveRequestData)) {
                    // Send notifications to all admins
                    try {
                        $adminModel = $this->model('M_admin');
                        $admins = $adminModel->getAllAdmins();
                        $user = $this->userModel->getUserById($_SESSION['user_id']);
                        $userName = $user->name ?? 'A premise officer';
                        
                        if ($admins && is_array($admins)) {
                            foreach ($admins as $admin) {
                                $this->notificationModel->addNotification(
                                    $admin->id,
                                    'info',
                                    'New Leave Request',
                                    "{$userName} (Premise Officer) submitted a leave request for {$data['leave_type_value']} from {$data['start_date_value']} to {$data['end_date_value']}",
                                    URL_ROOT . '/admin/pendings',
                                    'calendar_today',
                                    $_SESSION['user_id']
                                );
                            }
                        }
                        
                        // Log recent activity
                        $premiseOfficerModel = $this->model('M_premiseofficer');
                        $premiseOfficerModel->insertRecentActivity(
                            $_SESSION['user_id'],
                            'Leave Request Submitted',
                            "Submitted {$data['leave_type_value']} leave request from {$data['start_date_value']} to {$data['end_date_value']}",
                            'leave_request'
                        );
                    } catch (Exception $e) {
                        error_log("Error sending leave request notifications: " . $e->getMessage());
                    }
                    
                    flash('msg', 'Leave request submitted successfully', 'alert-success');
                    redirect('premiseOfficer/leaverequests');
                } else {
                    flash('msg', 'Failed to submit leave request', 'alert-danger');
                    $this->view('premiseofficer/leaverequests/v_create_leaverequest', $data);
                }
            } else {
                // Show form with errors
                $this->view('premiseofficer/leaverequests/v_create_leaverequest', $data);
            }
        } else {
            // Show empty form
            $data = [
                'title' => 'Leave Requests',
                'pageTitle' => 'Create Leave Request',
                'leave_type_value' => '',
                'reason_value' => '',
                'start_date_value' => '',
                'end_date_value' => '',
                'leave_type_err' => '',
                'reason_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'proof_file_err' => ''
            ];
            $this->view('premiseofficer/leaverequests/v_create_leaverequest', $data);
        }
    }

    // View Leave Request
    public function viewLeaveRequest($id) {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $leaveRequest = $this->leaveRequestModel->getLeaveRequestById($id);
        
        // Check if leave request exists and belongs to user
        if (!$leaveRequest || !$this->leaveRequestModel->isOwnedByUser($id, $_SESSION['user_id'])) {
            flash('msg', 'Leave request not found', 'alert-danger');
            redirect('premiseOfficer/leaverequests');
        }
        
        $data = [
            'title' => 'Leave Requests',
            'pageTitle' => 'Leave Request Details',
            'leaveRequest' => $leaveRequest
        ];
        $this->view('premiseofficer/leaverequests/v_view_request', $data);
    }

    // Edit Leave Request
    public function editLeaveRequest($id) {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $leaveRequest = $this->leaveRequestModel->getLeaveRequestById($id);
        
        // Check if leave request exists and belongs to user
        if (!$leaveRequest || !$this->leaveRequestModel->isOwnedByUser($id, $_SESSION['user_id'])) {
            flash('msg', 'Leave request not found', 'alert-danger');
            redirect('premiseOfficer/leaverequests');
        }

        // Check if request can be edited (only Pending or Rejected)
        if ($leaveRequest->status == 'Approved') {
            flash('msg', 'Cannot edit approved leave request', 'alert-danger');
            redirect('premiseOfficer/leaverequests');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission
            $data = [
                'title' => 'Leave Requests',
                'pageTitle' => 'Edit Leave Request',
                'leaveRequest' => $leaveRequest,
                'leave_type_value' => trim($_POST['leave_type'] ?? ''),
                'reason_value' => trim($_POST['reason'] ?? ''),
                'start_date_value' => trim($_POST['start_date'] ?? ''),
                'end_date_value' => trim($_POST['end_date'] ?? ''),
                'current_file' => $leaveRequest->proof_file,
                'leave_type_err' => '',
                'reason_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'proof_file_err' => ''
            ];
            
            // Validate leave type
            if (empty($data['leave_type_value'])) {
                $data['leave_type_err'] = 'Please select a leave type';
            }
            
            // Validate reason
            if (empty($data['reason_value'])) {
                $data['reason_err'] = 'Please enter a reason for leave';
            }
            
            // Validate start date
            if (empty($data['start_date_value'])) {
                $data['start_date_err'] = 'Please select a start date';
            }
            
            // Validate end date
            if (empty($data['end_date_value'])) {
                $data['end_date_err'] = 'Please select an end date';
            } elseif (!empty($data['start_date_value']) && strtotime($data['end_date_value']) < strtotime($data['start_date_value'])) {
                $data['end_date_err'] = 'End date must be after start date';
            }
            
            // Handle proof file upload/removal
            $proof_file_path = $leaveRequest->proof_file;
            
            // Check if user wants to remove existing file
            if (isset($_POST['remove_file']) && $_POST['remove_file'] == '1') {
                $proof_file_path = null;
            }
            
            // Handle new file upload
            if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] == UPLOAD_ERR_OK) {
                // Validate file type
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
                $file_type = $_FILES['proof_file']['type'];
                
                if (!in_array($file_type, $allowed_types)) {
                    $data['proof_file_err'] = 'Only JPG, PNG, GIF, and PDF files are allowed';
                } else {
                    // Upload file
                    $upload_dir = '/uploads/leave_proofs/';
                    $file_extension = pathinfo($_FILES['proof_file']['name'], PATHINFO_EXTENSION);
                    $unique_filename = 'proof_' . time() . '_' . uniqid() . '.' . $file_extension;
                    
                    if (uploadImage($_FILES['proof_file']['tmp_name'], $unique_filename, $upload_dir)) {
                        $proof_file_path = $upload_dir . $unique_filename;
                    } else {
                        $data['proof_file_err'] = 'Failed to upload proof file';
                    }
                }
            }
            
            // If no errors, update leave request
            if (empty($data['leave_type_err']) && empty($data['reason_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['proof_file_err'])) {
                $updateData = [
                    'leave_type' => $data['leave_type_value'],
                    'reason' => $data['reason_value'],
                    'start_date' => $data['start_date_value'],
                    'end_date' => $data['end_date_value'],
                    'proof_file' => $proof_file_path
                ];
                
                if ($this->leaveRequestModel->updateLeaveRequest($id, $updateData, $_SESSION['user_id'])) {
                    flash('msg', 'Leave request updated successfully', 'alert-success');
                    redirect('premiseOfficer/leaverequests');
                } else {
                    flash('msg', 'Failed to update leave request', 'alert-danger');
                    $this->view('premiseofficer/leaverequests/v_edit_request', $data);
                }
            } else {
                // Show form with errors
                $this->view('premiseofficer/leaverequests/v_edit_request', $data);
            }
        } else {
            // Show form with existing data
            $data = [
                'title' => 'Leave Requests',
                'pageTitle' => 'Edit Leave Request',
                'leaveRequest' => $leaveRequest,
                'leave_type_value' => $leaveRequest->leave_type,
                'reason_value' => $leaveRequest->reason,
                'start_date_value' => $leaveRequest->start_date,
                'end_date_value' => $leaveRequest->end_date,
                'current_file' => $leaveRequest->proof_file,
                'leave_type_err' => '',
                'reason_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'proof_file_err' => ''
            ];
            $this->view('premiseofficer/leaverequests/v_edit_request', $data);
        }
    }

    // Delete Leave Request
    public function deleteLeaveRequest($id) {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        // Verify ownership
        if (!$this->leaveRequestModel->isOwnedByUser($id, $_SESSION['user_id'])) {
            flash('msg', 'Unauthorized action', 'alert-danger');
            redirect('premiseOfficer/leaverequests');
        }

        if ($this->leaveRequestModel->deleteLeaveRequest($id, $_SESSION['user_id'])) {
            flash('msg', 'Leave request deleted successfully', 'alert-success');
        } else {
            flash('msg', 'Failed to delete leave request or request is already approved', 'alert-danger');
        }
        
        redirect('premiseOfficer/leaverequests');
    }


    public function messages() {
        $user_id = $_SESSION['user_id'] ?? null;
        
        $conversations = $this->messageModel->getConversations($user_id);
        $all_users = $this->messageModel->getAllUsersForPremiseOfficer($user_id);
        
        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages',
            'conversations' => $conversations,
            'all_users' => $all_users
        ];
        $this->view('premiseofficer/messages/v_messages', $data);
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

    public function notifications() {
        // TODO: Fetch notifications from database
        $notifications = $this->notificationModel->getNotifications($_SESSION['user_id']);
        
        $data = [
            'title' => 'Notifications',
            'pageTitle' => 'Notifications',
            'role' => 'premise officer',
            'notifications' => $notifications
        ];
        $this->view('components/notifications', $data);
    }

// ======================================================================== //
// =======================      profile       ====================== //
// ======================================================================== //
    // Profile action
    public function profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $data = [
            'title' => 'Profile',
            'pageTitle' => 'My Profile',
            'premiseofficer' => $this->premiseOfficerModel->getPremiseOfficerById($_SESSION['user_userID']),
        ];

        // Load view
        $this->view('premiseofficer/profile/v_profile', $data);
    }


     public function editProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $premiseofficer = $this->premiseOfficerModel->getPremiseOfficerById($_SESSION['user_userID']);
            
            $data = [
                'title' => 'Profile',
                'pageTitle' => 'Edit Profile',
                'premiseofficer' => $premiseofficer,
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
                } elseif (!password_verify($data['current_password'], $premiseofficer->password)) {
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
            $profileImageName = $premiseofficer->profile_image;
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
                        $profileImageName = $premiseofficer->profile_image; // Revert to old image
                    } else {
                        // Delete old image if it exists
                        $oldImagePath = PUB_ROOT . '/uploads/applicantPhotos/' . $premiseofficer->profile_image;
                        if (file_exists($oldImagePath) && $premiseofficer->profile_image !== 'default.png') {
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

                if ($this->premiseOfficerModel->updatePremiseOfficerProfile($_SESSION['user_id'], $updateData)) {
                    flash('msg', 'Profile updated successfully', 'alert-success');
                    redirect('PremiseOfficer/profile');
                } else {
                    flash('msg', 'Failed to update profile', 'alert-danger');
                    $data['premiseofficer'] = $this->premiseOfficerModel->getPremiseOfficerById($_SESSION['user_userID']);
                    $this->view('premiseofficer/profile/v_editProfile', $data);
                }
            } else {
                $data['premiseofficer'] = $this->premiseOfficerModel->getPremiseOfficerById($_SESSION['user_userID']);
                flash('msg', 'Please fix the errors in the form', 'alert-danger');
                $this->view('premiseofficer/profile/v_editProfile', $data);
            }
        } else {
            $premiseofficer = $this->premiseOfficerModel->getPremiseOfficerById($_SESSION['user_userID']);
            $data = [
                'title' => 'Profile',
                'pageTitle' => 'Edit Profile',
                'premiseofficer' => $premiseofficer,
                'name' => $premiseofficer->name ?? '',
                'email' => $premiseofficer->email ?? '',
                'phone_number' => $premiseofficer->phone_number ?? '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => '',
                'image_err' => ''
            ];
            $this->view('premiseofficer/profile/v_editProfile', $data);
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
?>