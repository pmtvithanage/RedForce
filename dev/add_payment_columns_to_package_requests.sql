-- Add payment tracking columns to package_requests table
-- This allows tracking payment status and linking to payments table
-- Database: redforce_db

USE redforce_db;

ALTER TABLE package_requests
ADD COLUMN payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid' AFTER status,
ADD COLUMN payment_id VARCHAR(100) DEFAULT NULL AFTER payment_status,
ADD INDEX idx_payment_status (payment_status);

-- Update existing records to have unpaid status
UPDATE package_requests SET payment_status = 'unpaid' WHERE payment_status IS NULL;

