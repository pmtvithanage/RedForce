<?php
/**
 * SMTP Connection Test - AJAX Endpoint
 * Tests if we can connect to the SMTP server
 */

// Start output buffering immediately
ob_start();

require_once '../app/config/config.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Clear any output that might have been generated
ob_clean();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    $mail = new PHPMailer(true);
    
    // Disable debug output - we don't need it for connection test
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    
    // Use custom debug output handler to capture messages
    $debugMessages = [];
    $mail->Debugoutput = function($str, $level) use (&$debugMessages) {
        $debugMessages[] = trim($str);
    };
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port = SMTP_PORT;
    $mail->Timeout = 10; // 10 second timeout
    
    // Try to connect
    $connected = $mail->smtpConnect();
    
    if ($connected) {
        $mail->smtpClose();
        $response['success'] = true;
        $response['message'] = 'Successfully connected to SMTP server: ' . SMTP_HOST . ':' . SMTP_PORT;
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to connect to SMTP server';
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Connection error: ' . $e->getMessage();
}

// Clear output buffer and send only JSON
ob_clean();
echo json_encode($response);
ob_end_flush();
