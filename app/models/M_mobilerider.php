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


    public function deleteIncidentById($id)
    {
        $this->db->query('DELETE FROM incident_reports WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?>