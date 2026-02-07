<?php
/**
 * PHPMailer Email Diagnostic Test
 * This script tests if PHPMailer email sending is working correctly
 * Access via: http://localhost/RedForce/test_email.php
 */

// Load configuration
require_once '../app/config/config.php';
require_once '../vendor/autoload.php';
require_once '../app/helpers/email_helper.php';

// Start output
?>
<!DOCTYPE html>
<html>
<head>
    <title>Email Diagnostic Test - <?php echo SITE_NAME; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #d32f2f;
            padding-bottom: 10px;
        }
        .config-section {
            background: #f9f9f9;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #2196F3;
        }
        .config-item {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #333;
            font-family: monospace;
        }
        .test-section {
            margin: 30px 0;
            padding: 20px;
            background: #e3f2fd;
            border-radius: 4px;
        }
        .result {
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .success {
            background: #c8e6c9;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }
        .error {
            background: #ffcdd2;
            color: #c62828;
            border: 1px solid #f44336;
        }
        .info {
            background: #fff9c4;
            color: #f57f17;
            border: 1px solid #ffc107;
        }
        button {
            background: #d32f2f;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #b71c1c;
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        pre {
            background: #f5f5f5;
            padding: 10px;
            overflow-x: auto;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Diagnostic Test</h1>
        
        <div class="warning">
            ⚠️ <strong>Security Notice:</strong> This is a diagnostic tool. Remove this file from production environments!
        </div>

        <h2>1. Configuration Check</h2>
        <div class="config-section">
            <div class="config-item">
                <span class="label">SMTP Host:</span>
                <span class="value"><?php echo SMTP_HOST; ?></span>
            </div>
            <div class="config-item">
                <span class="label">SMTP Port:</span>
                <span class="value"><?php echo SMTP_PORT; ?></span>
            </div>
            <div class="config-item">
                <span class="label">SMTP User:</span>
                <span class="value"><?php echo SMTP_USER; ?></span>
            </div>
            <div class="config-item">
                <span class="label">SMTP Password:</span>
                <span class="value"><?php echo str_repeat('*', strlen(SMTP_PASS)); ?> (<?php echo strlen(SMTP_PASS); ?> characters)</span>
            </div>
            <div class="config-item">
                <span class="label">SMTP Secure:</span>
                <span class="value"><?php echo SMTP_SECURE; ?></span>
            </div>
            <div class="config-item">
                <span class="label">From Email:</span>
                <span class="value"><?php echo SMTP_FROM; ?></span>
            </div>
            <div class="config-item">
                <span class="label">From Name:</span>
                <span class="value"><?php echo SMTP_FROM_NAME; ?></span>
            </div>
        </div>

        <h2>2. PHPMailer Library Check</h2>
        <div class="result <?php echo class_exists('PHPMailer\PHPMailer\PHPMailer') ? 'success' : 'error'; ?>">
            <?php if (class_exists('PHPMailer\PHPMailer\PHPMailer')): ?>
                ✓ PHPMailer library is loaded successfully
            <?php else: ?>
                ✗ PHPMailer library is NOT loaded. Run: composer require phpmailer/phpmailer
            <?php endif; ?>
        </div>

        <h2>3. Send Test Email</h2>
        <div class="test-section">
            <form method="POST" action="">
                <label for="test_email"><strong>Enter recipient email address:</strong></label>
                <input type="email" id="test_email" name="test_email" required 
                       placeholder="your-email@example.com" 
                       value="<?php echo isset($_POST['test_email']) ? htmlspecialchars($_POST['test_email']) : ''; ?>">
                <button type="submit" name="send_test">Send Test Email</button>
            </form>

            <?php
            if (isset($_POST['send_test']) && !empty($_POST['test_email'])) {
                $testEmail = filter_var($_POST['test_email'], FILTER_VALIDATE_EMAIL);
                
                if ($testEmail) {
                    echo '<h3>Test Results:</h3>';
                    
                    // Test 1: Basic email sending
                    echo '<h4>Test 1: Basic Email</h4>';
                    $result1 = send_email(
                        $testEmail,
                        'PHPMailer Test - Basic Email',
                        '<h1>Test Email</h1><p>This is a test email from ' . SITE_NAME . '</p><p>If you received this, PHPMailer is working correctly!</p><p><strong>Timestamp:</strong> ' . date('Y-m-d H:i:s') . '</p>',
                        'This is a test email. If you received this, PHPMailer is working correctly!'
                    );
                    
                    if ($result1['success']) {
                        echo '<div class="result success">✓ ' . htmlspecialchars($result1['message']) . '</div>';
                    } else {
                        echo '<div class="result error">✗ Error: ' . htmlspecialchars($result1['message']) . '</div>';
                    }
                    
                    // Test 2: Template-based email
                    echo '<h4>Test 2: Template Email (if templates exist)</h4>';
                    $templateVars = [
                        'name' => 'Test User',
                        'email' => $testEmail,
                        'subject' => 'PHPMailer Template Test'
                    ];
                    
                    // Try to send template email
                    $result2 = send_templated_email(
                        $testEmail,
                        'welcome_client',
                        $templateVars
                    );
                    
                    if ($result2['success']) {
                        echo '<div class="result success">✓ Template email sent: ' . htmlspecialchars($result2['message']) . '</div>';
                    } else {
                        echo '<div class="result info">ℹ Template test: ' . htmlspecialchars($result2['message']) . '</div>';
                    }
                    
                    // Show email templates available
                    echo '<h4>Available Email Templates:</h4>';
                    $templateDir = APP_ROOT . '/email_templates/';
                    if (is_dir($templateDir)) {
                        $templates = glob($templateDir . '*.php');
                        if (!empty($templates)) {
                            echo '<ul>';
                            foreach ($templates as $template) {
                                $templateName = basename($template, '.php');
                                echo '<li>' . htmlspecialchars($templateName) . '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<div class="info">No templates found in ' . htmlspecialchars($templateDir) . '</div>';
                        }
                    }
                    
                } else {
                    echo '<div class="result error">✗ Invalid email address provided</div>';
                }
            }
            ?>
        </div>

        <h2>4. Connection Test</h2>
        <div class="test-section">
            <button onclick="testSMTPConnection()">Test SMTP Connection</button>
            <div id="connection-result"></div>
            
            <script>
            function testSMTPConnection() {
                document.getElementById('connection-result').innerHTML = '<div class="result info">Testing connection...</div>';
                
                fetch('test_email_connection.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('connection-result').innerHTML = 
                                '<div class="result success">✓ ' + data.message + '</div>';
                        } else {
                            document.getElementById('connection-result').innerHTML = 
                                '<div class="result error">✗ ' + data.message + '</div>';
                        }
                    })
                    .catch(error => {
                        document.getElementById('connection-result').innerHTML = 
                            '<div class="result error">✗ Error: ' + error + '</div>';
                    });
            }
            </script>
        </div>

        <h2>5. Troubleshooting Tips</h2>
        <div class="info">
            <h3>Common Issues:</h3>
            <ul>
                <li><strong>Gmail "Less secure apps":</strong> Use App Password instead of regular password</li>
                <li><strong>Port 587 blocked:</strong> Try port 465 with SSL instead of TLS</li>
                <li><strong>Authentication failed:</strong> Verify SMTP_USER and SMTP_PASS are correct</li>
                <li><strong>SSL certificate problem:</strong> May need to update CA certificates</li>
                <li><strong>Firewall:</strong> Ensure outbound connections to SMTP server are allowed</li>
            </ul>
            
            <h3>Gmail App Password Setup:</h3>
            <ol>
                <li>Go to Google Account Settings → Security</li>
                <li>Enable 2-Step Verification</li>
                <li>Go to App Passwords</li>
                <li>Generate a new app password for "Mail"</li>
                <li>Use this 16-character password in SMTP_PASS</li>
            </ol>
        </div>

        <h2>6. Database Check</h2>
        <div class="test-section">
            <?php
            // Check if sent_emails table exists
            try {
                $db = new Database();
                $db->query("SELECT COUNT(*) as count FROM sent_emails LIMIT 1");
                $db->execute();
                echo '<div class="result success">✓ sent_emails table exists</div>';
            } catch (Exception $e) {
                echo '<div class="result error">✗ sent_emails table missing - Email logging will fail</div>';
                echo '<div class="info">';
                echo '<h4>To fix: Run this SQL in phpMyAdmin:</h4>';
                echo '<pre>CREATE TABLE IF NOT EXISTS `sent_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `body` text,
  `status` enum(\'sent\',\'failed\') DEFAULT \'sent\',
  `error` text,
  `meta` text COMMENT \'JSON metadata\',
  `sent_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_recipient` (`recipient`),
  KEY `idx_status` (`status`),
  KEY `idx_sent_at` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;</pre>';
                echo '<p>Or import: <code>/opt/lampp/htdocs/RedForce/dev/create_sent_emails_table.sql</code></p>';
                echo '</div>';
            }
            ?>
        </div>

        <h2>7. Recent Email Errors</h2>
        <div class="test-section">
            <?php
            $errorLog = ini_get('error_log');
            if ($errorLog && file_exists($errorLog)) {
                $cmd = "grep -i 'phpmailer\\|smtp\\|email\\|mail' " . escapeshellarg($errorLog) . " 2>/dev/null | tail -n 10";
                $errors = shell_exec($cmd);
                if ($errors) {
                    echo '<pre style="max-height: 300px; overflow-y: auto;">' . htmlspecialchars($errors) . '</pre>';
                } else {
                    echo '<div class="result success">No recent email-related errors found</div>';
                }
            } else {
                echo '<div class="result info">Error log not accessible</div>';
            }
            ?>
        </div>

        <h2>8. PHP Configuration</h2>
        <div class="test-section">
            <?php
            echo '<p><strong>Error Log Location:</strong> ' . ($errorLog ?: 'Not configured') . '</p>';
            echo '<p><strong>Display Errors:</strong> ' . (ini_get('display_errors') ? 'ON' : 'OFF') . '</p>';
            echo '<p><strong>Error Reporting:</strong> ' . error_reporting() . '</p>';
            echo '<p><strong>PHP Version:</strong> ' . phpversion() . '</p>';
            ?>
        </div>
    </div>
</body>
</html>
