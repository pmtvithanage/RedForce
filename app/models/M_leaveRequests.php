<?php
class M_leaveRequests {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get role column name based on role type
     * 
     * @param string $role Role type
     * @return string Column name
     */
    private function getRoleColumn($role = null) {
        if ($role === null) {
            // Try to determine from session
            $role = $_SESSION['role'] ?? 'premise officer';
        }
        
        $roleMap = [
            'supervisor' => 'supervisor_id',
            'caretaker' => 'caretaker_id',
            'mobile rider' => 'mobilerider_id',
            'premise officer' => 'premiseofficer_id'
        ];
        
        return $roleMap[strtolower($role)] ?? 'premiseofficer_id';
    }

    /**
     * Get all leave requests for a specific user
     * 
     * @param int $user_id The user ID
     * @param string $role The user's role (optional, defaults to session role)
     * @return array List of leave requests
     */
    public function getLeaveRequestsByUser($user_id, $role = null) {
        $roleColumn = $this->getRoleColumn($role);
        
        $this->db->query("
            SELECT lr.*, u.name AS requester_name
            FROM leave_requests lr
            LEFT JOIN Users u ON lr.$roleColumn = u.id
            WHERE lr.$roleColumn = :user_id
            ORDER BY lr.created_at DESC
        ");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    /**
     * Get a single leave request by ID
     * 
     * @param int $id Leave request ID
     * @param string $role The user's role (optional)
     * @return object Leave request details
     */
    public function getLeaveRequestById($id, $role = null) {
        $this->db->query('
            SELECT lr.*, 
                COALESCE(u1.name, u2.name, u3.name, u4.name) AS requester_name,
                COALESCE(u1.email, u2.email, u3.email, u4.email) AS requester_email
            FROM leave_requests lr
            LEFT JOIN Users u1 ON lr.supervisor_id = u1.id
            LEFT JOIN Users u2 ON lr.caretaker_id = u2.id
            LEFT JOIN Users u3 ON lr.mobilerider_id = u3.id
            LEFT JOIN Users u4 ON lr.premiseofficer_id = u4.id
            WHERE lr.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Create a new leave request
     * 
     * @param array $data Leave request data (must include role-specific ID key)
     * @return bool Success status
     */
    public function createLeaveRequest($data) {
        // Determine which role ID is provided
        $roleColumn = null;
        $userId = null;
        
        if (isset($data['supervisor_id'])) {
            $roleColumn = 'supervisor_id';
            $userId = $data['supervisor_id'];
        } elseif (isset($data['caretaker_id'])) {
            $roleColumn = 'caretaker_id';
            $userId = $data['caretaker_id'];
        } elseif (isset($data['mobilerider_id'])) {
            $roleColumn = 'mobilerider_id';
            $userId = $data['mobilerider_id'];
        } elseif (isset($data['premiseofficer_id'])) {
            $roleColumn = 'premiseofficer_id';
            $userId = $data['premiseofficer_id'];
        }
        
        if (!$roleColumn) {
            return false;
        }
        
        $this->db->query("
            INSERT INTO leave_requests 
            ($roleColumn, leave_type, reason, start_date, end_date, proof_file, status, created_at) 
            VALUES (:user_id, :leave_type, :reason, :start_date, :end_date, :proof_file, :status, NOW())
        ");
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file'] ?? null);
        $this->db->bind(':status', $data['status'] ?? 'Pending');
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update an existing leave request
     * 
     * @param int $id Leave request ID
     * @param array $data Updated data
     * @param int $user_id User ID for verification
     * @param string $role User's role (optional)
     * @return bool Success status
     */
    public function updateLeaveRequest($id, $data, $user_id, $role = null) {
        $roleColumn = $this->getRoleColumn($role);
        
        $this->db->query("
            UPDATE leave_requests 
            SET leave_type = :leave_type, 
                reason = :reason, 
                start_date = :start_date, 
                end_date = :end_date, 
                proof_file = :proof_file, 
                updated_at = NOW()
            WHERE id = :id AND $roleColumn = :user_id AND status = 'Pending'
        ");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file'] ?? null);
        
        return $this->db->execute();
    }

    /**
     * Delete a leave request (only if pending)
     * 
     * @param int $id Leave request ID
     * @param int $user_id User ID for verification
     * @param string $role User's role (optional)
     * @return bool Success status
     */
    public function deleteLeaveRequest($id, $user_id, $role = null) {
        $roleColumn = $this->getRoleColumn($role);
        
        $this->db->query("
            DELETE FROM leave_requests 
            WHERE id = :id AND $roleColumn = :user_id AND status = 'Pending'
        ");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->execute();
    }

    /**
     * Get leave request statistics for a user
     * 
     * @param int $user_id User ID
     * @param string $role User's role (optional)
     * @return object Statistics
     */
    public function getLeaveStats($user_id, $role = null) {
        $roleColumn = $this->getRoleColumn($role);
        
        $this->db->query("
            SELECT 
                COUNT(*) as total_requests,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_requests,
                SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved_requests,
                SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected_requests
            FROM leave_requests 
            WHERE $roleColumn = :user_id
        ");
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }

    /**
     * Check if leave request belongs to user
     * 
     * @param int $id Leave request ID
     * @param int $user_id User ID
     * @param string $role User's role (optional)
     * @return bool
     */
    public function isOwnedByUser($id, $user_id, $role = null) {
        $roleColumn = $this->getRoleColumn($role);
        
        $this->db->query("
            SELECT id FROM leave_requests 
            WHERE id = :id AND $roleColumn = :user_id
        ");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->single() ? true : false;
    }
}
?>