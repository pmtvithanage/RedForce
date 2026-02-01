<?php
class Supervisor extends Controller {
    private $supervisorModel;
    private $userModel;
    private $advertisementModel;
    private $notificationModel;

    public function __construct() {
        // Check if user is logged in and has supervisor role
        requireAuth('supervisor');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->supervisorModel = $this->model('M_supervisor');
        $this->userModel = $this->model('M_users');
        $this->notificationModel = $this->model('M_notifications');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('supervisor/dashboard/dashboard');
    }
    // Notifications
    public function notifications() {
        // TODO: Fetch notifications from database
        $notifications = $this->notificationModel->getNotifications($_SESSION['user_id']);
        
        $data = [
            'title' => 'Notifications',
            'pageTitle' => 'Notifications',
            'role' => 'supervisor',
            'notifications' => $notifications
        ];
        $this->view('components/notifications', $data);
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
        
        // Get recent activities
        $recentActivities = [];
        
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
            
            // Get recent activities
            $recentActivities = $this->supervisorModel->getRecentActivities($supervisor_id, 50);
        }

        $data = [
            'title' => 'Dashboard',
            'advertisements' => $advertisements,
            'todayAttendance' => $todayAttendance,
            'attendanceStats' => $attendanceStats,
            'recent_activities' => $recentActivities
        ];
        $this->view('supervisor/dashboard/v_dashboard', $data);
    }

    // Messages
    public function messages() {
        $user_id = $_SESSION['user_id'] ?? null;
        $messageModel = $this->model('M_message');
        
        $conversations = $messageModel->getConversations($user_id);
        $all_users = $messageModel->getAllUsersForSupervisor($user_id);
        
        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages',
            'conversations' => $conversations,
            'all_users' => $all_users
        ];
        $this->view('supervisor/messages/v_messages', $data);
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

    public function site_info(){
        $supervisorId = $_SESSION['user_id'] ?? null;
        
        if (!$supervisorId) {
            flash('msg', 'Session expired. Please login again.', 'alert-danger');
            redirect('users/login');
            return;
        }
        
        // Get officers and supervisors for this site
        $siteData = $this->supervisorModel->getSiteOfficers($supervisorId);
        
        // Get mobile riders for this site
        $mobileRiders = $this->supervisorModel->getSiteMobileRiders($supervisorId);
        
        $data = [
            'title' => 'Sites',
            'pageTitle' => 'Site Information',
            'officers' => $siteData['officers'],
            'supervisors' => $siteData['supervisors'],
            'site' => $siteData['site'],
            'mobile_riders' => $mobileRiders
        ];
        
        $this->view('supervisor/site/v_site_info', $data);
    }

    // Incidents - List all incidents
    public function incidents() {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            flash('msg', 'Session expired. Please login again.', 'alert-danger');
            redirect('users/login');
            return;
        }
        
        // Get incidents for this supervisor
        $incidents = $this->supervisorModel->getIncidentsByUserId($userId);
        
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
        
        $this->view('supervisor/incidents/v_incidents', $data);
    }

    // Create Incident
    public function createIncident()
    {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            flash('msg', 'Session expired. Please login again.', 'alert-danger');
            redirect('users/login');
            return;
        }
        
        // Get sites assigned to this supervisor only
        $sites = $this->supervisorModel->getAssignedSites($userId);
        
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
                    'officer_role' => 'supervisor',
                    'site_id' => $data['site_id'],
                    'property_site' => $data['site_id'],
                    'incident_type' => $data['incident_type'],
                    'incident_date' => $data['incident_date'],
                    'incident_time' => $data['incident_time'],
                    'incident_description' => $data['description'],
                    'action_taken' => $data['actions_taken'],
                    'severity' => $data['priority'],
                    'priority' => $data['priority'],
                    'people_involved' => $data['people_involved'],
                    'additional_details' => null,
                    'media_files' => !empty($uploadedFiles) ? implode(',', $uploadedFiles) : null,
                    'latitude' => !empty($data['latitude']) ? $data['latitude'] : null,
                    'longitude' => !empty($data['longitude']) ? $data['longitude'] : null,
                    'status' => 'Pending'
                ];
                
                // Add incident to database
                if ($this->supervisorModel->addIncident($incidentData)) {
                    // Log activity
                    $this->supervisorModel->logActivity([
                        'user_id' => $userId,
                        'activity_type' => 'incident',
                        'activity_titel' => 'New Incident Reported',
                        'activity_details' => "Reported incident: {$data['incident_type']} at site ID {$data['site_id']}"
                    ]);
                    
                    flash('incident_message', 'Incident reported successfully!', 'alert alert-success');
                    redirect('supervisor/incidents');
                } else {
                    flash('incident_message', 'Error submitting incident report. Please try again.', 'alert alert-danger');
                }
            }
            
            // Load view with errors
            $this->view('supervisor/incidents/v_create_Incident', $data);
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
            $this->view('supervisor/incidents/v_create_Incident', $data);
        }
    }

    // View Incident Detail
    public function viewIncident($id = null)
    {
        // Get incident ID from parameter or query string
        $incidentId = $id ?? $_GET['id'] ?? null;
        
        if (!$incidentId) {
            flash('incident_message', 'Invalid incident ID.', 'alert alert-danger');
            redirect('supervisor/incidents');
            return;
        }
        
        // Get incident details
        $incident = $this->supervisorModel->getIncidentById($incidentId);
        
        if (!$incident) {
            flash('incident_message', 'Incident not found.', 'alert alert-danger');
            redirect('supervisor/incidents');
            return;
        }
        
        // Verify this incident belongs to the current user
        $userId = $_SESSION['user_id'] ?? null;
        if ($incident->user_id != $userId) {
            flash('incident_message', 'Unauthorized access.', 'alert alert-danger');
            redirect('supervisor/incidents');
            return;
        }
        
        // Get reviews for this incident
        $reviews = $this->supervisorModel->getIncidentReviews($incidentId);
        
        $data = [
            'title' => 'Incidents',
            'pageTitle' => 'Incident Details',
            'incident' => $incident,
            'reviews' => $reviews
        ];
        
        $this->view('supervisor/incidents/v_view_incident', $data);
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
                redirect('supervisor/incidents');
                return;
            }
            
            // Validate input
            if (empty($_POST['review_title']) || empty($_POST['review_details'])) {
                flash('incident_message', 'Review title and details are required.', 'alert alert-danger');
                redirect('supervisor/viewIncident/' . $incidentId);
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
            if ($this->supervisorModel->addIncidentReview($reviewData)) {
                // Log activity
                $this->supervisorModel->logActivity([
                    'user_id' => $userId,
                    'activity_type' => 'incident_review',
                    'activity_titel' => 'Added Review to Incident',
                    'activity_details' => "Added review to incident #{$incidentId}: {$reviewData['review_title']}"
                ]);
                
                flash('incident_message', 'Review added successfully!', 'alert alert-success');
            } else {
                flash('incident_message', 'Error adding review. Please try again.', 'alert alert-danger');
            }
            
            redirect('supervisor/viewIncident/' . $incidentId);
        } else {
            redirect('supervisor/incidents');
        }
    }

    // ======================================================================== //
    // =======================      Messaging          ====================== //
    // ======================================================================== //

    // Get conversations (AJAX)
    public function getConversations() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            
            if (!$user_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            $messageModel = $this->model('M_message');
            $conversations = $messageModel->getConversations($user_id);
            echo json_encode(['status' => 'success', 'conversations' => $conversations]);
        }
    }

    // Load messages (AJAX)
    public function loadMessages() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $sender_id = $_SESSION['user_id'] ?? null;
            $recipient_id = $_POST['recipient_id'] ?? null;
            
            if (!$sender_id || !$recipient_id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid user']);
                return;
            }
            
            $messageModel = $this->model('M_message');
            // Mark messages as read
            $messageModel->markAsRead($recipient_id, $sender_id);
            
            // Get messages
            $messages = $messageModel->getMessages($sender_id, $recipient_id);
            echo json_encode(['status' => 'success', 'messages' => $messages]);
        }
    }

    // Send message (AJAX)
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
            
            $messageModel = $this->model('M_message');
            if ($messageModel->sendMessage($sender_id, $recipient_id, $message)) {
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

    // Mark messages as seen (AJAX)
    public function markAsSeen() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $sender_id = $_SESSION['user_id'] ?? null;
            $recipient_id = $_POST['recipient_id'] ?? null;
            
            if (!$sender_id || !$recipient_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            $messageModel = $this->model('M_message');
            // Mark messages as seen
            $messageModel->markAsSeen($sender_id, $recipient_id);
            
            echo json_encode(['status' => 'success']);
        }
    }

    // Delete message (AJAX)
    public function deleteMessage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            $message_id = $_POST['message_id'] ?? null;
            
            if (!$user_id || !$message_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            $messageModel = $this->model('M_message');
            if ($messageModel->deleteMessage($message_id, $user_id)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    // Update message (AJAX)
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
            
            $messageModel = $this->model('M_message');
            if ($messageModel->updateMessage($message_id, $user_id, $message)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update message']);
            }
        }
    }

    // Get user online status (AJAX)
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

    // Update user last seen (AJAX)
    public function updateLastSeen() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            
            if ($user_id) {
                $this->userModel->updateLastSeen($user_id);
            }
        }
    }

    // Set user offline (AJAX)
    public function setOffline() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            
            if ($user_id) {
                $this->userModel->setUserOffline($user_id);
            }
        }
    }

}
