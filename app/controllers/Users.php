<?php
    class Users extends Controller{
        private $userModel;
        public function __construct() {
            $this->userModel = $this->model('M_users');
        }

        public function index() {
            
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

