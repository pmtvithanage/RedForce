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
        $this->db->query("INSERT INTO notes ( userID, title, content, created_at) VALUES (:userID, :title, :content, NOW())");

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
        $this->db->query("SELECT * FROM notes");
        return $this->db->resultSet();
    }

    public function deleteNoteById($id)
    {
        $this->db->query("DELETE FROM notes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateNoteById($id, $title, $content)
    {
        $this->db->query("UPDATE notes SET title = :title, content = :content WHERE id = :id");
        $this->db->bind(":id", $id);
        $this->db->bind(":title", $title);
        $this->db->bind(":content", $content);
        return $this->db->execute();
    }

    public function addIncident($data)
    {
        // Prepare the SQL query
        $this->db->query("
        INSERT INTO incident_reports 
        (user_id, officer_name, officer_role, property_site, incident_type, 
         incident_date, incident_time, incident_description, action_taken, 
         severity, additional_details, media_files, created_at) 
        VALUES 
        (:user_id, :officer_name, :officer_role, :property_site, :incident_type, 
         :incident_date, :incident_time, :incident_description, :action_taken, 
         :severity, :additional_details, :media_files, NOW())");

        // Bind parameters
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':officer_name', $data['officer_name']);
        $this->db->bind(':officer_role', $data['officer_role']);
        $this->db->bind(':property_site', $data['property_site']);
        $this->db->bind(':incident_type', $data['incident_type']);
        $this->db->bind(':incident_date', $data['incident_date']);
        $this->db->bind(':incident_time', $data['incident_time']);
        $this->db->bind(':incident_description', $data['incident_description']);
        $this->db->bind(':action_taken', $data['action_taken']);
        $this->db->bind(':severity', $data['severity']);
        $this->db->bind(':additional_details', $data['follow_up_id']);
        $this->db->bind(':media_files', $data['media_files']);

        // Execute
        return $this->db->execute();
    }

    public function getAllIncidents()
    {
        $this->db->query("SELECT * FROM incident_reports");
        return $this->db->resultSet();
    }

    public function getIncidentById($id)
    {
        $this->db->query('SELECT * FROM incident_reports WHERE id = :id LIMIT 1');
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
}