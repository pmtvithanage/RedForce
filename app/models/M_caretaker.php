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

    // ==================== NOTES METHODS ====================

    // Get all notes for a caretaker with filters
    public function getNotes($caretaker_id, $filters = []) {
        $query = 'SELECT * FROM caretaker_notes WHERE caretaker_id = :caretaker_id';
        
        // Add filters
        if (!empty($filters['category']) && $filters['category'] !== 'All') {
            $query .= ' AND category = :category';
        }
        if (!empty($filters['priority']) && $filters['priority'] !== 'All') {
            $query .= ' AND priority = :priority';
        }
        if (!empty($filters['search'])) {
            $query .= ' AND (title LIKE :search OR note_content LIKE :search)';
        }
        
        // Order by pinned first, then by date
        $query .= ' ORDER BY is_pinned DESC, created_at DESC';
        
        $this->db->query($query);
        $this->db->bind(':caretaker_id', $caretaker_id);
        
        if (!empty($filters['category']) && $filters['category'] !== 'All') {
            $this->db->bind(':category', $filters['category']);
        }
        if (!empty($filters['priority']) && $filters['priority'] !== 'All') {
            $this->db->bind(':priority', $filters['priority']);
        }
        if (!empty($filters['search'])) {
            $this->db->bind(':search', '%' . $filters['search'] . '%');
        }
        
        return $this->db->resultSet();
    }

    // Get single note by ID
    public function getNoteById($id) {
        $this->db->query('SELECT * FROM caretaker_notes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get notes statistics
    public function getNotesStats($caretaker_id) {
        $this->db->query('
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN category = "Important" THEN 1 ELSE 0 END) as important,
                SUM(CASE WHEN priority = "High" THEN 1 ELSE 0 END) as highPriority,
                SUM(CASE WHEN reminder_date IS NOT NULL AND reminder_date >= CURDATE() AND is_completed = 0 THEN 1 ELSE 0 END) as pendingReminders
            FROM caretaker_notes 
            WHERE caretaker_id = :caretaker_id
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->single();
    }

    // Add new note
    public function addNote($data) {
        $this->db->query('
            INSERT INTO caretaker_notes 
            (caretaker_id, title, note_content, category, priority, reminder_date) 
            VALUES 
            (:caretaker_id, :title, :note_content, :category, :priority, :reminder_date)
        ');
        
        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':note_content', $data['note_content']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':reminder_date', $data['reminder_date'] ?? null);
        
        return $this->db->execute();
    }

    // Update note
    public function updateNote($data) {
        $this->db->query('
            UPDATE caretaker_notes 
            SET title = :title,
                note_content = :note_content,
                category = :category,
                priority = :priority,
                reminder_date = :reminder_date
            WHERE id = :id AND caretaker_id = :caretaker_id
        ');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':note_content', $data['note_content']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':reminder_date', $data['reminder_date'] ?? null);
        
        return $this->db->execute();
    }

    // Delete note
    public function deleteNote($id, $caretaker_id) {
        $this->db->query('DELETE FROM caretaker_notes WHERE id = :id AND caretaker_id = :caretaker_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->execute();
    }

    // Toggle pin status
    public function togglePin($id, $caretaker_id) {
        $this->db->query('
            UPDATE caretaker_notes 
            SET is_pinned = NOT is_pinned 
            WHERE id = :id AND caretaker_id = :caretaker_id
        ');
        $this->db->bind(':id', $id);
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->execute();
    }
}