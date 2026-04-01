<?php
class M_admin {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
// ======================================================================== //
// =======================      Admin Dashboard       ====================== //
// ======================================================================== //
    public function getRecentActivities($limit = 100) {
    $this->db->query('
        SELECT ra.*, u.name as user_name
        FROM recent_activities ra 
        LEFT JOIN Users u ON ra.user_id = u.id 
        WHERE u.role = "admin"
        ORDER BY ra.created_at DESC 
        LIMIT :limit
    ');
    $this->db->bind(':limit', $limit);
    return $this->db->resultSet();
}

public function insertRecentActivity($title, $description, $type, $userId = null) {
    // If userId is not provided, get it from session or set as null
    if ($userId === null) {
        // Get logged in user's ID from session
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    $this->db->query('
        INSERT INTO recent_activities (user_id, activity_titel, activity_details, activity_type) 
        VALUES (:user_id, :title, :description, :type)
    ');
    
    $this->db->bind(':user_id', $userId);
    $this->db->bind(':title', $title);
    $this->db->bind(':description', $description);
    $this->db->bind(':type', $type);
    
    return $this->db->execute();
}
// ======================================================================== //
// =======================      Admin Officers       ====================== //
// ======================================================================== //

public function getAllPO() {
        // Simple: Get all Users with role 'PO'
    $this->db->query("SELECT * FROM premise_officers_full_details; ORDER BY user_account_created DESC");
    return $this->db->resultSet();
}

public function getPOById($premise_officer_id) {
    $this->db->query("SELECT * FROM premise_officers_full_details WHERE premise_officer_id = :id OR user_id = :id");
    $this->db->bind(':id', $premise_officer_id);
    return $this->db->single();
}

public function getAllMR() {
        // Simple: Get all Users with role 'PO'
    $this->db->query("SELECT * FROM mobile_rider_full_details; ORDER BY user_account_created DESC");
    return $this->db->resultSet();
}

public function getAvailableMR($excludeRouteId = null) {
    // Get mobile riders who are not assigned to any route (or exclude current route)
    $query = "SELECT * FROM mobile_rider_full_details 
              WHERE user_id NOT IN (
                  SELECT assigned_rider_id FROM routes 
                  WHERE assigned_rider_id IS NOT NULL";
    
    if ($excludeRouteId !== null) {
        $query .= " AND id != :route_id";
    }
    
    $query .= ") ORDER BY user_account_created DESC";
    
    $this->db->query($query);
    
    if ($excludeRouteId !== null) {
        $this->db->bind(':route_id', $excludeRouteId);
    }
    
    return $this->db->resultSet();
}

public function getMRById($mobile_rider_id) {
    $this->db->query("SELECT * FROM mobile_rider_full_details WHERE mobile_rider_id = :id");
    $this->db->bind(':id', $mobile_rider_id);
    return $this->db->single();
}

public function getAllCT() {
        // Simple: Get all Users with role 'PO'
    $this->db->query("SELECT * FROM care_taker_full_details; ORDER BY user_account_created DESC");
    return $this->db->resultSet();
}

public function getCTById($care_taker_id) {
    $this->db->query("SELECT * FROM care_taker_full_details WHERE care_taker_id = :id");
    $this->db->bind(':id', $care_taker_id);
    return $this->db->single();
}


//Insert Job Application
public function insertJobApplication($data,$role) {
    $due_date = !empty($data['due_date']) ? $data['due_date'] : null; // If due_date is empty, set it to null
    $this->db->query("INSERT INTO jobApplication (role, completed,description, qualifications, due_date, created_at) 
                    VALUES (:role, :completed, :description, :qualifications, :due_date, NOW())");
    
    $this->db->bind(':role', $role);
    $this->db->bind(':completed', $data['completed']);
    $this->db->bind(':description', $data['description']);
    $this->db->bind(':qualifications', $data['qualifications']);
    $this->db->bind(':due_date', $data['due_date']);
    
    return $this->db->execute();
}

// Edit Job Application
public function editJobApplication($data,$role) {
    $due_date = !empty($data['due_date']) ? $data['due_date'] : null; // If due_date is empty, set it to null
    $this->db->query("UPDATE jobApplication SET role = :role, completed = :completed, description = :description, qualifications = :qualifications, due_date = :due_date, updated_at = NOW() WHERE role = :role");


    $this->db->bind(':role', $role);
    $this->db->bind(':completed', $data['completed']);
    $this->db->bind(':description', $data['description']);
    $this->db->bind(':qualifications', $data['qualifications']);
    $this->db->bind(':due_date', $data['due_date']);

    return $this->db->execute();
}

// Delete Job Application
public function deleteJobApplication($role) {
    $this->db->query("DELETE FROM jobApplication WHERE role = :role");
    $this->db->bind(':role', $role);
    return $this->db->execute();
}

// Get Job Applications by Role
public function getJobApplication($role) {
    $this->db->query("SELECT id, role, completed, description, qualifications, status, due_date FROM jobApplication WHERE role = :role");
    $this->db->bind(':role', $role);
    return $this->db->single(); 
}

// Change Status of Job Application
public function changeStatus($role, $status) {
    $this->db->query("UPDATE jobApplication SET status = :status WHERE role = :role");
    $this->db->bind(':role', $role);
    $this->db->bind(':status', $status);
    return $this->db->execute();
}

public function acceptOfficerApplication($id, $approved_by_user_id, $role) {
    // 1. Get the client request
    $this->db->query("SELECT * FROM submittedApplications WHERE id = :id");
    $this->db->bind(':id', $id);
    $request = $this->db->single();
    
    if (!$request) {
        return ['success' => false, 'message' => 'Application not found'];
    }
    
    // 2. Create user account
    $this->db->query("SELECT userID FROM Users WHERE email = :email");
    $this->db->bind(':email', $request->email);
    $existingUser = $this->db->single();

    if ($existingUser) {
        return [
            'success' => false,
            'message' => 'Email already exists in the system'
        ];
    }
    
    // Determine role prefix and name - use $role parameter, not $request->role
    $role_name = '';
    switch($role) {
        case 'po':
            $rolePrefix = 'PO';
            $role_name = 'Premise Officer';
            $role_name_data = 'Premise Officer';
            break;
        case 'ct':
            $rolePrefix = 'CT';
            $role_name = 'Care Taker';
            $role_name_data = 'caretaker';
            break;
        case 'mr':
            $rolePrefix = 'MR';
            $role_name = 'Mobile Rider';
            $role_name_data = 'Mobile Rider';
            break;
        default:
            $rolePrefix = 'OF';
            $role_name = 'Officer';
            $role_name_data = 'Officer';
    }
    
    // Get last userID for this role
    $this->db->query("SELECT userID FROM Users WHERE userID LIKE :prefix ORDER BY userID DESC LIMIT 1");
    $this->db->bind(':prefix', $rolePrefix . '%');
    $last = $this->db->single();
    
    if ($last) {
        // Extract number from PO001, CT001, etc.
        $number = (int) substr($last->userID, 2); // Remove prefix (2 characters)
        $nextNumber = $number + 1;
    } else {
        $nextNumber = 1; // First officer of this type
    }
    
    $userID = $rolePrefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    $tempPassword = '0000';
    
    // Insert into Users table
    $this->db->query("INSERT INTO Users (userID, name, email, phone_number, profile_image, password, role) 
                    VALUES (:userID, :name, :email, :phone, :profile_image, :password, :role)");
    $this->db->bind(':userID', $userID);
    $this->db->bind(':name', $request->name);
    $this->db->bind(':email', $request->email);
    $this->db->bind(':phone', $request->phone_number);
    $this->db->bind(':profile_image', $request->photo);
    $this->db->bind(':role', $role_name_data);
    $this->db->bind(':password', password_hash($tempPassword, PASSWORD_DEFAULT));
    
    if (!$this->db->execute()) {
        return ['success' => false, 'message' => 'Failed to create user account'];
    }
    
    // Get the newly inserted user's ID
    $this->db->query("SELECT id FROM Users WHERE userID = :userID");
    $this->db->bind(':userID', $userID);
    $userResult = $this->db->single();
    
    if (!$userResult) {
        return ['success' => false, 'message' => 'Failed to retrieve created user'];
    }
    
    $ID = $userResult->id;
    
    // 3. Add to appropriate officer table based on role
    $success = false;
    
    switch($role) {
        case 'po':
            $this->db->query("INSERT INTO premise_officers (officerID, userID, date_of_birth, NIC, gender, address, district, city, hire_date) 
                            VALUES (:officerID, :id, :date_of_birth, :NIC, :gender, :address, :district, :city, NOW())");
            $this->db->bind(':officerID', $userID);
            $this->db->bind(':id', $ID);
            $this->db->bind(':date_of_birth', $request->date_of_birth);
            $this->db->bind(':NIC', $request->NIC);
            $this->db->bind(':gender', $request->gender);
            $this->db->bind(':district', $request->district);
            $this->db->bind(':city', $request->city);
            $this->db->bind(':address', $request->address);
            $success = $this->db->execute();
            break;
            
        case 'ct':
            $this->db->query("INSERT INTO care_taker (caretakerID, userID, date_of_birth, NIC, gender, address, district, city, hire_date) 
                            VALUES (:officerID, :id, :date_of_birth, :NIC, :gender, :address, :district, :city, NOW())");
            $this->db->bind(':officerID', $userID);
            $this->db->bind(':id', $ID);
            $this->db->bind(':date_of_birth', $request->date_of_birth);
            $this->db->bind(':NIC', $request->NIC);
            $this->db->bind(':gender', $request->gender);
            $this->db->bind(':district', $request->district);
            $this->db->bind(':city', $request->city);
            $this->db->bind(':address', $request->address);
            $success = $this->db->execute();
            break;
            
        case 'mr':
            $this->db->query("INSERT INTO mobile_rider (riderID, userID, date_of_birth, NIC, gender, address, district, city, hire_date) 
                            VALUES (:officerID, :id, :date_of_birth, :NIC, :gender, :address, :district, :city, NOW())");
            $this->db->bind(':officerID', $userID);
            $this->db->bind(':id', $ID);
            $this->db->bind(':date_of_birth', $request->date_of_birth);
            $this->db->bind(':NIC', $request->NIC);
            $this->db->bind(':gender', $request->gender);
            $this->db->bind(':district', $request->district);
            $this->db->bind(':city', $request->city);
            $this->db->bind(':address', $request->address);
            $success = $this->db->execute();
            break;
            
        default:
            // Handle other officer types if needed
            $success = true;
    }
    
    if (!$success) {
        return [false];
    }
    
    // 4. Update submittedApplications table
    $this->db->query("UPDATE submittedApplications 
                    SET status = 'approved', 
                        approved_by = :approved_by, 
                        approved_at = NOW()
                    WHERE id = :id");
    $this->db->bind(':approved_by', $approved_by_user_id);
    $this->db->bind(':id', $id);
    
    if (!$this->db->execute()) {
        return ['success' => false, 'message' => 'Failed to update application status'];
    }
    
    return [
        'success' => true,
        'message' => 'Application approved successfully',
        'userID' => $userID,
        'tempPassword' => $tempPassword
    ];
}
    // Reject Client Request
    public function rejectOfficerApplication($id) {
        $this->db->query("UPDATE submittedApplications SET status = 'rejected' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Delete officer application
    public function deleteOfficerApplication($id) {
        $this->db->query("DELETE FROM submittedApplications WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
// ======================================================================== //
// =======================      Admin Clients       ====================== //
// ======================================================================== //
    public function getClientById($id) {
        // Get client with contact person name and profile image
        // $id parameter is Clients.id, not Users.id
        $this->db->query("
            SELECT 
                u.*, 
                c.contact_person_name,
                u.profile_image as client_profile
            FROM Clients c
            LEFT JOIN Users u ON c.user_id = u.id 
            WHERE c.id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get all Clients
    public function getAllClients() {
        // Simple: Get all Users with role 'client'
        $this->db->query("SELECT * FROM Users WHERE role = 'client' ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    // Get staff counts for a specific client
    public function getClientStaffCounts($clientId) {
        $this->db->query("
            SELECT 
                -- Count premise officers (excluding supervisors)
                (SELECT COUNT(DISTINCT osa.officer_id) 
                 FROM officer_site_assignments osa
                 INNER JOIN sites s ON osa.site_id = s.id
                 INNER JOIN premise_officers po ON osa.officer_id = po.userID
                 WHERE s.client_id = :client_id 
                 AND osa.status = 'Active'
                 AND (po.rank != 'Supervisor' OR po.rank IS NULL)
                ) as officers_count,
                
                -- Count supervisors
                (SELECT COUNT(DISTINCT osa.officer_id) 
                 FROM officer_site_assignments osa
                 INNER JOIN sites s ON osa.site_id = s.id
                 INNER JOIN premise_officers po ON osa.officer_id = po.userID
                 WHERE s.client_id = :client_id 
                 AND osa.status = 'Active'
                 AND po.rank = 'Supervisor'
                ) as supervisors_count,
                
                -- Count caretakers
                (SELECT COUNT(DISTINCT csa.caretaker_id) 
                 FROM caretaker_site_assignments csa
                 INNER JOIN sites s ON csa.site_id = s.id
                 WHERE s.client_id = :client_id 
                 AND csa.status = 'Active'
                ) as caretakers_count
        ");
        
        $this->db->bind(':client_id', $clientId);
        return $this->db->single();
    }
    
    // Get detailed client statistics by status
    public function getClientStatistics() {
        $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_count,
                SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended_count
            FROM Users 
            WHERE role = 'client'
        ");
        
        return $this->db->single();
    }

    // Usage example:
    // $stats = $userModel->getClientStatistics();
    // echo "Total: " . $stats->total;
    // echo "Active: " . $stats->active_count;
    // echo "Inactive: " . $stats->inactive_count;

    // Accept Client Request
    public function acceptClient($client_request_id, $approved_by_user_id) {
        // 1. Get the client request
        $this->db->query("SELECT * FROM client_requests WHERE id = :id");
        $this->db->bind(':id', $client_request_id);
        $request = $this->db->single();
        
        if (!$request) {
            return false;
        }
        
        // 2. Create user account
        // Generate CLIENT001, CLIENT002, etc.
        // Get last CLIENT number
        $this->db->query("SELECT userID FROM Users WHERE userID LIKE 'CLIENT%' ORDER BY userID DESC LIMIT 1");
        $last = $this->db->single();
        
        if ($last) {
            // Extract number from CLIENT001
            $number = (int) substr($last->userID, 6); // Remove "CLIENT" (6 characters)
            $nextNumber = $number + 1;
        } else {
            $nextNumber = 1; // First client
        }
        
        $userID = 'CLIENT' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $tempPassword = '0000'; // Simple temp password
        
        // Insert into Users table
        $this->db->query("INSERT INTO Users (userID, name, email, phone_number, profile_image, password, role) 
                        VALUES (:userID, :name, :email, :phone, :profile_image,:password, 'client')");
        $this->db->bind(':userID', $userID);
        $this->db->bind(':name', $request->company_name);
        $this->db->bind(':email', $request->email);
        $this->db->bind(':phone', $request->phone_number);
        $this->db->bind(':profile_image', $request->logo_path);
        $this->db->bind(':password', password_hash($tempPassword, PASSWORD_DEFAULT));
        
        if (!$this->db->execute()) {
            return false;
        }
        
        // Get the new user ID
        $new_user_id = $this->db->lastInsertId();
        
        // 3. Add to Clients table
        $this->db->query("INSERT INTO Clients (user_id, contact_person_name) 
                        VALUES (:user_id, :contact_name)");
        $this->db->bind(':user_id', $new_user_id);
        $this->db->bind(':contact_name', $request->contact_person_name);
        
        if (!$this->db->execute()) {
            return false;
        }
        
        // 4. Update client_requests table
        $this->db->query("UPDATE client_requests 
                        SET status = 'approved', 
                            approved_by = :approved_by, 
                            approved_at = NOW(),
                            client_id = :client_id
                        WHERE id = :id");
        $this->db->bind(':approved_by', $approved_by_user_id);
        $this->db->bind(':client_id', $new_user_id);
        $this->db->bind(':id', $client_request_id);
        
        // Execute the update first
        $success = $this->db->execute();
        if (!$success) {
            return ['success' => false];
        }

        // Now fetch the numeric user id (primary key) for the created user
        $this->db->query("SELECT id FROM Users WHERE userID = :userID");
        $this->db->bind(':userID', $userID);
        $CL_row = $this->db->single();
        $numericId = isset($CL_row->id) ? (int)$CL_row->id : null;

        return [
            'success' => true,
            'new_user_id' => $userID,
            'id' => $numericId,
            'email' => $request->email,
            'temp_password' => $tempPassword
        ];
    }

    // Reject Client Request
    public function rejectClient($client_id) {
        $this->db->query("UPDATE client_requests SET status = 'rejected' WHERE id = :client_id");
        $this->db->bind(':client_id', $client_id);
        return $this->db->execute();
    }
    // Delete Client Request
    public function deleteRequest($client_id) {
        $this->db->query("DELETE FROM client_requests WHERE id = :client_id");
        $this->db->bind(':client_id', $client_id);
        return $this->db->execute();
    }

    // Add Site
    public function addSite($data){
        $this->db->query("INSERT INTO sites (client_id, site_name, address, district, city, phone_number, image, latitude, longitude) 
                        VALUES (:client_id, :site_name, :site_address, :district, :site_city, :phone_number, :image_name, :latitude, :longitude)");
        
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':site_name', $data['site_name']);
        $this->db->bind(':site_address', $data['site_address']);
        $this->db->bind(':district', $data['district']);
        $this->db->bind(':site_city', $data['site_city']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':image_name', $data['image_name']);
        $this->db->bind(':latitude', !empty($data['latitude']) ? $data['latitude'] : null);
        $this->db->bind(':longitude', !empty($data['longitude']) ? $data['longitude'] : null);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId(); // Return the new site ID
        }
        return false;

    }
    public function deleteSite($siteId){
        $this->db->query("DELETE FROM sites WHERE id = :site_id");
        $this->db->bind(':site_id', $siteId);
        return $this->db->execute();
    }
    public function updateSite($data){
        $this->db->query("UPDATE sites SET site_name = :site_name, address = :site_address, district = :district, city = :site_city, phone_number = :phone_number, image = :image_name, latitude = :latitude, longitude = :longitude WHERE id = :site_id");
        $this->db->bind(':site_name', $data['site_name']);
        $this->db->bind(':site_address', $data['site_address']);
        $this->db->bind(':district', $data['district']);
        $this->db->bind(':site_city', $data['site_city']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':image_name', $data['image_name']);
        $this->db->bind(':latitude', !empty($data['latitude']) ? $data['latitude'] : null);
        $this->db->bind(':longitude', !empty($data['longitude']) ? $data['longitude'] : null);
        $this->db->bind(':site_id', $data['site_id']);

        if ($this->db->execute()) {
            return true;
        }
        return false;
    }
    // Get All sites
    public function getSiteByClientId($client_id) {
        // Sites.client_id has historically stored either Clients.id or Users.id
        // Make this query resilient: return sites where client_id matches the provided id,
        // or where client_id matches the Users.id for the Clients record, or where client_id
        // matches the Clients.id when provided a Users.id.
        $this->db->query(
            "SELECT * FROM sites WHERE client_id = :client_id
             OR client_id = (SELECT user_id FROM Clients WHERE id = :client_id)
             OR client_id = (SELECT id FROM Clients WHERE user_id = :client_id)"
        );
        $this->db->bind(':client_id', $client_id);
        return $this->db->resultSet();
    }
    // Get Site
    public function getSiteById($site_id) {
        $this->db->query("SELECT * FROM sites WHERE id = :site_id");
        $this->db->bind(':site_id', $site_id);
        return $this->db->single();
    }
    // Update Site

// ======================================================================== //
// =======================      Admin Advertisements       ====================== //
// ======================================================================== //
    // Get single advertisement by ID
    public function getAdvertisementById($id) {
        $this->db->query('
            SELECT a.*, u.name as creator_name 
            FROM advertisements a 
            LEFT JOIN Users u ON a.created_by = u.id 
            WHERE a.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get all advertisements
    public function getAdvertisements() {
        $this->db->query('
            SELECT a.*, u.name as creator_name 
            FROM advertisements a 
            LEFT JOIN Users u ON a.created_by = u.id 
            ORDER BY a.created_at DESC
        ');
        return $this->db->resultSet();
    }

    // Create new advertisement
    public function createAdvertisement($data) {
        $this->db->query('
            INSERT INTO advertisements 
            (title, description, image_path, target_roles, created_by, status) 
            VALUES (:title, :description, :image_path, :target_roles, :created_by, :status)
        ');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':target_roles', $data['target_roles']);
        $this->db->bind(':created_by', $data['created_by']);
        $this->db->bind(':status', $data['status']);
        
        if ($this->db->execute()) {
            return true;
        }
        return false;
    }

    // Update advertisement
    public function updateAdvertisement($id, $data) {
        $this->db->query('
            UPDATE advertisements SET 
                title = :title,
                description = :description,
                image_path = :image_path,
                target_roles = :target_roles,
                updated_at = NOW()
            WHERE id = :id
        ');
        $this->db->bind(':id', $id);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':target_roles', $data['target_roles']);
        return $this->db->execute();
    }

    // Delete advertisement
    public function deleteAdvertisement($id) {
        $this->db->query('DELETE FROM advertisements WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Toggle advertisement status
    public function toggleAdvertisementStatus($id) {
        $this->db->query('
            UPDATE advertisements 
            SET status = CASE WHEN status = "active" THEN "inactive" ELSE "active" END 
            WHERE id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    // ==============================
    // User Management (For Settings)
    // ==============================
    
    // Create new user
    public function createUser($data) {
        try {
            error_log("Starting user creation for: " . $data['email']);
            
            $this->db->beginTransaction();

            // Insert into Users table
            $query = "INSERT INTO Users (userID, name, email, password, role, status, created_at) 
                     VALUES (:userID, :name, :email, :password, :role, 'active', NOW())";
            
            $this->db->query($query);
            $this->db->bind(':userID', $data['userID']);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':role', $data['role']);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to insert user into Users table');
            }

            $userID = $this->db->lastInsertId();
            error_log("User inserted with ID: " . $userID);

            // Insert into user_details table
            $this->insertUserDetails($userID, $data);
            error_log("User details inserted successfully");

            $this->db->commit();
            error_log("Transaction committed successfully");
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("User creation error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    // Insert user details
    private function insertUserDetails($userID, $data) {
        $query = "INSERT INTO user_details (user_id, nic, mobile, address, additional_info) 
                 VALUES (:user_id, :nic, :mobile, :address, :additional_info)";
        
        $this->db->query($query);
        $this->db->bind(':user_id', $userID);
        $this->db->bind(':nic', $data['nic'] ?? null);
        $this->db->bind(':mobile', $data['mobile'] ?? null);
        $this->db->bind(':address', $data['address'] ?? null);
        $this->db->bind(':additional_info', $data['additional_info'] ?? null);
        
        if (!$this->db->execute()) {
            throw new Exception('Failed to insert user details');
        }
        
        return true;
    }

    // Check if email already exists
    public function findUserByEmail($email) {
        $this->db->query("SELECT id FROM Users WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
    // Get all admins
    public function getAdmins() {
        $this->db->query("
            SELECT u.userID, u.name, u.email, u.role, u.status, u.created_at, 
                   ud.mobile, ud.nic, ud.address
            FROM Users u 
            LEFT JOIN user_details ud ON u.id = ud.user_id 
            WHERE u.role = 'admin' AND u.status = 'active'
            ORDER BY u.created_at DESC
        ");
        return $this->db->resultSet();
    }

    // Get user by userID
    public function getUserByID($userID) {
        $this->db->query("
            SELECT u.*, ud.nic, ud.mobile, ud.address, ud.additional_info 
            FROM Users u 
            LEFT JOIN user_details ud ON u.id = ud.user_id 
            WHERE u.id = :userID
        ");
        $this->db->bind(':userID', $userID);
        return $this->db->single();
    }

    // Get all Users by role
    public function getUsersByRole($role) {
        $this->db->query("
            SELECT u.*, ud.nic, ud.mobile, ud.address 
            FROM Users u 
            LEFT JOIN user_details ud ON u.id = ud.user_id 
            WHERE u.role = :role AND u.status = 'active'
            ORDER BY u.name
        ");
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    // Update user status
    public function updateUserStatus($userID, $status) {
        $this->db->query("UPDATE Users SET status = :status, updated_at = NOW() WHERE userID = :userID");
        $this->db->bind(':userID', $userID);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    // Delete user
    public function deleteUser($userID) {
        try {
            $this->db->beginTransaction();

            // Get user id first
            $this->db->query("SELECT id FROM Users WHERE userID = :userID");
            $this->db->bind(':userID', $userID);
            $user = $this->db->single();
            
            if (!$user) {
                throw new Exception('User not found');
            }

            $userId = $user->id;

            // Delete from user_details
            $this->db->query("DELETE FROM user_details WHERE user_id = :user_id");
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // Delete from Users
            $this->db->query("DELETE FROM Users WHERE id = :user_id");
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("User deletion error: " . $e->getMessage());
            return false;
        }
    }

    // ==============================
    // Leave Request Management
    // ==============================
    
    // Get all pending leave requests (from all roles)
    public function getPendingLeaveRequests() {
        $this->db->query("
            SELECT 
                lr.*,
                CASE 
                    WHEN lr.caretaker_id IS NOT NULL THEN u1.name
                    WHEN lr.supervisor_id IS NOT NULL THEN u2.name
                    WHEN lr.mobilerider_id IS NOT NULL THEN u3.name
                    WHEN lr.premiseofficer_id IS NOT NULL THEN u4.name
                END as employee_name,
                CASE 
                    WHEN lr.caretaker_id IS NOT NULL THEN u1.email
                    WHEN lr.supervisor_id IS NOT NULL THEN u2.email
                    WHEN lr.mobilerider_id IS NOT NULL THEN u3.email
                    WHEN lr.premiseofficer_id IS NOT NULL THEN u4.email
                END as employee_email,
                CASE 
                    WHEN lr.caretaker_id IS NOT NULL THEN 'Caretaker'
                    WHEN lr.supervisor_id IS NOT NULL THEN 'Supervisor'
                    WHEN lr.mobilerider_id IS NOT NULL THEN 'Mobile Rider'
                    WHEN lr.premiseofficer_id IS NOT NULL THEN 'Premise Officer'
                END as employee_role
            FROM leave_requests lr
            LEFT JOIN Users u1 ON lr.caretaker_id = u1.id
            LEFT JOIN Users u2 ON lr.supervisor_id = u2.id
            LEFT JOIN Users u3 ON lr.mobilerider_id = u3.id
            LEFT JOIN Users u4 ON lr.premiseofficer_id = u4.id
            WHERE lr.status = 'Pending'
            ORDER BY lr.created_at DESC
        ");
        return $this->db->resultSet();
    }

    // Get all leave requests (from all roles)
    public function getAllLeaveRequests() {
        $this->db->query("
            SELECT 
                lr.*,
                CASE 
                    WHEN lr.caretaker_id IS NOT NULL THEN u1.name
                    WHEN lr.supervisor_id IS NOT NULL THEN u2.name
                    WHEN lr.mobilerider_id IS NOT NULL THEN u3.name
                    WHEN lr.premiseofficer_id IS NOT NULL THEN u4.name
                END as employee_name,
                CASE 
                    WHEN lr.caretaker_id IS NOT NULL THEN u1.email
                    WHEN lr.supervisor_id IS NOT NULL THEN u2.email
                    WHEN lr.mobilerider_id IS NOT NULL THEN u3.email
                    WHEN lr.premiseofficer_id IS NOT NULL THEN u4.email
                END as employee_email,
                CASE 
                    WHEN lr.caretaker_id IS NOT NULL THEN 'Caretaker'
                    WHEN lr.supervisor_id IS NOT NULL THEN 'Supervisor'
                    WHEN lr.mobilerider_id IS NOT NULL THEN 'Mobile Rider'
                    WHEN lr.premiseofficer_id IS NOT NULL THEN 'Premise Officer'
                END as employee_role
            FROM leave_requests lr
            LEFT JOIN Users u1 ON lr.caretaker_id = u1.id
            LEFT JOIN Users u2 ON lr.supervisor_id = u2.id
            LEFT JOIN Users u3 ON lr.mobilerider_id = u3.id
            LEFT JOIN Users u4 ON lr.premiseofficer_id = u4.id
            ORDER BY lr.created_at DESC
        ");
        return $this->db->resultSet();
    }

    // Get leave request by ID (from all roles)
    public function getLeaveRequestById($id) {
        $this->db->query("
            SELECT 
                lr.*,
                CASE 
                    WHEN lr.caretaker_id IS NOT NULL THEN u1.name
                    WHEN lr.supervisor_id IS NOT NULL THEN u2.name
                    WHEN lr.mobilerider_id IS NOT NULL THEN u3.name
                    WHEN lr.premiseofficer_id IS NOT NULL THEN u4.name
                END as employee_name,
                CASE 
                    WHEN lr.caretaker_id IS NOT NULL THEN u1.email
                    WHEN lr.supervisor_id IS NOT NULL THEN u2.email
                    WHEN lr.mobilerider_id IS NOT NULL THEN u3.email
                    WHEN lr.premiseofficer_id IS NOT NULL THEN u4.email
                END as employee_email,
                CASE 
                    WHEN lr.caretaker_id IS NOT NULL THEN 'Caretaker'
                    WHEN lr.supervisor_id IS NOT NULL THEN 'Supervisor'
                    WHEN lr.mobilerider_id IS NOT NULL THEN 'Mobile Rider'
                    WHEN lr.premiseofficer_id IS NOT NULL THEN 'Premise Officer'
                END as employee_role
            FROM leave_requests lr
            LEFT JOIN Users u1 ON lr.caretaker_id = u1.id
            LEFT JOIN Users u2 ON lr.supervisor_id = u2.id
            LEFT JOIN Users u3 ON lr.mobilerider_id = u3.id
            LEFT JOIN Users u4 ON lr.premiseofficer_id = u4.id
            WHERE lr.id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Approve leave request
    public function approveLeaveRequest($id, $admin_id) {
        $this->db->query("
            UPDATE leave_requests 
            SET status = 'Approved', 
                reviewed_by = :admin_id, 
                reviewed_at = NOW()
            WHERE id = :id
        ");
        $this->db->bind(':id', $id);
        $this->db->bind(':admin_id', $admin_id);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    // Reject leave request
    public function rejectLeaveRequest($id, $admin_id, $reason) {
        $this->db->query("
            UPDATE leave_requests 
            SET status = 'Rejected', 
                admin_response = :reason,
                reviewed_by = :admin_id, 
                reviewed_at = NOW()
            WHERE id = :id
        ");
        $this->db->bind(':id', $id);
        $this->db->bind(':admin_id', $admin_id);
        $this->db->bind(':reason', $reason);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    // Get leave request statistics
    public function getLeaveRequestStats() {
        $this->db->query("
            SELECT 
                COUNT(CASE WHEN status = 'Pending' THEN 1 END) as pending,
                COUNT(CASE WHEN status = 'Approved' THEN 1 END) as approved,
                COUNT(CASE WHEN status = 'Rejected' THEN 1 END) as rejected,
                COUNT(*) as total
            FROM leave_requests
        ");
        return $this->db->single();
    }

    // ==============================
    // Service Request Management
    // ==============================
    
    // Get all service requests from clients
    public function getAllServiceRequests() {
        $this->db->query("
            SELECT sr.*, u.name as client_name, u.email as client_email
            FROM service_requests sr
            JOIN Users u ON sr.client_id = u.id
            ORDER BY sr.submitted_date DESC
        ");
        return $this->db->resultSet();
    }

    // Get service request by ID
    public function getServiceRequestById($id) {
        $this->db->query("
            SELECT sr.*, u.name as client_name, u.email as client_email
            FROM service_requests sr
            JOIN Users u ON sr.client_id = u.id
            WHERE sr.id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update service request status (Approve/Reject)
    public function updateServiceRequestStatus($id, $status) {
        $this->db->query("
            UPDATE service_requests 
            SET status = :status, 
                updated_at = NOW()
            WHERE id = :id
        ");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    // Get service request statistics
    public function getServiceRequestStats() {
        $this->db->query("
            SELECT 
                COUNT(CASE WHEN status = 'Pending' THEN 1 END) as pending,
                COUNT(CASE WHEN status = 'Approved' THEN 1 END) as approved,
                COUNT(CASE WHEN status = 'Rejected' THEN 1 END) as rejected,
                COUNT(*) as total
            FROM service_requests
        ");
        return $this->db->single();
    }

    // Package request methods
    public function getAllPackageRequests() {
        $this->db->query("SELECT pr.*, u.name as client_name
                          FROM package_requests pr
                          JOIN Users u ON pr.client_id = u.id
                          WHERE (
                              LOWER(COALESCE(pr.payment_status, '')) = 'paid'
                              OR EXISTS (
                                  SELECT 1
                                  FROM payments p
                                  WHERE p.package_request_id = pr.id
                                  AND LOWER(COALESCE(p.status, '')) = 'paid'
                              )
                          )
                          ORDER BY pr.submitted_date DESC");
        return $this->db->resultSet();
    }

    public function getPackageRequestStats() {
        $this->db->query("SELECT
                            COUNT(CASE WHEN status = 'Pending' THEN 1 END) as pending,
                            COUNT(CASE WHEN status = 'Approved' THEN 1 END) as approved,
                            COUNT(CASE WHEN status = 'Rejected' THEN 1 END) as rejected,
                            COUNT(*) as total
                          FROM package_requests pr
                          WHERE (
                              LOWER(COALESCE(pr.payment_status, '')) = 'paid'
                              OR EXISTS (
                                  SELECT 1
                                  FROM payments p
                                  WHERE p.package_request_id = pr.id
                                  AND LOWER(COALESCE(p.status, '')) = 'paid'
                              )
                          )");
        return $this->db->single();
    }

    // Get package request by ID
    public function getPackageRequestById($id) {
        $this->db->query("SELECT *
                          FROM package_requests pr
                          WHERE pr.id = :id
                          AND (
                              LOWER(COALESCE(pr.payment_status, '')) = 'paid'
                              OR EXISTS (
                                  SELECT 1
                                  FROM payments p
                                  WHERE p.package_request_id = pr.id
                                  AND LOWER(COALESCE(p.status, '')) = 'paid'
                              )
                          )");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get package request details from site
    public function getPackageRequestBySiteId($site_id) {
        $this->db->query("SELECT pr.* FROM package_requests pr 
                          INNER JOIN sites s ON s.package_request_id = pr.id 
                          WHERE s.id = :site_id");
        $this->db->bind(':site_id', $site_id);
        return $this->db->single();
    }

    // Get client's phone number
    public function getClientPhoneNumber($user_id) {
        // client_id in package_requests references Users.id directly
        $this->db->query("SELECT phone_number FROM Users WHERE id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        return $result ? $result->phone_number : null;
    }

    // Get Clients table ID from Users ID
    public function getClientsTableId($user_id) {
        $this->db->query("SELECT id FROM Clients WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        
        if ($result) {
            error_log("Found Clients.id = {$result->id} for user_id = {$user_id}");
            return $result->id;
        } else {
            error_log("ERROR: No Clients record found for user_id = {$user_id}");
            return null;
        }
    }

    // Create site from approved package request
    public function createSiteFromPackageRequest($packageRequest) {
        // Log the package request details
        error_log("Creating site from package request ID: {$packageRequest->id}, client_id (Users.id): {$packageRequest->client_id}");
        
        // Get the Clients table ID (sites table references Clients.id, not Users.id)
        $clientsTableId = $this->getClientsTableId($packageRequest->client_id);
        
        if (!$clientsTableId) {
            error_log("CRITICAL ERROR: Failed to get Clients table ID for user_id: {$packageRequest->client_id}");
            return false;
        }
        
        error_log("Using Clients.id = {$clientsTableId} to create site for user_id = {$packageRequest->client_id}");
        
        // Get client's phone number from package request or Users table
        $phoneNumber = !empty($packageRequest->phone_number) ? $packageRequest->phone_number : $this->getClientPhoneNumber($packageRequest->client_id);
        
        // Insert site with all available fields (sites table uses 'image' not 'image_name')
        $this->db->query("INSERT INTO sites (client_id, package_request_id, site_name, address, city, district, phone_number, latitude, longitude, image, created_at, updated_at) 
                          VALUES (:client_id, :package_request_id, :site_name, :address, :city, :district, :phone_number, :latitude, :longitude, :image, NOW(), NOW())");
        $this->db->bind(':client_id', $clientsTableId);
        $this->db->bind(':package_request_id', $packageRequest->id);
        $this->db->bind(':site_name', $packageRequest->site_name);
        $this->db->bind(':address', $packageRequest->site_address);
        $this->db->bind(':city', $packageRequest->city);
        $this->db->bind(':district', $packageRequest->district ?? null);
        $this->db->bind(':phone_number', $phoneNumber);
        $this->db->bind(':latitude', $packageRequest->latitude ?? null);
        $this->db->bind(':longitude', $packageRequest->longitude ?? null);
        $this->db->bind(':image', $packageRequest->image_name ?? null);
        
        if ($this->db->execute()) {
            // Return the last inserted site ID
            $siteId = $this->db->lastInsertId();
            error_log("SUCCESS: Site created with ID: {$siteId} for Clients.id: {$clientsTableId} (Users.id: {$packageRequest->client_id})");
            return $siteId;
        }
        
        error_log("ERROR: Failed to insert site for Clients.id: {$clientsTableId}");
        return false;
    }

    public function approvePackageRequest($id, $admin_id, $notes) {
        // Guard: admin can only process paid requests.
        $packageRequest = $this->getPackageRequestById($id);
        if (!$packageRequest) {
            return false;
        }

        // Step 1: Update the package request status to Approved
        $this->db->query("UPDATE package_requests 
                          SET status = 'Approved', 
                              admin_notes = :notes, 
                              approved_by = :admin_id, 
                              approved_at = NOW() 
                          WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':admin_id', $admin_id);
        $this->db->bind(':notes', $notes);
        
        if (!$this->db->execute()) {
            return false; // Failed to approve
        }
        
        // Step 2: Create a site from the package request
        $siteId = $this->createSiteFromPackageRequest($packageRequest);
        
        // Return the site ID (or true if site creation failed but approval succeeded)
        return $siteId ? $siteId : true;
    }

    // Get available officers with filters
    public function getAvailableOfficers($filters) {
        $city = $filters['city'];
        $district = $filters['district'];
        $districtFilter = $filters['district_filter'];
        $cityFilter = $filters['city_filter'];
        $availability = $filters['availability'];
        $status = $filters['status'];
        $siteId = $filters['site_id'];

        // Build base query
        $query = "SELECT 
                    u.id as user_id,
                    u.name,
                    u.email,
                    u.phone_number,
                    u.profile_image,
                    po.officerID,
                    po.city,
                    po.district,
                    po.employment_status,
                    po.rank,
                    po.rating,
                    po.shift_pattern,
                    osa.id as current_assignment
                  FROM Users u
                  INNER JOIN premise_officers po ON u.id = po.userID
                  LEFT JOIN officer_site_assignments osa ON u.id = osa.officer_id AND osa.status = 'Active'
                  WHERE u.role = 'premise officer'
                  AND po.rank != 'Supervisor'";

        $bindParams = [];

        // Add district filter
        if ($districtFilter === 'same-district' && !empty($district)) {
            $query .= " AND LOWER(po.district) = LOWER(:district)";
            $bindParams[':district'] = $district;
        }

        // Add city filter
        if ($cityFilter === 'same-city' && !empty($city)) {
            $query .= " AND LOWER(po.city) = LOWER(:city)";
            $bindParams[':city'] = $city;
        }

        // Add availability filter
        if ($availability === 'available') {
            $query .= " AND osa.id IS NULL";
        } elseif ($availability === 'assigned') {
            $query .= " AND osa.id IS NOT NULL";
        }

        // Add employment status filter
        if ($status !== 'all') {
            $query .= " AND po.employment_status = :status";
            $bindParams[':status'] = $status;
        }

        // Order by: same district first, then same city, then by name
        $query .= " ORDER BY ";
        if (!empty($district)) {
            $query .= "LOWER(po.district) = LOWER(:order_district) DESC, ";
            $bindParams[':order_district'] = $district;
        }
        if (!empty($city)) {
            $query .= "LOWER(po.city) = LOWER(:order_city) DESC, ";
            $bindParams[':order_city'] = $city;
        }
        $query .= "u.name";

        $this->db->query($query);

        // Bind all parameters
        foreach ($bindParams as $param => $value) {
            $this->db->bind($param, $value);
        }

        return $this->db->resultSet();
    }

    // Get district from city name
    private function getDistrictFromCity($city) {
        // City to district mapping based on the JavaScript file
        $cityToDistrict = [
            // Colombo district cities
            'colombo 1' => 'colombo', 'colombo 2' => 'colombo', 'colombo 3' => 'colombo', 
            'colombo 4' => 'colombo', 'colombo 5' => 'colombo', 'colombo 6' => 'colombo',
            'colombo 7' => 'colombo', 'colombo 8' => 'colombo', 'colombo 9' => 'colombo',
            'colombo 10' => 'colombo', 'dehiwala' => 'colombo', 'mount lavinia' => 'colombo',
            'moratuwa' => 'colombo', 'nugegoda' => 'colombo', 'maharagama' => 'colombo',
            'kotte' => 'colombo', 'battaramulla' => 'colombo', 'rajagiriya' => 'colombo',
            
            // Gampaha district cities
            'gampaha' => 'gampaha', 'negombo' => 'gampaha', 'kelaniya' => 'gampaha',
            'kadawatha' => 'gampaha', 'ragama' => 'gampaha', 'wattala' => 'gampaha',
            'ja-ela' => 'gampaha', 'kandana' => 'gampaha', 'minuwangoda' => 'gampaha',
            
            // Kalutara district
            'kalutara' => 'kalutara', 'panadura' => 'kalutara', 'horana' => 'kalutara',
            'wadduwa' => 'kalutara', 'beruwala' => 'kalutara', 'aluthgama' => 'kalutara',
            
            // Kandy district
            'kandy' => 'kandy', 'peradeniya' => 'kandy', 'katugastota' => 'kandy',
            'gampola' => 'kandy', 'nawalapitiya' => 'kandy', 'akurana' => 'kandy',
            
            // Add more mappings as needed (keeping it concise for now)
        ];

        $cityLower = strtolower(trim($city));
        return $cityToDistrict[$cityLower] ?? null;
    }

    // Assign officer to site
    public function assignOfficerToSite($siteId, $officerId, $assignedBy, $shiftType = 'Full Time', $assignmentEnd = null) {
        // Check if officer is already assigned to another site
        $this->db->query("SELECT id FROM officer_site_assignments 
                          WHERE officer_id = :officer_id AND status = 'Active'");
        $this->db->bind(':officer_id', $officerId);
        $existing = $this->db->single();

        if ($existing) {
            return ['success' => false, 'message' => 'Officer is already assigned to another site'];
        }

        // Create new assignment with assignment_end if provided
        $this->db->query("INSERT INTO officer_site_assignments 
                          (site_id, officer_id, shift_type, assignment_start, assignment_end, assigned_by, status) 
                          VALUES (:site_id, :officer_id, :shift_type, CURDATE(), :assignment_end, :assigned_by, 'Active')");
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':officer_id', $officerId);
        $this->db->bind(':shift_type', $shiftType);
        $this->db->bind(':assignment_end', $assignmentEnd);
        $this->db->bind(':assigned_by', $assignedBy);

        if ($this->db->execute()) {
            return ['success' => true, 'message' => 'Officer assigned successfully'];
        }

        return ['success' => false, 'message' => 'Failed to assign officer'];
    }

    // Unassign officer from site
    public function unassignOfficerFromSite($assignmentId) {
        $this->db->query("UPDATE officer_site_assignments 
                          SET status = 'Completed', assignment_end = CURDATE() 
                          WHERE id = :id");
        $this->db->bind(':id', $assignmentId);

        if ($this->db->execute()) {
            return ['success' => true, 'message' => 'Officer unassigned successfully'];
        }

        return ['success' => false, 'message' => 'Failed to unassign officer'];
    }

    public function unassignCaretakerFromSite($assignmentId) {
        $this->db->query("UPDATE caretaker_site_assignments 
                          SET status = 'Completed', assignment_end = CURDATE() 
                          WHERE id = :id");
        $this->db->bind(':id', $assignmentId);

        if ($this->db->execute()) {
            return ['success' => true, 'message' => 'Caretaker unassigned successfully'];
        }

        return ['success' => false, 'message' => 'Failed to unassign caretaker'];
    }

    // Get assigned officers for a site
    public function getAssignedOfficers($siteId) {
        $this->db->query("SELECT 
                            osa.id as assignment_id,
                            osa.shift_type,
                            osa.assignment_start,
                            osa.assignment_end,
                            osa.status,
                            u.id as user_id,
                            u.name,
                            u.email,
                            u.phone_number,
                            u.profile_image,
                            po.officerID,
                            po.city,
                            po.district,
                            po.rank
                          FROM officer_site_assignments osa
                          INNER JOIN Users u ON osa.officer_id = u.id
                          INNER JOIN premise_officers po ON u.id = po.userID
                          WHERE osa.site_id = :site_id AND osa.status = 'Active'
                          ORDER BY osa.assignment_start DESC");
        $this->db->bind(':site_id', $siteId);
        return $this->db->resultSet();
    }

    public function getAssignedCaretakers($siteId) {
        $this->db->query("SELECT 
                            csa.id as assignment_id,
                            csa.assignment_start,
                            csa.assignment_end,
                            csa.status,
                            u.id as user_id,
                            u.name,
                            u.email,
                            u.phone_number,
                            u.profile_image,
                            ct.caretakerID,
                            ct.city,
                            ct.district,
                            ct.employment_status
                          FROM caretaker_site_assignments csa
                          INNER JOIN Users u ON csa.caretaker_id = u.id
                          INNER JOIN care_taker ct ON u.id = ct.userID
                          WHERE csa.site_id = :site_id AND csa.status = 'Active'
                          ORDER BY csa.assignment_start DESC");
        $this->db->bind(':site_id', $siteId);
        return $this->db->resultSet();
    }

    public function rejectPackageRequest($id, $admin_id, $reason) {
        // Guard: admin can only process paid requests.
        if (!$this->getPackageRequestById($id)) {
            return false;
        }

        $this->db->query("UPDATE package_requests SET status = 'Rejected', admin_notes = :reason, approved_by = :admin_id, approved_at = NOW() WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':admin_id', $admin_id);
        $this->db->bind(':reason', $reason);
        return $this->db->execute();
    }

    // Create draft site from package request
    public function createDraftSite($packageRequest) {
        // Get client's Clients table ID
        $clientsTableId = $this->getClientsTableId($packageRequest->client_id);
        
        if (!$clientsTableId) {
            return false;
        }

        // Get phone number
        $phoneNumber = $packageRequest->phone_number ?? $this->getClientPhoneNumber($packageRequest->client_id);
        
        // Insert draft site (is_draft = 1) - matching exact structure of createSiteFromPackageRequest
        $this->db->query("INSERT INTO sites 
            (is_draft, client_id, package_request_id, site_name, address, city, district, phone_number, 
             latitude, longitude, image) 
            VALUES 
            (1, :client_id, :package_request_id, :site_name, :address, :city, :district, :phone_number,
             :latitude, :longitude, :image)");
        
        $this->db->bind(':client_id', $clientsTableId);
        $this->db->bind(':package_request_id', $packageRequest->id);
        $this->db->bind(':site_name', $packageRequest->site_name);
        $this->db->bind(':address', $packageRequest->site_address);
        $this->db->bind(':city', $packageRequest->city ?? '');
        $this->db->bind(':district', $packageRequest->district ?? '');
        $this->db->bind(':phone_number', $phoneNumber);
        $this->db->bind(':latitude', $packageRequest->latitude ?? null);
        $this->db->bind(':longitude', $packageRequest->longitude ?? null);
        $this->db->bind(':image', $packageRequest->image_name ?? null);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    // Link draft site to package request
    public function linkDraftSiteToRequest($requestId, $draftSiteId) {
        $this->db->query("UPDATE package_requests SET draft_site_id = :draft_site_id WHERE id = :id");
        $this->db->bind(':draft_site_id', $draftSiteId);
        $this->db->bind(':id', $requestId);
        return $this->db->execute();
    }

    // Get count of assigned officers for a site
    public function getAssignedOfficerCount($siteId) {
        $this->db->query("SELECT COUNT(*) as count FROM officer_site_assignments WHERE site_id = :site_id AND status = 'Active'");
        $this->db->bind(':site_id', $siteId);
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    // Finalize draft site (convert to official)
    public function finalizeDraftSite($siteId) {
        $this->db->query("UPDATE sites SET is_draft = 0 WHERE id = :id");
        $this->db->bind(':id', $siteId);
        return $this->db->execute();
    }

    // Delete draft site and all assignments
    public function deleteDraftSite($siteId) {
        // Delete officer assignments first
        $this->db->query("DELETE FROM officer_site_assignments WHERE site_id = :site_id");
        $this->db->bind(':site_id', $siteId);
        $this->db->execute();
        
        // Delete the site
        $this->db->query("DELETE FROM sites WHERE id = :id AND is_draft = 1");
        $this->db->bind(':id', $siteId);
        return $this->db->execute();
    }

    // Approve package request (final)
    public function approvePackageRequestFinal($requestId, $adminId) {
        $this->db->query("UPDATE package_requests 
            SET status = 'Approved', 
                approved_by = :admin_id, 
                approved_at = NOW() 
            WHERE id = :id");
        
        $this->db->bind(':id', $requestId);
        $this->db->bind(':admin_id', $adminId);
        
        return $this->db->execute();
    }

    // Reject package request (final) and clear draft_site_id
    public function rejectPackageRequestFinal($requestId, $adminId) {
        $this->db->query("UPDATE package_requests 
            SET status = 'Rejected', 
                approved_by = :admin_id, 
                approved_at = NOW(),
                draft_site_id = NULL
            WHERE id = :id");
        
        $this->db->bind(':id', $requestId);
        $this->db->bind(':admin_id', $adminId);
        
        return $this->db->execute();
    }

    

    // ==============================
    // ===========Admins=============
    // ==============================

    public function getAllAdmins(){
        $this->db->query("SELECT * FROM Users WHERE role = 'admin' ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function addAdmin($data){
        $this->db->query("SELECT userID FROM Users WHERE userID LIKE 'ADMIN%' ORDER BY userID DESC LIMIT 1");
        $last = $this->db->single();
        
        if ($last) {
            // Extract number from ADMIN001
            $number = (int) substr($last->userID, 5); // Remove "ADMIN" (5 characters)
            $nextNumber = $number + 1;
        } else {
            $nextNumber = 1; // First admin
        }
        
        $userID = 'ADMIN' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $tempPassword = '0000'; // Simple temp password
        $role_name = 'admin';

        $this->db->query("INSERT INTO Users (userID, name, email, phone_number, profile_image, password, role) 
                    VALUES (:userID, :name, :email, :phone, :profile_image, :password, :role)");
        $this->db->bind(':userID', $userID);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone_number']);
        $this->db->bind(':profile_image', $data['image_name']);
        $this->db->bind(':role', $role_name);
        $this->db->bind(':password', password_hash($tempPassword, PASSWORD_DEFAULT));
    
        if ($this->db->execute()) {
            return [
                'success' => true,
                'id' => $this->db->lastInsertId(),
                'userID' => $userID,
                'tempPassword' => $tempPassword,
                'email' => $data['email'],
                'name' => $data['name']
            ];
        }
        return ['success' => false];

    }

    public function getAdmin($userID) {
        $this->db->query("SELECT * FROM Users WHERE userID = :userID");
        $this->db->bind(':userID', $userID);
        return $this->db->single();
    }

    public function getAdminById($id) {
        $this->db->query("SELECT * FROM Users WHERE id = :id AND role = 'admin'");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // ==============================
    // Route Management
    // ==============================

    public function generateRouteId() {
        $this->db->query("SELECT id FROM routes ORDER BY id DESC LIMIT 1");
        $last = $this->db->single();
        
        if ($last) {
            $number = (int)substr($last->id, 1);
            $nextNumber = $number + 1;
        } else {
            $nextNumber = 1;
        }
        
        return 'R' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function insertRoute($data) {
        $this->db->query("INSERT INTO routes (id, route_name, description, location, status, created_by) VALUES (:id, :route_name, :description, :location, :status, :created_by)");
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':route_name', $data['route_name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':location', $data['location'] ?? '');
        $this->db->bind(':status', $data['status'] ?? 'Active');
        $this->db->bind(':created_by', $data['created_by']);
        
        return $this->db->execute();
    }

    public function getAllSites() {
        $this->db->query("
            SELECT s.*, c.contact_person_name as client_name, u.name as client_user_name
            FROM sites s
            LEFT JOIN Clients c ON s.client_id = c.id
            LEFT JOIN Users u ON c.user_id = u.id
            WHERE s.is_draft = 0
            ORDER BY s.site_name
        ");
        return $this->db->resultSet();
    }

    public function getAllRoutes() {
        $this->db->query("
            SELECT r.*, COUNT(rs.site_id) as site_count, u.name as rider_name
            FROM routes r
            LEFT JOIN route_sites rs ON r.id = rs.route_id
            LEFT JOIN Users u ON r.assigned_rider_id = u.id
            GROUP BY r.id
            ORDER BY r.created_at DESC
        ");
        $routes = $this->db->resultSet();
        
        // Add sites with coordinates for each route
        foreach ($routes as $route) {
            $route->sites = $this->getRouteSitesWithCoords($route->id);
            
            // Calculate route center from sites
            if (!empty($route->sites)) {
                $latSum = 0;
                $lngSum = 0;
                $count = 0;
                
                foreach ($route->sites as $site) {
                    if ($site->latitude && $site->longitude) {
                        $latSum += floatval($site->latitude);
                        $lngSum += floatval($site->longitude);
                        $count++;
                    }
                }
                
                if ($count > 0) {
                    $route->center_lat = $latSum / $count;
                    $route->center_lng = $lngSum / $count;
                }
            }
        }
        
        return $routes;
    }
    
    public function getRouteSitesWithCoords($routeId) {
        $this->db->query("
            SELECT s.id, s.site_name, s.address, s.latitude, s.longitude
            FROM route_sites rs
            JOIN sites s ON rs.site_id = s.id
            WHERE rs.route_id = :route_id
            ORDER BY s.site_name
        ");
        $this->db->bind(':route_id', $routeId);
        return $this->db->resultSet();
    }

    public function getRouteById($id) {
        $this->db->query("SELECT * FROM routes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getRouteSites($routeId) {
        $this->db->query("
            SELECT s.*, c.contact_person_name as client_name, u.name as client_user_name
            FROM route_sites rs
            JOIN sites s ON rs.site_id = s.id
            LEFT JOIN Clients c ON s.client_id = c.id
            LEFT JOIN Users u ON c.user_id = u.id
            WHERE rs.route_id = :route_id
            ORDER BY s.site_name
        ");
        $this->db->bind(':route_id', $routeId);
        return $this->db->resultSet();
    }

    public function updateRouteLocation($routeId, $location) {
        $this->db->query("UPDATE routes SET location = :location WHERE id = :id");
        $this->db->bind(':location', $location);
        $this->db->bind(':id', $routeId);
        return $this->db->execute();
    }

    public function deleteRoute($routeId) {
        $this->db->query("DELETE FROM routes WHERE id = :id");
        $this->db->bind(':id', $routeId);
        return $this->db->execute();
    }
    
    public function isSiteAssignedToRoute($siteId) {
        $this->db->query("SELECT COUNT(*) as count FROM route_sites WHERE site_id = :site_id");
        $this->db->bind(':site_id', $siteId);
        $result = $this->db->single();
        return $result->count > 0;
    }
    
    public function assignSiteToRoute($siteId, $routeId) {
        // First check if already assigned
        if ($this->isSiteAssignedToRoute($siteId)) {
            return false;
        }
        
        $this->db->query("INSERT INTO route_sites (route_id, site_id) VALUES (:route_id, :site_id)");
        $this->db->bind(':route_id', $routeId);
        $this->db->bind(':site_id', $siteId);
        return $this->db->execute();
    }
    
    public function getRouteRider($routeId) {
        $this->db->query("SELECT u.id as user_id, u.name, u.email, u.profile_image 
                         FROM routes r 
                         LEFT JOIN Users u ON r.assigned_rider_id = u.id 
                         WHERE r.id = :route_id AND r.assigned_rider_id IS NOT NULL");
        $this->db->bind(':route_id', $routeId);
        return $this->db->single();
    }
    
    public function assignRiderToRoute($riderId, $routeId) {
        $this->db->query("UPDATE routes SET assigned_rider_id = :rider_id WHERE id = :route_id");
        $this->db->bind(':rider_id', $riderId);
        $this->db->bind(':route_id', $routeId);
        return $this->db->execute();
    }
    
    /**
     * Get all incidents for admin dashboard
     */
    public function getAllIncidents() {
        $this->db->query("
            SELECT 
                ir.*,
                s.site_name,
                u.name as officer_name,
                COALESCE(ir.status, 'Pending') as status,
                COALESCE(ir.priority, ir.severity, 'Medium') as priority
            FROM incident_reports ir
            LEFT JOIN sites s ON ir.site_id = s.id
            LEFT JOIN Users u ON ir.user_id = u.id
            ORDER BY ir.created_at DESC
        ");
        return $this->db->resultSet();
    }
    
    /**
     * Get incident statistics
     */
    public function getIncidentStats() {
        $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN COALESCE(status, 'Pending') = 'Pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status IN ('Resolved', 'Closed') THEN 1 ELSE 0 END) as resolved
            FROM incident_reports
        ");
        return $this->db->single();
    }

    /**
     * Get recent incidents with limit
     */
    public function getRecentIncidents($limit = 10) {
        $this->db->query("
            SELECT 
                ir.*,
                s.site_name,
                u.name as officer_name,
                u.role as officer_role,
                COALESCE(ir.status, 'Pending') as status,
                COALESCE(ir.priority, ir.severity, 'Medium') as priority
            FROM incident_reports ir
            LEFT JOIN sites s ON ir.site_id = s.id
            LEFT JOIN Users u ON ir.user_id = u.id
            ORDER BY ir.created_at DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    
    /**
     * Get incident by ID
     */
    public function getIncidentById($id) {
        $this->db->query("
            SELECT 
                ir.*,
                s.site_name,
                u.name as officer_name,
                u.role as officer_role,
                u.profile_image,
                COALESCE(ir.status, 'Pending') as status,
                COALESCE(ir.priority, ir.severity, 'Medium') as priority
            FROM incident_reports ir
            LEFT JOIN sites s ON ir.site_id = s.id
            LEFT JOIN Users u ON ir.user_id = u.id
            WHERE ir.id = :id 
            LIMIT 1
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Get all reviews for an incident
     */
    public function getIncidentReviews($incidentId) {
        $this->db->query('
            SELECT 
                ir.*,
                u.profile_image,
                u.name as reviewer_name,
                u.role
            FROM incident_reviews ir
            LEFT JOIN Users u ON ir.user_id = u.id
            WHERE ir.incident_id = :incident_id
            ORDER BY ir.created_at DESC
        ');
        
        $this->db->bind(':incident_id', $incidentId);
        return $this->db->resultSet();
    }
    
    /**
     * Add a review to an incident
     */
    public function addIncidentReview($incidentId, $userId, $reviewerName, $reviewTitle, $reviewType, $reviewDetails) {
        $this->db->query('
            INSERT INTO incident_reviews (
                incident_id,
                user_id,
                reviewer_name,
                review_title,
                review_type,
                review_details,
                created_at
            ) VALUES (
                :incident_id,
                :user_id,
                :reviewer_name,
                :review_title,
                :review_type,
                :review_details,
                NOW()
            )
        ');
        
        $this->db->bind(':incident_id', $incidentId);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':reviewer_name', $reviewerName);
        $this->db->bind(':review_title', $reviewTitle);
        $this->db->bind(':review_type', $reviewType);
        $this->db->bind(':review_details', $reviewDetails);
        
        return $this->db->execute();
    }
    
    /**
     * Update incident status
     */
    public function updateIncidentStatus($incidentId, $status) {
        $this->db->query('
            UPDATE incident_reports 
            SET status = :status, 
                updated_at = NOW() 
            WHERE id = :incident_id
        ');
        
        $this->db->bind(':incident_id', $incidentId);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
    }
    
    /**
     * Update incident actions taken
     */
    public function updateIncidentActionsTaken($incidentId, $actionsTaken) {
        $this->db->query('
            UPDATE incident_reports 
            SET action_taken = :actions_taken, 
                updated_at = NOW() 
            WHERE id = :incident_id
        ');
        
        $this->db->bind(':incident_id', $incidentId);
        $this->db->bind(':actions_taken', $actionsTaken);
        
        return $this->db->execute();
    }

    /**
     * Update officer field (rank or employment_status)
     */
    public function updateOfficerField($officerId, $field, $value, $role) {
        // Determine the table based on role
        $table = '';
        $statusField = 'employment_status';
        
        switch(strtolower($role)) {
            case 'premise officer':
                $table = 'premise_officers';
                break;
            case 'caretaker':
                $table = 'caretakers';
                break;
            case 'mobile rider':
                $table = 'mobile_riders';
                break;
            default:
                return false;
        }
        
        // Build query based on field
        if ($field === 'rank') {
            $this->db->query("UPDATE $table SET rank = :value WHERE userID = :user_id");
        } elseif ($field === 'employment_status') {
            $this->db->query("UPDATE $table SET $statusField = :value WHERE userID = :user_id");
        } else {
            return false;
        }
        
        $this->db->bind(':value', $value);
        $this->db->bind(':user_id', $officerId);
        
        return $this->db->execute();
    }

    // ==============================
    // ====== Supervisor Management =====
    // ==============================

    // Get available supervisors (premise officers with rank = Supervisor)
    public function getAvailableSupervisors($filters) {
        $sql = "SELECT DISTINCT
                    u.id,
                    u.userID,
                    u.name,
                    u.email,
                    u.phone_number as contact,
                    u.profile_image,
                    po.city,
                    po.district,
                    po.rank,
                    po.employment_status,
                    (SELECT COUNT(*) FROM officer_site_assignments osa 
                     WHERE osa.officer_id = u.id AND osa.status = 'Active') as current_assignment_count
                FROM Users u
                INNER JOIN premise_officers po ON u.id = po.userID
                WHERE u.role = 'premise officer'
                AND po.rank = 'Supervisor'";

        $params = [];

        // District filter
        if (isset($filters['district_filter'])) {
            if ($filters['district_filter'] === 'same-district' && !empty($filters['district'])) {
                $sql .= " AND po.district = :district";
                $params[':district'] = $filters['district'];
            }
            // 'all' means no district filter
        }

        // City filter
        if (isset($filters['city_filter'])) {
            if ($filters['city_filter'] === 'same-city' && !empty($filters['city'])) {
                $sql .= " AND po.city = :city";
                $params[':city'] = $filters['city'];
            }
            // 'all' means no city filter
        }

        // Availability filter - Available means NOT assigned to any site
        if (isset($filters['availability']) && $filters['availability'] === 'available') {
            $sql .= " AND NOT EXISTS (
                        SELECT 1 FROM officer_site_assignments osa 
                        WHERE osa.officer_id = u.id AND osa.status = 'Active'
                      )";
        }
        // 'all' means show everyone regardless of assignment status

        $sql .= " ORDER BY u.name ASC";

        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    // Assign supervisor to site
    public function assignSupervisorToSite($siteId, $supervisorId, $assignedBy) {
        // Check if supervisor is already assigned to another site
        $this->db->query("SELECT id FROM officer_site_assignments 
                          WHERE officer_id = :officer_id AND status = 'Active'");
        $this->db->bind(':officer_id', $supervisorId);
        $existing = $this->db->single();

        if ($existing) {
            return ['success' => false, 'message' => 'Supervisor is already assigned to another site'];
        }

        // Verify the user is actually a supervisor
        $this->db->query("SELECT po.rank FROM premise_officers po
                          INNER JOIN Users u ON po.userID = u.id
                          WHERE u.id = :user_id AND po.rank = 'Supervisor'");
        $this->db->bind(':user_id', $supervisorId);
        $supervisor = $this->db->single();

        if (!$supervisor) {
            return ['success' => false, 'message' => 'User is not a valid supervisor'];
        }

        // Create new assignment with shift_type as 'Supervisor'
        $this->db->query("INSERT INTO officer_site_assignments 
                          (site_id, officer_id, shift_type, assignment_start, assigned_by, status) 
                          VALUES (:site_id, :officer_id, 'Supervisor', CURDATE(), :assigned_by, 'Active')");
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':officer_id', $supervisorId);
        $this->db->bind(':assigned_by', $assignedBy);

        if ($this->db->execute()) {
            return ['success' => true, 'message' => 'Supervisor assigned successfully'];
        }

        return ['success' => false, 'message' => 'Failed to assign supervisor'];
    }

    // Update Admin
    public function updateAdmin($data) {
        $this->db->query("UPDATE Users 
                         SET name = :name, 
                             email = :email, 
                             phone_number = :phone_number
                         WHERE id = :admin_id AND role = 'admin'");
        
        $this->db->bind(':admin_id', $data['admin_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone_number', $data['phone_number']);
        
        return $this->db->execute();
    }

    // Delete Admin
    public function deleteAdmin($admin_id) {
        $this->db->query("DELETE FROM Users WHERE id = :admin_id AND role = 'admin'");
        $this->db->bind(':admin_id', $admin_id);
        
        return $this->db->execute();
    }

    // Update Profile Phone
    public function updateProfilePhone($user_id, $phone_number) {
        $this->db->query("UPDATE Users SET phone_number = :phone_number WHERE id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':phone_number', $phone_number);
        
        return $this->db->execute();
    }

    // Update Profile Email
    public function updateProfileEmail($user_id, $email) {
        $this->db->query("UPDATE Users SET email = :email WHERE id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':email', $email);
        
        return $this->db->execute();
    }

    // Update Profile Password
    public function updateProfilePassword($user_id, $hashed_password) {
        $this->db->query("UPDATE Users SET password = :password WHERE id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':password', $hashed_password);
        
        return $this->db->execute();
    }

    // Update Profile Image
    public function updateProfileImage($user_id, $image_name) {
        $this->db->query("UPDATE Users SET profile_image = :profile_image WHERE id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':profile_image', $image_name);
        
        return $this->db->execute();
    }

    // Get available caretakers for site assignment
    public function getAvailableCaretakers($filters) {
        $sql = "SELECT DISTINCT
                    u.id,
                    u.userID,
                    u.name,
                    u.email,
                    u.phone_number as contact,
                    u.profile_image,
                    ct.city,
                    ct.district,
                    ct.employment_status,
                    (SELECT COUNT(*) FROM caretaker_site_assignments csa 
                     WHERE csa.caretaker_id = u.id AND csa.status = 'Active') as current_assignment_count
                FROM Users u
                INNER JOIN care_taker ct ON u.id = ct.userID
                WHERE u.role = 'caretaker'";

        $params = [];

        // District filter
        if (isset($filters['district_filter'])) {
            if ($filters['district_filter'] === 'same-district' && !empty($filters['district'])) {
                $sql .= " AND ct.district = :district";
                $params[':district'] = $filters['district'];
            }
            // 'all' means no district filter
        }

        // City filter
        if (isset($filters['city_filter'])) {
            if ($filters['city_filter'] === 'same-city' && !empty($filters['city'])) {
                $sql .= " AND ct.city = :city";
                $params[':city'] = $filters['city'];
            }
            // 'all' means no city filter
        }

        // Availability filter - Available means NOT assigned to any site
        if (isset($filters['availability']) && $filters['availability'] === 'available') {
            $sql .= " AND NOT EXISTS (
                        SELECT 1 FROM caretaker_site_assignments csa 
                        WHERE csa.caretaker_id = u.id AND csa.status = 'Active'
                      )";
        }
        // 'all' means show everyone regardless of assignment status

        $sql .= " ORDER BY u.name ASC";

        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    // Assign caretaker to site
    public function assignCaretakerToSite($siteId, $caretakerId, $assignedBy) {
        // Check if caretaker is already assigned to another site
        $this->db->query("SELECT id FROM caretaker_site_assignments 
                          WHERE caretaker_id = :caretaker_id AND status = 'Active'");
        $this->db->bind(':caretaker_id', $caretakerId);
        $existing = $this->db->single();

        if ($existing) {
            return ['success' => false, 'message' => 'Caretaker is already assigned to another site'];
        }

        // Verify the user is actually a caretaker
        $this->db->query("SELECT ct.id FROM care_taker ct
                          INNER JOIN Users u ON ct.userID = u.id
                          WHERE u.id = :user_id AND u.role = 'caretaker'");
        $this->db->bind(':user_id', $caretakerId);
        $caretaker = $this->db->single();

        if (!$caretaker) {
            return ['success' => false, 'message' => 'User is not a valid caretaker'];
        }

        // Create new assignment
        $this->db->query("INSERT INTO caretaker_site_assignments 
                          (site_id, caretaker_id, assignment_start, assigned_by, status) 
                          VALUES (:site_id, :caretaker_id, CURDATE(), :assigned_by, 'Active')");
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':caretaker_id', $caretakerId);
        $this->db->bind(':assigned_by', $assignedBy);

        if ($this->db->execute()) {
            return ['success' => true, 'message' => 'Caretaker assigned successfully'];
        }

        return ['success' => false, 'message' => 'Failed to assign caretaker'];
    }

    /**
     * Get all clients' payments with client and site information
     * @return array
     */
    public function getAllClientsPayments() {
        $this->db->query('
            SELECT 
                p.*,
                u.name as client_name,
                u.email as client_email,
                s.site_name,
                s.address as site_address,
                s.city,
                s.district,
                pr.site_name as package_request_site_name,
                pr.package_name as package_request_package_name
            FROM payments p
            LEFT JOIN Users u ON p.client_id = u.id
            LEFT JOIN sites s ON p.site_id = s.id
            LEFT JOIN package_requests pr ON p.package_request_id = pr.id
            ORDER BY p.created_at DESC, p.payment_date DESC
        ');
        
        $results = $this->db->resultSet();
        return $results ? $results : [];
    }

    /**
     * Get overall payment statistics for all clients
     * @return object
     */
    public function getAllPaymentsStats() {
        $this->db->query('
            SELECT 
                COUNT(*) as total_payments,
                SUM(CASE WHEN status = "paid" THEN amount ELSE 0 END) as total_paid,
                SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = "overdue" THEN 1 ELSE 0 END) as overdue_count,
                SUM(CASE WHEN status = "pending" THEN amount ELSE 0 END) as pending_amount,
                SUM(CASE WHEN status = "overdue" THEN amount ELSE 0 END) as overdue_amount,
                MAX(CASE WHEN status = "paid" THEN payment_date END) as last_payment_date,
                AVG(CASE WHEN status = "paid" THEN amount END) as average_payment,
                SUM(CASE WHEN status = "paid" AND MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE()) THEN amount ELSE 0 END) as this_month_paid,
                COUNT(CASE WHEN status = "paid" AND MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE()) THEN 1 END) as this_month_count
            FROM payments
        ');
        
        $result = $this->db->single();
        
        // Ensure all numeric values are properly formatted
        if ($result) {
            $result->total_paid = floatval($result->total_paid ?? 0);
            $result->pending_amount = floatval($result->pending_amount ?? 0);
            $result->overdue_amount = floatval($result->overdue_amount ?? 0);
            $result->average_payment = floatval($result->average_payment ?? 0);
            $result->this_month_paid = floatval($result->this_month_paid ?? 0);
            $result->paid_count = intval($result->paid_count ?? 0);
            $result->pending_count = intval($result->pending_count ?? 0);
            $result->overdue_count = intval($result->overdue_count ?? 0);
            $result->total_payments = intval($result->total_payments ?? 0);
            $result->this_month_count = intval($result->this_month_count ?? 0);
        }
        
        return $result;
    }

    /**
     * Get payment details by ID (admin view)
     * @param int $payment_id
     * @return object|false
     */
    public function getPaymentDetailsById($payment_id) {
        $this->db->query('
            SELECT 
                p.*,
                u.name as client_name,
                u.email as client_email,
                s.site_name,
                s.address as site_address,
                s.city,
                s.district,
                pr.site_name as package_request_site_name,
                pr.package_name as package_request_package_name,
                pr.package_price as package_request_price
            FROM payments p
            LEFT JOIN Users u ON p.client_id = u.id
            LEFT JOIN sites s ON p.site_id = s.id
            LEFT JOIN package_requests pr ON p.package_request_id = pr.id
            WHERE p.id = :payment_id
        ');
        
        $this->db->bind(':payment_id', $payment_id);
        
        return $this->db->single();
    }

    /**
     * Update payment information (admin)
     * @param int $payment_id
     * @param array $data
     * @return bool
     */
    public function updatePayment($payment_id, $data) {
        $this->db->query('
            UPDATE payments 
            SET 
                amount = :amount,
                status = :status,
                payment_date = :payment_date,
                due_date = :due_date,
                payment_method = :payment_method,
                transaction_reference = :transaction_reference,
                description = :description,
                updated_at = NOW()
            WHERE id = :payment_id
        ');

        $this->db->bind(':payment_id', $payment_id);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':payment_date', $data['payment_date']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':payment_method', $data['payment_method'] ?? null);
        $this->db->bind(':transaction_reference', $data['transaction_reference'] ?? null);
        $this->db->bind(':description', $data['description'] ?? '');

        return $this->db->execute();
    }
}
