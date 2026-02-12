<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<style>
    :root {
        --accent: #a40000;
        --accent-light: #c41e1e;
        --shadow: 0 6px 18px rgba(20,20,40,0.06);
        --radius: 12px;
    }

    .payments-container {
        padding: 24px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .payments-header {
        margin-bottom: 32px;
    }

    .payments-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .payments-header p {
        color: #666;
        font-size: 15px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    }

    .stat-icon.paid {
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    }

    .stat-icon.overdue {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
    }

    .stat-icon .material-symbols-outlined {
        color: white;
        font-size: 32px;
    }

    .stat-info h3 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .payments-content {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
    }

    .filters-bar {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
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
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
    }

    .search-box .material-symbols-outlined {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 20px;
    }

    .filter-select {
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--accent);
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
        overflow: hidden;
    }

    .payments-table thead {
        background: #f8f9fa;
    }

    .payments-table th {
        padding: 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .payments-table td {
        padding: 18px 16px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: #333;
    }

    .payments-table tbody tr {
        transition: background 0.2s ease;
    }

    .payments-table tbody tr:hover {
        background: #f8f9fa;
    }

    .status-badge {
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

    .status-badge.paid {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.pending {
        background: #fff3e0;
        color: #e65100;
    }

    .status-badge.overdue {
        background: #ffebee;
        color: #c62828;
    }

    .status-badge .material-symbols-outlined {
        font-size: 16px;
    }

    .payment-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #666;
    }

    .payment-amount {
        font-weight: 700;
        font-size: 15px;
        color: #1a1a1a;
    }

    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn.view {
        background: #e3f2fd;
        color: #1976d2;
    }

    .action-btn.view:hover {
        background: #bbdefb;
    }

    .action-btn.download {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .action-btn.download:hover {
        background: #e1bee7;
    }

    .action-btn .material-symbols-outlined {
        font-size: 18px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state .material-symbols-outlined {
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

    @media (max-width: 1024px) {
        .payments-table {
            display: block;
            overflow-x: auto;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filters-bar {
            flex-direction: column;
        }

        .search-box {
            width: 100%;
        }

        .payments-table th,
        .payments-table td {
            padding: 12px 8px;
            font-size: 13px;
        }
    }
</style>

<div class="shell">
    <?php flash('payment_success'); ?>
    <?php flash('payment_error'); ?>

    <div class="payments-container">
        <div class="payments-header">
            <h2>Payment History</h2>
            <p>View and manage your payment transactions</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <div class="stat-info">
                    <h3>Total Paid</h3>
                    <div class="stat-value">LKR <?php echo isset($data['total_paid']) ? number_format($data['total_paid'], 2) : '0.00'; ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon paid">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div class="stat-info">
                    <h3>Paid Bills</h3>
                    <div class="stat-value"><?php echo isset($data['paid_count']) ? $data['paid_count'] : '0'; ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon pending">
                    <span class="material-symbols-outlined">pending</span>
                </div>
                <div class="stat-info">
                    <h3>Pending</h3>
                    <div class="stat-value"><?php echo isset($data['pending_count']) ? $data['pending_count'] : '0'; ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon overdue">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <div class="stat-info">
                    <h3>Overdue</h3>
                    <div class="stat-value"><?php echo isset($data['overdue_count']) ? $data['overdue_count'] : '0'; ?></div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="payments-content">
            <div class="filters-bar">
                <div class="search-box">
                    <span class="material-symbols-outlined">search</span>
                    <input type="text" id="searchPayments" placeholder="Search by invoice number, site name...">
                </div>
                <select class="filter-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                </select>
                <select class="filter-select" id="monthFilter">
                    <option value="">All Months</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>

            <?php if (!empty($data['payments'])): ?>
            <table class="payments-table" id="paymentsTable">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Site</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['payments'] as $payment): ?>
                    <tr>
                        <td><span class="payment-id">#<?php echo htmlspecialchars($payment->invoice_number ?? $payment->id); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($payment->payment_date ?? $payment->created_at)); ?></td>
                        <td><?php echo htmlspecialchars($payment->site_name ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($payment->description ?? 'Monthly Payment'); ?></td>
                        <td><span class="payment-amount">LKR <?php echo number_format($payment->amount, 2); ?></span></td>
                        <td>
                            <?php 
                            $status = strtolower($payment->status ?? 'pending');
                            $statusIcon = $status === 'paid' ? 'check_circle' : ($status === 'pending' ? 'pending' : 'warning');
                            ?>
                            <span class="status-badge <?php echo $status; ?>">
                                <span class="material-symbols-outlined"><?php echo $statusIcon; ?></span>
                                <?php echo ucfirst($status); ?>
                            </span>
                        </td>
                        <td>
                            <button class="action-btn view" onclick="viewPayment(<?php echo $payment->id; ?>)">
                                <span class="material-symbols-outlined">visibility</span>
                                View
                            </button>
                            <?php if ($status === 'paid'): ?>
                            <button class="action-btn download" onclick="downloadReceipt(<?php echo $payment->id; ?>)">
                                <span class="material-symbols-outlined">download</span>
                                Receipt
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <span class="material-symbols-outlined">receipt_long</span>
                <h3>No Payment History</h3>
                <p>Your payment transactions will appear here</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPayments');
    const statusFilter = document.getElementById('statusFilter');
    const monthFilter = document.getElementById('monthFilter');
    const tableBody = document.querySelector('#paymentsTable tbody');

    if (searchInput && tableBody) {
        // Search functionality
        searchInput.addEventListener('input', filterTable);
        
        // Status filter
        if (statusFilter) {
            statusFilter.addEventListener('change', filterTable);
        }
        
        // Month filter
        if (monthFilter) {
            monthFilter.addEventListener('change', filterTable);
        }
    }

    function filterTable() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const selectedStatus = statusFilter ? statusFilter.value.toLowerCase() : '';
        const selectedMonth = monthFilter ? monthFilter.value : '';
        
        const rows = tableBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const invoiceNumber = row.cells[0].textContent.toLowerCase();
            const date = row.cells[1].textContent;
            const siteName = row.cells[2].textContent.toLowerCase();
            const description = row.cells[3].textContent.toLowerCase();
            const statusBadge = row.querySelector('.status-badge');
            const status = statusBadge ? statusBadge.classList[1] : '';
            
            // Parse month from date (assuming format: "Mon dd, yyyy")
            const rowMonth = new Date(date).getMonth() + 1;
            const rowMonthStr = rowMonth.toString().padStart(2, '0');
            
            const matchesSearch = invoiceNumber.includes(searchTerm) || 
                                siteName.includes(searchTerm) || 
                                description.includes(searchTerm);
            const matchesStatus = !selectedStatus || status === selectedStatus;
            const matchesMonth = !selectedMonth || rowMonthStr === selectedMonth;
            
            if (matchesSearch && matchesStatus && matchesMonth) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
});

function viewPayment(paymentId) {
    window.location.href = `<?php echo URL_ROOT; ?>/client/viewPayment/${paymentId}`;
}

function downloadReceipt(paymentId) {
    window.location.href = `<?php echo URL_ROOT; ?>/client/downloadReceipt/${paymentId}`;
}
</script>

</main>
</div>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>