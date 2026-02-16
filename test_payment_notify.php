<?php
/**
 * Test script to manually trigger PayHere notify callback
 * Usage: Run this script to test the latest pending payment
 */

// Bootstrap the application
require_once 'app/bootloader.php';

// Get the latest pending order_id
$db = new Database();
$db->query("SELECT id, order_id, amount, client_id FROM payment_pending WHERE status = 'pending' ORDER BY created_at DESC LIMIT 1");
$pendingPayment = $db->single();

if ($pendingPayment) {
    $order_id = $pendingPayment->order_id;
    $amount = $pendingPayment->amount;
    
    echo "Testing payment notification for order: $order_id\n";
    echo "Amount: LKR $amount\n\n";
    
    // Simulate PayHere POST data
    $merchant_id = MERCHANT_ID;
    $payhere_amount = number_format($amount, 2, '.', '');
    $payhere_currency = PAYMENT_CURRENCY;
    $status_code = 2; // Success
    $payment_id = 'TEST_' . time(); // Simulated transaction ID
    
    // Generate MD5 signature
    $md5sig = strtoupper(
        md5(
            $merchant_id . 
            $order_id . 
            $payhere_amount . 
            $payhere_currency . 
            $status_code . 
            strtoupper(md5(MERCHANT_SECRET))
        )
    );
    
    // Prepare POST data
    $postData = [
        'merchant_id' => $merchant_id,
        'order_id' => $order_id,
        'payhere_amount' => $payhere_amount,
        'payhere_currency' => $payhere_currency,
        'status_code' => $status_code,
        'md5sig' => $md5sig,
        'payment_id' => $payment_id,
        'custom_1' => '',
        'custom_2' => ''
    ];
    
    echo "Sending notification to notify endpoint...\n";
    echo "POST Data:\n";
    print_r($postData);
    echo "\n";
    
    // Send request to notify endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, URL_ROOT . '/payment/notify');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch) . "\n";
    } else {
        echo "Response Code: $httpCode\n";
        echo "Response: $response\n";
    }
    
    curl_close($ch);
    
    echo "\n\nChecking payment_pending table...\n";
    $db->query("SELECT status, payment_id, payments_id FROM payment_pending WHERE order_id = :order_id");
    $db->bind(':order_id', $order_id);
    $checkResult = $db->single();
    
    if ($checkResult) {
        echo "Status: " . $checkResult->status . "\n";
        echo "PayHere Payment ID: " . ($checkResult->payment_id ?: 'NULL') . "\n";
        echo "Payments Table ID: " . ($checkResult->payments_id ?: 'NULL') . "\n";
    }
    
    echo "\n\nChecking payments table...\n";
    $db->query("SELECT id, invoice_number, amount, status FROM payments WHERE invoice_number LIKE :order_id ORDER BY created_at DESC LIMIT 5");
    $db->bind(':order_id', $order_id . '%');
    $paymentsResult = $db->resultSet();
    
    if ($paymentsResult && count($paymentsResult) > 0) {
        echo "Payment records created:\n";
        foreach ($paymentsResult as $payRow) {
            echo "  - ID: {$payRow->id}, Invoice: {$payRow->invoice_number}, Amount: LKR {$payRow->amount}, Status: {$payRow->status}\n";
        }
    } else {
        echo "No payment records found!\n";
    }
    
} else {
    echo "No pending payments found in payment_pending table.\n";
}
