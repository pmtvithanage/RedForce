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
            $this->db->query("SELECT * FROM Users WHERE userID = :userID");
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

            if ($this->db->rowCount() > 0) {
                $hashedPassword = $row->password;
                if (password_verify($password, $hashedPassword)) {
                    return $row; // User authenticated successfully
                } else {
                    return false; // Invalid password
                }
            } else {
                return false; // User not found
            }
        }

        // Get user by ID
        public function getUserById($id) {
            $this->db->query("SELECT * FROM Users WHERE id = :id");
            $this->db->bind(":id", $id);
            return $this->db->single();
        }

        // Get user by userID
        public function getUserByUserID($userID) {
            $this->db->query("SELECT * FROM Users WHERE userID = :userID");
            $this->db->bind(":userID", $userID);
            return $this->db->single();
        }

        // Create new user
        public function createUser($data) {
            $this->db->query("INSERT INTO Users (userID, name, email, password, role, created_at) VALUES (:userID, :name, :email, :password, :role, NOW())");
            
            // Hash password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Bind values
            $this->db->bind(":userID", $data['userID']);
            $this->db->bind(":name", $data['name']);
            $this->db->bind(":email", $data['email']);
            $this->db->bind(":password", $hashedPassword);
            $this->db->bind(":role", $data['role']);
            
            // Execute
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }

        // Update user
        public function updateUser($data) {
            $this->db->query("UPDATE Users SET name = :name, email = :email, role = :role WHERE id = :id");
            
            // Bind values
            $this->db->bind(":id", $data['id']);
            $this->db->bind(":name", $data['name']);
            $this->db->bind(":email", $data['email']);
            $this->db->bind(":role", $data['role']);
            
            // Execute
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }

        // Delete user
        public function deleteUser($id) {
            $this->db->query("DELETE FROM Users WHERE id = :id");
            $this->db->bind(":id", $id);
            
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }

        // Update user password (hashed expected or raw to be hashed here)
        public function updatePassword($id, $rawPassword) {
            $hashed = password_hash($rawPassword, PASSWORD_DEFAULT);
            $this->db->query("UPDATE Users SET password = :password WHERE id = :id");
            $this->db->bind(":password", $hashed);
            $this->db->bind(":id", $id);
            return $this->db->execute();
        }

        // Get users by role
        public function getUsersByRole($role) {
            $this->db->query("SELECT * FROM Users WHERE role = :role");
            $this->db->bind(":role", $role);
            return $this->db->resultSet();
        }

        // Check if userID exists
        public function userIDExists($userID) {
            $this->db->query("SELECT userID FROM Users WHERE userID = :userID");
            $this->db->bind(":userID", $userID);
            $row = $this->db->single();
            
            if ($this->db->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }

        // Check if email exists
        public function emailExists($email) {
            $this->db->query("SELECT email FROM Users WHERE email = :email");
            $this->db->bind(":email", $email);
            $row = $this->db->single();
            
            if ($this->db->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
?>