-- Equipment Requests Table for RedForce System
-- Created: November 27, 2025
-- Purpose: Track equipment/supplies requests from caretakers with cost management

CREATE TABLE IF NOT EXISTS equipment_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    
    -- Request Information
    caretaker_id INT NOT NULL,
    equipment_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    
    -- Cost Tracking
    estimated_cost DECIMAL(10, 2) NOT NULL DEFAULT 0.00,      -- Caretaker's estimate
    actual_cost DECIMAL(10, 2) NULL DEFAULT NULL,             -- Supervisor's actual cost
    total_cost DECIMAL(10, 2) GENERATED ALWAYS AS (quantity * IFNULL(actual_cost, estimated_cost)) STORED,
    
    -- Request Details
    reason TEXT NOT NULL,
    priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    
    -- Dates
    requested_date DATE NOT NULL,
    approved_date DATE NULL,
    
    -- Supervisor Information
    supervisor_notes TEXT NULL,
    approved_by INT NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (caretaker_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES Users(id) ON DELETE SET NULL,
    
    -- Indexes for Performance
    INDEX idx_caretaker_id (caretaker_id),
    INDEX idx_status (status),
    INDEX idx_requested_date (requested_date),
    INDEX idx_status_date (status, requested_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing
INSERT INTO equipment_requests 
    (caretaker_id, equipment_name, quantity, estimated_cost, reason, priority, requested_date, status) 
VALUES 
    (1, 'Flashlight', 2, 500.00, 'Previous flashlights broken during night patrol', 'High', '2025-11-26', 'Pending'),
    (1, 'Uniform Shirt', 1, 2500.00, 'Current uniform worn out', 'Medium', '2025-11-25', 'Approved'),
    (1, 'Radio', 1, 3000.00, 'Backup radio needed', 'Low', '2025-11-24', 'Rejected');

-- Update approved record with actual cost
UPDATE equipment_requests 
SET actual_cost = 2300.00, 
    approved_date = '2025-11-25',
    supervisor_notes = 'Approved. Purchased at discount.'
WHERE id = 2;
