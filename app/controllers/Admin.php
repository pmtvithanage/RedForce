<?php
class Admin extends Controller {
    private $adminModel;
    private $userModel;

    public function __construct() {
        requireAuth('admin');
        $this->adminModel = $this->model('M_admin');
        $this->userModel = $this->model('M_users');
        // Removed: $this->settingsModel = $this->model('SettingsModel');
    }

    public function index() {
        redirect('admin/dashboard');
    }

// ======================================================================== //
// =======================      Admin Dashboard       ====================== //
// ======================================================================== //

    public function dashboard() {
        $pendingLeaves = $this->adminModel->getPendingLeaveRequests();
        $leaveStats = $this->adminModel->getLeaveRequestStats();
    
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Admin Dashboard',
            'pendingLeaves' => $pendingLeaves,
            'leaveStats' => $leaveStats
        ];
        $this->view('admin/dashboard/v_dashboard', $data);
    }

    public function messages(){
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Messages'
        ];
        $this->view('admin/dashboard/v_messages', $data);
    }

    public function pendings(){
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Pending Leave Requests'
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
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Manage Officers'
    ];
        $this->view('admin/v_officers', $data);
    }

    public function Jobs() {
        $data = ['title' => 'Officers'];
        $this->view('admin/officers/jobs', $data);
    }

// ======================================================================== //
// =======================      Admin Clients       ====================== //
// ======================================================================== //

    public function clients() {
        // Get pending service requests count for notification badge
        $requestStats = $this->adminModel->getServiceRequestStats();
        $pendingCount = $requestStats->pending ?? 0;
        
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Manage Clients',
            'pendingRequestsCount' => $pendingCount
        ];
        $this->view('admin/clients/v_clients', $data);  
    }

     public function addclients(){
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Clients'
        ];
        $this->view('admin/clients/v_addClient', $data);
    }

     public function clientprofile(){
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Client Name'
        ];
        $this->view('admin/clients/v_clientProfile', $data);
    }

    public function clientRequests() {
        // Handle approve/reject actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['approve_request'])) {
                $requestId = $_POST['request_id'];
                if ($this->adminModel->updateServiceRequestStatus($requestId, 'Approved')) {
                    flash('request_success', 'Service request approved successfully');
                } else {
                    flash('request_error', 'Failed to approve service request');
                }
            } elseif (isset($_POST['reject_request'])) {
                $requestId = $_POST['request_id'];
                if ($this->adminModel->updateServiceRequestStatus($requestId, 'Rejected')) {
                    flash('request_success', 'Service request rejected');
                } else {
                    flash('request_error', 'Failed to reject service request');
                }
            }
            redirect('admin/clientRequests');
        }

        // Get all service requests
        $serviceRequests = $this->adminModel->getAllServiceRequests();
        $requestStats = $this->adminModel->getServiceRequestStats();

        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Client Service Requests',
            'serviceRequests' => $serviceRequests,
            'requestStats' => $requestStats
        ];
        
        $this->view('admin/v_client_requests', $data);
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
// =======================      Admin Salary       ====================== //
// ======================================================================== //

    public function salary() {
        $data = [
            'title' => 'Salary',
            'pageTitle' => 'Manage Salary'];
        $this->view('admin/v_salary', $data);
    }

    // View leave request details
    public function viewLeaveRequest($id) {
    $leaveRequest = $this->adminModel->getLeaveRequestById($id);
    
    if (!$leaveRequest) {
        flash('leave_error', 'Leave request not found');
        redirect('admin/dashboard');
    }
    
    $data = [
        'title' => 'Leave Request Details',
        'leaveRequest' => $leaveRequest
    ];
    $this->view('admin/v_leave_details', $data);
}

// Approve leave request
public function approveLeave($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $admin_id = $_SESSION['user_id'];
        
        if ($this->adminModel->approveLeaveRequest($id, $admin_id)) {
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
            flash('leave_success', 'Leave request rejected');
        } else {
            flash('leave_error', 'Failed to reject leave request');
        }
        
        redirect('admin/dashboard');
    }
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
        $this->view('admin/v_advertisements', $data);
    }

    public function createAdvertisement() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Enable detailed error logging
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        
        // Start output buffering
        ob_start();
        
        header('Content-Type: application/json');
        
        try {
            error_log("=== CREATE ADVERTISEMENT START ===");
            error_log("POST data: " . print_r($_POST, true));
            error_log("FILES data: " . print_r($_FILES, true));
            error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'not set'));

            $uploadDir = PUB_ROOT . '/uploads/advertisements/';
            error_log("Upload directory: " . $uploadDir);
            
            // Create directory
            if (!is_dir($uploadDir)) {
                error_log("Creating directory...");
                $oldUmask = umask(0);
                $result = mkdir($uploadDir, 0755, true);
                umask($oldUmask);
                
                if (!$result) {
                    throw new Exception('Failed to create upload directory');
                }
                error_log("Directory created successfully");
            }

            // Check if directory is writable
            if (!is_writable($uploadDir)) {
                error_log("Directory not writable, attempting to chmod...");
                if (!chmod($uploadDir, 0755)) {
                    throw new Exception('Upload directory is not writable');
                }
            }
            error_log("Directory is writable");

            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                error_log("File upload detected");
                
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($_FILES['image']['name']));
                $targetPath = $uploadDir . $fileName;
                error_log("Target path: " . $targetPath);

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $error = error_get_last();
                    throw new Exception('Failed to upload image: ' . ($error['message'] ?? 'Unknown error'));
                }
                error_log("File uploaded successfully");
                $imagePath = 'uploads/advertisements/' . $fileName;
            } else {
                $errorMessage = 'No image uploaded';
                if (isset($_FILES['image']['error'])) {
                    $errorCodes = [
                        UPLOAD_ERR_INI_SIZE => 'File too large',
                        UPLOAD_ERR_FORM_SIZE => 'File too large (form)',
                        UPLOAD_ERR_PARTIAL => 'File upload incomplete',
                        UPLOAD_ERR_NO_FILE => 'No file uploaded',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing temp folder',
                        UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk',
                        UPLOAD_ERR_EXTENSION => 'File upload stopped'
                    ];
                    $errorMessage = $errorCodes[$_FILES['image']['error']] ?? 'Unknown upload error';
                }
                throw new Exception($errorMessage);
            }

            $roles = $_POST['roles'] ?? [];
            if (!is_array($roles)) {
                $roles = explode(',', $roles);
            }
            error_log("Roles: " . print_r($roles, true));

            if (empty($roles)) {
                throw new Exception('No roles selected');
            }

            $data = [
                'title' => $_POST['title'] ?? 'Advertisement',
                'image_path' => $imagePath,
                'target_roles' => implode(',', $roles),
                'created_by' => $_SESSION['user_id'] ?? 0,
                'status' => 'active'
            ];
            error_log("Data for insert: " . print_r($data, true));

            // Insert into database
            error_log("Calling createAdvertisement model method...");
            if ($this->adminModel->createAdvertisement($data)) {
                $lastId = $this->adminModel->getLastInsertId();
                error_log("Insert successful. Last ID: " . $lastId);
                
                $response = [
                    'status' => 'success',
                    'ad' => [
                        'id' => $lastId,
                        'title' => $data['title'],
                        'image_path' => URL_ROOT . '/' . $imagePath,
                        'target_roles' => $data['target_roles'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'status' => 'active'
                    ]
                ];
                
                error_log("Sending success response");
                ob_end_clean();
                echo json_encode($response);
                
            } else {
                throw new Exception('Database insert failed');
            }
            
        } catch (Exception $e) {
            $error = error_get_last();
            error_log("Exception caught: " . $e->getMessage());
            error_log("PHP error: " . ($error['message'] ?? 'No PHP error'));
            
            ob_end_clean();
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}
    public function updateAdvertisement($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            // Turn off error reporting
            error_reporting(0);

            // Get current advertisement to handle image deletion
            $currentAd = $this->adminModel->getAdvertisementById($id);
            $currentImagePath = $currentAd->image_path ?? '';
            
            $imagePath = $currentImagePath;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $uploadDir = PUB_ROOT . '/uploads/advertisements/';
                
                // Create directory with proper permissions - silent mode
                if (!is_dir($uploadDir)) {
                    $oldUmask = umask(0);
                    $result = @mkdir($uploadDir, 0755, true);
                    umask($oldUmask);
                    
                    if (!$result) {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
                        exit;
                    }
                }

                // Sanitize filename
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($_FILES['image']['name']));
                $targetPath = $uploadDir . $fileName;
                
                if (@move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/advertisements/' . $fileName;
                    
                    // Delete old image if it exists and is different from new one
                    if (!empty($currentImagePath) && $currentImagePath !== $imagePath) {
                        $oldImageFullPath = PUB_ROOT . '/' . $currentImagePath;
                        if (file_exists($oldImageFullPath)) {
                            @unlink($oldImageFullPath);
                        }
                    }
                }
            }

            $roles = $_POST['roles'] ?? '';
            if (is_array($roles)) $roles = implode(',', $roles);

            $data = [
                'title' => $_POST['title'] ?? 'Advertisement',
                'image_path' => $imagePath,
                'target_roles' => $roles,
                'status' => $_POST['status'] ?? 'active'
            ];

            if ($this->adminModel->updateAdvertisement($id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Advertisement updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update advertisement']);
            }
            exit;
        }
    }

    public function deleteAdvertisement($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            // Get advertisement first to delete the image file
            $advertisement = $this->adminModel->getAdvertisementById($id);
            
            if ($this->adminModel->deleteAdvertisement($id)) {
                // Delete the associated image file
                if ($advertisement && !empty($advertisement->image_path)) {
                    $imagePath = PUB_ROOT . '/' . $advertisement->image_path;
                    if (file_exists($imagePath)) {
                        @unlink($imagePath);
                    }
                }
                echo json_encode(['status' => 'success', 'message' => 'Advertisement deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete advertisement']);
            }
            exit;
        }
    }

    public function toggleAdvertisementStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            if ($this->adminModel->toggleAdvertisementStatus($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Advertisement status updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
            }
            exit;
        }
    }

    public function getAdvertisement($id) {
        header('Content-Type: application/json');
        $advertisement = $this->adminModel->getAdvertisementById($id);
        if ($advertisement) {
            echo json_encode([
                'status' => 'success',
                'advertisement' => $advertisement
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Advertisement not found']);
        }
        exit;
    }

    public function incidents() {
        $data = [
            'title' => 'Incidents',
            'pageTitle' => 'Incidents Dashboard'
        ];
        $this->view('admin/v_incidents', $data);
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
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Incidents Reports'
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

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Clean all output buffers
            while (ob_get_level()) ob_end_clean();
            
            header('Content-Type: application/json; charset=utf-8');
            
            try {
                // Get POST data
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $confirm_password = trim($_POST['confirm_password'] ?? '');
                $role = trim($_POST['role'] ?? '');
                $nic = trim($_POST['nic'] ?? '');
                $mobile = trim($_POST['mobile'] ?? '');
                $address = trim($_POST['address'] ?? '');
                
                // Validation errors array
                $errors = [];
                
                // Validate name
                if (empty($name)) {
                    $errors['name'] = 'Please enter name';
                }
                
                // Validate NIC - must be exactly 12 digits
                if (!empty($nic)) {
                    if (!preg_match('/^\d{12}$/', $nic)) {
                        $errors['nic'] = 'NIC must be exactly 12 digits with no letters';
                    }
                }
                
                // Validate mobile - must be exactly 10 digits
                if (!empty($mobile)) {
                    if (!preg_match('/^\d{10}$/', $mobile)) {
                        $errors['mobile'] = 'Mobile number must be exactly 10 digits with no letters';
                    }
                }
                
                // Validate email
                if (empty($email)) {
                    $errors['email'] = 'Please enter email';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Please enter a valid email';
                } elseif ($this->userModel->findUserByEmail($email)) {
                    $errors['email'] = 'Email is already taken';
                }
                
                // Validate password
                if (empty($password)) {
                    $errors['password'] = 'Please enter password';
                } elseif (strlen($password) < 4) {
                    $errors['password'] = 'Password must be at least 4 characters';
                }
                
                // Validate confirm password
                if (empty($confirm_password)) {
                    $errors['confirm_password'] = 'Please confirm password';
                } elseif ($password !== $confirm_password) {
                    $errors['confirm_password'] = 'Passwords do not match';
                }
                
                // Validate role
                if (empty($role)) {
                    $errors['role'] = 'Please select a role';
                }
                
                // If there are validation errors, return them
                if (!empty($errors)) {
                    echo json_encode([
                        'success' => false,
                        'errors' => $errors
                    ]);
                    exit;
                }
                
                // Generate userID based on role
                $userID = $this->generateUserID($role);
                
                // Prepare user data
                $userData = [
                    'userID' => $userID,
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => $role,
                    'nic' => $nic,
                    'mobile' => $mobile,
                    'address' => $address,
                    'additional_info' => $this->getAdditionalInfo($_POST)
                ];
                
                // Create user
                if ($this->userModel->register($userData)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'User created successfully'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to create user. Please try again.'
                    ]);
                }
                
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage()
                ]);
            }
            exit;
        }
    }

    private function generateUserID($role) {
        // Define prefix based on role
        $prefix = '';
        switch($role) {
            case 'admin': 
                $prefix = 'ADMIN'; 
                break;
            case 'supervisor': 
                $prefix = 'SUPERVISOR'; 
                break;
            case 'premise officer': 
                $prefix = 'PREMISEOFFICER'; 
                break;
            case 'mobile rider': 
                $prefix = 'MOBILERIDER'; 
                break;
            case 'client': 
                $prefix = 'CLIENT'; 
                break;
            case 'caretaker': 
                $prefix = 'CARETAKER'; 
                break;
            default: 
                $prefix = 'USER';
        }
        
        // Get the count of existing users with this role
        $count = $this->userModel->getUserCountByRole($role);
        
        // Generate the next number (count + 1) with leading zeros
        $number = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        return $prefix . $number;
    }

    private function getAdditionalInfo($postData) {
        $additionalInfo = [];
        
        switch($postData['role']) {
            case 'mobile rider':
                $additionalInfo = [
                    'vehicle_type' => $postData['vehicle_type'] ?? '',
                    'license_number' => $postData['license_number'] ?? ''
                ];
                break;
            case 'caretaker':
                $additionalInfo = [
                    'qualifications' => $postData['qualifications'] ?? '',
                    'experience' => $postData['experience'] ?? ''
                ];
                break;
            case 'premise officer':
                $additionalInfo = [
                    'premise_id' => $postData['premise_id'] ?? '',
                    'shift' => $postData['shift'] ?? ''
                ];
                break;
        }
        
        return json_encode($additionalInfo);
    }

    public function getUserDetails($userID) {
        header('Content-Type: application/json');
        $user = $this->adminModel->getUserByID($userID);
        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
        exit;
    }

    public function getAdmins() {
        header('Content-Type: application/json');
        $admins = $this->adminModel->getAdmins();
        echo json_encode($admins);
        exit;
    }

    public function debugAdvertisement() {
    // Enable error reporting temporarily
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    header('Content-Type: application/json');
    
    try {
        // Test 1: Check if model methods exist
        $methods = [
            'createAdvertisement' => method_exists($this->adminModel, 'createAdvertisement'),
            'getLastInsertId' => method_exists($this->adminModel, 'getLastInsertId'),
            'getAdvertisementById' => method_exists($this->adminModel, 'getAdvertisementById')
        ];
        
        // Test 2: Check database connection
        $dbTest = $this->adminModel->getAdvertisements();
        $dbStatus = is_array($dbTest) ? 'connected' : 'failed';
        
        // Test 3: Test a simple database insert
        $testData = [
            'title' => 'Test Ad',
            'image_path' => 'uploads/advertisements/test.jpg',
            'target_roles' => 'Test',
            'created_by' => 1,
            'status' => 'active'
        ];
        
        $insertTest = $this->adminModel->createAdvertisement($testData);
        
        echo json_encode([
            'status' => 'success',
            'debug' => [
                'model_methods' => $methods,
                'database' => $dbStatus,
                'insert_test' => $insertTest ? 'success' : 'failed',
                'session_user_id' => $_SESSION['user_id'] ?? 'not_set'
            ]
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
    exit;
}
}