<?php
class Caretaker extends Controller {
    private $caretakerModel;
    private $userModel;
    private $advertisementModel;

    public function __construct() {
        requireAuth('caretaker');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->caretakerModel = $this->model('M_caretaker');
        $this->userModel = $this->model('M_users');
    }

    public function index() {
        redirect('caretaker/dashboard');
    }

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

    public function messages() {
        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages'
        ];
        $this->view('caretaker/v_messages', $data);
    }

    /* --------------------------
       LEAVE REQUESTS
    ---------------------------*/

    public function leaverequests() {
        $caretaker_id = $_SESSION['user_id'] ?? null;

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

    public function addLeave() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $caretaker_id = $_SESSION['user_id'] ?? null;

            if (!$caretaker_id) {
                flash('leave_error', 'User not authenticated');
                redirect('caretaker/leaverequests');
                return;
            }

            $proof_file = null;

            if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] == 0) {
                $upload_dir = 'uploads/leaverequest/';
                if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

                $file_extension = pathinfo($_FILES['proof_file']['name'], PATHINFO_EXTENSION);
                $file_name = 'leave_' . $caretaker_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['proof_file']['tmp_name'], $upload_path)) {
                    $proof_file = $upload_path;
                }
            }

            $start_date = $_POST['start_date'];
            $end_date   = $_POST['end_date'];

            if (strpos($start_date, '/') !== false) {
                $p = explode('/', $start_date);
                $start_date = "$p[2]-$p[1]-$p[0]";
            }

            if (strpos($end_date, '/') !== false) {
                $p = explode('/', $end_date);
                $end_date = "$p[2]-$p[1]-$p[0]";
            }

            $data = [
                'caretaker_id' => $caretaker_id,
                'leave_type' => trim($_POST['leave_type']),
                'reason' => trim($_POST['reason']),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'proof_file' => $proof_file
            ];

            if (empty($data['leave_type']) || empty($data['reason']) ||
                empty($data['start_date']) || empty($data['end_date'])) {

                flash('leave_error', 'Please fill all required fields');
                redirect('caretaker/leaverequests');
                return;
            }

            if ($this->caretakerModel->addLeaveRequest($data)) {
                flash('leave_success', 'Leave request submitted successfully');
            } else {
                flash('leave_error', 'Something went wrong');
            }

            redirect('caretaker/leaverequests');
        }

        redirect('caretaker/leaverequests');
    }

    public function editLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $caretaker_id = $_SESSION['user_id'] ?? null;

            if (!$caretaker_id) {
                flash('leave_error', 'User not authenticated');
                redirect('caretaker/leaverequests');
                return;
            }

            $existingLeave = $this->caretakerModel->getLeaveRequestById($id);

            if (!$existingLeave || $existingLeave->caretaker_id != $caretaker_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('caretaker/leaverequests');
                return;
            }

            $proof_file = $existingLeave->proof_file;

            if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] == 0) {

                $upload_dir = 'uploads/leaverequest/';
                if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

                $file_extension = pathinfo($_FILES['proof_file']['name'], PATHINFO_EXTENSION);
                $file_name = 'leave_' . $caretaker_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['proof_file']['tmp_name'], $upload_path)) {

                    if ($existingLeave->proof_file && file_exists($existingLeave->proof_file)) {
                        unlink($existingLeave->proof_file);
                    }

                    $proof_file = $upload_path;
                }
            }

            $start_date = $_POST['start_date'];
            $end_date   = $_POST['end_date'];

            if (strpos($start_date, '/') !== false) {
                $p = explode('/', $start_date);
                $start_date = "$p[2]-$p[1]-$p[0]";
            }

            if (strpos($end_date, '/') !== false) {
                $p = explode('/', $end_date);
                $end_date = "$p[2]-$p[1]-$p[0]";
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
                flash('leave_success', 'Leave updated successfully');
            } else {
                flash('leave_error', 'Update failed');
            }

            redirect('caretaker/leaverequests');
        }

        redirect('caretaker/leaverequests');
    }

    public function deleteLeave($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $caretaker_id = $_SESSION['user_id'] ?? null;
            if (!$caretaker_id) {
                flash('leave_error', 'User not authenticated');
                redirect('caretaker/leaverequests');
                return;
            }

            $leave = $this->caretakerModel->getLeaveRequestById($id);

            if (!$leave || $leave->caretaker_id != $caretaker_id) {
                flash('leave_error', 'Unauthorized access');
                redirect('caretaker/leaverequests');
                return;
            }

            if ($leave->proof_file && file_exists($leave->proof_file)) {
                unlink($leave->proof_file);
            }

            if ($this->caretakerModel->deleteLeaveRequest($id, $caretaker_id)) {
                flash('leave_success', 'Leave deleted');
            } else {
                flash('leave_error', 'Delete failed');
            }

            redirect('caretaker/leaverequests');
        }

        redirect('caretaker/leaverequests');
    }

    /* --------------------------
       PROFILE
    ---------------------------*/

    public function profile() {
        $data = [
            'title' => 'Profile',
            'pageTitle' => 'My Profile'
        ];
        $this->view('caretaker/v_profile', $data);
    }

    /* --------------------------
       EQUIPMENT REQUESTS
    ---------------------------*/

    public function equipmentRequests() {
        $caretaker_id = $_SESSION['user_id'] ?? null;

        if (!$caretaker_id) {
            flash('equipment_error', 'User not authenticated');
            redirect('caretaker/dashboard');
            return;
        }

        $equipmentRequests = $this->caretakerModel->getEquipmentRequests($caretaker_id);
        $stats = $this->caretakerModel->getEquipmentStats($caretaker_id);

        $data = [
            'title' => 'Equipment Requests',
            'pageTitle' => 'Equipment Requests',
            'requests' => $equipmentRequests,
            'stats' => $stats
        ];

        $this->view('caretaker/v_equipment_requests', $data);
    }

    public function addEquipmentPage() {
        $data = [
            'title' => 'Request Equipment',
            'pageTitle' => 'Request Equipment'
        ];
        $this->view('caretaker/v_add_equipment', $data);
    }

    public function addEquipmentRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $caretaker_id = $_SESSION['user_id'] ?? null;

            if (!$caretaker_id) {
                flash('equipment_error', 'User not authenticated');
                redirect('caretaker/equipmentRequests');
                return;
            }

            if (empty($_POST['equipment_name']) || empty($_POST['quantity']) ||
                empty($_POST['estimated_cost']) || empty($_POST['reason']) ||
                empty($_POST['priority'])) {

                flash('equipment_error', 'Please fill in all fields');
                redirect('caretaker/addEquipmentPage');
                return;
            }

            if (!is_numeric($_POST['quantity']) || $_POST['quantity'] < 1) {
                flash('equipment_error', 'Invalid quantity');
                redirect('caretaker/addEquipmentPage');
                return;
            }

            if (!is_numeric($_POST['estimated_cost']) || $_POST['estimated_cost'] < 0) {
                flash('equipment_error', 'Invalid cost');
                redirect('caretaker/addEquipmentPage');
                return;
            }

            if (strlen(trim($_POST['reason'])) < 10) {
                flash('equipment_error', 'Reason must be at least 10 characters');
                redirect('caretaker/addEquipmentPage');
                return;
            }

            $data = [
                'caretaker_id' => $caretaker_id,
                'equipment_name' => trim($_POST['equipment_name']),
                'quantity' => (int)$_POST['quantity'],
                'estimated_cost' => (float)$_POST['estimated_cost'],
                'reason' => trim($_POST['reason']),
                'priority' => $_POST['priority'],
                'requested_date' => date('Y-m-d')
            ];

            if ($this->caretakerModel->addEquipmentRequest($data)) {
                flash('equipment_message', 'Request submitted', 'alert-success');
            } else {
                flash('equipment_error', 'Failed to submit');
            }

            redirect('caretaker/equipmentRequests');
        }

        redirect('caretaker/equipmentRequests');
    }

    public function editEquipmentPage($id) {
        $caretaker_id = $_SESSION['user_id'] ?? null;

        if (!$caretaker_id) {
            flash('equipment_error', 'User not authenticated');
            redirect('caretaker/equipmentRequests');
            return;
        }

        $request = $this->caretakerModel->getEquipmentRequestById($id);

        if (!$request || $request->caretaker_id != $caretaker_id) {
            flash('equipment_error', 'Access denied');
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

    public function updateEquipmentRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $caretaker_id = $_SESSION['user_id'] ?? null;

            if (!$caretaker_id) {
                flash('equipment_error', 'User not authenticated');
                redirect('caretaker/equipmentRequests');
                return;
            }

            $request = $this->caretakerModel->getEquipmentRequestById($id);

            if (!$request || $request->caretaker_id != $caretaker_id) {
                flash('equipment_error', 'Access denied');
                redirect('caretaker/equipmentRequests');
                return;
            }

            if ($request->status != 'Pending') {
                flash('equipment_error', 'Cannot edit non-pending requests');
                redirect('caretaker/equipmentRequests');
                return;
            }

            if (empty($_POST['equipment_name']) || empty($_POST['quantity']) ||
                empty($_POST['estimated_cost']) || empty($_POST['reason'])) {

                flash('equipment_error', 'All fields required');
                redirect('caretaker/editEquipmentPage/' . $id);
                return;
            }

            $data = [
                'id' => $id,
                'equipment_name' => trim($_POST['equipment_name']),
                'quantity' => (int)$_POST['quantity'],
                'estimated_cost' => (float)$_POST['estimated_cost'],
                'reason' => trim($_POST['reason']),
                'priority' => $_POST['priority']
            ];

            if ($this->caretakerModel->updateEquipmentRequest($data)) {
                flash('equipment_message', 'Request updated');
            } else {
                flash('equipment_error', 'Update failed');
            }

            redirect('caretaker/equipmentRequests');
        }

        redirect('caretaker/equipmentRequests');
    }

    public function deleteEquipmentRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $caretaker_id = $_SESSION['user_id'] ?? null;

            if (!$caretaker_id) {
                flash('equipment_error', 'User not authenticated');
                redirect('caretaker/equipmentRequests');
                return;
            }

            $request = $this->caretakerModel->getEquipmentRequestById($id);

            if (!$request || $request->caretaker_id != $caretaker_id) {
                flash('equipment_error', 'Access denied');
                redirect('caretaker/equipmentRequests');
                return;
            }

            if ($request->status != 'Pending') {
                flash('equipment_error', 'Only pending requests can be deleted');
                redirect('caretaker/equipmentRequests');
                return;
            }

            if ($this->caretakerModel->deleteEquipmentRequest($id)) {
                flash('equipment_message', 'Request deleted');
            } else {
                flash('equipment_error', 'Delete failed');
            }

            redirect('caretaker/equipmentRequests');
        }

        redirect('caretaker/equipmentRequests');
    }

    /* --------------------------
       NOTES
    ---------------------------*/

    public function notes() {
        $caretaker_id = $_SESSION['user_id'] ?? null;

        $filters = [
            'category' => $_GET['category'] ?? 'All',
            'priority' => $_GET['priority'] ?? 'All',
            'search' => $_GET['search'] ?? ''
        ];

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

    public function addNotePage() {
        $data = [
            'title' => 'Add Note',
            'pageTitle' => 'Add New Note'
        ];
        $this->view('caretaker/v_add_note', $data);
    }

    public function addNote() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $caretaker_id = $_SESSION['user_id'] ?? null;
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $errors = [];

            if (empty(trim($_POST['title']))) $errors[] = 'Enter a title';
            if (empty(trim($_POST['note_content']))) $errors[] = 'Enter content';
            if (strlen(trim($_POST['title'])) > 255) $errors[] = 'Title too long';
            if (strlen(trim($_POST['note_content'])) < 10) $errors[] = 'Content too short';

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
                    flash('note_message', 'Note added');
                    redirect('caretaker/notes');
                } else {
                    flash('note_error', 'Failed to add');
                    redirect('caretaker/addNotePage');
                }

            } else {
                flash('note_error', implode('<br>', $errors));
                redirect('caretaker/addNotePage');
            }
        }

        redirect('caretaker/notes');
    }

    public function editNotePage($id) {
        $caretaker_id = $_SESSION['user_id'] ?? null;
        $note = $this->caretakerModel->getNoteById($id);

        if (!$note || $note->caretaker_id != $caretaker_id) {
            flash('note_error', 'Access denied');
            redirect('caretaker/notes');
        }

        $data = [
            'title' => 'Edit Note',
            'pageTitle' => 'Edit Note',
            'note' => $note
        ];

        $this->view('caretaker/v_edit_note', $data);
    }

    public function updateNote($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $caretaker_id = $_SESSION['user_id'] ?? null;

            $note = $this->caretakerModel->getNoteById($id);

            if (!$note || $note->caretaker_id != $caretaker_id) {
                flash('note_error', 'Access denied');
                redirect('caretaker/notes');
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $errors = [];

            if (empty(trim($_POST['title']))) $errors[] = 'Enter a title';
            if (empty(trim($_POST['note_content']))) $errors[] = 'Enter content';
            if (strlen(trim($_POST['title'])) > 255) $errors[] = 'Title too long';
            if (strlen(trim($_POST['note_content'])) < 10) $errors[] = 'Content too short';

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
                    flash('note_message', 'Note updated');
                    redirect('caretaker/notes');
                } else {
                    flash('note_error', 'Update failed');
                    redirect('caretaker/editNotePage/' . $id);
                }

            } else {
                flash('note_error', implode('<br>', $errors));
                redirect('caretaker/editNotePage/' . $id);
            }
        }

        redirect('caretaker/notes');
    }

    public function deleteNote($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $caretaker_id = $_SESSION['user_id'] ?? null;

            $note = $this->caretakerModel->getNoteById($id);

            if (!$note || $note->caretaker_id != $caretaker_id) {
                flash('note_error', 'Access denied');
                redirect('caretaker/notes');
            }

            if ($this->caretakerModel->deleteNote($id, $caretaker_id)) {
                flash('note_message', 'Note deleted');
            } else {
                flash('note_error', 'Delete failed');
            }

            redirect('caretaker/notes');
        }

        redirect('caretaker/notes');
    }

    public function togglePin($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $caretaker_id = $_SESSION['user_id'] ?? null;

            $note = $this->caretakerModel->getNoteById($id);

            if (!$note || $note->caretaker_id != $caretaker_id) {
                flash('note_error', 'Access denied');
                redirect('caretaker/notes');
            }

            if ($this->caretakerModel->togglePin($id, $caretaker_id)) {
                flash('note_message', $note->is_pinned ? 'Note unpinned' : 'Note pinned');
            } else {
                flash('note_error', 'Failed to update');
            }

            redirect('caretaker/notes');
        }

        redirect('caretaker/notes');
    }
}
