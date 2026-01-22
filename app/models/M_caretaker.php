<?php
class M_caretaker {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // ==================== LEAVE REQUESTS ====================

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
        
        return $this->db->execute();
    }

    // READ - Get all leave requests
    public function getLeaveRequests($caretaker_id) {
        $this->db->query('SELECT * FROM leave_requests WHERE caretaker_id = :caretaker_id ORDER BY created_at DESC');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->resultSet();
    }

    // READ - Get single leave request
    public function getLeaveRequestById($id) {
        $this->db->query('SELECT * FROM leave_requests WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // UPDATE - Leave request
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
        
        return $this->db->execute();
    }

    // DELETE - Leave request
    public function deleteLeaveRequest($id, $caretaker_id) {
        $this->db->query('DELETE FROM leave_requests WHERE id = :id AND caretaker_id = :caretaker_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->execute();
    }

    // ==================== NOTES METHODS (RDFC-71) ====================

    public function getNotes($caretaker_id, $filters = []) {
        $query = 'SELECT * FROM caretaker_notes WHERE caretaker_id = :caretaker_id';
        
        if (!empty($filters['category']) && $filters['category'] !== 'All') {
            $query .= ' AND category = :category';
        }
        if (!empty($filters['priority']) && $filters['priority'] !== 'All') {
            $query .= ' AND priority = :priority';
        }
        if (!empty($filters['search'])) {
            $query .= ' AND (title LIKE :search OR note_content LIKE :search)';
        }
        
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

    public function getNoteById($id) {
        $this->db->query('SELECT * FROM caretaker_notes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

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

    public function deleteNote($id, $caretaker_id) {
        $this->db->query('DELETE FROM caretaker_notes WHERE id = :id AND caretaker_id = :caretaker_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->execute();
    }

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

    // ==================== EQUIPMENT REQUESTS (dev branch) ====================

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

    public function getEquipmentRequests($caretaker_id) {
        $this->db->query('
            SELECT * FROM equipment_requests 
            WHERE caretaker_id = :caretaker_id 
            ORDER BY requested_date DESC, created_at DESC
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->resultSet();
    }

    public function getEquipmentRequestById($id) {
        $this->db->query('SELECT * FROM equipment_requests WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

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

    public function deleteEquipmentRequest($id) {
        $this->db->query('DELETE FROM equipment_requests WHERE id = :id AND status = "Pending"');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ==================== DASHBOARD STATISTICS ====================

    /**
     * Get dashboard statistics for caretaker
     * Returns total officers, on duty today, active status, and incidents
     */
    public function getDashboardStats($caretaker_id = null) {
        // Get today's date
        $today = date('Y-m-d');
        
        // Total officers assigned to this caretaker (or all if no caretaker_id)
        $this->db->query('
            SELECT COUNT(DISTINCT officer_id) as total_officers
            FROM officer_attendance
        ');
        $totalResult = $this->db->single();
        $total_officers = $totalResult->total_officers ?? 0;

        // Officers on duty today (Present status)
        $this->db->query('
            SELECT COUNT(DISTINCT officer_id) as on_duty
            FROM officer_attendance
            WHERE attendance_date = :today 
            AND status IN ("Present", "Late", "Half Day")
        ');
        $this->db->bind(':today', $today);
        $onDutyResult = $this->db->single();
        $on_duty = $onDutyResult->on_duty ?? 0;

        // Active officers (those who checked in today and haven't checked out yet or within last 12 hours)
        $this->db->query('
            SELECT COUNT(DISTINCT officer_id) as active
            FROM officer_attendance
            WHERE attendance_date = :today 
            AND status = "Present"
            AND check_in_time IS NOT NULL
        ');
        $this->db->bind(':today', $today);
        $activeResult = $this->db->single();
        $active = $activeResult->active ?? 0;

        // Incidents today (count absent and late as incidents)
        $this->db->query('
            SELECT COUNT(*) as incidents
            FROM officer_attendance
            WHERE attendance_date = :today 
            AND status IN ("Absent", "Late")
        ');
        $this->db->bind(':today', $today);
        $incidentsResult = $this->db->single();
        $incidents = $incidentsResult->incidents ?? 0;

        return [
            'total_officers' => $total_officers,
            'on_duty' => $on_duty,
            'active' => $active,
            'incidents' => $incidents
        ];
    }
}
