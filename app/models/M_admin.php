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
    // Leave Request Management
    // ==============================
    
    // Get all pending leave requests
    public function getPendingLeaveRequests() {
        $this->db->query("
            SELECT lr.*, u.name as caretaker_name, u.email as caretaker_email
            FROM leave_requests lr
            JOIN users u ON lr.caretaker_id = u.id
            WHERE lr.status = 'Pending'
            ORDER BY lr.created_at DESC
        ");
        return $this->db->resultSet();
    }

    // Get leave request by ID
    public function getLeaveRequestById($id) {
        $this->db->query("
            SELECT lr.*, u.name as caretaker_name, u.email as caretaker_email
            FROM leave_requests lr
            JOIN users u ON lr.caretaker_id = u.id
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