<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php if($data['role'] == 'admin'): ?>
    <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<?php elseif($data['role'] == 'caretaker'): ?>
    <?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?> 

<?php elseif($data['role'] == 'mobile rider'): ?>
    <?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>  

<?php elseif($data['role'] == 'premise officer'): ?>
    <?php require_once APP_ROOT . '/views/components/v_premiseofficer_sidebar.php'; ?>   

<?php elseif($data['role'] == 'supervisor'): ?>
    <?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<?php elseif($data['role'] == 'client'): ?>
    <?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>  

<?php endif; ?>

      <button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="history.back()"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>

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
                    <div class="notification-item <?php echo !empty($notification->is_read) ? 'read' : 'unread'; ?> <?php echo htmlspecialchars($type); ?>" 
                         data-notification-id="<?php echo htmlspecialchars($notification->id ?? ''); ?>">
                        
                        <div class="notification-icon">
                            <?php 
                                $type = $notification->type ?? 'info';
                                $title = $notification->title ?? '';
                                $message = $notification->message ?? '';
                                
                                // Debug output (remove this after testing)
                                // echo "<!-- DEBUG: Type: $type, Title: $title, Message: " . substr($message, 0, 30) . "... -->";
                                
                                // Determine icon based on notification content
                                $iconClass = 'fa-bell'; // default
                                
                                // Check for specific notification types based on title/message content
                                if (stripos($title, 'incident') !== false || stripos($message, 'incident') !== false) {
                                    if (stripos($title, 'review') !== false || stripos($message, 'review') !== false) {
                                        $iconClass = 'fa-comment-dots'; // Review/comment icon
                                    } else {
                                        $iconClass = 'fa-exclamation-triangle'; // Incident report icon
                                    }
                                } elseif (stripos($title, 'admin') !== false || stripos($message, 'admin') !== false) {
                                    $iconClass = 'fa-user-shield'; // Admin related
                                } elseif (stripos($title, 'officer') !== false || stripos($message, 'officer') !== false) {
                                    $iconClass = 'fa-user-check'; // Officer related
                                } elseif (stripos($title, 'client') !== false || stripos($message, 'client') !== false) {
                                    $iconClass = 'fa-user-plus'; // Client related
                                } elseif (stripos($title, 'mobile rider') !== false || stripos($message, 'mobile rider') !== false) {
                                    $iconClass = 'fa-motorcycle'; // Mobile rider related
                                } elseif (stripos($title, 'supervisor') !== false || stripos($message, 'supervisor') !== false) {
                                    $iconClass = 'fa-user-tie'; // Supervisor related
                                } elseif (stripos($title, 'welcome') !== false || stripos($message, 'welcome') !== false) {
                                    $iconClass = 'fa-handshake'; // Welcome messages
                                } elseif (stripos($title, 'rank') !== false || stripos($message, 'rank') !== false) {
                                    $iconClass = 'fa-star'; // Rank update icon
                                } elseif (stripos($title, 'password') !== false || stripos($message, 'password') !== false) {
                                    $iconClass = 'fa-key'; // Password related
                                } elseif (stripos($title, 'leave') !== false || stripos($message, 'leave') !== false) {
                                    $iconClass = 'fa-calendar-times'; // Leave requests
                                } elseif (stripos($title, 'attendance') !== false || stripos($message, 'attendance') !== false) {
                                    $iconClass = 'fa-clipboard-check'; // Attendance related
                                } elseif (stripos($title, 'message') !== false || stripos($message, 'message') !== false) {
                                    $iconClass = 'fa-envelope'; // Messages
                                } elseif ($type === 'success') {
                                    $iconClass = 'fa-check-circle';
                                } elseif ($type === 'warning') {
                                    $iconClass = 'fa-exclamation-triangle';
                                } elseif ($type === 'error') {
                                    $iconClass = 'fa-times-circle';
                                } elseif ($type === 'info') {
                                    $iconClass = 'fa-info-circle';
                                }
                                
                                // Debug output (remove this after testing)
                                // echo "<!-- DEBUG: Selected icon: $iconClass -->";
                            ?>
                            <i class="fas <?php echo $iconClass; ?> notification-icon" style="font-size: 20px; font-family: 'Font Awesome 6 Free', sans-serif;"></i>
                            <!-- Fallback text if FontAwesome doesn't load -->
                            <noscript><span style="font-size: 12px; color: #666;">[<?php echo strtoupper(str_replace(['fa-', '-'], ['', ' '], $iconClass)); ?>]</span></noscript>
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
                                    <i class="fas fa-check"></i> Mark as Read
                                </button>
                            <?php endif; ?>
                            <button class="delete-notification-btn" onclick="deleteNotification(<?php echo $notification->id; ?>)" title="Delete">
                                <i class="fas fa-trash"></i> Delete
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
    margin: 20px;
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
    transition: all 0.3s ease;
}

.notification-item:hover .notification-icon {
    transform: scale(1.1);
}

.notification-icon i {
    font-size: 20px;
    color: #2196F3;
}

/* Specific icon colors based on notification type */
.notification-icon .fa-exclamation-triangle { color: #ff9800; } /* Incident reports - orange */
.notification-icon .fa-comment-dots { color: #2196F3; } /* Reviews - blue */
.notification-icon .fa-user-shield { color: #9c27b0; } /* Admin - purple */
.notification-icon .fa-user-check { color: #4caf50; } /* Officer - green */
.notification-icon .fa-user-plus { color: #00bcd4; } /* Client - cyan */
.notification-icon .fa-motorcycle { color: #ff5722; } /* Mobile rider - deep orange */
.notification-icon .fa-user-tie { color: #795548; } /* Supervisor - brown */
.notification-icon .fa-handshake { color: #8bc34a; } /* Welcome - light green */
.notification-icon .fa-key { color: #607d8b; } /* Password - blue grey */
.notification-icon .fa-calendar-times { color: #e91e63; } /* Leave - pink */
.notification-icon .fa-clipboard-check { color: #3f51b5; } /* Attendance - indigo */
.notification-icon .fa-envelope { color: #2196F3; } /* Messages - blue */
.notification-icon .fa-check-circle { color: #4caf50; } /* Success - green */
.notification-icon .fa-times-circle { color: #f44336; } /* Error - red */
.notification-icon .fa-info-circle { color: #2196F3; } /* Info - blue */
.notification-icon .fa-bell { color: #9e9e9e; } /* Default - grey */

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
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 13px;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 5px;
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

/* Custom Delete Confirmation Modal Styles */
.delete-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    justify-content: center;
    align-items: center;
}

.delete-modal-content {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 90%;
    margin: 20px;
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.delete-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 20px 15px;
    border-bottom: 1px solid #e0e0e0;
}

.delete-modal-header h3 {
    margin: 0;
    color: #333;
    font-size: 18px;
    font-weight: 600;
}

.delete-modal-close {
    font-size: 24px;
    color: #999;
    cursor: pointer;
    transition: color 0.3s;
}

.delete-modal-close:hover {
    color: #666;
}

.delete-modal-body {
    padding: 20px;
    text-align: center;
}

.delete-modal-icon {
    font-size: 48px;
    color: #f44336;
    margin-bottom: 15px;
}

.delete-modal-body p {
    margin: 10px 0;
    color: #666;
    font-size: 16px;
}

.delete-modal-warning {
    color: #f44336 !important;
    font-weight: 500 !important;
}

.delete-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 15px 20px 20px;
    border-top: 1px solid #e0e0e0;
}

.delete-modal-cancel,
.delete-modal-confirm {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
}

.delete-modal-cancel {
    background-color: #f5f5f5;
    color: #666;
    border: 1px solid #ddd;
}

.delete-modal-cancel:hover {
    background-color: #e0e0e0;
    color: #333;
}

.delete-modal-confirm {
    background-color: #f44336;
    color: white;
}

.delete-modal-confirm:hover {
    background-color: #d32f2f;
}

@media (max-width: 480px) {
    .delete-modal-content {
        margin: 10px;
        width: calc(100% - 20px);
    }
    
    .delete-modal-footer {
        flex-direction: column;
    }
    
    .delete-modal-cancel,
    .delete-modal-confirm {
        width: 100%;
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
    
    // Show custom confirmation modal
    showDeleteConfirmation(notificationId);
}

// Helper function for time elapsed
<?php if(!function_exists('time_elapsed_string')): ?>
function time_elapsed_string(datetime) {
    // This is a JavaScript version, but ideally use PHP function
    return datetime;
}
<?php endif; ?>

// Custom delete confirmation modal functions
let notificationToDelete = null;

function showDeleteConfirmation(notificationId) {
    notificationToDelete = notificationId;
    const modal = document.getElementById('deleteConfirmationModal');
    modal.style.display = 'flex';
}

function hideDeleteConfirmation() {
    const modal = document.getElementById('deleteConfirmationModal');
    modal.style.display = 'none';
    notificationToDelete = null;
}

function confirmDelete() {
    if (!notificationToDelete) return;
    
    const notificationId = notificationToDelete;
    hideDeleteConfirmation();
    
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
        } else {
            alert('Failed to delete notification. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the notification.');
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteConfirmationModal');
    if (event.target == modal) {
        hideDeleteConfirmation();
    }
}
</script>

    </main>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div id="deleteConfirmationModal" class="delete-modal" style="display: none;">
        <div class="delete-modal-content">
            <div class="delete-modal-header">
                <h3>Delete Notification</h3>
                <span class="delete-modal-close" onclick="hideDeleteConfirmation()">&times;</span>
            </div>
            <div class="delete-modal-body">
                <div class="delete-modal-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <p>Are you sure you want to delete this notification?</p>
                <p class="delete-modal-warning">This action cannot be undone.</p>
            </div>
            <div class="delete-modal-footer">
                <button class="delete-modal-cancel" onclick="hideDeleteConfirmation()">Cancel</button>
                <button class="delete-modal-confirm" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>