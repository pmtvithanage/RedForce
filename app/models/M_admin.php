<?php
class M_admin {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

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
                throw new Exception('Failed to insert user');
            }

            $userID = $this->db->lastInsertId();

            // Insert into user_details table
            $this->insertUserDetails($userID, $data);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("User creation error: " . $e->getMessage());
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
        
        return $this->db->execute();
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