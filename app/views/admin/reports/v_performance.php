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

    .filter-section,
    .chart-card,
    .table-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .filter-section { margin-bottom: 30px; }

    .filter-section h3,
    .chart-card h2,
    .table-card h2 {
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

    .filter-btn { padding: 10px 18px; }

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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 22px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stat-card h3 {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .stat-card .value {
        font-size: 34px;
        font-weight: bold;
        color: #D32F2F;
    }

    .stat-card .sub {
        margin-top: 6px;
        font-size: 12px;
        color: #888;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-container {
        position: relative;
        height: 320px;
    }

    .chart-container.tall {
        height: 420px;
    }

    .performance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .performance-table th {
        background: #f5f5f5;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #ddd;
        white-space: nowrap;
    }

    .performance-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        vertical-align: top;
    }

    .performance-table tr:hover {
        background: #fafafa;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge.good { background: #E8F5E9; color: #2E7D32; }
    .badge.avg { background: #FFF3E0; color: #E65100; }
    .badge.poor { background: #FFEBEE; color: #C62828; }

    .muted { color: #888; }

    @media (max-width: 900px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .charts-grid { grid-template-columns: 1fr; }
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
            <p style="color:#666; margin-top:5px;">Officer performance insights based on attendance, rating, and incident activity</p>
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
                <label>Supervisor (marked attendance)</label>
                <select id="supervisorFilter">
                    <option value="">All Supervisors</option>
                    <?php foreach ($data['supervisors'] ?? [] as $supervisor): ?>
                        <option value="<?php echo $supervisor->id; ?>"><?php echo htmlspecialchars($supervisor->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Officer</label>
                <select id="officerSelect">
                    <option value="">All Officers</option>
                    <?php foreach ($data['officers'] ?? [] as $officer): ?>
                        <option value="<?php echo $officer->userID; ?>"><?php echo htmlspecialchars($officer->name); ?> (<?php echo htmlspecialchars($officer->userID); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Officer Search</label>
                <input type="text" id="officerSearch" placeholder="Officer ID or name">
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
            <h3>Officers in Report</h3>
            <div class="value" id="statOfficers">0</div>
            <div class="sub">Unique officers in filtered data</div>
        </div>
        <div class="stat-card">
            <h3>Attendance Records</h3>
            <div class="value" id="statRecords">0</div>
            <div class="sub">Total records in date range</div>
        </div>
        <div class="stat-card">
            <h3>Avg Attendance Rate</h3>
            <div class="value" id="statAttendanceRate">0%</div>
            <div class="sub">Present / total records</div>
        </div>
        <div class="stat-card">
            <h3>Avg Officer Rating</h3>
            <div class="value" id="statAvgRating">0.0</div>
            <div class="sub">From premise officer profiles</div>
        </div>
        <div class="stat-card">
            <h3>Incidents Reported</h3>
            <div class="value" id="statIncidents">0</div>
            <div class="sub">Incidents on attendance dates</div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card">
            <h2>Attendance Status Breakdown</h2>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h2>Top Officers by Performance Score</h2>
            <div class="chart-container">
                <canvas id="topOfficersChart"></canvas>
            </div>
        </div>

        <div class="chart-card" style="grid-column: span 2;">
            <h2 id="trendTitle">Daily Trend (Last 90 Days)</h2>
            <div class="chart-container tall">
                <canvas id="dailyTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="table-card">
        <h2>Officer Performance Summary</h2>
        <table class="performance-table">
            <thead>
                <tr>
                    <th>Officer</th>
                    <th>Rating</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Half Day</th>
                    <th>Attendance Rate</th>
                    <th>Incidents Reported</th>
                    <th>Sites</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody id="summaryTableBody">
                <tr>
                    <td colspan="10" style="text-align:center; padding:40px; color:#999;">Loading performance summary...</td>
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
const allRows = <?php echo json_encode($data['dataset'] ?? []); ?>;
let filteredRows = [];
let officerSummary = [];

let statusChart;
let topOfficersChart;
let dailyTrendChart;

Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#666';

function parseLocalDate(dateString) {
    if (!dateString) return null;
    const [year, month, day] = dateString.split('-').map(Number);
    return new Date(year, month - 1, day);
}

function formatDate(dateString) {
    const date = parseLocalDate(dateString);
    if (!date) return 'N/A';
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function normalizeStatus(status) {
    return status || 'Present';
}

function uniqueSitesFromRows(rows) {
    const set = new Set();
    rows.forEach(row => {
        String(row.site_name || '').split(',').map(s => s.trim()).filter(Boolean).forEach(site => set.add(site));
    });
    return Array.from(set);
}

function computeOfficerSummary(rows) {
    const byOfficer = new Map();

    rows.forEach(row => {
        const officerId = String(row.officer_id || '').trim();
        const officerName = String(row.officer_name || 'Unknown').trim();
        const key = officerId || officerName;
        const status = normalizeStatus(row.status);

        if (!byOfficer.has(key)) {
            byOfficer.set(key, {
                officer_id: officerId,
                officer_name: officerName,
                rating: Number(row.officer_rating || 0),
                present: 0,
                absent: 0,
                late: 0,
                halfDay: 0,
                incidents: 0,
                sites: new Set()
            });
        }

        const entry = byOfficer.get(key);
        if (status === 'Present') entry.present += 1;
        if (status === 'Absent') entry.absent += 1;
        if (status === 'Late') entry.late += 1;
        if (status === 'Half Day') entry.halfDay += 1;

        entry.incidents += Number(row.incidents_reported_that_day || 0);

        String(row.site_name || '').split(',').map(s => s.trim()).filter(Boolean).forEach(site => entry.sites.add(site));
        entry.rating = Number.isFinite(entry.rating) ? entry.rating : 0;
    });

    const summary = Array.from(byOfficer.values()).map(entry => {
        const total = entry.present + entry.absent + entry.late + entry.halfDay;
        const attendanceRate = total > 0 ? (entry.present / total) : 0;

        const score = (
            (entry.present * 1.0) +
            (entry.halfDay * 0.5) +
            (entry.late * 0.25) -
            (entry.absent * 1.0) +
            (Number(entry.rating || 0) * 0.5) -
            (entry.incidents * 0.1)
        );

        return {
            ...entry,
            total,
            attendanceRate,
            score: Number(score.toFixed(2)),
            sitesText: entry.sites.size ? Array.from(entry.sites).join(', ') : 'Unassigned'
        };
    });

    summary.sort((a, b) => b.score - a.score);
    return summary;
}

function updateStats(rows, summary) {
    const uniqueOfficers = new Set(summary.map(item => item.officer_id || item.officer_name));
    document.getElementById('statOfficers').textContent = uniqueOfficers.size;
    document.getElementById('statRecords').textContent = rows.length;

    const presentCount = rows.filter(r => normalizeStatus(r.status) === 'Present').length;
    const attendanceRate = rows.length ? ((presentCount / rows.length) * 100) : 0;
    document.getElementById('statAttendanceRate').textContent = `${attendanceRate.toFixed(1)}%`;

    const avgRating = summary.length
        ? (summary.reduce((sum, item) => sum + (Number(item.rating) || 0), 0) / summary.length)
        : 0;
    document.getElementById('statAvgRating').textContent = avgRating.toFixed(1);

    const totalIncidents = rows.reduce((sum, row) => sum + Number(row.incidents_reported_that_day || 0), 0);
    document.getElementById('statIncidents').textContent = totalIncidents;
}

function buildStatusChart(rows) {
    const counts = {
        Present: 0,
        Absent: 0,
        Late: 0,
        'Half Day': 0
    };

    rows.forEach(row => {
        const status = normalizeStatus(row.status);
        if (counts[status] !== undefined) {
            counts[status] += 1;
        }
    });

    const dataValues = [counts.Present, counts.Absent, counts.Late, counts['Half Day']];

    if (!statusChart) {
        statusChart = new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Late', 'Half Day'],
                datasets: [{
                    data: dataValues,
                    backgroundColor: ['#4CAF50', '#616161', '#FF9800', '#2196F3'],
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
                        labels: { padding: 15 }
                    }
                }
            }
        });
        return;
    }

    statusChart.data.datasets[0].data = dataValues;
    statusChart.update();
}

function buildTopOfficersChart(summary) {
    const top = summary.slice(0, 10);
    const labels = top.map(item => (item.officer_name || item.officer_id || 'Officer').slice(0, 18));
    const values = top.map(item => item.score);

    if (!topOfficersChart) {
        topOfficersChart = new Chart(document.getElementById('topOfficersChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Performance Score',
                    data: values,
                    backgroundColor: 'rgba(211, 47, 47, 0.7)',
                    borderColor: '#D32F2F',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
        return;
    }

    topOfficersChart.data.labels = labels;
    topOfficersChart.data.datasets[0].data = values;
    topOfficersChart.update();
}

function calculateDailyTrend(rows) {
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

    const daily = {};
    const cursor = new Date(startDate);
    while (cursor <= endDate) {
        const key = cursor.toISOString().slice(0, 10);
        daily[key] = { total: 0, present: 0, absent: 0, late: 0, halfDay: 0 };
        cursor.setDate(cursor.getDate() + 1);
    }

    rows.forEach(row => {
        const key = row.attendance_date;
        if (!daily[key]) return;
        const status = normalizeStatus(row.status);
        daily[key].total += 1;
        if (status === 'Present') daily[key].present += 1;
        if (status === 'Absent') daily[key].absent += 1;
        if (status === 'Late') daily[key].late += 1;
        if (status === 'Half Day') daily[key].halfDay += 1;
    });

    const keys = Object.keys(daily).sort();
    const labels = [];
    const total = [];
    const present = [];
    const absent = [];
    const late = [];
    const halfDay = [];
    const showFullDates = keys.length <= 30;
    const skipInterval = keys.length > 180 ? 7 : 3;

    keys.forEach((key, index) => {
        const date = parseLocalDate(key);
        const label = showFullDates || index % skipInterval === 0 || index === keys.length - 1
            ? date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
            : '';
        labels.push(label);
        total.push(daily[key].total);
        present.push(daily[key].present);
        absent.push(daily[key].absent);
        late.push(daily[key].late);
        halfDay.push(daily[key].halfDay);
    });

    return { labels, total, present, absent, late, halfDay };
}

function updateTrendTitle() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const title = document.getElementById('trendTitle');

    if (startDate || endDate) {
        const from = startDate ? formatDate(startDate) : 'Beginning';
        const to = endDate ? formatDate(endDate) : 'Present';
        title.textContent = `Daily Trend (${from} - ${to})`;
    } else {
        title.textContent = 'Daily Trend (Last 90 Days)';
    }
}

function buildDailyTrendChart(rows) {
    const trend = calculateDailyTrend(rows);
    updateTrendTitle();

    if (!dailyTrendChart) {
        dailyTrendChart = new Chart(document.getElementById('dailyTrendChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: trend.labels,
                datasets: [
                    { label: 'Total', data: trend.total, borderColor: '#D32F2F', borderWidth: 2, tension: 0.3, pointRadius: 2 },
                    { label: 'Present', data: trend.present, borderColor: '#4CAF50', borderWidth: 2, tension: 0.3, pointRadius: 2 },
                    { label: 'Absent', data: trend.absent, borderColor: '#616161', borderWidth: 2, tension: 0.3, pointRadius: 2 },
                    { label: 'Late', data: trend.late, borderColor: '#FF9800', borderWidth: 2, tension: 0.3, pointRadius: 2 },
                    { label: 'Half Day', data: trend.halfDay, borderColor: '#2196F3', borderWidth: 2, tension: 0.3, pointRadius: 2 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
                }
            }
        });
        return;
    }

    dailyTrendChart.data.labels = trend.labels;
    dailyTrendChart.data.datasets[0].data = trend.total;
    dailyTrendChart.data.datasets[1].data = trend.present;
    dailyTrendChart.data.datasets[2].data = trend.absent;
    dailyTrendChart.data.datasets[3].data = trend.late;
    dailyTrendChart.data.datasets[4].data = trend.halfDay;
    dailyTrendChart.update();
}

function badgeForAttendanceRate(rate) {
    if (rate >= 0.85) return { cls: 'good', text: 'Good' };
    if (rate >= 0.7) return { cls: 'avg', text: 'Average' };
    return { cls: 'poor', text: 'Needs Attention' };
}

function updateSummaryTable(summary) {
    const tbody = document.getElementById('summaryTableBody');
    if (!summary.length) {
        tbody.innerHTML = '<tr><td colspan="10" style="text-align:center; padding:40px; color:#999;">No officers match these filters</td></tr>';
        return;
    }

    tbody.innerHTML = summary.map(item => {
        const ratePct = (item.attendanceRate * 100).toFixed(1);
        const badge = badgeForAttendanceRate(item.attendanceRate);
        const ratingText = Number(item.rating || 0).toFixed(1);
        const sitesText = item.sitesText || 'Unassigned';

        return `
            <tr>
                <td>${item.officer_name || 'Unknown'} <span class="muted">(${item.officer_id || 'N/A'})</span></td>
                <td>${ratingText}</td>
                <td>${item.present}</td>
                <td>${item.absent}</td>
                <td>${item.late}</td>
                <td>${item.halfDay}</td>
                <td>${ratePct}% <span class="badge ${badge.cls}">${badge.text}</span></td>
                <td>${item.incidents}</td>
                <td>${sitesText}</td>
                <td><strong>${item.score}</strong></td>
            </tr>
        `;
    }).join('');
}

function recordMatchesSite(row, siteId) {
    if (!siteId) return true;
    const ids = String(row.site_ids || '').split(',').filter(Boolean);
    return ids.includes(String(siteId)) || String(row.site_id || '') === String(siteId);
}

function applyFilters() {
    const siteId = document.getElementById('siteFilter').value;
    const supervisorId = document.getElementById('supervisorFilter').value;
    const officerSelected = document.getElementById('officerSelect').value;
    const officerSearch = document.getElementById('officerSearch').value.trim().toLowerCase();
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    const start = startDate ? parseLocalDate(startDate) : null;
    const end = endDate ? parseLocalDate(endDate) : null;

    filteredRows = allRows.filter(row => {
        if (siteId && !recordMatchesSite(row, siteId)) return false;
        if (supervisorId && String(row.supervisor_id || '') !== String(supervisorId)) return false;
        if (officerSelected && String(row.officer_id || '') !== String(officerSelected)) return false;

        if (officerSearch) {
            const officerId = String(row.officer_id || '').toLowerCase();
            const officerName = String(row.officer_name || '').toLowerCase();
            if (!officerId.includes(officerSearch) && !officerName.includes(officerSearch)) return false;
        }

        const date = parseLocalDate(row.attendance_date);
        if (start && date < start) return false;
        if (end && date > end) return false;

        return true;
    });

    officerSummary = computeOfficerSummary(filteredRows);
    updateStats(filteredRows, officerSummary);
    buildStatusChart(filteredRows);
    buildTopOfficersChart(officerSummary);
    buildDailyTrendChart(filteredRows);
    updateSummaryTable(officerSummary);
}

function resetFilters() {
    const today = new Date();
    const ninetyDaysAgo = new Date();
    ninetyDaysAgo.setDate(today.getDate() - 89);

    document.getElementById('siteFilter').value = '';
    document.getElementById('supervisorFilter').value = '';
    document.getElementById('officerSelect').value = '';
    document.getElementById('officerSearch').value = '';
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

        const present = filteredRows.filter(r => normalizeStatus(r.status) === 'Present').length;
        const attendanceRate = filteredRows.length ? ((present / filteredRows.length) * 100).toFixed(1) : '0.0';
        const incidents = filteredRows.reduce((sum, row) => sum + Number(row.incidents_reported_that_day || 0), 0);
        const avgRating = officerSummary.length
            ? (officerSummary.reduce((sum, item) => sum + (Number(item.rating) || 0), 0) / officerSummary.length).toFixed(1)
            : '0.0';

        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 80, 'F');
        pdf.setTextColor(255, 255, 255);
        pdf.setFontSize(26);
        pdf.setFont(undefined, 'bold');
        pdf.text('OFFICER PERFORMANCE', pageWidth / 2, 35, { align: 'center' });
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'normal');
        pdf.text('Report & Analytics', pageWidth / 2, 50, { align: 'center' });
        pdf.setFontSize(11);
        pdf.text(`Generated: ${new Date().toLocaleString()}`, pageWidth / 2, 65, { align: 'center' });

        let yPos = 100;
        pdf.setTextColor(0);
        pdf.setFontSize(12);
        pdf.setFont(undefined, 'bold');
        pdf.text('Summary:', 20, yPos);
        yPos += 10;
        pdf.setFontSize(10);
        pdf.setFont(undefined, 'normal');
        pdf.text(`Officers in report: ${officerSummary.length}`, 25, yPos); yPos += 6;
        pdf.text(`Attendance records: ${filteredRows.length}`, 25, yPos); yPos += 6;
        pdf.text(`Attendance rate (Present): ${attendanceRate}%`, 25, yPos); yPos += 6;
        pdf.text(`Average rating: ${avgRating}`, 25, yPos); yPos += 6;
        pdf.text(`Incidents reported (attendance dates): ${incidents}`, 25, yPos);
        addPageNumber();

        // Charts pages
        const chartIds = ['statusChart', 'topOfficersChart', 'dailyTrendChart'];
        const titles = ['Attendance Status Breakdown', 'Top Officers by Score', 'Daily Trend'];
        chartIds.forEach((id, index) => {
            pdf.addPage();
            pdf.setFillColor(211, 47, 47);
            pdf.rect(0, 0, pageWidth, 15, 'F');
            pdf.setTextColor(255);
            pdf.setFontSize(16);
            pdf.setFont(undefined, 'bold');
            pdf.text(titles[index], 20, 10);
            const canvas = document.getElementById(id);
            const imgData = canvas.toDataURL('image/png');
            pdf.addImage(imgData, 'PNG', 20, 30, pageWidth - 40, 120);
            addPageNumber();
        });

        // Table
        pdf.addPage();
        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 15, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'bold');
        pdf.text('Officer Summary (Top 25)', 20, 10);
        pdf.setTextColor(0);
        pdf.setFont(undefined, 'bold');
        pdf.setFontSize(8);
        yPos = 28;
        pdf.text('Officer', 20, yPos);
        pdf.text('Rate', 75, yPos);
        pdf.text('Rating', 95, yPos);
        pdf.text('Inc', 115, yPos);
        pdf.text('Score', 130, yPos);
        yPos += 6;
        pdf.setFont(undefined, 'normal');

        officerSummary.slice(0, 25).forEach(item => {
            if (yPos > 275) {
                addPageNumber();
                pdf.addPage();
                yPos = 20;
            }
            const name = String(item.officer_name || 'Unknown').slice(0, 24);
            const rate = `${(item.attendanceRate * 100).toFixed(1)}%`;
            pdf.text(name, 20, yPos);
            pdf.text(rate, 75, yPos);
            pdf.text(Number(item.rating || 0).toFixed(1), 95, yPos);
            pdf.text(String(item.incidents), 115, yPos);
            pdf.text(String(item.score), 130, yPos);
            yPos += 6;
        });
        addPageNumber();

        // Recommendations
        pdf.addPage();
        pdf.setFillColor(211, 47, 47);
        pdf.rect(0, 0, pageWidth, 15, 'F');
        pdf.setTextColor(255);
        pdf.setFontSize(16);
        pdf.setFont(undefined, 'bold');
        pdf.text('Recommendations', 20, 10);
        pdf.setTextColor(0);
        pdf.setFontSize(10);
        pdf.setFont(undefined, 'normal');
        yPos = 30;

        const recommendations = [];
        const lowPerformers = officerSummary.filter(o => o.attendanceRate < 0.7).length;
        if (lowPerformers > 0) recommendations.push(`${lowPerformers} officer(s) have attendance rate below 70%. Consider supervisor follow-up and site-level staffing review.`);
        if (Number(attendanceRate) < 75) recommendations.push('Overall attendance rate is below 75%. Review roster planning and enforcement.');
        if (Number(avgRating) < 3) recommendations.push('Average rating is below 3.0. Consider targeted training and performance coaching.');
        if (!recommendations.length) recommendations.push('Performance is within expected limits for the selected period. Continue monitoring trends.');

        recommendations.forEach(rec => {
            const lines = pdf.splitTextToSize(rec, pageWidth - 50);
            pdf.setFillColor(245, 245, 245);
            const height = lines.length * 5 + 8;
            pdf.roundedRect(20, yPos, pageWidth - 40, height, 2, 2, 'F');
            pdf.setTextColor(0);
            pdf.text(lines, 25, yPos + 6);
            yPos += height + 10;
        });
        addPageNumber();

        pdf.save(`officer-performance-report-${new Date().toISOString().split('T')[0]}.pdf`);
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