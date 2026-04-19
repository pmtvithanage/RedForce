<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<style>
    .analytics-container { padding: 20px; background: #f5f5f5; }
    .page-header, .filter-section, .chart-card, .table-card {
        background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,.1); padding: 20px;
    }
    .page-header { display:flex; justify-content:space-between; align-items:center; gap:20px; margin-bottom:30px; }
    .filter-section, .charts-grid { margin-bottom: 30px; }
    .filter-section h3, .chart-card h2, .table-card h2 {
        font-size: 18px; margin-bottom: 20px; color:#333; border-bottom:2px solid #D32F2F; padding-bottom:10px;
    }
    .filter-controls { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:15px; align-items:end; }
    .filter-group { display:flex; flex-direction:column; gap:6px; }
    .filter-group label { font-size:13px; color:#666; font-weight:500; }
    .filter-group select, .filter-group input {
        padding:10px 14px; border:1px solid #ddd; border-radius:4px; font-size:14px; background:#fff;
    }
    .filter-buttons { display:flex; gap:10px; flex-wrap:wrap; }
    .filter-btn, .pdf-button {
        border:none; border-radius:4px; cursor:pointer; font-size:14px; font-weight:600; transition:.2s;
    }
    .filter-btn { padding:10px 18px; }
    .filter-btn.apply, .pdf-button { background:#D32F2F; color:#fff; }
    .filter-btn.apply:hover, .pdf-button:hover { background:#B71C1C; }
    .filter-btn.reset { background:#f0f0f0; color:#555; }
    .pdf-button { display:inline-flex; align-items:center; gap:8px; padding:12px 24px; }

    .stats-grid {
        display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom:30px;
    }
    .stat-card { background:#fff; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,.1); padding:22px; }
    .stat-card h3 { font-size:13px; color:#666; margin-bottom:8px; text-transform:uppercase; }
    .stat-card .value { font-size:34px; font-weight:700; color:#D32F2F; }
    .stat-card .sub { margin-top:6px; font-size:12px; color:#888; }

    .charts-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(450px,1fr)); gap:20px; }
    .chart-container { position:relative; height:320px; }

    .site-table { width:100%; border-collapse:collapse; }
    .site-table th {
        background:#f5f5f5; padding:12px; text-align:left; font-weight:600; color:#555; border-bottom:2px solid #ddd;
        white-space:nowrap;
    }
    .site-table td { padding:12px; border-bottom:1px solid #eee; vertical-align:top; }
    .site-table tr:hover { background:#fafafa; }
    .muted { color:#888; }
    .risk {
        display:inline-block; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600;
    }
    .risk.low { background:#E8F5E9; color:#2E7D32; }
    .risk.medium { background:#FFF3E0; color:#E65100; }
    .risk.high { background:#FFEBEE; color:#C62828; }
</style>

<div class="analytics-container">
    <button class="tertiary-btn" style="display:flex; width:100px; margin-bottom:20px; align-items:center;" onclick="history.back()">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>

    <div class="page-header">
        <div>
            <h1><?php echo $data['pageTitle']; ?></h1>
            <p style="color:#666; margin-top:5px;">Site-level staffing, incidents, and payment health report</p>
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
                <label>Client Name</label>
                <input type="text" id="clientFilter" placeholder="Search client">
            </div>
            <div class="filter-group">
                <label>District</label>
                <input type="text" id="districtFilter" placeholder="e.g. Colombo">
            </div>
            <div class="filter-group">
                <label>City</label>
                <input type="text" id="cityFilter" placeholder="e.g. Dehiwala">
            </div>
            <div class="filter-buttons">
                <button class="filter-btn apply" id="applyFilter" type="button">Apply Filter</button>
                <button class="filter-btn reset" id="resetFilter" type="button">Reset</button>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card"><h3>Total Sites</h3><div class="value" id="statSites">0</div></div>
        <div class="stat-card"><h3>Total Active Officers</h3><div class="value" id="statOfficers">0</div></div>
        <div class="stat-card"><h3>Total Supervisors</h3><div class="value" id="statSupervisors">0</div></div>
        <div class="stat-card"><h3>Total Caretakers</h3><div class="value" id="statCaretakers">0</div></div>
        <div class="stat-card"><h3>Total Incidents</h3><div class="value" id="statIncidents">0</div><div class="sub" id="statIncidentSplit"></div></div>
        <div class="stat-card"><h3>Total Paid Amount</h3><div class="value" id="statPaid">0</div><div class="sub" id="statPendingOverdue"></div></div>
    </div>

    <div class="charts-grid">
        <div class="chart-card">
            <h2>Incidents by Site (Top 10)</h2>
            <div class="chart-container"><canvas id="incidentsBySiteChart"></canvas></div>
        </div>
        <div class="chart-card">
            <h2>Staffing Distribution</h2>
            <div class="chart-container"><canvas id="staffingChart"></canvas></div>
        </div>
 
    </div>

    <div class="table-card">
        <h2>Site Summary</h2>
        <table class="site-table">
            <thead>
                <tr>
                    <th>Site</th>
                    <th>Client</th>
                    <th>Location</th>
                    <th>Officers</th>
                    <th>Supervisors</th>
                    <th>Caretakers</th>
                    <th>Incidents</th>
                    <th>Payments</th>
                </tr>
            </thead>
            <tbody id="siteTableBody">
                <tr><td colspan="8" style="text-align:center; padding:40px; color:#999;">Loading site data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

</main>
</div>
<div class="backdrop" id="backdrop" hidden></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
const allSites = <?php echo json_encode($data['siteDataset'] ?? []); ?>;
let filteredSites = [];
let incidentsBySiteChart, staffingChart;

Chart.defaults.font.family = "'Inter', sans-serif";

function toNum(value) { return Number(value || 0); }
function money(value) { return `LKR ${toNum(value).toLocaleString()}`; }

 

function updateStats(sites) {
    const totals = sites.reduce((acc, s) => {
        acc.officers += toNum(s.total_officers);
        acc.supervisors += toNum(s.supervisors);
        acc.caretakers += toNum(s.caretakers);
        acc.incidents += toNum(s.total_incidents);
        acc.pending += toNum(s.pending_incidents);
        acc.resolved += toNum(s.resolved_incidents);
        acc.paid += toNum(s.paid_amount);
        acc.pendingAmount += toNum(s.pending_amount);
        acc.overdueAmount += toNum(s.overdue_amount);
        return acc;
    }, { officers:0, supervisors:0, caretakers:0, incidents:0, pending:0, resolved:0, paid:0, pendingAmount:0, overdueAmount:0 });

    document.getElementById('statSites').textContent = sites.length;
    document.getElementById('statOfficers').textContent = totals.officers;
    document.getElementById('statSupervisors').textContent = totals.supervisors;
    document.getElementById('statCaretakers').textContent = totals.caretakers;
    document.getElementById('statIncidents').textContent = totals.incidents;
    document.getElementById('statIncidentSplit').textContent = `${totals.pending} pending / ${totals.resolved} resolved`;
    document.getElementById('statPaid').textContent = money(totals.paid);
    document.getElementById('statPendingOverdue').textContent = `Pending ${money(totals.pendingAmount)} | Overdue ${money(totals.overdueAmount)}`;
}

function buildIncidentsChart(sites) {
    const top = [...sites].sort((a,b) => toNum(b.total_incidents)-toNum(a.total_incidents)).slice(0,10);
    const labels = top.map(s => (s.site_name || 'Unknown').slice(0, 20));
    const values = top.map(s => toNum(s.total_incidents));
    if (!incidentsBySiteChart) {
        incidentsBySiteChart = new Chart(document.getElementById('incidentsBySiteChart').getContext('2d'), {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Incidents', data: values, backgroundColor: 'rgba(211,47,47,.75)' }] },
            options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{ beginAtZero:true, ticks:{stepSize:1, precision:0} } } }
        });
    } else {
        incidentsBySiteChart.data.labels = labels;
        incidentsBySiteChart.data.datasets[0].data = values;
        incidentsBySiteChart.update();
    }
}

function buildStaffingChart(sites) {
    const officers = sites.reduce((sum,s)=>sum+toNum(s.total_officers),0);
    const supervisors = sites.reduce((sum,s)=>sum+toNum(s.supervisors),0);
    const caretakers = sites.reduce((sum,s)=>sum+toNum(s.caretakers),0);
    const values = [officers, supervisors, caretakers];
    if (!staffingChart) {
        staffingChart = new Chart(document.getElementById('staffingChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Officers', 'Supervisors', 'Caretakers'],
                datasets: [{ data: values, backgroundColor: ['#F44336','#2196F3','#4CAF50'], borderColor:'#fff', borderWidth:2 }]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } } }
        });
    } else {
        staffingChart.data.datasets[0].data = values;
        staffingChart.update();
    }
}

 

function updateTable(sites) {
    const tbody = document.getElementById('siteTableBody');
    if (!sites.length) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align:center; padding:40px; color:#999;">No sites found for selected filters</td></tr>';
        return;
    }

    tbody.innerHTML = sites.map(site => {
        return `
            <tr>
                <td>${site.site_name || 'Unknown'}</td>
                <td>${site.client_name || 'Unknown'}</td>
                <td>${site.city || '-'}, ${site.district || '-'}</td>
                <td>${toNum(site.total_officers)}</td>
                <td>${toNum(site.supervisors)}</td>
                <td>${toNum(site.caretakers)}</td>
                <td>${toNum(site.total_incidents)} <span class="muted">(${toNum(site.pending_incidents)} pending)</span></td>
                <td>
                    <div>Paid: ${money(site.paid_amount)}</div>
                    <div class="muted">Pending: ${money(site.pending_amount)}, Overdue: ${money(site.overdue_amount)}</div>
                </td>
            </tr>
        `;
    }).join('');
}

function applyFilters() {
    const siteId = document.getElementById('siteFilter').value;
    const client = document.getElementById('clientFilter').value.trim().toLowerCase();
    const district = document.getElementById('districtFilter').value.trim().toLowerCase();
    const city = document.getElementById('cityFilter').value.trim().toLowerCase();

    filteredSites = allSites.filter(site => {
        if (siteId && String(site.id) !== String(siteId)) return false;
        if (client && !String(site.client_name || '').toLowerCase().includes(client)) return false;
        if (district && !String(site.district || '').toLowerCase().includes(district)) return false;
        if (city && !String(site.city || '').toLowerCase().includes(city)) return false;
        return true;
    });

    updateStats(filteredSites);
    buildIncidentsChart(filteredSites);
    buildStaffingChart(filteredSites);
    updateTable(filteredSites);
}

function resetFilters() {
    document.getElementById('siteFilter').value = '';
    document.getElementById('clientFilter').value = '';
    document.getElementById('districtFilter').value = '';
    document.getElementById('cityFilter').value = '';
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
        let page = 1;
        const addPageNo = () => {
            pdf.setFontSize(9);
            pdf.setTextColor(150);
            pdf.text(`Page ${page}`, pageWidth - 30, pageHeight - 10);
            pdf.text('RED FORCE Security Services', 20, pageHeight - 10);
            page += 1;
        };

        const totals = filteredSites.reduce((acc, s) => {
            acc.sites += 1;
            acc.officers += toNum(s.total_officers);
            acc.incidents += toNum(s.total_incidents);
            acc.paid += toNum(s.paid_amount);
            acc.pending += toNum(s.pending_amount);
            acc.overdue += toNum(s.overdue_amount);
            return acc;
        }, { sites:0, officers:0, incidents:0, paid:0, pending:0, overdue:0 });

        pdf.setFillColor(211,47,47);
        pdf.rect(0,0,pageWidth,80,'F');
        pdf.setTextColor(255);
        pdf.setFontSize(28);
        pdf.setFont(undefined,'bold');
        pdf.text('SITE REPORT', pageWidth/2, 35, {align:'center'});
        pdf.setFontSize(16);
        pdf.setFont(undefined,'normal');
        pdf.text('Analytics & Summary', pageWidth/2, 50, {align:'center'});
        pdf.setFontSize(11);
        pdf.text(`Generated: ${new Date().toLocaleString()}`, pageWidth/2, 65, {align:'center'});

        let y = 100;
        pdf.setTextColor(0);
        pdf.setFontSize(12);
        pdf.setFont(undefined,'bold');
        pdf.text('Executive Summary', 20, y);
        y += 8;
        pdf.setFontSize(10);
        pdf.setFont(undefined,'normal');
        pdf.text(`Sites in report: ${totals.sites}`, 25, y); y += 6;
        pdf.text(`Active officers across sites: ${totals.officers}`, 25, y); y += 6;
        pdf.text(`Total incidents: ${totals.incidents}`, 25, y); y += 6;
        pdf.text(`Paid amount: ${money(totals.paid)}`, 25, y); y += 6;
        pdf.text(`Pending + overdue: ${money(totals.pending + totals.overdue)}`, 25, y);
        addPageNo();

        const chartPages = [
            ['incidentsBySiteChart', 'Incidents by Site'],
            ['staffingChart', 'Staffing Distribution']
        ];
        chartPages.forEach(([id, title]) => {
            pdf.addPage();
            pdf.setFillColor(211,47,47);
            pdf.rect(0,0,pageWidth,15,'F');
            pdf.setTextColor(255);
            pdf.setFontSize(16);
            pdf.setFont(undefined,'bold');
            pdf.text(title, 20, 10);
            const img = document.getElementById(id).toDataURL('image/png');
            pdf.addImage(img, 'PNG', 20, 30, pageWidth - 40, 115);
            addPageNo();
        });

        pdf.addPage();
        pdf.setFillColor(211,47,47);
        pdf.rect(0,0,pageWidth,15,'F');
        pdf.setTextColor(255);
        pdf.setFontSize(16);
        pdf.setFont(undefined,'bold');
        pdf.text('Site List (Top 30)', 20, 10);
        pdf.setTextColor(0);
        pdf.setFontSize(8);
        let yPos = 28;
        pdf.text('Site', 20, yPos);
        pdf.text('Client', 70, yPos);
        pdf.text('Inc', 120, yPos);
        pdf.text('Officers', 135, yPos);
        pdf.text('Pending+Overdue', 155, yPos);
        yPos += 6;
        filteredSites.slice(0,30).forEach(site => {
            if (yPos > 275) {
                addPageNo();
                pdf.addPage();
                yPos = 20;
            }
            pdf.text(String(site.site_name || 'Unknown').slice(0, 22), 20, yPos);
            pdf.text(String(site.client_name || 'Unknown').slice(0, 20), 70, yPos);
            pdf.text(String(toNum(site.total_incidents)), 120, yPos);
            pdf.text(String(toNum(site.total_officers)), 135, yPos);
            pdf.text(money(toNum(site.pending_amount) + toNum(site.overdue_amount)), 155, yPos);
            yPos += 6;
        });
        addPageNo();

        pdf.save(`site-report-${new Date().toISOString().split('T')[0]}.pdf`);
    } catch (e) {
        console.error(e);
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