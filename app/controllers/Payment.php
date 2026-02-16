<?php
// app/controllers/Payment.php
class Payment extends Controller {
    private $payHereModel;
    private $paymentModel;
    private $clientModel;

    public function __construct() {
        if (!class_exists('PayHereModel')) {
            require_once '../app/models/PayHereModel.php';
        }
        $this->payHereModel = new PayHereModel();
        $this->paymentModel = $this->model('M_payment');
        $this->clientModel = $this->model('M_client');
    }

    /**
     * API Endpoint: Returns JSON for PayHere popup
     * Accessed via AJAX (GET/POST)
     */
    public function generatePaymentHash() {
        header('Content-Type: application/json');
        
        try {
            // Get data from POST
            $amount = $_POST['amount'] ?? 100.00;
            $order_id = 'ORD_' . uniqid() . '_' . time();
            $item_name = $_POST['item_name'] ?? 'Security Service Payment';
            $client_id = $_SESSION['user_id'] ?? null;
            
            // Get site and request data
            $site_data = $_POST['site_data'] ?? null;
            $request_data = $_POST['request_data'] ?? null;
            
            // Clean up stale pending payment records for this client before creating new ones
            // This prevents duplicate records from multiple "Pay Now" clicks
            $cleanupModel = new Database();
            $cleanupModel->query('DELETE FROM payments WHERE client_id = :client_id AND status = "pending"');
            $cleanupModel->bind(':client_id', $client_id);
            $cleanupModel->execute();
            error_log("Payment Hash - Cleaned up stale pending records for client_id={$client_id}");
            
            // Create pending payment records for sites in payments table
            if (!empty($site_data)) {
                $siteDataArray = json_decode($site_data, true);
                if ($siteDataArray && is_array($siteDataArray)) {
                    foreach ($siteDataArray as $site) {
                        if ($site['amount'] > 0) {
                            $paymentData = [
                                'client_id' => $client_id,
                                'site_id' => $site['site_id'],
                                'package_request_id' => null,
                                'invoice_number' => $order_id . '-SITE-' . $site['site_id'],
                                'amount' => $site['amount'],
                                'description' => 'Monthly Security Service Payment - ' . $site['site_name'],
                                'payment_date' => date('Y-m-d'),
                                'due_date' => date('Y-m-d', strtotime('+30 days')),
                                'status' => 'pending',
                                'payment_method' => 'PayHere Online',
                                'transaction_reference' => $order_id
                            ];
                            $this->paymentModel->createPayment($paymentData);
                        }
                    }
                }
            }
            
            // Create pending payment records for package requests in payments table
            if (!empty($request_data)) {
                $requestDataArray = json_decode($request_data, true);
                if ($requestDataArray && is_array($requestDataArray)) {
                    foreach ($requestDataArray as $request) {
                        $paymentData = [
                            'client_id' => $client_id,
                            'site_id' => $request['site_id'] ?? null,
                            'package_request_id' => $request['request_id'],
                            'invoice_number' => $order_id . '-REQ-' . $request['request_id'],
                            'amount' => $request['amount'],
                            'description' => 'Package Request Payment - ' . ($request['package_name'] ?? '') . ' for ' . ($request['site_name'] ?? ''),
                            'payment_date' => date('Y-m-d'),
                            'due_date' => date('Y-m-d', strtotime('+30 days')),
                            'status' => 'pending',
                            'payment_method' => 'PayHere Online',
                            'transaction_reference' => $order_id
                        ];
                        $this->paymentModel->createPayment($paymentData);
                    }
                }
            }

            // Generate hash with full amount
            $hash = $this->payHereModel->generateHash($order_id, $amount);
            $merchant_id = $this->payHereModel->getMerchantId();

            // Prepare response
            $response = [
                'merchant_id' => $merchant_id,
                'order_id' => $order_id,
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => PAYMENT_CURRENCY,
                'hash' => $hash,
                'item_name' => $item_name
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            error_log("Payment Hash Generation Error: " . $e->getMessage());
            echo json_encode([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * PAYHERE NOTIFY URL (Server-to-server callback)
     * This is NOT a redirect. This is a POST from PayHere.
     */
    public function notify() {
        // Log all incoming data for debugging
        error_log("PayHere Notify Called - POST data: " . print_r($_POST, true));
        
        try {
            // Get POST data from PayHere
            $merchant_id = $_POST['merchant_id'] ?? null;
            $order_id = $_POST['order_id'] ?? null;
            $payhere_amount = $_POST['payhere_amount'] ?? null;
            $payhere_currency = $_POST['payhere_currency'] ?? null;
            $status_code = $_POST['status_code'] ?? null;
            $md5sig = $_POST['md5sig'] ?? null;
            $payment_id = $_POST['payment_id'] ?? null;

            // Verify the signature
            $local_md5sig = strtoupper(
                md5(
                    $merchant_id . 
                    $order_id . 
                    $payhere_amount . 
                    $payhere_currency . 
                    $status_code . 
                    strtoupper(md5(MERCHANT_SECRET))
                )
            );

            // Log the payment attempt
            error_log("PayHere Notify - Order: $order_id, Status: $status_code, Payment ID: $payment_id, MD5 Match: " . ($local_md5sig === $md5sig ? 'YES' : 'NO'));

            if (($local_md5sig === $md5sig) && ($status_code == 2)) {
                error_log("PayHere Notify - Payment Success for Order: $order_id");
                
                // Get all pending payment records for this order
                $db = new Database();
                $db->query('SELECT * FROM payments 
                           WHERE transaction_reference = :order_id 
                           AND status = "pending"');
                $db->bind(':order_id', $order_id);
                $pendingPayments = $db->resultSet();
                
                if ($pendingPayments) {
                    $updatedCount = 0;
                    $approvedRequests = 0;
                    
                    foreach ($pendingPayments as $payment) {
                        // Update payment status to paid
                        // Keep original order_id in transaction_reference, append PayHere payment_id
                        $db->query('UPDATE payments 
                                   SET status = "paid", 
                                       payment_method = "PayHere Online",
                                       transaction_reference = :txn_ref,
                                       updated_at = NOW()
                                   WHERE id = :id');
                        $txnRef = $payment->transaction_reference . '|PAYHERE:' . $payment_id;
                        $db->bind(':txn_ref', $txnRef);
                        $db->bind(':id', $payment->id);
                        $db->execute();
                        $updatedCount++;
                        
                        error_log("PayHere Notify - Updated payments table: payment_id={$payment->id}, status=paid, payhere_transaction_id={$payment_id}");
                        
                        // Update package_requests table with payment status and payment ID
                        if (!empty($payment->package_request_id)) {
                            error_log("PayHere Notify - BEFORE UPDATE: Updating package_request_id={$payment->package_request_id} with internal_payment_id={$payment->id}, payment_status=paid");
                            
                            // First, get current status of package request
                            $db->query('SELECT id, status, payment_status, payment_id FROM package_requests WHERE id = :request_id');
                            $db->bind(':request_id', $payment->package_request_id);
                            $currentRequest = $db->single();
                            
                            if ($currentRequest) {
                                error_log("PayHere Notify - Current package_request state: ID={$currentRequest->id}, status={$currentRequest->status}, payment_status=" . ($currentRequest->payment_status ?? 'NULL') . ", payment_id=" . ($currentRequest->payment_id ?? 'NULL'));
                            } else {
                                error_log("PayHere Notify - WARNING: Package request {$payment->package_request_id} not found in database!");
                            }
                            
                            // Update package_requests table with internal payment ID (payments.id)
                            $db->query('UPDATE package_requests 
                                       SET payment_status = "paid",
                                           payment_id = :internal_payment_id,
                                           updated_at = NOW()
                                       WHERE id = :request_id');
                            $db->bind(':internal_payment_id', $payment->id); // Use internal payment ID, not PayHere payment_id
                            $db->bind(':request_id', $payment->package_request_id);
                            $updateResult = $db->execute();
                            
                            // Check if update was successful
                            if ($updateResult) {
                                $rowsAffected = $db->rowCount();
                                error_log("PayHere Notify - SUCCESS: Package request {$payment->package_request_id} updated successfully. Rows affected: {$rowsAffected}");
                                
                                // Verify the update
                                $db->query('SELECT id, status, payment_status, payment_id FROM package_requests WHERE id = :request_id');
                                $db->bind(':request_id', $payment->package_request_id);
                                $updatedRequest = $db->single();
                                
                                if ($updatedRequest) {
                                    error_log("PayHere Notify - AFTER UPDATE: package_request state: ID={$updatedRequest->id}, status={$updatedRequest->status}, payment_status={$updatedRequest->payment_status}, payment_id={$updatedRequest->payment_id}");
                                    
                                    if ($updatedRequest->payment_status === 'paid' && $updatedRequest->payment_id == $payment->id) {
                                        error_log("PayHere Notify - ✓ VERIFICATION PASSED: Payment data correctly saved to package_requests table (payment_id={$updatedRequest->payment_id} links to payments.id={$payment->id})");
                                    } else {
                                        error_log("PayHere Notify - ✗ VERIFICATION FAILED: Payment data mismatch! Expected payment_status=paid and payment_id={$payment->id}, but got payment_status={$updatedRequest->payment_status}, payment_id={$updatedRequest->payment_id}");
                                    }
                                }
                                
                                $approvedRequests++;
                                error_log("PayHere Notify - ✓ Package request {$payment->package_request_id} payment completed successfully!");
                                error_log("PayHere Notify - Payment details: Internal Payment ID={$payment->id}, PayHere Transaction ID={$payment_id}, Status=paid");
                                error_log("PayHere Notify - Package request status: payment_status=paid, awaiting admin approval");
                            } else {
                                error_log("PayHere Notify - ✗ ERROR: Failed to update package_request {$payment->package_request_id}. Database execute returned false.");
                            }
                        }
                    }
                    
                    error_log("PayHere Notify - Updated $updatedCount payment records, $approvedRequests package requests marked as paid");
                } else {
                    error_log("PayHere Notify - No pending payments found for order: $order_id");
                }
                
                error_log("Payment processed successfully - Order: $order_id");
            } else {
                error_log("Payment verification failed - Order: $order_id, Local: $local_md5sig, Remote: $md5sig, Status: $status_code");
            }
        } catch (Exception $e) {
            error_log("PayHere Notify - Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
        }

        // MUST output exactly this
        echo "200 OK";
        exit;
    }
    
    /**
     * Client-side payment completion handler
     */
    public function complete() {
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
        }
        
        $order_id = $_GET['order_id'] ?? null;
        
        if ($order_id) {
            // Clear pending payment data
            if (isset($_SESSION['pending_payment']) && $_SESSION['pending_payment']['order_id'] === $order_id) {
                unset($_SESSION['pending_payment']);
            }
            
            flash('payment_success', 'Payment completed successfully! Your payment is being processed.');
        } else {
            flash('payment_error', 'Payment status could not be verified.');
        }
        
        redirect('client/payments');
    }
}