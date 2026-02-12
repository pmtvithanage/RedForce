<?php
// app/controllers/Payment.php
class Payment extends Controller {
    private $payHereModel;

    public function __construct() {
        if (!class_exists('PayHereModel')) {
            require_once '../app/models/PayHereModel.php';
        }
        $this->payHereModel = new PayHereModel();
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

            // Generate hash
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
        error_log("PayHere Notify - Order: $order_id, Status: $status_code, Payment ID: $payment_id");

        if (($local_md5sig === $md5sig) && ($status_code == 2)) {
            // Payment success
            $client_id = $_SESSION['user_id'] ?? null;
            $this->payHereModel->savePayment($order_id, $payment_id, 'success', $payhere_amount, $client_id);
            error_log("Payment saved successfully - Order: $order_id");
        } else {
            error_log("Payment verification failed - Order: $order_id");
        }

        // MUST output exactly this
        echo "200 OK";
        exit;
    }
}