<?php
class M_caretaker {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // CREATE - Add new leave request
    public function addLeaveRequest($data) {
        $this->db->query('INSERT INTO leave_requests (caretaker_id, leave_type, reason, start_date, end_date, proof_file) 
                          VALUES (:caretaker_id, :leave_type, :reason, :start_date, :end_date, :proof_file)');
        
        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // READ - Get all leave requests for a caretaker
    public function getLeaveRequests($caretaker_id) {
        $this->db->query('SELECT * FROM leave_requests WHERE caretaker_id = :caretaker_id ORDER BY created_at DESC');
        $this->db->bind(':caretaker_id', $caretaker_id);
        
        $results = $this->db->resultSet();
        return $results;
    }

    // READ - Get single leave request by ID
    public function getLeaveRequestById($id) {
        $this->db->query('SELECT * FROM leave_requests WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        return $row;
    }

    // UPDATE - Update existing leave request
    public function updateLeaveRequest($data) {
        $this->db->query('UPDATE leave_requests 
                          SET leave_type = :leave_type, 
                              reason = :reason, 
                              start_date = :start_date, 
                              end_date = :end_date, 
                              proof_file = :proof_file 
                          WHERE id = :id AND caretaker_id = :caretaker_id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // DELETE - Delete leave request
    public function deleteLeaveRequest($id, $caretaker_id) {
        $this->db->query('DELETE FROM leave_requests WHERE id = :id AND caretaker_id = :caretaker_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':caretaker_id', $caretaker_id);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // ==================== EQUIPMENT REQUESTS METHODS ====================

    // CREATE - Add new equipment request
    public function addEquipmentRequest($data) {
        $this->db->query('
            INSERT INTO equipment_requests 
            (caretaker_id, equipment_name, quantity, estimated_cost, reason, priority, requested_date, status) 
            VALUES 
            (:caretaker_id, :equipment_name, :quantity, :estimated_cost, :reason, :priority, :requested_date, "Pending")
        ');
        
        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':equipment_name', $data['equipment_name']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':estimated_cost', $data['estimated_cost']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':requested_date', $data['requested_date']);
        
        return $this->db->execute();
    }

    // READ - Get all equipment requests for a caretaker
    public function getEquipmentRequests($caretaker_id) {
        $this->db->query('
            SELECT * FROM equipment_requests 
            WHERE caretaker_id = :caretaker_id 
            ORDER BY requested_date DESC, created_at DESC
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->resultSet();
    }

    // READ - Get single equipment request by ID
    public function getEquipmentRequestById($id) {
        $this->db->query('SELECT * FROM equipment_requests WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // READ - Get equipment request statistics for caretaker
    public function getEquipmentStats($caretaker_id) {
        $this->db->query('
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status = "Pending" THEN total_cost ELSE 0 END) as pending_cost,
                SUM(CASE WHEN status = "Approved" THEN total_cost ELSE 0 END) as approved_cost
            FROM equipment_requests 
            WHERE caretaker_id = :caretaker_id
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->single();
    }

    // UPDATE - Update equipment request (only if Pending)
    public function updateEquipmentRequest($data) {
        $this->db->query('
            UPDATE equipment_requests 
            SET equipment_name = :equipment_name,
                quantity = :quantity,
                estimated_cost = :estimated_cost,
                reason = :reason,
                priority = :priority
            WHERE id = :id AND status = "Pending"
        ');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':equipment_name', $data['equipment_name']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':estimated_cost', $data['estimated_cost']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':priority', $data['priority']);
        
        return $this->db->execute();
    }

    // DELETE - Delete equipment request (only if Pending)
    public function deleteEquipmentRequest($id) {
        $this->db->query('DELETE FROM equipment_requests WHERE id = :id AND status = "Pending"');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}