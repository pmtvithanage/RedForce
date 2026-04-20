<?php
class M_supervisor
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Get all leave requests for a supervisor
    public function getLeaveRequests($supervisor_id)
    {
        $this->db->query("SELECT * FROM leave_requests WHERE caretaker_id = :supervisor_id ORDER BY created_at DESC");
        $this->db->bind(':supervisor_id', $supervisor_id);
        return $this->db->resultSet();
    }

    // Get a single leave request by ID
    public function getLeaveRequestById($id)
    {
        $this->db->query("SELECT * FROM leave_requests WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add a new leave request
    public function addLeaveRequest($data)
    {
        $this->db->query("INSERT INTO leave_requests (caretaker_id, leave_type, reason, start_date, end_date, proof_file, status, created_at) 
                         VALUES (:supervisor_id, :leave_type, :reason, :start_date, :end_date, :proof_file, 'Pending', NOW())");

        $this->db->bind(':supervisor_id', $data['supervisor_id']);
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
        $this->db->query("UPDATE leave_requests 
                         SET leave_type = :leave_type, reason = :reason, start_date = :start_date, 
                             end_date = :end_date, proof_file = :proof_file, updated_at = NOW()
                         WHERE id = :id AND caretaker_id = :supervisor_id");

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':supervisor_id', $data['supervisor_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);

        return $this->db->execute();
    }

    // Delete a leave request
    public function deleteLeaveRequest($id, $supervisor_id)
    {
        $this->db->query("DELETE FROM leave_requests WHERE id = :id AND caretaker_id = :supervisor_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':supervisor_id', $supervisor_id);

        return $this->db->execute();
    }

    // Get leave request statistics for supervisor
    public function getLeaveStats($supervisor_id)
    {
        $this->db->query("SELECT 
                            COUNT(*) as total_requests,
                            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_requests,
                            SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved_requests,
                            SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected_requests
                         FROM leave_requests 
                         WHERE caretaker_id = :supervisor_id");
        $this->db->bind(':supervisor_id', $supervisor_id);
        return $this->db->single();
    }

    // Get recent leave requests for supervisor dashboard
    public function getRecentLeaveRequests($supervisor_id, $limit = 5)
    {
        $this->db->query("SELECT * FROM leave_requests 
                         WHERE caretaker_id = :supervisor_id 
                         ORDER BY created_at DESC 
                         LIMIT :limit");
        $this->db->bind(':supervisor_id', $supervisor_id);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    // Mark attendance for an officer via QR scanner
    public function markAttendance($officer_id, $supervisor_id, $timestamp)
    {
        // Check if attendance already marked for today
        $this->db->query("SELECT id FROM attendance 
                         WHERE officer_id = :officer_id 
                         AND DATE(timestamp) = CURDATE()");
        $this->db->bind(':officer_id', $officer_id);
        $existing = $this->db->single();

        if ($existing) {
            return 'duplicate'; // Already marked today
        }

        // Insert new attendance record
        $this->db->query("INSERT INTO attendance (officer_id, supervisor_id, timestamp, status, created_at) 
                         VALUES (:officer_id, :supervisor_id, :timestamp, 'present', NOW())");

        $this->db->bind(':officer_id', $officer_id);
        $this->db->bind(':supervisor_id', $supervisor_id);
        $this->db->bind(':timestamp', $timestamp);

        if ($this->db->execute()) {
            return true;
        }

        return false;
    }

    // ==========================================
    // OFFICER ATTENDANCE + DUTY POINT METHODS
    // ==========================================

    public function getSupervisorPrimarySiteId($supervisor_id)
    {
        $this->db->query('
            SELECT site_id
            FROM officer_site_assignments
            WHERE officer_id = :supervisor_id
              AND status = "Active"
              AND shift_type = "Supervisor"
            ORDER BY id DESC
            LIMIT 1
        ');
        $this->db->bind(':supervisor_id', $supervisor_id);

        $assignment = $this->db->single();
        return $assignment ? (int)$assignment->site_id : null;
    }

    public function getSupervisorAttendanceSite($supervisor_id)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisor_id);
        if (!$siteId) {
            return null;
        }

        $this->db->query('SELECT id, site_name, address, city, district FROM sites WHERE id = :site_id LIMIT 1');
        $this->db->bind(':site_id', $siteId);
        return $this->db->single();
    }

    public function getAttendanceEligibleStaff($supervisor_id)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisor_id);
        if (!$siteId) {
            return [];
        }

        $this->db->query('
            (
                SELECT DISTINCT
                    u.id AS staff_user_id,
                    u.userID AS staff_code,
                    u.name AS staff_name,
                    "Premise Officer" AS staff_role
                FROM officer_site_assignments osa
                INNER JOIN Users u ON u.id = osa.officer_id
                WHERE osa.site_id = :site_id
                  AND osa.status = "Active"
                  AND (osa.shift_type IS NULL OR osa.shift_type <> "Supervisor")
                  AND u.role IN ("Premise Officer", "premise officer")
            )
            UNION ALL
            (
                SELECT DISTINCT
                    u.id AS staff_user_id,
                    u.userID AS staff_code,
                    u.name AS staff_name,
                    "Caretaker" AS staff_role
                FROM caretaker_site_assignments csa
                INNER JOIN Users u ON u.id = csa.caretaker_id
                WHERE csa.site_id = :site_id
                  AND csa.status = "Active"
                  AND u.role IN ("Caretaker", "Care Taker", "Care-Taker", "caretaker")
            )
            ORDER BY staff_name ASC
        ');
        $this->db->bind(':site_id', $siteId);
        return $this->db->resultSet();
    }

    public function getValidAttendanceStaffMember($supervisor_id, $staff_user_id)
    {
        $staff_user_id = (int)$staff_user_id;
        if ($staff_user_id <= 0) {
            return null;
        }

        $eligibleStaff = $this->getAttendanceEligibleStaff($supervisor_id);
        foreach ($eligibleStaff as $staff) {
            if ((int)$staff->staff_user_id === $staff_user_id) {
                return $staff;
            }
        }

        return null;
    }

    public function getAttendanceDutyPoints($supervisor_id)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisor_id);
        if (!$siteId) {
            return [];
        }

        $this->db->query('
            SELECT id, duty_point_name
            FROM supervisor_duty_points
            WHERE site_id = :site_id
              AND status = "Active"
            ORDER BY duty_point_name ASC
        ');
        $this->db->bind(':site_id', $siteId);
        return $this->db->resultSet();
    }

    public function addAttendanceDutyPoint($supervisor_id, $duty_point_name)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisor_id);
        if (!$siteId) {
            return false;
        }

        $this->db->query('
            INSERT INTO supervisor_duty_points (site_id, created_by, duty_point_name, status)
            VALUES (:site_id, :created_by, :duty_point_name, "Active")
        ');
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':created_by', $supervisor_id);
        $this->db->bind(':duty_point_name', $duty_point_name);
        return $this->db->execute();
    }

    public function updateAttendanceDutyPoint($supervisor_id, $duty_point_id, $duty_point_name)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisor_id);
        if (!$siteId || !$duty_point_id) {
            return false;
        }

        $this->db->query('
            UPDATE supervisor_duty_points
            SET duty_point_name = :duty_point_name
            WHERE id = :id
              AND site_id = :site_id
              AND status = "Active"
        ');
        $this->db->bind(':duty_point_name', $duty_point_name);
        $this->db->bind(':id', (int)$duty_point_id);
        $this->db->bind(':site_id', $siteId);

        if (!$this->db->execute()) {
            return false;
        }

        return $this->db->rowCount() > 0;
    }

    public function deleteAttendanceDutyPoint($supervisor_id, $duty_point_id)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisor_id);
        if (!$siteId || !$duty_point_id) {
            return false;
        }

        $this->db->query('
            UPDATE supervisor_duty_points
            SET status = "Inactive"
            WHERE id = :id
              AND site_id = :site_id
              AND status = "Active"
        ');
        $this->db->bind(':id', (int)$duty_point_id);
        $this->db->bind(':site_id', $siteId);

        if (!$this->db->execute()) {
            return false;
        }

        return $this->db->rowCount() > 0;
    }

    public function isValidDutyPointForSupervisor($supervisor_id, $duty_point_id)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisor_id);
        if (!$siteId || !$duty_point_id) {
            return null;
        }

        $this->db->query('
            SELECT id, duty_point_name
            FROM supervisor_duty_points
            WHERE id = :id
              AND site_id = :site_id
              AND status = "Active"
            LIMIT 1
        ');
        $this->db->bind(':id', (int)$duty_point_id);
        $this->db->bind(':site_id', $siteId);
        return $this->db->single();
    }

    // CREATE - Add new attendance record
    public function addAttendance($data)
    {
        // Honor existing unique key (officer_id + attendance_date) by upserting.
        $this->db->query('SELECT id FROM officer_attendance WHERE officer_id = :officer_id AND attendance_date = :attendance_date LIMIT 1');
        $this->db->bind(':officer_id', $data['officer_id']);
        $this->db->bind(':attendance_date', $data['attendance_date']);
        $existing = $this->db->single();

        if ($existing) {
            $this->db->query('UPDATE officer_attendance
                              SET supervisor_id = :supervisor_id,
                                  officer_name = :officer_name,
                                  check_in_time = :check_in_time,
                                  check_out_time = :check_out_time,
                                  status = :status,
                                  notes = :notes,
                                  duty_point = :duty_point,
                                  staff_role = :staff_role,
                                  updated_at = NOW()
                              WHERE id = :id');
            $this->db->bind(':id', $existing->id);
        } else {
            $this->db->query('INSERT INTO officer_attendance
                              (supervisor_id, officer_id, officer_name, attendance_date, check_in_time, check_out_time, status, notes, duty_point, staff_role)
                              VALUES (:supervisor_id, :officer_id, :officer_name, :attendance_date, :check_in_time, :check_out_time, :status, :notes, :duty_point, :staff_role)');
            $this->db->bind(':officer_id', $data['officer_id']);
            $this->db->bind(':attendance_date', $data['attendance_date']);
        }

        $this->db->bind(':supervisor_id', $data['supervisor_id']);
        $this->db->bind(':officer_name', $data['officer_name']);
        $this->db->bind(':check_in_time', $data['check_in_time']);
        $this->db->bind(':check_out_time', $data['check_out_time']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':duty_point', $data['duty_point']);
        $this->db->bind(':staff_role', $data['staff_role']);

        return $this->db->execute();
    }

    // READ - Get all attendance records with optional filters
    public function getAttendanceRecords($supervisor_id, $filters = [])
    {
        $query = 'SELECT * FROM officer_attendance WHERE supervisor_id = :supervisor_id';

        if (!empty($filters['date'])) {
            $query .= ' AND attendance_date = :date';
        }
        if (!empty($filters['status'])) {
            $query .= ' AND status = :status';
        }
        if (!empty($filters['officer_id'])) {
            $query .= ' AND (officer_id LIKE :officer_id OR officer_name LIKE :officer_name)';
        }
        if (!empty($filters['duty_point'])) {
            $query .= ' AND duty_point = :duty_point';
        }

        $query .= ' ORDER BY attendance_date DESC, officer_name ASC';

        $this->db->query($query);
        $this->db->bind(':supervisor_id', $supervisor_id);

        if (!empty($filters['date'])) {
            $this->db->bind(':date', $filters['date']);
        }
        if (!empty($filters['status'])) {
            $this->db->bind(':status', $filters['status']);
        }
        if (!empty($filters['officer_id'])) {
            $searchTerm = '%' . $filters['officer_id'] . '%';
            $this->db->bind(':officer_id', $searchTerm);
            $this->db->bind(':officer_name', $searchTerm);
        }
        if (!empty($filters['duty_point'])) {
            $this->db->bind(':duty_point', $filters['duty_point']);
        }

        return $this->db->resultSet();
    }

    // READ - Get single attendance record by ID
    public function getAttendanceById($id)
    {
        $this->db->query('SELECT * FROM officer_attendance WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    // UPDATE - Update existing attendance record
    public function updateAttendance($data)
    {
        $this->db->query('UPDATE officer_attendance 
                          SET officer_id = :officer_id,
                              officer_name = :officer_name,
                              attendance_date = :attendance_date, 
                              check_in_time = :check_in_time, 
                              check_out_time = :check_out_time, 
                              status = :status, 
                              notes = :notes 
                          WHERE id = :id AND supervisor_id = :supervisor_id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':supervisor_id', $data['supervisor_id']);
        $this->db->bind(':officer_id', $data['officer_id']);
        $this->db->bind(':officer_name', $data['officer_name']);
        $this->db->bind(':attendance_date', $data['attendance_date']);
        $this->db->bind(':check_in_time', $data['check_in_time']);
        $this->db->bind(':check_out_time', $data['check_out_time']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    // DELETE - Delete attendance record
    public function deleteAttendance($id, $supervisor_id)
    {
        $this->db->query('DELETE FROM officer_attendance WHERE id = :id AND supervisor_id = :supervisor_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':supervisor_id', $supervisor_id);

        return $this->db->execute();
    }

    // READ - Get today's attendance statistics
    public function getAttendanceStats($supervisor_id, $date)
    {
        $this->db->query('SELECT 
                            COUNT(*) as total_officers,
                            SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present,
                            SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent,
                            SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late,
                            SUM(CASE WHEN status = "Half Day" THEN 1 ELSE 0 END) as half_day
                          FROM officer_attendance 
                          WHERE supervisor_id = :supervisor_id AND attendance_date = :date');

        $this->db->bind(':supervisor_id', $supervisor_id);
        $this->db->bind(':date', $date);

        return $this->db->single();
    }

    // Get total staff count for this supervisor site
    public function getTotalOfficersCount($supervisor_id = null)
    {
        if ($supervisor_id) {
            return count($this->getAttendanceEligibleStaff($supervisor_id));
        } else {
            // Get all unique officers
            $this->db->query('
                SELECT COUNT(DISTINCT officer_id) as total
                FROM officer_attendance
            ');

            $result = $this->db->single();
            return $result->total ?? 0;
        }
    }

    // Get recent activities for supervisor
    public function getRecentActivities($userId, $limit = 50)
    {
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

    // Log activity for supervisor
    public function logActivity($data)
    {
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

    // ========== Incident Management ==========

    // Get all incidents for a supervisor
    public function getIncidentsByUserId($userId)
    {
        $this->db->query("
            SELECT 
                ir.*,
                s.site_name,
                COALESCE(ir.status, 'Pending') as status,
                COALESCE(ir.priority, ir.severity, 'Medium') as priority
            FROM incident_reports ir
            LEFT JOIN sites s ON ir.site_id = s.id
            WHERE ir.user_id = :user_id
            ORDER BY ir.created_at DESC
        ");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Add new incident
    public function addIncident($data)
    {
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

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':officer_name', $data['officer_name'] ?? '');
        $this->db->bind(':officer_role', $data['officer_role'] ?? 'supervisor');
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

        return $this->db->execute();
    }

    // Get incident by ID
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

    // Get incident reviews
    public function getIncidentReviews($incidentId)
    {
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

    // Get all sites (for incident creation)
    public function getAllSites()
    {
        $this->db->query('
            SELECT id, site_name, address, city, district, latitude, longitude
            FROM sites 
            WHERE is_draft = 0
            ORDER BY site_name ASC
        ');
        return $this->db->resultSet();
    }

    // Get sites assigned to a specific supervisor
    public function getAssignedSites($supervisorId)
    {
        $this->db->query('
            SELECT s.id, s.site_name, s.address, s.city, s.district, s.latitude, s.longitude
            FROM sites s
            INNER JOIN officer_site_assignments osa ON s.id = osa.site_id
            WHERE osa.officer_id = :supervisor_id 
            AND osa.status = "Active"
            AND osa.shift_type = "Supervisor"
            AND s.is_draft = 0
            ORDER BY s.site_name ASC
        ');
        $this->db->bind(':supervisor_id', $supervisorId);
        return $this->db->resultSet();
    }

    /**
     * Add a review to an incident
     */
    public function addIncidentReview($data)
    {
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
     * Get assigned officers for a supervisor's site
     * Separates officers and supervisors
     */
    public function getSiteOfficers($supervisorId)
    {
        // First get the supervisor's assigned site
        $this->db->query('
            SELECT site_id 
            FROM officer_site_assignments 
            WHERE officer_id = :supervisor_id 
            AND status = "Active" 
            AND shift_type = "Supervisor"
            LIMIT 1
        ');
        $this->db->bind(':supervisor_id', $supervisorId);
        $siteAssignment = $this->db->single();

        if (!$siteAssignment) {
            return ['officers' => [], 'supervisors' => [], 'site' => null];
        }

        $siteId = $siteAssignment->site_id;

        // Get site details
        $this->db->query('
            SELECT * FROM sites WHERE id = :site_id
        ');
        $this->db->bind(':site_id', $siteId);
        $site = $this->db->single();

        // Get all assigned staff to this site
        $this->db->query("SELECT 
                            osa.id as assignment_id,
                            osa.shift_type,
                            osa.assignment_start,
                            osa.status,
                            u.id as user_id,
                            u.name,
                            u.email,
                            u.phone_number,
                            u.profile_image,
                            po.officerID,
                            po.city,
                            po.district,
                            po.rank,
                            po.employment_status
                          FROM officer_site_assignments osa
                          INNER JOIN Users u ON osa.officer_id = u.id
                          INNER JOIN premise_officers po ON u.id = po.userID
                          WHERE osa.site_id = :site_id AND osa.status = 'Active'
                          ORDER BY po.rank DESC, u.name ASC");
        $this->db->bind(':site_id', $siteId);
        $allStaff = $this->db->resultSet();

        // Separate officers and supervisors
        $officers = [];
        $supervisors = [];

        foreach ($allStaff as $staff) {
            if ($staff->rank === 'Supervisor' || $staff->shift_type === 'Supervisor') {
                $supervisors[] = $staff;
            } else {
                $officers[] = $staff;
            }
        }

        return [
            'officers' => $officers,
            'supervisors' => $supervisors,
            'site' => $site
        ];
    }

    /**
     * Validate that a supervisor can rate the selected premise officer in supervisor's site.
     */
    public function canSupervisorRateOfficer($supervisorId, $officerUserId, $ratingDate = null)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisorId);
        if (!$siteId) {
            return false;
        }

        $query = '
            SELECT osa.id
            FROM officer_site_assignments osa
            INNER JOIN Users u ON u.id = osa.officer_id
            WHERE osa.site_id = :site_id
              AND osa.status = "Active"
              AND (osa.shift_type != "Supervisor" OR osa.shift_type IS NULL)
              AND osa.officer_id = :officer_user_id
              AND LOWER(TRIM(u.role)) = "premise officer"';

        if (!empty($ratingDate)) {
            $query .= ' AND :rating_date BETWEEN osa.assignment_start AND IFNULL(osa.assignment_end, "2099-12-31")';
        }

        $query .= ' LIMIT 1';

        $this->db->query($query);
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':officer_user_id', (int)$officerUserId);
        if (!empty($ratingDate)) {
            $this->db->bind(':rating_date', $ratingDate);
        }

        return (bool)$this->db->single();
    }

    public function saveSupervisorOfficerRating($supervisorId, $officerUserId, $ratingDate, $ratingValue, $description)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisorId);
        if (!$siteId) {
            return false;
        }

        $this->db->query('
            INSERT INTO officer_performance_ratings
                (site_id, officer_user_id, reviewer_user_id, reviewer_role, rating_date, rating_value, description)
            VALUES
                (:site_id, :officer_user_id, :supervisor_id, "supervisor", :rating_date, :rating_value, :description)
            ON DUPLICATE KEY UPDATE
                rating_value = VALUES(rating_value),
                description = VALUES(description),
                updated_at = CURRENT_TIMESTAMP
        ');
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':officer_user_id', (int)$officerUserId);
        $this->db->bind(':supervisor_id', (int)$supervisorId);
        $this->db->bind(':rating_date', $ratingDate);
        $this->db->bind(':rating_value', (int)$ratingValue);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    public function deleteSupervisorOfficerRating($supervisorId, $officerUserId, $ratingDate)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisorId);
        if (!$siteId) {
            return false;
        }

        $this->db->query('
            DELETE FROM officer_performance_ratings
            WHERE site_id = :site_id
              AND officer_user_id = :officer_user_id
              AND reviewer_user_id = :supervisor_id
              AND reviewer_role = "supervisor"
              AND rating_date = :rating_date
        ');
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':officer_user_id', (int)$officerUserId);
        $this->db->bind(':supervisor_id', (int)$supervisorId);
        $this->db->bind(':rating_date', $ratingDate);
        return $this->db->execute();
    }

    public function getSupervisorOfficerRatings($supervisorId)
    {
        $siteId = $this->getSupervisorPrimarySiteId($supervisorId);
        if (!$siteId) {
            return [];
        }

        $this->db->query('
            SELECT officer_user_id, rating_date, rating_value, description, updated_at
            FROM officer_performance_ratings
            WHERE site_id = :site_id
              AND reviewer_user_id = :supervisor_id
              AND reviewer_role = "supervisor"
            ORDER BY rating_date DESC, updated_at DESC
        ');
        $this->db->bind(':site_id', $siteId);
        $this->db->bind(':supervisor_id', (int)$supervisorId);
        return $this->db->resultSet();
    }

    /**
     * Get mobile riders assigned to a supervisor's site
     */
    public function getSiteMobileRiders($supervisorId)
    {
        // First get the supervisor's assigned site
        $this->db->query('
            SELECT site_id 
            FROM officer_site_assignments 
            WHERE officer_id = :supervisor_id 
            AND status = "Active" 
            AND shift_type = "Supervisor"
            LIMIT 1
        ');
        $this->db->bind(':supervisor_id', $supervisorId);
        $siteAssignment = $this->db->single();

        if (!$siteAssignment) {
            return [];
        }

        $siteId = $siteAssignment->site_id;

        // Get all mobile riders assigned to this site through route_sites
        $this->db->query("SELECT DISTINCT
                            u.id as user_id,
                            u.name,
                            u.email,
                            u.phone_number,
                            u.profile_image,
                            u.userID
                          FROM routes r
                          INNER JOIN route_sites rs ON r.id = rs.route_id
                          INNER JOIN Users u ON r.assigned_rider_id = u.id
                          WHERE rs.site_id = :site_id
                          AND u.role = 'Mobile Rider'
                          AND r.assigned_rider_id IS NOT NULL
                          ORDER BY u.name ASC");
        $this->db->bind(':site_id', $siteId);
        return $this->db->resultSet();
    }

    /**
     * Get caretakers assigned to a supervisor's site
     */
    public function getSiteCaretakers($supervisorId)
    {
        // First get the supervisor's assigned site
        $this->db->query('
            SELECT site_id 
            FROM officer_site_assignments 
            WHERE officer_id = :supervisor_id 
            AND status = "Active" 
            AND shift_type = "Supervisor"
            LIMIT 1
        ');
        $this->db->bind(':supervisor_id', $supervisorId);
        $siteAssignment = $this->db->single();

        if (!$siteAssignment) {
            return [];
        }

        $siteId = $siteAssignment->site_id;

        // Get all caretakers assigned to this site with equipment request count
        $this->db->query("SELECT 
                            csa.id as assignment_id,
                            csa.assignment_start,
                            csa.assignment_end,
                            csa.status,
                            csa.notes,
                            u.id as user_id,
                            u.name,
                            u.email,
                            u.phone_number,
                            u.profile_image,
                            u.role,
                            COUNT(er.id) as pending_requests_count
                          FROM caretaker_site_assignments csa
                          INNER JOIN Users u ON csa.caretaker_id = u.id
                          LEFT JOIN equipment_requests er ON u.id = er.caretaker_id AND er.status = 'Pending'
                          WHERE csa.site_id = :site_id 
                          AND csa.status = 'Active'
                          GROUP BY csa.id, u.id
                          ORDER BY u.name ASC");
        $this->db->bind(':site_id', $siteId);
        return $this->db->resultSet();
    }

    /**
     * Get pending equipment requests for supervisor's sites
     */
    public function getPendingEquipmentRequests($supervisor_id)
    {
        $this->db->query("
            SELECT 
                er.*,
                u_caretaker.name as caretaker_name,
                u_caretaker.phone_number as caretaker_phone,
                s.site_name,
                s.location as site_location,
                c.name as client_name
            FROM equipment_requests er
            INNER JOIN Users u_caretaker ON er.caretaker_id = u_caretaker.id
            INNER JOIN caretaker_site_assignments csa ON er.caretaker_id = csa.caretaker_id AND csa.status = 'Active'
            INNER JOIN sites s ON csa.site_id = s.id
            INNER JOIN Users c ON s.client_id = c.id
            INNER JOIN officer_site_assignments osa ON s.id = osa.site_id
            WHERE osa.officer_id = :supervisor_id
            AND osa.shift_type = 'Supervisor'
            AND osa.status = 'Active'
            AND s.is_draft = 0
            AND er.status = 'Pending'
            ORDER BY 
                CASE er.priority
                    WHEN 'Critical' THEN 1
                    WHEN 'High' THEN 2
                    WHEN 'Medium' THEN 3
                    WHEN 'Low' THEN 4
                END,
                er.created_at DESC
        ");
        $this->db->bind(':supervisor_id', $supervisor_id);
        return $this->db->resultSet();
    }

    /**
     * Get equipment approval statistics for supervisor
     */
    public function getEquipmentApprovalStats($supervisor_id)
    {
        $this->db->query("
            SELECT 
                COUNT(CASE WHEN er.status = 'Pending' THEN 1 END) as pending_count,
                COUNT(CASE WHEN er.status = 'Supervisor Approved' THEN 1 END) as approved_count,
                COUNT(CASE WHEN er.status = 'Rejected' THEN 1 END) as rejected_count,
                COUNT(*) as total_count
            FROM equipment_requests er
            INNER JOIN caretaker_site_assignments csa ON er.caretaker_id = csa.caretaker_id AND csa.status = 'Active'
            INNER JOIN officer_site_assignments osa ON csa.site_id = osa.site_id
            WHERE osa.officer_id = :supervisor_id
            AND osa.shift_type = 'Supervisor'
            AND osa.status = 'Active'
        ");
        $this->db->bind(':supervisor_id', $supervisor_id);
        $result = $this->db->single();

        return [
            'pending' => $result->pending_count ?? 0,
            'approved' => $result->approved_count ?? 0,
            'rejected' => $result->rejected_count ?? 0,
            'total' => $result->total_count ?? 0
        ];
    }

    /**
     * Get equipment request by ID
     */
    public function getEquipmentRequestById($request_id)
    {
        $this->db->query("
            SELECT 
                er.*,
                u_caretaker.name as caretaker_name,
                u_caretaker.phone_number as caretaker_phone,
                s.site_name,
                s.client_id,
                c.name as client_name
            FROM equipment_requests er
            INNER JOIN Users u_caretaker ON er.caretaker_id = u_caretaker.id
            INNER JOIN caretaker_site_assignments csa ON er.caretaker_id = csa.caretaker_id AND csa.status = 'Active'
            INNER JOIN sites s ON csa.site_id = s.id
            INNER JOIN Users c ON s.client_id = c.id
            WHERE er.id = :request_id
        ");
        $this->db->bind(':request_id', $request_id);
        return $this->db->single();
    }

    /**
     * Get equipment requests for a specific caretaker
     */
    public function getCaretakerEquipmentRequests($caretaker_id)
    {
        $this->db->query("
            SELECT 
                er.*,
                u.name as caretaker_name,
                u.phone_number as caretaker_phone
            FROM equipment_requests er
            INNER JOIN Users u ON er.caretaker_id = u.id
            WHERE er.caretaker_id = :caretaker_id
            ORDER BY 
                CASE er.status
                    WHEN 'Pending' THEN 1
                    WHEN 'Supervisor Approved' THEN 2
                    WHEN 'Approved' THEN 3
                    WHEN 'Rejected' THEN 4
                END,
                CASE er.priority
                    WHEN 'High' THEN 1
                    WHEN 'Medium' THEN 2
                    WHEN 'Low' THEN 3
                END,
                er.created_at DESC
        ");
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->resultSet();
    }

    /**
     * Approve an equipment request (supervisor level)
     */
    public function approveEquipmentRequest($request_id, $supervisor_id, $notes = '')
    {
        $this->db->query("
            UPDATE equipment_requests 
            SET 
                status = 'Supervisor Approved',
                approved_by = :supervisor_id,
                approved_date = CURDATE(),
                supervisor_notes = :notes
            WHERE id = :request_id
            AND status = 'Pending'
        ");
        $this->db->bind(':request_id', $request_id);
        $this->db->bind(':supervisor_id', $supervisor_id);
        $this->db->bind(':notes', $notes);

        return $this->db->execute();
    }

    /**
     * Reject an equipment request
     */
    public function rejectEquipmentRequest($request_id, $supervisor_id, $reason)
    {
        $this->db->query("
            UPDATE equipment_requests 
            SET 
                status = 'Rejected',
                approved_by = :supervisor_id,
                approved_date = CURDATE(),
                supervisor_notes = :reason
            WHERE id = :request_id
            AND status = 'Pending'
        ");
        $this->db->bind(':request_id', $request_id);
        $this->db->bind(':supervisor_id', $supervisor_id);
        $this->db->bind(':reason', $reason);

        return $this->db->execute();
    }

    // Get recent activities for supervisor dashboard
    // Insert recent activity for supervisor
    public function insertRecentActivity($userId, $title, $description, $type)
    {
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



    // ======================================================================== //
    // =======================      profile       ====================== //
    // ======================================================================== //

    public function getSupervisorById($userID)
    {
        $this->db->query("SELECT * FROM Users WHERE userID = :userID");
        $this->db->bind(':userID', $userID);
        return $this->db->single();
    }

    public function updateSupervisorProfile($user_id, $data)
    {
        $fields = [];
        $bindings = [];

        // Only update fields that are provided
        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $bindings[':name'] = $data['name'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $bindings[':email'] = $data['email'];
        }
        if (isset($data['phone_number'])) {
            $fields[] = 'phone_number = :phone_number';
            $bindings[':phone_number'] = $data['phone_number'];
        }
        if (isset($data['profile_image'])) {
            $fields[] = 'profile_image = :profile_image';
            $bindings[':profile_image'] = $data['profile_image'];
        }
        if (isset($data['password'])) {
            $fields[] = 'password = :password';
            $bindings[':password'] = $data['password'];
        }

        if (empty($fields)) {
            return false; // Nothing to update
        }

        $query = 'UPDATE Users SET ' . implode(', ', $fields) . ' WHERE id = :user_id';
        $this->db->query($query);

        $this->db->bind(':user_id', $user_id);
        foreach ($bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->execute();
    }
}
