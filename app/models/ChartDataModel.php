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
            WHERE s.is_draft = 0 OR s.is_draft IS NULL
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

    /**
     * Get incidents by severity for a specific client's sites
     */
    public function getClientIncidentsBySeverity($client_id) {
        $this->db->query('
            SELECT 
                severity,
                COUNT(*) as count
            FROM incident_reports ir
            INNER JOIN sites s ON ir.site_id = s.id
            WHERE s.client_id = :client_id AND s.is_draft = 0
            GROUP BY severity
            ORDER BY FIELD(severity, "critical", "high", "medium", "low")
        ');
        
        $this->db->bind(':client_id', $client_id);
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
     * Get monthly incident trend for a specific client's sites (last 6 months)
     */
    public function getClientMonthlyIncidentTrend($client_id) {
        $this->db->query('
            SELECT 
                DATE_FORMAT(ir.created_at, "%Y-%m") as month,
                COUNT(*) as count
            FROM incident_reports ir
            INNER JOIN sites s ON ir.site_id = s.id
            WHERE s.client_id = :client_id 
            AND s.is_draft = 0
            AND ir.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(ir.created_at, "%Y-%m")
            ORDER BY month ASC
        ');
        
        $this->db->bind(':client_id', $client_id);
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
     * Get incidents by site for a specific client
     */
    public function getClientIncidentsBySite($client_id) {
        $this->db->query('
            SELECT 
                s.site_name,
                COUNT(ir.id) as count
            FROM sites s
            LEFT JOIN incident_reports ir ON s.id = ir.site_id
            WHERE s.client_id = :client_id AND s.is_draft = 0
            GROUP BY s.id, s.site_name
            ORDER BY count DESC
            LIMIT 10
        ');
        
        $this->db->bind(':client_id', $client_id);
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
            $data['labels'][] = $row->site_name;
            $data['data'][] = $row->count;
        }
        
        return $data;
    }

    /**
     * Get incidents by type/category for a specific client
     */
    public function getClientIncidentsByType($client_id) {
        $this->db->query('
            SELECT 
                ir.incident_type,
                COUNT(*) as count
            FROM incident_reports ir
            INNER JOIN sites s ON ir.site_id = s.id
            WHERE s.client_id = :client_id AND s.is_draft = 0
            GROUP BY ir.incident_type
            ORDER BY count DESC
            LIMIT 8
        ');
        
        $this->db->bind(':client_id', $client_id);
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'colors' => [
                '#B71C1C', '#D32F2F', '#F44336', '#FF5252',
                '#FF8A80', '#E65100', '#F57C00', '#FF9800'
            ]
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = ucfirst($row->incident_type);
            $data['data'][] = $row->count;
        }
        
        return $data;
    }

    /**
     * Get incidents by status for a specific client
     */
    public function getClientIncidentsByStatus($client_id) {
        $this->db->query('
            SELECT 
                ir.status,
                COUNT(*) as count
            FROM incident_reports ir
            INNER JOIN sites s ON ir.site_id = s.id
            WHERE s.client_id = :client_id AND s.is_draft = 0
            GROUP BY ir.status
        ');
        
        $this->db->bind(':client_id', $client_id);
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
     * Get total officers per site (premise officers + supervisors + caretakers)
     */
    public function getClientSitesWithOfficers($client_id) {
        $this->db->query('
            SELECT 
                s.id,
                s.site_name,
                COALESCE(pm.premise_officers, 0) +
                COALESCE(sp.supervisors, 0) +
                COALESCE(ct.caretakers, 0) as total_officers
            FROM sites s
            LEFT JOIN (
                SELECT site_id, COUNT(*) as premise_officers
                FROM officer_site_assignments
                WHERE status = "Active" AND (shift_type != "Supervisor")
                GROUP BY site_id
            ) pm ON s.id = pm.site_id
            LEFT JOIN (
                SELECT site_id, COUNT(*) as supervisors
                FROM officer_site_assignments
                WHERE status = "Active" AND shift_type = "Supervisor"
                GROUP BY site_id
            ) sp ON s.id = sp.site_id
            LEFT JOIN (
                SELECT site_id, COUNT(*) as caretakers
                FROM caretaker_site_assignments
                WHERE status = "Active"
                GROUP BY site_id
            ) ct ON s.id = ct.site_id
            WHERE s.client_id = :client_id AND (s.is_draft = 0 OR s.is_draft IS NULL)
            ORDER BY s.site_name
        ');
        
        $this->db->bind(':client_id', $client_id);
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'backgroundColor' => '#FF8A80'
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = $row->site_name;
            $data['data'][] = (int)$row->total_officers;
        }
        
        return $data;
    }

    /**
     * Get incident status pie chart data (Resolved, Pending, In Progress)
     */
    public function getClientIncidentStatusPie($client_id) {
        $this->db->query('
            SELECT 
                ir.status,
                COUNT(*) as count
            FROM incident_reports ir
            INNER JOIN sites s ON ir.site_id = s.id
            WHERE s.client_id = :client_id AND s.is_draft = 0
            GROUP BY ir.status
            ORDER BY ir.status
        ');
        
        $this->db->bind(':client_id', $client_id);
        $result = $this->db->resultSet();
        
        $statusColors = [
            'Resolved' => '#66BB6A',
            'Pending' => '#FFB74D',
            'In Progress' => '#42A5F5',
            'Rejected' => '#FF8A80'
        ];
        
        $data = [
            'labels' => [],
            'data' => [],
            'colors' => []
        ];
        
        foreach ($result as $row) {
            $status = $row->status;
            $data['labels'][] = ucfirst($status);
            $data['data'][] = (int)$row->count;
            $data['colors'][] = $statusColors[$status] ?? '#999999';
        }
        
        return $data;
    }

    /**
     * Get payment history (last 12 months)
     */
    public function getClientPaymentHistory($client_id) {
        $this->db->query('
            SELECT 
                DATE_FORMAT(payment_date, "%Y-%m") as month,
                SUM(amount) as total_amount
            FROM payments
            WHERE client_id = :client_id
            AND payment_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            AND payment_date IS NOT NULL
            GROUP BY DATE_FORMAT(payment_date, "%Y-%m")
            ORDER BY month ASC
        ');
        
        $this->db->bind(':client_id', $client_id);
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'borderColor' => '#FF8A80',
            'backgroundColor' => 'rgba(255, 138, 128, 0.3)'
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = date('M Y', strtotime($row->month . '-01'));
            $data['data'][] = (float)$row->total_amount;
        }
        
        return $data;
    }

    /**
     * Get next payment due for each site (pending payments)
     */
    public function getClientNextPaymentBySite($client_id) {
        $this->db->query('
            SELECT 
                s.site_name,
                COALESCE(SUM(p.amount), 0) as next_payment_amount
            FROM sites s
            LEFT JOIN payments p ON s.id = p.site_id 
            AND p.client_id = :client_id 
            AND p.status = "pending"
            WHERE s.client_id = :client_id AND (s.is_draft = 0 OR s.is_draft IS NULL)
            GROUP BY s.id, s.site_name
            ORDER BY s.site_name
        ');
        
        $this->db->bind(':client_id', $client_id);
        $result = $this->db->resultSet();
        
        $data = [
            'labels' => [],
            'data' => [],
            'backgroundColor' => '#FFB74D'
        ];
        
        foreach ($result as $row) {
            $data['labels'][] = $row->site_name;
            $data['data'][] = (float)$row->next_payment_amount;
        }
        
        return $data;
    }
}
?>