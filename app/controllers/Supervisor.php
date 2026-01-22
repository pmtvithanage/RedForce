<?php
class Supervisor extends Controller {
    private $supervisorModel;
    private $userModel;
    private $advertisementModel;
    private $messageModel;

    public function __construct() {
        // Check if user is logged in and has supervisor role
        requireAuth('supervisor');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->supervisorModel = $this->model('M_supervisor');
        $this->userModel = $this->model('M_users');
        // reuse messaging methods from M_mobilerider
        $this->messageModel = $this->model('M_mobilerider');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('supervisor/dashboard');
    }

    // dashboard
    public function dashboard() {
        $role = 'supervisor';
        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);
        
        // Get supervisor ID from session
        $supervisor_id = $_SESSION['user_id'] ?? null;
        
        // Get today's attendance records
        $todayAttendance = [];
        $attendanceStats = [
            'total' => 0,
            'present' => 0,
            'absent' => 0,
            'late' => 0
        ];
        
        // Get total unique officers count
        $totalOfficers = 0;
        
        if ($supervisor_id) {
            // Get today's date
            $today = date('Y-m-d');
            
            // Fetch attendance records for today
            $todayAttendance = $this->supervisorModel->getAttendanceRecords($supervisor_id, ['date' => $today]);
            
            // Get total unique officers count
            $totalOfficers = $this->supervisorModel->getTotalOfficersCount($supervisor_id);
            
            // Get attendance statistics
            $stats = $this->supervisorModel->getAttendanceStats($supervisor_id, $today);
            if ($stats) {
                $attendanceStats = [
                    'total' => $totalOfficers, // Use total unique officers instead of today's count
                    'present' => $stats->present ?? 0,
                    'absent' => $stats->absent ?? 0,
                    'late' => $stats->late ?? 0
                ];
            } else {
                $attendanceStats['total'] = $totalOfficers;
            }
        }

        $data = [
            'title' => 'Dashboard',
            'advertisements' => $advertisements,
            'todayAttendance' => $todayAttendance,
            'attendanceStats' => $attendanceStats
        ];
        $this->view('supervisor/v_dashboard', $data);
    }

    // officers
    public function officers() {
        $data = [
            'title' => 'Officers',
        ];
        $this->view('supervisor/v_officers', $data);
    }
    // ==================== MESSAGES (Supervisor) ====================
    public function messages()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            redirect('supervisor/dashboard');
            return;
        }

        $conversations = $this->messageModel->getConversations($user_id);
        $all_users = $this->messageModel->getAllUsers($user_id);
        $unread_count = $this->messageModel->getUnreadCount($user_id);

        $data = [
            'title' => 'Messages',
            'conversations' => $conversations,
            'all_users' => $all_users,
            'unread_count' => $unread_count,
            'current_recipient_id' => isset($_GET['with']) ? $_GET['with'] : null
        ];

        $this->view('supervisor/v_messages', $data);
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

    //Leave Requests - Display page with all leave requests
    public function leaverequests() {
        // Get supervisor ID from session
        $supervisor_id = $_SESSION['user_id'] ?? null;
        
        // Fetch all leave requests
        $leaveRequests = [];
        if ($supervisor_id) {
            $leaveRequests = $this->supervisorModel->getLeaveRequests($supervisor_id);
        }
        
        $data = [
            'title' => 'Leave Requests',
            'leaveRequests' => $leaveRequests
        ];
        $this->view('supervisor/v_leaverequests', $data);  
    }

    //Leave Requests - Alias for backwards compatibility
    public function leave_requests() {
        $this->leaverequests();
    }

    // CREATE - Add new leave request
    public function addLeave() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // Get supervisor ID from session
            $supervisor_id = $_SESSION['user_id'] ?? null;
            
            if (!$supervisor_id) {
                flash('leave_error', 'User not authenticated');
                redirect('supervisor/leaverequests');
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
                $file_name = 'leave_' . $supervisor_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['proof_file']['tmp_name'], $upload_path)) {
                    // Store full path in database
                    $proof_file = $upload_dir . $file_name;
                }
            }
            
            // Convert date format from DD/MM/YYYY to YYYY-MM-DD
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            
            // Convert if in DD/MM/YYYY format
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
            
            // Prepare data
            $data = [
                'supervisor_id' => $supervisor_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            // Validate
            if (empty($data['leave_type']) || empty($data['reason']) || empty($data['start_date']) || empty($data['end_date'])) {
                flash('leave_error', 'Please fill all required fields');
                redirect('supervisor/leaverequests');
                return;
            }
            
            // Add leave request
            if ($this->supervisorModel->addLeaveRequest($data)) {
                flash('leave_success', 'Leave request submitted successfully');
            } else {
                flash('leave_error', 'Something went wrong. Please try again');
            }
            
            redirect('supervisor/leaverequests');
        } else {
            redirect('supervisor/leaverequests');
        }
    }

    // UPDATE - Edit leave request
    public function editLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $supervisor_id = $_SESSION['user_id'] ?? null;
            
            if (!$supervisor_id) {
                flash('leave_error', 'User not authenticated');
                redirect('supervisor/leaverequests');
                return;
            }
            
            // Get existing leave request
            $existingLeave = $this->supervisorModel->getLeaveRequestById($id);
            
            if (!$existingLeave || $existingLeave->caretaker_id != $supervisor_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('supervisor/leaverequests');
                return;
            }
            
            // Handle file upload
            $proof_file = $existingLeave->proof_file; // Keep existing file
            if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] == 0) {
                $upload_dir = 'uploads/leaverequest/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['proof_file']['name'], PATHINFO_EXTENSION);
                $file_name = 'leave_' . $supervisor_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['proof_file']['tmp_name'], $upload_path)) {
                    // Delete old file if exists
                    if ($existingLeave->proof_file && file_exists($existingLeave->proof_file)) {
                        unlink($existingLeave->proof_file);
                    }
                    // Store full path in database
                    $proof_file = $upload_dir . $file_name;
                }
            }
            
            // Convert date format from DD/MM/YYYY to YYYY-MM-DD
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            
            // Convert if in DD/MM/YYYY format
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
                'supervisor_id' => $supervisor_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            if ($this->supervisorModel->updateLeaveRequest($data)) {
                flash('leave_success', 'Leave request updated successfully');
            } else {
                flash('leave_error', 'Failed to update leave request');
            }
            
            redirect('supervisor/leaverequests');
        } else {
            redirect('supervisor/leaverequests');
        }
    }

    // DELETE - Remove leave request
    public function deleteLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supervisor_id = $_SESSION['user_id'] ?? null;
            
            if (!$supervisor_id) {
                flash('leave_error', 'User not authenticated');
                redirect('supervisor/leaverequests');
                return;
            }
            
            // Get leave request to verify ownership and get file
            $leave = $this->supervisorModel->getLeaveRequestById($id);
            
            if (!$leave || $leave->caretaker_id != $supervisor_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('supervisor/leaverequests');
                return;
            }
            
            // Delete file if exists
            if ($leave->proof_file) {
                // proof_file already contains full path
                if (file_exists($leave->proof_file)) {
                    unlink($leave->proof_file);
                }
            }
            
            if ($this->supervisorModel->deleteLeaveRequest($id, $supervisor_id)) {
                flash('leave_success', 'Leave request deleted successfully');
            } else {
                flash('leave_error', 'Failed to delete leave request');
            }
            
            redirect('supervisor/leaverequests');
        } else {
            redirect('supervisor/leaverequests');
        }
    }

    //Profile
    public function profile() {
        $data = [
            'title' => 'Profile',
        ];
        $this->view('supervisor/v_profile', $data);
    }

    // Mark Attendance via QR Scanner
    public function markAttendance() {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Get supervisor ID from session
        $supervisor_id = $_SESSION['user_id'] ?? null;
        
        if (!$supervisor_id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User not authenticated']);
            return;
        }
        
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['officer_id']) || !isset($input['name'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid QR code data']);
            return;
        }
        
        $officer_id = trim($input['officer_id']);
        $officer_name = trim($input['name']);
        $timestamp = $input['timestamp'] ?? date('Y-m-d H:i:s');
        
        // Validate officer_id
        if (empty($officer_id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Officer ID is required']);
            return;
        }
        
        // Mark attendance in database
        $result = $this->supervisorModel->markAttendance($officer_id, $supervisor_id, $timestamp);
        
        header('Content-Type: application/json');
        if ($result === true) {
            echo json_encode([
                'success' => true, 
                'message' => 'Attendance marked successfully for ' . $officer_name
            ]);
        } elseif ($result === 'duplicate') {
            echo json_encode([
                'success' => false, 
                'message' => 'Attendance already marked for today'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to mark attendance. Please try again.'
            ]);
        }
    }

    // ==========================================
    // OFFICER ATTENDANCE CRUD METHODS
    // ==========================================

    // Display attendance page
    public function attendance() {
        $supervisor_id = $_SESSION['user_id'] ?? null;
        
        if (!$supervisor_id) {
            redirect('users/login');
            return;
        }
        
        // Get filters from GET request
        $filters = [
            'date' => $_GET['date'] ?? '',
            'status' => $_GET['status'] ?? '',
            'officer_id' => $_GET['officer_id'] ?? ''
        ];
        
        // Fetch attendance records with filters
        $attendanceRecords = $this->supervisorModel->getAttendanceRecords($supervisor_id, $filters);
        
        // Get today's statistics
        $today = date('Y-m-d');
        $stats = $this->supervisorModel->getAttendanceStats($supervisor_id, $today);
        
        $data = [
            'title' => 'Attendance',
            'pageTitle' => 'Officer Attendance',
            'attendanceRecords' => $attendanceRecords,
            'stats' => $stats,
            'filters' => $filters
        ];
        
        $this->view('supervisor/v_attendance', $data);
    }

    // Show mark attendance form page
    public function markAttendancePage() {
        $supervisor_id = $_SESSION['user_id'] ?? null;
        
        if (!$supervisor_id) {
            redirect('users/login');
            return;
        }
        
        $data = [
            'title' => 'Mark Attendance',
            'pageTitle' => 'Mark Attendance'
        ];
        
        $this->view('supervisor/v_mark_attendance', $data);
    }

    // Show edit attendance form page
    public function editAttendancePage($id) {
        $supervisor_id = $_SESSION['user_id'] ?? null;
        
        if (!$supervisor_id) {
            redirect('users/login');
            return;
        }
        
        // Get attendance record
        $attendance = $this->supervisorModel->getAttendanceById($id);
        
        // Verify ownership
        if (!$attendance || $attendance->supervisor_id != $supervisor_id) {
            flash('attendance_error', 'Unauthorized access');
            redirect('supervisor/attendance');
            return;
        }
        
        $data = [
            'title' => 'Edit Attendance',
            'pageTitle' => 'Edit Attendance',
            'attendance' => $attendance
        ];
        
        $this->view('supervisor/v_edit_attendance', $data);
    }

    // CREATE - Add new attendance record
    public function addAttendance() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $supervisor_id = $_SESSION['user_id'] ?? null;
            
            if (!$supervisor_id) {
                flash('attendance_error', 'User not authenticated');
                redirect('supervisor/markAttendancePage');
                return;
            }
            
            $data = [
                'supervisor_id' => $supervisor_id,
                'officer_id' => trim($_POST['officer_id']),
                'officer_name' => trim($_POST['officer_name']),
                'attendance_date' => trim($_POST['attendance_date']),
                'check_in_time' => !empty($_POST['check_in_time']) ? trim($_POST['check_in_time']) : null,
                'check_out_time' => !empty($_POST['check_out_time']) ? trim($_POST['check_out_time']) : null,
                'status' => trim($_POST['status']),
                'notes' => trim($_POST['notes'])
            ];
            
            // Validate required fields
            if (empty($data['officer_id']) || empty($data['officer_name']) || 
                empty($data['attendance_date']) || empty($data['status'])) {
                flash('attendance_error', 'Please fill all required fields');
                redirect('supervisor/markAttendancePage');
                return;
            }
            
            if ($this->supervisorModel->addAttendance($data)) {
                flash('attendance_success', 'Attendance record added successfully');
            } else {
                flash('attendance_error', 'Failed to add attendance record. Officer may already have attendance for this date.');
            }
            
            redirect('supervisor/attendance');
        } else {
            redirect('supervisor/markAttendancePage');
        }
    }

    // UPDATE - Edit attendance record
    public function updateAttendance($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $supervisor_id = $_SESSION['user_id'] ?? null;
            
            if (!$supervisor_id) {
                flash('attendance_error', 'User not authenticated');
                redirect('supervisor/attendance');
                return;
            }
            
            // Verify ownership
            $existingRecord = $this->supervisorModel->getAttendanceById($id);
            if (!$existingRecord || $existingRecord->supervisor_id != $supervisor_id) {
                flash('attendance_error', 'Unauthorized access');
                redirect('supervisor/attendance');
                return;
            }
            
            $data = [
                'id' => $id,
                'supervisor_id' => $supervisor_id,
                'officer_id' => trim($_POST['officer_id']),
                'officer_name' => trim($_POST['officer_name']),
                'attendance_date' => trim($_POST['attendance_date']),
                'check_in_time' => !empty($_POST['check_in_time']) ? trim($_POST['check_in_time']) : null,
                'check_out_time' => !empty($_POST['check_out_time']) ? trim($_POST['check_out_time']) : null,
                'status' => trim($_POST['status']),
                'notes' => trim($_POST['notes'])
            ];
            
            if ($this->supervisorModel->updateAttendance($data)) {
                flash('attendance_success', 'Attendance record updated successfully');
            } else {
                flash('attendance_error', 'Failed to update attendance record');
            }
            
            redirect('supervisor/attendance');
        } else {
            redirect('supervisor/attendance');
        }
    }

    // DELETE - Remove attendance record
    public function deleteAttendance($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supervisor_id = $_SESSION['user_id'] ?? null;
            
            if (!$supervisor_id) {
                flash('attendance_error', 'User not authenticated');
                redirect('supervisor/attendance');
                return;
            }
            
            // Verify ownership
            $record = $this->supervisorModel->getAttendanceById($id);
            if (!$record || $record->supervisor_id != $supervisor_id) {
                flash('attendance_error', 'Unauthorized access');
                redirect('supervisor/attendance');
                return;
            }
            
            if ($this->supervisorModel->deleteAttendance($id, $supervisor_id)) {
                flash('attendance_success', 'Attendance record deleted successfully');
            } else {
                flash('attendance_error', 'Failed to delete attendance record');
            }
            
            redirect('supervisor/attendance');
        } else {
            redirect('supervisor/attendance');
        }
    }

}