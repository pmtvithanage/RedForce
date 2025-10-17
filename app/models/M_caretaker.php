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
}