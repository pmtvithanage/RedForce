<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root {
        --primary-color: #a40000;
        --primary-light: #c41e1e;
        --success-color: #2e7d32;
        --warning-color: #f57c00;
        --danger-color: #c62828;
        --info-color: #1976d2;
        --bg-light: #f8f9fa;
        --border-color: #e0e0e0;
        --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        --radius: 8px;
    }

    * {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    *::-webkit-scrollbar {
        display: none;
    }

    .main-content {
        padding: 30px;
        max-width: 1600px;
        margin: 0 40px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .page-header p {
        color: #666;
        font-size: 15px;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .stat-icon.total {
        background: #e3f2fd;
        color: var(--info-color);
    }

    .stat-icon.paid {
        background: #e8f5e9;
        color: var(--success-color);
    }

    .stat-icon.pending {
        background: #fff3e0;
        color: var(--warning-color);
    }

    .stat-icon.overdue {
        background: #ffebee;
        color: var(--danger-color);
    }

    .stat-info h3 {
        margin: 0 0 6px 0;
        font-size: 14px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
    }

    /* Filters Section */
    .filters-section {
        background: white;
        padding: 20px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 24px;
    }

    .filters-bar {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 12px 12px 12px 44px;
        border: 2px solid var(--border-color);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
    }

    .search-box .fa-search {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .filter-select {
        padding: 12px 16px;
        border: 2px solid var(--border-color);
        border-radius: var(--radius);
        font-size: 14px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 150px;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .export-btn {
        padding: 12px 20px;
        background: var(--success-color);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .export-btn:hover {
        background: #1b5e20;
        transform: translateY(-1px);
    }

    /* Payments Table Section */
    .payments-table-section {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .table-container {
        overflow-x: auto;
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
    }

    .payments-table thead {
        background: var(--bg-light);
        border-bottom: 2px solid var(--border-color);
    }

    .payments-table th {
        padding: 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .payments-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    .payments-table tbody tr {
        transition: background 0.2s ease;
    }

    .payments-table tbody tr:hover {
        background: var(--bg-light);
    }

    .client-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .client-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .client-details {
        display: flex;
        flex-direction: column;
    }

    .client-name {
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.4;
    }

    .client-email {
        font-size: 12px;
        color: #666;
    }

    .site-name {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #333;
    }

    .site-name .fa-map-marker-alt {
        color: var(--primary-color);
    }

    .payment-amount {
        font-weight: 700;
        font-size: 16px;
        color: #1a1a1a;
    }

    .invoice-number {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #666;
        background: var(--bg-light);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 13px;
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status.paid {
        background: #e8f5e9;
        color: var(--success-color);
    }

    .status.pending {
        background: #fff3e0;
        color: var(--warning-color);
    }

    .status.overdue {
        background: #ffebee;
        color: var(--danger-color);
    }

    .status .fa {
        font-size: 10px;
    }

    .date-cell {
        color: #666;
        font-size: 13px;
    }

    .actions-cell {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn.view {
        background: #e3f2fd;
        color: var(--info-color);
    }

    .action-btn.view:hover {
        background: var(--info-color);
        color: white;
    }

    .action-btn.download {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .action-btn.download:hover {
        background: #7b1fa2;
        color: white;
    }

    .action-btn.edit {
        background: #fff3e0;
        color: var(--warning-color);
    }

    .action-btn.edit:hover {
        background: var(--warning-color);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state .fa-file-invoice {
        font-size: 72px;
        color: #ddd;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        color: #666;
        margin: 0 0 8px 0;
    }

    .empty-state p {
        font-size: 14px;
        color: #999;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: var(--radius);
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 30px;
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
        padding: 5px;
        line-height: 1;
    }

    .modal-close:hover {
        color: var(--danger-color);
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 20px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filters-bar {
            flex-direction: column;
        }

        .search-box,
        .filter-select {
            width: 100%;
        }

        .payments-table {
            font-size: 12px;
        }

        .payments-table th,
        .payments-table td {
            padding: 12px 8px;
        }

        .actions-cell {
            flex-direction: column;
        }
    }
</style>

<main class="main-content">
    <!-- Flash Messages -->
    <?php flash('payment_success'); ?>
    <?php flash('payment_error'); ?>

    <!-- Page Header -->
    <div class="page-header">
        <h2>Client Payments Management</h2>
        <p>View and manage all client payment records</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="stat-info">
                <h3>Total Payments</h3>
                <div class="stat-value"><?= isset($data['stats']->total_payments) ? $data['stats']->total_payments : 0 ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon paid">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>Total Paid</h3>
                <div class="stat-value">Rs. <?= isset($data['stats']->total_paid) ? number_format($data['stats']->total_paid, 2) : '0.00' ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>Pending Amount</h3>
                <div class="stat-value">Rs. <?= isset($data['stats']->pending_amount) ? number_format($data['stats']->pending_amount, 2) : '0.00' ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon overdue">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <h3>Overdue Amount</h3>
                <div class="stat-value">Rs. <?= isset($data['stats']->overdue_amount) ? number_format($data['stats']->overdue_amount, 2) : '0.00' ?></div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchPayments" placeholder="Search by client name, invoice number, or site...">
            </div>
            
            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                <option value="overdue">Overdue</option>
            </select>
            
            <select id="monthFilter" class="filter-select">
                <option value="">All Months</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>

            <button class="export-btn" onclick="exportPayments()">
                <i class="fas fa-download"></i>
                Export to CSV
            </button>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="payments-table-section">
        <div class="table-container">
            <table class="payments-table" id="paymentsTable">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Client</th>
                        <th>Site</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="paymentsTableBody">
                    <?php if (!empty($data['payments'])): ?>
                        <?php foreach($data['payments'] as $payment): ?>
                            <tr data-payment-id="<?= $payment->id ?>" 
                                data-status="<?= strtolower($payment->status) ?>" 
                                data-month="<?= date('n', strtotime($payment->payment_date)) ?>">
                                <td>
                                    <span class="invoice-number"><?= htmlspecialchars($payment->invoice_number) ?></span>
                                </td>
                                <td>
                                    <div class="client-info">
                                        <div class="client-avatar">
                                            <?= strtoupper(substr($payment->client_name ?? 'C', 0, 1)) ?>
                                        </div>
                                        <div class="client-details">
                                            <span class="client-name"><?= htmlspecialchars($payment->client_name ?? 'N/A') ?></span>
                                            <span class="client-email"><?= htmlspecialchars($payment->client_email ?? '') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="site-name">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php 
                                        if (!empty($payment->site_name)) {
                                            echo htmlspecialchars($payment->site_name);
                                        } elseif (!empty($payment->package_request_site_name)) {
                                            echo htmlspecialchars($payment->package_request_site_name);
                                            echo ' <span style="color: #ff9800; font-size: 11px; font-weight: 600;">(New Request)</span>';
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="payment-amount">Rs. <?= number_format($payment->amount, 2) ?></span>
                                </td>
                                <td class="date-cell">
                                    <?= $payment->payment_date ? date('d M Y', strtotime($payment->payment_date)) : '-' ?>
                                </td>
                                <td class="date-cell">
                                    <?= $payment->due_date ? date('d M Y', strtotime($payment->due_date)) : '-' ?>
                                </td>
                                <td>
                                    <span class="status <?= strtolower($payment->status) ?>">
                                        <?php if($payment->status === 'paid'): ?>
                                            <i class="fas fa-check-circle"></i>
                                        <?php elseif($payment->status === 'pending'): ?>
                                            <i class="fas fa-clock"></i>
                                        <?php else: ?>
                                            <i class="fas fa-exclamation-triangle"></i>
                                        <?php endif; ?>
                                        <?= ucfirst($payment->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <button class="action-btn view" onclick="viewPaymentDetails(<?= $payment->id ?>)" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <?php if($payment->status === 'paid'): ?>
                                            <button class="action-btn download" onclick="downloadReceipt(<?= $payment->id ?>)" title="Download Receipt">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="action-btn edit" onclick="editPayment(<?= $payment->id ?>)" title="Edit Payment">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-file-invoice"></i>
                                    <h3>No Payment Records Found</h3>
                                    <p>There are no payment records to display at the moment.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script>
// Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPayments');
    const statusFilter = document.getElementById('statusFilter');
    const monthFilter = document.getElementById('monthFilter');
    const tableBody = document.getElementById('paymentsTableBody');

    if (searchInput && tableBody) {
        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
        monthFilter.addEventListener('change', filterTable);
    }

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const monthValue = monthFilter.value;
        const rows = tableBody.getElementsByTagName('tr');

        for (let row of rows) {
            if (row.querySelector('.empty-state')) {
                continue;
            }

            const text = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');
            const month = row.getAttribute('data-month');

            let showRow = true;

            // Filter by search term
            if (searchTerm && !text.includes(searchTerm)) {
                showRow = false;
            }

            // Filter by status
            if (statusValue && status !== statusValue) {
                showRow = false;
            }

            // Filter by month
            if (monthValue && month !== monthValue) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        }
    }
});

// View Payment Details
function viewPaymentDetails(paymentId) {
    window.location.href = `<?php echo URL_ROOT; ?>/admin/viewPaymentDetails/${paymentId}`;
}

// Download Receipt
function downloadReceipt(paymentId) {
    window.location.href = `<?php echo URL_ROOT; ?>/admin/downloadPaymentReceipt/${paymentId}`;
}

// Edit Payment
function editPayment(paymentId) {
    window.location.href = `<?php echo URL_ROOT; ?>/admin/editPayment/${paymentId}`;
}

// Export to CSV
function exportPayments() {
    const searchTerm = document.getElementById('searchPayments').value;
    const status = document.getElementById('statusFilter').value;
    const month = document.getElementById('monthFilter').value;
    
    let url = `<?php echo URL_ROOT; ?>/admin/exportPayments?`;
    if (searchTerm) url += `search=${encodeURIComponent(searchTerm)}&`;
    if (status) url += `status=${status}&`;
    if (month) url += `month=${month}&`;
    
    window.location.href = url;
}
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>