<?php
class PremiseOfficer extends Controller {
    private $premiseOfficerModel;
    private $userModel;
    private $advertisementModel;
    private $notificationModel;
    private $leaveRequestModel;

    public function __construct() {
        // Check if user is logged in and has premise officer role
        //requireAuth('premise officer');

        $this->advertisementModel = $this->model('M_advertisements');
        $this->premiseOfficerModel = $this->model('M_premiseofficer');
        $this->userModel = $this->model('M_users');
        $this->notificationModel = $this->model('M_notifications');
        $this->leaveRequestModel = $this->model('M_leaveRequests');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('premiseOfficer/dashboard');
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