<?php
class PremiseOfficer extends Controller {
    private $premiseOfficerModel;
    private $userModel;
    private $advertisementModel;
    private $notificationModel;

    public function __construct() {
        // Check if user is logged in and has premise officer role
        //requireAuth('premise officer');

        $this->advertisementModel = $this->model('M_advertisements');
        $this->premiseOfficerModel = $this->model('M_premiseofficer');
        $this->userModel = $this->model('M_users');
        $this->notificationModel = $this->model('M_notifications');
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
        $data = [
            'title' => 'Schedule',
            'pageTitle' => 'My Schedule'
        ];
        $this->view('premiseofficer/v_schedule', $data);
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
}
?>