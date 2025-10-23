-- Supervisor Leave Requests Table Schema
-- This table stores leave requests submitted by supervisors

CREATE TABLE IF NOT EXISTS supervisor_leave_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supervisor_id INT NOT NULL,
    leave_type VARCHAR(50) NOT NULL,
    reason TEXT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    proof_file VARCHAR(255) DEFAULT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    admin_response TEXT DEFAULT NULL,
    reviewed_by INT DEFAULT NULL,
    reviewed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (supervisor_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES Users(id) ON DELETE SET NULL
);

-- Note: If using the existing leave_requests table, make sure it supports supervisor_id column
-- You may need to add supervisor_id column or modify the existing structure

-- Alternative: Modify existing leave_requests table to support multiple user types
-- ALTER TABLE leave_requests ADD COLUMN user_type ENUM('caretaker', 'supervisor', 'mobile_rider', 'premise_officer') DEFAULT 'caretaker';
-- ALTER TABLE leave_requests ADD COLUMN user_id INT NOT NULL;
-- Then update the model queries accordingly