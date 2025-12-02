<?php
class Home extends Controller {
    private $homeModel;

    public function __construct() {
        $this->homeModel = $this->model('M_home');
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

            if(empty($data['image'])){
                $data['image_err'] = 'Please upload a logo';
            }elseif($data['image']['size'] > 0){
                if(uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/clientLogos/')){
                    
                }else{
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
        $this->view('home/v_premise_officer_job');
    }
    public function care_taker() {
        $this->view('home/v_care_taker_job');
    }
    public function mobile_rider() {
        $this->view('home/v_mobile_rider_job');
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