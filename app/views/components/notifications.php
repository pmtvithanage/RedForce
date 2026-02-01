<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php if($data['role'] == 'admin'): ?>
    <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<?php elseif($data['role'] == 'caretaker'): ?>
    <?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?> 

<?php elseif($data['role'] == 'mobilerider'): ?>
    <?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>  

<?php elseif($data['role'] == 'premisofficer'): ?>
    <?php require_once APP_ROOT . '/views/components/v_premiseofficer_sidebar.php'; ?>   

<?php elseif($data['role'] == 'premisofficer' && $data['status'] == 'supervisor'): ?>
    <?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<?php elseif($data['role'] == 'client'): ?>
    <?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>  

<?php endif; ?>

<!-- Notifications View - Common for All Users -->
<div class="notifications-container">
    <div class="notifications-header">
        <h2>Notifications</h2>
        <?php if(!empty($data['notifications'])): ?>
            <button class="mark-all-read-btn" onclick="markAllAsRead()">Mark All as Read</button>
        <?php endif; ?>
    </div>

    <div class="notifications-content">
        <?php if(!empty($data['notifications']) && is_array($data['notifications'])): ?>
            <div class="notifications-list">
                <?php foreach($data['notifications'] as $notification): ?>
                    <div class="notification-item <?php echo !empty($notification->is_read) ? 'read' : 'unread'; ?>" 
                         data-notification-id="<?php echo htmlspecialchars($notification->id ?? ''); ?>">
                        
                        <div class="notification-icon">
                            <?php 
                                $type = $notification->type ?? 'info';
                                $iconClass = '';
                                switch($type) {
                                    case 'success':
                                        $iconClass = 'fa-check-circle';
                                        break;
                                    case 'warning':
                                        $iconClass = 'fa-exclamation-triangle';
                                        break;
                                    case 'error':
                                        $iconClass = 'fa-times-circle';
                                        break;
                                    case 'message':
                                        $iconClass = 'fa-envelope';
                                        break;
                                    default:
                                        $iconClass = 'fa-bell';
                                }
                            ?>
                            <i class="fas <?php echo $iconClass; ?>"></i>
                        </div>

                        <div class="notification-content">
                            <div class="notification-title">
                                <?php echo htmlspecialchars($notification->title ?? 'Notification'); ?>
                            </div>
                            <div class="notification-message">
                                <?php echo htmlspecialchars($notification->message ?? ''); ?>
                            </div>
                            <div class="notification-meta">
                                <span class="notification-time">
                                    <i class="far fa-clock"></i>
                                    <?php 
                                        if(isset($notification->created_at)) {
                                            echo time_elapsed_string($notification->created_at);
                                        }
                                    ?>
                                </span>
                                <?php if(!empty($notification->link)): ?>
                                    <a href="<?php echo htmlspecialchars($notification->link); ?>" class="notification-link">
                                        View Details <i class="fas fa-arrow-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="notification-actions">
                            <?php if(empty($notification->is_read)): ?>
                                <button class="mark-read-btn" onclick="markAsRead(<?php echo $notification->id; ?>)" title="Mark as read">
                                    <i class="fas fa-check"></i>
                                </button>
                            <?php endif; ?>
                            <button class="delete-notification-btn" onclick="deleteNotification(<?php echo $notification->id; ?>)" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination if needed -->
            <?php if(isset($data['total_pages']) && $data['total_pages'] > 1): ?>
                <div class="notifications-pagination">
                    <?php 
                        $current_page = $data['current_page'] ?? 1;
                        $total_pages = $data['total_pages'];
                    ?>
                    
                    <?php if($current_page > 1): ?>
                        <a href="?page=<?php echo $current_page - 1; ?>" class="pagination-btn">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    <?php endif; ?>

                    <span class="pagination-info">
                        Page <?php echo $current_page; ?> of <?php echo $total_pages; ?>
                    </span>

                    <?php if($current_page < $total_pages): ?>
                        <a href="?page=<?php echo $current_page + 1; ?>" class="pagination-btn">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="no-notifications">
                <div class="no-notifications-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h3>No Notifications</h3>
                <p>You're all caught up! No new notifications at this time.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.notifications-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.notifications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e0e0e0;
}

.notifications-header h2 {
    margin: 0;
    color: #333;
}

.mark-all-read-btn {
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.mark-all-read-btn:hover {
    background-color: #45a049;
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.notification-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s;
    border-left: 4px solid transparent;
}

.notification-item.unread {
    background-color: #f0f8ff;
    border-left-color: #2196F3;
}

.notification-item.read {
    opacity: 0.8;
}

.notification-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.notification-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: #f5f5f5;
}

.notification-icon i {
    font-size: 20px;
    color: #2196F3;
}

.notification-content {
    flex-grow: 1;
}

.notification-title {
    font-weight: 600;
    font-size: 16px;
    color: #333;
    margin-bottom: 5px;
}

.notification-message {
    color: #666;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 8px;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 13px;
}

.notification-time {
    color: #999;
}

.notification-link {
    color: #2196F3;
    text-decoration: none;
    font-weight: 500;
}

.notification-link:hover {
    text-decoration: underline;
}

.notification-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex-shrink: 0;
}

.mark-read-btn, .delete-notification-btn {
    padding: 6px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
}

.mark-read-btn {
    background-color: #4CAF50;
    color: white;
}

.mark-read-btn:hover {
    background-color: #45a049;
}

.delete-notification-btn {
    background-color: #f44336;
    color: white;
}

.delete-notification-btn:hover {
    background-color: #da190b;
}

.no-notifications {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.no-notifications-icon {
    font-size: 64px;
    margin-bottom: 20px;
    color: #ddd;
}

.no-notifications h3 {
    color: #666;
    margin-bottom: 10px;
}

.no-notifications p {
    color: #999;
}

.notifications-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.pagination-btn {
    padding: 8px 16px;
    background-color: #2196F3;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.pagination-btn:hover {
    background-color: #0b7dda;
}

.pagination-info {
    color: #666;
    font-size: 14px;
}

@media (max-width: 768px) {
    .notifications-container {
        padding: 15px;
    }
    
    .notifications-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .notification-item {
        flex-direction: column;
    }
    
    .notification-actions {
        flex-direction: row;
    }
}
</style>

<script>
function markAsRead(notificationId) {
    if(!notificationId) return;
    
    fetch('<?php echo URL_ROOT; ?>/api/notifications/mark-read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notification_id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if(notificationElement) {
                notificationElement.classList.remove('unread');
                notificationElement.classList.add('read');
                const markReadBtn = notificationElement.querySelector('.mark-read-btn');
                if(markReadBtn) {
                    markReadBtn.remove();
                }
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    fetch('<?php echo URL_ROOT; ?>/api/notifications/mark-all-read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteNotification(notificationId) {
    if(!notificationId) return;
    
    if(!confirm('Are you sure you want to delete this notification?')) {
        return;
    }
    
    fetch('<?php echo URL_ROOT; ?>/api/notifications/delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notification_id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if(notificationElement) {
                notificationElement.style.opacity = '0';
                setTimeout(() => {
                    notificationElement.remove();
                    // Reload if no more notifications
                    if(document.querySelectorAll('.notification-item').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

// Helper function for time elapsed
<?php if(!function_exists('time_elapsed_string')): ?>
function time_elapsed_string(datetime) {
    // This is a JavaScript version, but ideally use PHP function
    return datetime;
}
<?php endif; ?>
</script>

    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>