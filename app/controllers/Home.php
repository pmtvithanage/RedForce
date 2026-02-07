<?php
class Home extends Controller {
    private $homeModel;
    private $adminModel;

    public function __construct() {
        try {
            $this->homeModel = $this->model('M_home');
            $this->adminModel = $this->model('M_admin');
        } catch (Exception $e) {
            $this->showError('Model initialization failed', $e);
        }
    }

    // Default action
    public function index() {
        try {
            $data = [
                'title' => 'Welcome to RedForce',
                'description' => 'This is the home page of the Security Officers Management System.'
            ];
            $this->view('home/index', $data);
        } catch (Exception $e) {
            $this->showError('Home page loading failed', $e);
        }
    }

    // get services
    public function service() {
        try {
            if($_SERVER['REQUEST_METHOD']=='POST'){
                $data = [
                    'image' => $_FILES['image'],
                    'image_name' => time(). '_' . $_FILES['image']['name'],
                    'company_name' => $this->sanitizeInput($_POST['company_name'] ?? ''),
                    'legal_company_name' => $this->sanitizeInput($_POST['legal_company_name'] ?? ''),
                    'company_type' => $this->sanitizeInput($_POST['company_type'] ?? ''),
                    'business_registration_number' => $this->sanitizeInput($_POST['business_registration_number'] ?? ''),
                    'registered_address' => $this->sanitizeInput($_POST['registered_address'] ?? ''),
                    'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                    'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
                    'contact_person_name' => $this->sanitizeInput($_POST['contact_person_name'] ?? ''),
                    'business_document' => $_FILES['business_document'] ?? null,
                    'business_document_name' => isset($_FILES['business_document']['name']) ? time(). '_' . $_FILES['business_document']['name'] : '',
                    'image_err' => '',
                    'company_name_err' => '',
                    'legal_company_name_err' => '',
                    'company_type_err' => '',
                    'business_registration_number_err' => '',
                    'registered_address_err' => '',
                    'email_err' => '',
                    'phone_number_err' => '',
                    'contact_person_name_err' => '',
                    'business_document_err' => '',
                    
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
                if(empty($data['legal_company_name'])){
                    $data['legal_company_name_err'] = 'Please enter legal company name';
                }
                if(empty($data['company_type'])){
                    $data['company_type_err'] = 'Please select company type';
                }
                if(empty($data['business_registration_number'])){
                    $data['business_registration_number_err'] = 'Please enter business registration number';
                }
                if(empty($data['registered_address'])){
                    $data['registered_address_err'] = 'Please enter registered address';
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
                
                // Validate business document
                if(empty($data['business_document']['name'])){
                    $data['business_document_err'] = 'Please upload business documentation';
                } elseif($data['business_document']['size'] > 0){
                    // Check file size (5MB max)
                    if($data['business_document']['size'] > 5242880){
                        $data['business_document_err'] = 'File size must be less than 5MB';
                    } else {
                        // Check file type
                        $allowed_types = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                        $file_extension = strtolower(pathinfo($data['business_document']['name'], PATHINFO_EXTENSION));
                        $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png'];
                        
                        if(!in_array($file_extension, $allowed_extensions)){
                            $data['business_document_err'] = 'Only PDF, JPG, and PNG files are allowed';
                        } else {
                            // Upload the document
                            if(uploadImage($data['business_document']['tmp_name'], $data['business_document_name'], '/uploads/businessDocuments/')){
                                // Document uploaded successfully
                            } else {
                                $data['business_document_err'] = 'Failed to upload business document';
                            }
                        }
                    }
                }

                // Make sure no errors
                if(empty($data['image_err']) && empty($data['company_name_err']) && empty($data['legal_company_name_err']) && 
                   empty($data['company_type_err']) && empty($data['business_registration_number_err']) && 
                   empty($data['registered_address_err']) && empty($data['email_err']) && 
                   empty($data['phone_number_err']) && empty($data['contact_person_name_err']) && 
                   empty($data['business_document_err'])){
                    if($this->homeModel->saveServiceRequest($data)){
                        flash('msg', 'Service request sent successfully', 'alert-success');
                        $this->view('home/v_success', $data);
                        return;
                    } else {
                        throw new Exception('Failed to save service request to database');
                    }
                }
                else{
                    $this->view('home/v_services',$data);
                }
            }
            else{
                $data = [
                    'image' => '', 
                    'image_name' => '',
                    'company_name' => '',
                    'legal_company_name' => '',
                    'company_type' => '',
                    'business_registration_number' => '',
                    'registered_address' => '',
                    'email' => '',
                    'phone_number' => '',
                    'contact_person_name' => '',
                    'business_document' => '',
                    'business_document_name' => '',
                    'image_err' => '',
                    'company_name_err' => '',
                    'legal_company_name_err' => '',
                    'company_type_err' => '',
                    'business_registration_number_err' => '',
                    'registered_address_err' => '',
                    'email_err' => '',
                    'phone_number_err' => '',
                    'contact_person_name_err' => '',
                    'business_document_err' => ''
                ];
                
                $this->view('home/v_services',$data);
            }
        } catch (Exception $e) {
            $this->showError('Service request processing failed', $e);
        }
    }

    // job applications
    public function premise_officer() {
        try {
            $this->submit_application('po');
        } catch (Exception $e) {
            $this->showError('Premise officer application failed', $e);
        }
    }
    
    public function care_taker() {
        try {
            $this->submit_application('ct'); 
        } catch (Exception $e) {
            $this->showError('Care taker application failed', $e);
        }
    }
    
    public function mobile_rider() {
        try {
            $this->submit_application('mr');
        } catch (Exception $e) {
            $this->showError('Mobile rider application failed', $e);
        }
    }

    public function submit_application($role) {
        try {
            $exist = $this->adminModel->getJobApplication($role);
            
            // Check if due date has passed
            if ($exist && !empty($exist->due_date)) { 
                $dueDate = date('Y-m-d', strtotime($exist->due_date));
                $today = date('Y-m-d');
                if ($dueDate < $today) {
                    $this->adminModel->changeStatus($role, 'closed');
                }
            }
            
            if(!$exist || $exist->status != 'open') {
                $this->view('home/v_not_opened');
                return;
            }
            else{
                if($_SERVER['REQUEST_METHOD']=='POST'){
                    $data = [
                        'description' => $exist->description,
                        'qualifications' => $exist->qualifications,
                        'due_date' => $exist->due_date,
                        'role' => $role,
                        'name' => $this->sanitizeInput($_POST['name'] ?? ''),
                        'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                        'phone' => $this->sanitizeInput($_POST['phone'] ?? ''),
                        'birthday' => $this->sanitizeInput($_POST['birthday'] ?? ''),
                        'national_id' => $this->sanitizeInput($_POST['national_id'] ?? ''),
                        'gender' => $this->sanitizeInput($_POST['gender'] ?? ''),
                        'address' => $this->sanitizeInput($_POST['address'] ?? ''),
                        'district' => $this->sanitizeInput($_POST['district'] ?? ''),
                        'city' => $this->sanitizeInput($_POST['city'] ?? ''),
                        
                        'image' => $_FILES['image'],
                        'image_name' => time(). '_' . $_FILES['image']['name'],
                        'cv' => $_FILES['cv'],
                        'cv_name' => time(). '_' . $_FILES['cv']['name'],

                        'image_err' => '',
                        'name_err' => '',
                        'email_err' => '',
                        'phone_err' => '',
                        'cv_err' => '',

                        'birthday_err' => '',
                        'national_id_err' => '',
                        'gender_err' => '',
                        'address_err' => '',
                        'district_err' => '',
                        'city_err' => '',
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
                    if(empty($data['birthday'])){
                        $data['birthday_err'] = 'Please enter your birthday';
                    }
                    if(empty($data['national_id'])){
                        $data['national_id_err'] = 'Please enter your NIC number';
                    }
                    if(empty($data['gender'])){
                        $data['gender_err'] = 'Please enter your gender';
                    }
                    if(empty($data['address'])){
                        $data['address_err'] = 'Please enter your address';
                    }
                    if(empty($data['district'])){
                        $data['district_err'] = 'Please enter your district';
                    }
                    if(empty($data['city'])){
                        $data['city_err'] = 'Please enter your city';
                    }

                    // Make sure no errors
                    if(empty($data['name_err']) && empty($data['image_err']) && empty($data['cv_err']) && empty($data['email_err']) && empty($data['phone_err']) && empty($data['birthday_err']) && empty($data['national_id_err']) && empty($data['gender_err']) && empty($data['address_err']) && empty($data['district_err']) && empty($data['city_err'])) {
                        // Upload files
                        
                        $imageUploaded = uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/applicantPhotos/');
                        $cvUploaded = uploadImage($data['cv']['tmp_name'], $data['cv_name'], '/uploads/applicantCVs/');
                        
                        if(!$imageUploaded || !$cvUploaded) {
                            throw new Exception('File upload failed. Please try again.');
                        }
                        
                        if($this->homeModel->saveJobApplication($data, $role)){
                            flash('msg', 'Application submitted successfully', 'alert-success');
                            $this->view('home/v_success_application', $data);
                            return;
                        } else {
                            throw new Exception('Failed to save application to database');
                        }
                    }
                    else{
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
                        'birthday' => '',
                        'national_id' => '',
                        'gender' => '',
                        'address' => '',
                        'district' => '',
                        'city' => '',

                        'image' => '',
                        'image_name' => '',
                        'cv' => '',
                        'cv_name' => '',

                        'image_err' => '',
                        'name_err' => '',
                        'email_err' => '',
                        'phone_err' => '',
                        'cv_err' => '',
                        'birthday_err' => '',
                        'national_id_err' => '',
                        'gender_err' => '',
                        'address_err' => '',
                        'district_err' => '',
                        'city_err' => '',
                    ];

                    $this->view('home/v_job_application',$data);
                }
            }
        } catch (Exception $e) {
            $this->showError('Job application submission failed', $e);
        }
    }

    /**
     * Sanitize input data
     */
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

    /**
     * Display error page
     */
    private function showError($title, $exception) {
        $errorCode = method_exists($exception, 'getCode') ? $exception->getCode() : 500;
        if ($errorCode < 400 || $errorCode > 599) {
            $errorCode = 500;
        }
        
        $data = [
            'error_code' => $errorCode,
            'error_title' => $title,
            'error_message' => $exception->getMessage(),
            'error_file' => $exception->getFile(),
            'error_line' => $exception->getLine(),
            'error_trace' => DEBUG_MODE ? $exception->getTraceAsString() : '',
            'request_url' => $_SERVER['REQUEST_URI'] ?? '',
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? '',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Log the error
        error_log("Error: " . $title . " - " . $exception->getMessage() . 
                 " in " . $exception->getFile() . " on line " . $exception->getLine());
        
        // Set HTTP response code
        http_response_code($errorCode);
        
        // Load error view
        $this->view('components/v_error_page', $data);
    }
}