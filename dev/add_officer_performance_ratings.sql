-- Site-scoped performance ratings for premise officers.
-- Only client and supervisor roles are allowed as reviewers at application level.

CREATE TABLE IF NOT EXISTS officer_performance_ratings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id BIGINT UNSIGNED NOT NULL,
    officer_user_id INT NOT NULL,
    reviewer_user_id INT NOT NULL,
    reviewer_role ENUM('client','supervisor') NOT NULL,
    rating_date DATE NOT NULL,
    rating_value TINYINT UNSIGNED NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_officer_rating (site_id, officer_user_id, reviewer_user_id, reviewer_role, rating_date),
    KEY idx_rated_officer (officer_user_id),
    KEY idx_rating_site (site_id),
    KEY idx_rating_reviewer (reviewer_user_id, reviewer_role),
    KEY idx_rating_date (rating_date),
    CONSTRAINT fk_officer_ratings_site FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE,
    CONSTRAINT fk_officer_ratings_officer FOREIGN KEY (officer_user_id) REFERENCES Users(id) ON DELETE CASCADE,
    CONSTRAINT fk_officer_ratings_reviewer FOREIGN KEY (reviewer_user_id) REFERENCES Users(id) ON DELETE CASCADE,
    CONSTRAINT chk_rating_value CHECK (rating_value BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
