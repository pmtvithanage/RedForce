<?php
class M_message {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get all conversations for a user
     * Returns list of users with their last message
     */
    public function getConversations($user_id) {
        $this->db->query("
            SELECT 
                u.id,
                u.name,
                u.email,
                CASE 
                    WHEN u.role = 'premise officer' AND po.rank = 'Supervisor' THEN 'Supervisor'
                    ELSE u.role
                END as role,
                u.profile_image,
                m.message as last_message,
                m.created_at as last_message_time,
                m.is_read,
                (SELECT COUNT(*) FROM messages 
                 WHERE recipient_id = :user_id1 
                 AND sender_id = u.id 
                 AND is_read = 0) as unread_count
            FROM Users u
            LEFT JOIN premise_officers po ON po.userID = u.id
            INNER JOIN messages m ON (
                (m.sender_id = u.id AND m.recipient_id = :user_id2) OR
                (m.recipient_id = u.id AND m.sender_id = :user_id3)
            )
            WHERE u.id != :user_id4
            AND m.created_at = (
                SELECT MAX(created_at)
                FROM messages
                WHERE (sender_id = u.id AND recipient_id = :user_id5)
                   OR (sender_id = :user_id6 AND recipient_id = u.id)
            )
            ORDER BY m.created_at DESC
        ");
        
        $this->db->bind(':user_id1', $user_id);
        $this->db->bind(':user_id2', $user_id);
        $this->db->bind(':user_id3', $user_id);
        $this->db->bind(':user_id4', $user_id);
        $this->db->bind(':user_id5', $user_id);
        $this->db->bind(':user_id6', $user_id);
        
        return $this->db->resultSet();
    }

    /**
     * Get all users except the current user
     * Used for starting new conversations
     */
    public function getAllUsers($current_user_id) {
        $this->db->query("
            SELECT 
                u.id, 
                u.name, 
                u.email, 
                CASE 
                    WHEN u.role = 'premise officer' AND po.rank = 'Supervisor' THEN 'Supervisor'
                    ELSE u.role
                END as role,
                u.profile_image
            FROM Users u
            LEFT JOIN premise_officers po ON po.userID = u.id
            WHERE u.id != :user_id
            AND u.role != 'admin'
            ORDER BY u.name ASC
        ");
        
        $this->db->bind(':user_id', $current_user_id);
        return $this->db->resultSet();
    }

    /**
     * Get messages between two users
     */
    public function getMessages($user_id, $other_user_id) {
        $this->db->query("
            SELECT 
                m.*,
                sender.name as sender_name,
                recipient.name as recipient_name
            FROM messages m
            LEFT JOIN Users sender ON m.sender_id = sender.id
            LEFT JOIN Users recipient ON m.recipient_id = recipient.id
            WHERE 
                (m.sender_id = :user_id1 AND m.recipient_id = :other_user_id1) OR
                (m.sender_id = :other_user_id2 AND m.recipient_id = :user_id2)
            ORDER BY m.created_at ASC
        ");
        
        $this->db->bind(':user_id1', $user_id);
        $this->db->bind(':user_id2', $user_id);
        $this->db->bind(':other_user_id1', $other_user_id);
        $this->db->bind(':other_user_id2', $other_user_id);
        
        return $this->db->resultSet();
    }

    /**
     * Send a message
     */
    public function sendMessage($sender_id, $recipient_id, $message) {
        $this->db->query("
            INSERT INTO messages (sender_id, recipient_id, message, is_read, created_at)
            VALUES (:sender_id, :recipient_id, :message, 0, NOW())
        ");
        
        $this->db->bind(':sender_id', $sender_id);
        $this->db->bind(':recipient_id', $recipient_id);
        $this->db->bind(':message', $message);
        
        $result = $this->db->execute();
        
        // Send notification to recipient
        if ($result) {
            $this->sendMessageNotification($sender_id, $recipient_id, $message);
        }
        
        return $result;
    }
    
    /**
     * Send notification to recipient about new message
     */
    private function sendMessageNotification($sender_id, $recipient_id, $message) {
        // Get sender information
        $this->db->query("SELECT name FROM Users WHERE id = :sender_id");
        $this->db->bind(':sender_id', $sender_id);
        $sender = $this->db->single();
        
        // Get recipient information for link generation
        $this->db->query("SELECT role FROM Users WHERE id = :recipient_id");
        $this->db->bind(':recipient_id', $recipient_id);
        $recipient = $this->db->single();
        
        if ($sender && $recipient) {
            $senderName = $sender->name;
            
            // Convert role to controller name (same logic as in messages.php)
            $recipientRole = $recipient->role;
            $role = str_replace(' ', '', ucwords($recipientRole));
            
            // Truncate message for preview
            $messagePreview = strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message;
            
            // Load and create notification
            if (!class_exists('M_notifications')) {
                require_once '../app/models/M_notifications.php';
            }
            $notificationModel = new M_notifications();
            $notificationModel->addNotification(
                $recipient_id,
                'info',
                'New Message from ' . $senderName,
                $messagePreview,
                URL_ROOT . '/' . $role . '/messages',
                'message',
                $sender_id
            );
        }
    }

    /**
     * Mark messages as read (delivered)
     */
    public function markAsRead($user_id, $other_user_id) {
        $this->db->query("
            UPDATE messages
            SET is_read = 1
            WHERE recipient_id = :user_id
            AND sender_id = :other_user_id
            AND is_deleted = 0
            AND is_read = 0
        ");
        
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':other_user_id', $other_user_id);
        
        return $this->db->execute();
    }

    /**
     * Mark messages as seen (set read_at timestamp)
     */
    public function markAsSeen($user_id, $other_user_id) {
        $this->db->query("
            UPDATE messages
            SET read_at = COALESCE(read_at, NOW())
            WHERE recipient_id = :user_id
            AND sender_id = :other_user_id
            AND is_deleted = 0
            AND is_read = 1
            AND read_at IS NULL
        ");
        
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':other_user_id', $other_user_id);
        
        return $this->db->execute();
    }

    /**
     * Get unread message count
     */
    public function getUnreadCount($user_id) {
        $this->db->query("
            SELECT COUNT(*) as count
            FROM messages
            WHERE recipient_id = :user_id
            AND is_read = 0
        ");
        
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        
        return $result ? $result->count : 0;
    }

    /**
     * Search conversations by user name
     */
    public function searchConversations($user_id, $search_term) {
        $this->db->query("
            SELECT DISTINCT
                u.id,
                u.name,
                u.email,
                u.role,
                u.profile_image
            FROM Users u
            INNER JOIN messages m ON (
                (m.sender_id = u.id AND m.recipient_id = :user_id1) OR
                (m.recipient_id = u.id AND m.sender_id = :user_id2)
            )
            WHERE u.id != :user_id3
            AND u.name LIKE :search_term
            ORDER BY u.name ASC
        ");
        
        $this->db->bind(':user_id1', $user_id);
        $this->db->bind(':user_id2', $user_id);
        $this->db->bind(':user_id3', $user_id);
        $this->db->bind(':search_term', '%' . $search_term . '%');
        
        return $this->db->resultSet();
    }

    /**
     * Delete a message
     * Only allows deleting own messages
     */
    public function deleteMessage($message_id, $user_id) {
        $this->db->query("
            UPDATE messages
            SET is_deleted = 1, message = 'This message was deleted', updated_at = NOW()
            WHERE id = :message_id
            AND sender_id = :user_id
        ");
        
        $this->db->bind(':message_id', $message_id);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->execute();
    }

    /**
     * Update a message
     * Only allows updating own messages
     */
    public function updateMessage($message_id, $user_id, $message) {
        $this->db->query("
            UPDATE messages
            SET message = :message, is_edited = 1, updated_at = NOW()
            WHERE id = :message_id
            AND sender_id = :user_id
        ");
        
        $this->db->bind(':message', $message);
        $this->db->bind(':message_id', $message_id);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->execute();
    }

    /**
     * Get a single message by ID
     */
    public function getMessageById($message_id) {
        $this->db->query("
            SELECT * FROM messages
            WHERE id = :message_id
        ");
        
        $this->db->bind(':message_id', $message_id);
        return $this->db->single();
    }

    /**
     * Get conversation statistics
     */
    public function getConversationStats($user_id, $other_user_id) {
        $this->db->query("
            SELECT 
                COUNT(*) as total_messages,
                SUM(CASE WHEN is_read = 0 AND recipient_id = :user_id THEN 1 ELSE 0 END) as unread_messages,
                MAX(created_at) as last_message_time
            FROM messages
            WHERE 
                (sender_id = :user_id1 AND recipient_id = :other_user_id1) OR
                (sender_id = :other_user_id2 AND recipient_id = :user_id2)
        ");
        
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':user_id1', $user_id);
        $this->db->bind(':user_id2', $user_id);
        $this->db->bind(':other_user_id1', $other_user_id);
        $this->db->bind(':other_user_id2', $other_user_id);
        
        return $this->db->single();
    }

    /**
     * Get allowed users for mobile riders to message
     * Mobile riders can only message:
     * 1. Admins (role = 'admin')
     * 2. Supervisors assigned to sites in the mobile rider's route
     */
    public function getAllUsersForMobileRider($mobile_rider_user_id) {
        $this->db->query("
            SELECT DISTINCT
                u.id,
                u.name,
                u.email,
                CASE 
                    WHEN u.role = 'premise officer' AND po.rank = 'Supervisor' THEN 'Supervisor'
                    ELSE u.role
                END as role,
                u.profile_image
            FROM Users u
            LEFT JOIN premise_officers po ON po.userID = u.id
            WHERE 
                -- Admins
                u.role = 'admin'
                OR
                -- Supervisors assigned to sites in the mobile rider's route
                (
                    u.role = 'premise officer'
                    AND po.rank = 'Supervisor'
                    AND u.id IN (
                        SELECT DISTINCT osa.officer_id
                        FROM routes r
                        INNER JOIN route_sites rs ON r.id = rs.route_id
                        INNER JOIN officer_site_assignments osa ON rs.site_id = osa.site_id
                        WHERE r.assigned_rider_id = :user_id
                        AND osa.shift_type = 'Supervisor'
                        AND osa.status = 'Active'
                    )
                )
            AND u.id != :user_id2
            ORDER BY 
                CASE WHEN u.role = 'admin' THEN 0 ELSE 1 END,
                u.name ASC
        ");
        
        $this->db->bind(':user_id', $mobile_rider_user_id);
        $this->db->bind(':user_id2', $mobile_rider_user_id);
        
        return $this->db->resultSet();
    }

    /**
     * Get all messageable users for a supervisor
     * Returns: admins + all officers in supervisor's assigned site
     */
    public function getAllUsersForSupervisor($supervisor_user_id) {
        $this->db->query("
            SELECT DISTINCT
                u.id,
                u.name,
                u.email,
                u.phone_number,
                u.profile_image,
                CASE 
                    WHEN u.role = 'premise officer' AND po.rank = 'Supervisor' THEN 'Supervisor'
                    ELSE u.role
                END as role
            FROM Users u
            LEFT JOIN premise_officers po ON po.userID = u.id
            WHERE 
                (u.role = 'admin')
                OR 
                (u.role = 'premise officer' AND u.id IN (
                    SELECT officer_id FROM officer_site_assignments 
                    WHERE site_id = (
                        SELECT site_id FROM officer_site_assignments 
                        WHERE officer_id = :supervisor_id AND shift_type = 'Supervisor' LIMIT 1
                    )
                ))
            AND u.id != :supervisor_id2
            ORDER BY u.role DESC, u.name ASC
        ");
        
        $this->db->bind(':supervisor_id', $supervisor_user_id);
        $this->db->bind(':supervisor_id2', $supervisor_user_id);
        
        return $this->db->resultSet();
    }

    /**
     * Get all messageable users for a caretaker
     * Returns: admins + all supervisors assigned to the caretaker's site
     */
    public function getAllUsersForCaretaker($caretaker_user_id) {
        $this->db->query("
            SELECT DISTINCT
                u.id,
                u.name,
                u.email,
                u.phone_number,
                u.profile_image,
                CASE 
                    WHEN u.role = 'premise officer' AND po.rank = 'Supervisor' THEN 'Supervisor'
                    ELSE u.role
                END as role
            FROM Users u
            LEFT JOIN premise_officers po ON po.userID = u.id
            WHERE 
                -- All admins
                (u.role = 'admin')
                OR 
                -- All supervisors assigned to the caretaker's site
                (
                    u.role = 'premise officer' 
                    AND po.rank = 'Supervisor'
                    AND u.id IN (
                        SELECT officer_id 
                        FROM officer_site_assignments 
                        WHERE site_id = (
                            SELECT site_id 
                            FROM caretaker_site_assignments 
                            WHERE caretaker_id = :caretaker_id 
                            AND status = 'Active'
                            LIMIT 1
                        )
                        AND shift_type = 'Supervisor'
                        AND status = 'Active'
                    )
                )
            AND u.id != :caretaker_id2
            ORDER BY 
                CASE WHEN u.role = 'admin' THEN 0 ELSE 1 END,
                u.name ASC
        ");
        
        $this->db->bind(':caretaker_id', $caretaker_user_id);
        $this->db->bind(':caretaker_id2', $caretaker_user_id);
        
        return $this->db->resultSet();
    }
}
