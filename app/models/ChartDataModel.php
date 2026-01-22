<?php
class ChartDataModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get user role distribution for pie chart
     */
    public function getUserRolePieChart() {
        $this->db->query('
            SELECT 
                role,
                COUNT(*) as count
            FROM Users 
            GROUP BY role
        ');
        
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'colors' => [
                '#B71C1C', // Deep Red - admin
                '#D32F2F', // Red - supervisor
                '#F44336', // Light Red - premise officer
                '#FF5252', // Bright Red - mobile rider
                '#FF8A80', // Pale Red - client
                '#FFCDD2'  // Very Light Red/Pink - caretaker
            ]
            /*'colors' => [
                '#FF6384', // admin
                '#36A2EB', // supervisor
                '#FFCE56', // premise officer
                '#4BC0C0', // mobile rider
                '#9966FF', // client
                '#FF9F40'  // caretaker
            ]*/
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = ucwords($row->role);
            $data['data'][] = $row->count;
        }
        
        return $data;
    }
}
?>