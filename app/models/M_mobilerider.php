<?php
class M_mobilerider
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addNote($data)
    {
        $this->db->query("INSERT INTO Notes ( userID, title, content, created_at) VALUES (:userID, :title, :content, NOW())");

        // Bind values
        $this->db->bind(":userID", $data['userID']);
        $this->db->bind(":title", $data['title']);
        $this->db->bind(":content", $data['content']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllNotes()
    {
        $this->db->query("SELECT * FROM Notes");
        return $this->db->resultSet();
    }

    public function deleteNoteById($id)
    {
        $this->db->query("DELETE FROM Notes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateNoteById($id, $title, $content)
    {
        $this->db->query("UPDATE Notes SET title = :title, content = :content WHERE id = :id");
        $this->db->bind(":id", $id);
        $this->db->bind(":title", $title);
        $this->db->bind(":content", $content);
        return $this->db->execute();
    }

    public function addIncident($data)
    {
        // Prepare the SQL query - Updated to include new fields
        $this->db->query("
        INSERT INTO incident_reports 
        (user_id, officer_name, officer_role, site_id, property_site, incident_type, 
         incident_date, incident_time, incident_description, action_taken, 
         severity, priority, people_involved, additional_details, media_files, 
         latitude, longitude, status, created_at) 
        VALUES 
        (:user_id, :officer_name, :officer_role, :site_id, :property_site, :incident_type, 
         :incident_date, :incident_time, :incident_description, :action_taken, 
         :severity, :priority, :people_involved, :additional_details, :media_files, 
         :latitude, :longitude, :status, NOW())");

        // Bind parameters
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':officer_name', $data['officer_name'] ?? '');
        $this->db->bind(':officer_role', $data['officer_role'] ?? 'mobile rider');
        $this->db->bind(':site_id', $data['site_id'] ?? null);
        $this->db->bind(':property_site', $data['property_site'] ?? $data['site_id'] ?? null);
        $this->db->bind(':incident_type', $data['incident_type']);
        $this->db->bind(':incident_date', $data['incident_date']);
        $this->db->bind(':incident_time', $data['incident_time']);
        $this->db->bind(':incident_description', $data['incident_description']);
        $this->db->bind(':action_taken', $data['action_taken'] ?? '');
        $this->db->bind(':severity', $data['severity'] ?? null);
        $this->db->bind(':priority', $data['priority'] ?? 'Medium');
        $this->db->bind(':people_involved', $data['people_involved'] ?? null);
        $this->db->bind(':additional_details', $data['additional_details'] ?? null);
        $this->db->bind(':media_files', $data['media_files'] ?? null);
        $this->db->bind(':latitude', $data['latitude'] ?? null);
        $this->db->bind(':longitude', $data['longitude'] ?? null);
        $this->db->bind(':status', $data['status'] ?? 'Pending');

        // Execute
        return $this->db->execute();
    }

    public function getAllIncidents()
    {
        $this->db->query("SELECT * FROM incident_reports");
        return $this->db->resultSet();
    }
    
    // Get incidents for a specific user (mobile rider)
    public function getIncidentsByUserId($userId)
    {
        $this->db->query("
            SELECT 
                ir.*,
                s.site_name,
                COALESCE(ir.status, 'Pending') as status,
                COALESCE(ir.priority, ir.severity, 'Medium') as priority,
                u.name as reporter_name,
                u.role as reporter_role
            FROM incident_reports ir
            LEFT JOIN sites s ON ir.site_id = s.id
            LEFT JOIN Users u ON ir.user_id = u.id
            WHERE ir.site_id IN (
                SELECT DISTINCT rs.site_id 
                FROM route_sites rs
                INNER JOIN routes r ON rs.route_id = r.id
                WHERE r.assigned_rider_id = :user_id
            )
            ORDER BY ir.created_at DESC
        ");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    // Create new incident report with new schema
    public function createIncidentReport($data)
    {
        $this->db->query("
            INSERT INTO incident_reports 
            (user_id, property_site, incident_type, incident_date, incident_time, 
             incident_description, action_taken, severity, additional_details, 
             media_files, created_at) 
            VALUES 
            (:user_id, :site_id, :incident_type, :incident_date, :incident_time, 
             :description, :actions_taken, :priority, :people_involved, 
             :evidence_files, NOW())
        ");
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':site_id', $data['site_id']);
        $this->db->bind(':incident_type', $data['incident_type']);
        $this->db->bind(':incident_date', $data['incident_date']);
        $this->db->bind(':incident_time', $data['incident_time']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':actions_taken', $data['actions_taken']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':people_involved', $data['people_involved']);
        $this->db->bind(':evidence_files', $data['evidence_files']);
        
        return $this->db->execute();
    }

    public function getIncidentById($id)
    {
        $this->db->query("
            SELECT 
                ir.*,
                s.site_name,
                COALESCE(ir.status, 'Pending') as status,
                COALESCE(ir.priority, ir.severity, 'Medium') as priority
            FROM incident_reports ir
            LEFT JOIN sites s ON ir.site_id = s.id
            WHERE ir.id = :id 
            LIMIT 1
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateIncident($data)
    {
        $this->db->query("
        UPDATE incident_reports
        SET 
            property_site = :property_site,
            incident_type = :incident_type,
            incident_description = :incident_description,
            action_taken = :action_taken,
            severity = :severity,
            media_files = :media_files
        WHERE id = :id
    ");

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':property_site', $data['property_site']);
        $this->db->bind(':incident_type', $data['incident_type']);
        $this->db->bind(':incident_description', $data['incident_description']);
        $this->db->bind(':action_taken', $data['action_taken']);
        $this->db->bind(':severity', $data['severity']);
        $this->db->bind(':media_files', $data['media_files']);

        return $this->db->execute();
    }

    // Get total incident count
    public function getTotalIncidents()
    {
        $this->db->query("SELECT COUNT(*) as total FROM incident_reports");
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Get count by specific severity
    public function getIncidentCountBySeverity($severity)
    {
        $this->db->query("SELECT COUNT(*) as count FROM incident_reports WHERE severity LIKE :severity");
        $this->db->bind(':severity', '%' . $severity . '%');
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Get all incident statistics in one call
    public function getAllIncidentStatistics()
    {
        $total = $this->getTotalIncidents();
        
        // Since status column doesn't exist, we'll assume all incidents are 'open'
        // Modify this logic based on your actual business requirements
        $open = $total;
        $progress = 0;
        $resolved = 0;
        
        $critical = $this->getIncidentCountBySeverity('critical');
        $high = $this->getIncidentCountBySeverity('high');
        $medium = $this->getIncidentCountBySeverity('medium');
        $low = $this->getIncidentCountBySeverity('low');

        return [
            'total' => $total,
            'open' => $open,
            'progress' => $progress,
            'resolved' => $resolved,
            'critical' => $critical,
            'high' => $high,
            'medium' => $medium,
            'low' => $low
        ];
    }

    public function deleteIncidentById($id)
    {
        $this->db->query('DELETE FROM incident_reports WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Leave Request CRUD Methods
    
    // Get all leave requests for a mobile rider
    public function getLeaveRequests($mobilerider_id)
    {
        $this->db->query("SELECT * FROM mobile_rider_leave_requests WHERE mobile_rider_id = :mobilerider_id ORDER BY created_at DESC");
        $this->db->bind(':mobilerider_id', $mobilerider_id);
        return $this->db->resultSet();
    }

    // Get a single leave request by ID
    public function getLeaveRequestById($id)
    {
        $this->db->query("SELECT * FROM mobile_rider_leave_requests WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add a new leave request
    public function addLeaveRequest($data)
    {
        $this->db->query("INSERT INTO mobile_rider_leave_requests 
        (mobile_rider_id, leave_type, reason, start_date, end_date, proof_file, status, created_at) 
        VALUES (:mobile_rider_id, :leave_type, :reason, :start_date, :end_date, :proof_file, 'Pending', NOW())");

        $this->db->bind(':mobile_rider_id', $data['mobile_rider_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);

        return $this->db->execute();
    }

    // Update an existing leave request
    public function updateLeaveRequest($data)
    {
        $this->db->query("UPDATE mobile_rider_leave_requests 
        SET leave_type = :leave_type, reason = :reason, start_date = :start_date, 
            end_date = :end_date, proof_file = :proof_file, updated_at = NOW()
        WHERE id = :id AND mobile_rider_id = :mobile_rider_id");

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':mobile_rider_id', $data['mobile_rider_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);

        return $this->db->execute();
    }

    // Delete a leave request
    public function deleteLeaveRequest($id, $mobile_rider_id)
    {
        $this->db->query("DELETE FROM mobile_rider_leave_requests WHERE id = :id AND mobile_rider_id = :mobile_rider_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':mobile_rider_id', $mobile_rider_id);

        return $this->db->execute();
    }

    // Get leave request statistics for mobile rider
    public function getLeaveStats($mobile_rider_id)
    {
        $this->db->query("
        SELECT 
            COUNT(*) AS total_requests,
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_requests,
            SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) AS approved_requests,
            SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) AS rejected_requests
        FROM mobile_rider_leave_requests
        WHERE mobile_rider_id = :mobile_rider_id
    ");
        $this->db->bind(':mobile_rider_id', $mobile_rider_id);
        return $this->db->single();
    }

    // Get recent leave requests for mobile rider dashboard
    public function getRecentLeaveRequests($mobile_rider_id, $limit = 5)
    {
        $this->db->query("
        SELECT * FROM mobile_rider_leave_requests
        WHERE mobile_rider_id = :mobile_rider_id
        ORDER BY created_at DESC
        LIMIT :limit
    ");
        $this->db->bind(':mobile_rider_id', $mobile_rider_id);
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // ========================================
    // ROUTE AND SITE MANAGEMENT
    // ========================================

    /**
     * Get mobile rider record by user ID
     * Using mobile_rider_full_details view for complete information
     */
    public function getMobileRiderByUserId($userId)
    {
        $this->db->query("SELECT * FROM mobile_rider_full_details WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /**
     * Get route information for a mobile rider by their user ID
     * Routes are assigned via routes.assigned_rider_id = Users.id
     */
    public function getRouteByUserId($userId)
    {
        if (empty($userId)) {
            return null;
        }
        
        $this->db->query("
            SELECT r.*, 
                   COUNT(rs.site_id) as site_count
            FROM routes r
            LEFT JOIN route_sites rs ON r.id = rs.route_id
            WHERE r.assigned_rider_id = :user_id
            GROUP BY r.id
            LIMIT 1
        ");
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /**
     * Get route information by route ID
     */
    public function getRouteByRiderId($routeId)
    {
        if (empty($routeId)) {
            return null;
        }
        
        $this->db->query("
            SELECT r.*, 
                   COUNT(rs.site_id) as site_count
            FROM routes r
            LEFT JOIN route_sites rs ON r.id = rs.route_id
            WHERE r.id = :route_id
            GROUP BY r.id
        ");
        $this->db->bind(':route_id', $routeId);
        return $this->db->single();
    }

    /**
     * Get all sites assigned to a route with client information
     */
    public function getRouteSites($routeId)
    {
        $this->db->query("
            SELECT s.*, 
                   c.contact_person_name as client_name, 
                   u.name as client_user_name
            FROM route_sites rs
            JOIN sites s ON rs.site_id = s.id
            LEFT JOIN Clients c ON s.client_id = c.id
            LEFT JOIN Users u ON c.user_id = u.id
            WHERE rs.route_id = :route_id
            ORDER BY s.site_name
        ");
        $this->db->bind(':route_id', $routeId);
        return $this->db->resultSet();
    }

    /**
     * Get route sites with coordinates for map display
     */
    public function getRouteSitesWithCoords($routeId)
    {
        $this->db->query("
            SELECT s.id, s.site_name, s.address, s.latitude, s.longitude
            FROM route_sites rs
            JOIN sites s ON rs.site_id = s.id
            WHERE rs.route_id = :route_id
            ORDER BY s.site_name
        ");
        $this->db->bind(':route_id', $routeId);
        return $this->db->resultSet();
    }

    /**
     * Get site visit statistics for mobile rider
     * NOTE: Only counts visits from TODAY (resets daily at midnight)
     */
    public function getSiteVisitStats($routeId)
    {
        $this->db->query("
            SELECT 
                COUNT(rs.site_id) as total_sites,
                COUNT(sv.id) as sites_visited
            FROM route_sites rs
            LEFT JOIN site_visits sv ON rs.site_id = sv.site_id AND DATE(sv.visit_time) = CURDATE()
            WHERE rs.route_id = :route_id
        ");
        $this->db->bind(':route_id', $routeId);
        return $this->db->single();
    }

    /**
     * Mark a site as visited (with visit details)
     * Now accepts an array of visit data including form fields
     */
    public function markSiteAsVisited($visitData)
    {
        $siteId = $visitData['site_id'];
        $userId = $visitData['user_id'];
        
        // Check if already visited today
        $this->db->query("
            SELECT id FROM site_visits 
            WHERE site_id = :site_id 
            AND user_id = :user_id 
            AND DATE(visit_time) = CURDATE()
        ");
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':user_id', $userId);
        $existing = $this->db->single();

        if ($existing) {
            return ['success' => false, 'message' => 'Site already marked as visited today'];
        }

        // Insert new visit record with all form data
        $this->db->query("
            INSERT INTO site_visits (
                site_id, 
                user_id, 
                visit_time, 
                officer_attendance_satisfactory,
                officer_activities,
                site_condition,
                issues_found,
                notes
            ) 
            VALUES (
                :site_id, 
                :user_id, 
                NOW(),
                :officer_attendance_satisfactory,
                :officer_activities,
                :site_condition,
                :issues_found,
                :notes
            )
        ");
        
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':officer_attendance_satisfactory', $visitData['officer_attendance_satisfactory'] ?? 0);
        $this->db->bind(':officer_activities', $visitData['officer_activities'] ?? null);
        $this->db->bind(':site_condition', $visitData['site_condition'] ?? 'Good');
        $this->db->bind(':issues_found', $visitData['issues_found'] ?? null);
        $this->db->bind(':notes', $visitData['notes'] ?? null);
        
        if ($this->db->execute()) {
            return ['success' => true, 'message' => 'Site visit report submitted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to submit site visit report'];
        }
    }

    /**
     * Check if a site has been visited today
     * NOTE: Returns false for visits from previous days (resets daily)
     */
    public function isSiteVisitedToday($siteId, $userId)
    {
        $this->db->query("
            SELECT id FROM site_visits 
            WHERE site_id = :site_id 
            AND user_id = :user_id 
            AND DATE(visit_time) = CURDATE()
        ");
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':user_id', $userId);
        return $this->db->single() !== false;
    }

    /**
     * Insert recent activity for mobile rider
     */
    public function insertRecentActivity($title, $description, $type, $userId = null) {
        // If userId is not provided, get it from session
        if ($userId === null) {
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        }
        
        $this->db->query('
            INSERT INTO recent_activities (user_id, activity_titel, activity_details, activity_type) 
            VALUES (:user_id, :title, :description, :type)
        ');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':title', $title);
        $this->db->bind(':description', $description);
        $this->db->bind(':type', $type);
        
        return $this->db->execute();
    }

    /**
     * Get recent activities for current mobile rider
     */
    public function getRecentActivities($userId, $limit = 50) {
        $this->db->query('
            SELECT * FROM recent_activities 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC 
            LIMIT :limit
        ');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
    
    /**
     * Log activity for mobile rider
     */
    public function logActivity($data) {
        $this->db->query('
            INSERT INTO recent_activities 
            (user_id, activity_type, activity_titel, activity_details, created_at) 
            VALUES 
            (:user_id, :activity_type, :activity_titel, :activity_details, NOW())
        ');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':activity_type', $data['activity_type']);
        $this->db->bind(':activity_titel', $data['activity_titel']);
        $this->db->bind(':activity_details', $data['activity_details']);
        
        return $this->db->execute();
    }
    
    /**
     * Add review to incident
     */
    public function addIncidentReview($data) {
        $this->db->query('
            INSERT INTO incident_reviews 
            (incident_id, user_id, reviewer_name, review_type, review_title, review_details, created_at) 
            VALUES 
            (:incident_id, :user_id, :reviewer_name, :review_type, :review_title, :review_details, NOW())
        ');
        
        $this->db->bind(':incident_id', $data['incident_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':reviewer_name', $data['reviewer_name']);
        $this->db->bind(':review_type', $data['review_type']);
        $this->db->bind(':review_title', $data['review_title']);
        $this->db->bind(':review_details', $data['review_details']);
        
        return $this->db->execute();
    }
    
    /**
     * Get all reviews for an incident
     */
    public function getIncidentReviews($incidentId) {
        $this->db->query('
            SELECT 
                ir.*,
                u.profile_image,
                u.name as user_name,
                u.role
            FROM incident_reviews ir
            LEFT JOIN Users u ON ir.user_id = u.id
            WHERE ir.incident_id = :incident_id
            ORDER BY ir.created_at DESC
        ');
        
        $this->db->bind(':incident_id', $incidentId);
        return $this->db->resultSet();
    }

    /**
     * Calculate average response time for mobile rider's incidents
     * Response time = time from incident creation to first review
     */
    public function getAverageResponseTime($userId) {
        $this->db->query("
            SELECT 
                AVG(TIMESTAMPDIFF(MINUTE, ir.created_at, rev.first_review_time)) as avg_minutes
            FROM incident_reports ir
            INNER JOIN (
                SELECT incident_id, MIN(created_at) as first_review_time
                FROM incident_reviews
                GROUP BY incident_id
            ) rev ON ir.id = rev.incident_id
            WHERE ir.user_id = :user_id
            AND ir.status != 'Pending'
        ");
        
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        
        if (!$result || $result->avg_minutes === null) {
            return 'N/A';
        }
        
        $avgMinutes = round($result->avg_minutes);
        
        // Format the response time
        if ($avgMinutes < 60) {
            return $avgMinutes . ' min';
        } else {
            $hours = floor($avgMinutes / 60);
            $minutes = $avgMinutes % 60;
            return $hours . 'h ' . $minutes . 'm';
        }
    }
}
