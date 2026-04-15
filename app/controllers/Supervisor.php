<?php
class Supervisor extends Controller {
    private $supervisorModel;
    private $userModel;
    private $advertisementModel;
    private $notificationModel;
    private $leaveRequestModel;

    public function __construct() {
        // Check if user is logged in and has supervisor role
        requireAuth('supervisor');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->supervisorModel = $this->model('M_supervisor');
        $this->userModel = $this->model('M_users');
        $this->notificationModel = $this->model('M_notifications');
        $this->leaveRequestModel = $this->model('M_leaveRequests');
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

// ======================================================================== //
// =======================      profile       ====================== //
// ======================================================================== //
    //Profile
    public function profile() {
        $data = [
            'title' => 'Profile',
            'pageTitle' => 'My Profile',
            'supervisor' => $this->supervisorModel->getSupervisorById($_SESSION['user_userID'])
        ];
        $this->view('supervisor/profile/v_profile', $data);
    }

     public function editProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supervisor = $this->supervisorModel->getSupervisorById($_SESSION['user_id']);
            
            $data = [
                'title' => 'Profile',
                'pageTitle' => 'Edit Profile',
                'supervisor' => $supervisor,
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
                } elseif (!password_verify($data['current_password'], $supervisor->password)) {
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
            $profileImageName = $supervisor->profile_image;
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
                        $profileImageName = $supervisor->profile_image; // Revert to old image
                    } else {
                        // Delete old image if it exists
                        $oldImagePath = PUB_ROOT . '/uploads/applicantPhotos/' . $supervisor->profile_image;
                        if (file_exists($oldImagePath) && $supervisor->profile_image !== 'default.png') {
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

                if ($this->supervisorModel->updateSupervisorProfile($_SESSION['user_id'], $updateData)) {
                    flash('msg', 'Profile updated successfully', 'alert-success');
                    redirect('Supervisor/profile');
                } else {
                    flash('msg', 'Failed to update profile', 'alert-danger');
                    $data['supervisor'] = $this->supervisorModel->getSupervisorById($_SESSION['user_userID']);
                    $this->view('supervisor/profile/v_editProfile', $data);
                }
            } else {
                $data['supervisor'] = $this->supervisorModel->getSupervisorById($_SESSION['user_userID']);
                flash('msg', 'Please fix the errors in the form', 'alert-danger');
                $this->view('supervisor/profile/v_editProfile', $data);
            }
        } else {
            $supervisor = $this->supervisorModel->getSupervisorById($_SESSION['user_userID']);
            $data = [
                'title' => 'Profile',
                'pageTitle' => 'Edit Profile',
                'supervisor' => $supervisor,
                'name' => $supervisor->name ?? '',
                'email' => $supervisor->email ?? '',
                'phone_number' => $supervisor->phone_number ?? '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => '',
                'image_err' => ''
            ];
            $this->view('supervisor/profile/v_editProfile', $data);
        }
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
        
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $officerSearch = trim($_GET['officer_id'] ?? '');

        // Get filters from GET request
        $filters = [
            'date' => $selectedDate,
            'officer_id' => $officerSearch
        ];

        $site = $this->supervisorModel->getSupervisorAttendanceSite($supervisor_id);
        $eligibleStaff = $this->supervisorModel->getAttendanceEligibleStaff($supervisor_id);
        $dutyPoints = $this->supervisorModel->getAttendanceDutyPoints($supervisor_id);

        $todayDate = date('Y-m-d');
        $isPastDate = strtotime($selectedDate) < strtotime($todayDate);

        // Get all marked attendance for selected date and map by staff code.
        $markedRecords = $this->supervisorModel->getAttendanceRecords($supervisor_id, ['date' => $selectedDate]);
        $markedByOfficerCode = [];
        foreach ($markedRecords as $record) {
            $markedByOfficerCode[(string)$record->officer_id] = $record;
        }

        // Build visible records from current site staff list.
        // Rule: only infer absent for past dates (after day has ended).
        $attendanceRecords = [];
        foreach ($eligibleStaff as $staff) {
            $staffCode = !empty($staff->staff_code) ? trim($staff->staff_code) : (string)$staff->staff_user_id;
            $staffName = trim($staff->staff_name ?? '');

            if ($officerSearch !== '') {
                $inCode = stripos($staffCode, $officerSearch) !== false;
                $inName = stripos($staffName, $officerSearch) !== false;
                if (!$inCode && !$inName) {
                    continue;
                }
            }

            if (isset($markedByOfficerCode[$staffCode])) {
                $row = $markedByOfficerCode[$staffCode];
                if (empty($row->staff_role)) {
                    $row->staff_role = $staff->staff_role;
                }
                $attendanceRecords[] = $row;
                continue;
            }

            if ($isPastDate) {
                $absentRow = new stdClass();
                $absentRow->attendance_date = $selectedDate;
                $absentRow->officer_id = $staffCode;
                $absentRow->officer_name = $staffName;
                $absentRow->staff_role = $staff->staff_role;
                $absentRow->duty_point = '-';
                $absentRow->status = 'Absent';
                $absentRow->notes = 'Not marked';
                $attendanceRecords[] = $absentRow;
            }
        }

        usort($attendanceRecords, function ($a, $b) {
            return strcmp((string)$a->officer_name, (string)$b->officer_name);
        });

        $presentCount = 0;
        $absentCount = 0;
        foreach ($attendanceRecords as $record) {
            if (($record->status ?? '') === 'Present') {
                $presentCount++;
            } elseif (($record->status ?? '') === 'Absent') {
                $absentCount++;
            }
        }

        $stats = (object)[
            'total_officers' => count($attendanceRecords),
            'present' => $presentCount,
            'absent' => $absentCount
        ];
        
        $data = [
            'title' => 'Attendance',
            'pageTitle' => 'Attendance',
            'attendanceRecords' => $attendanceRecords,
            'stats' => $stats,
            'filters' => $filters,
            'site' => $site,
            'eligibleStaff' => $eligibleStaff,
            'dutyPoints' => $dutyPoints,
            'selectedDate' => $selectedDate
        ];
        
        $this->view('supervisor/v_attendance', $data);
    }

    // Show mark attendance form page
    public function markAttendancePage() {
        redirect('supervisor/attendance');
    }

    // CREATE - Add a duty point in supervisor's assigned site
    public function addDutyPoint() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('supervisor/attendance');
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $supervisor_id = $_SESSION['user_id'] ?? null;
        if (!$supervisor_id) {
            flash('attendance_error', 'User not authenticated');
            redirect('users/login');
            return;
        }

        $dutyPointName = trim($_POST['duty_point_name'] ?? '');
        if ($dutyPointName === '') {
            flash('attendance_error', 'Duty point name is required');
            redirect('supervisor/attendance');
            return;
        }

        if (strlen($dutyPointName) > 120) {
            flash('attendance_error', 'Duty point name is too long');
            redirect('supervisor/attendance');
            return;
        }

        if ($this->supervisorModel->addAttendanceDutyPoint($supervisor_id, $dutyPointName)) {
            flash('attendance_success', 'Duty point added successfully');
        } else {
            flash('attendance_error', 'Failed to add duty point. Make sure you are assigned as a supervisor to a site.');
        }

        redirect('supervisor/attendance');
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
            
            $staffUserId = (int)($_POST['staff_user_id'] ?? 0);
            $attendanceDate = trim($_POST['attendance_date'] ?? '');
            $notes = trim($_POST['notes'] ?? '');
            $dutyPointId = (int)($_POST['duty_point_id'] ?? 0);

            if ($staffUserId <= 0 || $attendanceDate === '' || $dutyPointId <= 0) {
                flash('attendance_error', 'Please fill all required fields correctly');
                redirect('supervisor/attendance');
                return;
            }

            $staff = $this->supervisorModel->getValidAttendanceStaffMember($supervisor_id, $staffUserId);
            if (!$staff) {
                flash('attendance_error', 'Invalid staff member. You can only mark attendance for officers/caretakers in your site.');
                redirect('supervisor/attendance');
                return;
            }

            $dutyPoint = $this->supervisorModel->isValidDutyPointForSupervisor($supervisor_id, $dutyPointId);
            if (!$dutyPoint) {
                flash('attendance_error', 'Invalid duty point selected');
                redirect('supervisor/attendance');
                return;
            }

            $data = [
                'supervisor_id' => $supervisor_id,
                'officer_id' => !empty($staff->staff_code) ? trim($staff->staff_code) : (string)$staff->staff_user_id,
                'officer_name' => trim($staff->staff_name),
                'attendance_date' => $attendanceDate,
                'check_in_time' => null,
                'check_out_time' => null,
                'status' => 'Present',
                'notes' => $notes,
                'duty_point' => $dutyPoint->duty_point_name,
                'staff_role' => $staff->staff_role
            ];
            
            if ($this->supervisorModel->addAttendance($data)) {
                flash('attendance_success', 'Attendance record added successfully');
            } else {
                flash('attendance_error', 'Failed to add attendance record');
            }
            
            redirect('supervisor/attendance?date=' . urlencode($attendanceDate));
        } else {
            redirect('supervisor/attendance');
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
        
        // Get caretakers for this site
        $caretakers = $this->supervisorModel->getSiteCaretakers($supervisorId);

        // Get this supervisor's existing officer ratings
        $officerRatings = $this->supervisorModel->getSupervisorOfficerRatings($supervisorId);

        $ratingSuccess = $_SESSION['supervisor_rating_success'] ?? '';
        $ratingError = $_SESSION['supervisor_rating_error'] ?? '';
        unset($_SESSION['supervisor_rating_success'], $_SESSION['supervisor_rating_error']);
        
        $data = [
            'title' => 'Sites',
            'pageTitle' => 'Site Information',
            'officers' => $siteData['officers'],
            'supervisors' => $siteData['supervisors'],
            'site' => $siteData['site'],
            'mobile_riders' => $mobileRiders,
            'caretakers' => $caretakers,
            'officer_ratings' => $officerRatings,
            'rating_success' => $ratingSuccess,
            'rating_error' => $ratingError
        ];
        
        $this->view('supervisor/site/v_site_info', $data);
    }

    public function saveOfficerRating() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('supervisor/site_info');
            return;
        }

        $supervisorId = $_SESSION['user_id'] ?? null;
        $officerUserId = (int)($_POST['officer_user_id'] ?? 0);
        $ratingValue = (int)($_POST['rating_value'] ?? 0);
        $ratingDate = trim((string)($_POST['rating_date'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        if (!$supervisorId || $officerUserId <= 0) {
            $_SESSION['supervisor_rating_error'] = 'Invalid request for officer rating.';
            redirect('supervisor/site_info');
            return;
        }

        $dateObj = DateTime::createFromFormat('Y-m-d', $ratingDate);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $ratingDate) {
            $_SESSION['supervisor_rating_error'] = 'Invalid rating date.';
            redirect('supervisor/site_info');
            return;
        }

        if ($ratingValue < 1 || $ratingValue > 5) {
            $_SESSION['supervisor_rating_error'] = 'Rating must be between 1 and 5.';
            redirect('supervisor/site_info');
            return;
        }

        if (!$this->supervisorModel->canSupervisorRateOfficer($supervisorId, $officerUserId, $ratingDate)) {
            $_SESSION['supervisor_rating_error'] = 'You can only rate premise officers assigned to your site on that date.';
            redirect('supervisor/site_info');
            return;
        }

        if ($this->supervisorModel->saveSupervisorOfficerRating($supervisorId, $officerUserId, $ratingDate, $ratingValue, $description)) {
            $_SESSION['supervisor_rating_success'] = 'Officer rating saved successfully.';
        } else {
            $_SESSION['supervisor_rating_error'] = 'Failed to save officer rating.';
        }

        redirect('supervisor/site_info');
    }

    public function deleteOfficerRating() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('supervisor/site_info');
            return;
        }

        $supervisorId = $_SESSION['user_id'] ?? null;
        $officerUserId = (int)($_POST['officer_user_id'] ?? 0);
        $ratingDate = trim((string)($_POST['rating_date'] ?? ''));

        if (!$supervisorId || $officerUserId <= 0) {
            $_SESSION['supervisor_rating_error'] = 'Invalid delete request for officer rating.';
            redirect('supervisor/site_info');
            return;
        }

        $dateObj = DateTime::createFromFormat('Y-m-d', $ratingDate);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $ratingDate) {
            $_SESSION['supervisor_rating_error'] = 'Invalid rating date.';
            redirect('supervisor/site_info');
            return;
        }

        if ($this->supervisorModel->deleteSupervisorOfficerRating($supervisorId, $officerUserId, $ratingDate)) {
            $_SESSION['supervisor_rating_success'] = 'Officer rating deleted successfully.';
        } else {
            $_SESSION['supervisor_rating_error'] = 'Failed to delete officer rating.';
        }

        redirect('supervisor/site_info');
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
                    
                    // Send notifications to mobile riders and admins
                    $this->sendIncidentNotifications($userId, $data['site_id'], $data['incident_type'], $data['priority']);
                    
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
                
                // Send notifications to related users
                $this->sendIncidentReviewNotifications($userId, $incidentId, $reviewData);
                
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

    /**
     * Send notifications when an incident is reported
     * Sends to all admins and mobile riders assigned to the site
     */
    private function sendIncidentNotifications($supervisorId, $siteId, $incidentType, $priority) {
        // Get supervisor details
        $supervisor = $this->userModel->getUserById($supervisorId);
        $supervisorName = $supervisor->name ?? 'A supervisor';
        
        // Get site name (if available)
        $siteName = "Site ID: {$siteId}";
        
        // Prepare notification details
        $notificationTitle = "New Incident Reported";
        $notificationMessage = "{$supervisorName} reported a {$incidentType} incident at {$siteName}. Priority: {$priority}";
        $notificationLink = URL_ROOT . '/admin/incidents'; // Admins can view all incidents
        $notificationType = ($priority == 'High' || $priority == 'Critical') ? 'warning' : 'info';
        $notificationIcon = 'warning';
        
        // 1. Send notifications to all admins
        $adminModel = $this->model('M_admin');
        $admins = $adminModel->getAllAdmins();
        
        if ($admins && is_array($admins)) {
            foreach ($admins as $admin) {
                $this->notificationModel->insertNotification(
                    $admin->id,
                    $notificationType,
                    $notificationTitle,
                    $notificationMessage,
                    $notificationLink,
                    $notificationIcon,
                    $supervisorId
                );
            }
        }
        
        // 2. Send notifications to mobile riders assigned to the supervisor's site
        $mobileRiders = $this->supervisorModel->getSiteMobileRiders($supervisorId);
        
        if ($mobileRiders && is_array($mobileRiders)) {
            $riderNotificationLink = URL_ROOT . '/MobileRider/incidents'; // Mobile riders view
            
            foreach ($mobileRiders as $rider) {
                $this->notificationModel->insertNotification(
                    $rider->user_id,
                    $notificationType,
                    $notificationTitle,
                    $notificationMessage,
                    $riderNotificationLink,
                    $notificationIcon,
                    $supervisorId
                );
            }
        }
    }

    /**
     * Send notifications when an incident review is added
     * For Supervisor: Notify admins + mobile riders of the site
     */
    private function sendIncidentReviewNotifications($reviewerId, $incidentId, $reviewData) {
        try {
            // Get incident details
            $incident = $this->supervisorModel->getIncidentById($incidentId);
            
            if (!$incident) {
                error_log("Incident not found: {$incidentId}");
                return;
            }
            
            // Get reviewer details
            $reviewer = $this->userModel->getUserById($reviewerId);
            $reviewerName = $reviewer->name ?? 'A user';
            $reviewerRole = $reviewer->role ?? 'supervisor';
            
            // Prepare notification details
            $notificationTitle = "New Review on Incident #{$incidentId}";
            $notificationMessage = "{$reviewerName} (Supervisor) added a review: \"{$reviewData['review_title']}\" on incident #{$incidentId}";
            $notificationType = 'info';
            $notificationIcon = 'comment';
            
            // Collect all users to notify (use array to avoid duplicates)
            $usersToNotify = [];
            
            // SUPERVISOR adds review: Notify admins + mobile riders of the site
            
            // 1. Notify all admins
            try {
                $adminModel = $this->model('M_admin');
                $admins = $adminModel->getAllAdmins();
                
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
            
            // 2. Notify mobile riders assigned to the incident site
            if (isset($incident->site_id) && isset($incident->user_id)) {
                try {
                    $mobileRiders = $this->supervisorModel->getSiteMobileRiders($incident->user_id);
                    
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
            
            error_log("Sent {$sentCount} notifications for incident review #{$incidentId} by supervisor");
            
        } catch (Exception $e) {
            error_log("Error in sendIncidentReviewNotifications: " . $e->getMessage());
        }
    }

    /**
     * Display equipment approval requests
     */
    public function equipmentApprovals() {
        if (!isLoggedIn() || !hasRole('supervisor')) {
            redirect('users/login');
        }

        $supervisor_id = $_SESSION['user_id'];
        
        // Get pending equipment requests for this supervisor's sites
        $pending_requests = $this->supervisorModel->getPendingEquipmentRequests($supervisor_id);
        
        // Get stats for the dashboard cards
        $stats = $this->supervisorModel->getEquipmentApprovalStats($supervisor_id);
        
        $data = [
            'title' => 'Equipment Approvals',
            'pending_requests' => $pending_requests,
            'stats' => $stats
        ];
        
        $this->view('supervisor/equipmentApprovals/v_equipment_approvals', $data);
    }

    /**
     * Get equipment requests for a caretaker (AJAX)
     */
    public function getCaretakerEquipmentRequests() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $caretaker_id = $_POST['caretaker_id'] ?? null;
        
        if (!$caretaker_id) {
            echo json_encode(['success' => false, 'message' => 'Caretaker ID required']);
            return;
        }
        
        $requests = $this->supervisorModel->getCaretakerEquipmentRequests($caretaker_id);
        echo json_encode(['success' => true, 'requests' => $requests]);
    }

    /**
     * Approve an equipment request
     */
    public function approveEquipmentRequest() {
        header('Content-Type: application/json');
        
        if (!isLoggedIn() || !hasRole('supervisor')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supervisor_id = $_SESSION['user_id'];
            $request_id = $_POST['request_id'] ?? null;
            $supervisor_notes = $_POST['supervisor_notes'] ?? '';

            if (!$request_id) {
                echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
                exit;
            }

            // Get request details before approval
            $request = $this->supervisorModel->getEquipmentRequestById($request_id);
            
            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Request not found']);
                return;
            }

            // Approve the request
            $result = $this->supervisorModel->approveEquipmentRequest($request_id, $supervisor_id, $supervisor_notes);

            if ($result) {
                // Try to send notification to client (if client_id exists)
                try {
                    if (isset($request->client_id) && $request->client_id) {
                        $clientId = $request->client_id;
                        $caretakerName = $request->caretaker_name;
                        $equipmentName = $request->equipment_name;
                        
                        $notificationTitle = "Equipment Request Approved by Supervisor";
                        $notificationMessage = "Equipment request for {$equipmentName} from {$caretakerName} has been approved by supervisor and needs your approval.";
                        $notificationLink = URL_ROOT . '/client/equipmentApprovals';
                        
                        $this->notificationModel->insertNotification(
                            $clientId,
                            'info',
                            $notificationTitle,
                            $notificationMessage,
                            $notificationLink,
                            'approval',
                            $supervisor_id
                        );
                    }
                    
                    // Log recent activity for supervisor
                    $this->supervisorModel->insertRecentActivity(
                        $supervisor_id,
                        'Equipment Request Approved',
                        "Approved equipment request for {$request->equipment_name} from {$request->caretaker_name}",
                        'equipment_approval'
                    );
                } catch (Exception $e) {
                    // Log error but don't fail the approval
                    error_log("Failed to send notification: " . $e->getMessage());
                }

                echo json_encode([
                    'success' => true, 
                    'message' => 'Equipment request approved successfully.'
                ]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to approve request. The request may have already been processed.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
    }

    /**
     * Reject an equipment request
     */
    public function rejectEquipmentRequest() {
        header('Content-Type: application/json');
        
        if (!isLoggedIn() || !hasRole('supervisor')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supervisor_id = $_SESSION['user_id'];
            $request_id = $_POST['request_id'] ?? null;
            $rejection_reason = $_POST['rejection_reason'] ?? '';

            if (!$request_id || empty($rejection_reason)) {
                echo json_encode(['success' => false, 'message' => 'Request ID and rejection reason are required']);
                return;
            }

            // Get request details before rejection
            $request = $this->supervisorModel->getEquipmentRequestById($request_id);
            
            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Request not found']);
                return;
            }

            // Reject the request
            $result = $this->supervisorModel->rejectEquipmentRequest($request_id, $supervisor_id, $rejection_reason);

            if ($result) {
                // Try to send notification to caretaker
                try {
                    $caretakerId = $request->caretaker_id;
                    $equipmentName = $request->equipment_name;
                    
                    $notificationTitle = "Equipment Request Rejected";
                    $notificationMessage = "Your equipment request for {$equipmentName} has been rejected by supervisor. Reason: {$rejection_reason}";
                    $notificationLink = URL_ROOT . '/caretaker/equipment';
                    
                    $this->notificationModel->insertNotification(
                        $caretakerId,
                        'warning',
                        $notificationTitle,
                        $notificationMessage,
                        $notificationLink,
                        'cancel',
                        $supervisor_id
                    );
                    
                    // Log recent activity for supervisor
                    $this->supervisorModel->insertRecentActivity(
                        $supervisor_id,
                        'Equipment Request Rejected',
                        "Rejected equipment request for {$equipmentName} from caretaker. Reason: {$rejection_reason}",
                        'equipment_rejection'
                    );
                } catch (Exception $e) {
                    // Log error but don't fail the rejection
                    error_log("Failed to send notification: " . $e->getMessage());
                }

                echo json_encode([
                    'success' => true, 
                    'message' => 'Equipment request rejected successfully.'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to reject request. The request may have already been processed.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }


    // ======================================================================== //
    // =======================      Leave Requests      ======================= //
    // ======================================================================== //

    // Leave Requests
    public function leaverequests() {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            redirect('login');
        }

        $leaveRequests = $this->leaveRequestModel->getLeaveRequestsByUser($user_id, 'supervisor');
        $stats = $this->leaveRequestModel->getLeaveStats($user_id, 'supervisor');
        
        $data = [
            'title' => 'Leave Requests',
            'pageTitle' => 'Leave Requests',
            'leaveRequests' => $leaveRequests,
            'stats' => $stats
        ];
        $this->view('supervisor/leaverequests/v_leaverequests', $data);
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
                    'supervisor_id' => $_SESSION['user_id'],
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
                        $userName = $user->name ?? 'A supervisor';
                        
                        if ($admins && is_array($admins)) {
                            foreach ($admins as $admin) {
                                $this->notificationModel->addNotification(
                                    $admin->id,
                                    'info',
                                    'New Leave Request',
                                    "{$userName} (Supervisor) submitted a leave request for {$data['leave_type_value']} from {$data['start_date_value']} to {$data['end_date_value']}",
                                    URL_ROOT . '/admin/pendings',
                                    'calendar_today',
                                    $_SESSION['user_id']
                                );
                            }
                        }
                        
                        // Log recent activity
                        $supervisorModel = $this->model('M_supervisor');
                        $supervisorModel->insertRecentActivity(
                            $_SESSION['user_id'],
                            'Leave Request Submitted',
                            "Submitted {$data['leave_type_value']} leave request from {$data['start_date_value']} to {$data['end_date_value']}",
                            'leave_request'
                        );
                    } catch (Exception $e) {
                        error_log("Error sending leave request notifications: " . $e->getMessage());
                    }
                    
                    flash('msg', 'Leave request submitted successfully', 'alert-success');
                    redirect('supervisor/leaverequests');
                } else {
                    flash('msg', 'Failed to submit leave request', 'alert-danger');
                    $this->view('supervisor/leaverequests/v_create_leaverequest', $data);
                }
            } else {
                // Show form with errors
                $this->view('supervisor/leaverequests/v_create_leaverequest', $data);
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
            $this->view('supervisor/leaverequests/v_create_leaverequest', $data);
        }
    }

    // View Leave Request
    public function viewLeaveRequest($id) {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $leaveRequest = $this->leaveRequestModel->getLeaveRequestById($id, 'supervisor');
        
        // Check if leave request exists and belongs to user
        if (!$leaveRequest || !$this->leaveRequestModel->isOwnedByUser($id, $_SESSION['user_id'], 'supervisor')) {
            flash('msg', 'Leave request not found', 'alert-danger');
            redirect('supervisor/leaverequests');
        }
        
        $data = [
            'title' => 'Leave Requests',
            'pageTitle' => 'Leave Request Details',
            'leaveRequest' => $leaveRequest
        ];
        $this->view('supervisor/leaverequests/v_view_request', $data);
    }

    // Edit Leave Request
    public function editLeaveRequest($id) {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $leaveRequest = $this->leaveRequestModel->getLeaveRequestById($id, 'supervisor');
        
        // Check if leave request exists and belongs to user
        if (!$leaveRequest || !$this->leaveRequestModel->isOwnedByUser($id, $_SESSION['user_id'], 'supervisor')) {
            flash('msg', 'Leave request not found', 'alert-danger');
            redirect('supervisor/leaverequests');
        }

        // Check if request can be edited (only Pending)
        if ($leaveRequest->status != 'Pending') {
            flash('msg', 'Cannot edit this leave request', 'alert-danger');
            redirect('supervisor/leaverequests');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission (similar to create)
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
            
            // Validate (same as create)
            if (empty($data['leave_type_value'])) {
                $data['leave_type_err'] = 'Please select a leave type';
            }
            if (empty($data['reason_value'])) {
                $data['reason_err'] = 'Please enter a reason for leave';
            }
            if (empty($data['start_date_value'])) {
                $data['start_date_err'] = 'Please select a start date';
            }
            if (empty($data['end_date_value'])) {
                $data['end_date_err'] = 'Please select an end date';
            } elseif (!empty($data['start_date_value']) && strtotime($data['end_date_value']) < strtotime($data['start_date_value'])) {
                $data['end_date_err'] = 'End date must be after start date';
            }
            
            // Handle file updates
            $proof_file_path = $leaveRequest->proof_file;
            if (isset($_POST['remove_file']) && $_POST['remove_file'] == '1') {
                $proof_file_path = null;
            }
            if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] == UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
                $file_type = $_FILES['proof_file']['type'];
                if (!in_array($file_type, $allowed_types)) {
                    $data['proof_file_err'] = 'Only JPG, PNG, GIF, and PDF files are allowed';
                } else {
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
            
            if (empty($data['leave_type_err']) && empty($data['reason_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['proof_file_err'])) {
                $updateData = [
                    'leave_type' => $data['leave_type_value'],
                    'reason' => $data['reason_value'],
                    'start_date' => $data['start_date_value'],
                    'end_date' => $data['end_date_value'],
                    'proof_file' => $proof_file_path
                ];
                
                if ($this->leaveRequestModel->updateLeaveRequest($id, $updateData, $_SESSION['user_id'], 'supervisor')) {
                    flash('msg', 'Leave request updated successfully', 'alert-success');
                    redirect('supervisor/leaverequests');
                } else {
                    flash('msg', 'Failed to update leave request', 'alert-danger');
                    $this->view('supervisor/leaverequests/v_edit_request', $data);
                }
            } else {
                $this->view('supervisor/leaverequests/v_edit_request', $data);
            }
        } else {
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
            $this->view('supervisor/leaverequests/v_edit_request', $data);
        }
    }

    // Delete Leave Request
    public function deleteLeaveRequest($id) {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        if (!$this->leaveRequestModel->isOwnedByUser($id, $_SESSION['user_id'], 'supervisor')) {
            flash('msg', 'Unauthorized action', 'alert-danger');
            redirect('supervisor/leaverequests');
        }

        if ($this->leaveRequestModel->deleteLeaveRequest($id, $_SESSION['user_id'], 'supervisor')) {
            flash('msg', 'Leave request deleted successfully', 'alert-success');
        } else {
            flash('msg', 'Failed to delete leave request or request is not pending', 'alert-danger');
        }
        
        redirect('supervisor/leaverequests');
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
