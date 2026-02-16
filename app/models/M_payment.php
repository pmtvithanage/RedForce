<?php
class M_payment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get all payments for a client
     * @param int $client_id
     * @return array
     */
    public function getClientPayments($client_id) {
        $this->db->query('
            SELECT 
                p.*,
                s.site_name,
                s.address as site_address
            FROM payments p
            LEFT JOIN sites s ON p.site_id = s.id
            WHERE p.client_id = :client_id
            ORDER BY p.payment_date DESC, p.created_at DESC
        ');
        
        $this->db->bind(':client_id', $client_id);
        
        $results = $this->db->resultSet();
        return $results ? $results : [];
    }

    /**
     * Get payment details by ID
     * @param int $payment_id
     * @param int $client_id
     * @return object|false
     */
    public function getPaymentDetails($payment_id, $client_id) {
        $this->db->query('
            SELECT 
                p.*,
                s.site_name,
                s.address as site_address,
                s.city,
                s.district,
                u.name as client_name,
                u.email
            FROM payments p
            LEFT JOIN sites s ON p.site_id = s.id
            LEFT JOIN Users u ON p.client_id = u.id
            WHERE p.id = :payment_id 
            AND p.client_id = :client_id
        ');
        
        $this->db->bind(':payment_id', $payment_id);
        $this->db->bind(':client_id', $client_id);
        
        return $this->db->single();
    }

    /**
     * Get payment statistics for a client
     * @param int $client_id
     * @return object
     */
    public function getPaymentStats($client_id) {
        $this->db->query('
            SELECT 
                COUNT(*) as total_payments,
                SUM(CASE WHEN status = "paid" THEN amount ELSE 0 END) as total_paid,
                SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = "overdue" THEN 1 ELSE 0 END) as overdue_count,
                SUM(CASE WHEN status = "pending" THEN amount ELSE 0 END) as pending_amount,
                SUM(CASE WHEN status = "overdue" THEN amount ELSE 0 END) as overdue_amount,
                MAX(CASE WHEN status = "paid" THEN payment_date END) as last_payment_date,
                AVG(CASE WHEN status = "paid" THEN amount END) as average_payment,
                SUM(CASE WHEN status = "paid" AND MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE()) THEN amount ELSE 0 END) as this_month_paid,
                COUNT(CASE WHEN status = "paid" AND MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE()) THEN 1 END) as this_month_count
            FROM payments
            WHERE client_id = :client_id
        ');
        
        $this->db->bind(':client_id', $client_id);
        
        $result = $this->db->single();
        
        // Ensure all numeric values are properly formatted
        if ($result) {
            $result->total_paid = floatval($result->total_paid ?? 0);
            $result->pending_amount = floatval($result->pending_amount ?? 0);
            $result->overdue_amount = floatval($result->overdue_amount ?? 0);
            $result->average_payment = floatval($result->average_payment ?? 0);
            $result->this_month_paid = floatval($result->this_month_paid ?? 0);
            $result->paid_count = intval($result->paid_count ?? 0);
            $result->pending_count = intval($result->pending_count ?? 0);
            $result->overdue_count = intval($result->overdue_count ?? 0);
            $result->total_payments = intval($result->total_payments ?? 0);
            $result->this_month_count = intval($result->this_month_count ?? 0);
        }
        
        return $result;
    }

    /**
     * Get payments by status
     * @param int $client_id
     * @param string $status
     * @return array
     */
    public function getPaymentsByStatus($client_id, $status) {
        $this->db->query('
            SELECT 
                p.*,
                s.site_name,
                s.address as site_address
            FROM payments p
            LEFT JOIN sites s ON p.site_id = s.id
            WHERE p.client_id = :client_id
            AND p.status = :status
            ORDER BY p.payment_date DESC, p.created_at DESC
        ');
        
        $this->db->bind(':client_id', $client_id);
        $this->db->bind(':status', $status);
        
        $results = $this->db->resultSet();
        return $results ? $results : [];
    }

    /**
     * Get payments for a specific site
     * @param int $site_id
     * @param int $client_id
     * @return array
     */
    public function getSitePayments($site_id, $client_id) {
        $this->db->query('
            SELECT 
                p.*,
                s.site_name,
                s.address as site_address
            FROM payments p
            LEFT JOIN sites s ON p.site_id = s.id
            WHERE p.site_id = :site_id
            AND p.client_id = :client_id
            ORDER BY p.payment_date DESC, p.created_at DESC
        ');
        
        $this->db->bind(':site_id', $site_id);
        $this->db->bind(':client_id', $client_id);
        
        $results = $this->db->resultSet();
        return $results ? $results : [];
    }

    /**
     * Create a new payment record
     * @param array $data
     * @return bool
     */
    public function createPayment($data) {
        $this->db->query('
            INSERT INTO payments (
                client_id,
                site_id,
                package_request_id,
                invoice_number,
                amount,
                description,
                payment_date,
                due_date,
                status,
                payment_method,
                transaction_reference,
                created_at
            ) VALUES (
                :client_id,
                :site_id,
                :package_request_id,
                :invoice_number,
                :amount,
                :description,
                :payment_date,
                :due_date,
                :status,
                :payment_method,
                :transaction_reference,
                NOW()
            )
        ');

        // Bind values
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':site_id', $data['site_id'] ?? null);
        $this->db->bind(':package_request_id', $data['package_request_id'] ?? null);
        $this->db->bind(':invoice_number', $data['invoice_number']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':description', $data['description'] ?? 'Monthly Payment');
        $this->db->bind(':payment_date', $data['payment_date'] ?? date('Y-m-d'));
        $this->db->bind(':due_date', $data['due_date'] ?? null);
        $this->db->bind(':status', $data['status'] ?? 'pending');
        $this->db->bind(':payment_method', $data['payment_method'] ?? null);
        $this->db->bind(':transaction_reference', $data['transaction_reference'] ?? null);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update payment status
     * @param int $payment_id
     * @param string $status
     * @return bool
     */
    public function updatePaymentStatus($payment_id, $status) {
        $this->db->query('
            UPDATE payments 
            SET status = :status,
                updated_at = NOW()
            WHERE id = :payment_id
        ');

        $this->db->bind(':payment_id', $payment_id);
        $this->db->bind(':status', $status);

        return $this->db->execute();
    }

    /**
     * Record a payment transaction
     * @param int $payment_id
     * @param array $data
     * @return bool
     */
    public function recordPaymentTransaction($payment_id, $data) {
        $this->db->query('
            UPDATE payments 
            SET status = "paid",
                payment_date = :payment_date,
                payment_method = :payment_method,
                transaction_reference = :transaction_reference,
                updated_at = NOW()
            WHERE id = :payment_id
        ');

        $this->db->bind(':payment_id', $payment_id);
        $this->db->bind(':payment_date', $data['payment_date']);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':transaction_reference', $data['transaction_reference'] ?? null);

        return $this->db->execute();
    }

    /**
     * Get overdue payments
     * @param int $client_id
     * @return array
     */
    public function getOverduePayments($client_id) {
        $this->db->query('
            SELECT 
                p.*,
                s.site_name,
                s.address as site_address
            FROM payments p
            LEFT JOIN sites s ON p.site_id = s.id
            WHERE p.client_id = :client_id
            AND p.status != "paid"
            AND p.due_date < CURDATE()
            ORDER BY p.due_date ASC
        ');
        
        $this->db->bind(':client_id', $client_id);
        
        $results = $this->db->resultSet();
        return $results ? $results : [];
    }

    /**
     * Get monthly payment total for a client
     * @param int $client_id
     * @param int $year
     * @param int $month
     * @return float
     */
    public function getMonthlyPaymentTotal($client_id, $year, $month) {
        $this->db->query('
            SELECT SUM(amount) as total
            FROM payments
            WHERE client_id = :client_id
            AND YEAR(payment_date) = :year
            AND MONTH(payment_date) = :month
            AND status = "paid"
        ');
        
        $this->db->bind(':client_id', $client_id);
        $this->db->bind(':year', $year);
        $this->db->bind(':month', $month);
        
        $result = $this->db->single();
        return $result ? (float)$result->total : 0.0;
    }
}
