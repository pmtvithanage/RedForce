<?php
class Caretaker extends Controller {
    private $caretakerModel;
    private $userModel;
    private $advertisementModel;

    public function __construct() {
        // Check if user is logged in and has care taker role
        requireAuth('caretaker');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->caretakerModel = $this->model('M_caretaker');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('caretaker/dashboard');
    }

    // dashboard
    public function dashboard() {
        $role = 'Care-Taker';
        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);

        $data = [
            'title' => 'Dashboard',
            'advertisements' => $advertisements
        ];
        $this->view('caretaker/v_dashboard', $data);
    }

    // Messages
    public function messages() {
        $data = [
            'title' => 'Messages',
        ];
        $this->view('caretaker/v_messages', $data);
    }

    //Leave Requests - Display page with all leave requests
    public function leaverequests() {
        // Get caretaker ID from session
        $caretaker_id = $_SESSION['user_id'] ?? null;
        
        // Fetch all leave requests
        $leaveRequests = [];
        if ($caretaker_id) {
            $leaveRequests = $this->caretakerModel->getLeaveRequests($caretaker_id);
        }
        
        $data = [
            'title' => 'Leave Requests',
            'leaveRequests' => $leaveRequests
        ];
        $this->view('caretaker/v_leaverequests', $data);  
    }

    // CREATE - Add new leave request
    public function addLeave() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // Get caretaker ID from session
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            if (!$caretaker_id) {
                flash('leave_error', 'User not authenticated');
                redirect('caretaker/leaverequests');
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
                $file_name = 'leave_' . $caretaker_id . '_' . time() . '.' . $file_extension;
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
                'caretaker_id' => $caretaker_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            // Validate
            if (empty($data['leave_type']) || empty($data['reason']) || empty($data['start_date']) || empty($data['end_date'])) {
                flash('leave_error', 'Please fill all required fields');
                redirect('caretaker/leaverequests');
                return;
            }
            
            // Add leave request
            if ($this->caretakerModel->addLeaveRequest($data)) {
                flash('leave_success', 'Leave request submitted successfully');
            } else {
                flash('leave_error', 'Something went wrong. Please try again');
            }
            
            redirect('caretaker/leaverequests');
        } else {
            redirect('caretaker/leaverequests');
        }
    }

    // UPDATE - Edit leave request
    public function editLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            if (!$caretaker_id) {
                flash('leave_error', 'User not authenticated');
                redirect('caretaker/leaverequests');
                return;
            }
            
            // Get existing leave request
            $existingLeave = $this->caretakerModel->getLeaveRequestById($id);
            
            if (!$existingLeave || $existingLeave->caretaker_id != $caretaker_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('caretaker/leaverequests');
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
                $file_name = 'leave_' . $caretaker_id . '_' . time() . '.' . $file_extension;
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
                'caretaker_id' => $caretaker_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            if ($this->caretakerModel->updateLeaveRequest($data)) {
                flash('leave_success', 'Leave request updated successfully');
            } else {
                flash('leave_error', 'Failed to update leave request');
            }
            
            redirect('caretaker/leaverequests');
        } else {
            redirect('caretaker/leaverequests');
        }
    }

    // DELETE - Remove leave request
    public function deleteLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            if (!$caretaker_id) {
                flash('leave_error', 'User not authenticated');
                redirect('caretaker/leaverequests');
                return;
            }
            
            // Get leave request to verify ownership and get file
            $leave = $this->caretakerModel->getLeaveRequestById($id);
            
            if (!$leave || $leave->caretaker_id != $caretaker_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('caretaker/leaverequests');
                return;
            }
            
            // Delete file if exists
            if ($leave->proof_file) {
                // proof_file already contains full path
                if (file_exists($leave->proof_file)) {
                    unlink($leave->proof_file);
                }
            }
            
            if ($this->caretakerModel->deleteLeaveRequest($id, $caretaker_id)) {
                flash('leave_success', 'Leave request deleted successfully');
            } else {
                flash('leave_error', 'Failed to delete leave request');
            }
            
            redirect('caretaker/leaverequests');
        } else {
            redirect('caretaker/leaverequests');
        }
    }

    //Profile
    public function profile() {
        $data = [
            'title' => 'Profile',
        ];
        $this->view('caretaker/v_profile', $data);
    }


}