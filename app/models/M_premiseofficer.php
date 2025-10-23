<?php
class M_premiseofficer {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Leave Request CRUD Methods
    
    // Get all leave requests for a premise officer
    public function getLeaveRequests($premiseofficer_id) {
        $this->db->query("SELECT * FROM leave_requests WHERE premiseofficer_id = :premiseofficer_id ORDER BY created_at DESC");
        $this->db->bind(':premiseofficer_id', $premiseofficer_id);
        return $this->db->resultSet();
    }

    // Get a single leave request by ID
    public function getLeaveRequestById($id) {
        $this->db->query("SELECT * FROM leave_requests WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add a new leave request
    public function addLeaveRequest($data) {
        $this->db->query("INSERT INTO leave_requests (premiseofficer_id, leave_type, reason, start_date, end_date, proof_file, status, created_at) 
                         VALUES (:premiseofficer_id, :leave_type, :reason, :start_date, :end_date, :proof_file, 'Pending', NOW())");
        
        $this->db->bind(':premiseofficer_id', $data['premiseofficer_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);
        
        return $this->db->execute();
    }

    // Update an existing leave request
    public function updateLeaveRequest($data) {
        $this->db->query("UPDATE leave_requests 
                         SET leave_type = :leave_type, reason = :reason, start_date = :start_date, 
                             end_date = :end_date, proof_file = :proof_file, updated_at = NOW()
                         WHERE id = :id AND premiseofficer_id = :premiseofficer_id");
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':premiseofficer_id', $data['premiseofficer_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);
        
        return $this->db->execute();
    }

    // Delete a leave request
    public function deleteLeaveRequest($id, $premiseofficer_id) {
        $this->db->query("DELETE FROM leave_requests WHERE id = :id AND premiseofficer_id = :premiseofficer_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':premiseofficer_id', $premiseofficer_id);
        
        return $this->db->execute();
    }

    // Get leave request statistics for premise officer
    public function getLeaveStats($premiseofficer_id) {
        $this->db->query("SELECT 
                            COUNT(*) as total_requests,
                            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_requests,
                            SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved_requests,
                            SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected_requests
                         FROM leave_requests 
                         WHERE premiseofficer_id = :premiseofficer_id");
        $this->db->bind(':premiseofficer_id', $premiseofficer_id);
        return $this->db->single();
    }

    // Get recent leave requests for premise officer dashboard
    public function getRecentLeaveRequests($premiseofficer_id, $limit = 5) {
        $this->db->query("SELECT * FROM leave_requests 
                         WHERE premiseofficer_id = :premiseofficer_id 
                         ORDER BY created_at DESC 
                         LIMIT :limit");
        $this->db->bind(':premiseofficer_id', $premiseofficer_id);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    
}