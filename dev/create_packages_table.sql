-- Packages Table for RedForce System
-- Created: February 11, 2026
-- Purpose: Store package definitions offered by the admin to clients

CREATE TABLE IF NOT EXISTS packages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    
    -- Package Information
    package_name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    
    -- Personnel Requirements
    number_of_officers INT NOT NULL DEFAULT 0,
    number_of_supervisors INT NOT NULL DEFAULT 0,
    number_of_caretakers INT NOT NULL DEFAULT 0,
    
    -- Pricing
    package_price DECIMAL(10, 2) NOT NULL,
    
    -- Package Image/Background
    background_image VARCHAR(255) DEFAULT NULL,
    
    -- Package Status
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    
    -- Audit Fields
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (created_by) REFERENCES Users(id) ON DELETE SET NULL,
    
    -- Indexes for Performance
    INDEX idx_status (status),
    INDEX idx_package_name (package_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default packages
INSERT INTO packages (package_name, description, number_of_officers, number_of_supervisors, number_of_caretakers, package_price, status) VALUES
('Basic Security', 'Essential security coverage for small sites', 2, 0, 0, 25000.00, 'Active'),
('Budget Guardian', 'Affordable protection for residential areas', 3, 0, 1, 35000.00, 'Active'),
('Vigilant Watch', 'Comprehensive security for medium-sized premises', 5, 1, 1, 55000.00, 'Active'),
('Pro Shield', 'Advanced security solution with supervisory support', 8, 2, 2, 85000.00, 'Active'),
('Ultra Secure', 'Maximum protection for high-security requirements', 12, 3, 3, 125000.00, 'Active'),
('Custom Package', 'Tailored security solutions based on specific needs', 0, 0, 0, 0.00, 'Active');
