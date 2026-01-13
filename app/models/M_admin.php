<?php
class M_admin {
    private $db;

    public function __construct() {
        $this->db = new Database();
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
    $this->db->query("SELECT * FROM premise_officers_full_details WHERE premise_officer_id = :id");
    $this->db->bind(':id', $premise_officer_id);
    return $this->db->single();
}

public function getAllMR() {
        // Simple: Get all Users with role 'PO'
    $this->db->query("SELECT * FROM mobile_rider_full_details; ORDER BY user_account_created DESC");
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
            break;
        case 'ct':
            $rolePrefix = 'CT';
            $role_name = 'Care Taker';
            $role_name_data = 'caretaker';
            break;
        case 'mr':
            $rolePrefix = 'MR';
            $role_name = 'Mobile Rider';
            break;
        default:
            $rolePrefix = 'OF';
            $role_name = 'Officer';
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
    $tempPassword = '1234';
    
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
        $this->db->query("
            SELECT 
                u.*, 
                c.contact_person_name,
                u.profile_image as client_profile
            FROM Users u 
            LEFT JOIN Clients c ON u.id = c.user_id 
            WHERE u.id = :id
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
        $tempPassword = '1234'; // Simple temp password
        
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
        
        return $this->db->execute();
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
        $this->db->query("INSERT INTO sites (client_id, site_name, address, city, phone_number, image) 
                        VALUES (:client_id, :site_name, :site_address, :site_city, :phone_number, :image_name)");
        
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':site_name', $data['site_name']);
        $this->db->bind(':site_address', $data['site_address']);
        $this->db->bind(':site_city', $data['site_city']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':image_name', $data['image_name']);
        
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
        $this->db->query("UPDATE sites SET site_name = :site_name, address = :site_address, city = :site_city, phone_number = :phone_number, image = :image_name WHERE id = :site_id");
        $this->db->bind(':site_name', $data['site_name']);
        $this->db->bind(':site_address', $data['site_address']);
        $this->db->bind(':site_city', $data['site_city']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':image_name', $data['image_name']);
        $this->db->bind(':site_id', $data['site_id']);

        if ($this->db->execute()) {
            return true;
        }
        return false;
    }
    // Get All sites
    public function getSiteByClientId($client_id) {
        $this->db->query("SELECT * FROM sites WHERE client_id = :client_id");
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
            (title, image_path, target_roles, created_by, status) 
            VALUES (:title, :image_path, :target_roles, :created_by, :status)
        ');
        $this->db->bind(':title', $data['title']);
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
                image_path = :image_path,
                target_roles = :target_roles,
                status = :status,
                updated_at = NOW()
            WHERE id = :id
        ');
        $this->db->bind(':id', $id);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':target_roles', $data['target_roles']);
        $this->db->bind(':status', $data['status']);
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

    // Add permissions for a user
    public function addUserPermissions($userId, $permissions) {
        if (!empty($permissions) && is_array($permissions)) {
            foreach ($permissions as $permission) {
                $this->db->query("INSERT INTO user_permissions (user_id, permission) VALUES (:user_id, :permission)");
                $this->db->bind(':user_id', $userId);
                $this->db->bind(':permission', $permission);
                $this->db->execute();
            }
            return true;
        }
        return false;
    }

    // Get user by userID
    public function getUserByID($userID) {
        $this->db->query("
            SELECT u.*, ud.nic, ud.mobile, ud.address, ud.additional_info 
            FROM Users u 
            LEFT JOIN user_details ud ON u.id = ud.user_id 
            WHERE u.userID = :userID
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
        //return $this->db->resultSet();
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

    
}
