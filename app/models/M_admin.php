<?php
class M_admin {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

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
        // Simple: Get all users with role 'client'
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
        $this->db->query("INSERT INTO Sites (client_id, site_name, address, city, phone_number, image) 
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
        $this->db->query("DELETE FROM Sites WHERE id = :site_id");
        $this->db->bind(':site_id', $siteId);
        return $this->db->execute();
    }
    public function updateSite($data){
        $this->db->query("UPDATE Sites SET site_name = :site_name, address = :site_address, city = :site_city, phone_number = :phone_number, image = :image_name WHERE id = :site_id");
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
    // Get All Sites
    public function getSiteByClientId($client_id) {
        $this->db->query("SELECT * FROM Sites WHERE client_id = :client_id");
        $this->db->bind(':client_id', $client_id);
        return $this->db->resultSet();
    }
    // Get Site
    public function getSiteById($site_id) {
        $this->db->query("SELECT * FROM Sites WHERE id = :site_id");
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
            LEFT JOIN users u ON a.created_by = u.id 
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
            LEFT JOIN users u ON a.created_by = u.id 
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

            // Insert into users table
            $query = "INSERT INTO users (userID, name, email, password, role, status, created_at) 
                     VALUES (:userID, :name, :email, :password, :role, 'active', NOW())";
            
            $this->db->query($query);
            $this->db->bind(':userID', $data['userID']);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':role', $data['role']);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to insert user into users table');
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
        $this->db->query("SELECT id FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    // Get all admins
    public function getAdmins() {
        $this->db->query("
            SELECT u.userID, u.name, u.email, u.role, u.status, u.created_at, 
                   ud.mobile, ud.nic, ud.address
            FROM users u 
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
            FROM users u 
            LEFT JOIN user_details ud ON u.id = ud.user_id 
            WHERE u.userID = :userID
        ");
        $this->db->bind(':userID', $userID);
        return $this->db->single();
    }

    // Get all users by role
    public function getUsersByRole($role) {
        $this->db->query("
            SELECT u.*, ud.nic, ud.mobile, ud.address 
            FROM users u 
            LEFT JOIN user_details ud ON u.id = ud.user_id 
            WHERE u.role = :role AND u.status = 'active'
            ORDER BY u.name
        ");
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    // Update user status
    public function updateUserStatus($userID, $status) {
        $this->db->query("UPDATE users SET status = :status, updated_at = NOW() WHERE userID = :userID");
        $this->db->bind(':userID', $userID);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    // Delete user
    public function deleteUser($userID) {
        try {
            $this->db->beginTransaction();

            // Get user id first
            $this->db->query("SELECT id FROM users WHERE userID = :userID");
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

            // Delete from users
            $this->db->query("DELETE FROM users WHERE id = :user_id");
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
            LEFT JOIN users u1 ON lr.caretaker_id = u1.id
            LEFT JOIN users u2 ON lr.supervisor_id = u2.id
            LEFT JOIN users u3 ON lr.mobilerider_id = u3.id
            LEFT JOIN users u4 ON lr.premiseofficer_id = u4.id
            WHERE lr.status = 'Pending'
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
            LEFT JOIN users u1 ON lr.caretaker_id = u1.id
            LEFT JOIN users u2 ON lr.supervisor_id = u2.id
            LEFT JOIN users u3 ON lr.mobilerider_id = u3.id
            LEFT JOIN users u4 ON lr.premiseofficer_id = u4.id
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
            JOIN users u ON sr.client_id = u.id
            ORDER BY sr.submitted_date DESC
        ");
        return $this->db->resultSet();
    }

    // Get service request by ID
    public function getServiceRequestById($id) {
        $this->db->query("
            SELECT sr.*, u.name as client_name, u.email as client_email
            FROM service_requests sr
            JOIN users u ON sr.client_id = u.id
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
