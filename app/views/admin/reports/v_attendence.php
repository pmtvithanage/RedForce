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
        gap: 20px;
        margin-bottom: 30px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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

    .stat-card.present .value { color: #4CAF50; }
    .stat-card.absent .value { color: #616161; }

    .filter-section,
    .chart-card,
    .recent-attendance {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .filter-section {
        margin-bottom: 30px;
    }

    .filter-section h3,
    .chart-card h2,
    .recent-attendance h2 {
        font-size: 18px;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #D32F2F;
        padding-bottom: 10px;
    }

    .filter-controls {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
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
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #D32F2F;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-btn,
    .pdf-button {
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .filter-btn {
        padding: 10px 18px;
    }

    .filter-btn.apply,
    .pdf-button {
        background: #D32F2F;
        color: white;
    }

    .filter-btn.apply:hover,
    .pdf-button:hover {
        background: #B71C1C;
    }

    .filter-btn.reset {
        background: #f0f0f0;
        color: #555;
    }

    .filter-btn.reset:hover {
        background: #e0e0e0;
    }

    .pdf-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    .chart-container.tall {
        height: 400px;
    }

    .chart-controls {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .chart-checkbox {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        font-size: 13px;
        user-select: none;
    }

    .chart-checkbox input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #D32F2F;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .color-indicator {
        width: 20px;
        height: 3px;
        border-radius: 2px;
    }

    .color-indicator.total { background: #D32F2F; height: 4px; }
    .color-indicator.present { background: #4CAF50; }
    .color-indicator.absent { background: #616161; }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .attendance-table th {
        background: #f5f5f5;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #ddd;
    }

    .attendance-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        vertical-align: top;
    }

    .attendance-table tr:hover {
        background: #fafafa;
    }

    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge.present { background: #E8F5E9; color: #2E7D32; }
    .badge.absent { background: #F5F5F5; color: #424242; }

    .muted {
        color: #888;
    }

    @media (max-width: 900px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="analytics-container">
    <button class="tertiary-btn" style="display:flex; width:100px; margin-bottom:20px; align-items:center;" onclick="history.back()">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>

    <div class="page-header">
        <div>
            <h1><?php echo $data['pageTitle']; ?></h1>
            <p style="color:#666; margin-top:5px;">Attendance analytics, trend tracking, and downloadable reporting</p>
        </div>
        <button class="pdf-button" id="downloadPdf">
            <span class="material-symbols-outlined">picture_as_pdf</span>
            Download PDF Report
        </button>
    </div>

    <div class="filter-section">
        <h3>Filter Reports</h3>
        <div class="filter-controls">
            <div class="filter-group">
                <label>Site</label>
                <select id="siteFilter">
                    <option value="">All Sites</option>
                    <?php foreach ($data['sites'] ?? [] as $site): ?>
                        <option value="<?php echo $site->id; ?>"><?php echo htmlspecialchars($site->site_name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Supervisor</label>
                <select id="supervisorFilter">
                    <option value="">All Supervisors</option>
                    <?php foreach ($data['supervisors'] ?? [] as $supervisor): ?>
                        <option value="<?php echo $supervisor->id; ?>"><?php echo htmlspecialchars($supervisor->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Officer</label>
                <input type="text" id="officerFilter" placeholder="Officer ID or name">
            </div>

 

            <div class="filter-group">
                <label>From</label>
                <input type="date" id="startDate" value="<?php echo date('Y-m-d', strtotime('-89 days')); ?>">
            </div>

            <div class="filter-group">
                <label>To</label>
                <input type="date" id="endDate" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="filter-buttons">
                <button class="filter-btn apply" id="applyFilter" type="button">Apply Filter</button>
                <button class="filter-btn reset" id="resetFilter" type="button">Reset</button>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Records</h3>
            <div class="value" id="statTotal">0</div>
        </div>
        <div class="stat-card present">
            <h3>Present</h3>
            <div class="value" id="statPresent">0</div>
        </div>
        <div class="stat-card absent">
            <h3>Absent</h3>
            <div class="value" id="statAbsent">0</div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card">
            <h2>Attendance by Status</h2>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Removed trend chart -->
    </div>

    <div class="recent-attendance">
        <h2>Attendance Records</h2>
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Officer ID</th>
                    <th>Officer Name</th>
                    <th>Supervisor</th>
                    <th>Site</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody id="attendanceTableBody">
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:#999;">Loading attendance records...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
const allAttendanceRecords = <?php echo json_encode($data['attendanceRecords'] ?? []); ?>;
let filteredAttendanceRecords = [];
let statusChart;

Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#666';

function normalizeStatus(status) {
    return status || 'Present';
}

function parseLocalDate(dateString) {
    if (!dateString) {
        return null;
    }

    const [year, month, day] = dateString.split('-').map(Number);
    return new Date(year, month - 1, day);
}

function formatDate(dateString) {
    if (!dateString) {
        return 'N/A';
    }

    const date = parseLocalDate(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function formatTime(timeString) {
    if (!timeString) {
        return '<span class="muted">-</span>';
    }

    const [hours, minutes] = timeString.split(':');
    const date = new Date();
    date.setHours(Number(hours), Number(minutes), 0, 0);
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}

function getStatusCounts(records) {
    return {
        total: records.length,
        present: records.filter(record => normalizeStatus(record.status) === 'Present').length,
        absent: records.filter(record => normalizeStatus(record.status) === 'Absent').length
    };
}

function buildStatusChart(records) {
    const counts = getStatusCounts(records);
    const chartValues = [
        counts.present,
        counts.absent
    ];

    if (!statusChart) {
        statusChart = new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: chartValues,
                    backgroundColor: ['#4CAF50', '#616161'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15
                        }
                    }
                }
            }
        });
        return;
    }

    statusChart.data.datasets[0].data = chartValues;
    statusChart.update();
}

 

function updateStats(records) {
    const counts = getStatusCounts(records);
    document.getElementById('statTotal').textContent = counts.total;
    document.getElementById('statPresent').textContent = counts.present;
    document.getElementById('statAbsent').textContent = counts.absent;
}

function updateTable(records) {
    const tbody = document.getElementById('attendanceTableBody');

    if (!records.length) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:40px; color:#999;">No attendance records found</td></tr>';
        return;
    }

    tbody.innerHTML = records.map(record => {
        const status = normalizeStatus(record.status);
        const badgeClass = status.toLowerCase().replace(/\s+/g, '-');
        const notes = record.notes ? String(record.notes) : '';
        const shortNotes = notes.length > 50 ? `${notes.slice(0, 50)}...` : notes || '<span class="muted">-</span>';
        const siteName = record.site_name || 'Unassigned';

        return `
            <tr>
                <td>${formatDate(record.attendance_date)}</td>
                <td>${record.officer_id || 'N/A'}</td>
                <td>${record.officer_name || 'Unknown'}</td>
                <td>${record.supervisor_name || 'Unknown'}</td>
                <td>${siteName}</td>
                <td><span class="badge ${badgeClass}">${status}</span></td>
                <td>${shortNotes}</td>
            </tr>
        `;
    }).join('');
}

function applyFilters() {
    const siteId = document.getElementById('siteFilter').value;
    const supervisorId = document.getElementById('supervisorFilter').value;
    const officerSearch = document.getElementById('officerFilter').value.trim().toLowerCase();
 
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    filteredAttendanceRecords = allAttendanceRecords.filter(record => {
        const recordDate = parseLocalDate(record.attendance_date);
        const start = startDate ? parseLocalDate(startDate) : null;
        const end = endDate ? parseLocalDate(endDate) : null;
        const siteNames = String(record.site_name || '').toLowerCase();
        const siteIds = String(record.site_ids || '').split(',').filter(Boolean);
        const officerId = String(record.officer_id || '').toLowerCase();
        const officerName = String(record.officer_name || '').toLowerCase();

        if (siteId && !siteIds.includes(String(siteId)) && String(record.site_id || '') !== String(siteId)) {
            return false;
        }

        if (supervisorId && String(record.supervisor_id || '') !== String(supervisorId)) {
            return false;
        }

 

        if (officerSearch && !officerId.includes(officerSearch) && !officerName.includes(officerSearch)) {
            return false;
        }

        if (start && recordDate < start) {
            return false;
        }

        if (end && recordDate > end) {
            return false;
        }

        return true;
    });

    updateStats(filteredAttendanceRecords);
    buildStatusChart(filteredAttendanceRecords);
    updateTable(filteredAttendanceRecords);
}

function resetFilters() {
    const today = new Date();
    const ninetyDaysAgo = new Date();
    ninetyDaysAgo.setDate(today.getDate() - 89);

    document.getElementById('siteFilter').value = '';
    document.getElementById('supervisorFilter').value = '';
    document.getElementById('officerFilter').value = '';
 
    document.getElementById('endDate').value = today.toISOString().split('T')[0];
    document.getElementById('startDate').value = ninetyDaysAgo.toISOString().split('T')[0];

    applyFilters();
}

 

async function downloadPdfReport() {
    const button = document.getElementById('downloadPdf');
    button.disabled = true;
    button.innerHTML = '<span class="material-symbols-outlined">hourglass_empty</span> Generating PDF...';

    try {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        let currentPage = 1;

        function addPageNumber() {
            pdf.setFontSize(9);
            pdf.setTextColor(150);
            pdf.text(`Page ${currentPage}`, pageWidth - 30, pageHeight - 10);
            pdf.text('RED FORCE Security Services', 20, pageHeight - 10);
            currentPage += 1;
        }

        const counts = getStatusCounts(filteredAttendanceRecords);
        const attendanceRate = counts.total > 0 ? ((counts.present / counts.total) * 100).toFixed(1) : '0.0';

        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 80, 'F');
        pdf.setTextColor(255, 255, 255);
        pdf.setFontSize(28);
        pdf.setFont(undefined, 'bold');
        pdf.text('ATTENDANCE REPORT', pageWidth / 2, 35, { align: 'center' });
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'normal');
        pdf.text('Analytics & Summary', pageWidth / 2, 50, { align: 'center' });
        pdf.setFontSize(11);
        pdf.text(`Generated: ${new Date().toLocaleString()}`, pageWidth / 2, 65, { align: 'center' });

        let yPos = 100;
        pdf.setTextColor(0);
        pdf.setFontSize(12);
        pdf.setFont(undefined, 'bold');
        pdf.text('Report Filters:', 20, yPos);
        yPos += 8;
        pdf.setFont(undefined, 'normal');
        pdf.setFontSize(10);

        const siteText = document.querySelector('#siteFilter option:checked').textContent;
        const supervisorText = document.querySelector('#supervisorFilter option:checked').textContent;

        pdf.text(`Site: ${siteText}`, 25, yPos); yPos += 6;
        pdf.text(`Supervisor: ${supervisorText}`, 25, yPos); yPos += 6;
        pdf.text(`Officer Search: ${document.getElementById('officerFilter').value || 'All Officers'}`, 25, yPos); yPos += 6;
        pdf.text(`Date Range: ${document.getElementById('startDate').value || 'Beginning'} to ${document.getElementById('endDate').value || 'Present'}`, 25, yPos); yPos += 6;
        pdf.text(`Total Records: ${counts.total}`, 25, yPos);

        yPos += 15;
        pdf.setFillColor(245, 245, 245);
        pdf.roundedRect(20, yPos, pageWidth - 40, 60, 3, 3, 'F');
        pdf.setTextColor(211, 47, 47);
        pdf.setFontSize(14);
        pdf.setFont(undefined, 'bold');
        pdf.text('Executive Summary', 30, yPos + 10);
        pdf.setTextColor(0);
        pdf.setFontSize(10);
        pdf.setFont(undefined, 'normal');
        pdf.text(`This report covers ${counts.total} attendance record(s).`, 30, yPos + 22);
        pdf.text(`Present: ${counts.present}, Absent: ${counts.absent}.`, 30, yPos + 30);
        pdf.text(`Attendance Rate: ${attendanceRate}%`, 30, yPos + 38);
        addPageNumber();

        pdf.addPage();
        yPos = 20;
        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 15, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'bold');
        pdf.text('Key Statistics', 20, 10);

        yPos = 30;
        const statBoxWidth = (pageWidth - 55) / 2;
        const statBoxHeight = 25;
        const statCards = [
            ['TOTAL', counts.total, [211, 47, 47]],
            ['PRESENT', counts.present, [76, 175, 80]],
            ['ABSENT', counts.absent, [97, 97, 97]]
        ];

        statCards.forEach((card, index) => {
            const row = Math.floor(index / 2);
            const col = index % 2;
            const xPos = 20 + ((statBoxWidth + 10) * col);
            const yCard = yPos + (row * (statBoxHeight + 10));
            pdf.setFillColor(...card[2]);
            pdf.roundedRect(xPos, yCard, statBoxWidth, statBoxHeight, 2, 2, 'F');
            pdf.setTextColor(255);
            pdf.setFontSize(10);
            pdf.setFont(undefined, 'normal');
            pdf.text(card[0], xPos + 5, yCard + 8);
            pdf.setFontSize(20);
            pdf.setFont(undefined, 'bold');
            pdf.text(String(card[1]), xPos + 5, yCard + 20);
        });
        addPageNumber();

        const chartConfigs = [
            ['statusChart', 'Attendance by Status']
        ];

        chartConfigs.forEach(([canvasId, title]) => {
            pdf.addPage();
            pdf.setFillColor(211, 47, 47);
            pdf.rect(0, 0, pageWidth, 15, 'F');
            pdf.setTextColor(255);
            pdf.setFontSize(16);
            pdf.setFont(undefined, 'bold');
            pdf.text(title, 20, 10);
            const canvas = document.getElementById(canvasId);
            const imgData = canvas.toDataURL('image/png');
            pdf.addImage(imgData, 'PNG', 20, 35, pageWidth - 40, 110);
            addPageNumber();
        });

        pdf.addPage();
        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 15, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'bold');
        pdf.text('Attendance Details', 20, 10);

        yPos = 30;
        pdf.setTextColor(0);
        pdf.setFontSize(8);
        pdf.setFont(undefined, 'bold');
        pdf.text('Date', 20, yPos);
        pdf.text('Officer', 42, yPos);
        pdf.text('Supervisor', 85, yPos);
        pdf.text('Site', 125, yPos);
        pdf.text('Status', 165, yPos);
        yPos += 6;

        pdf.setFont(undefined, 'normal');
        filteredAttendanceRecords.slice(0, 30).forEach(record => {
            if (yPos > 275) {
                addPageNumber();
                pdf.addPage();
                yPos = 20;
            }

            pdf.text(formatDate(record.attendance_date), 20, yPos);
            pdf.text(String(record.officer_name || 'Unknown').slice(0, 22), 42, yPos);
            pdf.text(String(record.supervisor_name || 'Unknown').slice(0, 20), 85, yPos);
            pdf.text(String(record.site_name || 'Unassigned').slice(0, 22), 125, yPos);
            pdf.text(normalizeStatus(record.status), 165, yPos);
            yPos += 6;
        });

        if (filteredAttendanceRecords.length > 30) {
            yPos += 6;
            pdf.setTextColor(100);
            pdf.text(`... and ${filteredAttendanceRecords.length - 30} more attendance record(s) in the system.`, 20, yPos);
        }
        addPageNumber();

        pdf.addPage();
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
        if (counts.absent > counts.total * 0.2) {
            recommendations.push('Absence volume is elevated. Review staffing gaps and supervisor follow-up for affected officers.');
        }
        if (counts.present / Math.max(counts.total, 1) < 0.7) {
            recommendations.push('Present attendance rate is below 70%. Investigate operational causes and site-specific shortages.');
        }
        if (!recommendations.length) {
            recommendations.push('Attendance performance is within acceptable limits for the selected period. Continue monitoring for deviations.');
        }

        recommendations.forEach(rec => {
            const lines = pdf.splitTextToSize(rec, pageWidth - 60);
            const boxHeight = (lines.length * 5) + 10;
            pdf.setFillColor(245, 245, 245);
            pdf.roundedRect(20, yPos, pageWidth - 40, boxHeight, 2, 2, 'F');
            pdf.setFillColor(211, 47, 47);
            pdf.circle(28, yPos + 7, 3, 'F');
            pdf.setTextColor(0);
            pdf.text(lines, 38, yPos + 9);
            yPos += boxHeight + 8;
        });
        addPageNumber();

        pdf.save(`attendance-report-${new Date().toISOString().split('T')[0]}.pdf`);
    } catch (error) {
        console.error('PDF generation error:', error);
        alert('Failed to generate PDF. Please try again.');
    } finally {
        button.disabled = false;
        button.innerHTML = '<span class="material-symbols-outlined">picture_as_pdf</span> Download PDF Report';
    }
}

document.getElementById('applyFilter').addEventListener('click', applyFilters);
document.getElementById('resetFilter').addEventListener('click', resetFilters);
document.getElementById('downloadPdf').addEventListener('click', downloadPdfReport);

 

window.addEventListener('DOMContentLoaded', applyFilters);
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>