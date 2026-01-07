<?php
class M_client {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Create a new service request
    public function createServiceRequest($data) {
        $this->db->query('INSERT INTO service_requests (client_id, event_name, event_description, start_date, end_date, start_time, end_time, location, number_of_guards, comments) VALUES (:client_id, :event_name, :event_description, :start_date, :end_date, :start_time, :end_time, :location, :number_of_guards, :comments)');
        
        // Bind values
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':event_name', $data['event_name']);
        $this->db->bind(':event_description', $data['event_description']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':number_of_guards', $data['number_of_guards']);
        $this->db->bind(':comments', $data['comments']);
        
        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Get all requests for a specific client
    public function getClientServiceRequests($client_id) {
        $this->db->query('SELECT * FROM service_requests WHERE client_id = :client_id ORDER BY submitted_date DESC');
        $this->db->bind(':client_id', $client_id);
        
        return $this->db->resultSet();
    }

    // Get a single request by ID
    public function getServiceRequestById($id) {
        $this->db->query('SELECT * FROM service_requests WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Get all requests (for admin)
    public function getAllServiceRequests() {
        $this->db->query('
            SELECT sr.*, u.username, u.email 
            FROM service_requests sr 
            JOIN users u ON sr.client_id = u.id 
            ORDER BY sr.submitted_date DESC
        ');
        
        return $this->db->resultSet();
    }

    // Delete a service request
    public function deleteServiceRequest($request_id, $client_id) {
        $this->db->query('DELETE FROM service_requests WHERE id = :request_id AND client_id = :client_id');
        
        $this->db->bind(':request_id', $request_id);
        $this->db->bind(':client_id', $client_id);
        
        return $this->db->execute();
    }

    // Package request methods
    public function createPackageRequest($data) {
        $this->db->query('INSERT INTO package_requests (client_id, package_name, site_name, city, site_address, start_date, end_date, number_of_guards, day_guards, night_guards, package_price, comments) 
            VALUES (:client_id, :package_name, :site_name, :city, :site_address, :start_date, :end_date, :number_of_guards, :day_guards, :night_guards, :package_price, :comments)');
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':package_name', $data['package_name']);
        $this->db->bind(':site_name', $data['site_name']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':site_address', $data['site_address']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':number_of_guards', $data['number_of_guards']);
        $this->db->bind(':day_guards', $data['day_guards'] ?? null);
        $this->db->bind(':night_guards', $data['night_guards'] ?? null);
        $this->db->bind(':package_price', $data['package_price']);
        $this->db->bind(':comments', $data['comments'] ?? null);
        return $this->db->execute();
    }

    public function getClientPackageRequests($client_id) {
        $this->db->query('SELECT * FROM package_requests WHERE client_id = :client_id ORDER BY submitted_date DESC');
        $this->db->bind(':client_id', $client_id);
        return $this->db->resultSet();
    }

    public function deletePackageRequest($id, $client_id) {
        $this->db->query('DELETE FROM package_requests WHERE id = :id AND client_id = :client_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':client_id', $client_id);
        return $this->db->execute();
    }
}
