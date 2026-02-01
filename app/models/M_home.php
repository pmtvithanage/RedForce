<?php
class M_home {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
// Save job application
    public function saveJobApplication($data, $role) {
        $this->db->query("INSERT INTO submittedApplications (role, name, email, phone_number, date_of_birth, NIC, gender, address, district, city, photo, cv, submitted_at) 
                        VALUES (:role, :name, :email, :phone, :birthday, :national_id, :gender, :address, :district, :city, :photo, :cv, NOW())");
        $this->db->bind(':role', $role);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);

        $this->db->bind(':birthday', $data['birthday']);
        $this->db->bind(':national_id', $data['national_id']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':district', $data['district']);
        $this->db->bind(':city', $data['city']);

        $this->db->bind(':photo', $data['image_name']);
        $this->db->bind(':cv', $data['cv_name']);

        return $this->db->execute();
    }

    // Get job application details
    public function getPendingOfficerApplications($role) {
        $this->db->query("SELECT * FROM submittedApplications WHERE role = :role AND status = 'pending' ORDER BY submitted_at DESC");
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }
    // Get job application details
    public function getAllPendingOfficerApplications() {
        $this->db->query("SELECT * FROM submittedApplications WHERE status = 'pending' ORDER BY submitted_at DESC");
        return $this->db->resultSet();
    }
    // Get job application details
    public function getApprovedOfficerApplications($role) {
        $this->db->query("SELECT * FROM submittedApplications WHERE role = :role AND status = 'approved' ORDER BY submitted_at DESC");
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }
    // Get job application details
    public function getAllApprovedOfficerApplications() {
        $this->db->query("SELECT * FROM submittedApplications WHERE status = 'approved' ORDER BY submitted_at DESC");
        return $this->db->resultSet();
    }

    // Get job application details
    public function getRejectedOfficerApplications($role) {
        $this->db->query("SELECT * FROM submittedApplications WHERE role = :role AND status = 'rejected' ORDER BY submitted_at DESC");
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }
    // Get job application details
    public function getAllRejectedOfficerApplications() {
        $this->db->query("SELECT * FROM submittedApplications WHERE status = 'rejected' ORDER BY submitted_at DESC");
        return $this->db->resultSet();
    }

    // Get application by ID
    public function getApplicationById($id) {
        $this->db->query("SELECT * FROM submittedApplications WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Save client requests from service page
    public function getPendingRequest() {
    $this->db->query("SELECT * FROM client_requests WHERE status = 'pending' ORDER BY created_at DESC");
    return $this->db->resultSet();
}
public function getApprovedRequest() {
    $this->db->query("
        SELECT 
            cr.*, 
            u.name as approved_by_username,
            DATE_FORMAT(cr.approved_at, '%Y-%m-%d %H:%i:%s') as approved_time
        FROM client_requests cr
        LEFT JOIN Users u ON cr.approved_by = u.id
        WHERE cr.status = 'approved' 
        ORDER BY cr.approved_at DESC
    ");
    return $this->db->resultSet();
}
    public function getRejectedRequest() {
    $this->db->query("SELECT * FROM client_requests WHERE status = 'rejected' ORDER BY created_at DESC");
    return $this->db->resultSet();
}
    public function saveServiceRequest($data) {
        $this->db->query("INSERT INTO client_requests (company_name, email, phone_number, contact_person_name, logo_path, created_at) 
                         VALUES (:company_name, :email, :phone_number, :contact_person_name, :logo_path, NOW())");
        
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':contact_person_name', $data['contact_person_name']);
        $this->db->bind(':logo_path', $data['image_name']);
        
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}