<?php
    class M_users {
        private $db;
        public function __construct($db = null) {
            // Allow dependency injection for testing; fall back to real Database otherwise
            $this->db = $db ?: new Database();
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
        public function updatePassword($id, $currentPassword, $newPassword) {
            // Get current password hash
            $this->db->query("SELECT password FROM Users WHERE id = :id");
            $this->db->bind(":id", $id);
            $user = $this->db->single();
            
            if (!$user) {
                return false;
            }
            
            // Verify current password
            if (!password_verify($currentPassword, $user->password)) {
                return false;
            }
            
            // Hash new password
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update password
            $this->db->query("UPDATE Users SET password = :password WHERE id = :id");
            $this->db->bind(":password", $hashed);
            $this->db->bind(":id", $id);
            return $this->db->execute();
        }
        
        // Update contact number
        public function updateContact($id, $contact) {
            $this->db->query("UPDATE Users SET contact = :contact WHERE id = :id");
            $this->db->bind(":contact", $contact);
            $this->db->bind(":id", $id);
            return $this->db->execute();
        }
        
        // Update email
        public function updateEmail($id, $email) {
            // Check if email already exists for another user
            $this->db->query("SELECT id FROM Users WHERE email = :email AND id != :id");
            $this->db->bind(":email", $email);
            $this->db->bind(":id", $id);
            $existing = $this->db->single();
            
            if ($existing) {
                return false; // Email already exists
            }
            
            // Update email
            $this->db->query("UPDATE Users SET email = :email WHERE id = :id");
            $this->db->bind(":email", $email);
            $this->db->bind(":id", $id);
            return $this->db->execute();
        }
        
        // Update profile image
        public function updateProfileImage($id, $image_path) {
            $this->db->query("UPDATE Users SET profile_image = :profile_image WHERE id = :id");
            $this->db->bind(":profile_image", $image_path);
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

        // Find user by email (returns boolean)
        public function findUserByEmail($email) {
            $this->db->query("SELECT id FROM Users WHERE email = :email");
            $this->db->bind(":email", $email);
            $this->db->single();
            return $this->db->rowCount() > 0;
        }

        // Get count of users by role
        public function getUserCountByRole($role) {
            $this->db->query("SELECT COUNT(*) as count FROM Users WHERE role = :role");
            $this->db->bind(":role", $role);
            $result = $this->db->single();
            return $result->count ?? 0;
        }

        // Get officer rank from premise_officers table
        public function getOfficerRank($userId) {
            $this->db->query("SELECT rank FROM premise_officers WHERE userID = :userID");
            $this->db->bind(":userID", $userId);
            $result = $this->db->single();
            return $result->rank ?? null;
        }

        // Register new user with details
        public function register($data) {
            try {
                // Start transaction
                $this->db->beginTransaction();
                
                // Insert into users table
                $this->db->query("INSERT INTO Users (userID, name, email, password, role, status, created_at) 
                                 VALUES (:userID, :name, :email, :password, :role, 'active', NOW())");
                
                $this->db->bind(':userID', $data['userID']);
                $this->db->bind(':name', $data['name']);
                $this->db->bind(':email', $data['email']);
                $this->db->bind(':password', $data['password']);
                $this->db->bind(':role', $data['role']);
                
                if (!$this->db->execute()) {
                    throw new Exception('Failed to insert user');
                }
                
                // Get the inserted user ID
                $userId = $this->db->lastInsertId();
                
                // Insert into user_details table
                $this->db->query("INSERT INTO user_details (user_id, nic, mobile, address, additional_info) 
                                 VALUES (:user_id, :nic, :mobile, :address, :additional_info)");
                
                $this->db->bind(':user_id', $userId);
                $this->db->bind(':nic', $data['nic'] ?? null);
                $this->db->bind(':mobile', $data['mobile'] ?? null);
                $this->db->bind(':address', $data['address'] ?? null);
                $this->db->bind(':additional_info', $data['additional_info'] ?? null);
                
                if (!$this->db->execute()) {
                    throw new Exception('Failed to insert user details');
                }
                
                // Commit transaction
                $this->db->commit();
                return true;
                
            } catch (Exception $e) {
                // Rollback on error
                $this->db->rollBack();
               // error_log("User registration error: " . $e->getMessage());
                return false;
            }
        }

        // Get user online status
        public function getUserOnlineStatus($userId)
        {
            $this->db->query("SELECT is_online, last_seen FROM Users WHERE id = :id");
            $this->db->bind(':id', $userId);
            return $this->db->single();
        }

        // Update user last seen
        public function updateLastSeen($userId)
        {
            $this->db->query("UPDATE Users SET last_seen = NOW(), is_online = 1 WHERE id = :id");
            $this->db->bind(':id', $userId);
            return $this->db->execute();
        }

        // Set user offline
        public function setUserOffline($userId)
        {
            $this->db->query("UPDATE Users SET is_online = 0, last_seen = NOW() WHERE id = :id");
            $this->db->bind(':id', $userId);
            return $this->db->execute();
        }
    }
?>