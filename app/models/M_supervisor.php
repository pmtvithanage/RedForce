<?php
class M_supervisor {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all leave requests for a supervisor
    public function getLeaveRequests($supervisor_id) {
        $this->db->query("SELECT * FROM leave_requests WHERE caretaker_id = :supervisor_id ORDER BY created_at DESC");
        $this->db->bind(':supervisor_id', $supervisor_id);
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
        $this->db->query("INSERT INTO leave_requests (caretaker_id, leave_type, reason, start_date, end_date, proof_file, status, created_at) 
                         VALUES (:supervisor_id, :leave_type, :reason, :start_date, :end_date, :proof_file, 'Pending', NOW())");
        
        $this->db->bind(':supervisor_id', $data['supervisor_id']);
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
                         WHERE id = :id AND caretaker_id = :supervisor_id");
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':supervisor_id', $data['supervisor_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);
        
        return $this->db->execute();
    }

    // Delete a leave request
    public function deleteLeaveRequest($id, $supervisor_id) {
        $this->db->query("DELETE FROM leave_requests WHERE id = :id AND caretaker_id = :supervisor_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':supervisor_id', $supervisor_id);
        
        return $this->db->execute();
    }

    // Get leave request statistics for supervisor
    public function getLeaveStats($supervisor_id) {
        $this->db->query("SELECT 
                            COUNT(*) as total_requests,
                            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_requests,
                            SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved_requests,
                            SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected_requests
                         FROM leave_requests 
                         WHERE caretaker_id = :supervisor_id");
        $this->db->bind(':supervisor_id', $supervisor_id);
        return $this->db->single();
    }

    // Get recent leave requests for supervisor dashboard
    public function getRecentLeaveRequests($supervisor_id, $limit = 5) {
        $this->db->query("SELECT * FROM leave_requests 
                         WHERE caretaker_id = :supervisor_id 
                         ORDER BY created_at DESC 
                         LIMIT :limit");
        $this->db->bind(':supervisor_id', $supervisor_id);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    // Mark attendance for an officer via QR scanner
    public function markAttendance($officer_id, $supervisor_id, $timestamp) {
        // Check if attendance already marked for today
        $this->db->query("SELECT id FROM attendance 
                         WHERE officer_id = :officer_id 
                         AND DATE(timestamp) = CURDATE()");
        $this->db->bind(':officer_id', $officer_id);
        $existing = $this->db->single();
        
        if ($existing) {
            return 'duplicate'; // Already marked today
        }
        
        // Insert new attendance record
        $this->db->query("INSERT INTO attendance (officer_id, supervisor_id, timestamp, status, created_at) 
                         VALUES (:officer_id, :supervisor_id, :timestamp, 'present', NOW())");
        
        $this->db->bind(':officer_id', $officer_id);
        $this->db->bind(':supervisor_id', $supervisor_id);
        $this->db->bind(':timestamp', $timestamp);
        
        if ($this->db->execute()) {
            return true;
        }
        
        return false;
    }

    // ==========================================
    // OFFICER ATTENDANCE CRUD METHODS
    // ==========================================

    // CREATE - Add new attendance record
    public function addAttendance($data) {
        $this->db->query('INSERT INTO officer_attendance 
                          (supervisor_id, officer_id, officer_name, attendance_date, check_in_time, check_out_time, status, notes) 
                          VALUES (:supervisor_id, :officer_id, :officer_name, :attendance_date, :check_in_time, :check_out_time, :status, :notes)');
        
        $this->db->bind(':supervisor_id', $data['supervisor_id']);
        $this->db->bind(':officer_id', $data['officer_id']);
        $this->db->bind(':officer_name', $data['officer_name']);
        $this->db->bind(':attendance_date', $data['attendance_date']);
        $this->db->bind(':check_in_time', $data['check_in_time']);
        $this->db->bind(':check_out_time', $data['check_out_time']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', $data['notes']);
        
        return $this->db->execute();
    }

    // READ - Get all attendance records with optional filters
    public function getAttendanceRecords($supervisor_id, $filters = []) {
        $query = 'SELECT * FROM officer_attendance WHERE supervisor_id = :supervisor_id';
        
        // Add filters if provided
        if (!empty($filters['date'])) {
            $query .= ' AND attendance_date = :date';
        }
        if (!empty($filters['status'])) {
            $query .= ' AND status = :status';
        }
        if (!empty($filters['officer_id'])) {
            $query .= ' AND officer_id LIKE :officer_id';
        }
        
        $query .= ' ORDER BY attendance_date DESC, check_in_time DESC';
        
        $this->db->query($query);
        $this->db->bind(':supervisor_id', $supervisor_id);
        
        if (!empty($filters['date'])) {
            $this->db->bind(':date', $filters['date']);
        }
        if (!empty($filters['status'])) {
            $this->db->bind(':status', $filters['status']);
        }
        if (!empty($filters['officer_id'])) {
            $this->db->bind(':officer_id', '%' . $filters['officer_id'] . '%');
        }
        
        return $this->db->resultSet();
    }

    // READ - Get single attendance record by ID
    public function getAttendanceById($id) {
        $this->db->query('SELECT * FROM officer_attendance WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // UPDATE - Update existing attendance record
    public function updateAttendance($data) {
        $this->db->query('UPDATE officer_attendance 
                          SET officer_id = :officer_id,
                              officer_name = :officer_name,
                              attendance_date = :attendance_date, 
                              check_in_time = :check_in_time, 
                              check_out_time = :check_out_time, 
                              status = :status, 
                              notes = :notes 
                          WHERE id = :id AND supervisor_id = :supervisor_id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':supervisor_id', $data['supervisor_id']);
        $this->db->bind(':officer_id', $data['officer_id']);
        $this->db->bind(':officer_name', $data['officer_name']);
        $this->db->bind(':attendance_date', $data['attendance_date']);
        $this->db->bind(':check_in_time', $data['check_in_time']);
        $this->db->bind(':check_out_time', $data['check_out_time']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', $data['notes']);
        
        return $this->db->execute();
    }

    // DELETE - Delete attendance record
    public function deleteAttendance($id, $supervisor_id) {
        $this->db->query('DELETE FROM officer_attendance WHERE id = :id AND supervisor_id = :supervisor_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':supervisor_id', $supervisor_id);
        
        return $this->db->execute();
    }

    // READ - Get today's attendance statistics
    public function getAttendanceStats($supervisor_id, $date) {
        $this->db->query('SELECT 
                            COUNT(*) as total_officers,
                            SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present,
                            SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent,
                            SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late,
                            SUM(CASE WHEN status = "Half Day" THEN 1 ELSE 0 END) as half_day
                          FROM officer_attendance 
                          WHERE supervisor_id = :supervisor_id AND attendance_date = :date');
        
        $this->db->bind(':supervisor_id', $supervisor_id);
        $this->db->bind(':date', $date);
        
        return $this->db->single();
    }
}
?>