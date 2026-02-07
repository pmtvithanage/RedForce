<?php
require_once 'app/config/config.php';
require_once 'app/libraries/Database.php';
$db = new Database();
$db->query('SELECT COUNT(*) as count FROM notifications');
$result = $db->single();
echo 'Total notifications in database: ' . ($result ? $result->count : 0) . PHP_EOL;
if ($result && $result->count > 0) {
    $db->query('SELECT title, message, type FROM notifications LIMIT 3');
    $notifications = $db->resultSet();
    echo 'Sample notifications:' . PHP_EOL;
    foreach ($notifications as $notif) {
        echo '- Title: ' . $notif->title . PHP_EOL;
        echo '  Message: ' . substr($notif->message, 0, 50) . '...' . PHP_EOL;
        echo '  Type: ' . $notif->type . PHP_EOL . PHP_EOL;
    }
} else {
    echo 'No notifications found in database.' . PHP_EOL;
}
?>
