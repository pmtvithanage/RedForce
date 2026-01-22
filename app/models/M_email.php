<?php
class M_email {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Log sent email (best-effort). If the `sent_emails` table does not exist, this will fail silently.
     * @param string $recipient
     * @param string $subject
     * @param string $body
     * @param string $status 'sent'|'failed'
     * @param string|null $error
     * @param mixed $meta
     * @return bool
     */
    public function log($recipient, $subject, $body, $status = 'sent', $error = null, $meta = null) {
        try {
            $this->db->query("INSERT INTO sent_emails (recipient, subject, body, status, error, meta, sent_at) VALUES (:recipient, :subject, :body, :status, :error, :meta, NOW())");
            $this->db->bind(':recipient', $recipient);
            $this->db->bind(':subject', $subject);
            $this->db->bind(':body', $body);
            $this->db->bind(':status', $status);
            $this->db->bind(':error', $error);
            $this->db->bind(':meta', $meta ? json_encode($meta) : null);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('M_email::log failed: ' . $e->getMessage());
            return false;
        }
    }
}
