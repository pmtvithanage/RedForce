<?php
/**
 * Caretaker Notes Table Installer
 * Run this file once to create the caretaker_notes table
 * Delete this file after successful installation
 */

// Database configuration
$host = 'localhost';
$dbname = 'redforce_db';
$username = 'root';  // Change if needed
$password = '';      // Change if needed

try {
    // Create connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Installing Caretaker Notes Table...</h2>";
    
    // SQL to create table
    $sql = "CREATE TABLE IF NOT EXISTS `caretaker_notes` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `caretaker_id` INT NOT NULL COMMENT 'Foreign key to Users table',
        `title` VARCHAR(255) NOT NULL,
        `note_content` TEXT NOT NULL,
        `category` ENUM('General', 'Important', 'Reminder', 'Observation') NOT NULL DEFAULT 'General',
        `priority` ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
        `reminder_date` DATE NULL COMMENT 'Optional reminder date',
        `is_pinned` TINYINT(1) DEFAULT 0 COMMENT '1 = pinned to top',
        `is_completed` TINYINT(1) DEFAULT 0 COMMENT '1 = completed reminder',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        CONSTRAINT `fk_notes_caretaker` FOREIGN KEY (`caretaker_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE,
        
        INDEX `idx_caretaker` (`caretaker_id`),
        INDEX `idx_category` (`category`),
        INDEX `idx_priority` (`priority`),
        INDEX `idx_reminder_date` (`reminder_date`),
        INDEX `idx_created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $pdo->exec($sql);
    
    echo "<p style='color: green; font-weight: bold;'>✓ Table 'caretaker_notes' created successfully!</p>";
    
    // Insert sample data (only if table is empty)
    $checkSql = "SELECT COUNT(*) FROM caretaker_notes";
    $count = $pdo->query($checkSql)->fetchColumn();
    
    if ($count == 0) {
        echo "<p>Inserting sample data...</p>";
        
        // Get a caretaker ID to use for sample data
        $caretakerSql = "SELECT id FROM Users WHERE role = 'caretaker' LIMIT 1";
        $result = $pdo->query($caretakerSql);
        $caretakerRow = $result->fetch(PDO::FETCH_ASSOC);
        
        if ($caretakerRow) {
            $caretakerId = $caretakerRow['id'];
            
            $sampleData = [
                [
                    'title' => 'Check main gate lock',
                    'content' => 'Need to inspect the main gate lock mechanism as it was sticking yesterday. May need lubrication or replacement.',
                    'category' => 'Observation',
                    'priority' => 'High',
                    'reminder' => date('Y-m-d')
                ],
                [
                    'title' => 'Submit monthly report',
                    'content' => 'Monthly security report due by end of week. Include all incident logs and patrol summaries.',
                    'category' => 'Reminder',
                    'priority' => 'Medium',
                    'reminder' => date('Y-m-d', strtotime('+3 days'))
                ],
                [
                    'title' => 'Equipment inventory',
                    'content' => 'Flashlight batteries running low. Radio channel 3 has static. Need to request new uniform.',
                    'category' => 'General',
                    'priority' => 'Low',
                    'reminder' => null
                ]
            ];
            
            $insertStmt = $pdo->prepare("INSERT INTO caretaker_notes (caretaker_id, title, note_content, category, priority, reminder_date) VALUES (?, ?, ?, ?, ?, ?)");
            
            foreach ($sampleData as $data) {
                $insertStmt->execute([
                    $caretakerId,
                    $data['title'],
                    $data['content'],
                    $data['category'],
                    $data['priority'],
                    $data['reminder']
                ]);
            }
            
            echo "<p style='color: green;'>✓ Sample data inserted successfully!</p>";
        }
    }
    
    echo "<p>You can now use the Notes feature in the Caretaker dashboard.</p>";
    echo "<p style='color: red;'><strong>IMPORTANT:</strong> Please delete this file (install_caretaker_notes_table.php) for security reasons.</p>";
    echo "<p><a href='/RedForce/caretaker/notes'>Go to Notes Page →</a></p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check:</p>";
    echo "<ul>";
    echo "<li>Database credentials are correct</li>";
    echo "<li>Database 'redforce_db' exists</li>";
    echo "<li>MySQL server is running</li>";
    echo "<li>'Users' table exists (required for foreign key)</li>";
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Caretaker Notes Table Installer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h2 {
            color: #e74c3c;
        }
        p, li {
            line-height: 1.6;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
</body>
</html>
