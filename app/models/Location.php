<?php
// Location.php - Model

class Location {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAllLocations() {
        $query = "SELECT * FROM locations ORDER BY created_at DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }
    
    public function getLocationById($id) {
        $query = "SELECT * FROM locations WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function saveLocation($data) {
        $query = "INSERT INTO locations (name, address, lat, lng) 
                  VALUES (:name, :address, :lat, :lng)";
        $this->db->query($query);
        
        // Bind parameters
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':lat', $data['lat']);
        $this->db->bind(':lng', $data['lng']);
        
        // Execute and return result
        return $this->db->execute();
    }
    
    public function updateLocation($id, $data) {
        $query = "UPDATE locations 
                  SET name = :name, address = :address, lat = :lat, lng = :lng
                  WHERE id = :id";
        $this->db->query($query);
        
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':lat', $data['lat']);
        $this->db->bind(':lng', $data['lng']);
        
        return $this->db->execute();
    }
    
    public function deleteLocation($id) {
        $query = "DELETE FROM locations WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    public function searchLocations($keyword) {
        $query = "SELECT * FROM locations 
                  WHERE name LIKE :keyword OR address LIKE :keyword 
                  ORDER BY name";
        $this->db->query($query);
        $this->db->bind(':keyword', '%' . $keyword . '%');
        return $this->db->resultSet();
    }
    
    public function getLocationsByProximity($lat, $lng, $radiusKm = 10) {
        // Using Haversine formula for distance calculation
        $query = "SELECT *, 
                  (6371 * acos(cos(radians(:lat)) * cos(radians(lat)) 
                  * cos(radians(lng) - radians(:lng)) + sin(radians(:lat)) 
                  * sin(radians(lat)))) AS distance 
                  FROM locations 
                  HAVING distance < :radius 
                  ORDER BY distance";
        
        $this->db->query($query);
        $this->db->bind(':lat', $lat);
        $this->db->bind(':lng', $lng);
        $this->db->bind(':radius', $radiusKm);
        
        return $this->db->resultSet();
    }
}
?>