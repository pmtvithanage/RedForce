-- ============================================
-- INCIDENT REVIEWS TABLE
-- Stores reviews and comments added by mobile riders to incidents
-- ============================================

CREATE TABLE IF NOT EXISTS incident_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    incident_id INT NOT NULL COMMENT 'Foreign key to incident_reports table',
    user_id INT NOT NULL COMMENT 'User who added the review',
    reviewer_name VARCHAR(255) NOT NULL COMMENT 'Name of the reviewer',
    review_type ENUM('Update', 'Action', 'Comment', 'Follow-up') DEFAULT 'Comment' COMMENT 'Type of review',
    review_title VARCHAR(255) NOT NULL COMMENT 'Brief title for the review',
    review_details TEXT NOT NULL COMMENT 'Detailed review content',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    CONSTRAINT fk_review_incident FOREIGN KEY (incident_id) REFERENCES incident_reports(id) ON DELETE CASCADE,
    CONSTRAINT fk_review_user FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_incident_id (incident_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    INDEX idx_review_type (review_type)
);
