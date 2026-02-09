<?php
/**
 * API endpoint to delete a notification
 */

// Start session
session_start();

// Include configuration and database
require_once '../../../app/config/config.php';
require_once '../../../app/libraries/Database.php';
require_once '../../../app/models/M_notifications.php';

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized. Please log in.'
    ]);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['notification_id']) || empty($input['notification_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Notification ID is required'
    ]);
    exit;
}

$notificationId = (int)$input['notification_id'];
$userId = $_SESSION['user_id'];

// Initialize model
$notificationModel = new M_notifications();

// Delete notification (with user verification for security)
$result = $notificationModel->deleteNotification($notificationId, $userId);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Notification deleted successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete notification'
    ]);
}
