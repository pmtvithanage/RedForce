<?php
class M_caretaker
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Get caretaker user record for sidebar/profile display.
    public function getCaretakerById($caretakerIdentifier)
    {
        $this->db->query('
            SELECT *
            FROM Users
            WHERE id = :caretaker_id OR userID = :caretaker_user_id
            LIMIT 1
        ');

        $this->db->bind(':caretaker_id', $caretakerIdentifier);
        $this->db->bind(':caretaker_user_id', $caretakerIdentifier);

        return $this->db->single();
    }

    // ==================== LEAVE REQUESTS ====================

    // CREATE - Add new leave request
    public function addLeaveRequest($data)
    {
        $this->db->query('INSERT INTO leave_requests (caretaker_id, leave_type, reason, start_date, end_date, proof_file) 
                          VALUES (:caretaker_id, :leave_type, :reason, :start_date, :end_date, :proof_file)');

        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':start_time', $data['start_time'] ?? null);
        $this->db->bind(':proof_file', $data['proof_file']);

        return $this->db->execute();
    }

    // READ - Get all leave requests
    public function getLeaveRequests($caretaker_id)
    {
        $this->db->query('SELECT * FROM leave_requests WHERE caretaker_id = :caretaker_id ORDER BY created_at DESC');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->resultSet();
    }

    // READ - Get single leave request
    public function getLeaveRequestById($id)
    {
        $this->db->query('SELECT * FROM leave_requests WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // UPDATE - Leave request
    public function updateLeaveRequest($data)
    {
        $this->db->query('UPDATE leave_requests 
                          SET leave_type = :leave_type, 
                              reason = :reason, 
                              start_date = :start_date, 
                              end_date = :end_date, 
                              proof_file = :proof_file 
                          WHERE id = :id AND caretaker_id = :caretaker_id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':start_time', $data['start_time'] ?? null);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':proof_file', $data['proof_file']);

        return $this->db->execute();
    }

    // DELETE - Leave request
    public function deleteLeaveRequest($id, $caretaker_id)
    {
        $this->db->query('DELETE FROM leave_requests WHERE id = :id AND caretaker_id = :caretaker_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->execute();
    }

    // ==================== NOTES METHODS (RDFC-71) ====================

    public function getNotes($caretaker_id, $filters = [])
    {
        $query = 'SELECT * FROM caretaker_notes WHERE caretaker_id = :caretaker_id';

        if (!empty($filters['category']) && $filters['category'] !== 'All') {
            $query .= ' AND category = :category';
        }
        if (!empty($filters['priority']) && $filters['priority'] !== 'All') {
            $query .= ' AND priority = :priority';
        }
        if (!empty($filters['search'])) {
            $query .= ' AND (title LIKE :search OR note_content LIKE :search)';
        }

        $query .= ' ORDER BY is_pinned DESC, created_at DESC';

        $this->db->query($query);
        $this->db->bind(':caretaker_id', $caretaker_id);

        if (!empty($filters['category']) && $filters['category'] !== 'All') {
            $this->db->bind(':category', $filters['category']);
        }
        if (!empty($filters['priority']) && $filters['priority'] !== 'All') {
            $this->db->bind(':priority', $filters['priority']);
        }
        if (!empty($filters['search'])) {
            $this->db->bind(':search', '%' . $filters['search'] . '%');
        }

        return $this->db->resultSet();
    }

    public function getNoteById($id)
    {
        $this->db->query('SELECT * FROM caretaker_notes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getNotesStats($caretaker_id)
    {
        $this->db->query('
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN category = "Important" THEN 1 ELSE 0 END) as important,
                SUM(CASE WHEN priority = "High" THEN 1 ELSE 0 END) as highPriority,
                SUM(CASE WHEN reminder_date IS NOT NULL AND reminder_date >= CURDATE() AND is_completed = 0 THEN 1 ELSE 0 END) as pendingReminders
            FROM caretaker_notes 
            WHERE caretaker_id = :caretaker_id
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->single();
    }

    // Get today's reminders
    public function getTodayReminders($caretaker_id)
    {
        $this->db->query('
            SELECT * FROM caretaker_notes 
            WHERE caretaker_id = :caretaker_id 
            AND reminder_date = CURDATE()
            AND is_completed = 0
            ORDER BY priority DESC, created_at ASC
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->resultSet();
    }

    // Get overdue reminders (missed)
    public function getOverdueReminders($caretaker_id)
    {
        $this->db->query('
            SELECT * FROM caretaker_notes 
            WHERE caretaker_id = :caretaker_id 
            AND reminder_date < CURDATE()
            AND is_completed = 0
            ORDER BY reminder_date DESC, priority DESC
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->resultSet();
    }

    // Mark reminder as completed
    public function completeReminder($note_id, $caretaker_id)
    {
        $this->db->query('
            UPDATE caretaker_notes 
            SET is_completed = 1 
            WHERE id = :id AND caretaker_id = :caretaker_id
        ');
        $this->db->bind(':id', $note_id);
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->execute();
    }

    public function addNote($data)
    {
        $this->db->query('
            INSERT INTO caretaker_notes 
            (caretaker_id, title, note_content, category, priority, reminder_date) 
            VALUES 
            (:caretaker_id, :title, :note_content, :category, :priority, :reminder_date)
        ');

        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':note_content', $data['note_content']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':reminder_date', $data['reminder_date'] ?? null);

        return $this->db->execute();
    }

    public function updateNote($data)
    {
        $this->db->query('
            UPDATE caretaker_notes 
            SET title = :title,
                note_content = :note_content,
                category = :category,
                priority = :priority,
                reminder_date = :reminder_date
            WHERE id = :id AND caretaker_id = :caretaker_id
        ');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':note_content', $data['note_content']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':reminder_date', $data['reminder_date'] ?? null);

        return $this->db->execute();
    }

    public function deleteNote($id, $caretaker_id)
    {
        $this->db->query('DELETE FROM caretaker_notes WHERE id = :id AND caretaker_id = :caretaker_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->execute();
    }

    public function togglePin($id, $caretaker_id)
    {
        $this->db->query('
            UPDATE caretaker_notes 
            SET is_pinned = NOT is_pinned 
            WHERE id = :id AND caretaker_id = :caretaker_id
        ');
        $this->db->bind(':id', $id);
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->execute();
    }

    // ==================== EQUIPMENT REQUESTS (dev branch) ====================

    public function addEquipmentRequest($data)
    {
        $this->db->query('
            INSERT INTO equipment_requests 
            (caretaker_id, equipment_name, quantity, estimated_cost, reason, priority, requested_date, status) 
            VALUES 
            (:caretaker_id, :equipment_name, :quantity, :estimated_cost, :reason, :priority, :requested_date, "Pending")
        ');

        $this->db->bind(':caretaker_id', $data['caretaker_id']);
        $this->db->bind(':equipment_name', $data['equipment_name']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':estimated_cost', $data['estimated_cost']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':requested_date', $data['requested_date']);

        return $this->db->execute();
    }

    public function getEquipmentRequests($caretaker_id)
    {
        $this->db->query('
            SELECT * FROM equipment_requests 
            WHERE caretaker_id = :caretaker_id 
            ORDER BY requested_date DESC, created_at DESC
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->resultSet();
    }

    public function getEquipmentRequestById($id)
    {
        $this->db->query('SELECT * FROM equipment_requests WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getEquipmentStats($caretaker_id)
    {
        $this->db->query('
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status = "Pending" THEN total_cost ELSE 0 END) as pending_cost,
                SUM(CASE WHEN status = "Approved" THEN total_cost ELSE 0 END) as approved_cost
            FROM equipment_requests 
            WHERE caretaker_id = :caretaker_id
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->single();
    }

    public function updateEquipmentRequest($data)
    {
        $this->db->query('
            UPDATE equipment_requests 
            SET equipment_name = :equipment_name,
                quantity = :quantity,
                estimated_cost = :estimated_cost,
                reason = :reason,
                priority = :priority
            WHERE id = :id AND status = "Pending"
        ');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':equipment_name', $data['equipment_name']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':estimated_cost', $data['estimated_cost']);
        $this->db->bind(':reason', $data['reason']);
        $this->db->bind(':priority', $data['priority']);

        return $this->db->execute();
    }

    public function deleteEquipmentRequest($id)
    {
        $this->db->query('DELETE FROM equipment_requests WHERE id = :id AND status = "Pending"');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ==================== DASHBOARD STATISTICS ====================

    /**
     * Get dashboard statistics for caretaker
     * Returns total officers, on duty today, active status, and incidents
     */
    public function getDashboardStats($caretaker_id = null)
    {
        // Get today's date
        $today = date('Y-m-d');

        // Total officers assigned to this caretaker (or all if no caretaker_id)
        $this->db->query('
            SELECT COUNT(DISTINCT officer_id) as total_officers
            FROM officer_attendance
        ');
        $totalResult = $this->db->single();
        $total_officers = $totalResult->total_officers ?? 0;

        // Officers on duty today (Present status)
        $this->db->query('
            SELECT COUNT(DISTINCT officer_id) as on_duty
            FROM officer_attendance
            WHERE attendance_date = :today 
            AND status IN ("Present", "Late", "Half Day")
        ');
        $this->db->bind(':today', $today);
        $onDutyResult = $this->db->single();
        $on_duty = $onDutyResult->on_duty ?? 0;

        // Active officers (those who checked in today and haven't checked out yet or within last 12 hours)
        $this->db->query('
            SELECT COUNT(DISTINCT officer_id) as active
            FROM officer_attendance
            WHERE attendance_date = :today 
            AND status = "Present"
            AND check_in_time IS NOT NULL
        ');
        $this->db->bind(':today', $today);
        $activeResult = $this->db->single();
        $active = $activeResult->active ?? 0;

        // Incidents today (count absent and late as incidents)
        $this->db->query('
            SELECT COUNT(*) as incidents
            FROM officer_attendance
            WHERE attendance_date = :today 
            AND status IN ("Absent", "Late")
        ');
        $this->db->bind(':today', $today);
        $incidentsResult = $this->db->single();
        $incidents = $incidentsResult->incidents ?? 0;

        return [
            'total_officers' => $total_officers,
            'on_duty' => $on_duty,
            'active' => $active,
            'incidents' => $incidents
        ];
    }

    // Get recent activities for caretaker dashboard
    public function getRecentActivities($caretaker_id = null, $limit = 10)
    {
        $query = '
            SELECT 
                activity_type,
                activity_titel,
                activity_details,
                created_at
            FROM recent_activities
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT :limit
        ';

        $this->db->query($query);
        $this->db->bind(':user_id', $caretaker_id, PDO::PARAM_INT);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Insert recent activity for caretaker
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

    // ==================== SITE INFORMATION ====================

    /**
     * Get assigned site information for caretaker
     * Returns site details with client information
     */
    public function getAssignedSite($caretaker_id)
    {
        $this->db->query('
            SELECT 
                s.*,
                c.name as client_name,
                c.email as client_email,
                c.phone_number as client_phone,
                c.profile_image as client_logo,
                csa.assignment_start,
                csa.assignment_end,
                csa.status as assignment_status
            FROM caretaker_site_assignments csa
            INNER JOIN sites s ON csa.site_id = s.id
            INNER JOIN Users c ON s.client_id = c.id
            WHERE csa.caretaker_id = :caretaker_id 
            AND csa.status = "Active"
            AND s.is_draft = 0
            ORDER BY csa.assignment_start DESC
            LIMIT 1
        ');
        $this->db->bind(':caretaker_id', $caretaker_id);
        return $this->db->single();
    }

    /**
     * Get assigned supervisors for a site
     * Returns list of supervisors assigned to the site
     */
    public function getAssignedSupervisors($site_id)
    {
        $this->db->query('
            SELECT 
                u.id,
                u.name,
                u.email,
                u.phone_number,
                u.profile_image,
                po.rank,
                osa.assignment_start,
                osa.assignment_end,
                osa.shift_type,
                osa.status
            FROM officer_site_assignments osa
            INNER JOIN Users u ON osa.officer_id = u.id
            INNER JOIN premise_officers po ON u.id = po.userID
            WHERE osa.site_id = :site_id 
            AND osa.status = "Active"
            AND po.rank = "Supervisor"
            ORDER BY osa.assignment_start DESC
        ');
        $this->db->bind(':site_id', $site_id);
        return $this->db->resultSet();
    }
// ======================================================================== //
// =======================      profile       ====================== //
// ======================================================================== //

public function getCaretakerById($userID)
{
    $this->db->query("SELECT * FROM Users WHERE userID = :userID");
    $this->db->bind(':userID', $userID);
    return $this->db->single();
}

public function updateCaretakerProfile($user_id, $data)
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

    $query = 'UPDATE Users SET ' . implode(', ', $fields) . ' WHERE userID = :user_id';
    $this->db->query($query);

    $this->db->bind(':user_id', $user_id);

    foreach ($bindings as $key => $value) {
        $this->db->bind($key, $value);
    }

    return $this->db->execute();
}

}
