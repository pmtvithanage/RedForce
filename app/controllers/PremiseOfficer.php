<?php
class PremiseOfficer extends Controller {
    private $premiseOfficerModel;
    private $userModel;
    private $advertisementModel;

    public function __construct() {
        // Check if user is logged in and has premise officer role
        //requireAuth('premise officer');

        $this->advertisementModel = $this->model('M_advertisements');
        $this->premiseOfficerModel = $this->model('M_premiseofficer');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('premiseofficer/dashboard');
    }

    // Dashboard action
    public function dashboard() {
        // Fetch advertisements based on role dynamically
        $role = 'premise officer';
        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);

        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'advertisements' => $advertisements
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
        }
        
        $data = [
            'title' => 'Schedule',
            'pageTitle' => 'My Schedule',
            'assignments' => $assignments,
            'leaveDates' => $leaveDates
        ];
        $this->view('premiseofficer/v_schedule', $data);
    }
    
    // AJAX endpoint to get shift details for a specific date
    public function getShiftDetails() {
        header('Content-Type: application/json');
        
        // Log the request for debugging
        error_log('getShiftDetails called');
        error_log('REQUEST_METHOD: ' . $_SERVER['REQUEST_METHOD']);
        error_log('POST data: ' . print_r($_POST, true));
        error_log('SESSION user_id: ' . ($_SESSION['user_id'] ?? 'not set'));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $premiseofficer_id = $_SESSION['user_id'] ?? null;
        $date = $_POST['date'] ?? null;
        
        if (!$premiseofficer_id || !$date) {
            error_log('Missing parameters - premiseofficer_id: ' . ($premiseofficer_id ?? 'null') . ', date: ' . ($date ?? 'null'));
            echo json_encode(['success' => false, 'message' => 'Missing required parameters - ID: ' . ($premiseofficer_id ? 'OK' : 'MISSING') . ', Date: ' . ($date ? 'OK' : 'MISSING')]);
            return;
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
            echo json_encode([
                'success' => true,
                'type' => 'leave',
                'leave_type' => $leaveInfo->leave_type,
                'reason' => $leaveInfo->reason
            ]);
            return;
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
            
            echo json_encode([
                'success' => true,
                'type' => 'shift',
                'location' => $shift->site_name,
                'address' => $shift->address . ', ' . $shift->city,
                'time' => $shiftTime,
                'shift_type' => $shift->shift_type,
                'client' => $shift->contact_person_name ?? '',
                'notes' => $shift->notes ?? 'No additional notes'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'type' => 'no_shift'
            ]);
        }
    }

    // Leave Requests
    public function leaverequests() {
        $premiseofficer_id = $_SESSION['user_id'] ?? null;
        
        $leaveRequests = [];
        if ($premiseofficer_id) {
            $leaveRequests = $this->premiseOfficerModel->getLeaveRequests($premiseofficer_id);
        }
        
        $data = [
            'title' => 'Leave Requests',
            'pageTitle' => 'Leave Requests',
            'leaveRequests' => $leaveRequests
        ];
        $this->view('premiseofficer/v_leaverequests', $data);
    }

    // Add Leave Request
    public function addLeave() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $premiseofficer_id = $_SESSION['user_id'] ?? null;
            
            if (!$premiseofficer_id) {
                flash('leave_error', 'User not authenticated');
                redirect('premiseofficer/leaverequests');
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
                $file_name = 'leave_' . $premiseofficer_id . '_' . time() . '.' . $file_extension;
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
                'premiseofficer_id' => $premiseofficer_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            if (empty($data['leave_type']) || empty($data['reason']) || empty($data['start_date']) || empty($data['end_date'])) {
                flash('leave_error', 'Please fill all required fields');
                redirect('premiseofficer/leaverequests');
                return;
            }
            
            if ($this->premiseOfficerModel->addLeaveRequest($data)) {
                flash('leave_success', 'Leave request submitted successfully');
            } else {
                flash('leave_error', 'Something went wrong. Please try again');
            }
            
            redirect('premiseofficer/leaverequests');
        } else {
            redirect('premiseofficer/leaverequests');
        }
    }

    // Edit Leave Request
    public function editLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $premiseofficer_id = $_SESSION['user_id'] ?? null;
            
            if (!$premiseofficer_id) {
                flash('leave_error', 'User not authenticated');
                redirect('premiseofficer/leaverequests');
                return;
            }
            
            $existingLeave = $this->premiseOfficerModel->getLeaveRequestById($id);
            
            if (!$existingLeave || $existingLeave->premiseofficer_id != $premiseofficer_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('premiseofficer/leaverequests');
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
                $file_name = 'leave_' . $premiseofficer_id . '_' . time() . '.' . $file_extension;
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
                'premiseofficer_id' => $premiseofficer_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            if ($this->premiseOfficerModel->updateLeaveRequest($data)) {
                flash('leave_success', 'Leave request updated successfully');
            } else {
                flash('leave_error', 'Failed to update leave request');
            }
            
            redirect('premiseofficer/leaverequests');
        } else {
            redirect('premiseofficer/leaverequests');
        }
    }

    // Delete Leave Request
    public function deleteLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $premiseofficer_id = $_SESSION['user_id'] ?? null;
            
            if (!$premiseofficer_id) {
                flash('leave_error', 'User not authenticated');
                redirect('premiseofficer/leaverequests');
                return;
            }
            
            $leave = $this->premiseOfficerModel->getLeaveRequestById($id);
            
            if (!$leave || $leave->premiseofficer_id != $premiseofficer_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('premiseofficer/leaverequests');
                return;
            }
            
            // Delete file if exists
            if ($leave->proof_file && file_exists($leave->proof_file)) {
                unlink($leave->proof_file);
            }
            
            if ($this->premiseOfficerModel->deleteLeaveRequest($id, $premiseofficer_id)) {
                flash('leave_success', 'Leave request deleted successfully');
            } else {
                flash('leave_error', 'Failed to delete leave request');
            }
            
            redirect('premiseofficer/leaverequests');
        } else {
            redirect('premiseofficer/leaverequests');
        }
    }

    // Profile action
    public function profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $data = [
            'title' => 'Profile',
            'pageTitle' => 'My Profile'
        ];

        // Load view
        $this->view('premiseofficer/v_profile', $data);
    }
}
?>