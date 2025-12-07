<?php
class Home extends Controller {
    private $homeModel;
    private $adminModel;

    public function __construct() {
        $this->homeModel = $this->model('M_home');
        $this->adminModel = $this->model('M_admin');
    }

    // Default action
    public function index() {
        $data = [
            'title' => 'Welcome to RedForce',
            'description' => 'This is the home page of the Security Officers Management System.'
        ];
        $this->view('home/index', $data);
    }

    // get services
    public function service() {

        if($_SERVER['REQUEST_METHOD']=='POST'){
            
            $data = [
                'image' => $_FILES['image'], // Handle file upload separately
                'image_name' => time(). '_' . $_FILES['image']['name'],

                'company_name' => $this->sanitizeInput($_POST['company_name'] ?? ''),
                'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
                'contact_person_name' => $this->sanitizeInput($_POST['contact_person_name'] ?? ''),
                
                'image_err' => '',
                'company_name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'contact_person_name_err' => ''
            ];

            // Validate inputs
            if(empty($data['image']['name'])){
                $data['image_err'] = 'Please upload an image';
            } elseif($data['image']['size'] > 0){
                if(uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/clientLogos/')){
                    // Image uploaded successfully
                } else {
                    $data['image_err'] = 'Failed to upload image';
                }
            }
            if(empty($data['company_name'])){
                $data['company_name_err'] = 'Please enter company name';
            }
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $data['email_err'] = 'Please enter a valid email address';
            }
            if(empty($data['phone_number'])){
                $data['phone_number_err'] = 'Please enter phone number';
            }elseif(!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])){
                $data['phone_number_err'] = 'Please enter a valid phone number';
            }

            if(empty($data['contact_person_name'])){
                $data['contact_person_name_err'] = 'Please enter contact person name';
            }

            // Make sure no errors
            if(empty($data['image_err']) && empty($data['company_name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && empty($data['contact_person_name_err'])){
                // Validated
                // Process the service request here (e.g., save to database, send email, etc.)
                if($this->homeModel->saveServiceRequest($data)){
                    flash('service_message', 'Service request submitted successfully.');
                    // Redirect to a thank you page or show success message
                    $this->view('home/v_success', $data);
                    return;
                } else {
                    die('Something went wrong. Please try again.');
                }
            }
            else{
                // Load the form with errors
                $this->view('home/v_services',$data);
            }
        }
        else{
            $data = [
                'image' => '', 
                'image_name' => '',

                'company_name' => '',
                'email' => '',
                'phone_number' => '',
                'contact_person_name' => '',

                'image_err' => '',
                'company_name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'contact_person_name_err' => ''
            ];
        }
        $this->view('home/v_services',$data);
    }

    

    // job applications
    public function premise_officer() {
        $this->submit_application('po');
    }
    public function care_taker() {
        $this->submit_application('ct'); 
    }
    public function mobile_rider() {
        $this->submit_application('mr');
    }

    public function submit_application($role) {
        $exist = $this->adminModel->getJobApplication($role);
        
        // Check if due date has passed
        if ($exist && !empty($exist->due_date)) { 
            $dueDate = date('Y-m-d', strtotime($exist->due_date));
                $today = date('Y-m-d');
            if ($dueDate < $today) {
                // If due date has passed, close the status
                $this->adminModel->changeStatus($role, 'closed');
            }
        }
        
        if(!$exist || $exist->status != 'open') {
            $this->view('home/v_not_opened');
            return;
        }
        else{
            if($_SERVER['REQUEST_METHOD']=='POST'){
                // Process the application form submission
                $data = [
                    'description' => $exist->description,
                    'qualifications' => $exist->qualifications,
                    'due_date' => $exist->due_date,
                    'role' => $role,

                    'name' => $this->sanitizeInput($_POST['name'] ?? ''),
                    'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                    'phone' => $this->sanitizeInput($_POST['phone'] ?? ''),
                    'image' => $_FILES['image'],
                    'image_name' => time(). '_' . $_FILES['image']['name'],
                    'cv' => $_FILES['cv'],
                    'cv_name' => time(). '_' . $_FILES['cv']['name'],

                    'image_err' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'phone_err' => '',
                    'cv_err' => '',
                    
                ];

                // Validate inputs
                if(empty($data['name'])){
                    $data['name_err'] = 'Please enter your name';
                }
                if(empty($data['email'])){
                    $data['email_err'] = 'Please enter email';
                } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                    $data['email_err'] = 'Please enter a valid email address';
                }
                if(empty($data['phone'])){
                    $data['phone_err'] = 'Please enter phone number';
                }elseif(!preg_match('/^[0-9]{10,15}$/', $data['phone'])){
                    $data['phone_err'] = 'Please enter a valid phone number';
                }
                if(empty($data['image']['name'])){
                    $data['image_err'] = 'Please upload a photo';
                } 
                if(empty($data['cv']['name'])){
                    $data['cv_err'] = 'Please attach your CV';
                }

                // Make sure no errors
                if(empty($data['name_err']) && empty($data['image_err']) && empty($data['cv_err']) && empty($data['email_err']) && empty($data['phone_err'])){
                    uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/applicantPhotos/');
                    uploadImage($data['cv']['tmp_name'], $data['cv_name'], '/uploads/applicantCVs/');
                    // Validated
                    if($this->homeModel->saveJobApplication($data, $role)){
                        flash('application_message', 'Application submitted successfully.');
                        $this->view('home/v_success_application', $data);
                        return;
                    } else {
                        die('Something went wrong. Please try again.');
                    }
                }
                else{
                    // Load the form with errors
                    $this->view('home/v_job_application',$data);
                }
            }
            else{
                $data = [
                    'description' => $exist->description,
                    'qualifications' => $exist->qualifications,
                    'due_date' => $exist->due_date,
                    'role' => $role,

                    'name' => '',
                    'email' => '',
                    'phone' => '',
                    'image' => '',
                    'image_name' => '',
                    'cv' => '',
                    'cv_name' => '',

                    'image_err' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'phone_err' => '',
                    'cv_err' => '',
                    

                ];

                $this->view('home/v_job_application',$data);
            }
        }   
        
    }

    /**
     * Sanitize input data
     * Replacement for FILTER_SANITIZE_STRING
     */
    private function sanitizeInput($input) {
        $input = trim($input ?? '');
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Remove or encode potentially dangerous characters
        $input = strip_tags($input);
        return $input;
    }
}