<?php
class M_advertisements {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get advertisements targeted to a specific user role
     * 
     * @param string $role The role to filter advertisements for
     * @return array List of advertisements
     */
    public function getAdvertisementsByRole($role) {
        $this->db->query('
            SELECT a.*, u.name AS creator_name
            FROM advertisements a
            LEFT JOIN users u ON a.created_by = u.id
            WHERE a.status = "active"
              AND (a.target_roles LIKE :role OR a.target_roles = "all")
            ORDER BY a.created_at DESC
        ');

        // Bind the parameter safely (e.g. "%premise officer%")
        $this->db->bind(':role', '%' . $role . '%');

        return $this->db->resultSet();
    }
}
?>