<?php
// app/models/PayHereModel.php
class PayHereModel {
    private $merchant_id;
    private $merchant_secret;
    private $currency;
    private $db;

    public function __construct() {
        $this->merchant_id = MERCHANT_ID;
        $this->merchant_secret = MERCHANT_SECRET;
        $this->currency = PAYMENT_CURRENCY;
        $this->db = new Database();
    }

    /**
     * Generate PayHere MD5 Hash
     * Formula: strtoupper(md5(merchant_id + order_id + amount + currency + strtoupper(md5(merchant_secret))))
     */
    public function generateHash($order_id, $amount) {
        $hashed_secret = strtoupper(md5($this->merchant_secret));
        $hash = strtoupper(md5(
            $this->merchant_id .
            $order_id .
            number_format($amount, 2, '.', '') .
            $this->currency .
            $hashed_secret
        ));
        return $hash;
    }

    /**
     * Get Merchant ID (to send to frontend)
     */
    public function getMerchantId() {
        return $this->merchant_id;
    }

    /**
     * Save payment record after verification (Called by notify_url)
     */
    public function savePayment($order_id, $payment_id, $status, $amount, $client_id = null) {
        $this->db->query('INSERT INTO payments (client_id, invoice_number, amount, description, payment_date, status, payment_method, transaction_reference) VALUES (:client_id, :invoice_number, :amount, :description, :payment_date, :status, :payment_method, :transaction_reference)');
        $this->db->bind(':client_id', $client_id);
        $this->db->bind(':invoice_number', $order_id);
        $this->db->bind(':amount', $amount);
        $this->db->bind(':description', 'Monthly Security Service Payment - PayHere');
        $this->db->bind(':payment_date', date('Y-m-d'));
        $this->db->bind(':status', 'paid');
        $this->db->bind(':payment_method', 'online');
        $this->db->bind(':transaction_reference', $payment_id);
        return $this->db->execute();
    }
}