<?php
    class Users extends Controller{
        private $userModel;
        public function __construct() {
            $this->userModel = $this->model('M_users');
        }

        public function index() {
            // Redirect to login if not logged in
            if(!isLoggedIn()) {
                redirect('users/login');
            } else {
                // Redirect to appropriate dashboard based on role
                $this->redirectToDashboard();
            }
        }

        public function login(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
                $userID = isset($_POST['userID']) ? trim(htmlspecialchars($_POST['userID'])) : '';
                $password = isset($_POST['password']) ? trim(htmlspecialchars($_POST['password'])) : '';
        
                $data = [
                    'userID' => $userID,
                    'password' => $password,
                    'userID_err' => '',
                    'password_err' => ''
                ];

                //Validate userID
                if(empty($data['userID'])){
                    $data['userID_err'] = 'Please enter User ID';
                }else {
                    //Check if userID exists
                    if($this->userModel->findUserByUserID($data['userID'])){
                        //User found
                    }else {
                        $data['userID_err'] = 'No user found';
                    }
                }

                //Validate password
                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter password';
                }
                
                //Check if no errors log in the user
                if(empty($data['userID_err']) && empty($data['password_err'])) {
                    $loggedInUser = $this->userModel->login($data['userID'], $data['password']);

                    if($loggedInUser){
                        // Prevent session fixation
                        if (session_status() !== PHP_SESSION_ACTIVE) {
                            session_start();
                        }
                        session_regenerate_id(true);
                        
                        // Check if premise officer has Supervisor rank
                        if(strtolower($loggedInUser->role) === 'premise officer') {
                            $officerRank = $this->userModel->getOfficerRank($loggedInUser->id);
                            if($officerRank === 'Supervisor') {
                                $loggedInUser->role = 'supervisor';
                            }
                        }
                        
                        //Create session and redirect to appropriate dashboard
                        $this->createUserSession($loggedInUser);
                        $this->redirectToDashboard();
                        
                    } else {
                        $data['password_err'] = 'Password incorrect';
                        // Load the login view with errors
                        $this->view('users/v_login', $data);
                    }
                }else {
                    // Load the login view with errors
                    $this->view('users/v_login', $data);
                }

            }else {
                //Initially show the login form
                // Ensure any stale session is cleared to allow re-login
                if (isLoggedIn()) {
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                    $_SESSION = [];
                    if (ini_get('session.use_cookies')) {
                        $params = session_get_cookie_params();
                        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
                    }
                    session_destroy();
                    session_write_close();
                    // Start a fresh session context for the login form
                    session_start();
                }
                $data = [
                    'userID' => '',
                    'password' => '',
                    'userID_err' => '',
                    'password_err' => ''
                ];
                // Prevent cached copies of a previously authenticated page
                if (!headers_sent()) {
                    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
                    header('Pragma: no-cache');
                    header('Expires: 0');
                }
                // Load the login view with the data
                $this->view('users/v_login', $data);
                
            }
        }

        public function logout() {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            
            // Set user offline before destroying session
            if (isset($_SESSION['user_id'])) {
                $this->userModel->setUserOffline($_SESSION['user_id']);
            }
            
            // Unset all session values
            $_SESSION = [];

            // Delete the session cookie
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }

            // Destroy session
            session_destroy();
            session_write_close();

            // Redirect to login
            redirect('users/login');
        }

        private function createUserSession($user) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_userID'] = $user->userID;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_role'] = $user->role;
            $_SESSION['user_email'] = $user->email ?? '';
        }

        private function redirectToDashboard() {
            $role = $_SESSION['user_role'] ?? '';
            
            switch(strtolower($role)) {
                case 'admin':
                    redirect('admin/dashboard');
                    break;
                case 'supervisor':
                    redirect('supervisor/dashboard');
                    break;
                case 'premise officer':
                    redirect('PremiseOfficer/dashboard');
                    break;
                case 'mobile rider':
                    redirect('MobileRider/dashboard');
                    break;
                case 'client':
                    redirect('client/dashboard');
                    break;
                case 'caretaker':
                    redirect('caretaker/dashboard');
                    break;
                default:
                    // Default to admin dashboard for unknown roles
                    redirect('admin/dashboard');
                    break;
            }
        }

        public function about() {
            $users = $this->userModel->getUsers();
            
            $data = [
                'users' => $users
            ];
            $this->view('v_about',$data);
        }

        // Change password via POST (expects: currentPassword, newPassword)
        public function changePassword() {
            if (!isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                return;
            }

            header('Content-Type: application/json');

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $current = trim($_POST['currentPassword'] ?? '');
            $next = trim($_POST['newPassword'] ?? '');

            if ($current === '' || $next === '') {
                echo json_encode(['success' => false, 'message' => 'Missing fields']);
                return;
            }

            $userId = getCurrentUserId();
            $user = $this->userModel->getUserById($userId);
            if (!$user) {
                echo json_encode(['success' => false, 'message' => 'User not found']);
                return;
            }

            if (!password_verify($current, $user->password)) {
                echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
                return;
            }

            if (strlen($next) < 4) {
                echo json_encode(['success' => false, 'message' => 'New password must be at least 4 characters']);
                return;
            }

            $ok = $this->userModel->updatePassword($userId, $next);
            if ($ok) {
                echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update password']);
            }
        }
    }
?>


