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
}