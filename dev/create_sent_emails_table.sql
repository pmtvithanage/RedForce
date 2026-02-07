-- Create sent_emails table for email logging
CREATE TABLE IF NOT EXISTS `sent_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `body` text,
  `status` enum('sent','failed') DEFAULT 'sent',
  `error` text,
  `meta` text COMMENT 'JSON metadata',
  `sent_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_recipient` (`recipient`),
  KEY `idx_status` (`status`),
  KEY `idx_sent_at` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
