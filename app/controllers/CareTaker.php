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

    // ==================== NOTES METHODS ====================

    // Display notes page
    public function notes() {
        $caretaker_id = $_SESSION['user_id'] ?? null;
        
        // Get filters from GET request
        $filters = [
            'category' => $_GET['category'] ?? 'All',
            'priority' => $_GET['priority'] ?? 'All',
            'search' => $_GET['search'] ?? ''
        ];
        
        // Fetch notes and statistics
        $notes = $this->caretakerModel->getNotes($caretaker_id, $filters);
        $stats = $this->caretakerModel->getNotesStats($caretaker_id);
        
        $data = [
            'title' => 'My Notes',
            'pageTitle' => 'My Notes',
            'notes' => $notes,
            'stats' => $stats,
            'filters' => $filters
        ];
        
        $this->view('caretaker/v_notes', $data);
    }

    // Add note page
    public function addNotePage() {
        $data = [
            'title' => 'Add Note',
            'pageTitle' => 'Add New Note'
        ];
        $this->view('caretaker/v_add_note', $data);
    }

    // Process add note
    public function addNote() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Validate inputs
            $errors = [];
            
            if (empty(trim($_POST['title']))) {
                $errors[] = 'Please enter a title';
            }
            
            if (empty(trim($_POST['note_content']))) {
                $errors[] = 'Please enter note content';
            }
            
            if (strlen(trim($_POST['title'])) > 255) {
                $errors[] = 'Title must be less than 255 characters';
            }
            
            if (strlen(trim($_POST['note_content'])) < 10) {
                $errors[] = 'Note content must be at least 10 characters';
            }
            
            // If no errors, add note
            if (empty($errors)) {
                $data = [
                    'caretaker_id' => $caretaker_id,
                    'title' => trim($_POST['title']),
                    'note_content' => trim($_POST['note_content']),
                    'category' => $_POST['category'] ?? 'General',
                    'priority' => $_POST['priority'] ?? 'Medium',
                    'reminder_date' => !empty($_POST['reminder_date']) ? $_POST['reminder_date'] : null
                ];
                
                if ($this->caretakerModel->addNote($data)) {
                    flash('note_message', 'Note added successfully', 'alert-success');
                    redirect('caretaker/notes');
                } else {
                    flash('note_error', 'Failed to add note', 'alert-danger');
                    redirect('caretaker/addNotePage');
                }
            } else {
                flash('note_error', implode('<br>', $errors), 'alert-danger');
                redirect('caretaker/addNotePage');
            }
        } else {
            redirect('caretaker/notes');
        }
    }

    // Edit note page
    public function editNotePage($id) {
        $caretaker_id = $_SESSION['user_id'] ?? null;
        $note = $this->caretakerModel->getNoteById($id);
        
        // Verify ownership
        if (!$note || $note->caretaker_id != $caretaker_id) {
            flash('note_error', 'Note not found or access denied', 'alert-danger');
            redirect('caretaker/notes');
        }
        
        $data = [
            'title' => 'Edit Note',
            'pageTitle' => 'Edit Note',
            'note' => $note
        ];
        
        $this->view('caretaker/v_edit_note', $data);
    }

    // Process update note
    public function updateNote($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            // Verify ownership
            $note = $this->caretakerModel->getNoteById($id);
            if (!$note || $note->caretaker_id != $caretaker_id) {
                flash('note_error', 'Note not found or access denied', 'alert-danger');
                redirect('caretaker/notes');
            }
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Validate inputs
            $errors = [];
            
            if (empty(trim($_POST['title']))) {
                $errors[] = 'Please enter a title';
            }
            
            if (empty(trim($_POST['note_content']))) {
                $errors[] = 'Please enter note content';
            }
            
            if (strlen(trim($_POST['title'])) > 255) {
                $errors[] = 'Title must be less than 255 characters';
            }
            
            if (strlen(trim($_POST['note_content'])) < 10) {
                $errors[] = 'Note content must be at least 10 characters';
            }
            
            // If no errors, update note
            if (empty($errors)) {
                $data = [
                    'id' => $id,
                    'caretaker_id' => $caretaker_id,
                    'title' => trim($_POST['title']),
                    'note_content' => trim($_POST['note_content']),
                    'category' => $_POST['category'] ?? 'General',
                    'priority' => $_POST['priority'] ?? 'Medium',
                    'reminder_date' => !empty($_POST['reminder_date']) ? $_POST['reminder_date'] : null
                ];
                
                if ($this->caretakerModel->updateNote($data)) {
                    flash('note_message', 'Note updated successfully', 'alert-success');
                    redirect('caretaker/notes');
                } else {
                    flash('note_error', 'Failed to update note', 'alert-danger');
                    redirect('caretaker/editNotePage/' . $id);
                }
            } else {
                flash('note_error', implode('<br>', $errors), 'alert-danger');
                redirect('caretaker/editNotePage/' . $id);
            }
        } else {
            redirect('caretaker/notes');
        }
    }

    // Delete note
    public function deleteNote($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            // Verify ownership
            $note = $this->caretakerModel->getNoteById($id);
            if (!$note || $note->caretaker_id != $caretaker_id) {
                flash('note_error', 'Note not found or access denied', 'alert-danger');
                redirect('caretaker/notes');
            }
            
            if ($this->caretakerModel->deleteNote($id, $caretaker_id)) {
                flash('note_message', 'Note deleted successfully', 'alert-success');
            } else {
                flash('note_error', 'Failed to delete note', 'alert-danger');
            }
            
            redirect('caretaker/notes');
        } else {
            redirect('caretaker/notes');
        }
    }

    // Toggle pin
    public function togglePin($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $caretaker_id = $_SESSION['user_id'] ?? null;
            
            // Verify ownership
            $note = $this->caretakerModel->getNoteById($id);
            if (!$note || $note->caretaker_id != $caretaker_id) {
                flash('note_error', 'Note not found or access denied', 'alert-danger');
                redirect('caretaker/notes');
            }
            
            if ($this->caretakerModel->togglePin($id, $caretaker_id)) {
                flash('note_message', $note->is_pinned ? 'Note unpinned' : 'Note pinned to top', 'alert-success');
            } else {
                flash('note_error', 'Failed to update pin status', 'alert-danger');
            }
            
            redirect('caretaker/notes');
        } else {
            redirect('caretaker/notes');
        }
    }

}
