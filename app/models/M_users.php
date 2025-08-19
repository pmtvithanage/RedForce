<?php
    class M_users {
        private $db;
        public function __construct() {
            // Initialize the database connection
            $this->db = new Database();
        }

        public function getUsers() {
            $this->db->query("SELECT * FROM Users");
            return $this->db->resultSet();
        }
    }
?>