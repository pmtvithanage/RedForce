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

    // Get recent activities for premise officer dashboard
    public function getRecentActivities($premiseofficer_id, $limit = 10) {
        $this->db->query('
            SELECT 
                activity_type,
                activity_titel,
                activity_details,
                created_at
            FROM recent_activities
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':user_id', $premiseofficer_id, PDO::PARAM_INT);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Insert recent activity for premise officer
    public function insertRecentActivity($userId, $title, $description, $type) {
        $this->db->query('
            INSERT INTO recent_activities (user_id, activity_titel, activity_details, activity_type) 
            VALUES (:user_id, :title, :description, :type)
        ');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':title', $title);
        $this->db->bind(':description', $description);
        $this->db->bind(':type', $type);
        
        return $this->db->execute();
    }
    
    // Get active site assignments for a premise officer
    public function getActiveAssignments($premiseofficer_id) {
        $this->db->query("SELECT 
                            osa.id,
                            osa.site_id,
                            osa.shift_type,
                            osa.assignment_start,
                            osa.assignment_end,
                            osa.notes,
                            s.site_name,
                            s.address,
                            s.city,
                            c.contact_person_name
                         FROM officer_site_assignments osa
                         INNER JOIN sites s ON osa.site_id = s.id
                         LEFT JOIN Clients c ON s.client_id = c.id
                         WHERE osa.officer_id = :premiseofficer_id 
                         AND osa.status = 'Active'
                         AND s.is_draft = 0
                         ORDER BY osa.assignment_start DESC");
        $this->db->bind(':premiseofficer_id', $premiseofficer_id);
        return $this->db->resultSet();
    }
    
    // Get approved leave dates for a premise officer
    public function getApprovedLeaveDates($premiseofficer_id) {
        $this->db->query("SELECT start_date, end_date, leave_type, reason 
                         FROM leave_requests 
                         WHERE premiseofficer_id = :premiseofficer_id 
                         AND status = 'Approved'
                         ORDER BY start_date ASC");
        $this->db->bind(':premiseofficer_id', $premiseofficer_id);
        return $this->db->resultSet();
    }
    
    // Get shift details for a specific date
    public function getShiftDetailsForDate($premiseofficer_id, $date) {
        $this->db->query("SELECT 
                            osa.id,
                            osa.shift_type,
                            osa.assignment_start,
                            osa.assignment_end,
                            osa.notes,
                            s.site_name,
                            s.address,
                            s.city,
                            s.phone_number,
                            c.contact_person_name
                         FROM officer_site_assignments osa
                         INNER JOIN sites s ON osa.site_id = s.id
                         LEFT JOIN Clients c ON s.client_id = c.id
                         WHERE osa.officer_id = :premiseofficer_id 
                         AND osa.status = 'Active'
                         AND s.is_draft = 0
                         AND :date BETWEEN osa.assignment_start 
                         AND IFNULL(osa.assignment_end, '2099-12-31')");
        $this->db->bind(':premiseofficer_id', $premiseofficer_id);
        $this->db->bind(':date', $date);
        return $this->db->single();
    }
    
    // Check if a specific date is a leave day for the premise officer
    public function getLeaveForDate($premiseofficer_id, $date) {
        $this->db->query("SELECT leave_type, reason 
                         FROM leave_requests 
                         WHERE premiseofficer_id = :premiseofficer_id 
                         AND :date BETWEEN start_date AND end_date
                         AND status = 'Approved'");
        $this->db->bind(':premiseofficer_id', $premiseofficer_id);
        $this->db->bind(':date', $date);
        return $this->db->single();
    }

// ======================================================================== //
// =======================      profile       ====================== //
// ======================================================================== //
    public function getPremiseOfficerById($userID) {
        $this->db->query("SELECT * FROM Users WHERE userID = :userID");
        $this->db->bind(':userID', $userID);
        return $this->db->single();
    }
    

     public function updatePremiseOfficerProfile($user_id, $data) {
        $fields = [];
        $bindings = [];
        
        // Only update fields that are provided
        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $bindings[':name'] = $data['name'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $bindings[':email'] = $data['email'];
        }
        if (isset($data['phone_number'])) {
            $fields[] = 'phone_number = :phone_number';
            $bindings[':phone_number'] = $data['phone_number'];
        }
        if (isset($data['profile_image'])) {
            $fields[] = 'profile_image = :profile_image';
            $bindings[':profile_image'] = $data['profile_image'];
        }
        if (isset($data['password'])) {
            $fields[] = 'password = :password';
            $bindings[':password'] = $data['password'];
        }
        
        if (empty($fields)) {
            return false; // Nothing to update
        }
        
        $query = 'UPDATE Users SET ' . implode(', ', $fields) . ' WHERE id = :user_id';
        $this->db->query($query);
        
        $this->db->bind(':user_id', $user_id);
        foreach ($bindings as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        return $this->db->execute();
    }

}