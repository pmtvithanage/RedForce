<?php
class RecruitmentModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Create new job posting
    public function createJob($data) {
        $this->db->query('INSERT INTO recruitment_jobs 
                         (job_title, job_description, qualifications, requirements, salary_range, location, due_date, status, officer_type, created_at) 
                         VALUES (:job_title, :job_description, :qualifications, :requirements, :salary_range, :location, :due_date, :status, :officer_type, NOW())');

        // Bind values
        $this->db->bind(':job_title', $data['job_title']);
        $this->db->bind(':job_description', $data['job_description']);
        $this->db->bind(':qualifications', $data['qualifications']);
        $this->db->bind(':requirements', $data['requirements']);
        $this->db->bind(':salary_range', $data['salary_range']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':officer_type', $data['officer_type']);

        // Execute
        return $this->db->execute();
    }

    // Get job by ID
    public function getJobById($id) {
        $this->db->query('SELECT * FROM recruitment_jobs WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Get all jobs by officer type
    public function getJobsByOfficerType($officerType) {
        $this->db->query('SELECT * FROM recruitment_jobs WHERE officer_type = :officer_type ORDER BY created_at DESC');
        $this->db->bind(':officer_type', $officerType);

        $results = $this->db->resultSet();

        return $results;
    }

    // Update job posting
    public function updateJob($data) {
        $this->db->query('UPDATE recruitment_jobs 
                         SET job_title = :job_title, 
                             job_description = :job_description, 
                             qualifications = :qualifications, 
                             requirements = :requirements, 
                             salary_range = :salary_range, 
                             location = :location, 
                             due_date = :due_date, 
                             status = :status, 
                             officer_type = :officer_type,
                             updated_at = NOW()
                         WHERE id = :id');

        // Bind values
        $this->db->bind(':id', $data['job_id']);
        $this->db->bind(':job_title', $data['job_title']);
        $this->db->bind(':job_description', $data['job_description']);
        $this->db->bind(':qualifications', $data['qualifications']);
        $this->db->bind(':requirements', $data['requirements']);
        $this->db->bind(':salary_range', $data['salary_range']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':officer_type', $data['officer_type']);

        // Execute
        return $this->db->execute();
    }

    // Delete job posting
    public function deleteJob($id) {
        $this->db->query('DELETE FROM recruitment_jobs WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // Get active job postings
    public function getActiveJobs($officerType = null) {
        $query = 'SELECT * FROM recruitment_jobs WHERE status = "active"';
        
        if ($officerType) {
            $query .= ' AND officer_type = :officer_type';
        }
        
        $query .= ' ORDER BY created_at DESC';

        $this->db->query($query);
        
        if ($officerType) {
            $this->db->bind(':officer_type', $officerType);
        }

        $results = $this->db->resultSet();

        return $results;
    }

    // Check if job exists
    public function jobExists($id) {
        $this->db->query('SELECT id FROM recruitment_jobs WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row ? true : false;
    }
}
?>