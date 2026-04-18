<?php
class MapController {
    private $locationModel;
    
    public function __construct() {
        $this->locationModel = $this->model('Location');
    }
    
    public function index() {
        $locations = $this->locationModel->getAllLocations();
        $apiKey = 'AIzaSyBUc91mpWQzeFwZB8byhyY0GBGSS35XjW0'; // Google Maps API Key
        
        require_once '../app/views/map/index.php';
    }
    
    public function show($id) {
        $location = $this->locationModel->getLocationById($id);
        require_once '../app/views/map/show.php';
    }
    
    public function saveLocation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'lat' => $_POST['lat'],
                'lng' => $_POST['lng']
            ];
            
            $result = $this->locationModel->saveLocation($data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Location saved']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error saving location']);
            }
        }
    }
    
    public function getLocations() {
        $locations = $this->locationModel->getAllLocations();
        header('Content-Type: application/json');
        echo json_encode($locations);
    }
}
?>