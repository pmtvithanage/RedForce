-- ========================================
-- OFFICER ATTENDANCE TABLE
-- Standalone SQL file for easy installation
-- ========================================

USE redforce_db;

CREATE TABLE IF NOT EXISTS officer_attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supervisor_id INT NOT NULL COMMENT 'References Users table',
    officer_id VARCHAR(50) NOT NULL COMMENT 'Officer ID from Users table',
    officer_name VARCHAR(255) NOT NULL,
    attendance_date DATE NOT NULL,
    check_in_time TIME DEFAULT NULL,
    check_out_time TIME DEFAULT NULL,
    status ENUM('Present', 'Absent', 'Late', 'Half Day') NOT NULL DEFAULT 'Present',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supervisor_id) REFERENCES Users(id) ON DELETE CASCADE,
    INDEX idx_supervisor_date (supervisor_id, attendance_date),
    INDEX idx_officer_id (officer_id),
    INDEX idx_attendance_date (attendance_date),
    INDEX idx_status (status),
    UNIQUE KEY unique_officer_date (officer_id, attendance_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
