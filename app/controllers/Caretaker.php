<?php
class Caretaker extends Controller
{
    private $caretakerModel;
    private $userModel;
    private $advertisementModel;
    private $notificationModel;
    private $leaveRequestModel;

    public function __construct()
    {
        requireAuth('caretaker');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->caretakerModel = $this->model('M_caretaker');
        $this->userModel = $this->model('M_users');
        $this->notificationModel = $this->model('M_notifications');
        $this->leaveRequestModel = $this->model('M_leaveRequests');
    }

    public function index()
    {
        redirect('caretaker/dashboard');
    }

    public function dashboard()
    {
        $role = 'Care-Taker';
        $caretaker_id = $_SESSION['user_id'] ?? null;

        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);
        $stats = $this->caretakerModel->getDashboardStats($caretaker_id);
        $recent_activities = $this->caretakerModel->getRecentActivities($caretaker_id);

        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'advertisements' => $advertisements,
            'stats' => $stats,
            'recent_activities' => $recent_activities
        ];
        $this->view('caretaker/dashboard/v_dashboard', $data);
    }

    public function messages()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        $messageModel = $this->model('M_message');

        $conversations = $messageModel->getConversations($user_id);
        $all_users = $messageModel->getAllUsersForCaretaker($user_id);

        $data = [
            'title' => 'Messages',
            'pageTitle' => 'Messages',
            'conversations' => $conversations,
            'all_users' => $all_users
        ];
        $this->view('caretaker/v_messages', $data);
    }

    public function notifications()
    {
        // TODO: Fetch notifications from database
        $notifications = $this->notificationModel->getNotifications($_SESSION['user_id']);

        $data = [
            'title' => 'Notifications',
            'pageTitle' => 'Notifications',
            'role' => 'caretaker',
            'notifications' => $notifications
        ];
        $this->view('components/notifications', $data);
    }
    /* --------------------------
       View Site Info
    ---------------------------*/
    public function siteInfo()
    {
        $caretaker_id = $_SESSION['user_id'] ?? null;

        if (!$caretaker_id) {
            flash('site_error', 'User not authenticated');
            redirect('caretaker/dashboard');
            return;
        }

        $site = $this->caretakerModel->getAssignedSite($caretaker_id);
        $supervisors = [];

        if ($site) {
            $supervisors = $this->caretakerModel->getAssignedSupervisors($site->id);
        }

        $data = [
            'title' => 'Site Information',
            'pageTitle' => $site ? 'Site Information - ' . $site->site_name : 'Site Information',
            'site' => $site,
            'supervisors' => $supervisors
        ];
        $this->view('caretaker/site_info/v_site_info', $data);
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

    public function equipmentRequests()
    {
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

        $this->view('caretaker/equipmentRequests/request', $data);
    }

    public function addEquipmentPage()
    {
        $data = [
            'title' => 'Equipment Requests',
            'pageTitle' => 'Request Equipment'
        ];
        $this->view('caretaker/equipmentRequests/v_add_equipment', $data);
    }

    public function addEquipmentRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $caretaker_id = $_SESSION['user_id'] ?? null;

            if (!$caretaker_id) {
                flash('equipment_error', 'User not authenticated');
                redirect('caretaker/equipmentRequests');
                return;
            }

            if (
                empty($_POST['equipment_name']) || empty($_POST['quantity']) ||
                empty($_POST['estimated_cost']) || empty($_POST['reason']) ||
                empty($_POST['priority'])
            ) {

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
                // Log recent activity
                try {
                    $this->caretakerModel->insertRecentActivity(
                        $caretaker_id,
                        'Equipment Request Submitted',
                        "Requested {$data['quantity']} x {$data['equipment_name']} - Priority: {$data['priority']}",
                        'equipment_request'
                    );
                } catch (Exception $e) {
                    error_log("Error logging equipment request activity: " . $e->getMessage());
                }

                flash('equipment_message', 'Request submitted', 'alert-success');
            } else {
                flash('equipment_error', 'Failed to submit');
            }

            redirect('caretaker/equipmentRequests');
        }

        redirect('caretaker/equipmentRequests');
    }

    public function editEquipmentPage($id)
    {
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
            'title' => 'Equipment Requests',
            'pageTitle' => 'Edit Equipment Request',
            'request' => $request
        ];

        $this->view('caretaker/equipmentRequests/v_edit_equipment', $data);
    }

    public function updateEquipmentRequest($id)
    {
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

            if (
                empty($_POST['equipment_name']) || empty($_POST['quantity']) ||
                empty($_POST['estimated_cost']) || empty($_POST['reason'])
            ) {

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

    public function deleteEquipmentRequest($id)
    {
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

    public function notes()
    {
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

    public function addNotePage()
    {
        $data = [
            'title' => 'My Notes',
            'pageTitle' => 'Add New Note'
        ];
        $this->view('caretaker/v_add_note', $data);
    }

    public function addNote()
    {
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
                    'comments' => $POST['comments'] ?? '',
                    'reminder_date' => !empty($_POST['reminder_date']) ? $_POST['reminder_date'] : null
                ];

                if ($this->caretakerModel->addNote($data)) {
                    // Log recent activity
                    try {
                        $this->caretakerModel->insertRecentActivity(
                            $caretaker_id,
                            'Note Created',
                            "Created note: {$data['title']} - Category: {$data['category']}",
                            'note_created'
                        );
                    } catch (Exception $e) {
                        error_log("Error logging note creation activity: " . $e->getMessage());
                    }

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

    public function editNotePage($id)
    {
        $caretaker_id = $_SESSION['user_id'] ?? null;
        $note = $this->caretakerModel->getNoteById($id);

        if (!$note || $note->caretaker_id != $caretaker_id) {
            flash('note_error', 'Access denied');
            redirect('caretaker/notes');
        }

        $data = [
            'title' => 'My Notes',
            'pageTitle' => 'Edit Note',
            'note' => $note
        ];

        $this->view('caretaker/v_edit_note', $data);
    }

    public function updateNote($id)
    {
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

    public function deleteNote($id)
    {
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

    public function togglePin($id)
    {
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

    /* --------------------------
       MESSAGING FUNCTIONALITY
    ---------------------------*/

    // Get conversations (AJAX)
    public function getConversations()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            $user_id = $_SESSION['user_id'] ?? null;

            if (!$user_id) {
                echo json_encode(['status' => 'error']);
                return;
            }

            $messageModel = $this->model('M_message');
            $conversations = $messageModel->getConversations($user_id);
            echo json_encode(['status' => 'success', 'conversations' => $conversations]);
        }
    }

    // Load messages (AJAX)
    public function loadMessages()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            $sender_id = $_SESSION['user_id'] ?? null;
            $recipient_id = $_POST['recipient_id'] ?? null;

            if (!$sender_id || !$recipient_id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid user']);
                return;
            }

            $messageModel = $this->model('M_message');
            // Mark messages as read
            $messageModel->markAsRead($recipient_id, $sender_id);

            // Get messages
            $messages = $messageModel->getMessages($sender_id, $recipient_id);
            echo json_encode(['status' => 'success', 'messages' => $messages]);
        }
    }

    // Send message (AJAX)
    public function sendMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            $sender_id = $_SESSION['user_id'] ?? null;
            $recipient_id = $_POST['recipient_id'] ?? null;
            $message = trim($_POST['message'] ?? '');

            if (!$sender_id || !$recipient_id || empty($message)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
                return;
            }

            // Sanitize message
            $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

            $messageModel = $this->model('M_message');
            if ($messageModel->sendMessage($sender_id, $recipient_id, $message)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => $message,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
            }
        }
    }

    // Mark messages as seen (AJAX)
    public function markAsSeen()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            $sender_id = $_SESSION['user_id'] ?? null;
            $recipient_id = $_POST['recipient_id'] ?? null;

            if (!$sender_id || !$recipient_id) {
                echo json_encode(['status' => 'error']);
                return;
            }

            $messageModel = $this->model('M_message');
            // Mark messages as seen
            $messageModel->markAsSeen($sender_id, $recipient_id);

            echo json_encode(['status' => 'success']);
        }
    }

    // Delete message (AJAX)
    public function deleteMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            $user_id = $_SESSION['user_id'] ?? null;
            $message_id = $_POST['message_id'] ?? null;

            if (!$user_id || !$message_id) {
                echo json_encode(['status' => 'error']);
                return;
            }

            $messageModel = $this->model('M_message');
            if ($messageModel->deleteMessage($message_id, $user_id)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    // Update message (AJAX)
    public function updateMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            $user_id = $_SESSION['user_id'] ?? null;
            $message_id = $_POST['message_id'] ?? null;
            $message = $_POST['message'] ?? null;

            if (!$user_id || !$message_id || !$message) {
                echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
                return;
            }

            $messageModel = $this->model('M_message');
            if ($messageModel->updateMessage($message_id, $user_id, $message)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update message']);
            }
        }
    }

    // Get user online status (AJAX)
    public function getUserStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            $user_id = $_POST['user_id'] ?? null;

            if (!$user_id) {
                echo json_encode(['status' => 'error', 'message' => 'User ID required']);
                return;
            }

            $userStatus = $this->userModel->getUserOnlineStatus($user_id);

            if ($userStatus) {
                echo json_encode([
                    'status' => 'success',
                    'is_online' => $userStatus->is_online ?? false,
                    'last_seen' => $userStatus->last_seen ?? null
                ]);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'is_online' => false,
                    'last_seen' => null
                ]);
            }
        }
    }

    // Update user last seen (AJAX)
    public function updateLastSeen()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;

            if ($user_id) {
                $this->userModel->updateLastSeen($user_id);
            }
        }
    }

    // Set user offline (AJAX)
    public function setOffline()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;

            if ($user_id) {
                $this->userModel->setUserOffline($user_id);
            }
        }
    }

    // ======================================================================== //
    // =======================      Leave Requests      ======================= //
    // ======================================================================== //

    // Leave Requests
    public function leaverequests()
    {
        $user_id = $_SESSION['user_id'] ?? null;

        if (!$user_id) {
            redirect('login');
        }

        $leaveRequests = $this->leaveRequestModel->getLeaveRequestsByUser($user_id, 'caretaker');
        $stats = $this->leaveRequestModel->getLeaveStats($user_id, 'caretaker');

        $data = [
            'title' => 'Leave Requests',
            'pageTitle' => 'Leave Requests',
            'leaveRequests' => $leaveRequests,
            'stats' => $stats
        ];
        $this->view('caretaker/leaverequests/v_leaverequests', $data);
    }

    // Create Leave Request
    public function createLeaveRequest()
    {
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
                    'caretaker_id' => $_SESSION['user_id'],
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
                        $userName = $user->name ?? 'A caretaker';

                        if ($admins && is_array($admins)) {
                            foreach ($admins as $admin) {
                                $this->notificationModel->addNotification(
                                    $admin->id,
                                    'info',
                                    'New Leave Request',
                                    "{$userName} (Caretaker) submitted a leave request for {$data['leave_type_value']} from {$data['start_date_value']} to {$data['end_date_value']}",
                                    URL_ROOT . '/admin/pendings',
                                    'calendar_today',
                                    $_SESSION['user_id']
                                );
                            }
                        }

                        // Log recent activity
                        $caretakerModel = $this->model('M_caretaker');
                        $caretakerModel->insertRecentActivity(
                            $_SESSION['user_id'],
                            'Leave Request Submitted',
                            "Submitted {$data['leave_type_value']} leave request from {$data['start_date_value']} to {$data['end_date_value']}",
                            'leave_request'
                        );
                    } catch (Exception $e) {
                        error_log("Error sending leave request notifications: " . $e->getMessage());
                    }

                    flash('msg', 'Leave request submitted successfully', 'alert-success');
                    redirect('caretaker/leaverequests');
                } else {
                    flash('msg', 'Failed to submit leave request', 'alert-danger');
                    $this->view('caretaker/leaverequests/v_create_leaverequest', $data);
                }
            } else {
                // Show form with errors
                $this->view('caretaker/leaverequests/v_create_leaverequest', $data);
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
            $this->view('caretaker/leaverequests/v_create_leaverequest', $data);
        }
    }

    // View Leave Request
    public function viewLeaveRequest($id)
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $leaveRequest = $this->leaveRequestModel->getLeaveRequestById($id, 'caretaker');

        // Check if leave request exists and belongs to user
        if (!$leaveRequest || !$this->leaveRequestModel->isOwnedByUser($id, $_SESSION['user_id'], 'caretaker')) {
            flash('msg', 'Leave request not found', 'alert-danger');
            redirect('caretaker/leaverequests');
        }

        $data = [
            'title' => 'Leave Requests',
            'pageTitle' => 'Leave Request Details',
            'leaveRequest' => $leaveRequest
        ];
        $this->view('caretaker/leaverequests/v_view_request', $data);
    }

    // Edit Leave Request
    public function editLeaveRequest($id)
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $leaveRequest = $this->leaveRequestModel->getLeaveRequestById($id, 'caretaker');

        // Check if leave request exists and belongs to user
        if (!$leaveRequest || !$this->leaveRequestModel->isOwnedByUser($id, $_SESSION['user_id'], 'caretaker')) {
            flash('msg', 'Leave request not found', 'alert-danger');
            redirect('caretaker/leaverequests');
        }

        // Check if request can be edited (only Pending)
        if ($leaveRequest->status != 'Pending') {
            flash('msg', 'Cannot edit this leave request', 'alert-danger');
            redirect('caretaker/leaverequests');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission (similar to create)
            $data = [
                'title' => 'Leave Requests',
                'pageTitle' => 'Edit Leave Request',
                'leaveRequest' => $leaveRequest,
                'leave_type_value' => trim($_POST['leave_type'] ?? ''),
                'reason_value' => trim($_POST['reason'] ?? ''),
                'start_date_value' => trim($_POST['start_date'] ?? ''),
                'start_time_value' => trim($_POST['start_time'] ?? ''),
                'end_date_value' => trim($_POST['end_date'] ?? ''),
                'current_file' => $leaveRequest->proof_file,
                'leave_type_err' => '',
                'reason_err' => '',
                'start_date_err' => '',
                'start_time_err' => '',
                'end_date_err' => '',
                'proof_file_err' => ''
            ];

            // Validate (same as create)
            if (empty($data['leave_type_value'])) {
                $data['leave_type_err'] = 'Please select a leave type';
            }
            if (empty($data['reason_value'])) {
                $data['reason_err'] = 'Please enter a reason for leave';
            }
            if (empty($data['start_date_value'])) {
                $data['start_date_err'] = 'Please select a start date';
            }
            if (empty($data['start_time_value'])) {
                $data['start_time_err'] = 'Please select a start time';
            }
            if (empty($data['end_date_value'])) {
                $data['end_date_err'] = 'Please select an end date';
            } elseif (!empty($data['start_date_value']) && strtotime($data['end_date_value']) < strtotime($data['start_date_value'])) {
                $data['end_date_err'] = 'End date must be after start date';
            }

            // Handle file updates
            $proof_file_path = $leaveRequest->proof_file;
            if (isset($_POST['remove_file']) && $_POST['remove_file'] == '1') {
                $proof_file_path = null;
            }
            if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] == UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
                $file_type = $_FILES['proof_file']['type'];
                if (!in_array($file_type, $allowed_types)) {
                    $data['proof_file_err'] = 'Only JPG, PNG, GIF, and PDF files are allowed';
                } else {
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

            if (empty($data['leave_type_err']) && empty($data['reason_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['proof_file_err'])) {
                $updateData = [
                    'leave_type' => $data['leave_type_value'],
                    'reason' => $data['reason_value'],
                    'start_date' => $data['start_date_value'],
                    'end_date' => $data['end_date_value'],
                    'proof_file' => $proof_file_path
                ];

                if ($this->leaveRequestModel->updateLeaveRequest($id, $updateData, $_SESSION['user_id'], 'caretaker')) {
                    flash('msg', 'Leave request updated successfully', 'alert-success');
                    redirect('caretaker/leaverequests');
                } else {
                    flash('msg', 'Failed to update leave request', 'alert-danger');
                    $this->view('caretaker/leaverequests/v_edit_request', $data);
                }
            } else {
                $this->view('caretaker/leaverequests/v_edit_request', $data);
            }
        } else {
            $data = [
                'title' => 'Leave Requests',
                'pageTitle' => 'Edit Leave Request',
                'leaveRequest' => $leaveRequest,
                'leave_type_value' => $leaveRequest->leave_type,
                'reason_value' => $leaveRequest->reason,
                'start_date_value' => $leaveRequest->start_date,
                'start_time_value' => $leaveRequest->start_time,
                'end_date_value' => $leaveRequest->end_date,
                'current_file' => $leaveRequest->proof_file,
                'leave_type_err' => '',
                'reason_err' => '',
                'start_date_err' => '',
                'start_time_err' => '',
                'end_date_err' => '',
                'proof_file_err' => ''
            ];
            $this->view('caretaker/leaverequests/v_edit_request', $data);
        }
    }

    // Delete Leave Request
    public function deleteLeaveRequest($id)
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        if (!$this->leaveRequestModel->isOwnedByUser($id, $_SESSION['user_id'], 'caretaker')) {
            flash('msg', 'Unauthorized action', 'alert-danger');
            redirect('caretaker/leaverequests');
        }

        if ($this->leaveRequestModel->deleteLeaveRequest($id, $_SESSION['user_id'], 'caretaker')) {
            flash('msg', 'Leave request deleted successfully', 'alert-success');
        } else {
            flash('msg', 'Failed to delete leave request or request is not pending', 'alert-danger');
        }

        redirect('caretaker/leaverequests');
    }

    /* --------------------------
       REMINDER AJAX ENDPOINTS
    ---------------------------*/

    // Get pending and overdue reminders (AJAX)
    public function getReminders()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $caretaker_id = $_SESSION['user_id'];

        // Get today's reminders
        $todayReminders = $this->caretakerModel->getTodayReminders($caretaker_id);

        // Get overdue reminders
        $overdueReminders = $this->caretakerModel->getOverdueReminders($caretaker_id);

        echo json_encode([
            'success' => true,
            'today' => $todayReminders ?? [],
            'overdue' => $overdueReminders ?? []
        ]);
    }

    // Mark reminder as completed (AJAX)
    public function completeReminder()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $caretaker_id = $_SESSION['user_id'];
        $note_id = $_POST['note_id'] ?? null;

        if (!$note_id) {
            echo json_encode(['success' => false, 'message' => 'Note ID required']);
            return;
        }

        if ($this->caretakerModel->completeReminder($note_id, $caretaker_id)) {
            echo json_encode(['success' => true, 'message' => 'Reminder marked as completed']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to complete reminder']);
        }
    }
// ======================================================================== //
// =======================      profile       ====================== //
// ======================================================================== //

public function profile()
{
    $data = [
        'title' => 'Profile',
        'pageTitle' => 'My Profile',
        'caretaker' => $this->caretakerModel->getCaretakerById($_SESSION['user_userID'])
    ];
    $this->view('caretaker/profile/v_profile', $data);
}

public function editProfile()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $caretaker = $this->caretakerModel->getCaretakerById($_SESSION['user_userID']);

        $data = [
            'title' => 'Profile',
            'pageTitle' => 'Edit Profile',
            'caretaker' => $caretaker,
            'name' => $this->sanitizeInput($_POST['name'] ?? ''),
            'email' => $this->sanitizeInput($_POST['email'] ?? ''),
            'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
            'current_password' => $_POST['current_password'] ?? '',
            'new_password' => $_POST['new_password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'name_err' => '',
            'email_err' => '',
            'phone_number_err' => '',
            'current_password_err' => '',
            'new_password_err' => '',
            'confirm_password_err' => '',
            'image_err' => ''
        ];

        // Validate name
        if (empty($data['name'])) {
            $data['name_err'] = 'Please enter your name';
        }

        // Validate email
        if (empty($data['email'])) {
            $data['email_err'] = 'Please enter your email';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['email_err'] = 'Please enter a valid email address';
        }

        // Validate phone number
        if (empty($data['phone_number'])) {
            $data['phone_number_err'] = 'Please enter your phone number';
        } elseif (!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])) {
            $data['phone_number_err'] = 'Please enter a valid phone number (10-15 digits)';
        }

        // Password validation
        if (!empty($data['new_password']) || !empty($data['confirm_password'])) {

            if (empty($data['current_password'])) {
                $data['current_password_err'] = 'Please enter your current password';
            } elseif (!password_verify($data['current_password'], $caretaker->password)) {
                $data['current_password_err'] = 'Current password is incorrect';
            }

            if (empty($data['new_password'])) {
                $data['new_password_err'] = 'Please enter a new password';
            } elseif (strlen($data['new_password']) < 6) {
                $data['new_password_err'] = 'Password must be at least 6 characters';
            }

            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm your password';
            } elseif ($data['new_password'] !== $data['confirm_password']) {
                $data['confirm_password_err'] = 'Passwords do not match';
            }
        }

        // Image upload
        $profileImageName = $caretaker->profile_image;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {

            $file = $_FILES['profile_image'];
            $allowed = ['image/jpeg', 'image/jpg', 'image/png'];

            if (!in_array($file['type'], $allowed)) {
                $data['image_err'] = 'Only JPEG and PNG images are allowed';
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $data['image_err'] = 'Image size must be less than 5MB';
            } else {

                $profileImageName = uniqid() . '_' . basename($file['name']);
                $uploadPath = PUB_ROOT . '/uploads/applicantPhotos/' . $profileImageName;

                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {

                    $oldImagePath = PUB_ROOT . '/uploads/applicantPhotos/' . $caretaker->profile_image;
                    if (file_exists($oldImagePath) && $caretaker->profile_image !== 'default.png') {
                        unlink($oldImagePath);
                    }

                } else {
                    $data['image_err'] = 'Failed to upload image';
                    $profileImageName = $caretaker->profile_image;
                }
            }
        }

        // Final update
        if (
            empty($data['name_err']) &&
            empty($data['email_err']) &&
            empty($data['phone_number_err']) &&
            empty($data['current_password_err']) &&
            empty($data['new_password_err']) &&
            empty($data['confirm_password_err']) &&
            empty($data['image_err'])
        ) {

            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'profile_image' => $profileImageName
            ];

            if (!empty($data['new_password'])) {
                $updateData['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
            }

            if ($this->caretakerModel->updateCaretakerProfile($_SESSION['user_userID'], $updateData)) {
                flash('msg', 'Profile updated successfully', 'alert-success');
                redirect('Caretaker/profile');
            } else {
                flash('msg', 'Failed to update profile', 'alert-danger');
                $this->view('caretaker/profile/v_editProfile', $data);
            }

        } else {
            flash('msg', 'Please fix the errors in the form', 'alert-danger');
            $this->view('caretaker/profile/v_editProfile', $data);
        }

    } else {

        $caretaker = $this->caretakerModel->getCaretakerById($_SESSION['user_userID']);

        $data = [
            'title' => 'Profile',
            'pageTitle' => 'Edit Profile',
            'caretaker' => $caretaker,
            'name' => $caretaker->name ?? '',
            'email' => $caretaker->email ?? '',
            'phone_number' => $caretaker->phone_number ?? '',
            'name_err' => '',
            'email_err' => '',
            'phone_number_err' => '',
            'current_password_err' => '',
            'new_password_err' => '',
            'confirm_password_err' => '',
            'image_err' => ''
        ];

        $this->view('caretaker/profile/v_editProfile', $data);
    }
}

// Sanitize input
private function sanitizeInput($input)
{
    $input = trim($input ?? '');
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return strip_tags($input);
}
