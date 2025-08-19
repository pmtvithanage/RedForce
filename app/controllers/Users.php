<?php
    class Users extends Controller{
        private $userModel;
        public function __construct() {
            $this->userModel = $this->model('M_users');
        }

        public function index() {
            
        }

        public function login(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //form submission
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'userID' => trim($_POST['userID'] ?? ''),
                    'password' => trim($_POST['password'] ?? ''),
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
                        //Redirect to home page or dashboard
                        $this->createUserSession($loggedInUser);
                        
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
                $data = [
                    'userID' => '',
                    'password' => '',
                    'userID_err' => '',
                    'password_err' => ''
                ];
                // Load the login view with the data
                $this->view('users/v_login', $data);
                
            }
        }

        public function about() {
            $users = $this->userModel->getUsers();
            
            $data = [
                'users' => $users
            ];
            $this->view('v_about',$data);
        }
    }
?>


