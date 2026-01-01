<?php
class Client extends Controller {
    private $clientModel;
    private $userModel;
    private $messageModel;

    public function __construct() {
        // Check if user is logged in and has client role
        requireAuth('client');
        $this->clientModel = $this->model('M_client');
        $this->userModel = $this->model('M_users');
        $this->messageModel = $this->model('M_mobilerider');
    }

    // Default action - redirect to dashboard
    public function index() {
        redirect('client/dashboard');
    }

    // dashboard
    public function dashboard() {
        // Sample data - replace with actual database queries
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard'
        ];
        
        $this->view('client/v_dashboard', $data);
    }

    //view officers (guards)
    public function officers() {
        // Sample data - replace with actual database queries
        $data = [
            'title' => 'View Officers',
            'pageTitle' => 'View Guards'
        ];
        
        $this->view('client/v_officers', $data);
    }

    //requests
    public function requests() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        // Initialize variables
        $data = [
            'title' => 'Requests',
            'pageTitle' => 'Service Requests',
            'showSuccessMessage' => false,
            'errorMessage' => '',
            'previousRequests' => [],
            'showHistory' => isset($_GET['show_history'])
        ];

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
            // Get current date
            $currentDate = date('Y-m-d');
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            
            // Validate dates
            if ($startDate < $currentDate) {
                $data['errorMessage'] = 'Starting date cannot be in the past. Please select today or a future date.';
            } elseif ($endDate < $currentDate) {
                $data['errorMessage'] = 'Ending date cannot be in the past. Please select today or a future date.';
            } elseif ($endDate < $startDate) {
                $data['errorMessage'] = 'Ending date cannot be before starting date.';
            } else {
                // Prepare data for database
                $requestData = [
                    'client_id' => $_SESSION['user_id'],
                    'event_name' => trim($_POST['eventName']),
                    'event_description' => trim($_POST['eventDescription']),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'start_time' => $_POST['startTime'],
                    'end_time' => $_POST['endTime'],
                    'location' => trim($_POST['location']),
                    'number_of_guards' => $_POST['numberOfGuards'],  // Changed from guardCount
                    'comments' => trim($_POST['comments'])
                ];

                // Save to database (removed guard type validation)
                if ($this->clientModel->createServiceRequest($requestData)) {
                    $data['showSuccessMessage'] = true;
                    // Clear POST data to prevent resubmission
                    $_POST = [];
                } else {
                    $data['errorMessage'] = 'Failed to submit request. Please try again.';
                }
            }
        }

        // Handle delete request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_request'])) {
            $request_id = $_POST['request_id'];
            
            if ($this->clientModel->deleteServiceRequest($request_id, $_SESSION['user_id'])) {
                // Redirect back to history view with success message
                redirect('client/requests?show_history=1&deleted=1');
            } else {
                $data['errorMessage'] = 'Failed to delete request. Please try again.';
            }
        }

        // Get client's previous requests for history popup
        $data['previousRequests'] = $this->clientModel->getClientServiceRequests($_SESSION['user_id']);
        $data['todayDate'] = date('Y-m-d');

        // Load view
        $this->view('client/v_requests', $data);
    }

    //view history
    public function history() {
        $data = [
            'title' => 'View History',
            'pageTitle' => 'Service History'
        ];
        $this->view('client/v_history', $data);
    }

    //profile
    public function profile() {
        $data = [
            'title' => 'Profile',
            'pageTitle' => 'My Profile'
        ];
        $this->view('client/v_profile', $data); 
    }

    // ==================== MESSAGES (Client) ====================
    public function messages()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            redirect('client/dashboard');
            return;
        }
        
        $conversations = $this->messageModel->getConversations($user_id);
        $all_users = $this->messageModel->getAllUsers($user_id);
        $unread_count = $this->messageModel->getUnreadCount($user_id);
        
        $data = [
            'title' => 'Messages',
            'conversations' => $conversations,
            'all_users' => $all_users,
            'unread_count' => $unread_count,
            'current_recipient_id' => isset($_GET['with']) ? $_GET['with'] : null
        ];
        
        $this->view('Client/v_messages', $data);
    }

    // Load messages with a specific user (AJAX)
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
            
            // Mark messages as read
            $this->messageModel->markAsRead($recipient_id, $sender_id);
            
            // Get messages
            $messages = $this->messageModel->getMessages($sender_id, $recipient_id);
            
            echo json_encode(['status' => 'success', 'messages' => $messages]);
        }
    }

    // Send a message (AJAX)
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
            
            if ($this->messageModel->sendMessage($sender_id, $recipient_id, $message)) {
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

    // Get all available users (AJAX)
    public function getAllUsers()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            
            if (!$user_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            $users = $this->messageModel->getAllUsers($user_id);
            echo json_encode(['status' => 'success', 'users' => $users]);
        }
    }

    // Get conversations (AJAX for updates)
    public function getConversations()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            
            if (!$user_id) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            $conversations = $this->messageModel->getConversations($user_id);
            echo json_encode(['status' => 'success', 'conversations' => $conversations]);
        }
    }

    // Search conversations (AJAX)
    public function searchMessages()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $user_id = $_SESSION['user_id'] ?? null;
            $search_term = trim($_POST['search'] ?? '');
            
            if (!$user_id || empty($search_term)) {
                echo json_encode(['status' => 'error']);
                return;
            }
            
            $results = $this->messageModel->searchConversations($user_id, $search_term);
            echo json_encode(['status' => 'success', 'results' => $results]);
        }
    }

    // Delete a message (AJAX)
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
            
            if ($this->messageModel->deleteMessage($message_id, $user_id)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

}