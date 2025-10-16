<?php
class Admin extends Controller {
    private $adminModel;
    private $userModel;

    public function __construct() {
        requireAuth('admin');
        $this->adminModel = $this->model('M_admin');
        $this->userModel = $this->model('M_users');
    }

    public function index() {
        redirect('admin/dashboard');
    }

    public function dashboard() {
        $pendingLeaves = $this->adminModel->getPendingLeaveRequests();
        $leaveStats = $this->adminModel->getLeaveRequestStats();
    
        $data = [
            'title' => 'Dashboard',
            'pendingLeaves' => $pendingLeaves,
            'leaveStats' => $leaveStats
        ];
        $this->view('admin/v_dashboard', $data);
    }

    public function officers() {
        $data = ['title' => 'Officers'];
        $this->view('admin/v_officers', $data);
    }

    public function clients() {
        $data = ['title' => 'Clients'];
        $this->view('admin/v_clients', $data);  
    }

    public function scheduling() {
        $data = ['title' => 'Scheduling'];
        $this->view('admin/v_scheduling', $data);
    }

    public function salary() {
        $data = ['title' => 'Salary'];
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
// ==============================
// Advertisements
// ==============================
    public function advertisements() {
        $advertisements = $this->adminModel->getAdvertisements();
        $data = [
            'title' => 'Advertisements',
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
        $data = ['title' => 'Incidents'];
        $this->view('admin/v_incidents', $data);
    }

    public function reports() {
        $data = ['title' => 'Reports'];
        $this->view('admin/v_reports', $data);  
    }

    public function settings() {
        $data = ['title' => 'Settings'];
        $this->view('admin/v_settings', $data);
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