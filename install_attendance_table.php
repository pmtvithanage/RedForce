<?php
/**
 * Officer Attendance Table Installer
 * Run this file once to create the officer_attendance table
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
    
    echo "<h2>Installing Officer Attendance Table...</h2>";
    
    // SQL to create table
    $sql = "CREATE TABLE IF NOT EXISTS officer_attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        supervisor_id INT NOT NULL COMMENT 'References Users table',
        officer_id VARCHAR(50) NOT NULL COMMENT 'Officer ID from Users table',
        officer_name VARCHAR(255) NOT NULL,
        attendance_date DATE NOT NULL,
        check_in_time TIME DEFAULT NULL,
        check_out_time TIME DEFAULT NULL,
        status ENUM('Present', 'Absent', 'Late', 'Half Day') NOT NULL DEFAULT 'Present',
        notes TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (supervisor_id) REFERENCES Users(id) ON DELETE CASCADE,
        INDEX idx_supervisor_date (supervisor_id, attendance_date),
        INDEX idx_officer_id (officer_id),
        INDEX idx_attendance_date (attendance_date),
        INDEX idx_status (status),
        UNIQUE KEY unique_officer_date (officer_id, attendance_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $pdo->exec($sql);
    
    echo "<p style='color: green; font-weight: bold;'>✓ Table 'officer_attendance' created successfully!</p>";
    echo "<p>You can now use the Officer Attendance feature in the Supervisor dashboard.</p>";
    echo "<p style='color: red;'><strong>IMPORTANT:</strong> Please delete this file (install_attendance_table.php) for security reasons.</p>";
    echo "<p><a href='/RedForce/supervisor/attendance'>Go to Attendance Page →</a></p>";
    
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
    <title>Officer Attendance Installer</title>
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
