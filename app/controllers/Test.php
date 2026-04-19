<?php
class Test extends Controller {
    private $testModel;
    public function __construct() {
        $this-> testModel = $this->model('M_test');
    }
    public function index() {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $data = [
                'name' => $this->sanitizeInput($_POST['name'] ?? ''),
                'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                'gender' => $this->sanitizeInput($_POST['gender'] ?? ''),
                'description' => $this->sanitizeInput($_POST['description'] ?? ''),
                'reading_books' => isset($_POST['reading_books']) ? 1 : 0,
                'play_games' => isset($_POST['play_games']) ? 1 : 0,
                'collect_stamps' => isset($_POST['collect_stamps']) ? 1 : 0,
                'watch_tv' => isset($_POST['watch_tv']) ? 1 : 0,

                'image' => $_FILES['image'],
                'image_name' => time(). '_' . $_FILES['image']['name'],

                'image_err' => '',
                'name_err' => '',
                'email_err' => '',
                'gender_err' => '',
                'description_err' => ''
            ];

            if(empty($data['name'])){
                $data['name_err'] = 'Please enter your name';
            }
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $data['email_err'] = 'Please enter a valid email address';
            }
            if(empty($data['gender'])){
                $data['gender_err'] = 'Please select a gender';
            }
            if(empty($data['description'])){
                $data['description_err'] = 'Please enter a description';
            }

            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['gender_err']) && empty($data['description_err']) && empty($data['image_err'])){
                $imageUploaded = uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/applicantPhotos/');

                if($this->testModel->saveApplication($data)){
                    flash('msg', 'Application submitted successfully', 'alert-success');
                    redirect('test/table');
                    return;
                }
            }
        }
        else{
            $data = [
                'name' => '',
                'email' => '',

                'gender' => '',
                
                'description' => '',

                'reading_books' => 0,
                'play_games' => 0,
                'collect_stamps' => 0,
                'watch_tv' => 0,

                'image' => '',
                'image_name' => '',

                'image_err' => '',
                'name_err' => '',
                'email_err' => '',
                'gender_err' => '',
                'description_err' => ''
            ];
        }

        $this->view('v_test', $data);
    }

    public function table(){
        $data = [
            'applications' => $this->testModel->getApplications()
        ];
        $this->view('v_test2', $data);
    }
















    private function sanitizeInput($input) {
        try {
            $input = trim($input ?? '');
            $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $input = strip_tags($input);
            return $input;
        } catch (Exception $e) {
            return '';
        }
    }
}