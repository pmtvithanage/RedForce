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

         //find user by userID
        public function findUserByUserID($userID) {
            $this->db->query("SELECT * FROM Users wHERE userID = :userID");
            $this->db->bind(":userID", $userID);
            $row = $this->db->single();
            
            if ($this->db->rowCount() > 0) {
                return true; // User with this userID exists
            } else {
                return false; // No user found with this userID
            }
        }

        //login user
        public function login($userID, $password) {
            $this->db->query("SELECT * FROM Users WHERE userID = :userID");
            $this->db->bind(":userID", $userID);
            $row = $this->db->single();

            $hashedPassword = $row->password;
            if (password_verify($password, $hashedPassword)) {
                return $row; // User authenticated successfully
            } else {
                return false; // Invalid password
            }
        }
    }
?>