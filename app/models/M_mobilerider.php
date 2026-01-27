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

    public function getAllNotes()
    {
        $this->db->query("SELECT * FROM notes");
        return $this->db->resultSet();
    }

    public function deleteNoteById($id)
    {
        $this->db->query("DELETE FROM notes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateNoteById($id, $title, $content)
    {
        $this->db->query("UPDATE notes SET title = :title, content = :content WHERE id = :id");
        $this->db->bind(":id", $id);
        $this->db->bind(":title", $title);
        $this->db->bind(":content", $content);
        return $this->db->execute();
    }

    public function addIncident($data)
    {
        // Prepare the SQL query
        $this->db->query("
        INSERT INTO incident_reports 
        (user_id, officer_name, officer_role, property_site, incident_type, 
         incident_date, incident_time, incident_description, action_taken, 
         severity, additional_details, media_files, created_at) 
        VALUES 
        (:user_id, :officer_name, :officer_role, :property_site, :incident_type, 
         :incident_date, :incident_time, :incident_description, :action_taken, 
         :severity, :additional_details, :media_files, NOW())");

        // Bind parameters
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':officer_name', $data['officer_name']);
        $this->db->bind(':officer_role', $data['officer_role']);
        $this->db->bind(':property_site', $data['property_site']);
        $this->db->bind(':incident_type', $data['incident_type']);
        $this->db->bind(':incident_date', $data['incident_date']);
        $this->db->bind(':incident_time', $data['incident_time']);
        $this->db->bind(':incident_description', $data['incident_description']);
        $this->db->bind(':action_taken', $data['action_taken']);
        $this->db->bind(':severity', $data['severity']);
        $this->db->bind(':additional_details', $data['follow_up_id']);
        $this->db->bind(':media_files', $data['media_files']);

        // Execute
        return $this->db->execute();
    }

    public function getAllIncidents()
    {
        $this->db->query("SELECT * FROM incident_reports");
        return $this->db->resultSet();
    }

    public function getIncidentById($id)
    {
        $this->db->query('SELECT * FROM incident_reports WHERE id = :id LIMIT 1');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateIncident($data)
    {
        $this->db->query("
        UPDATE incident_reports
        SET 
            property_site = :property_site,
            incident_type = :incident_type,
            incident_description = :incident_description,
            action_taken = :action_taken,
            severity = :severity,
            media_files = :media_files
        WHERE id = :id
    ");

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':property_site', $data['property_site']);
        $this->db->bind(':incident_type', $data['incident_type']);
        $this->db->bind(':incident_description', $data['incident_description']);
        $this->db->bind(':action_taken', $data['action_taken']);
        $this->db->bind(':severity', $data['severity']);
        $this->db->bind(':media_files', $data['media_files']);

        return $this->db->execute();
    }

    // Get total incident count
    public function getTotalIncidents()
    {
        $this->db->query("SELECT COUNT(*) as total FROM incident_reports");
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Get count by specific severity
    public function getIncidentCountBySeverity($severity)
    {
        $this->db->query("SELECT COUNT(*) as count FROM incident_reports WHERE severity LIKE :severity");
        $this->db->bind(':severity', '%' . $severity . '%');
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Get count by specific status (Open, In Progress, Resolved)
    public function getIncidentCountByStatus($status)
    {
        $this->db->query("SELECT COUNT(*) as count FROM incident_reports WHERE status = :status");
        $this->db->bind(':status', $status);
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Get all incident statistics in one call
    public function getAllIncidentStatistics()
    {
        $total = $this->getTotalIncidents();

        // Count by status if the column exists in the DB. Defaults to 0 when not found.
        $open = $this->getIncidentCountByStatus('Open');
        $progress = $this->getIncidentCountByStatus('In Progress');
        $resolved = $this->getIncidentCountByStatus('Resolved');

        $critical = $this->getIncidentCountBySeverity('critical');
        $high = $this->getIncidentCountBySeverity('high');
        $medium = $this->getIncidentCountBySeverity('medium');
        $low = $this->getIncidentCountBySeverity('low');

        return [
            'total' => $total,
            'open' => $open,
            'progress' => $progress,
            'resolved' => $resolved,
            'critical' => $critical,
            'high' => $high,
            'medium' => $medium,
            'low' => $low
        ];
    }

    public function deleteIncidentById($id)
    {
        $this->db->query('DELETE FROM incident_reports WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Update only the status of an incident
     */
    public function updateIncidentStatus($id, $status)
    {
        $this->db->query('UPDATE incident_reports SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Leave Request CRUD Methods
    
    // Get all leave requests for a mobile rider
    public function getLeaveRequests($mobilerider_id)
    {
        $this->db->query("SELECT * FROM mobile_rider_leave_requests WHERE mobile_rider_id = :mobilerider_id ORDER BY created_at DESC");
        $this->db->bind(':mobilerider_id', $mobilerider_id);
        return $this->db->resultSet();
    }

    // Get a single leave request by ID
    public function getLeaveRequestById($id)
    {
        $this->db->query("SELECT * FROM mobile_rider_leave_requests WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add a new leave request
    public function addLeaveRequest($data)
    {
        $this->db->query("INSERT INTO mobile_rider_leave_requests 
        (mobile_rider_id, leave_type, reason, start_date, end_date, proof_file, status, created_at) 
        VALUES (:mobile_rider_id, :leave_type, :reason, :start_date, :end_date, :proof_file, 'Pending', NOW())");

        $this->db->bind(':mobile_rider_id', $data['mobile_rider_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);

        return $this->db->execute();
    }

    // Update an existing leave request
    public function updateLeaveRequest($data)
    {
        $this->db->query("UPDATE mobile_rider_leave_requests 
        SET leave_type = :leave_type, reason = :reason, start_date = :start_date, 
            end_date = :end_date, proof_file = :proof_file, updated_at = NOW()
        WHERE id = :id AND mobile_rider_id = :mobile_rider_id");

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':mobile_rider_id', $data['mobile_rider_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);

        return $this->db->execute();
    }

    // Delete a leave request
    public function deleteLeaveRequest($id, $mobile_rider_id)
    {
        $this->db->query("DELETE FROM mobile_rider_leave_requests WHERE id = :id AND mobile_rider_id = :mobile_rider_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':mobile_rider_id', $mobile_rider_id);

        return $this->db->execute();
    }

    // Get leave request statistics for mobile rider
    public function getLeaveStats($mobile_rider_id)
    {
        $this->db->query("
        SELECT 
            COUNT(*) AS total_requests,
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_requests,
            SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) AS approved_requests,
            SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) AS rejected_requests
        FROM mobile_rider_leave_requests
        WHERE mobile_rider_id = :mobile_rider_id
    ");
        $this->db->bind(':mobile_rider_id', $mobile_rider_id);
        return $this->db->single();
    }

    // Get recent leave requests for mobile rider dashboard
    public function getRecentLeaveRequests($mobile_rider_id, $limit = 5)
    {
        $this->db->query("
        SELECT * FROM mobile_rider_leave_requests
        WHERE mobile_rider_id = :mobile_rider_id
        ORDER BY created_at DESC
        LIMIT :limit
    ");
        $this->db->bind(':mobile_rider_id', $mobile_rider_id);
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // ==================== MESSAGING METHODS ====================

    // Get all conversations for a user
    public function getConversations($user_id, $limit = 50, $offset = 0)
    {
        $this->db->query("
            SELECT DISTINCT
                CASE 
                    WHEN sender_id = :user_id THEN recipient_id 
                    ELSE sender_id 
                END as other_user_id,
                u.id,
                u.name,
                u.role,
                (SELECT message FROM messages 
                 WHERE (sender_id = :user_id AND recipient_id = u.id) 
                    OR (sender_id = u.id AND recipient_id = :user_id)
                 ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT created_at FROM messages 
                 WHERE (sender_id = :user_id AND recipient_id = u.id) 
                    OR (sender_id = u.id AND recipient_id = :user_id)
                 ORDER BY created_at DESC LIMIT 1) as last_message_time,
                (SELECT COUNT(*) FROM messages 
                 WHERE sender_id = u.id AND recipient_id = :user_id AND is_read = 0) as unread_count
            FROM messages m
            JOIN users u ON (
                (m.sender_id = :user_id AND m.recipient_id = u.id) 
                OR (m.sender_id = u.id AND m.recipient_id = :user_id)
            )
            WHERE m.sender_id = :user_id OR m.recipient_id = :user_id
            GROUP BY other_user_id
            ORDER BY last_message_time DESC
            LIMIT :limit OFFSET :offset
        ");
        
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Get all users except current user (NEW - for starting conversations)
    public function getAllUsers($user_id)
    {
        $this->db->query("
            SELECT id, name, role 
            FROM users 
            WHERE id != :user_id 
            ORDER BY name ASC
        ");
        
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    // Get messages between two users
    public function getMessages($sender_id, $recipient_id, $limit = 50, $offset = 0)
    {
        $this->db->query("
            SELECT * FROM messages
            WHERE (sender_id = :sender_id AND recipient_id = :recipient_id)
                OR (sender_id = :recipient_id AND recipient_id = :sender_id)
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        
        $this->db->bind(':sender_id', $sender_id);
        $this->db->bind(':recipient_id', $recipient_id);
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        
        $results = $this->db->resultSet();
        return array_reverse($results); // Reverse to show oldest first
    }

    // Send a message
    public function sendMessage($sender_id, $recipient_id, $message)
    {
        $this->db->query("
            INSERT INTO messages (sender_id, recipient_id, message, is_read, created_at)
            VALUES (:sender_id, :recipient_id, :message, 0, NOW())
        ");
        
        $this->db->bind(':sender_id', $sender_id);
        $this->db->bind(':recipient_id', $recipient_id);
        $this->db->bind(':message', $message);
        
        return $this->db->execute();
    }

    // Mark messages as read
    public function markAsRead($sender_id, $recipient_id)
    {
        $this->db->query("
            UPDATE messages SET is_read = 1 
            WHERE sender_id = :sender_id AND recipient_id = :recipient_id AND is_read = 0
        ");
        
        $this->db->bind(':sender_id', $sender_id);
        $this->db->bind(':recipient_id', $recipient_id);
        
        return $this->db->execute();
    }

    // Get unread message count for a user
    public function getUnreadCount($user_id)
    {
        $this->db->query("
            SELECT COUNT(*) as count FROM messages 
            WHERE recipient_id = :user_id AND is_read = 0
        ");
        
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        
        return $result->count ?? 0;
    }

    // Search conversations
    public function searchConversations($user_id, $search_term)
    {
        $this->db->query("
            SELECT DISTINCT
                CASE 
                    WHEN sender_id = :user_id THEN recipient_id 
                    ELSE sender_id 
                END as other_user_id,
                u.id,
                u.name,
                u.role
            FROM messages m
            JOIN users u ON (
                (m.sender_id = :user_id AND m.recipient_id = u.id) 
                OR (m.sender_id = u.id AND m.recipient_id = :user_id)
            )
            WHERE (m.sender_id = :user_id OR m.recipient_id = :user_id)
                AND u.name LIKE :search_term
            GROUP BY other_user_id
            ORDER BY u.name ASC
        ");
        
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':search_term', '%' . $search_term . '%');
        
        return $this->db->resultSet();
    }

    // Delete a message
    public function deleteMessage($message_id, $user_id)
    {
        $this->db->query("
            DELETE FROM messages 
            WHERE id = :message_id AND sender_id = :user_id
        ");
        
        $this->db->bind(':message_id', $message_id);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->execute();
    }
}