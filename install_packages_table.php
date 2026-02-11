<?php
/**
 * Install Packages Table
 * Run this file once to create the packages table in the database
 */

require_once 'app/config/config.php';

try {
    // Create database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected to database successfully.<br><br>";
    
    // Read SQL file
    $sql = file_get_contents('dev/create_packages_table.sql');
    
    // Split into separate queries (by semicolon)
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($queries as $query) {
        // Skip empty queries and comments
        if (empty($query) || strpos(trim($query), '--') === 0) {
            continue;
        }
        
        if ($conn->multi_query($query . ';')) {
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());
            
            $success_count++;
            echo "✓ Query executed successfully<br>";
        } else {
            $error_count++;
            echo "✗ Error: " . $conn->error . "<br>";
        }
    }
    
    echo "<br><strong>Installation Complete!</strong><br>";
    echo "Successful queries: $success_count<br>";
    echo "Failed queries: $error_count<br>";
    
    // Verify table creation
    $result = $conn->query("SHOW TABLES LIKE 'packages'");
    if ($result->num_rows > 0) {
        echo "<br>✓ 'packages' table created successfully!<br>";
        
        // Show table structure
        $result = $conn->query("DESCRIBE packages");
        echo "<br><strong>Table Structure:</strong><br>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "<td>{$row['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Count inserted packages
        $result = $conn->query("SELECT COUNT(*) as count FROM packages");
        $row = $result->fetch_assoc();
        echo "<br>✓ {$row['count']} default packages inserted successfully!<br>";
    } else {
        echo "<br>✗ Table creation failed!<br>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
