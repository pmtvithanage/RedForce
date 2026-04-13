-- Supervisor attendance enhancement
-- 1) Add duty points table for each site
-- 2) Extend officer_attendance with duty point + staff role fields

CREATE TABLE IF NOT EXISTS supervisor_duty_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_id BIGINT UNSIGNED NOT NULL,
    created_by INT NOT NULL,
    duty_point_name VARCHAR(120) NOT NULL,
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_supervisor_duty_points_site FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE,
    CONSTRAINT fk_supervisor_duty_points_creator FOREIGN KEY (created_by) REFERENCES Users(id) ON DELETE CASCADE,
    INDEX idx_supervisor_duty_points_site (site_id),
    INDEX idx_supervisor_duty_points_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE officer_attendance
    ADD COLUMN IF NOT EXISTS duty_point VARCHAR(120) DEFAULT NULL AFTER notes,
    ADD COLUMN IF NOT EXISTS staff_role VARCHAR(50) DEFAULT NULL AFTER duty_point;
