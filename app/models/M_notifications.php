<?php
class M_notifications {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get all notifications for a user (alias for getNotificationsByUserId)
     * @param int $userId User ID
     * @param int $limit Optional limit for results
     * @param int $offset Optional offset for pagination
     * @return array Array of notification objects
     */
    public function getNotifications($userId, $limit = null, $offset = 0) {
        return $this->getNotificationsByUserId($userId, $limit, $offset);
    }

    /**
     * Get all notifications for a user
     * @param int $userId User ID
     * @param int $limit Optional limit for results
     * @param int $offset Optional offset for pagination
     * @return array Array of notification objects
     */
    public function getNotificationsByUserId($userId, $limit = null, $offset = 0) {
        $sql = "SELECT n.*, 
                       u.name as from_user_name,
                       u.email as from_user_email
                FROM notifications n
                LEFT JOIN Users u ON n.from_user_id = u.id
                WHERE n.user_id = :user_id 
                ORDER BY n.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        
        if ($limit) {
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Get unread notifications for a user
     * @param int $userId User ID
     * @return array Array of unread notification objects
     */
    public function getUnreadNotifications($userId) {
        $this->db->query("SELECT n.*, 
                                 u.name as from_user_name,
                                 u.email as from_user_email
                          FROM notifications n
                          LEFT JOIN Users u ON n.from_user_id = u.id
                          WHERE n.user_id = :user_id 
                          AND n.is_read = 0
                          ORDER BY n.created_at DESC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /**
     * Get count of unread notifications for a user
     * @param int $userId User ID
     * @return int Count of unread notifications
     */
    public function getUnreadCount($userId) {
        $this->db->query("SELECT COUNT(*) as count 
                          FROM notifications 
                          WHERE user_id = :user_id 
                          AND is_read = 0");
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    /**
     * Get notification by ID
     * @param int $notificationId Notification ID
     * @return object|false Notification object or false
     */
    public function getNotificationById($notificationId) {
        $this->db->query("SELECT n.*, 
                                 u.name as from_user_name,
                                 u.email as from_user_email
                          FROM notifications n
                          LEFT JOIN Users u ON n.from_user_id = u.id
                          WHERE n.id = :id");
        $this->db->bind(':id', $notificationId);
        return $this->db->single();
    }

    /**
     * Add a new notification
     * @param int $userId Recipient user ID
     * @param string $type Notification type (info, success, warning, danger)
     * @param string $title Notification title
     * @param string $message Notification message
     * @param string|null $link Optional link
     * @param string $icon Icon name (default: 'notifications')
     * @param int|null $fromUserId Sender user ID (optional)
     * @return bool Success status
     */
    public function addNotification($userId, $type, $title, $message, $link = null, $icon = 'notifications', $fromUserId = null) {
        $this->db->query("INSERT INTO notifications 
                          (user_id, type, title, message, link, icon, from_user_id) 
                          VALUES 
                          (:user_id, :type, :title, :message, :link, :icon, :from_user_id)");
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':type', $type);
        $this->db->bind(':title', $title);
        $this->db->bind(':message', $message);
        $this->db->bind(':link', $link);
        $this->db->bind(':icon', $icon);
        $this->db->bind(':from_user_id', $fromUserId);
        
        return $this->db->execute();
    }

    /**
     * Insert a notification (alias for addNotification for backward compatibility)
     * @param int $userId Recipient user ID
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param string|null $link Optional link
     * @param string $icon Icon name (default: 'notifications')
     * @param int|null $fromUserId Sender user ID (optional)
     * @return bool Success status
     */
    public function insertNotification($userId, $type, $title, $message, $link = null, $icon = 'notifications', $fromUserId = null) {
        return $this->addNotification($userId, $type, $title, $message, $link, $icon, $fromUserId);
    }

    /**
     * Mark a notification as read
     * @param int $notificationId Notification ID
     * @param int $userId User ID (for security check)
     * @return bool Success status
     */
    public function markAsRead($notificationId, $userId = null) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = :id";
        
        if ($userId) {
            $sql .= " AND user_id = :user_id";
        }
        
        $this->db->query($sql);
        $this->db->bind(':id', $notificationId);
        
        if ($userId) {
            $this->db->bind(':user_id', $userId);
        }
        
        return $this->db->execute();
    }

    /**
     * Mark all notifications as read for a user
     * @param int $userId User ID
     * @return bool Success status
     */
    public function markAllAsRead($userId) {
        $this->db->query("UPDATE notifications 
                          SET is_read = 1 
                          WHERE user_id = :user_id 
                          AND is_read = 0");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * Delete a notification
     * @param int $notificationId Notification ID
     * @param int $userId User ID (for security check)
     * @return bool Success status
     */
    public function deleteNotification($notificationId, $userId = null) {
        $sql = "DELETE FROM notifications WHERE id = :id";
        
        if ($userId) {
            $sql .= " AND user_id = :user_id";
        }
        
        $this->db->query($sql);
        $this->db->bind(':id', $notificationId);
        
        if ($userId) {
            $this->db->bind(':user_id', $userId);
        }
        
        return $this->db->execute();
    }

    /**
     * Delete all read notifications for a user
     * @param int $userId User ID
     * @return bool Success status
     */
    public function deleteAllRead($userId) {
        $this->db->query("DELETE FROM notifications 
                          WHERE user_id = :user_id 
                          AND is_read = 1");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * Delete all notifications for a user
     * @param int $userId User ID
     * @return bool Success status
     */
    public function deleteAllNotifications($userId) {
        $this->db->query("DELETE FROM notifications WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * Get total count of notifications for a user
     * @param int $userId User ID
     * @return int Total count
     */
    public function getTotalCount($userId) {
        $this->db->query("SELECT COUNT(*) as count 
                          FROM notifications 
                          WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    /**
     * Get notifications with pagination
     * @param int $userId User ID
     * @param int $page Current page number (1-based)
     * @param int $perPage Items per page
     * @return array Array with 'notifications', 'current_page', 'total_pages', 'total_count'
     */
    public function getNotificationsWithPagination($userId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        // Get notifications
        $notifications = $this->getNotificationsByUserId($userId, $perPage, $offset);
        
        // Get total count
        $totalCount = $this->getTotalCount($userId);
        $totalPages = ceil($totalCount / $perPage);
        
        return [
            'notifications' => $notifications,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_count' => $totalCount,
            'per_page' => $perPage
        ];
    }

    /**
     * Send notification to multiple users
     * @param array $userIds Array of user IDs
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param string|null $link Optional link
     * @param string $icon Icon name
     * @param int|null $fromUserId Sender user ID
     * @return bool Success status
     */
    public function sendBulkNotification($userIds, $type, $title, $message, $link = null, $icon = 'notifications', $fromUserId = null) {
        $success = true;
        foreach ($userIds as $userId) {
            if (!$this->addNotification($userId, $type, $title, $message, $link, $icon, $fromUserId)) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Delete old notifications (cleanup)
     * @param int $days Delete notifications older than this many days
     * @return bool Success status
     */
    public function deleteOldNotifications($days = 30) {
        $this->db->query("DELETE FROM notifications 
                          WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)");
        $this->db->bind(':days', $days);
        return $this->db->execute();
    }
}
