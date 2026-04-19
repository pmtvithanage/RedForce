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
        font-size:18px; margin-bottom:20px; color:#333; border-bottom:2px solid #D32F2F; padding-bottom:10px;
    }
    .filter-controls { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:15px; align-items:end; }
    .filter-group { display:flex; flex-direction:column; gap:6px; }
    .filter-group label { font-size:13px; color:#666; font-weight:500; }
    .filter-group select, .filter-group input {
        padding:10px 14px; border:1px solid #ddd; border-radius:4px; font-size:14px; background:#fff;
    }
    .filter-buttons { display:flex; gap:10px; flex-wrap:wrap; }
    .filter-btn, .pdf-button { border:none; border-radius:4px; cursor:pointer; font-size:14px; font-weight:600; transition:.2s; }
    .filter-btn { padding:10px 18px; }
    .filter-btn.apply, .pdf-button { background:#D32F2F; color:#fff; }
    .filter-btn.apply:hover, .pdf-button:hover { background:#B71C1C; }
    .filter-btn.reset { background:#f0f0f0; color:#555; }
    .pdf-button { display:inline-flex; align-items:center; gap:8px; padding:12px 24px; }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom:30px; }
    .stat-card { background:#fff; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,.1); padding:22px; }
    .stat-card h3 { font-size:13px; color:#666; margin-bottom:8px; text-transform:uppercase; }
    .stat-card .value { font-size:32px; font-weight:700; color:#D32F2F; }
    .stat-card .sub { margin-top:6px; font-size:12px; color:#888; }

    .charts-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(450px,1fr)); gap:20px; }
    .chart-container { position:relative; height:320px; }

    .payment-table { width:100%; border-collapse:collapse; }
    .payment-table th {
        background:#f5f5f5; padding:12px; text-align:left; font-weight:600; color:#555; border-bottom:2px solid #ddd;
        white-space:nowrap;
    }
    .payment-table td { padding:12px; border-bottom:1px solid #eee; vertical-align:top; }
    .payment-table tr:hover { background:#fafafa; }
    .badge { display:inline-block; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600; }
    .badge.paid { background:#E8F5E9; color:#2E7D32; }
    .badge.pending { background:#FFF3E0; color:#E65100; }
    .badge.overdue { background:#FFEBEE; color:#C62828; }
    .muted { color:#888; }
</style>

<div class="analytics-container">
    <button class="tertiary-btn" style="display:flex; width:100px; margin-bottom:20px; align-items:center;" onclick="history.back()">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>

    <div class="page-header">
        <div>
            <h1><?php echo $data['pageTitle']; ?></h1>
            <p style="color:#666; margin-top:5px;">Payment analytics and collection health across all client sites</p>
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
                <label>Client</label>
                <input type="text" id="clientFilter" placeholder="Search client name">
            </div>
 
            <div class="filter-group">
                <label>From Date</label>
                <input type="date" id="startDate">
            </div>
            <div class="filter-group">
                <label>To Date</label>
                <input type="date" id="endDate">
            </div>
            <div class="filter-buttons">
                <button class="filter-btn apply" id="applyFilter" type="button">Apply Filter</button>
                <button class="filter-btn reset" id="resetFilter" type="button">Reset</button>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Payments</h3>
            <div class="value" id="statTotalPayments">0</div>
            <div class="sub">All filtered payment records</div>
        </div>
        <div class="stat-card">
            <h3>Paid Amount</h3>
            <div class="value" id="statPaidAmount">LKR 0</div>
            <div class="sub" id="statPaidCount">0 paid records</div>
        </div>
        <div class="stat-card">
            <h3>Pending Amount</h3>
            <div class="value" id="statPendingAmount">LKR 0</div>
            <div class="sub" id="statPendingCount">0 pending records</div>
        </div>
        <div class="stat-card">
            <h3>Overdue Amount</h3>
            <div class="value" id="statOverdueAmount">LKR 0</div>
            <div class="sub" id="statOverdueCount">0 overdue records</div>
        </div>
 
        <div class="stat-card">
            <h3>Average Payment</h3>
            <div class="value" id="statAveragePayment">LKR 0</div>
            <div class="sub">Average paid transaction value</div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card">
            <h2>Payment Status Distribution</h2>
            <div class="chart-container"><canvas id="statusChart"></canvas></div>
        </div>
        <div class="chart-card" style="grid-column: span 2;">
            <h2>Payment Growth by Date</h2>
            <div class="chart-container"><canvas id="paymentGrowthChart"></canvas></div>
        </div>
    </div>

    <div class="table-card">
        <h2>Payment Records</h2>
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Site</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment Date</th>
                    <th>Due Date</th>
                    <th>Method</th>
                    <th>Reference</th>
                </tr>
            </thead>
            <tbody id="paymentTableBody">
                <tr><td colspan="8" style="text-align:center; padding:40px; color:#999;">Loading payment records...</td></tr>
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
const allPayments = <?php echo json_encode($data['payments'] ?? []); ?>;
let filteredPayments = [];
let statusChart, paymentGrowthChart;

function toNum(v) { return Number(v || 0); }
function money(v) { return `LKR ${toNum(v).toLocaleString()}`; }
function normalizeStatus(s) { return String(s || 'pending').toLowerCase(); }

function parseDate(value) {
    if (!value) return null;
    const datePart = String(value).slice(0, 10);
    const [y, m, d] = datePart.split('-').map(Number);
    return new Date(y, m - 1, d);
}

function formatDate(value) {
    const date = parseDate(value);
    if (!date) return '-';
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function updateStats(payments) {
    const totals = payments.reduce((acc, p) => {
        const amount = toNum(p.amount);
        const status = normalizeStatus(p.status);
        acc.total += 1;
        acc.billed += amount;
        if (status === 'paid') { acc.paidAmount += amount; acc.paidCount += 1; }
        if (status === 'pending') { acc.pendingAmount += amount; acc.pendingCount += 1; }
        if (status === 'overdue') { acc.overdueAmount += amount; acc.overdueCount += 1; }
        return acc;
    }, { total:0, billed:0, paidAmount:0, pendingAmount:0, overdueAmount:0, paidCount:0, pendingCount:0, overdueCount:0 });

    const averagePayment = totals.paidCount > 0 ? (totals.paidAmount / totals.paidCount) : 0;

    document.getElementById('statTotalPayments').textContent = totals.total;
    document.getElementById('statPaidAmount').textContent = money(totals.paidAmount);
    document.getElementById('statPaidCount').textContent = `${totals.paidCount} paid records`;
    document.getElementById('statPendingAmount').textContent = money(totals.pendingAmount);
    document.getElementById('statPendingCount').textContent = `${totals.pendingCount} pending records`;
    document.getElementById('statOverdueAmount').textContent = money(totals.overdueAmount);
    document.getElementById('statOverdueCount').textContent = `${totals.overdueCount} overdue records`;
    document.getElementById('statAveragePayment').textContent = money(averagePayment);
}

function buildStatusChart(payments) {
    const counts = { paid:0, pending:0, overdue:0 };
    payments.forEach(p => {
        const s = normalizeStatus(p.status);
        if (counts[s] !== undefined) counts[s] += 1;
    });
    const values = [counts.paid, counts.pending, counts.overdue];

    if (!statusChart) {
        statusChart = new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Pending', 'Overdue'],
                datasets: [{ data: values, backgroundColor: ['#4CAF50', '#FF9800', '#D32F2F'], borderColor:'#fff', borderWidth:2 }]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } } }
        });
    } else {
        statusChart.data.datasets[0].data = values;
        statusChart.update();
    }
}

function buildPaymentGrowthChart(payments) {
    const monthly = {};
    payments.forEach(p => {
        const date = parseDate(p.payment_date || p.created_at);
        if (!date) return;
        const key = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
        if (!monthly[key]) monthly[key] = 0;
        if (normalizeStatus(p.status) === 'paid') monthly[key] += toNum(p.amount);
    });

    const keys = Object.keys(monthly).sort();
    const labels = [];
    const growthData = [];
    let cumulative = 0;

    keys.forEach(k => {
        const [y, m] = k.split('-');
        labels.push(new Date(Number(y), Number(m) - 1, 1).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));
        cumulative += monthly[k];
        growthData.push(cumulative);
    });

    if (!paymentGrowthChart) {
        paymentGrowthChart = new Chart(document.getElementById('paymentGrowthChart').getContext('2d'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Cumulative Paid Amount (LKR)',
                    data: growthData,
                    borderColor: '#2196F3',
                    backgroundColor: 'rgba(33,150,243,0.2)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, scales: { y: { beginAtZero: true } } }
        });
    } else {
        paymentGrowthChart.data.labels = labels;
        paymentGrowthChart.data.datasets[0].data = growthData;
        paymentGrowthChart.update();
    }
}

function updateTable(payments) {
    const tbody = document.getElementById('paymentTableBody');
    if (!payments.length) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding:40px; color:#999;">No payment records found for selected filters</td></tr>';
        return;
    }

    tbody.innerHTML = payments.map(p => {
        const status = normalizeStatus(p.status);
        const siteName = p.site_name || p.package_request_site_name || 'N/A';
        const method = p.payment_method || '-';
        const ref = p.transaction_reference || '-';
        return `
            <tr>
                <td>${p.client_name || 'Unknown Client'}</td>
                <td>${siteName}</td>
                <td>${money(p.amount)}</td>
                <td><span class="badge ${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                <td>${formatDate(p.payment_date)}</td>
                <td>${formatDate(p.due_date)}</td>
                <td>${method}</td>
                <td>${ref}</td>
            </tr>
        `;
    }).join('');
}

function applyFilters() {
    const siteId = document.getElementById('siteFilter').value;
    const client = document.getElementById('clientFilter').value.trim().toLowerCase();
 
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const start = startDate ? parseDate(startDate) : null;
    const end = endDate ? parseDate(endDate) : null;

    filteredPayments = allPayments.filter(p => {
        if (siteId && String(p.site_id || '') !== String(siteId)) return false;
        if (client && !String(p.client_name || '').toLowerCase().includes(client)) return false;
 

        const paymentDate = parseDate(p.payment_date || p.created_at);
        if (start && paymentDate && paymentDate < start) return false;
        if (end && paymentDate && paymentDate > end) return false;
        return true;
    });

    updateStats(filteredPayments);
    buildStatusChart(filteredPayments);
    buildPaymentGrowthChart(filteredPayments);
    updateTable(filteredPayments);
}

function resetFilters() {
    document.getElementById('siteFilter').value = '';
    document.getElementById('clientFilter').value = '';
 
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
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

        const totals = filteredPayments.reduce((acc, p) => {
            const amount = toNum(p.amount);
            const status = normalizeStatus(p.status);
            acc.total += 1;
            acc.billed += amount;
            if (status === 'paid') acc.paid += amount;
            if (status === 'pending') acc.pending += amount;
            if (status === 'overdue') acc.overdue += amount;
            return acc;
        }, { total:0, billed:0, paid:0, pending:0, overdue:0 });

        pdf.setFillColor(211,47,47);
        pdf.rect(0,0,pageWidth,80,'F');
        pdf.setTextColor(255);
        pdf.setFontSize(28);
        pdf.setFont(undefined,'bold');
        pdf.text('CLIENT PAYMENT REPORT', pageWidth / 2, 35, { align:'center' });
        pdf.setFontSize(16);
        pdf.setFont(undefined,'normal');
        pdf.text('Analytics & Summary', pageWidth / 2, 50, { align:'center' });
        pdf.setFontSize(11);
        pdf.text(`Generated: ${new Date().toLocaleString()}`, pageWidth / 2, 65, { align:'center' });

        let y = 100;
        pdf.setTextColor(0);
        pdf.setFontSize(12);
        pdf.setFont(undefined,'bold');
        pdf.text('Executive Summary', 20, y);
        y += 8;
        pdf.setFontSize(10);
        pdf.setFont(undefined,'normal');
        pdf.text(`Payment records: ${totals.total}`, 25, y); y += 6;
        pdf.text(`Paid amount: ${money(totals.paid)}`, 25, y); y += 6;
        pdf.text(`Pending amount: ${money(totals.pending)}`, 25, y); y += 6;
        pdf.text(`Overdue amount: ${money(totals.overdue)}`, 25, y);
        addPageNo();

        const chartPages = [
            ['statusChart', 'Payment Status Distribution'],
            ['paymentGrowthChart', 'Payment Growth by Date']
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
        pdf.text('Payment List (Top 35)', 20, 10);
        pdf.setTextColor(0);
        pdf.setFontSize(8);
        let yPos = 28;
        pdf.text('Client', 20, yPos);
        pdf.text('Site', 70, yPos);
        pdf.text('Amount', 115, yPos);
        pdf.text('Status', 140, yPos);
        pdf.text('Date', 165, yPos);
        yPos += 6;
        filteredPayments.slice(0, 35).forEach(p => {
            if (yPos > 275) {
                addPageNo();
                pdf.addPage();
                yPos = 20;
            }
            pdf.text(String(p.client_name || 'Unknown').slice(0, 22), 20, yPos);
            pdf.text(String(p.site_name || p.package_request_site_name || 'N/A').slice(0, 20), 70, yPos);
            pdf.text(money(p.amount), 115, yPos);
            pdf.text(normalizeStatus(p.status).toUpperCase(), 140, yPos);
            pdf.text(formatDate(p.payment_date), 165, yPos);
            yPos += 6;
        });
        addPageNo();

        pdf.save(`client-payment-report-${new Date().toISOString().split('T')[0]}.pdf`);
    } catch (error) {
        console.error(error);
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