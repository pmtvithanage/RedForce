-- Add pricing fields for Custom Package
-- These fields allow clients to build their own package by selecting quantity

ALTER TABLE packages ADD COLUMN IF NOT EXISTS price_per_officer DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Price for each officer in custom packages';
ALTER TABLE packages ADD COLUMN IF NOT EXISTS price_per_supervisor DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Price for each supervisor in custom packages';
ALTER TABLE packages ADD COLUMN IF NOT EXISTS price_per_caretaker DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Price for each caretaker in custom packages';

-- Set default prices for the Custom Package
UPDATE packages 
SET price_per_officer = 15000.00,
    price_per_supervisor = 20000.00,
    price_per_caretaker = 12000.00
WHERE package_name = 'Custom Package';
