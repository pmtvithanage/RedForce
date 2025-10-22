<?php
class MobileRider extends Controller
{
    private $mobileRiderModel;
    private $userModel;
    private $advertisementModel;

    public function __construct()
    {
        // Check if user is logged in and has mobile rider role
        requireAuth('mobile rider');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->mobileRiderModel = $this->model('M_mobileRider');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index()
    {
        redirect('mobilerider/dashboard');
    }

    // Dashboard action
    public function dashboard()
    {
        $role = 'mobile rider';
        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);

        $notes = $this->getNotes();

        $data = [
            'title' => 'Dashboard',
            'advertisements' => $advertisements,
            'notes' => $notes,
        ];
        $this->view('mobilerider/v_dashboard', $data);
    }

    // Schedule action
    public function sites()
    {
        $data = [
            'title' => 'Sites',
        ];
        $this->view('mobilerider/v_sites', $data);
    }
    // Messages
    public function messages()
    {
        $data = [
            'title' => 'Messages',
        ];
        $this->view('mobilerider/v_messages', $data);
    }
    // Incidents
    public function incidents()
    {
        $data = [
            'title' => 'Incidents',
        ];
        $this->view('mobilerider/v_incidents', $data);
    }
    
    // Leave Requests
    public function leaverequests()
    {
        $mobilerider_id = $_SESSION['user_id'] ?? null;
        
        $leaveRequests = [];
        if ($mobilerider_id) {
            $leaveRequests = $this->mobileRiderModel->getLeaveRequests($mobilerider_id);
        }
        
        $data = [
            'title' => 'Leave Requests',
            'leaveRequests' => $leaveRequests
        ];
        $this->view('mobilerider/v_leaverequests', $data);
    }

    // Add Leave Request
    public function addLeave() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $mobilerider_id = $_SESSION['user_id'] ?? null;
            
            if (!$mobilerider_id) {
                flash('leave_error', 'User not authenticated');
                redirect('mobilerider/leaverequests');
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
                $file_name = 'leave_' . $mobilerider_id . '_' . time() . '.' . $file_extension;
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
                'mobilerider_id' => $mobilerider_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            if (empty($data['leave_type']) || empty($data['reason']) || empty($data['start_date']) || empty($data['end_date'])) {
                flash('leave_error', 'Please fill all required fields');
                redirect('mobilerider/leaverequests');
                return;
            }
            
            if ($this->mobileRiderModel->addLeaveRequest($data)) {
                flash('leave_success', 'Leave request submitted successfully');
            } else {
                flash('leave_error', 'Something went wrong. Please try again');
            }
            
            redirect('mobilerider/leaverequests');
        } else {
            redirect('mobilerider/leaverequests');
        }
    }

    // Edit Leave Request
    public function editLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $mobilerider_id = $_SESSION['user_id'] ?? null;
            
            if (!$mobilerider_id) {
                flash('leave_error', 'User not authenticated');
                redirect('mobilerider/leaverequests');
                return;
            }
            
            $existingLeave = $this->mobileRiderModel->getLeaveRequestById($id);
            
            if (!$existingLeave || $existingLeave->mobilerider_id != $mobilerider_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('mobilerider/leaverequests');
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
                $file_name = 'leave_' . $mobilerider_id . '_' . time() . '.' . $file_extension;
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
                'mobilerider_id' => $mobilerider_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];
            
            if ($this->mobileRiderModel->updateLeaveRequest($data)) {
                flash('leave_success', 'Leave request updated successfully');
            } else {
                flash('leave_error', 'Failed to update leave request');
            }
            
            redirect('mobilerider/leaverequests');
        } else {
            redirect('mobilerider/leaverequests');
        }
    }

    // Delete Leave Request
    public function deleteLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mobilerider_id = $_SESSION['user_id'] ?? null;
            
            if (!$mobilerider_id) {
                flash('leave_error', 'User not authenticated');
                redirect('mobilerider/leaverequests');
                return;
            }
            
            $leave = $this->mobileRiderModel->getLeaveRequestById($id);
            
            if (!$leave || $leave->mobilerider_id != $mobilerider_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('mobilerider/leaverequests');
                return;
            }
            
            // Delete file if exists
            if ($leave->proof_file && file_exists($leave->proof_file)) {
                unlink($leave->proof_file);
            }
            
            if ($this->mobileRiderModel->deleteLeaveRequest($id, $mobilerider_id)) {
                flash('leave_success', 'Leave request deleted successfully');
            } else {
                flash('leave_error', 'Failed to delete leave request');
            }
            
            redirect('mobilerider/leaverequests');
        } else {
            redirect('mobilerider/leaverequests');
        }
    }
    
    // Profile
    public function profile()
    {
        $data = [
            'title' => 'Profile',
        ];
        $this->view('mobilerider/v_profile', $data);
    }

    public function addNote()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $noteId = isset($_POST['noteId']) && !empty($_POST['noteId']) ? $_POST['noteId'] : null;

        // Check if it's an edit or new note
        if ($noteId) {
            // Edit existing note
            if ($this->mobileRiderModel->updateNoteById($noteId, $title, $content)) {
                header("Location: " . URL_ROOT . "/mobilerider/dashboard?notes=open");
                exit;
            } else {
                echo 'Update failed';
            }
        } else {
            // Add new note
            $data = [
                'title' => $title,
                'content' => $content,
                'userID' => $_SESSION['user_userID'],
            ];

            if ($this->mobileRiderModel->addNote($data)) {
                header("Location: " . URL_ROOT . "/mobilerider/dashboard?notes=open");
                exit;
            } else {
                echo 'Add failed';
            }
        }
    }
}

    public function getNotes() {
        $notes = $this->mobileRiderModel->getAllNotes();
        return $notes;
    }

    public function deleteNote(){
        $id = $_GET['id'];
        //echo $id;
        $this->mobileRiderModel->deleteNoteById($id);
        header(header: "Location:" . URL_ROOT . "/mobilerider/dashboard?notes=open");
    }

public function editNote()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $noteId = $_POST['noteId'];
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        if ($this->mobileRiderModel->updateNoteById($noteId, $title, $content)) {
            header("Location: " . URL_ROOT . "/mobilerider/dashboard?notes=open");
            exit;
        } else {
            echo 'Update failed';
        }
    }
}


}