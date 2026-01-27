<?php
class Admin extends Controller {
    private $adminModel;
    private $userModel;
    private $homeModel;
    private $chartModel;
    

    public function __construct() {
        requireAuth('admin');
        $this->adminModel = $this->model('M_admin');
        $this->userModel = $this->model('M_users');
        $this->homeModel = $this->model('M_home');
        
        // Load route helper
        require_once APP_ROOT . '/helpers/route_helper.php';
        
        // Try to load chart model
        $chartModel = $this->model('ChartDataModel');
        if ($chartModel) {
            $this->chartModel = $chartModel;
        }
    }

    public function index() {
        redirect('admin/dashboard');
    }

    public function dashboard() {
        $pendingLeaves = $this->adminModel->getPendingLeaveRequests();
        $leaveStats = $this->adminModel->getLeaveRequestStats();
        $recentActivities = $this->adminModel->getRecentActivities(100);
        
        // Initialize chart data
        $userRoleChart = [
            'labels' => [],
            'data' => [],
            'colors' => []
        ];
        
        // Only try to get chart data if model exists
        if ($this->chartModel) {
            $userRoleChart = $this->chartModel->getUserRolePieChart();
        }
    
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Admin Dashboard',
            'pendingLeaves' => $pendingLeaves,
            'leaveStats' => $leaveStats,
            'recent_activities' => $recentActivities,
            'userRoleChart' => $userRoleChart
        ];
        
        $this->view('admin/dashboard/v_dashboard', $data);
    }

    public function messages(){
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Messages'
        ];
        $this->view('admin/dashboard/v_messages', $data);
    }

    public function pendings(){
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Pending Leave Requests'
        ];
        $this->view('admin/dashboard/v_pendings', $data);
    }

    public function assign(){
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Officer Assignment'
        ];
        $this->view('admin/dashboard/v_assign', $data);
    }

    public function alerts(){
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Send Alerts'
        ];
        $this->view('admin/dashboard/v_alerts', $data);
    }

// ======================================================================== //
// =======================      Admin Officers       ====================== //
// ======================================================================== //

    public function officers() {
        $officers = $this->adminModel->getAllPO();
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Manage Officers',
            'officer' => $officers
    ];
        $this->view('admin/officers/v_officers', $data);
    }
    public function mobileriders() {
        $officers = $this->adminModel->getAllMR();
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Manage Officers',
            'officer' => $officers
    ];
        $this->view('admin/officers/v_mobileriders', $data);
    }
    public function caretakers() {
        $officers = $this->adminModel->getAllCT();
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Manage Officers',
            'officer' => $officers
    ];
        $this->view('admin/officers/v_caretakers', $data);
    }

    public function officer_profile($id){
        $officer = $this->adminModel->getPOById($id);
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Officer Profile',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_officer_profile', $data);
    }
    public function mobile_rider_profile($id){
        $officer = $this->adminModel->getMRById($id);
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Officer Profile',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_mobilerider_profile', $data);
    }
    public function care_taker_profile($id){
        $officer = $this->adminModel->getCTById($id);
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Officer Profile',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_caretaker_profile', $data);
    }

    public function porecruitment() {
        $exists = $this->adminModel->getJobApplication('po');
        if($exists) {
            $this->edit_job_application($exists,'po');
        }
        else{
            $this->add_job_application('po');
        }
    }
    public function mrrecruitment() {
        $exists = $this->adminModel->getJobApplication('mr');
        if($exists) {
            $this->edit_job_application($exists,'mr');
        }
        else{
            $this->add_job_application('mr');
        }
    }
    public function ctrecruitment() {
        $exists = $this->adminModel->getJobApplication('ct');
        if($exists) {
            $this->edit_job_application($exists,'ct');
        }
        else{
            $this->add_job_application('ct');
        }
    }

    public function add_job_application($role) {

        if($role == 'po') $role_name = "Premise Officer";
        elseif($role == 'mr') $role_name = "Mobile Rider";
        elseif($role == 'ct') $role_name = "Care Taker";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => 'Officers',
                'pageTitle' => 'Add Job Application',

                'description' => $this->sanitizeInput($_POST['description'] ?? ''),
                'qualifications' => $this->sanitizeInput($_POST['qualifications'] ?? ''),
                'due_date' => $this->sanitizeInput($_POST['due_date'] ?? ''),
                'completed' => ''

            ];
            $this->adminModel->insertJobApplication($data, $role);
            flash('msg', 'Job Application Added Successfully', 'alert-success');

            
            // Validate form
            if(!empty($data['description']) && !empty($data['qualifications']) && !empty($data['due_date'])) {
                $data['completed'] = 'true';
                
                $title = "Job Application Created";
                $description = "New " . $role_name . " job application created with due date: " . $data['due_date'];
                $type = "update";
                $this->adminModel->insertRecentActivity($title, $description,$type);


                $this->view('admin/officers/v_'.$role.'_recruitment', $data);
            }
            else{
                $data['completed'] = 'false';

                $title = "Job Application Created";
                $description = "New " . $role_name . " job application created with due date: " . $data['due_date'] ."(Not Completed)";
                $type = "alert";
                $this->adminModel->insertRecentActivity($title, $description,$type);

                $this->view('admin/officers/v_'.$role.'_recruitment', $data);

            }
            
        }
        else {
            $data = [
                'title' => 'Officers',
                'pageTitle' => 'Add Job Application',

                'description' => '',
                'qualifications' => '',
                'due_date' => '',
                

            ];
            $this->view('admin/officers/v_'.$role.'_recruitment', $data);

        }

        
    }
    public function edit_job_application($exists,$role) {
        if($role == 'po') $role_name = "Premise Officer";
        elseif($role == 'mr') $role_name = "Mobile Rider";
        elseif($role == 'ct') $role_name = "Care Taker";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => 'Officers',
                'pageTitle' => 'Add Job Application',

                'description' => $this->sanitizeInput($_POST['description'] ?? ''),
                'qualifications' => $this->sanitizeInput($_POST['qualifications'] ?? ''),
                'due_date' => $this->sanitizeInput($_POST['due_date'] ?? ''),
                'completed' => '',
                'status' => $exists->status

            ];

            // Validate form
            if(!empty($data['description']) && !empty($data['qualifications']) && !empty($data['due_date'])) {
                $data['completed'] = 'true';
                
                // Check if due date has passed
                $dueDate = date('Y-m-d', strtotime($data['due_date']));
                $today = date('Y-m-d');
                if ($dueDate < $today) {
                    // If due date has passed, close the status
                    $this->adminModel->changeStatus($role, 'closed');
                }

                $title = "Job Application Updated";
                $description = "Updated " . $role_name . " job application created with due date: " . $data['due_date'];
                $type = "update";
                $this->adminModel->insertRecentActivity($title, $description,$type);

                flash('msg', 'Job Application Updated Successfully', 'alert-success');
                $this->view('admin/officers/v_'.$role.'_recruitment', $data);
            }
            else {
                
                $this->adminModel->changeStatus($role,'closed');
                $data['completed'] = 'false';

                $title = "Job Application Updated";
                $description = "Updated " . $role_name . " job application created with due date: " . $data['due_date'] ." (Not Completed)";
                $type = "alert";
                $this->adminModel->insertRecentActivity($title, $description,$type);

                flash('msg', 'Job Application Updated Successfully', 'alert-success');
                $this->view('admin/officers/v_'.$role.'_recruitment', $data);
            }
            $this->adminModel->editJobApplication($data, $role);
            
        }
        else {
            $data = [
                'title' => 'Officers',
                'pageTitle' => 'Add Job Application',

                'description' => $exists->description,
                'qualifications' =>  $exists->qualifications,
                'due_date' => $exists->due_date,
                'completed' => $exists->completed,
                'status' => $exists->status
                

            ];
            $this->view('admin/officers/v_'.$role.'_recruitment', $data);

        }
    }
    public function changeStatus($role,$status) {
        $this->adminModel->changeStatus($role,$status);
        $data = $this->adminModel->getJobApplication($role);
        
        // Add activity log
        $role_name = $this->getRoleName($role);
        $title = "Job Application Status Changed";
        $description = $role_name . " job application status changed to: " . $status;
        $type = $status == 'open' ? "message" : "leave";
        $this->adminModel->insertRecentActivity($title, $description, $type);
        
        flash('msg', 'Job Application Status Updated Successfully', 'alert-success');
        $this->view('admin/officers/v_'.$role.'_recruitment', $data);
    }
    
    private function getRoleName($role) {
        switch($role) {
            case 'po': return "Premise Officer";
            case 'mr': return "Mobile Rider";
            case 'ct': return "Care Taker";
            default: return "Officer";
        }
    }
    
    public function delete_job_application($role) {
        if($role == 'po') $role_name = "Premise Officer";
        elseif($role == 'mr') $role_name = "Mobile Rider";
        elseif($role == 'ct') $role_name = "Care Taker";

        $this->adminModel->deleteJobApplication($role);

        $title = "Job Application Removed";
        $description = "The " . $role_name . " job application was removed.";
        $type = "alert";
        $this->adminModel->insertRecentActivity($title, $description,$type);

        flash('msg', 'Job Application Deleted Successfully', 'alert-success');
        redirect('admin/'.$role.'recruitment');
    }


    public function pending_officer_applications($type) {
        if($type == 'po' || $type == 'ct' || $type == 'mr') {
            $officer = $this->homeModel->getPendingOfficerApplications($type);
        } else {
            $officer = $this->homeModel->getAllPendingOfficerApplications();
        }
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Pending Officer Applications',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_pending_officer_applications', $data);
    }
    public function accepted_officer_applications($type) {
       
        if($type == 'po' || $type == 'ct' || $type == 'mr') {
            $officer = $this->homeModel->getApprovedOfficerApplications($type);
        } else {
            $officer = $this->homeModel->getAllApprovedOfficerApplications();
        }
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Approved Officer Applications',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_accepted_officer_applications', $data);
    }
    public function rejected_officer_applications($type) {
        if($type == 'po' || $type == 'ct' || $type == 'mr') {
            $officer = $this->homeModel->getRejectedOfficerApplications($type);
        } else {
            $officer = $this->homeModel->getAllRejectedOfficerApplications();
        }
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Rejected Officer Applications',
            'officer' => $officer
    ];
        $this->view('admin/officers/v_rejected_officer_applications', $data);
    }
    public function accept_officer_applications($id,$role) {
        if($role == 'po') $role_name = "Premise Officer";
        elseif($role == 'mr') $role_name = "Mobile Rider";
        elseif($role == 'ct') $role_name = "Care Taker";
        
        // Get the logged-in admin ID (you need to adjust this based on your auth system)
        $adminId = $_SESSION['user_id'] ?? 1; // Default to 1 if session not set
        
        if ($this->adminModel->acceptOfficerApplication($id, $adminId, $role)) {
            // Add activity log
            $title = "Officer Application Accepted";
            $description = $role_name . " application #" . $id . " was approved";
            $type = "registration";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            flash('msg', 'Officer application accepted successfully', 'alert-success');
            redirect('admin/pending_officer_applications/all');
        } else {
            flash('msg', 'Failed to accept officer application', 'alert-danger');
            redirect('admin/pending_officer_applications/all');
        }
    }
    public function reject_officer_applications($id) {
        if ($this->adminModel->rejectOfficerApplication($id)) {
            // Add activity log
            $title = "Officer Application Rejected";
            $description = "Officer application #" . $id . " was rejected";
            $type = "incident";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            flash('msg', 'Officer application rejected successfully', 'alert-success');
            redirect('admin/pending_officer_applications/all');
        } else {
            flash('officer_message', 'Failed to reject officer application', 'alert-danger');
            redirect('admin/pending_officer_applications/all');
        }
    }
    public function deleteOfficerApplication($id){
        if ($this->adminModel->deleteOfficerApplication($id)) {
            // Add activity log
            $title = "Officer Application Deleted";
            $description = "Officer application #" . $id . " was permanently deleted";
            $type = "alert";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            flash('msg', 'Officer application deleted successfully', 'alert-success');
            redirect('admin/rejected_officer_applications/all');
        } else {
            flash('officer_message', 'Failed to reject officer application', 'alert-danger');
            redirect('admin/rejected_officer_applications/all');
        }
    }
// ======================================================================== //
// =======================      Admin Clients       ====================== //
// ======================================================================== //

    public function clients() {
        // Get pending service requests count for notification badge
        $requestStats = $this->adminModel->getServiceRequestStats();

        $stats = $this->adminModel->getClientStatistics();
        $pendingCount = $requestStats->pending ?? 0;

        $clients = $this->adminModel->getAllClients();
        
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Manage Clients',
            'pendingRequestsCount' => $pendingCount,
            'clients' => $clients,
            'stats' => $stats
        ];
        $this->view('admin/clients/v_clients', $data);  
    }

    public function addclients(){
        $clients = $this->homeModel->getPendingRequest();
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Clients',
            'clients' => $clients
        ];

        $this->view('admin/clients/v_requests-pending', $data);
    }
    public function acceptClient($clientId) {
    // Get the logged-in admin ID (you need to adjust this based on your auth system)
    $adminId = $_SESSION['user_id'] ?? 1; // Default to 1 if session not set
    
    if ($this->adminModel->acceptClient($clientId, $adminId)) {
        // Add activity log
        $title = "Client Accepted";
        $description = "Client #" . $clientId . " registration was approved";
        $type = "updregistrationate";
        $this->adminModel->insertRecentActivity($title, $description, $type);
        
        flash('msg', 'Client accepted successfully', 'alert-success');
        redirect('admin/addclients');
    } else {
        flash('client_message', 'Failed to accept client', 'alert-danger');
        redirect('admin/addclients');
    }
}
    public function rejectClient($clientId){
        if($this->adminModel->rejectClient($clientId)){
            // Add activity log
            $title = "Client Rejected";
            $description = "Client #" . $clientId . " registration was rejected";
            $type = "incident";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            flash('msg', 'Client rejected successfully', 'alert-success');
            redirect('admin/addclients');
        } else {
            flash('client_message', 'Failed to reject client', 'alert-danger');
            redirect('admin/addclients');
        }
    }
    public function deleterequest($clientId){
        $client =  $this->adminModel->getClientById($clientId); // NOT WORKING DELETE IMAGE FILE 🥲
        
        $imagePath = PUB_ROOT . '/uploads/clientLogos/' . $client->client_profile;
        deleteImage($imagePath);
        if($this->adminModel->deleteRequest($clientId)){
            // Add activity log
            $title = "Client Request Deleted";
            $description = "Client request #" . $clientId . " was permanently deleted";
            $type = "alert";
            $this->adminModel->insertRecentActivity($title, $description, $type);
            
            flash('msg', 'Client request deleted successfully', 'alert-success');
            redirect('admin/rejected');
        } else {
            flash('client_message', 'Failed to delete client request', 'alert-danger');
            redirect('admin/rejected');
        }
    }
    public function accepted(){
        $clients = $this->homeModel->getApprovedRequest();
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Clients',
            'clients' => $clients
        ];

        $this->view('admin/clients/v_requests-accepted', $data);
    }

    public function rejected(){
        $clients = $this->homeModel->getRejectedRequest();
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Clients',
            'clients' => $clients
        ];

        $this->view('admin/clients/v_requests-rejected', $data);
    }

    

     public function clientprofile($Id){
        $client = $this->adminModel->getClientById($Id);
        $sites = $this->adminModel->getSiteByClientId($Id);
        $data = [
            
            'title' => 'Clients',
            'pageTitle' => $client->name . ' Profile',
            'client' => $client
            ,'sites' => $sites
        ];
        $this->view('admin/clients/v_clientProfile', $data);
    }

    public function addsite($Id){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $data = [
                'client_id' => $Id, // Use the parameter from URL
                'title' => 'Clients',
                'pageTitle' => 'Add Site',

                'image' => $_FILES['image'],
                'image_name' => time(). '_' . $_FILES['image']['name'],

                'site_name' => $this->sanitizeInput($_POST['site_name'] ?? ''),
                'site_address' => $this->sanitizeInput($_POST['site_address'] ?? ''),
                'district' => $this->sanitizeInput($_POST['district'] ?? ''),
                'site_city' => $this->sanitizeInput($_POST['city'] ?? ''),
                'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
                'latitude' => $this->sanitizeInput($_POST['latitude'] ?? ''),
                'longitude' => $this->sanitizeInput($_POST['longitude'] ?? ''),

                'image_err' => '',
                'site_name_err' => '',
                'site_address_err' => '',
                'district_err' => '',
                'site_city_err' => '',
                'phone_number_err' => '',
            ];

            // Validate form
            if(empty($data['image']['name'])){
                $data['image_err'] = 'Please upload an image';
            } elseif($data['image']['size'] > 0){
                if(uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/siteImages/')){
                    // Image uploaded successfully
                } else {
                    $data['image_err'] = 'Failed to upload image';
                }
            }

            if(empty($data['site_name'])){
                $data['site_name_err'] = 'Please enter site name';
            }

            if(empty($data['site_address'])){
                $data['site_address_err'] = 'Please enter site address';
            }

            if(empty($data['district'])){
                $data['district_err'] = 'Please enter district';
            }

            if(empty($data['site_city'])){
                $data['site_city_err'] = 'Please enter site city';
            }

            if(empty($data['phone_number'])){
                $data['phone_number_err'] = 'Please enter phone number';
            } elseif(!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])){
                $data['phone_number_err'] = 'Please enter a valid phone number (10-15 digits)';
            }

            // Make sure there are no errors
            if(empty($data['image_err']) && 
            empty($data['site_name_err']) && 
            empty($data['site_address_err']) && 
            empty($data['district_err']) && 
            empty($data['site_city_err']) && 
            empty($data['phone_number_err'])){

                // Insert site and get the new site ID
            $siteId = $this->adminModel->addSite($data);
                
                if($siteId){
                    // Add activity log
                    $title = "New Site Added";
                    $description = "Site '" . $data['site_name'] . "' added for client ID: " . $Id;
                    $type = "shift";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    
                    flash('msg', 'Site added successfully', 'alert-success');
                    redirect('admin/viewsites/'.$siteId); // Redirect properly
                } else {
                    flash('msg', 'Failed to add site', 'alert-danger');
                    $this->view('admin/clients/v_addSite',$data);
                }
            } else {
                $this->view('admin/clients/v_addSite',$data);
            }

        } else {
            $data = [
                'client_id' => $Id, // Add client_id here too
                'title' => 'Clients',
                'pageTitle' => 'Add Site',

                'image' => '', 
                'image_name' => '',

                'site_name' => '',
                'site_address' => '',
                'district' => '',
                'site_city' => '',
                'phone_number' => '',
                'latitude' => '',
                'longitude' => '',

                'image_err' => '',
                'site_name_err' => '',
                'site_address_err' => '',
                'district_err' => '',
                'site_city_err' => '',
                'phone_number_err' => '',
            ];
            
            $this->view('admin/clients/v_addSite', $data);
        }
    }

    public function viewsites($site_id){
        $site = $this->adminModel->getSiteById($site_id);
        $clients = $this->adminModel->getClientById($site->client_id);
        $assignedOfficers = $this->adminModel->getAssignedOfficers($site_id);
        
        $data = [
            'title' => 'Clients',
            'pageTitle' => $clients->name . ' - ' . $site->site_name,
            'site' => $site,
            'client' => $clients,
            'assigned_officers' => $assignedOfficers
        ];
        $this->view('admin/clients/v_viewsites', $data);
    }
public function editSite($site_id){
    // First get the existing site data
    $existingSite = $this->adminModel->getSiteById($site_id);
    
    if(!$existingSite) {
        flash('msg', 'Site not found', 'alert-danger');
        redirect('admin/clients');
        return;
    }
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $data = [
            'site_id' => $site_id, // Important: include site_id for update
            'client_id' => $existingSite->client_id, // Use existing client_id
            'title' => 'Clients',
            'pageTitle' => 'Edit Site',

            'image' => $_FILES['image'],
            'image_name' => time(). '_' . $_FILES['image']['name'],
            'current_image' => $existingSite->image, // Store current image

            'site_name' => $this->sanitizeInput($_POST['site_name'] ?? ''),
            'site_address' => $this->sanitizeInput($_POST['site_address'] ?? ''),
            'site_city' => $this->sanitizeInput($_POST['site_city'] ?? ''),
            'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
            'latitude' => $this->sanitizeInput($_POST['latitude'] ?? ''),
            'longitude' => $this->sanitizeInput($_POST['longitude'] ?? ''),

            'image_err' => '',
            'site_name_err' => '',
            'site_address_err' => '',
            'site_city_err' => '',
            'phone_number_err' => '',
        ];

        // Validation
        if(empty($data['site_name'])){
            $data['site_name_err'] = 'Please enter site name';
        }

        if(empty($data['site_address'])){
            $data['site_address_err'] = 'Please enter site address';
        }

        if(empty($data['site_city'])){
            $data['site_city_err'] = 'Please enter site city';
        }

        if(empty($data['phone_number'])){
            $data['phone_number_err'] = 'Please enter phone number';
        } elseif(!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])){
            $data['phone_number_err'] = 'Please enter a valid phone number (10-15 digits)';
        }

        // Handle image upload (optional for edit)
        // Check if new image was uploaded
        if($data['image']['size'] > 0){
            if(uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/siteImages/')){
                // Image uploaded successfully
                // Delete old image if it exists
                if(!empty($existingSite->image)) {
                    $oldImagePath = PUB_ROOT . '/uploads/siteImages/' . $existingSite->image;
                    if(file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
            } else {
                $data['image_err'] = 'Failed to upload image';
            }
        } else {
            // Keep the current image
            $data['image_name'] = $existingSite->image;
        }

        // Check for errors
        if(empty($data['site_name_err']) && 
           empty($data['site_address_err']) && 
           empty($data['site_city_err']) && 
           empty($data['phone_number_err']) &&
           empty($data['image_err'])) {

            // Update site - use editSite method in model
            if($this->adminModel->updateSite($data)){
                // Add activity log
                $title = "Site Updated";
                $description = "Site '" . $data['site_name'] . "' (ID: " . $site_id . ") was updated";
                $type = "update";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                flash('msg', 'Site updated successfully', 'alert-success');
                redirect('admin/viewsites/'.$site_id);
            } else {
                flash('msg', 'Failed to update site', 'alert-danger');
                $this->view('admin/clients/v_editSite', $data);
            }
        } else {
            $this->view('admin/clients/v_editSite', $data);
        }

    } else {
        // Load existing data into form - FIX FIELD NAMES HERE
        $data = [
            'site_id' => $site_id,
            'client_id' => $existingSite->client_id,
            'title' => 'Clients',
            'pageTitle' => 'Edit Site',

            'image' => '', 
            'image_name' => $existingSite->image,
            'current_image' => $existingSite->image,

            'site_name' => $existingSite->site_name,
            'site_address' => $existingSite->address, // Changed from address
            'site_city' => $existingSite->city,       // Changed from city
            'phone_number' => $existingSite->phone_number,
            'latitude' => $existingSite->latitude ?? '',
            'longitude' => $existingSite->longitude ?? '',

            'image_err' => '',
            'site_name_err' => '',
            'site_address_err' => '',
            'site_city_err' => '',
            'phone_number_err' => '',
        ];
        
        $this->view('admin/clients/v_editSite', $data);
    }
}
    public function deleteSite($siteId){
    
    // First get the site to get client_id before deleting
    $site = $this->adminModel->getSiteById($siteId);
    
    if(!$site) {
        flash('msg', 'Site not found', 'alert-danger');
        redirect('admin/sites');
        return;
    }
    
    $clientId = $site->client_id;
    $imagePath = PUB_ROOT . '/uploads/siteImages/' . $site->image;
    deleteImage($imagePath);
    
    if($this->adminModel->deleteSite($siteId)){
        // Add activity log
        $title = "Site Deleted";
        $description = "Site '" . $site->site_name . "' (ID: " . $siteId . ") was deleted";
        $type = "alert";
        $this->adminModel->insertRecentActivity($title, $description, $type);
        
        flash('msg', 'Site deleted successfully', 'alert-success');
        redirect('admin/clientprofile/' . $clientId);
    } else {
        flash('msg', 'Failed to delete site', 'alert-danger');
        redirect('admin/clientprofile/' . $clientId);
    }
}
    

    public function editassignment(){
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Edit Assignment'
        ];
        $this->view('admin/clients/v_editAssignment', $data);
    }

    public function adddutypoint(){
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Duty Point'
        ];
        $this->view('admin/clients/v_addDutyPoint', $data);
    }

    public function clientRequests() {
        // Handle approve/reject actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Handle package request approval
            if (isset($_POST['approve_package_request'])) {
                $requestId = $_POST['request_id'];
                $adminId = $_SESSION['user_id'];
                $notes = trim($_POST['admin_notes'] ?? '');
                
                // Approve the package request and create site
                $result = $this->adminModel->approvePackageRequest($requestId, $adminId, $notes);
                
                if ($result && is_numeric($result)) {
                    // Site created successfully, redirect to it
                    flash('request_success', 'Package request approved and site created successfully', 'alert-success');
                    redirect('admin/viewsites/' . $result);
                    exit();
                } elseif ($result) {
                    // Approved but site creation failed
                    flash('request_success', 'Package request approved', 'alert-success');
                    redirect('admin/clientRequests');
                    exit();
                } else {
                    // Approval failed
                    flash('request_error', 'Failed to approve package request', 'alert-danger');
                    redirect('admin/clientRequests');
                    exit();
                }
            }
            
            // Handle package request rejection
            if (isset($_POST['reject_package_request'])) {
                $requestId = $_POST['request_id'];
                $adminId = $_SESSION['user_id'];
                $reason = trim($_POST['rejection_reason'] ?? '');
                
                if ($this->adminModel->rejectPackageRequest($requestId, $adminId, $reason)) {
                    flash('request_success', 'Package request rejected', 'alert-success');
                } else {
                    flash('request_error', 'Failed to reject package request', 'alert-danger');
                }
                redirect('admin/clientRequests');
                exit();
            }
            
            // Handle old service request approval
            if (isset($_POST['approve_request'])) {
                $requestId = $_POST['request_id'];
                if ($this->adminModel->updateServiceRequestStatus($requestId, 'Approved')) {
                    $title = "Service Request Approved";
                    $description = "Service request #" . $requestId . " was approved";
                    $type = "update";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    flash('request_success', 'Service request approved successfully');
                } else {
                    flash('request_error', 'Failed to approve service request');
                }
                redirect('admin/clientRequests');
                exit();
            }
            
            // Handle old service request rejection
            if (isset($_POST['reject_request'])) {
                $requestId = $_POST['request_id'];
                if ($this->adminModel->updateServiceRequestStatus($requestId, 'Rejected')) {
                    $title = "Service Request Rejected";
                    $description = "Service request #" . $requestId . " was rejected";
                    $type = "alert";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    flash('request_success', 'Service request rejected');
                } else {
                    flash('request_error', 'Failed to reject service request');
                }
                redirect('admin/clientRequests');
                exit();
            }
        }

        // Get all service requests (old system)
        $serviceRequests = $this->adminModel->getAllServiceRequests();
        $requestStats = $this->adminModel->getServiceRequestStats();
        
        // Get all package requests (new system)
        $packageRequests = $this->adminModel->getAllPackageRequests();
        $packageStats = $this->adminModel->getPackageRequestStats();

        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Client Service Requests',
            'serviceRequests' => $serviceRequests,
            'requestStats' => $requestStats,
            'packageRequests' => $packageRequests,
            'packageStats' => $packageStats
        ];
        
        $this->view('admin/v_client_requests', $data);
    }

// ======================================================================== //
// =======================      Admin Scheduling       ====================== //
// ======================================================================== //

    public function scheduling() {
        $data = [
            'title' => 'Scheduling',
            'pageTitle' => 'Manage Scheduling'
        ];
        $this->view('admin/v_scheduling', $data);
    }

// ======================================================================== //
// =======================      Admin Routes       ====================== //
// ======================================================================== //

    public function routes() {
        $routes = $this->adminModel->getAllRoutes();
        $allSites = $this->adminModel->getAllSites();
        $allMobileRiders = $this->adminModel->getAllMR();
        
        // Filter unassigned sites
        $unassignedSites = [];
        foreach ($allSites as $site) {
            // Check if site is not assigned to any route
            $isAssigned = $this->adminModel->isSiteAssignedToRoute($site->id);
            if (!$isAssigned) {
                $unassignedSites[] = $site;
            }
        }
        
        // Add matching routes to each unassigned site
        $sitesWithMatches = getSitesWithMatchingRoutes($unassignedSites, $routes);
        
        // Calculate stats
        $totalRiders = count($allMobileRiders);
        $totalRoutes = count($routes);
        $totalSites = count($allSites);
        $unassignedSitesCount = count($unassignedSites);
        
        $data = [
            'title' => 'Routes',
            'pageTitle' => 'Manage Routings',
            'routes' => $routes,
            'sites' => $sitesWithMatches,
            'totalRiders' => $totalRiders,
            'totalRoutes' => $totalRoutes,
            'totalSites' => $totalSites,
            'unassignedSitesCount' => $unassignedSitesCount
        ];
        $this->view('admin/routes/v_routes', $data);
    }

    public function addroute() {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $data = [
                'route_name' => trim($_POST['route_name']),
                'description' => trim($_POST['description']),
                'location' => trim($_POST['location']),
            ];

            // Validation
            if(empty($data['route_name'])){
                $data['route_name_err'] = 'Please enter route name';
            }

            if(empty($data['route_name_err'])){
                $route_id = $this->adminModel->generateRouteId();
                $created_by = $_SESSION['user_id'];

                $routeData = [
                    'id' => $route_id,
                    'route_name' => $data['route_name'],
                    'description' => $data['description'],
                    'location' => $data['location'],
                    'status' => 'Active',
                    'created_by' => $created_by
                ];

                if($this->adminModel->insertRoute($routeData)){
                    // Add activity log
                    $title = "Route Created";
                    $description = "New route '" . $data['route_name'] . "' (ID: " . $route_id . ") was created";
                    $type = "success";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    
                    flash('msg', 'Route added successfully', 'alert-success');
                    redirect('admin/routes');
                } else {
                    flash('msg', 'Failed to add route', 'alert-danger');
                }
            } else {
                // Get sites for the form - but now no sites
                $this->view('admin/routes/v_addRoute', $data);
            }
        } else {
            $data = [
                'title' => 'Routes',
                'pageTitle' => 'Add Route',
                'route_name' => '',
                'description' => '',
                'location' => '',
                'route_name_err' => ''
            ];

            $this->view('admin/routes/v_addRoute', $data);
        }
    }

    public function viewroute($id) {
        $route = $this->adminModel->getRouteById($id);
        $routeSites = $this->adminModel->getRouteSites($id);
        
        if (!$route) {
            flash('msg', 'Route not found', 'alert-danger');
            redirect('admin/routes');
            return;
        }
        
        // Get creator information
        $creator = $this->adminModel->getUserByID($route->created_by);
        
        // Get available mobile riders (excluding already assigned ones)
        $availableMobileRiders = $this->adminModel->getAvailableMR($id);
        $assignedRider = $this->adminModel->getRouteRider($id);
        
        $data = [
            'title' => 'Routes',
            'pageTitle' => 'View Route: ' . htmlspecialchars($route->route_name),
            'route' => $route,
            'routeSites' => $routeSites,
            'creator' => $creator,
            'mobileRiders' => $availableMobileRiders,
            'assignedRider' => $assignedRider
        ];
        $this->view('admin/routes/v_viewRoute', $data);
    }

    public function updateRouteLocation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeId = trim($_POST['route_id']);
            $location = trim($_POST['location']);

            // Validate input
            if (empty($routeId) || empty($location)) {
                echo json_encode(['success' => false, 'message' => 'Route ID and location are required']);
                return;
            }

            // Validate JSON format
            $coordinates = json_decode($location, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'message' => 'Invalid location data format']);
                return;
            }

            // Validate coordinates array
            if (!is_array($coordinates) || count($coordinates) < 3) {
                echo json_encode(['success' => false, 'message' => 'Location must contain at least 3 coordinate points']);
                return;
            }

            // Update route location in database
            $result = $this->adminModel->updateRouteLocation($routeId, $location);

            if ($result) {
                // Get route name for activity log
                $route = $this->adminModel->getRouteById($routeId);
                $routeName = $route ? $route->route_name : 'Route #' . $routeId;
                
                // Add activity log
                $title = "Route Location Updated";
                $description = "Location area for route '" . $routeName . "' was updated";
                $type = "info";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                echo json_encode(['success' => true, 'message' => 'Route location updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update route location']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function deleteRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeId = trim($_POST['route_id']);

            // Validate input
            if (empty($routeId)) {
                echo json_encode(['success' => false, 'message' => 'Route ID is required']);
                return;
            }

            // Get route name before deletion for activity log
            $route = $this->adminModel->getRouteById($routeId);
            $routeName = $route ? $route->route_name : 'Route #' . $routeId;
            
            // Delete route
            $result = $this->adminModel->deleteRoute($routeId);

            if ($result) {
                // Add activity log
                $title = "Route Deleted";
                $description = "Route '" . $routeName . "' (ID: " . $routeId . ") was permanently deleted";
                $type = "alert";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                echo json_encode(['success' => true, 'message' => 'Route deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete route']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    public function assignSiteToRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $siteId = isset($input['site_id']) ? trim($input['site_id']) : '';
            $routeId = isset($input['route_id']) ? trim($input['route_id']) : '';

            // Validate input
            if (empty($siteId) || empty($routeId)) {
                echo json_encode(['success' => false, 'message' => 'Site ID and Route ID are required']);
                return;
            }

            // Assign site to route
            $result = $this->adminModel->assignSiteToRoute($siteId, $routeId);

            if ($result) {
                // Get route and site names for activity log
                $route = $this->adminModel->getRouteById($routeId);
                $site = $this->adminModel->getSiteById($siteId);
                $routeName = $route ? $route->route_name : 'Route #' . $routeId;
                $siteName = $site ? $site->site_name : 'Site #' . $siteId;
                
                // Add activity log
                $title = "Site Assigned to Route";
                $description = "Site '" . $siteName . "' was assigned to route '" . $routeName . "'";
                $type = "success";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                echo json_encode(['success' => true, 'message' => 'Site assigned to route successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Site is already assigned to a route or assignment failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    public function assignRiderToRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $riderId = isset($input['rider_id']) ? trim($input['rider_id']) : '';
            $routeId = isset($input['route_id']) ? trim($input['route_id']) : '';

            // Validate input
            if (empty($riderId) || empty($routeId)) {
                echo json_encode(['success' => false, 'message' => 'Rider ID and Route ID are required', 'debug' => ['rider_id' => $riderId, 'route_id' => $routeId]]);
                return;
            }

            // Get route and rider names for activity log
            $route = $this->adminModel->getRouteById($routeId);
            $rider = $this->adminModel->getUserByID($riderId);
            
            if (!$route) {
                echo json_encode(['success' => false, 'message' => 'Route not found', 'debug' => ['route_id' => $routeId]]);
                return;
            }
            
            if (!$rider) {
                echo json_encode(['success' => false, 'message' => 'Rider not found', 'debug' => ['rider_id' => $riderId]]);
                return;
            }
            
            $routeName = $route->route_name;
            $riderName = $rider->name;

            // Assign rider to route
            $result = $this->adminModel->assignRiderToRoute($riderId, $routeId);

            if ($result) {
                // Add activity log
                $title = "Mobile Rider Assigned to Route";
                $description = "Mobile rider '" . $riderName . "' was assigned to route '" . $routeName . "'";
                $type = "success";
                $this->adminModel->insertRecentActivity($title, $description, $type);
                
                echo json_encode(['success' => true, 'message' => 'Mobile rider assigned to route successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database update failed', 'debug' => ['rider_id' => $riderId, 'route_id' => $routeId]]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

// ======================================================================== //
// =======================      Admin Salary       ====================== //
// ======================================================================== //

    public function salary() {
        $data = [
            'title' => 'Salary',
            'pageTitle' => 'Manage Salary'];
        $this->view('admin/v_salary', $data);
    }

// ======================================================================== //
// =======================      Admin client payments       ================ //
// ======================================================================== //

    public function clients_payments() {
        $data = [
            'title' => 'Salary',
            'pageTitle' => 'Clients Payments'];
        $this->view('admin/clients_payments/v_clients_payments', $data);
    }


// ======================================================================== //
// =======================      Admin officer leave requests      ================ //
// ======================================================================== //
    // View leave request details
    public function viewLeaveRequest($id) {
    $leaveRequest = $this->adminModel->getLeaveRequestById($id);
    
    if (!$leaveRequest) {
        flash('leave_error', 'Leave request not found');
        redirect('admin/dashboard');
    }
    
    $data = [
        'title' => 'Leave Request Details',
        'leaveRequest' => $leaveRequest
    ];
    $this->view('admin/v_leave_details', $data);
}

// Approve leave request
public function approveLeave($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $admin_id = $_SESSION['user_id'];
        
        if ($this->adminModel->approveLeaveRequest($id, $admin_id)) {
            flash('leave_success', 'Leave request approved successfully');
        } else {
            flash('leave_error', 'Failed to approve leave request');
        }
        
        redirect('admin/dashboard');
    }
}


// Reject leave request
public function rejectLeave($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $admin_id = $_SESSION['user_id'];
        $reason = trim($_POST['reason'] ?? '');
        
        if (empty($reason)) {
            flash('leave_error', 'Please provide a reason for rejection');
            redirect('admin/viewLeaveRequest/' . $id);
            return;
        }
        
        if ($this->adminModel->rejectLeaveRequest($id, $admin_id, $reason)) {
            flash('leave_success', 'Leave request rejected');
        } else {
            flash('leave_error', 'Failed to reject leave request');
        }
        
        redirect('admin/dashboard');
    }
}

// ======================================================================== //
// =======================      Admin Advertisements       ====================== //
// ======================================================================== //

    public function advertisements() {
        $advertisements = $this->adminModel->getAdvertisements();
        $data = [
            'title' => 'Advertisements',
            'pageTitle' => 'Manage Advertisements',
            'advertisements' => $advertisements
        ];
        $this->view('admin/v_advertisements', $data);
    }

    public function createAdvertisement() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Enable detailed error logging
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        
        // Start output buffering
        ob_start();
        
        header('Content-Type: application/json');
        
        try {
            error_log("=== CREATE ADVERTISEMENT START ===");
            error_log("POST data: " . print_r($_POST, true));
            error_log("FILES data: " . print_r($_FILES, true));
            error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'not set'));

            $uploadDir = PUB_ROOT . '/uploads/advertisements/';
            error_log("Upload directory: " . $uploadDir);
            
            // Create directory
            if (!is_dir($uploadDir)) {
                error_log("Creating directory...");
                $oldUmask = umask(0);
                $result = mkdir($uploadDir, 0755, true);
                umask($oldUmask);
                
                if (!$result) {
                    throw new Exception('Failed to create upload directory');
                }
                error_log("Directory created successfully");
            }

            // Check if directory is writable
            if (!is_writable($uploadDir)) {
                error_log("Directory not writable, attempting to chmod...");
                if (!chmod($uploadDir, 0755)) {
                    throw new Exception('Upload directory is not writable');
                }
            }
            error_log("Directory is writable");

            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                error_log("File upload detected");
                
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($_FILES['image']['name']));
                $targetPath = $uploadDir . $fileName;
                error_log("Target path: " . $targetPath);

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $error = error_get_last();
                    throw new Exception('Failed to upload image: ' . ($error['message'] ?? 'Unknown error'));
                }
                error_log("File uploaded successfully");
                $imagePath = 'uploads/advertisements/' . $fileName;
            } else {
                $errorMessage = 'No image uploaded';
                if (isset($_FILES['image']['error'])) {
                    $errorCodes = [
                        UPLOAD_ERR_INI_SIZE => 'File too large',
                        UPLOAD_ERR_FORM_SIZE => 'File too large (form)',
                        UPLOAD_ERR_PARTIAL => 'File upload incomplete',
                        UPLOAD_ERR_NO_FILE => 'No file uploaded',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing temp folder',
                        UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk',
                        UPLOAD_ERR_EXTENSION => 'File upload stopped'
                    ];
                    $errorMessage = $errorCodes[$_FILES['image']['error']] ?? 'Unknown upload error';
                }
                throw new Exception($errorMessage);
            }

            $roles = $_POST['roles'] ?? [];
            if (!is_array($roles)) {
                $roles = explode(',', $roles);
            }
            error_log("Roles: " . print_r($roles, true));

            if (empty($roles)) {
                throw new Exception('No roles selected');
            }

            $data = [
                'title' => $_POST['title'] ?? 'Advertisement',
                'image_path' => $imagePath,
                'target_roles' => implode(',', $roles),
                'created_by' => $_SESSION['user_id'] ?? 0,
                'status' => 'active'
            ];
            error_log("Data for insert: " . print_r($data, true));

            // Insert into database
            error_log("Calling createAdvertisement model method...");
            if ($this->adminModel->createAdvertisement($data)) {
                $lastId = $this->adminModel->getLastInsertId();
                error_log("Insert successful. Last ID: " . $lastId);
                
                $response = [
                    'status' => 'success',
                    'ad' => [
                        'id' => $lastId,
                        'title' => $data['title'],
                        'image_path' => URL_ROOT . '/' . $imagePath,
                        'target_roles' => $data['target_roles'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'status' => 'active'
                    ]
                ];
                
                error_log("Sending success response");
                ob_end_clean();
                echo json_encode($response);
                
            } else {
                throw new Exception('Database insert failed');
            }
            
        } catch (Exception $e) {
            $error = error_get_last();
            error_log("Exception caught: " . $e->getMessage());
            error_log("PHP error: " . ($error['message'] ?? 'No PHP error'));
            
            ob_end_clean();
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}
    public function updateAdvertisement($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            // Turn off error reporting
            error_reporting(0);

            // Get current advertisement to handle image deletion
            $currentAd = $this->adminModel->getAdvertisementById($id);
            $currentImagePath = $currentAd->image_path ?? '';
            
            $imagePath = $currentImagePath;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $uploadDir = PUB_ROOT . '/uploads/advertisements/';
                
                // Create directory with proper permissions - silent mode
                if (!is_dir($uploadDir)) {
                    $oldUmask = umask(0);
                    $result = @mkdir($uploadDir, 0755, true);
                    umask($oldUmask);
                    
                    if (!$result) {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
                        exit;
                    }
                }

                // Sanitize filename
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($_FILES['image']['name']));
                $targetPath = $uploadDir . $fileName;
                
                if (@move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/advertisements/' . $fileName;
                    
                    // Delete old image if it exists and is different from new one
                    if (!empty($currentImagePath) && $currentImagePath !== $imagePath) {
                        $oldImageFullPath = PUB_ROOT . '/' . $currentImagePath;
                        if (file_exists($oldImageFullPath)) {
                            @unlink($oldImageFullPath);
                        }
                    }
                }
            }

            $roles = $_POST['roles'] ?? '';
            if (is_array($roles)) $roles = implode(',', $roles);

            $data = [
                'title' => $_POST['title'] ?? 'Advertisement',
                'image_path' => $imagePath,
                'target_roles' => $roles,
                'status' => $_POST['status'] ?? 'active'
            ];

            if ($this->adminModel->updateAdvertisement($id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Advertisement updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update advertisement']);
            }
            exit;
        }
    }

    public function deleteAdvertisement($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            // Get advertisement first to delete the image file
            $advertisement = $this->adminModel->getAdvertisementById($id);
            
            if ($this->adminModel->deleteAdvertisement($id)) {
                // Delete the associated image file
                if ($advertisement && !empty($advertisement->image_path)) {
                    $imagePath = PUB_ROOT . '/' . $advertisement->image_path;
                    if (file_exists($imagePath)) {
                        @unlink($imagePath);
                    }
                }
                echo json_encode(['status' => 'success', 'message' => 'Advertisement deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete advertisement']);
            }
            exit;
        }
    }

    public function toggleAdvertisementStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            if ($this->adminModel->toggleAdvertisementStatus($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Advertisement status updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
            }
            exit;
        }
    }

    public function getAdvertisement($id) {
        header('Content-Type: application/json');
        $advertisement = $this->adminModel->getAdvertisementById($id);
        if ($advertisement) {
            echo json_encode([
                'status' => 'success',
                'advertisement' => $advertisement
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Advertisement not found']);
        }
        exit;
    }
// ======================================================================== //
// =======================      Admin incidents        ====================== //
// ======================================================================== //
    public function incidents() {
        // Fetch all incidents from database
        $incidents = $this->adminModel->getAllIncidents();
        $stats = $this->adminModel->getIncidentStats();
        
        $data = [
            'title' => 'Incidents',
            'pageTitle' => 'Incidents Dashboard',
            'incidents' => $incidents,
            'stats' => $stats
        ];
        $this->view('admin/incidents/v_incidents', $data);
    }
    
    public function viewIncident($id) {
        // Get incident details
        $incident = $this->adminModel->getIncidentById($id);
        
        if (!$incident) {
            flash('incident_message', 'Incident not found', 'alert-danger');
            redirect('admin/incidents');
            return;
        }
        
        // Get incident reviews
        $reviews = $this->adminModel->getIncidentReviews($id);
        
        $data = [
            'title' => 'Incidents',
            'pageTitle' => 'Incident Details',
            'incident' => $incident,
            'reviews' => $reviews
        ];
        
        $this->view('admin/incidents/v_view_incidents', $data);
    }
    
    public function addIncidentReview() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('admin/incidents');
            return;
        }
        
        // Get form data (PDO prepared statements handle SQL injection)
        $incidentId = trim($_POST['incident_id'] ?? '');
        $reviewTitle = trim($_POST['review_title'] ?? '');
        $reviewType = trim($_POST['review_type'] ?? '');
        $reviewDetails = trim($_POST['review_details'] ?? '');
        $userId = $_SESSION['user_id'] ?? null;
        $userName = $_SESSION['user_name'] ?? 'Admin';
        
        // Validate required fields
        if (empty($incidentId) || empty($reviewTitle) || empty($reviewDetails) || empty($userId)) {
            flash('incident_message', 'Please fill all required fields', 'alert-danger');
            redirect('admin/viewIncident/' . $incidentId);
            return;
        }
        
        // Add review using admin model
        $result = $this->adminModel->addIncidentReview(
            $incidentId,
            $userId,
            $userName,
            $reviewTitle,
            $reviewType,
            $reviewDetails
        );
        
        if ($result) {
            // Update incident status to In Progress
            $statusUpdated = $this->adminModel->updateIncidentStatus($incidentId, 'In Progress');
            
            if (!$statusUpdated) {
                error_log("Failed to update incident status for incident ID: " . $incidentId);
            }
            
            // Log activity
            $this->adminModel->insertRecentActivity(
                'Incident Review Added',
                'Admin added a review to incident #' . $incidentId,
                'Incident',
                $userId
            );
            
            flash('incident_message', 'Review added successfully and status updated to In Progress', 'alert-success');
        } else {
            flash('incident_message', 'Failed to add review. Please try again.', 'alert-danger');
        }
        
        redirect('admin/viewIncident/' . $incidentId);
    }
    
    public function resolveIncident() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('admin/incidents');
            return;
        }
        
        // Get form data
        $incidentId = trim($_POST['incident_id'] ?? '');
        $resolutionTitle = trim($_POST['resolution_title'] ?? '');
        $resolutionDetails = trim($_POST['resolution_details'] ?? '');
        $actionsTaken = trim($_POST['actions_taken'] ?? '');
        $userId = $_SESSION['user_id'] ?? null;
        $userName = $_SESSION['user_name'] ?? 'Admin';
        
        // Validate required fields
        if (empty($incidentId) || empty($resolutionTitle) || empty($resolutionDetails) || empty($userId)) {
            flash('incident_message', 'Please fill all required fields', 'alert-danger');
            redirect('admin/viewIncident/' . $incidentId);
            return;
        }
        
        // Add resolution as a review
        $reviewResult = $this->adminModel->addIncidentReview(
            $incidentId,
            $userId,
            $userName,
            $resolutionTitle,
            'Action',
            $resolutionDetails
        );
        
        // Update incident status to Resolved
        $statusUpdated = $this->adminModel->updateIncidentStatus($incidentId, 'Resolved');
        
        // Update action_taken field if provided
        if (!empty($actionsTaken)) {
            $this->adminModel->updateIncidentActionsTaken($incidentId, $actionsTaken);
        }
        
        if ($reviewResult && $statusUpdated) {
            // Log activity
            $this->adminModel->insertRecentActivity(
                'Incident Resolved',
                'Admin marked incident #' . $incidentId . ' as resolved',
                'Incident',
                $userId
            );
            
            flash('incident_message', 'Incident marked as resolved successfully', 'alert-success');
        } else {
            flash('incident_message', 'Failed to resolve incident. Please try again.', 'alert-danger');
        }
        
        redirect('admin/viewIncident/' . $incidentId);
    }

// ======================================================================== //
// =======================      Admin Reports        ====================== //
// ======================================================================== //
    public function reports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Admin Reports'
        ];
        $this->view('admin/reports/v_reports', $data);  
    }

    public function attendencereports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Atendence Reports'
        ];
        $this->view('admin/reports/v_attendence', $data);  
    }

    public function paymentsreports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Client Payment Reports'
        ];
        $this->view('admin/reports/v_clientpayments', $data);  
    }

    public function requestsreports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Client Requests Reports'
        ];
        $this->view('admin/reports/v_clientrequests', $data);  
    }

    public function incidentsreports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Incidents Reports'
        ];
        $this->view('admin/reports/v_incidents', $data);  
    }

    public function sitereports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Site Reports'
        ];
        $this->view('admin/reports/v_site', $data);  
    }

    public function performancereports() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Officer Performance Reports'
        ];
        $this->view('admin/reports/v_performance', $data);  
    }

// ======================================================================== //
// =======================      Admin Settings       ====================== //
// ======================================================================== //
    public function settings() {
        $data = [
            'title' => 'Settings',
            'pageTitle' => 'System Settings',
            'admins' => $this->adminModel->getAdmins()
        ];
        $this->view('admin/v_settings', $data);
    }

// ======================================================================== //
// =======================      Admin Panal       ====================== //
// ======================================================================== //
    public function admins() {
        if(isset($_SESSION['user_userID']) && $_SESSION['user_userID'] == 'ADMIN001'){
            $data = [
                'title' => 'Admins',
                'pageTitle' => 'Admin Panal',
                'admins' => $this->adminModel->getAllAdmins()
            ];
            $this->view('admin/admins/v_admins', $data);
        }
    }

    public function addadmin(){
        if(isset($_SESSION['user_userID']) && $_SESSION['user_userID'] == 'ADMIN001'){
        if(($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'){
            $data = [
                
                'title' => 'Admins',
                'pageTitle' => 'Create Admin',

                'image' => $_FILES['image'],
                'image_name' => time(). '_' . $_FILES['image']['name'],

                'name' => $this->sanitizeInput($_POST['name'] ?? ''),
                'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),

                'image_err' => '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
            ];

            // Validate form
            if(empty($data['image']['name'])){
                $data['image_err'] = 'Please upload an image';
            } elseif($data['image']['size'] > 0){
                if(uploadImage($data['image']['tmp_name'], $data['image_name'], '/uploads/image/')){
                    // Image uploaded successfully
                } else {
                    $data['image_err'] = 'Failed to upload image';
                }
            }

            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }

            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }

            if(empty($data['phone_number'])){
                $data['phone_number_err'] = 'Please enter phone number';
            } elseif(!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])){
                $data['phone_number_err'] = 'Please enter a valid phone number (10-15 digits)';
            }

            // Make sure there are no errors
            if(empty($data['image_err']) && 
            empty($data['name_err']) && 
            empty($data['email_err']) && 
            empty($data['phone_number_err'])){

                // Insert admin and get the new admin ID
                $adminId = $this->adminModel->addAdmin($data);
                
                if($adminId){
                    // Add activity log
                    $title = "New Admin Added";
                    $description = "Admin '" . $data['name'] . "' added";
                    $type = "shift";
                    $this->adminModel->insertRecentActivity($title, $description, $type);
                    
                    flash('msg', 'Admin added successfully', 'alert-success');
                    redirect('admin/admins/'.$adminId); // Redirect properly
                } else {
                    flash('msg', 'Failed to add admin', 'alert-danger');
                    $this->view('admin/admins/v_create_admin',$data);
                }
            } else {
                $this->view('admin/admins/v_create_admin',$data);
            }

        } else {
            $data = [
                
                'title' => 'Admins',
                'pageTitle' => 'Add Admin',

                'image' => '', 
                'image_name' => '',

                'name' => '',
                'email' => '',
                'phone_number' => '',

                'image_err' => '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
            ];
            
            $this->view('admin/admins/v_create_admin', $data);
        }
    }
    }


    public function profile() {
        $data = [
            'title' => 'Profile',
            'pageTitle' => 'Admin Profile',
            'admin' => $this->adminModel->getAdmin($_SESSION['user_userID'])
        ];
        $this->view('admin/admins/v_profile', $data);
    }
// ---------------------------------------For all--------------------------------------//

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

    // AJAX endpoint to get available officers
    public function getAvailableOfficers() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $filters = [
            'site_id' => $input['site_id'] ?? null,
            'city' => $input['city'] ?? '',
            'district' => $input['district'] ?? '',
            'district_filter' => $input['district_filter'] ?? 'same-district',
            'city_filter' => $input['city_filter'] ?? 'same-city',
            'availability' => $input['availability'] ?? 'available',
            'status' => $input['status'] ?? 'Active'
        ];

        $officers = $this->adminModel->getAvailableOfficers($filters);
        
        echo json_encode(['officers' => $officers]);
    }

    // AJAX endpoint to assign officer to site
    public function assignOfficerToSite() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $siteId = $input['site_id'] ?? null;
        $officerId = $input['officer_id'] ?? null;
        $shiftType = $input['shift_type'] ?? 'Full Time';
        $assignedBy = $_SESSION['user_id'] ?? null;

        if (!$siteId || !$officerId || !$assignedBy) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        $result = $this->adminModel->assignOfficerToSite($siteId, $officerId, $assignedBy, $shiftType);
        echo json_encode($result);
    }

    // AJAX endpoint to unassign officer from site
    public function unassignOfficerFromSite() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $assignmentId = $input['assignment_id'] ?? null;

        if (!$assignmentId) {
            echo json_encode(['success' => false, 'message' => 'Missing assignment ID']);
            return;
        }

        $result = $this->adminModel->unassignOfficerFromSite($assignmentId);
        echo json_encode($result);
    }
}



//For create folders 

//.  sudo chown -R daemon:daemon /Applications/XAMPP/xamppfiles/htdocs/RedForce/public/uploads/

//. # If the clientLogos directory doesn't exist yet, create it with proper permissions
//. sudo mkdir -p /Applications/XAMPP/xamppfiles/htdocs/RedForce/public/uploads/siteImages 
//. sudo chown -R daemon:daemon /Applications/XAMPP/xamppfiles/htdocs/RedForce/public/uploads/siteImages 
//. sudo chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/RedForce/public/uploads/siteImages 