<?php
class M_mobilerider
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addNote($data)
    {
        $this->db->query("INSERT INTO notes ( userID, title, content, created_at) VALUES (:userID, :title, :content, NOW())");

        // Bind values
        $this->db->bind(":userID", $data['userID']);
        $this->db->bind(":title", $data['title']);
        $this->db->bind(":content", $data['content']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllNotes() {
        $this->db->query("SELECT * FROM notes");
        return $this->db->resultSet();
    }

   public function deleteNoteById($id) {
    $this->db->query("DELETE FROM notes WHERE id = :id");
    $this->db->bind(':id', $id);
    return $this->db->execute();
}

public function updateNoteById($id, $title, $content) {
    $this->db->query("UPDATE notes SET title = :title, content = :content WHERE id = :id");
    $this->db->bind(":id", $id);
    $this->db->bind(":title", $title);
    $this->db->bind(":content", $content);
    return $this->db->execute();
}

    // Leave Request CRUD Methods
    
    // Get all leave requests for a mobile rider
    public function getLeaveRequests($mobilerider_id) {
        $this->db->query("SELECT * FROM leave_requests WHERE mobilerider_id = :mobilerider_id ORDER BY created_at DESC");
        $this->db->bind(':mobilerider_id', $mobilerider_id);
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
        $this->db->query("INSERT INTO leave_requests (mobilerider_id, leave_type, reason, start_date, end_date, proof_file, status, created_at) 
                         VALUES (:mobilerider_id, :leave_type, :reason, :start_date, :end_date, :proof_file, 'Pending', NOW())");
        
        $this->db->bind(':mobilerider_id', $data['mobilerider_id']);
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
                         WHERE id = :id AND mobilerider_id = :mobilerider_id");
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':mobilerider_id', $data['mobilerider_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);
        
        return $this->db->execute();
    }

    // Delete a leave request
    public function deleteLeaveRequest($id, $mobilerider_id) {
        $this->db->query("DELETE FROM leave_requests WHERE id = :id AND mobilerider_id = :mobilerider_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':mobilerider_id', $mobilerider_id);
        
        return $this->db->execute();
    }

    // Get leave request statistics for mobile rider
    public function getLeaveStats($mobilerider_id) {
        $this->db->query("SELECT 
                            COUNT(*) as total_requests,
                            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_requests,
                            SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved_requests,
                            SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected_requests
                         FROM leave_requests 
                         WHERE mobilerider_id = :mobilerider_id");
        $this->db->bind(':mobilerider_id', $mobilerider_id);
        return $this->db->single();
    }

    // Get recent leave requests for mobile rider dashboard
    public function getRecentLeaveRequests($mobilerider_id, $limit = 5) {
        $this->db->query("SELECT * FROM leave_requests 
                         WHERE mobilerider_id = :mobilerider_id 
                         ORDER BY created_at DESC 
                         LIMIT :limit");
        $this->db->bind(':mobilerider_id', $mobilerider_id);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

}
