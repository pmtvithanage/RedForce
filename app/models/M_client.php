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
        // Map new personnel fields to existing database columns
        // number_of_officers -> number_of_guards
        // number_of_supervisors -> day_guards
        // number_of_caretakers -> night_guards
        
        $this->db->query('INSERT INTO package_requests (client_id, package_name, site_name, district, city, site_address, latitude, longitude, phone_number, image_name, start_date, end_date, number_of_guards, day_guards, night_guards, package_price, comments, status) 
            VALUES (:client_id, :package_name, :site_name, :district, :city, :site_address, :latitude, :longitude, :phone_number, :image_name, :start_date, :end_date, :number_of_guards, :day_guards, :night_guards, :package_price, :comments, :status)');
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':package_name', $data['package_name']);
        $this->db->bind(':site_name', $data['site_name']);
        $this->db->bind(':district', $data['district'] ?? null);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':site_address', $data['site_address']);
        $this->db->bind(':latitude', $data['latitude'] ?? null);
        $this->db->bind(':longitude', $data['longitude'] ?? null);
        $this->db->bind(':phone_number', $data['phone_number'] ?? null);
        $this->db->bind(':image_name', $data['image_name'] ?? null);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        // Map new fields to old columns
        $this->db->bind(':number_of_guards', $data['number_of_officers'] ?? 0);
        $this->db->bind(':day_guards', $data['number_of_supervisors'] ?? 0);
        $this->db->bind(':night_guards', $data['number_of_caretakers'] ?? 0);
        $this->db->bind(':package_price', $data['package_price']);
        $this->db->bind(':comments', $data['comments'] ?? null);
        $this->db->bind(':status', $data['status'] ?? 'Pending');
        return $this->db->execute();
    }

    public function getClientPackageRequests($client_id) {
        $this->db->query('SELECT * FROM package_requests WHERE client_id = :client_id ORDER BY submitted_date DESC');
        $this->db->bind(':client_id', $client_id);
        return $this->db->resultSet();
    }
    
    public function getPendingPackageRequests($client_id) {
        // Get pending package requests with mapped personnel fields
        // Uses pr.payment_status and pr.payment_id directly from package_requests table
        // (these columns are updated by Payment::notify() when PayHere confirms payment)
        $this->db->query("SELECT pr.id, pr.package_name, pr.site_name, pr.site_address, pr.district, 
                         pr.number_of_guards as number_of_officers, 
                         pr.day_guards as number_of_supervisors, 
                         pr.night_guards as number_of_caretakers, 
                         pr.package_price, pr.status, pr.submitted_date, pr.start_date, pr.comments,
                         pr.draft_site_id,
                         pr.payment_status,
                         pr.payment_id,
                         p.price_per_officer as officer_price,
                         p.price_per_supervisor as supervisor_price,
                         p.price_per_caretaker as caretaker_price
                         FROM package_requests pr
                         LEFT JOIN packages p ON pr.package_name = p.package_name
                         WHERE pr.client_id = :client_id AND pr.status = 'Pending' 
                         ORDER BY pr.submitted_date DESC");
        $this->db->bind(':client_id', $client_id);
        return $this->db->resultSet();
    }

    public function deletePackageRequest($id, $client_id) {
        // Only allow deletion of pending requests that haven't been paid
        // Check both the package_requests.payment_status column AND payments table
        $this->db->query('SELECT payment_status FROM package_requests WHERE id = :id AND client_id = :client_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':client_id', $client_id);
        $pkgResult = $this->db->single();
        
        if ($pkgResult && $pkgResult->payment_status === 'paid') {
            // Request is marked as paid in package_requests table
            return false;
        }
        
        // Also check payments table as a fallback
        $this->db->query('SELECT COUNT(*) as count FROM payments WHERE package_request_id = :id AND status = :paid_status');
        $this->db->bind(':id', $id);
        $this->db->bind(':paid_status', 'paid');
        $result = $this->db->single();
        
        if ($result->count > 0) {
            // Request has been paid, don't allow deletion
            return false;
        }
        
        // Delete only pending requests without paid payments
        $this->db->query('DELETE FROM package_requests WHERE id = :id AND client_id = :client_id AND status = :status');
        $this->db->bind(':id', $id);
        $this->db->bind(':client_id', $client_id);
        $this->db->bind(':status', 'pending');
        return $this->db->execute();
    }

    public function getPendingRequestsForSite($siteName, $client_id) {
        // Get all pending package requests for a specific site by site name
        $this->db->query('
            SELECT * FROM package_requests 
            WHERE site_name = :site_name 
            AND client_id = :client_id 
            AND status = :status
        ');
        $this->db->bind(':site_name', $siteName);
        $this->db->bind(':client_id', $client_id);
        $this->db->bind(':status', 'pending');
        return $this->db->resultSet();
    }

    public function deletePendingRequestsForSite($siteName, $client_id) {
        // Delete all pending package requests for a specific site by site name
        $this->db->query('
            DELETE FROM package_requests 
            WHERE site_name = :site_name 
            AND client_id = :client_id 
            AND status = :status
        ');
        $this->db->bind(':site_name', $siteName);
        $this->db->bind(':client_id', $client_id);
        $this->db->bind(':status', 'pending');
        
        return $this->db->execute();
    }

    // ==================== EQUIPMENT REQUESTS METHODS ====================

    // Get all equipment requests for client's caretakers with filters
    public function getAllEquipmentRequests($client_id, $filters = []) {
        $query = '
            SELECT er.*, 
                   u.name as caretaker_name, 
                   ud.nic,
                   s.site_name,
                   s.address as site_address
            FROM equipment_requests er
            JOIN Users u ON er.caretaker_id = u.id
            LEFT JOIN user_details ud ON u.id = ud.user_id
            JOIN caretaker_site_assignments csa ON er.caretaker_id = csa.caretaker_id AND csa.status = "Active"
            JOIN sites s ON csa.site_id = s.id
            WHERE s.client_id = :client_id
            AND s.is_draft = 0
            AND er.status != "Pending"
        ';
        
        // Add filters
        if (!empty($filters['status'])) {
            $query .= ' AND er.status = :status';
        }
        if (!empty($filters['priority'])) {
            $query .= ' AND er.priority = :priority';
        }
        if (!empty($filters['caretaker_id'])) {
            $query .= ' AND er.caretaker_id = :caretaker_id';
        }
        if (!empty($filters['date_from'])) {
            $query .= ' AND er.requested_date >= :date_from';
        }
        if (!empty($filters['date_to'])) {
            $query .= ' AND er.requested_date <= :date_to';
        }
        
        $query .= ' ORDER BY 
                    CASE er.status
                        WHEN "Supervisor Approved" THEN 1
                        WHEN "Approved" THEN 2
                        WHEN "Rejected" THEN 3
                        WHEN "Pending" THEN 4
                    END,
                    CASE er.priority 
                        WHEN "High" THEN 1 
                        WHEN "Medium" THEN 2 
                        WHEN "Low" THEN 3 
                    END,
                    er.requested_date DESC';
        
        $this->db->query($query);
        
        // Bind client_id first
        $this->db->bind(':client_id', $client_id);
        
        if (!empty($filters['status'])) {
            $this->db->bind(':status', $filters['status']);
        }
        if (!empty($filters['priority'])) {
            $this->db->bind(':priority', $filters['priority']);
        }
        if (!empty($filters['caretaker_id'])) {
            $this->db->bind(':caretaker_id', $filters['caretaker_id']);
        }
        if (!empty($filters['date_from'])) {
            $this->db->bind(':date_from', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->bind(':date_to', $filters['date_to']);
        }
        
        return $this->db->resultSet();
    }

    // Get equipment request details by ID for client
    public function getEquipmentRequestDetails($id, $client_id) {
        $this->db->query('
            SELECT er.*, 
                   u.name as caretaker_name, 
                   u.phone_number as contact_number,
                   s.site_name,
                   supervisor_user.name as supervisor_name
            FROM equipment_requests er
            JOIN Users u ON er.caretaker_id = u.id
            LEFT JOIN caretaker_site_assignments csa ON er.caretaker_id = csa.caretaker_id AND csa.status = "Active"
            LEFT JOIN sites s ON csa.site_id = s.id
            LEFT JOIN Users supervisor_user ON er.supervisor_approved_by = supervisor_user.id
            WHERE er.id = :id
            AND (s.is_draft = 0 OR s.is_draft IS NULL)
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Approve equipment request (as client)
    public function approveEquipmentRequest($data) {
        $this->db->query('
            UPDATE equipment_requests 
            SET status = "Approved",
                client_notes = :client_notes,
                approved_date = CURDATE()
            WHERE id = :id AND status = "Supervisor Approved"
        ');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':client_notes', $data['client_notes'] ?? '');
        
        return $this->db->execute();
    }

    // Reject equipment request (as client)
    public function rejectEquipmentRequest($data) {
        $this->db->query('
            UPDATE equipment_requests 
            SET status = "Rejected",
                client_notes = :client_notes,
                approved_date = CURDATE()
            WHERE id = :id AND status = "Supervisor Approved"
        ');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':client_notes', $data['client_notes'] ?? '');
        $this->db->bind(':client_id', $data['client_id'] ?? null);
        
        return $this->db->execute();
    }

    // Get equipment request statistics for client
    public function getEquipmentRequestStats($client_id) {
        $this->db->query('
            SELECT 
                COUNT(*) as total_requests,
                SUM(CASE WHEN er.status = "Supervisor Approved" THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN er.status = "Approved" OR er.status = "Client Approved" THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN er.status = "Rejected" THEN 1 ELSE 0 END) as rejected_count,
                SUM(CASE WHEN er.status = "Supervisor Approved" THEN er.total_cost ELSE 0 END) as pending_cost,
                SUM(CASE WHEN er.status = "Approved" OR er.status = "Client Approved" THEN er.total_cost ELSE 0 END) as approved_cost,
                SUM(CASE WHEN er.status = "Rejected" THEN er.total_cost ELSE 0 END) as rejected_cost
            FROM equipment_requests er
            INNER JOIN caretaker_site_assignments csa ON er.caretaker_id = csa.caretaker_id AND csa.status = "Active"
            INNER JOIN sites s ON csa.site_id = s.id
            WHERE s.client_id = :client_id
            AND s.is_draft = 0
            AND er.status != "Pending"
        ');
        $this->db->bind(':client_id', $client_id);
        
        return $this->db->single();
    }

    // Get all caretakers for this client (for filter dropdown)
    public function getCaretakersForClient($client_id) {
        $this->db->query('
            SELECT DISTINCT u.id, u.name 
            FROM Users u
            INNER JOIN caretaker_site_assignments csa ON u.id = csa.caretaker_id
            INNER JOIN sites s ON csa.site_id = s.id
            WHERE u.role = "Care-Taker"
            AND csa.status = "Active"
            AND s.is_draft = 0
            AND s.client_id = :client_id
            ORDER BY u.name
        ');
        $this->db->bind(':client_id', $client_id);
        return $this->db->resultSet();
    }

    // Get all sites for a specific client
    public function getClientSites($client_id) {
        $this->db->query('
            SELECT s.*, 
                   COUNT(DISTINCT CASE WHEN osa.shift_type != "Supervisor" THEN osa.officer_id END) as assigned_officers,
                   COUNT(DISTINCT CASE WHEN osa.shift_type = "Supervisor" THEN osa.officer_id END) as assigned_supervisors,
                   COUNT(DISTINCT csa.caretaker_id) as assigned_caretakers,
                   supervisor.name as supervisor_name
            FROM sites s
            LEFT JOIN officer_site_assignments osa ON s.id = osa.site_id AND osa.status = "Active"
            LEFT JOIN caretaker_site_assignments csa ON s.id = csa.site_id AND csa.status = "Active"
            LEFT JOIN officer_site_assignments sup_osa ON s.id = sup_osa.site_id AND sup_osa.status = "Active" AND sup_osa.shift_type = "Supervisor"
            LEFT JOIN Users supervisor ON sup_osa.officer_id = supervisor.id
            WHERE s.client_id = :client_id
            AND s.is_draft = 0
            GROUP BY s.id
            ORDER BY s.created_at DESC
        ');
        $this->db->bind(':client_id', $client_id);
        return $this->db->resultSet();
    }

    // Get single site details for a client
    public function getSiteDetails($site_id, $client_id) {
        $this->db->query('
            SELECT s.*, 
                   supervisor.name as supervisor_name,
                   supervisor.phone_number as supervisor_phone,
                   supervisor.email as supervisor_email
            FROM sites s
            LEFT JOIN officer_site_assignments sup_osa ON s.id = sup_osa.site_id AND sup_osa.status = "Active" AND sup_osa.shift_type = "Supervisor"
            LEFT JOIN Users supervisor ON sup_osa.officer_id = supervisor.id
            WHERE s.id = :site_id 
            AND s.client_id = :client_id
            AND s.is_draft = 0
        ');
        $this->db->bind(':site_id', $site_id);
        $this->db->bind(':client_id', $client_id);
        return $this->db->single();
    }

    // Get officers assigned to a site (excluding supervisors)
    public function getSiteOfficers($site_id) {
        $this->db->query('
            SELECT u.*, osa.assignment_start, osa.assignment_end, osa.shift_type,
                   po.officerID
            FROM Users u
            JOIN officer_site_assignments osa ON u.id = osa.officer_id
            LEFT JOIN premise_officers po ON u.id = po.userID
            WHERE osa.site_id = :site_id
            AND osa.status = "Active"
            AND osa.shift_type != "Supervisor"
            ORDER BY osa.assignment_start DESC
        ');
        $this->db->bind(':site_id', $site_id);
        return $this->db->resultSet();
    }

    // Get caretakers assigned to a site
    public function getSiteCaretakers($site_id) {
        $this->db->query('
            SELECT u.*, csa.assignment_start, csa.assignment_end,
                   ct.caretakerID
            FROM Users u
            JOIN caretaker_site_assignments csa ON u.id = csa.caretaker_id
            LEFT JOIN care_taker ct ON u.id = ct.userID
            WHERE csa.site_id = :site_id
            AND csa.status = "Active"
            ORDER BY csa.assignment_start DESC
        ');
        $this->db->bind(':site_id', $site_id);
        return $this->db->resultSet();
    }

    // Get supervisors assigned to a site
    public function getSiteSupervisors($site_id) {
        $this->db->query('
            SELECT u.*, osa.assignment_start, osa.assignment_end,
                   po.officerID
            FROM Users u
            JOIN officer_site_assignments osa ON u.id = osa.officer_id
            LEFT JOIN premise_officers po ON u.id = po.userID
            WHERE osa.site_id = :site_id
            AND osa.status = "Active"
            AND osa.shift_type = "Supervisor"
            ORDER BY osa.assignment_start DESC
        ');
        $this->db->bind(':site_id', $site_id);
        return $this->db->resultSet();
    }

    /**
     * Get active sites with package information for next payment
     */
    public function getActiveSitesWithPackages($client_id) {
        $this->db->query('
            SELECT s.id, s.site_name, s.address, s.district,
                   pr.package_name, pr.package_price,
                   -- Count actual assigned personnel
                   (SELECT COUNT(*) FROM officer_site_assignments osa 
                    WHERE osa.site_id = s.id 
                    AND osa.status = "Active" 
                    AND (osa.shift_type != "Supervisor" OR osa.shift_type IS NULL)) as number_of_officers,
                   (SELECT COUNT(*) FROM officer_site_assignments osa 
                    WHERE osa.site_id = s.id 
                    AND osa.status = "Active" 
                    AND osa.shift_type = "Supervisor") as number_of_supervisors,
                   (SELECT COUNT(*) FROM caretaker_site_assignments csa 
                    WHERE csa.site_id = s.id 
                    AND csa.status = "Active") as number_of_caretakers,
                   -- Get pricing from packages table, fallback to Custom Package if null
                   COALESCE(p.price_per_officer, (SELECT price_per_officer FROM packages WHERE package_name = "Custom Package" LIMIT 1), 0) as officer_price,
                   COALESCE(p.price_per_supervisor, (SELECT price_per_supervisor FROM packages WHERE package_name = "Custom Package" LIMIT 1), 0) as supervisor_price,
                   COALESCE(p.price_per_caretaker, (SELECT price_per_caretaker FROM packages WHERE package_name = "Custom Package" LIMIT 1), 0) as caretaker_price
            FROM sites s
            LEFT JOIN package_requests pr ON s.package_request_id = pr.id
            LEFT JOIN packages p ON pr.package_name = p.package_name
            WHERE s.client_id = :client_id
            AND s.is_draft = 0
            ORDER BY s.site_name ASC
        ');
        $this->db->bind(':client_id', $client_id);
        return $this->db->resultSet();
    }
    
    /**
     * Approve a package request
     * @param int $request_id
     * @return bool
     */
    public function approvePackageRequest($request_id) {
        $this->db->query('
            UPDATE package_requests 
            SET status = "approved",
                updated_at = NOW()
            WHERE id = :request_id
        ');
        $this->db->bind(':request_id', $request_id);
        return $this->db->execute();
    }
}
