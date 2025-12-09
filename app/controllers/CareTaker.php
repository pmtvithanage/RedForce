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
            'pageTitle' => 'Dashboard',
            'advertisements' => $advertisements
        ];
        $this->view('caretaker/v_dashboard', $data);
    }

    // Messages
    public function messages() {
        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages'
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
            'pageTitle' => 'Leave Requests',
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
            'pageTitle' => 'My Profile'
        ];
        $this->view('caretaker/v_profile', $data);
    }

    // ==================== EQUIPMENT REQUESTS METHODS ====================

    // Equipment Requests - Display page with all equipment requests
    public function equipmentRequests() {
        $caretaker_id = $_SESSION['user_id'] ?? null;
        
        if (!$caretaker_id) {
            flash('equipment_error', 'User not authenticated');
            redirect('caretaker/dashboard');
            return;
        }
        
        // Fetch all equipment requests
        $equipmentRequests = $this->caretakerModel->getEquipmentRequests($caretaker_id);
        
        // Get statistics
        $stats = $this->caretakerModel->getEquipmentStats($caretaker_id);
        
        $data = [
            'title' => 'Equipment Requests',
            'pageTitle' => 'Equipment Requests',
            'requests' => $equipmentRequests,
            'stats' => $stats
        ];
        
        $this->view('caretaker/v_equipment_requests', $data);
    }

    // Show add equipment request form page
    public function addEquipmentPage() {
        $data = [
            'title' => 'Request Equipment',
            'pageTitle' => 'Request Equipment'
        ];
        $this->view('caretaker/v_add_equipment', $data);
    }

    // CREATE - Add new equipment request
    public function addEquipmentRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            if (!$caretaker_id) {
                flash('equipment_error', 'User not authenticated', 'alert alert-danger');
                redirect('caretaker/equipmentRequests');
                return;
            }
            
            // Validate required fields
            if (empty($_POST['equipment_name']) || 
                empty($_POST['quantity']) || 
                empty($_POST['estimated_cost']) ||
                empty($_POST['reason']) ||
                empty($_POST['priority'])) {
                
                flash('equipment_error', 'Please fill in all required fields', 'alert alert-danger');
                redirect('caretaker/addEquipmentPage');
                return;
            }
            
            // Validate quantity
            if (!is_numeric($_POST['quantity']) || $_POST['quantity'] < 1) {
                flash('equipment_error', 'Quantity must be at least 1', 'alert alert-danger');
                redirect('caretaker/addEquipmentPage');
                return;
            }
            
            // Validate cost
            if (!is_numeric($_POST['estimated_cost']) || $_POST['estimated_cost'] < 0) {
                flash('equipment_error', 'Estimated cost must be a valid positive number', 'alert alert-danger');
                redirect('caretaker/addEquipmentPage');
                return;
            }
            
            // Validate reason length
            if (strlen(trim($_POST['reason'])) < 10) {
                flash('equipment_error', 'Please provide a detailed reason (minimum 10 characters)', 'alert alert-danger');
                redirect('caretaker/addEquipmentPage');
                return;
            }
            
            // Prepare data
            $data = [
                'caretaker_id' => $caretaker_id,
                'equipment_name' => trim($_POST['equipment_name']),
                'quantity' => (int)$_POST['quantity'],
                'estimated_cost' => (float)$_POST['estimated_cost'],
                'reason' => trim($_POST['reason']),
                'priority' => $_POST['priority'],
                'requested_date' => date('Y-m-d')
            ];
            
            // Add equipment request
            if ($this->caretakerModel->addEquipmentRequest($data)) {
                flash('equipment_message', 'Equipment request submitted successfully. You will be notified once reviewed.', 'alert alert-success');
            } else {
                flash('equipment_error', 'Failed to submit request. Please try again.', 'alert alert-danger');
            }
            
            redirect('caretaker/equipmentRequests');
        } else {
            redirect('caretaker/equipmentRequests');
        }
    }

    // Show edit equipment request form page
    public function editEquipmentPage($id) {
        $caretaker_id = $_SESSION['user_id'] ?? null;
        
        if (!$caretaker_id) {
            flash('equipment_error', 'User not authenticated', 'alert alert-danger');
            redirect('caretaker/equipmentRequests');
            return;
        }
        
        // Get the request from database
        $request = $this->caretakerModel->getEquipmentRequestById($id);
        
        // Verify ownership
        if (!$request || $request->caretaker_id != $caretaker_id) {
            flash('equipment_error', 'Request not found or access denied', 'alert alert-danger');
            redirect('caretaker/equipmentRequests');
            return;
        }
        
        $data = [
            'title' => 'Edit Equipment Request',
            'pageTitle' => 'Edit Equipment Request',
            'request' => $request
        ];
        
        $this->view('caretaker/v_edit_equipment', $data);
    }

    // UPDATE - Update equipment request
    public function updateEquipmentRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            if (!$caretaker_id) {
                flash('equipment_error', 'User not authenticated', 'alert alert-danger');
                redirect('caretaker/equipmentRequests');
                return;
            }
            
            // Get existing request
            $request = $this->caretakerModel->getEquipmentRequestById($id);
            
            // Verify ownership and status
            if (!$request || $request->caretaker_id != $caretaker_id) {
                flash('equipment_error', 'Cannot edit this request', 'alert alert-danger');
                redirect('caretaker/equipmentRequests');
                return;
            }
            
            // Only allow editing if status is Pending
            if ($request->status != 'Pending') {
                flash('equipment_error', 'Cannot edit ' . $request->status . ' requests', 'alert alert-warning');
                redirect('caretaker/equipmentRequests');
                return;
            }
            
            // Validate
            if (empty($_POST['equipment_name']) || empty($_POST['quantity']) || 
                empty($_POST['estimated_cost']) || empty($_POST['reason'])) {
                flash('equipment_error', 'Please fill all required fields', 'alert alert-danger');
                redirect('caretaker/editEquipmentPage/' . $id);
                return;
            }
            
            // Prepare update data
            $data = [
                'id' => $id,
                'equipment_name' => trim($_POST['equipment_name']),
                'quantity' => (int)$_POST['quantity'],
                'estimated_cost' => (float)$_POST['estimated_cost'],
                'reason' => trim($_POST['reason']),
                'priority' => $_POST['priority']
            ];
            
            // Update in database
            if ($this->caretakerModel->updateEquipmentRequest($data)) {
                flash('equipment_message', 'Request updated successfully', 'alert alert-success');
            } else {
                flash('equipment_error', 'Failed to update request', 'alert alert-danger');
            }
            
            redirect('caretaker/equipmentRequests');
        } else {
            redirect('caretaker/equipmentRequests');
        }
    }

    // DELETE - Delete equipment request
    public function deleteEquipmentRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            if (!$caretaker_id) {
                flash('equipment_error', 'User not authenticated', 'alert alert-danger');
                redirect('caretaker/equipmentRequests');
                return;
            }
            
            // Get request
            $request = $this->caretakerModel->getEquipmentRequestById($id);
            
            // Verify ownership and status
            if (!$request || $request->caretaker_id != $caretaker_id) {
                flash('equipment_error', 'Cannot delete this request', 'alert alert-danger');
                redirect('caretaker/equipmentRequests');
                return;
            }
            
            // Only allow deleting Pending requests
            if ($request->status != 'Pending') {
                flash('equipment_error', 'Cannot delete ' . $request->status . ' requests', 'alert alert-warning');
                redirect('caretaker/equipmentRequests');
                return;
            }
            
            // Delete from database
            if ($this->caretakerModel->deleteEquipmentRequest($id)) {
                flash('equipment_message', 'Request deleted successfully', 'alert alert-success');
            } else {
                flash('equipment_error', 'Failed to delete request', 'alert alert-danger');
            }
            
            redirect('caretaker/equipmentRequests');
        } else {
            redirect('caretaker/equipmentRequests');
        }
    }


}