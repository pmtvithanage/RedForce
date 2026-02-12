-- Create payments table for managing client payments and invoices
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `client_id` INT(11) NOT NULL,
  `site_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
  `amount` DECIMAL(10,2) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `payment_date` DATE DEFAULT NULL,
  `due_date` DATE DEFAULT NULL,
  `status` ENUM('pending', 'paid', 'overdue', 'cancelled') DEFAULT 'pending',
  `payment_method` VARCHAR(50) DEFAULT NULL COMMENT 'e.g., bank_transfer, cash, card, online',
  `transaction_reference` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_site_id` (`site_id`),
  KEY `idx_status` (`status`),
  KEY `idx_payment_date` (`payment_date`),
  KEY `idx_due_date` (`due_date`),
  CONSTRAINT `fk_payments_client` FOREIGN KEY (`client_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_payments_site` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing (optional)
-- Note: Update client_id and site_id values based on your database
INSERT INTO `payments` 
  (`client_id`, `site_id`, `invoice_number`, `amount`, `description`, `payment_date`, `due_date`, `status`, `payment_method`, `transaction_reference`) 
VALUES
  (7, 7, 'INV-2026-001', 50000.00, 'Security Services - January 2026', '2026-01-15', '2026-01-31', 'paid', 'bank_transfer', 'TXN-ABC123'),
  (7, 7, 'INV-2026-002', 50000.00, 'Security Services - February 2026', NULL, '2026-02-28', 'pending', NULL, NULL),
  (7, 4, 'INV-2026-003', 75000.00, 'Security Services - January 2026', '2026-01-20', '2026-01-31', 'paid', 'cash', 'CASH-2026-001'),
  (7, 4, 'INV-2026-004', 75000.00, 'Security Services - February 2026', NULL, '2026-02-28', 'pending', NULL, NULL),
  (7, 7, 'INV-2025-012', 50000.00, 'Security Services - December 2025', NULL, '2025-12-31', 'overdue', NULL, NULL);
