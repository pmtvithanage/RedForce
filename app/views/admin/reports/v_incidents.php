<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<style>
    .analytics-container {
        padding: 20px;
        background: #f5f5f5;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .stat-card h3 {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .stat-card .value {
        font-size: 36px;
        font-weight: bold;
        color: #D32F2F;
    }
    
    .stat-card.pending .value { color: #FFC107; }
    .stat-card.in-progress .value { color: #2196F3; }
    .stat-card.resolved .value { color: #4CAF50; }
    
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .chart-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .chart-card h2 {
        font-size: 18px;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #D32F2F;
        padding-bottom: 10px;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
    }
    
    .chart-container.tall {
        height: 400px;
    }
    
    .recent-incidents {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .recent-incidents h2 {
        font-size: 18px;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #D32F2F;
        padding-bottom: 10px;
    }
    
    .incident-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .incident-table th {
        background: #f5f5f5;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #ddd;
    }
    
    .incident-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }
    
    .incident-table tr:hover {
        background: #f9f9f9;
    }
    
    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge.critical { background: #D32F2F; color: white; }
    .badge.high { background: #FF6F00; color: white; }
    .badge.medium { background: #FBC02D; color: #333; }
    .badge.low { background: #388E3C; color: white; }
    
    .badge.pending { background: #FFC107; color: #333; }
    .badge.in-progress { background: #2196F3; color: white; }
    .badge.resolved { background: #4CAF50; color: white; }
    
    .pdf-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #D32F2F;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: background 0.2s;
    }
    
    .pdf-button:hover {
        background: #B71C1C;
    }
    
    .pdf-button .material-symbols-outlined {
        font-size: 20px;
    }
    
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .filter-section h3 {
        font-size: 16px;
        margin-bottom: 15px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .filter-controls {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        min-width: 250px;
    }
    
    .filter-group label {
        font-size: 13px;
        color: #666;
        font-weight: 500;
    }
    
    .filter-group select,
    .filter-group input {
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        background: white;
        cursor: pointer;
    }
    
    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #D32F2F;
    }
    
    .filter-buttons {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }
    
    .filter-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .filter-btn.apply {
        background: #D32F2F;
        color: white;
    }
    
    .filter-btn.apply:hover {
        background: #B71C1C;
    }
    
    .filter-btn.reset {
        background: #f5f5f5;
        color: #666;
    }
    
    .filter-btn.reset:hover {
        background: #e0e0e0;
    }
</style>

<!-- Content -->
<div class="analytics-container">
    <button class="tertiary-btn" style="display:flex; width:100px; margin-bottom: 20px;align-items:center;" onclick="history.back()"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>

    <div class="page-header">
        <div>
            <h1><?php echo $data['pageTitle']; ?></h1>
            <p style="color: #666; margin-top: 5px;">Comprehensive incident analytics and reporting</p>
        </div>
        <button class="pdf-button" id="downloadPdf">
            <span class="material-symbols-outlined">picture_as_pdf</span>
            Download PDF Report
        </button>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <h3>
            <span class="material-symbols-outlined">filter_alt</span>
            Filter Reports
        </h3>
        <div class="filter-controls">
            <div class="filter-group">
                <label>Filter by Site</label>
                <select id="siteFilter">
                    <option value="">All Sites</option>
                    <?php if (!empty($data['sites'])): ?>
                        <?php foreach ($data['sites'] as $site): ?>
                            <option value="<?php echo $site->id; ?>"><?php echo htmlspecialchars($site->site_name); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Date Range</label>
                <input type="date" id="startDate" />
            </div>
            
            <div class="filter-group">
                <label>To</label>
                <input type="date" id="endDate" />
            </div>
            
            <div class="filter-buttons">
                <button class="filter-btn apply" id="applyFilter">
                    <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">search</span>
                    Apply Filter
                </button>
                <button class="filter-btn reset" id="resetFilter">
                    <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">refresh</span>
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Incidents</h3>
            <div class="value"><?php echo $data['stats']->total ?? 0; ?></div>
        </div>
        <div class="stat-card pending">
            <h3>Pending</h3>
            <div class="value"><?php echo $data['stats']->pending ?? 0; ?></div>
        </div>
        <div class="stat-card in-progress">
            <h3>In Progress</h3>
            <div class="value"><?php echo $data['stats']->in_progress ?? 0; ?></div>
        </div>
        <div class="stat-card resolved">
            <h3>Resolved</h3>
            <div class="value"><?php echo $data['stats']->resolved ?? 0; ?></div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Status Distribution -->
        <div class="chart-card">
            <h2>Incidents by Status</h2>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Severity Distribution -->
        <div class="chart-card">
            <h2>Incidents by Severity</h2>
            <div class="chart-container">
                <canvas id="severityChart"></canvas>
            </div>
        </div>

        <!-- Monthly Trend -->
        <div class="chart-card" style="grid-column: span 2;">
            <h2>Monthly Incident Trend (Last 12 Months)</h2>
            <div class="chart-container tall">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Incidents Table -->
    <div class="recent-incidents">
        <h2>Recent Incidents</h2>
        <?php if (!empty($data['incidents']) && is_array($data['incidents'])): ?>
            <table class="incident-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Reported By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['incidents'] as $incident): ?>
                        <tr>
                            <td>#<?php echo $incident->id; ?></td>
                            <td><?php echo htmlspecialchars($incident->incident_type ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($incident->site_name ?? 'Unknown'); ?></td>
                            <td><span class="badge <?php echo strtolower($incident->severity ?? 'medium'); ?>"><?php echo ucfirst($incident->severity ?? 'Medium'); ?></span></td>
                            <td><span class="badge <?php echo strtolower(str_replace(' ', '-', $incident->status ?? 'pending')); ?>"><?php echo ucfirst($incident->status ?? 'Pending'); ?></span></td>
                            <td><?php echo htmlspecialchars($incident->officer_name ?? 'N/A'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($incident->created_at)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; padding: 40px; color: #999;">No incidents found</p>
        <?php endif; ?>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- jsPDF for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
// Chart data from PHP
const chartData = <?php echo json_encode($data['chartData']); ?>;

// Chart.js default configuration
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#666';

// Store chart instances for updates
let statusChart, severityChart, monthlyTrendChart;

// Status Chart (Doughnut)
const statusCtx = document.getElementById('statusChart').getContext('2d');
statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: chartData.statusChart.labels,
        datasets: [{
            data: chartData.statusChart.data,
            backgroundColor: chartData.statusChart.colors,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});

// Severity Chart (Pie)
const severityCtx = document.getElementById('severityChart').getContext('2d');
severityChart = new Chart(severityCtx, {
    type: 'pie',
    data: {
        labels: chartData.severityChart.labels,
        datasets: [{
            data: chartData.severityChart.data,
            backgroundColor: chartData.severityChart.colors,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});

// Monthly Trend Chart (Line)
const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
monthlyTrendChart = new Chart(monthlyTrendCtx, {
    type: 'line',
    data: {
        labels: chartData.monthlyTrend.labels,
        datasets: [{
            label: 'Incidents',
            data: chartData.monthlyTrend.data,
            borderColor: chartData.monthlyTrend.borderColor,
            backgroundColor: chartData.monthlyTrend.backgroundColor,
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// PDF Download Functionality
document.getElementById('downloadPdf').addEventListener('click', async function() {
    const button = this;
    button.disabled = true;
    button.innerHTML = '<span class="material-symbols-outlined">hourglass_empty</span> Generating PDF...';
    
    try {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        let currentPage = 1;
        
        // Helper function to add page number
        function addPageNumber() {
            pdf.setFontSize(9);
            pdf.setTextColor(150);
            pdf.text(`Page ${currentPage}`, pageWidth - 30, pageHeight - 10);
            pdf.text('RED FORCE Security Services', 20, pageHeight - 10);
            currentPage++;
        }
        
        // Helper function to add new page
        function addNewPage() {
            addPageNumber();
            pdf.addPage();
        }
        
        // ===== COVER PAGE =====
        // Background color for header
        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 80, 'F');
        
        // Title
        pdf.setTextColor(255, 255, 255);
        pdf.setFontSize(28);
        pdf.setFont(undefined, 'bold');
        pdf.text('INCIDENT REPORT', pageWidth / 2, 35, { align: 'center' });
        
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'normal');
        pdf.text('Analytics & Summary', pageWidth / 2, 50, { align: 'center' });
        
        // Date and filter info
        pdf.setFontSize(11);
        const today = new Date();
        pdf.text(`Generated: ${today.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })}`, pageWidth / 2, 65, { align: 'center' });
        
        // Filter information if applied
        const siteId = document.getElementById('siteFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        let yPos = 100;
        pdf.setTextColor(0);
        pdf.setFontSize(12);
        pdf.setFont(undefined, 'bold');
        pdf.text('Report Filters:', 20, yPos);
        yPos += 8;
        
        pdf.setFont(undefined, 'normal');
        pdf.setFontSize(10);
        if (siteId) {
            const siteName = document.querySelector(`#siteFilter option[value="${siteId}"]`).text;
            pdf.text(`Site: ${siteName}`, 25, yPos);
            yPos += 6;
        } else {
            pdf.text('Site: All Sites', 25, yPos);
            yPos += 6;
        }
        
        if (startDate || endDate) {
            const dateRange = `${startDate || 'Beginning'} to ${endDate || 'Present'}`;
            pdf.text(`Date Range: ${dateRange}`, 25, yPos);
            yPos += 6;
        } else {
            pdf.text('Date Range: All Time', 25, yPos);
            yPos += 6;
        }
        
        pdf.text(`Total Incidents: ${filteredIncidents.length}`, 25, yPos);
        
        // Executive Summary Box
        yPos += 15;
        pdf.setFillColor(245, 245, 245);
        pdf.roundedRect(20, yPos, pageWidth - 40, 60, 3, 3, 'F');
        
        pdf.setFontSize(14);
        pdf.setFont(undefined, 'bold');
        pdf.setTextColor(211, 47, 47);
        pdf.text('Executive Summary', 30, yPos + 10);
        
        pdf.setFontSize(10);
        pdf.setFont(undefined, 'normal');
        pdf.setTextColor(0);
        
        const stats = {
            total: filteredIncidents.length,
            pending: filteredIncidents.filter(i => (i.status || 'Pending') === 'Pending').length,
            inProgress: filteredIncidents.filter(i => i.status === 'In Progress').length,
            resolved: filteredIncidents.filter(i => i.status === 'Resolved' || i.status === 'Closed').length
        };
        
        const criticalCount = filteredIncidents.filter(i => 
            (i.severity || '').toLowerCase() === 'critical'
        ).length;
        
        yPos += 20;
        pdf.text(`This report covers ${stats.total} incident(s) with ${stats.pending} pending, `, 30, yPos);
        yPos += 5;
        pdf.text(`${stats.inProgress} in progress, and ${stats.resolved} resolved.`, 30, yPos);
        
        if (criticalCount > 0) {
            yPos += 7;
            pdf.setTextColor(211, 47, 47);
            pdf.setFont(undefined, 'bold');
            pdf.text(`⚠ ${criticalCount} CRITICAL incidents require immediate attention.`, 30, yPos);
            pdf.setFont(undefined, 'normal');
            pdf.setTextColor(0);
        }
        
        yPos += 10;
        const resolutionRate = stats.total > 0 ? ((stats.resolved / stats.total) * 100).toFixed(1) : 0;
        pdf.text(`Resolution Rate: ${resolutionRate}%`, 30, yPos);
        
        addPageNumber();
        
        // ===== PAGE 2: KEY STATISTICS =====
        pdf.addPage();
        yPos = 20;
        
        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 15, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'bold');
        pdf.text('Key Statistics', 20, 10);
        
        yPos = 30;
        pdf.setTextColor(0);
        
        // Statistics Grid
        const statBoxWidth = (pageWidth - 50) / 2;
        const statBoxHeight = 25;
        let xPos = 20;
        
        // Total Incidents
        pdf.setFillColor(211, 47, 47);
        pdf.roundedRect(xPos, yPos, statBoxWidth, statBoxHeight, 2, 2, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(10);
        pdf.text('TOTAL INCIDENTS', xPos + 5, yPos + 8);
        pdf.setFontSize(20);
        pdf.setFont(undefined, 'bold');
        pdf.text(String(stats.total), xPos + 5, yPos + 20);
        
        // Pending
        xPos += statBoxWidth + 10;
        pdf.setFillColor(255, 193, 7);
        pdf.roundedRect(xPos, yPos, statBoxWidth, statBoxHeight, 2, 2, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(10);
        pdf.setFont(undefined, 'normal');
        pdf.text('PENDING', xPos + 5, yPos + 8);
        pdf.setFontSize(20);
        pdf.setFont(undefined, 'bold');
        pdf.text(String(stats.pending), xPos + 5, yPos + 20);
        
        // In Progress
        yPos += statBoxHeight + 10;
        xPos = 20;
        pdf.setFillColor(33, 150, 243);
        pdf.roundedRect(xPos, yPos, statBoxWidth, statBoxHeight, 2, 2, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(10);
        pdf.setFont(undefined, 'normal');
        pdf.text('IN PROGRESS', xPos + 5, yPos + 8);
        pdf.setFontSize(20);
        pdf.setFont(undefined, 'bold');
        pdf.text(String(stats.inProgress), xPos + 5, yPos + 20);
        
        // Resolved
        xPos += statBoxWidth + 10;
        pdf.setFillColor(76, 175, 80);
        pdf.roundedRect(xPos, yPos, statBoxWidth, statBoxHeight, 2, 2, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(10);
        pdf.setFont(undefined, 'normal');
        pdf.text('RESOLVED', xPos + 5, yPos + 8);
        pdf.setFontSize(20);
        pdf.setFont(undefined, 'bold');
        pdf.text(String(stats.resolved), xPos + 5, yPos + 20);
        
        // Severity Breakdown
        yPos += 45;
        pdf.setTextColor(0);
        pdf.setFontSize(14);
        pdf.text('Severity Breakdown', 20, yPos);
        
        yPos += 10;
        const severityData = calculateSeverityData(filteredIncidents);
        const severityColors = {
            'Critical': [211, 47, 47],
            'High': [255, 111, 0],
            'Medium': [251, 192, 45],
            'Low': [56, 142, 60]
        };
        
        severityData.labels.forEach((severity, index) => {
            const count = severityData.data[index];
            const percentage = stats.total > 0 ? ((count / stats.total) * 100).toFixed(1) : 0;
            
            pdf.setFillColor(...(severityColors[severity] || [150, 150, 150]));
            pdf.circle(25, yPos + 3, 2, 'F');
            
            pdf.setFontSize(11);
            pdf.setTextColor(0);
            pdf.text(`${severity}: ${count} (${percentage}%)`, 32, yPos + 5);
            
            // Progress bar
            const barWidth = 80;
            const barHeight = 4;
            const fillWidth = (count / stats.total) * barWidth;
            
            pdf.setDrawColor(200);
            pdf.setLineWidth(0.5);
            pdf.rect(120, yPos + 1, barWidth, barHeight);
            pdf.setFillColor(...(severityColors[severity] || [150, 150, 150]));
            pdf.rect(120, yPos + 1, fillWidth, barHeight, 'F');
            
            yPos += 12;
        });
        
        addPageNumber();
        
        // ===== CHARTS PAGES =====
        const chartIds = ['statusChart', 'severityChart', 'monthlyTrendChart'];
        const chartTitles = ['Incidents by Status', 'Incidents by Severity', 'Monthly Incident Trend'];
        const chartDescriptions = [
            'Distribution of incidents across different status categories showing workflow progress.',
            'Breakdown of incidents by severity level indicating priority and urgency.',
            'Trend analysis of incidents over the last 12 months identifying patterns.'
        ];
        
        for (let i = 0; i < chartIds.length; i++) {
            pdf.addPage();
            yPos = 20;
            
            // Section header
            pdf.setFillColor(211, 47, 47);
            pdf.rect(0, 0, pageWidth, 15, 'F');
            pdf.setTextColor(255);
            pdf.setFontSize(16);
            pdf.setFont(undefined, 'bold');
            pdf.text(chartTitles[i], 20, 10);
            
            yPos = 30;
            pdf.setTextColor(100);
            pdf.setFontSize(9);
            pdf.setFont(undefined, 'normal');
            const descLines = pdf.splitTextToSize(chartDescriptions[i], pageWidth - 40);
            pdf.text(descLines, 20, yPos);
            
            yPos += descLines.length * 5 + 10;
            
            // Chart image
            const canvas = document.getElementById(chartIds[i]);
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = pageWidth - 40;
            const imgHeight = 110;
            
            pdf.addImage(imgData, 'PNG', 20, yPos, imgWidth, imgHeight);
            
            yPos += imgHeight + 15;
            
            // Chart insights
            pdf.setFillColor(245, 245, 245);
            pdf.roundedRect(20, yPos, pageWidth - 40, 30, 2, 2, 'F');
            
            pdf.setFontSize(11);
            pdf.setFont(undefined, 'bold');
            pdf.setTextColor(211, 47, 47);
            pdf.text('Key Insights:', 25, yPos + 8);
            
            pdf.setFontSize(9);
            pdf.setFont(undefined, 'normal');
            pdf.setTextColor(0);
            
            let insights = '';
            if (i === 0) { // Status chart
                const dominant = severityData.labels[0] || 'N/A';
                insights = `Most incidents are in "${dominant}" status. Focus on moving pending items to resolution.`;
            } else if (i === 1) { // Severity chart
                const highPriority = criticalCount + (filteredIncidents.filter(i => 
                    (i.severity || '').toLowerCase() === 'high'
                ).length);
                insights = `${highPriority} high-priority incidents detected. Immediate action recommended for critical cases.`;
            } else { // Monthly trend
                const recentMonth = filteredIncidents.filter(i => {
                    const date = new Date(i.created_at);
                    const now = new Date();
                    return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
                }).length;
                insights = `Current month shows ${recentMonth} incident(s). Monitor trends for resource allocation.`;
            }
            
            const insightLines = pdf.splitTextToSize(insights, pageWidth - 50);
            pdf.text(insightLines, 25, yPos + 18);
            
            addPageNumber();
        }
        
        // ===== INCIDENTS TABLE =====
        pdf.addPage();
        yPos = 20;
        
        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 15, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'bold');
        pdf.text('Incident Details', 20, 10);
        
        yPos = 30;
        pdf.setTextColor(0);
        pdf.setFontSize(10);
        
        // Table header
        pdf.setFillColor(240, 240, 240);
        pdf.rect(20, yPos, pageWidth - 40, 8, 'F');
        pdf.setFont(undefined, 'bold');
        pdf.text('ID', 22, yPos + 6);
        pdf.text('Type', 35, yPos + 6);
        pdf.text('Location', 75, yPos + 6);
        pdf.text('Severity', 120, yPos + 6);
        pdf.text('Status', 145, yPos + 6);
        pdf.text('Date', 170, yPos + 6);
        
        yPos += 10;
        pdf.setFont(undefined, 'normal');
        pdf.setFontSize(8);
        
        const incidentsToShow = filteredIncidents.slice(0, 30);
        
        incidentsToShow.forEach((incident, index) => {
            if (yPos > 270) {
                addPageNumber();
                pdf.addPage();
                yPos = 30;
                
                // Repeat header
                pdf.setFillColor(240, 240, 240);
                pdf.rect(20, yPos, pageWidth - 40, 8, 'F');
                pdf.setFont(undefined, 'bold');
                pdf.setFontSize(10);
                pdf.text('ID', 22, yPos + 6);
                pdf.text('Type', 35, yPos + 6);
                pdf.text('Location', 75, yPos + 6);
                pdf.text('Severity', 120, yPos + 6);
                pdf.text('Status', 145, yPos + 6);
                pdf.text('Date', 170, yPos + 6);
                yPos += 10;
                pdf.setFont(undefined, 'normal');
                pdf.setFontSize(8);
            }
            
            // Alternate row colors
            if (index % 2 === 0) {
                pdf.setFillColor(250, 250, 250);
                pdf.rect(20, yPos - 3, pageWidth - 40, 7, 'F');
            }
            
            pdf.setTextColor(0);
            pdf.text(`#${incident.id}`, 22, yPos + 3);
            
            const incidentType = (incident.incident_type || 'N/A').substring(0, 18);
            pdf.text(incidentType, 35, yPos + 3);
            
            const location = (incident.site_name || 'Unknown').substring(0, 20);
            pdf.text(location, 75, yPos + 3);
            
            // Color code severity
            const severity = incident.severity || 'Medium';
            const sevColors = {
                'Critical': [211, 47, 47],
                'High': [255, 111, 0],
                'Medium': [251, 192, 45],
                'Low': [56, 142, 60]
            };
            const sevColor = sevColors[severity.charAt(0).toUpperCase() + severity.slice(1).toLowerCase()] || [100, 100, 100];
            pdf.setTextColor(...sevColor);
            pdf.text(severity, 120, yPos + 3);
            
            // Status
            pdf.setTextColor(0);
            const status = incident.status || 'Pending';
            pdf.text(status, 145, yPos + 3);
            
            const date = new Date(incident.created_at);
            pdf.text(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }), 170, yPos + 3);
            
            yPos += 7;
        });
        
        if (filteredIncidents.length > 30) {
            yPos += 5;
            pdf.setTextColor(100);
            pdf.setFontSize(9);
            pdf.text(`... and ${filteredIncidents.length - 30} more incident(s). Full details available in the system.`, 20, yPos);
        }
        
        addPageNumber();
        
        // ===== FINAL PAGE: RECOMMENDATIONS =====
        pdf.addPage();
        yPos = 20;
        
        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 15, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'bold');
        pdf.text('Recommendations & Action Items', 20, 10);
        
        yPos = 35;
        pdf.setTextColor(0);
        pdf.setFontSize(10);
        pdf.setFont(undefined, 'normal');
        
        const recommendations = [];
        
        if (stats.pending > stats.total * 0.4) {
            recommendations.push('High volume of pending incidents detected. Consider increasing resource allocation for faster response.');
        }
        
        if (criticalCount > 0) {
            recommendations.push(`${criticalCount} critical incident(s) require immediate escalation and resolution.`);
        }
        
        if (stats.resolved / stats.total < 0.5) {
            recommendations.push('Resolution rate is below 50%. Review incident management processes for improvement opportunities.');
        }
        
        const recentIncidents = filteredIncidents.filter(i => {
            const date = new Date(i.created_at);
            const weekAgo = new Date();
            weekAgo.setDate(weekAgo.getDate() - 7);
            return date >= weekAgo;
        }).length;
        
        if (recentIncidents > 10) {
            recommendations.push(`${recentIncidents} incidents reported in the last 7 days. Monitor for emerging patterns or recurring issues.`);
        }
        
        if (recommendations.length === 0) {
            recommendations.push('Incident management performance is within acceptable parameters. Continue monitoring trends.');
        }
        
        recommendations.forEach((rec, index) => {
            pdf.setFillColor(245, 245, 245);
            const recHeight = pdf.splitTextToSize(rec, pageWidth - 60).length * 5 + 10;
            pdf.roundedRect(20, yPos, pageWidth - 40, recHeight, 2, 2, 'F');
            
            pdf.setFillColor(211, 47, 47);
            pdf.circle(28, yPos + 7, 3, 'F');
            
            pdf.setTextColor(0);
            const recLines = pdf.splitTextToSize(rec, pageWidth - 60);
            pdf.text(recLines, 38, yPos + 9);
            
            yPos += recHeight + 8;
        });
        
        // Footer note
        yPos += 15;
        pdf.setFillColor(240, 240, 240);
        pdf.rect(20, yPos, pageWidth - 40, 25, 'F');
        
        pdf.setFontSize(9);
        pdf.setTextColor(100);
        pdf.setFont(undefined, 'italic');
        const footerText = 'This report was automatically generated by RED FORCE Incident Management System. For questions or additional analysis, please contact your system administrator.';
        const footerLines = pdf.splitTextToSize(footerText, pageWidth - 50);
        pdf.text(footerLines, 25, yPos + 8);
        
        addPageNumber();
        
        // Save PDF with timestamp
        const filename = `incident-report-${siteId ? 'filtered-' : ''}${new Date().toISOString().split('T')[0]}.pdf`;
        pdf.save(filename);
        
    } catch (error) {
        console.error('PDF generation error:', error);
        alert('Failed to generate PDF. Please try again.');
    } finally {
        button.disabled = false;
        button.innerHTML = '<span class="material-symbols-outlined">picture_as_pdf</span> Download PDF Report';
    }
});

// Filter Functionality
const allIncidents = <?php echo json_encode($data['incidents'] ?? []); ?>;
let filteredIncidents = allIncidents;

// Apply filter
document.getElementById('applyFilter').addEventListener('click', function() {
    const siteId = document.getElementById('siteFilter').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Filter incidents
    filteredIncidents = allIncidents.filter(incident => {
        let matches = true;
        
        // Filter by site
        if (siteId && incident.site_id != siteId) {
            matches = false;
        }
        
        // Filter by date range
        if (startDate && new Date(incident.created_at) < new Date(startDate)) {
            matches = false;
        }
        if (endDate && new Date(incident.created_at) > new Date(endDate)) {
            matches = false;
        }
        
        return matches;
    });
    
    // Update stats
    updateStats(filteredIncidents);
    
    // Update charts
    updateCharts(filteredIncidents);
    
    // Update table
    updateIncidentTable(filteredIncidents);
    
    // Show filter applied message
    const siteName = siteId ? document.querySelector(`#siteFilter option[value="${siteId}"]`).text : 'All Sites';
    console.log(`Filter applied: ${siteName}, ${filteredIncidents.length} incidents found`);
});

// Reset filter
document.getElementById('resetFilter').addEventListener('click', function() {
    document.getElementById('siteFilter').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    
    filteredIncidents = allIncidents;
    updateStats(allIncidents);
    updateCharts(allIncidents);
    updateIncidentTable(allIncidents);
});

// Update statistics
function updateStats(incidents) {
    const total = incidents.length;
    const pending = incidents.filter(i => (i.status || 'Pending') === 'Pending').length;
    const inProgress = incidents.filter(i => i.status === 'In Progress').length;
    const resolved = incidents.filter(i => i.status === 'Resolved' || i.status === 'Closed').length;
    
    document.querySelector('.stat-card:nth-child(1) .value').textContent = total;
    document.querySelector('.stat-card:nth-child(2) .value').textContent = pending;
    document.querySelector('.stat-card:nth-child(3) .value').textContent = inProgress;
    document.querySelector('.stat-card:nth-child(4) .value').textContent = resolved;
}

// Update charts with filtered data
function updateCharts(incidents) {
    // Calculate status distribution
    const statusData = calculateStatusData(incidents);
    statusChart.data.labels = statusData.labels;
    statusChart.data.datasets[0].data = statusData.data;
    statusChart.update();
    
    // Calculate severity distribution
    const severityData = calculateSeverityData(incidents);
    severityChart.data.labels = severityData.labels;
    severityChart.data.datasets[0].data = severityData.data;
    severityChart.update();
    
    // Calculate monthly trend
    const monthlyData = calculateMonthlyTrend(incidents);
    monthlyTrendChart.data.labels = monthlyData.labels;
    monthlyTrendChart.data.datasets[0].data = monthlyData.data;
    monthlyTrendChart.update();
}

// Calculate status distribution from incidents
function calculateStatusData(incidents) {
    const statusCount = {};
    incidents.forEach(incident => {
        const status = incident.status || 'Pending';
        statusCount[status] = (statusCount[status] || 0) + 1;
    });
    
    return {
        labels: Object.keys(statusCount),
        data: Object.values(statusCount)
    };
}

// Calculate severity distribution from incidents
function calculateSeverityData(incidents) {
    const severityOrder = ['Critical', 'High', 'Medium', 'Low'];
    const severityCount = {};
    
    incidents.forEach(incident => {
        const severity = incident.severity || 'Medium';
        const capitalizedSeverity = severity.charAt(0).toUpperCase() + severity.slice(1).toLowerCase();
        severityCount[capitalizedSeverity] = (severityCount[capitalizedSeverity] || 0) + 1;
    });
    
    // Sort by severity order
    const labels = [];
    const data = [];
    severityOrder.forEach(severity => {
        if (severityCount[severity]) {
            labels.push(severity);
            data.push(severityCount[severity]);
        }
    });
    
    return { labels, data };
}

// Calculate monthly trend from incidents
function calculateMonthlyTrend(incidents) {
    const monthlyCount = {};
    const now = new Date();
    
    // Initialize last 12 months
    for (let i = 11; i >= 0; i--) {
        const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
        const monthKey = date.toISOString().slice(0, 7); // YYYY-MM format
        monthlyCount[monthKey] = 0;
    }
    
    // Count incidents per month
    incidents.forEach(incident => {
        const date = new Date(incident.created_at);
        const monthKey = date.toISOString().slice(0, 7);
        if (monthlyCount.hasOwnProperty(monthKey)) {
            monthlyCount[monthKey]++;
        }
    });
    
    // Format labels and data
    const labels = [];
    const data = [];
    Object.keys(monthlyCount).sort().forEach(monthKey => {
        const date = new Date(monthKey + '-01');
        const label = date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
        labels.push(label);
        data.push(monthlyCount[monthKey]);
    });
    
    return { labels, data };
}

// Update incident table
function updateIncidentTable(incidents) {
    const tbody = document.querySelector('.incident-table tbody');
    
    if (!incidents || incidents.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #999;">No incidents found</td></tr>';
        return;
    }
    
    tbody.innerHTML = incidents.map(incident => {
        const severityClass = (incident.severity || 'medium').toLowerCase();
        const statusClass = (incident.status || 'pending').toLowerCase().replace(' ', '-');
        const date = new Date(incident.created_at);
        const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        
        return `
            <tr>
                <td>#${incident.id}</td>
                <td>${incident.incident_type || 'N/A'}</td>
                <td>${incident.site_name || 'Unknown'}</td>
                <td><span class="badge ${severityClass}">${incident.severity || 'Medium'}</span></td>
                <td><span class="badge ${statusClass}">${incident.status || 'Pending'}</span></td>
                <td>${incident.officer_name || 'N/A'}</td>
                <td>${formattedDate}</td>
            </tr>
        `;
    }).join('');
}
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
