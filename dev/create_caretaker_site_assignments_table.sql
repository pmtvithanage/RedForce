-- Create caretaker_site_assignments table
CREATE TABLE IF NOT EXISTS caretaker_site_assignments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    site_id BIGINT(20) UNSIGNED NOT NULL,
    caretaker_id INT(11) NOT NULL,
    assignment_start DATE NOT NULL,
    assignment_end DATE DEFAULT NULL,
    status ENUM('Active', 'Completed', 'Cancelled') DEFAULT 'Active',
    assigned_by INT(11) DEFAULT NULL,
    assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE,
    FOREIGN KEY (caretaker_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES Users(id) ON DELETE SET NULL,
    
    INDEX idx_site_id (site_id),
    INDEX idx_caretaker_id (caretaker_id),
    INDEX idx_status (status),
    INDEX idx_assigned_by (assigned_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
