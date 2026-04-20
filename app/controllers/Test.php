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

                'username' => $this->sanitizeInput($_POST['username'] ?? ''),
                'contact_email' => $this->sanitizeInput($_POST['contact_email'] ?? ''),
                'pwd' => $this->sanitizeInput($_POST['pwd'] ?? ''),
                'age' => $this->sanitizeInput($_POST['age'] ?? ''),
                'dob' => $this->sanitizeInput($_POST['dob'] ?? ''),
                'nic' => $this->sanitizeInput($_POST['nic'] ?? ''),
                'subscribe' => isset($_POST['subscribe']) ? 1 : 0,
                'gender_identity' => $this->sanitizeInput($_POST['gender_identity'] ?? ''),
                'favcolor' => $this->sanitizeInput($_POST['favcolor'] ?? ''),
                'volume' => $this->sanitizeInput($_POST['volume'] ?? ''),
                'user_id' => $this->sanitizeInput($_POST['user_id'] ?? ''),
                'query' => $this->sanitizeInput($_POST['query'] ?? ''),
                'phone' => $this->sanitizeInput($_POST['phone'] ?? ''),
                'website' => $this->sanitizeInput($_POST['website'] ?? ''),
                'meeting_time' => $this->sanitizeInput($_POST['meeting_time'] ?? ''),
                'message' => $this->sanitizeInput($_POST['message'] ?? ''),
                'country' => $this->sanitizeInput($_POST['country'] ?? ''),

                'image' => $_FILES['image'],
                'image_name' => time(). '_' . $_FILES['image']['name'],
                'upload' => $_FILES['upload'] ?? null,
                'upload_name' => (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) ? time(). '_' . $_FILES['upload']['name'] : '',

                'image_err' => '',
                'name_err' => '',
                'email_err' => '',
                'gender_err' => '',
                'description_err' => '',

                'username_err' => '',
                'contact_email_err' => '',
                'pwd_err' => '',
                'age_err' => '',
                'dob_err' => '',
                'nic_err' => '',
                'subscribe_err' => '',
                'gender_identity_err' => '',
                'favcolor_err' => '',
                'volume_err' => '',
                'upload_err' => '',
                'user_id_err' => '',
                'query_err' => '',
                'phone_err' => '',
                'website_err' => '',
                'meeting_time_err' => '',
                'message_err' => '',
                'country_err' => ''
            ];

            if(empty($data['name'])){
                $data['name_err'] = 'Please enter your name';
            } elseif(!preg_match('/^[A-Za-z](?:[A-Za-z0-9]*[A-Za-z])?$/', $data['name'])){
                $data['name_err'] = 'Name must start and end with a letter; middle characters can be letters or numbers';
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
            } elseif(mb_strlen($data['description']) !== 100){
                $data['description_err'] = 'Description must be exactly 100 characters';
            }

            if(empty($data['username'])){
                $data['username_err'] = 'Please enter a username';
            }

            if(empty($data['contact_email'])){
                $data['contact_email_err'] = 'Please enter email';
            } elseif(!filter_var($data['contact_email'], FILTER_VALIDATE_EMAIL)){
                $data['contact_email_err'] = 'Please enter a valid email address';
            }

            if(empty($data['pwd'])){
                $data['pwd_err'] = 'Please enter a password';
            } elseif(strlen($data['pwd']) < 6){
                $data['pwd_err'] = 'Password must be at least 6 characters';
            } elseif(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/', $data['pwd'])){
                $data['pwd_err'] = 'Password must include uppercase, lowercase, number, and symbol';
            }

            if($data['age'] === ''){
                $data['age_err'] = 'Please enter age';
            } elseif(!is_numeric($data['age']) || (int)$data['age'] < 0 || (int)$data['age'] > 120){
                $data['age_err'] = 'Please enter a valid age between 0 and 120';
            }

            if(empty($data['dob'])){
                $data['dob_err'] = 'Please select date of birth';
            } else {
                $dobDate = DateTime::createFromFormat('Y-m-d', $data['dob']);
                $today = new DateTime();
                $minAllowedDob = (clone $today)->modify('-18 years');

                if(!$dobDate || $dobDate->format('Y-m-d') !== $data['dob']){
                    $data['dob_err'] = 'Please enter a valid date of birth';
                } elseif($dobDate > $minAllowedDob){
                    $data['dob_err'] = 'You must be at least 18 years old';
                }
            }

            if(empty($data['nic'])){
                $data['nic_err'] = 'Please enter NIC number';
            } elseif(!preg_match('/^\d{12}$/', $data['nic'])){
                $data['nic_err'] = 'NIC number must be exactly 12 digits';
            } else {
                $nicGenderCode = (int) substr($data['nic'], 4, 3);
                $derivedGender = $nicGenderCode < 500 ? 'male' : 'female';

                if(!empty($data['gender_identity']) && $data['gender_identity'] !== $derivedGender){
                    $data['gender_identity_err'] = 'Gender does not match NIC number';
                }

                $data['gender_identity'] = $derivedGender;
            }

            if(empty($data['gender_identity'])){
                $data['gender_identity_err'] = 'Please select a gender';
            }

            if(empty($data['favcolor'])){
                $data['favcolor_err'] = 'Please choose a color';
            }

            if($data['volume'] === ''){
                $data['volume_err'] = 'Please select volume';
            } elseif(!is_numeric($data['volume']) || (int)$data['volume'] < 0 || (int)$data['volume'] > 100){
                $data['volume_err'] = 'Please select a valid volume between 0 and 100';
            }

            if(empty($data['upload']) || empty($data['upload']['name'])){
                $data['upload_err'] = 'Please upload a file';
            } elseif($data['upload']['size'] > 0){
                $uploadExtension = strtolower(pathinfo($data['upload']['name'], PATHINFO_EXTENSION));
                $allowedUploadExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

                if(!in_array($uploadExtension, $allowedUploadExtensions)){
                    $data['upload_err'] = 'Only JPG, PNG, and PDF files are allowed';
                }
            }

            if(empty($data['user_id'])){
                $data['user_id_err'] = 'Invalid user id';
            }

            if(empty($data['query'])){
                $data['query_err'] = 'Please enter a search value';
            }

            if(empty($data['phone'])){
                $data['phone_err'] = 'Please enter phone number';
            } elseif(!preg_match('/^07\d{8}$/', $data['phone'])){
                $data['phone_err'] = 'Phone number must be 10 digits and start with 07';
            }

            // if(empty($data['phone'])){
            //     $data['phone_err'] = 'Please enter phone number';
            // } elseif(!preg_match('/^[0-9+\-\s()]{7,20}$/', $data['phone'])){
            //     $data['phone_err'] = 'Please enter a valid phone number';
            // }

            if(empty($data['website'])){
                $data['website_err'] = 'Please enter website URL';
            } elseif(!filter_var($data['website'], FILTER_VALIDATE_URL)){
                $data['website_err'] = 'Please enter a valid URL';
            }

            if(empty($data['meeting_time'])){
                $data['meeting_time_err'] = 'Please select meeting time';
            }

            if(empty($data['message'])){
                $data['message_err'] = 'Please enter a message';
            }

            if(empty($data['country'])){
                $data['country_err'] = 'Please select a country';
            } elseif(!in_array($data['country'], ['us', 'ca', 'uk'])){
                $data['country_err'] = 'Invalid country selected';
            }

                if(empty($data['image']['name'])){
                    $data['image_err'] = 'Please upload an image';
                } elseif($data['image']['size'] > 0){
                    $fileExtension = strtolower(pathinfo($data['image']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
                    $detectedMimeType = mime_content_type($data['image']['tmp_name']);

                    if(!in_array($fileExtension, $allowedExtensions) || !in_array($detectedMimeType, $allowedMimeTypes)){
                        $data['image_err'] = 'Only JPG, PNG, and WEBP images are allowed';
                    } elseif(uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/clientLogos/')){
                        // Image uploaded successfully
                    } else {
                        $data['image_err'] = 'Failed to upload image';
                    }
                }

            if(
                empty($data['name_err']) &&
                empty($data['email_err']) &&
                empty($data['gender_err']) &&
                empty($data['description_err']) &&
                empty($data['image_err']) &&
                empty($data['username_err']) &&
                empty($data['contact_email_err']) &&
                empty($data['pwd_err']) &&
                empty($data['age_err']) &&
                empty($data['dob_err']) &&
                empty($data['nic_err']) &&
                empty($data['subscribe_err']) &&
                empty($data['gender_identity_err']) &&
                empty($data['favcolor_err']) &&
                empty($data['volume_err']) &&
                empty($data['upload_err']) &&
                empty($data['user_id_err']) &&
                empty($data['query_err']) &&
                empty($data['phone_err']) &&
                empty($data['website_err']) &&
                empty($data['meeting_time_err']) &&
                empty($data['message_err']) &&
                empty($data['country_err'])
            ){

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

                'username' => '',
                'contact_email' => '',
                'pwd' => '',
                'age' => '',
                'dob' => '',
                'nic' => '',
                'subscribe' => 0,
                'gender_identity' => '',
                'favcolor' => '#000000',
                'volume' => '50',
                'upload' => '',
                'upload_name' => '',
                'user_id' => '12345',
                'query' => '',
                'phone' => '',
                'website' => '',
                'meeting_time' => '',
                'message' => '',
                'country' => '',

                'image' => '',
                'image_name' => '',

                'image_err' => '',
                'name_err' => '',
                'email_err' => '',
                'gender_err' => '',
                'description_err' => '',

                'username_err' => '',
                'contact_email_err' => '',
                'pwd_err' => '',
                'age_err' => '',
                'dob_err' => '',
                'nic_err' => '',
                'subscribe_err' => '',
                'gender_identity_err' => '',
                'favcolor_err' => '',
                'volume_err' => '',
                'upload_err' => '',
                'user_id_err' => '',
                'query_err' => '',
                'phone_err' => '',
                'website_err' => '',
                'meeting_time_err' => '',
                'message_err' => '',
                'country_err' => ''
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