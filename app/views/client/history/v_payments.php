<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<style>
    :root {
        --accent: #a40000;
        --accent-light: #c41e1e;
        --shadow: 0 6px 18px rgba(20,20,40,0.06);
        --radius: 12px;
    }

    /* Hide scrollbars while maintaining scroll functionality */
    * {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
    }

    *::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    .payments-container {
        padding: 24px;
        max-width: 1400px;
        margin: 0 auto;
        overflow-x: hidden;
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
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon.total {
        background: #e3f2fd;
        color: #1976d2;
    }

    .stat-icon.paid {
        background: #e8f5e9;
        color: #388e3c;
    }

    .stat-icon.pending {
        background: #fff3e0;
        color: #f57c00;
    }

    .stat-icon.overdue {
        background: #ffebee;
        color: #d32f2f;
    }

    .stat-icon .material-symbols-outlined {
        color: inherit;
        font-size: 24px;
    }

    .stat-info h3 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
        color: #666;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .stat-content h3 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .stat-content p {
        margin: 5px 0 0 0;
        font-size: 14px;
        color: #666;
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

    /* Next Payment Section */
    .next-payment-section {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
        margin-bottom: 32px;
    }

    .next-payment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }

    .next-payment-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
    }

    .section-toggle-icon {
        transition: transform 0.3s ease;
        color: #666;
    }

    .section-toggle-icon.expanded {
        transform: rotate(180deg);
    }

    .next-payment-header .material-symbols-outlined {
        color: var(--accent);
        font-size: 28px;
    }

    .due-date-badge {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .site-payment-card {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .site-payment-card:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .site-payment-header {
        display: flex;
        align-items: start;
        gap: 12px;
        margin-bottom: 16px;
    }

    .site-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .site-icon .material-symbols-outlined {
        color: white;
        font-size: 24px;
    }

    .site-details h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .site-details p {
        margin: 0;
        font-size: 13px;
        color: #666;
    }

    .package-info {
        background: white;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 12px;
    }

    .package-name {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .package-name .material-symbols-outlined {
        font-size: 18px;
        color: var(--accent);
    }

    .package-personnel {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .personnel-item {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #666;
    }

    .personnel-item .material-symbols-outlined {
        font-size: 16px;
        color: #999;
    }

    .site-amount {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid #e0e0e0;
    }

    .site-amount-label {
        font-size: 13px;
        color: #666;
        font-weight: 600;
    }

    .site-amount-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--accent);
    }

    .site-invoice {
        background: white;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .invoice-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }

    .invoice-row:last-child {
        border-bottom: none;
    }

    .invoice-label {
        color: #666;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .invoice-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .invoice-pending {
        color: #ff9800;
        font-style: italic;
        font-size: 12px;
    }

    .invoice-subtotal {
        background: #f8f9fa;
        margin: 0 -16px -16px;
        padding: 16px;
        border-radius: 0 0 8px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
    }

    .invoice-subtotal-label {
        color: #1a1a1a;
        font-size: 15px;
    }

    .invoice-subtotal-value {
        color: var(--accent);
        font-size: 18px;
    }

    .total-next-payment {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
        border-radius: 10px;
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-next-payment-label {
        color: white;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .total-next-payment-label .material-symbols-outlined {
        font-size: 24px;
    }

    .total-next-payment-value {
        color: white;
        font-size: 32px;
        font-weight: 700;
    }

    /* Pending Requests Section */
    .pending-requests-section {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
        margin-bottom: 32px;
    }

    .pending-requests-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }

    .pending-requests-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
    }

    .pending-requests-header .material-symbols-outlined {
        color: #ff9800;
        font-size: 28px;
    }

    .pending-badge {
        background: #ff9800;
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .request-card {
        background: #fff9e6;
        border: 2px solid #ffe082;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }

    .request-card:hover {
        border-color: #ff9800;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.2);
        transform: translateY(-2px);
    }

    .request-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 16px;
    }

    .request-info h4 {
        margin: 0 0 4px 0;
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .request-info h4 .material-symbols-outlined {
        color: #ff9800;
        font-size: 22px;
    }

    .request-info p {
        margin: 0;
        font-size: 13px;
        color: #666;
    }

    .request-status-badge {
        background: #ff9800;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .request-details {
        background: white;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .request-details-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .request-details-row:last-child {
        border-bottom: none;
    }

    .request-details-label {
        font-size: 13px;
        color: #666;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .request-details-label .material-symbols-outlined {
        font-size: 18px;
        color: #999;
    }

    .request-details-value {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .request-price {
        font-size: 20px;
        font-weight: 700;
        color: #ff9800;
    }

    .request-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid #ffe082;
    }

    .request-date {
        font-size: 12px;
        color: #999;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .request-date .material-symbols-outlined {
        font-size: 16px;
    }

    .btn-delete-request {
        background: #ffebee;
        color: #c62828;
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

    .btn-delete-request:hover {
        background: #ef5350;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(198, 40, 40, 0.3);
    }

    .btn-delete-request .material-symbols-outlined {
        font-size: 18px;
    }

    .btn-pay-now {
        width: 100%;
        padding: 18px;
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .btn-pay-now:hover {
        background: linear-gradient(135deg, #45a049 0%, #388e3c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(76, 175, 80, 0.4);
    }

    .btn-pay-now:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-pay-now .material-symbols-outlined {
        font-size: 24px;
    }

    .section-content {
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.4s ease, opacity 0.3s ease;
        opacity: 0;
    }

    .section-content.expanded {
        max-height: 10000px;
        opacity: 1;
    }

    .payments-content-wrapper {
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.4s ease, opacity 0.3s ease;
        opacity: 0;
    }

    .payments-content-wrapper.expanded {
        max-height: 10000px;
        opacity: 1;
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
                    <div class="stat-value">LKR <?php echo number_format($data['total_paid'] ?? 0, 2); ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon paid">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div class="stat-info">
                    <h3>Paid Bills</h3>
                    <div class="stat-value"><?php echo $data['paid_count'] ?? 0; ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon pending">
                    <span class="material-symbols-outlined">pending</span>
                </div>
                <div class="stat-info">
                    <h3>Pending</h3>
                    <div class="stat-value"><?php echo $data['pending_count'] ?? 0; ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon overdue">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <div class="stat-info">
                    <h3>Overdue</h3>
                    <div class="stat-value"><?php echo $data['overdue_count'] ?? 0; ?></div>
                </div>
            </div>
        </div>

        <!-- Pending Package Requests -->
        <?php if (!empty($data['pending_requests'])): ?>
        <div class="pending-requests-section">
            <div class="pending-requests-header" onclick="toggleSection('pendingRequests')">
                <h3>
                    <span class="material-symbols-outlined">pending_actions</span>
                    Pending Package Requests
                    <span class="material-symbols-outlined section-toggle-icon" id="pendingRequestsIcon">expand_more</span>
                </h3>
                <div class="pending-badge">
                    <span class="material-symbols-outlined">schedule</span>
                    <?php echo count($data['pending_requests']); ?> Request<?php echo count($data['pending_requests']) > 1 ? 's' : ''; ?>
                </div>
            </div>

            <div class="section-content" id="pendingRequestsContent">
            <?php foreach ($data['pending_requests'] as $request): ?>
            <div class="request-card">
                <div class="request-card-header">
                    <div class="request-info">
                        <h4>
                            <span class="material-symbols-outlined">inventory_2</span>
                            <?php echo htmlspecialchars($request->package_name ?? ''); ?>
                        </h4>
                        <p><strong><?php echo htmlspecialchars($request->site_name ?? ''); ?><?php if (!empty($request->comments)): ?> <span style="color: #4caf50;">(<?php echo htmlspecialchars($request->comments); ?>)</span><?php endif; ?></strong></p>
                        <p><?php echo htmlspecialchars($request->site_address ?? ''); ?></p>
                    </div>
                    <div class="request-status-badge">
                        <span class="material-symbols-outlined">hourglass_empty</span>
                        Pending Approval
                    </div>
                </div>

                <div class="request-details">
                    <?php if ($request->number_of_officers != 0): ?>
                    <div class="request-details-row">
                        <span class="request-details-label">
                            <span class="material-symbols-outlined" style="color: <?php echo $request->number_of_officers > 0 ? '#4caf50' : '#c62828'; ?>;"><?php echo $request->number_of_officers > 0 ? 'add_circle' : 'remove_circle'; ?></span>
                            Security Officers
                        </span>
                        <span class="request-details-value" style="color: <?php echo $request->number_of_officers > 0 ? '#4caf50' : '#c62828'; ?>;"><?php echo $request->number_of_officers > 0 ? '+' : ''; ?><?php echo $request->number_of_officers; ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($request->number_of_supervisors != 0): ?>
                    <div class="request-details-row">
                        <span class="request-details-label">
                            <span class="material-symbols-outlined" style="color: <?php echo $request->number_of_supervisors > 0 ? '#4caf50' : '#c62828'; ?>;"><?php echo $request->number_of_supervisors > 0 ? 'add_circle' : 'remove_circle'; ?></span>
                            Supervisors
                        </span>
                        <span class="request-details-value" style="color: <?php echo $request->number_of_supervisors > 0 ? '#4caf50' : '#c62828'; ?>;"><?php echo $request->number_of_supervisors > 0 ? '+' : ''; ?><?php echo $request->number_of_supervisors; ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($request->number_of_caretakers != 0): ?>
                    <div class="request-details-row">
                        <span class="request-details-label">
                            <span class="material-symbols-outlined" style="color: <?php echo $request->number_of_caretakers > 0 ? '#4caf50' : '#c62828'; ?>;"><?php echo $request->number_of_caretakers > 0 ? 'add_circle' : 'remove_circle'; ?></span>
                            Caretakers
                        </span>
                        <span class="request-details-value" style="color: <?php echo $request->number_of_caretakers > 0 ? '#4caf50' : '#c62828'; ?>;"><?php echo $request->number_of_caretakers > 0 ? '+' : ''; ?><?php echo $request->number_of_caretakers; ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="request-details-row">
                        <span class="request-details-label">
                            <span class="material-symbols-outlined">payments</span>
                            Monthly Cost Change
                        </span>
                        <span class="request-details-value request-price" style="color: <?php echo $request->package_price >= 0 ? '#4caf50' : '#c62828'; ?>;"><?php echo $request->package_price > 0 ? '+' : ''; ?>LKR <?php echo number_format($request->package_price, 2); ?></span>
                    </div>
                </div>

                <div class="request-footer">
                    <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center;">
                        <div class="request-date">
                            <span class="material-symbols-outlined">event</span>
                            Submitted: <?php echo date('M d, Y', strtotime($request->submitted_date)); ?>
                        </div>
                        <div class="request-date">
                            <span class="material-symbols-outlined">calendar_today</span>
                            Start Date: <?php echo date('M d, Y', strtotime($request->start_date)); ?>
                        </div>
                    </div>
                    <button class="btn-delete-request" onclick="deletePackageRequest(<?php echo $request->id; ?>, '<?php echo htmlspecialchars($request->site_name ?? '', ENT_QUOTES); ?>')">
                        <span class="material-symbols-outlined">delete</span>
                        Delete Request
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Next Payment Information -->
        <div class="next-payment-section">
            <div class="next-payment-header" onclick="toggleSection('nextPayment')">
                <h3>
                    <span class="material-symbols-outlined">calendar_month</span>
                    Next Payment Due
                    <span class="material-symbols-outlined section-toggle-icon" id="nextPaymentIcon">expand_more</span>
                </h3>
                <div class="due-date-badge">
                    <span class="material-symbols-outlined">schedule</span>
                    Due: <?php echo date('M d, Y', strtotime('first day of next month')); ?>
                </div>
            </div>

            <div class="section-content" id="nextPaymentContent">
            <?php 
            $grandTotal = 0;
            
            // Group pending requests by site
            $pendingBySite = [];
            if (!empty($data['pending_requests'])) {
                foreach ($data['pending_requests'] as $request) {
                    $siteKey = $request->site_id ?? $request->site_name;
                    if (!isset($pendingBySite[$siteKey])) {
                        $pendingBySite[$siteKey] = [];
                    }
                    $pendingBySite[$siteKey][] = $request;
                }
            }
            ?>
            
            <?php if (!empty($data['active_sites']) || !empty($data['pending_requests'])): ?>
            <div class="sites-grid">
                <?php 
                // Process active sites
                foreach ($data['active_sites'] as $site): 
                    $siteSubtotal = 0;
                    
                    // Calculate existing cost from actual assigned personnel
                    $existingCost = 0;
                    if (isset($site->officer_price) && isset($site->number_of_officers)) {
                        $existingCost += ($site->number_of_officers * $site->officer_price);
                        $existingCost += (($site->number_of_supervisors ?? 0) * ($site->supervisor_price ?? 0));
                        $existingCost += (($site->number_of_caretakers ?? 0) * ($site->caretaker_price ?? 0));
                    } else {
                        // Fallback to package price if detailed pricing not available
                        $existingCost = $site->package_price ?? 0;
                    }
                    
                    $siteSubtotal += $existingCost;
                    
                    // Check for pending requests for this site
                    $siteKey = $site->id ?? $site->site_name;
                    $sitePendingRequests = $pendingBySite[$siteKey] ?? [];
                    $pendingTotal = 0;
                    foreach ($sitePendingRequests as $pending) {
                        $pendingTotal += $pending->package_price ?? 0;
                    }
                    $siteSubtotal += $pendingTotal;
                    $grandTotal += $siteSubtotal;
                ?>
                <div class="site-payment-card">
                    <div class="site-payment-header">
                        <div class="site-icon">
                            <span class="material-symbols-outlined">location_on</span>
                        </div>
                        <div class="site-details">
                            <h4><?php echo htmlspecialchars($site->site_name ?? ''); ?></h4>
                            <p><?php echo htmlspecialchars($site->address ?? ''); ?></p>
                        </div>
                    </div>

                    <div class="site-invoice">
                        <h5 style="font-size: 13px; font-weight: 700; color: #666; text-transform: uppercase; margin: 0 0 12px 0; letter-spacing: 0.5px;">
                            Invoice Breakdown
                        </h5>
                        
                        <!-- Existing Package Header -->
                        <div style="font-size: 12px; font-weight: 700; color: #4caf50; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                            <?php echo htmlspecialchars($site->package_name ?? 'Package'); ?> - Current
                        </div>
                        
                        <!-- Detailed Officer Costs -->
                        <?php 
                        $hasDetailedPricing = isset($site->officer_price) || isset($site->supervisor_price) || isset($site->caretaker_price);
                        if ($hasDetailedPricing):
                        ?>
                            <?php if ($site->number_of_officers > 0): ?>
                            <div class="invoice-row">
                                <div class="invoice-label" style="padding-left: 22px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px; color: #666;">badge</span>
                                    <?php echo $site->number_of_officers; ?> Officer<?php echo $site->number_of_officers > 1 ? 's' : ''; ?>
                                    <?php if (isset($site->officer_price) && $site->officer_price > 0): ?>
                                        × LKR <?php echo number_format($site->officer_price, 0); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="invoice-value">
                                    LKR <?php 
                                        $officerCost = isset($site->officer_price) ? ($site->number_of_officers * $site->officer_price) : 0;
                                        echo number_format($officerCost, 2); 
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($site->number_of_supervisors > 0): ?>
                            <div class="invoice-row">
                                <div class="invoice-label" style="padding-left: 22px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px; color: #666;">shield_person</span>
                                    <?php echo $site->number_of_supervisors; ?> Supervisor<?php echo $site->number_of_supervisors > 1 ? 's' : ''; ?>
                                    <?php if (isset($site->supervisor_price) && $site->supervisor_price > 0): ?>
                                        × LKR <?php echo number_format($site->supervisor_price, 0); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="invoice-value">
                                    LKR <?php 
                                        $supervisorCost = isset($site->supervisor_price) ? ($site->number_of_supervisors * $site->supervisor_price) : 0;
                                        echo number_format($supervisorCost, 2); 
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($site->number_of_caretakers > 0): ?>
                            <div class="invoice-row">
                                <div class="invoice-label" style="padding-left: 22px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px; color: #666;">supervised_user_circle</span>
                                    <?php echo $site->number_of_caretakers; ?> Caretaker<?php echo $site->number_of_caretakers > 1 ? 's' : ''; ?>
                                    <?php if (isset($site->caretaker_price) && $site->caretaker_price > 0): ?>
                                        × LKR <?php echo number_format($site->caretaker_price, 0); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="invoice-value">
                                    LKR <?php 
                                        $caretakerCost = isset($site->caretaker_price) ? ($site->number_of_caretakers * $site->caretaker_price) : 0;
                                        echo number_format($caretakerCost, 2); 
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Show summary if detailed pricing not available -->
                            <div class="invoice-row" style="padding-left: 22px;">
                                <div class="invoice-label">
                                    <div style="font-size: 11px; color: #999;">
                                        <?php 
                                        $parts = [];
                                        if ($site->number_of_officers > 0) $parts[] = $site->number_of_officers . ' Officer' . ($site->number_of_officers > 1 ? 's' : '');
                                        if ($site->number_of_supervisors > 0) $parts[] = $site->number_of_supervisors . ' Supervisor' . ($site->number_of_supervisors > 1 ? 's' : '');
                                        if ($site->number_of_caretakers > 0) $parts[] = $site->number_of_caretakers . ' Caretaker' . ($site->number_of_caretakers > 1 ? 's' : '');
                                        echo implode(', ', $parts);
                                        ?>
                                    </div>
                                </div>
                                <div class="invoice-value">
                                    LKR <?php echo number_format($existingCost, 2); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Existing Package Total -->
                        <div class="invoice-row" style="background: #f0f9f0; margin: 8px -16px 8px; padding: 10px 16px; font-weight: 600;">
                            <div class="invoice-label">Current Package Total</div>
                            <div class="invoice-value" style="color: #4caf50;">LKR <?php echo number_format($existingCost, 2); ?></div>
                        </div>

                        <!-- Pending Changes if any -->
                        <?php if (!empty($sitePendingRequests)): ?>
                            <!-- Pending Section Header -->
                            <div style="font-size: 12px; font-weight: 700; color: #ff9800; margin: 16px 0 8px 0; display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">hourglass_empty</span>
                                Pending Changes
                            </div>
                            
                            <?php 
                            $pendingItemsTotal = 0;
                            foreach ($sitePendingRequests as $pending): 
                                $hasPendingDetailedPricing = isset($pending->officer_price) || isset($pending->supervisor_price) || isset($pending->caretaker_price);
                            ?>
                                <?php if ($hasPendingDetailedPricing): ?>
                                    <!-- Detailed breakdown for pending request -->
                                    <?php if ($pending->number_of_officers != 0): ?>
                                    <div class="invoice-row">
                                        <div class="invoice-label" style="padding-left: 22px;">
                                            <span class="material-symbols-outlined" style="font-size: 16px; color: <?php echo $pending->number_of_officers > 0 ? '#ff9800' : '#c62828'; ?>;"><?php echo $pending->number_of_officers > 0 ? 'badge' : 'remove_circle'; ?></span>
                                            <?php echo $pending->number_of_officers > 0 ? '+' : ''; ?><?php echo $pending->number_of_officers; ?> Officer<?php echo abs($pending->number_of_officers) > 1 ? 's' : ''; ?>
                                            <?php if (isset($pending->officer_price) && $pending->officer_price > 0): ?>
                                                × LKR <?php echo number_format($pending->officer_price, 0); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="invoice-value" style="color: <?php echo $pending->number_of_officers > 0 ? '#ff9800' : '#c62828'; ?>;">
                                            <?php 
                                                $cost = isset($pending->officer_price) ? ($pending->number_of_officers * $pending->officer_price) : 0;
                                                $pendingItemsTotal += $cost;
                                                echo ($cost > 0 ? '+' : '') . 'LKR ' . number_format($cost, 2); 
                                            ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($pending->number_of_supervisors != 0): ?>
                                    <div class="invoice-row">
                                        <div class="invoice-label" style="padding-left: 22px;">
                                            <span class="material-symbols-outlined" style="font-size: 16px; color: <?php echo $pending->number_of_supervisors > 0 ? '#ff9800' : '#c62828'; ?>;"><?php echo $pending->number_of_supervisors > 0 ? 'shield_person' : 'remove_circle'; ?></span>
                                            <?php echo $pending->number_of_supervisors > 0 ? '+' : ''; ?><?php echo $pending->number_of_supervisors; ?> Supervisor<?php echo abs($pending->number_of_supervisors) > 1 ? 's' : ''; ?>
                                            <?php if (isset($pending->supervisor_price) && $pending->supervisor_price > 0): ?>
                                                × LKR <?php echo number_format($pending->supervisor_price, 0); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="invoice-value" style="color: <?php echo $pending->number_of_supervisors > 0 ? '#ff9800' : '#c62828'; ?>;">
                                            <?php 
                                                $cost = isset($pending->supervisor_price) ? ($pending->number_of_supervisors * $pending->supervisor_price) : 0;
                                                $pendingItemsTotal += $cost;
                                                echo ($cost > 0 ? '+' : '') . 'LKR ' . number_format($cost, 2); 
                                            ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($pending->number_of_caretakers != 0): ?>
                                    <div class="invoice-row">
                                        <div class="invoice-label" style="padding-left: 22px;">
                                            <span class="material-symbols-outlined" style="font-size: 16px; color: <?php echo $pending->number_of_caretakers > 0 ? '#ff9800' : '#c62828'; ?>;"><?php echo $pending->number_of_caretakers > 0 ? 'supervised_user_circle' : 'remove_circle'; ?></span>
                                            <?php echo $pending->number_of_caretakers > 0 ? '+' : ''; ?><?php echo $pending->number_of_caretakers; ?> Caretaker<?php echo abs($pending->number_of_caretakers) > 1 ? 's' : ''; ?>
                                            <?php if (isset($pending->caretaker_price) && $pending->caretaker_price > 0): ?>
                                                × LKR <?php echo number_format($pending->caretaker_price, 0); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="invoice-value" style="color: <?php echo $pending->number_of_caretakers > 0 ? '#ff9800' : '#c62828'; ?>;">
                                            <?php 
                                                $cost = isset($pending->caretaker_price) ? ($pending->number_of_caretakers * $pending->caretaker_price) : 0;
                                                $pendingItemsTotal += $cost;
                                                echo ($cost > 0 ? '+' : '') . 'LKR ' . number_format($cost, 2); 
                                            ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- Summary for pending request if no detailed pricing -->
                                    <div class="invoice-row" style="background: #fff9e6; margin: 0 -16px; padding: 10px 16px;">
                                        <div class="invoice-label">
                                            <span class="material-symbols-outlined" style="font-size: 16px; color: #ff9800;">inventory_2</span>
                                            <div>
                                                <div><?php echo htmlspecialchars($pending->package_name ?? ''); ?> <span class="invoice-pending">(Pending)</span></div>
                                                <div style="font-size: 11px; color: #999;">
                                                    <?php 
                                                    $parts = [];
                                                    if ($pending->number_of_officers != 0) $parts[] = ($pending->number_of_officers > 0 ? '+' : '') . $pending->number_of_officers . ' Officer' . (abs($pending->number_of_officers) > 1 ? 's' : '');
                                                    if ($pending->number_of_supervisors != 0) $parts[] = ($pending->number_of_supervisors > 0 ? '+' : '') . $pending->number_of_supervisors . ' Supervisor' . (abs($pending->number_of_supervisors) > 1 ? 's' : '');
                                                    if ($pending->number_of_caretakers != 0) $parts[] = ($pending->number_of_caretakers > 0 ? '+' : '') . $pending->number_of_caretakers . ' Caretaker' . (abs($pending->number_of_caretakers) > 1 ? 's' : '');
                                                    echo implode(', ', $parts);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invoice-value" style="color: <?php echo ($pending->package_price ?? 0) >= 0 ? '#ff9800' : '#c62828'; ?>;">
                                            <?php 
                                                $cost = $pending->package_price ?? 0;
                                                $pendingItemsTotal += $cost;
                                                echo ($cost > 0 ? '+' : '') . 'LKR ' . number_format($cost, 2); 
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            
                            <!-- Pending Changes Total -->
                            <div class="invoice-row" style="background: <?php echo $pendingItemsTotal >= 0 ? '#fff3e0' : '#ffebee'; ?>; margin: 8px -16px 8px; padding: 10px 16px; font-weight: 600;">
                                <div class="invoice-label">Pending Changes Total</div>
                                <div class="invoice-value" style="color: <?php echo $pendingItemsTotal >= 0 ? '#ff9800' : '#c62828'; ?>;"><?php echo ($pendingItemsTotal > 0 ? '+' : ''); ?>LKR <?php echo number_format($pendingItemsTotal, 2); ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- Subtotal -->
                        <div class="invoice-subtotal" style="background: <?php echo $siteSubtotal >= 0 ? '#f8f9fa' : '#ffebee'; ?>;">
                            <span class="invoice-subtotal-label">Site Subtotal:</span>
                            <span class="invoice-subtotal-value" style="color: <?php echo $siteSubtotal >= 0 ? 'var(--accent)' : '#c62828'; ?>;"><?php echo ($siteSubtotal > 0 ? '+' : ''); ?>LKR <?php echo number_format($siteSubtotal, 2); ?></span>
                        </div>
                    </div>
                </div>
                <?php 
                    // Remove processed pending requests from array
                    unset($pendingBySite[$siteKey]);
                endforeach; 
                ?>

                <?php 
                // Process any remaining pending requests for new sites
                foreach ($pendingBySite as $siteKey => $pendingRequests):
                    if (empty($pendingRequests)) continue;
                    
                    $firstRequest = $pendingRequests[0];
                    $siteSubtotal = 0;
                ?>
                <div class="site-payment-card" style="background: #fff9e6; border-color: #ffe082;">
                    <div class="site-payment-header">
                        <div class="site-icon" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);">
                            <span class="material-symbols-outlined">add_location</span>
                        </div>
                        <div class="site-details">
                            <h4><?php echo htmlspecialchars($firstRequest->site_name ?? ''); ?> <?php if (!empty($firstRequest->comments)): ?><span style="font-size: 12px; color: #4caf50; font-weight: 600;">(<?php echo htmlspecialchars($firstRequest->comments); ?>)</span><?php else: ?><span style="font-size: 12px; color: #ff9800; font-weight: 600;">(New)</span><?php endif; ?></h4>
                            <p><?php echo htmlspecialchars($firstRequest->site_address ?? ''); ?></p>
                        </div>
                    </div>

                    <div class="site-invoice">
                        <h5 style="font-size: 13px; font-weight: 700; color: #ff9800; text-transform: uppercase; margin: 0 0 12px 0; letter-spacing: 0.5px;">
                            Pending Approval
                        </h5>
                        
                        <?php 
                        foreach ($pendingRequests as $pending): 
                            $hasPendingDetailedPricing = isset($pending->officer_price) || isset($pending->supervisor_price) || isset($pending->caretaker_price);
                        ?>
                            <?php if ($hasPendingDetailedPricing): ?>
                                <!-- Detailed breakdown for pending request -->
                                <?php if ($pending->number_of_officers != 0): ?>
                                <div class="invoice-row">
                                    <div class="invoice-label" style="padding-left: 22px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px; color: <?php echo $pending->number_of_officers > 0 ? '#ff9800' : '#c62828'; ?>;"><?php echo $pending->number_of_officers > 0 ? 'badge' : 'remove_circle'; ?></span>
                                        <?php echo $pending->number_of_officers > 0 ? '+' : ''; ?><?php echo $pending->number_of_officers; ?> Officer<?php echo abs($pending->number_of_officers) > 1 ? 's' : ''; ?>
                                        <?php if (isset($pending->officer_price) && $pending->officer_price > 0): ?>
                                            × LKR <?php echo number_format($pending->officer_price, 0); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="invoice-value" style="color: <?php echo $pending->number_of_officers > 0 ? '#ff9800' : '#c62828'; ?>;">
                                        <?php 
                                            $cost = isset($pending->officer_price) ? ($pending->number_of_officers * $pending->officer_price) : 0;
                                            $siteSubtotal += $cost;
                                            echo ($cost > 0 ? '+' : '') . 'LKR ' . number_format($cost, 2); 
                                        ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($pending->number_of_supervisors != 0): ?>
                                <div class="invoice-row">
                                    <div class="invoice-label" style="padding-left: 22px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px; color: <?php echo $pending->number_of_supervisors > 0 ? '#ff9800' : '#c62828'; ?>;"><?php echo $pending->number_of_supervisors > 0 ? 'shield_person' : 'remove_circle'; ?></span>
                                        <?php echo $pending->number_of_supervisors > 0 ? '+' : ''; ?><?php echo $pending->number_of_supervisors; ?> Supervisor<?php echo abs($pending->number_of_supervisors) > 1 ? 's' : ''; ?>
                                        <?php if (isset($pending->supervisor_price) && $pending->supervisor_price > 0): ?>
                                            × LKR <?php echo number_format($pending->supervisor_price, 0); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="invoice-value" style="color: <?php echo $pending->number_of_supervisors > 0 ? '#ff9800' : '#c62828'; ?>;">
                                        <?php 
                                            $cost = isset($pending->supervisor_price) ? ($pending->number_of_supervisors * $pending->supervisor_price) : 0;
                                            $siteSubtotal += $cost;
                                            echo ($cost > 0 ? '+' : '') . 'LKR ' . number_format($cost, 2); 
                                        ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($pending->number_of_caretakers != 0): ?>
                                <div class="invoice-row">
                                    <div class="invoice-label" style="padding-left: 22px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px; color: <?php echo $pending->number_of_caretakers > 0 ? '#ff9800' : '#c62828'; ?>;"><?php echo $pending->number_of_caretakers > 0 ? 'supervised_user_circle' : 'remove_circle'; ?></span>
                                        <?php echo $pending->number_of_caretakers > 0 ? '+' : ''; ?><?php echo $pending->number_of_caretakers; ?> Caretaker<?php echo abs($pending->number_of_caretakers) > 1 ? 's' : ''; ?>
                                        <?php if (isset($pending->caretaker_price) && $pending->caretaker_price > 0): ?>
                                            × LKR <?php echo number_format($pending->caretaker_price, 0); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="invoice-value" style="color: <?php echo $pending->number_of_caretakers > 0 ? '#ff9800' : '#c62828'; ?>;">
                                        <?php 
                                            $cost = isset($pending->caretaker_price) ? ($pending->number_of_caretakers * $pending->caretaker_price) : 0;
                                            $siteSubtotal += $cost;
                                            echo ($cost > 0 ? '+' : '') . 'LKR ' . number_format($cost, 2); 
                                        ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- Summary for pending request if no detailed pricing -->
                                <div class="invoice-row" style="background: #fffbf0; margin: 0 -16px; padding: 10px 16px;">
                                    <div class="invoice-label">
                                        <span class="material-symbols-outlined" style="font-size: 16px; color: #ff9800;">inventory_2</span>
                                        <div>
                                            <div><?php echo htmlspecialchars($pending->package_name ?? ''); ?></div>
                                            <div style="font-size: 11px; color: #999;">
                                                <?php 
                                                $parts = [];
                                                if ($pending->number_of_officers != 0) $parts[] = ($pending->number_of_officers > 0 ? '+' : '') . $pending->number_of_officers . ' Officer' . (abs($pending->number_of_officers) > 1 ? 's' : '');
                                                if ($pending->number_of_supervisors != 0) $parts[] = ($pending->number_of_supervisors > 0 ? '+' : '') . $pending->number_of_supervisors . ' Supervisor' . (abs($pending->number_of_supervisors) > 1 ? 's' : '');
                                                if ($pending->number_of_caretakers != 0) $parts[] = ($pending->number_of_caretakers > 0 ? '+' : '') . $pending->number_of_caretakers . ' Caretaker' . (abs($pending->number_of_caretakers) > 1 ? 's' : '');
                                                echo implode(', ', $parts);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invoice-value" style="color: <?php echo ($pending->package_price ?? 0) >= 0 ? '#ff9800' : '#c62828'; ?>;">
                                        <?php 
                                            $cost = $pending->package_price ?? 0;
                                            $siteSubtotal += $cost;
                                            echo ($cost > 0 ? '+' : '') . 'LKR ' . number_format($cost, 2); 
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php $grandTotal += $siteSubtotal; ?>

                        <!-- Subtotal -->
                        <div class="invoice-subtotal" style="background: <?php echo $siteSubtotal >= 0 ? '#fff9e6' : '#ffebee'; ?>;">
                            <span class="invoice-subtotal-label">Site Subtotal:</span>
                            <span class="invoice-subtotal-value" style="color: <?php echo $siteSubtotal >= 0 ? '#ff9800' : '#c62828'; ?>;"><?php echo ($siteSubtotal > 0 ? '+' : ''); ?>LKR <?php echo number_format($siteSubtotal, 2); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Grand Total -->
            <div class="total-next-payment" style="background: linear-gradient(135deg, <?php echo $grandTotal >= 0 ? 'var(--accent) 0%, var(--accent-light) 100%' : '#c62828 0%, #d32f2f 100%'; ?>);">
                <div class="total-next-payment-label">
                    <span class="material-symbols-outlined">payment</span>
                    Total Amount Due
                </div>
                <div class="total-next-payment-value">
                    LKR <?php echo number_format($grandTotal, 2); ?>
                </div>
            </div>
            
            <!-- Pay Now Button -->
            <?php if ($grandTotal > 0): ?>
            <button class="btn-pay-now" onclick="initiatePayment()" id="payNowBtn">
                <span class="material-symbols-outlined">credit_card</span>
                Pay Now - LKR <?php echo number_format($grandTotal, 2); ?>
            </button>
            <?php endif; ?>
            <?php else: ?>
            <div class="empty-state">
                <span class="material-symbols-outlined">info</span>
                <h3>No Active Sites</h3>
                <p>You don't have any active sites with payment due</p>
            </div>
            <?php endif; ?>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="payments-content">
            <div class="payments-header" onclick="toggleSection('paymentHistory')" style="cursor: pointer; user-select: none; margin-bottom: 0; padding-bottom: 24px;">
                <h2 style="display: flex; align-items: center; gap: 10px;">
                    Payment History
                    <span class="material-symbols-outlined section-toggle-icon" id="paymentHistoryIcon">expand_more</span>
                </h2>
            </div>

            <div class="payments-content-wrapper" id="paymentHistoryContent">
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
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script>
function toggleSection(sectionName) {
    const content = document.getElementById(sectionName + 'Content');
    const icon = document.getElementById(sectionName + 'Icon');
    
    if (content && icon) {
        content.classList.toggle('expanded');
        icon.classList.toggle('expanded');
    }
}

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

function showErrorMessage(message) {
    // Create error message container if it doesn't exist
    let errorContainer = document.getElementById('deleteErrorMessage');
    if (!errorContainer) {
        errorContainer = document.createElement('div');
        errorContainer.id = 'deleteErrorMessage';
        errorContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #ef5350 0%, #c62828 100%);
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            max-width: 400px;
            display: none;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            animation: slideIn 0.3s ease;
        `;
        document.body.appendChild(errorContainer);
    }
    
    errorContainer.innerHTML = `
        <span class="material-symbols-outlined" style="font-size: 24px;">error</span>
        <span>${message}</span>
    `;
    errorContainer.style.display = 'flex';
    
    setTimeout(() => {
        errorContainer.style.display = 'none';
    }, 5000);
}

function deletePackageRequest(requestId, siteName) {
    // Show loading state
    const btn = event.target.closest('.btn-delete-request');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined">hourglass_empty</span> Deleting...';
    btn.disabled = true;
    
    fetch(`<?php echo URL_ROOT; ?>/client/deletePackageRequest/${requestId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Remove the card with animation
            const card = btn.closest('.request-card');
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                card.remove();
                
                // Check if there are any remaining requests
                const remainingCards = document.querySelectorAll('.request-card');
                if (remainingCards.length === 0) {
                    // Reload page to hide the section
                    window.location.reload();
                } else {
                    // Update the badge count
                    const badge = document.querySelector('.pending-badge');
                    if (badge) {
                        const countText = badge.textContent.match(/\d+/);
                        const newCount = remainingCards.length;
                        badge.innerHTML = `<span class="material-symbols-outlined">schedule</span>${newCount} Request${newCount > 1 ? 's' : ''}`;
                    }
                }
            }, 300);
        } else {
            showErrorMessage('Error: ' + (data.message || 'Failed to delete request'));
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Failed to delete request: ' + error.message);
        btn.innerHTML = originalContent;
        btn.disabled = false;
    });
}

// PayHere Payment Integration
function initiatePayment() {
    const btn = document.getElementById('payNowBtn');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined">hourglass_empty</span> Processing...';
    btn.disabled = true;
    
    const totalAmount = <?php echo $grandTotal; ?>;
    
    // Prepare data
    const formData = new FormData();
    formData.append('amount', totalAmount);
    formData.append('item_name', 'Monthly Security Service Payment');
    
    // Fetch payment hash from backend
    fetch('<?php echo URL_ROOT; ?>/payment/generatePaymentHash', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Check for error in response
        if (data.error) {
            throw new Error(data.message || 'Failed to generate payment hash');
        }
        
        // Define PayHere event handlers
        payhere.onCompleted = function onCompleted(orderId) {
            console.log("Payment completed. OrderID:" + orderId);
            alert('Payment successful! Your payment has been processed.');
            window.location.reload();
        };

        payhere.onDismissed = function onDismissed() {
            console.log("Payment dismissed");
            btn.innerHTML = originalContent;
            btn.disabled = false;
        };

        payhere.onError = function onError(error) {
            console.log("Error:" + error);
            alert('Payment error occurred: ' + error);
            btn.innerHTML = originalContent;
            btn.disabled = false;
        };

        // Payment Object
        var payment = {
            sandbox: <?php echo PAYMENT_SANDBOX ? 'true' : 'false'; ?>,
            merchant_id: data.merchant_id,
            return_url: undefined,
            cancel_url: undefined,
            notify_url: '<?php echo URL_ROOT; ?>/payment/notify',
            order_id: data.order_id,
            items: data.item_name,
            amount: data.amount,
            currency: data.currency,
            hash: data.hash,
            first_name: "<?php echo isset($_SESSION['user_name']) ? explode(' ', $_SESSION['user_name'])[0] : 'Client'; ?>",
            last_name: "<?php echo isset($_SESSION['user_name']) && count(explode(' ', $_SESSION['user_name'])) > 1 ? explode(' ', $_SESSION['user_name'])[1] : 'User'; ?>",
            email: "<?php echo $_SESSION['user_email'] ?? 'client@redforce.com'; ?>",
            phone: "0771234567",
            address: "Colombo",
            city: "Colombo",
            country: "Sri Lanka"
        };

        // Launch PayHere Popup
        payhere.startPayment(payment);
    })
    .catch(error => {
        console.error('Error fetching payment hash:', error);
        alert('Failed to initiate payment. Please try again.');
        btn.innerHTML = originalContent;
        btn.disabled = false;
    });
}
</script>

<!-- PayHere SDK -->
<script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>

</main>
</div>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>