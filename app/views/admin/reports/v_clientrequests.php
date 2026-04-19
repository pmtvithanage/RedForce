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

    .stat-card.approved .value { color: #4CAF50; }
    .stat-card.pending .value { color: #FF9800; }
    .stat-card.rejected .value { color: #F44336; }

    .filter-section,
    .chart-card,
    .recent-records {
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
    .recent-records h2 {
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
    .color-indicator.pending { background: #FF9800; }
    .color-indicator.approved { background: #4CAF50; }
    .color-indicator.rejected { background: #F44336; }

    .records-table {
        width: 100%;
        border-collapse: collapse;
    }

    .records-table th {
        background: #f5f5f5;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #ddd;
    }

    .records-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .records-table tr:hover {
        background: #fafafa;
    }

    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        text-transform: capitalize;
    }

    .badge.pending { background: #FFF3E0; color: #E65100; }
    .badge.approved { background: #E8F5E9; color: #2E7D32; }
    .badge.rejected { background: #FFEBEE; color: #C62828; }

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
    <button class="tertiary-btn" style="display:flex; width:100px; margin-bottom:20px; align-items:center; background:none; border:none; cursor:pointer;" onclick="location.href='<?php echo URL_ROOT; ?>/admin/reports'">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>

    <div class="page-header">
        <div>
            <h1><?php echo $data['pageTitle']; ?></h1>
            <p style="color:#666; margin-top:5px;">Client requests analytics, trend tracking, and downloadable reporting</p>
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
                <label>Company Search</label>
                <input type="text" id="companySearchFilter" placeholder="Name or email">
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
            <h3>Total Requests</h3>
            <div class="value" id="statTotal">0</div>
        </div>
        <div class="stat-card pending">
            <h3>Pending</h3>
            <div class="value" id="statPending">0</div>
        </div>
        <div class="stat-card approved">
            <h3>Approved</h3>
            <div class="value" id="statApproved">0</div>
        </div>
        <div class="stat-card rejected">
            <h3>Rejected</h3>
            <div class="value" id="statRejected">0</div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card">
            <h2>Requests by Status</h2>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="chart-card" style="grid-column: span 2;">
            <h2 id="trendChartTitle">Daily Requests Trend (Last 90 Days)</h2>
            <div class="chart-controls">
 
                <label class="chart-checkbox">
                    <input type="checkbox" id="togglePending" checked>
                    <span class="checkbox-label">
                        <span class="color-indicator pending"></span>
                        <span>Pending</span>
                    </span>
                </label>
                <label class="chart-checkbox">
                    <input type="checkbox" id="toggleApproved" checked>
                    <span class="checkbox-label">
                        <span class="color-indicator approved"></span>
                        <span>Approved</span>
                    </span>
                </label>
                <label class="chart-checkbox">
                    <input type="checkbox" id="toggleRejected" checked>
                    <span class="checkbox-label">
                        <span class="color-indicator rejected"></span>
                        <span>Rejected</span>
                    </span>
                </label>
            </div>
            <div class="chart-container tall">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="recent-records">
        <h2>Client Requests Records</h2>
        <div style="overflow-x: auto;">
            <table class="records-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="recordsTableBody">
                    <tr>
                        <td colspan="6" style="text-align:center; padding:40px; color:#999;">Loading records...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
const allRecords = <?php echo json_encode($data['requestsRecords'] ?? []); ?>;
let filteredRecords = [];
let statusChart;
let trendChart;

Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#666';

function normalizeStatus(status) {
    return (status || 'pending').toLowerCase();
}

function parseLocalDate(dateString) {
    if (!dateString) return null;
    const datePart = dateString.split(' ')[0];
    const [year, month, day] = datePart.split('-').map(Number);
    return new Date(year, month - 1, day);
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = parseLocalDate(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function getStatusCounts(records) {
    return {
        total: records.length,
        pending: records.filter(record => normalizeStatus(record.status) === 'pending').length,
        approved: records.filter(record => normalizeStatus(record.status) === 'approved').length,
        rejected: records.filter(record => normalizeStatus(record.status) === 'rejected').length
    };
}

function buildStatusChart(records) {
    const counts = getStatusCounts(records);
    const chartValues = [
        counts.pending,
        counts.approved,
        counts.rejected
    ];

    if (!statusChart) {
        statusChart = new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: chartValues,
                    backgroundColor: ['#FF9800', '#4CAF50', '#F44336'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 15 } }
                }
            }
        });
        return;
    }

    statusChart.data.datasets[0].data = chartValues;
    statusChart.update();
}

function calculateTrendData(records) {
    const startDateInput = document.getElementById('startDate').value;
    const endDateInput = document.getElementById('endDate').value;
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let startDate = startDateInput ? parseLocalDate(startDateInput) : new Date(today);
    let endDate = endDateInput ? parseLocalDate(endDateInput) : new Date(today);

    if (!startDateInput && !endDateInput) {
        startDate.setDate(startDate.getDate() - 89);
    }

    startDate.setHours(0, 0, 0, 0);
    endDate.setHours(0, 0, 0, 0);

    const dailyMap = {};
    const currentDate = new Date(startDate);

    while (currentDate <= endDate) {
        const key = currentDate.toISOString().slice(0, 10);
        dailyMap[key] = { pending: 0, approved: 0, rejected: 0 };
        currentDate.setDate(currentDate.getDate() + 1);
    }

    records.forEach(record => {
        if (!record.created_at) return;
        const key = record.created_at.split(' ')[0];
        if (!dailyMap[key]) return;

        const status = normalizeStatus(record.status);

        if (status === 'pending') dailyMap[key].pending += 1;
        if (status === 'approved') dailyMap[key].approved += 1;
        if (status === 'rejected') dailyMap[key].rejected += 1;
    });

    const keys = Object.keys(dailyMap).sort();
    const labels = [];
    const pending = [];
    const approved = [];
    const rejected = [];
    const showFullDates = keys.length <= 30;

    keys.forEach((key, index) => {
        const date = parseLocalDate(key);
        const skipInterval = keys.length > 180 ? 7 : 3;
        const label = showFullDates || index % skipInterval === 0 || index === keys.length - 1
            ? date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
            : '';

        labels.push(label);
        pending.push(dailyMap[key].pending);
        approved.push(dailyMap[key].approved);
        rejected.push(dailyMap[key].rejected);
    });

    return { labels, pending, approved, rejected };
}

function buildTrendChart(records) {
    const trendData = calculateTrendData(records);

    if (!trendChart) {
        trendChart = new Chart(document.getElementById('trendChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: trendData.labels,
                datasets: [
                    {
                        label: 'Pending',
                        data: trendData.pending,
                        borderColor: '#FF9800',
                        backgroundColor: 'rgba(255, 152, 0, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.3
                    },
                    {
                        label: 'Approved',
                        data: trendData.approved,
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.3
                    },
                    {
                        label: 'Rejected',
                        data: trendData.rejected,
                        borderColor: '#F44336',
                        backgroundColor: 'rgba(244, 67, 54, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: true, position: 'top', labels: { padding: 15, usePointStyle: true } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, title: { display: true, text: 'Requests' } },
                    x: { ticks: { maxRotation: 45, minRotation: 45, autoSkip: true, maxTicksLimit: 15 } }
                }
            }
        });
    } else {
        const hiddenStates = trendChart.data.datasets.map(dataset => dataset.hidden || false);
        trendChart.data.labels = trendData.labels;
        trendChart.data.datasets[0].data = trendData.pending;
        trendChart.data.datasets[1].data = trendData.approved;
        trendChart.data.datasets[2].data = trendData.rejected;
        trendChart.data.datasets.forEach((dataset, index) => {
            dataset.hidden = hiddenStates[index];
        });
        trendChart.update();
    }

    updateTrendTitle();
}

function updateTrendTitle() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const title = document.getElementById('trendChartTitle');

    if (startDate || endDate) {
        const fromText = startDate ? formatDate(startDate) : 'Beginning';
        const toText = endDate ? formatDate(endDate) : 'Present';
        title.textContent = `Daily Requests Trend (${fromText} - ${toText})`;
    } else {
        title.textContent = 'Daily Requests Trend (Last 90 Days)';
    }
}

function updateStats(records) {
    const counts = getStatusCounts(records);
    document.getElementById('statTotal').textContent = counts.total;
    document.getElementById('statPending').textContent = counts.pending;
    document.getElementById('statApproved').textContent = counts.approved;
    document.getElementById('statRejected').textContent = counts.rejected;
}

function updateTable(records) {
    const tbody = document.getElementById('recordsTableBody');

    if (!records.length) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:40px; color:#999;">No requests found</td></tr>';
        return;
    }

    tbody.innerHTML = records.map(record => {
        const status = normalizeStatus(record.status);
        const badgeClass = status;

        return `
            <tr>
                <td>${formatDate(record.created_at)}</td>
                <td>
                    <strong>${record.company_name || 'N/A'}</strong>
                    ${record.company_type ? `<div style="font-size:12px; color:#666;">${record.company_type}</div>` : ''}
                </td>
                <td>${record.contact_person_name || 'N/A'}</td>
                <td>${record.email || 'N/A'}</td>
                <td>${record.phone_number || 'N/A'}</td>
                <td><span class="badge ${badgeClass}">${status}</span></td>
            </tr>
        `;
    }).join('');
}

function applyFilters() {
    const searchString = document.getElementById('companySearchFilter').value.trim().toLowerCase();
 
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    filteredRecords = allRecords.filter(record => {
        const recordDate = parseLocalDate(record.created_at);
        const start = startDate ? parseLocalDate(startDate) : null;
        const end = endDate ? parseLocalDate(endDate) : null;

        if (start && recordDate < start) return false;
        if (end && recordDate > end) return false;

 

        if (searchString) {
            const companyMatch = (record.company_name || '').toLowerCase().includes(searchString);
            const emailMatch = (record.email || '').toLowerCase().includes(searchString);
            if (!companyMatch && !emailMatch) {
                return false;
            }
        }

        return true;
    });

    updateStats(filteredRecords);
    buildStatusChart(filteredRecords);
    buildTrendChart(filteredRecords);
    updateTable(filteredRecords);
}

function resetFilters() {
    document.getElementById('companySearchFilter').value = '';
 

    const today = new Date();
    const past = new Date();
    past.setDate(today.getDate() - 89);
    
    document.getElementById('startDate').value = past.toISOString().slice(0, 10);
    document.getElementById('endDate').value = today.toISOString().slice(0, 10);

    applyFilters();
}

// Chart toggle listeners
document.getElementById('togglePending').addEventListener('change', (e) => {
    trendChart.setDatasetVisibility(0, e.target.checked);
    trendChart.update();
});
document.getElementById('toggleApproved').addEventListener('change', (e) => {
    trendChart.setDatasetVisibility(1, e.target.checked);
    trendChart.update();
});
document.getElementById('toggleRejected').addEventListener('change', (e) => {
    trendChart.setDatasetVisibility(2, e.target.checked);
    trendChart.update();
});

// PDF Generation
document.getElementById('downloadPdf').addEventListener('click', async () => {
    const { jsPDF } = window.jspdf;
    
    const originalStyle = document.querySelector('.analytics-container').style.cssText;
    document.querySelector('.analytics-container').style.width = '1200px';
    document.querySelector('.analytics-container').style.margin = '0';
    
    const elementsToHide = [
        document.querySelector('.page-header button'),
        document.querySelector('.tertiary-btn'),
        document.querySelector('.filter-section')
    ];
    elementsToHide.forEach(el => {
        if (el) el.style.display = 'none';
    });

    try {
        const canvas = await html2canvas(document.querySelector('.analytics-container'), {
            scale: 2,
            useCORS: true,
            logging: false
        });

        const imgData = canvas.toDataURL('image/jpeg', 1.0);
        
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
        
        pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
        pdf.save('Client_Requests_Report_' + new Date().toISOString().slice(0,10) + '.pdf');
    } catch (error) {
        console.error('PDF generation failed:', error);
        alert('Failed to generate PDF report. Please try again.');
    } finally {
        document.querySelector('.analytics-container').style.cssText = originalStyle;
        elementsToHide.forEach(el => {
            if (el) el.style.display = '';
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('applyFilter').addEventListener('click', applyFilters);
    document.getElementById('resetFilter').addEventListener('click', resetFilters);
    applyFilters();
});
</script>
</body>
</html>