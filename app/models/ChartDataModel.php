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

    /**
     * Get comprehensive incident analytics data for charts
     */
    public function getIncidentAnalytics() {
        $analytics = [
            'statusChart' => $this->getIncidentsByStatus(),
            'severityChart' => $this->getIncidentsBySeverity(),
            'monthlyTrend' => $this->getMonthlyIncidentTrend(),
            'categoryChart' => $this->getIncidentsByCategory(),
            'locationChart' => $this->getIncidentsByLocation(),
            'responseTimeChart' => $this->getAverageResponseTime()
        ];
        
        return $analytics;
    }

    /**
     * Get incidents grouped by status
     */
    private function getIncidentsByStatus() {
        $this->db->query('
            SELECT 
                status,
                COUNT(*) as count
            FROM incident_reports 
            GROUP BY status
        ');
        
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'colors' => ['#FFC107', '#2196F3', '#4CAF50', '#F44336']
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = ucfirst($row->status);
            $data['data'][] = $row->count;
        }
        
        return $data;
    }

    /**
     * Get incidents grouped by severity
     */
    private function getIncidentsBySeverity() {
        $this->db->query('
            SELECT 
                severity,
                COUNT(*) as count
            FROM incident_reports 
            GROUP BY severity
            ORDER BY FIELD(severity, "critical", "high", "medium", "low")
        ');
        
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'colors' => ['#D32F2F', '#FF6F00', '#FBC02D', '#388E3C']
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = ucfirst($row->severity);
            $data['data'][] = $row->count;
        }
        
        return $data;
    }

    /**
     * Get monthly incident trend for the last 12 months
     */
    private function getMonthlyIncidentTrend() {
        $this->db->query('
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as count
            FROM incident_reports 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, "%Y-%m")
            ORDER BY month ASC
        ');
        
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'borderColor' => '#D32F2F',
            'backgroundColor' => 'rgba(211, 47, 47, 0.1)'
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = date('M Y', strtotime($row->month . '-01'));
            $data['data'][] = $row->count;
        }
        
        return $data;
    }

    /**
     * Get incidents grouped by category
     */
    private function getIncidentsByCategory() {
        $this->db->query('
            SELECT 
                incident_type as category,
                COUNT(*) as count
            FROM incident_reports 
            GROUP BY incident_type
            ORDER BY count DESC
            LIMIT 10
        ');
        
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'colors' => [
                '#B71C1C', '#D32F2F', '#F44336', '#FF5252', '#FF8A80',
                '#E65100', '#F57C00', '#FF9800', '#FFB74D', '#FFCC80'
            ]
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = ucfirst($row->category);
            $data['data'][] = $row->count;
        }
        
        return $data;
    }

    /**
     * Get incidents grouped by location/site
     */
    private function getIncidentsByLocation() {
        $this->db->query('
            SELECT 
                s.site_name,
                COUNT(ir.id) as count
            FROM incident_reports ir
            LEFT JOIN sites s ON ir.site_id = s.id
            GROUP BY s.site_name
            ORDER BY count DESC
            LIMIT 10
        ');
        
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'backgroundColor' => '#2196F3'
        ];
        
        foreach ($result as $row) {
            $siteName = $row->site_name ?? 'Unknown';
            $data['labels'][] = $siteName;
            $data['data'][] = $row->count;
        }
        
        return $data;
    }

    /**
     * Get average response time statistics
     */
    private function getAverageResponseTime() {
        $this->db->query('
            SELECT 
                severity,
                AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours
            FROM incident_reports 
            WHERE status = "Resolved"
            GROUP BY severity
        ');
        
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'backgroundColor' => [
                'rgba(211, 47, 47, 0.6)',
                'rgba(255, 111, 0, 0.6)',
                'rgba(251, 192, 45, 0.6)',
                'rgba(56, 142, 60, 0.6)'
            ]
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = ucfirst($row->severity);
            $data['data'][] = round($row->avg_hours, 1);
        }
        
        return $data;
    }
}
?>