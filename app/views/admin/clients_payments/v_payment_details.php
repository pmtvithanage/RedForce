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

    .main-content {
        padding: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: var(--bg-light);
        color: #333;
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: #e0e0e0;
    }

    .payment-details-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.paid {
        background: #e8f5e9;
        color: var(--success-color);
    }

    .status-badge.pending {
        background: #fff3e0;
        color: var(--warning-color);
    }

    .status-badge.overdue {
        background: #ffebee;
        color: var(--danger-color);
    }

    .card-body {
        padding: 30px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 30px;
    }

    .detail-group {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 12px;
    }

    .detail-label {
        font-size: 13px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .detail-value {
        font-size: 16px;
        color: #1a1a1a;
        font-weight: 500;
    }

    .amount-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-color);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border-color);
    }

    .actions-bar {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--info-color);
        color: white;
    }

    .btn-primary:hover {
        background: #1565c0;
    }

    .btn-success {
        background: var(--success-color);
        color: white;
    }

    .btn-success:hover {
        background: #1b5e20;
    }

    .btn-warning {
        background: var(--warning-color);
        color: white;
    }

    .btn-warning:hover {
        background: #e65100;
    }
</style>

<main class="main-content">
    <a href="<?= URL_ROOT ?>/admin/clients_payments" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Payments
    </a>

    <div class="payment-details-card">
        <div class="card-header">
            <h2><i class="fas fa-file-invoice"></i> Payment Details</h2>
            <span class="status-badge <?= strtolower($data['payment']->status) ?>">
                <?php if($data['payment']->status === 'paid'): ?>
                    <i class="fas fa-check-circle"></i>
                <?php elseif($data['payment']->status === 'pending'): ?>
                    <i class="fas fa-clock"></i>
                <?php else: ?>
                    <i class="fas fa-exclamation-triangle"></i>
                <?php endif; ?>
                <?= ucfirst($data['payment']->status) ?>
            </span>
        </div>

        <div class="card-body">
            <!-- Payment Information -->
            <h3 class="section-title">Payment Information</h3>
            <div class="details-grid">
                <div class="detail-group">
                    <div class="detail-label">Invoice Number</div>
                    <div class="detail-value"><?= htmlspecialchars($data['payment']->invoice_number) ?></div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Amount</div>
                    <div class="amount-value">Rs. <?= number_format($data['payment']->amount, 2) ?></div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Payment Date</div>
                    <div class="detail-value">
                        <?= $data['payment']->payment_date ? date('F d, Y', strtotime($data['payment']->payment_date)) : 'Not paid yet' ?>
                    </div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Due Date</div>
                    <div class="detail-value">
                        <?= $data['payment']->due_date ? date('F d, Y', strtotime($data['payment']->due_date)) : 'N/A' ?>
                    </div>
                </div>

                <?php if($data['payment']->payment_method): ?>
                <div class="detail-group">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value"><?= htmlspecialchars($data['payment']->payment_method) ?></div>
                </div>
                <?php endif; ?>

                <?php if($data['payment']->transaction_reference): ?>
                <div class="detail-group">
                    <div class="detail-label">Transaction Reference</div>
                    <div class="detail-value"><?= htmlspecialchars($data['payment']->transaction_reference) ?></div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Client Information -->
            <h3 class="section-title">Client Information</h3>
            <div class="details-grid">
                <div class="detail-group">
                    <div class="detail-label">Client Name</div>
                    <div class="detail-value"><?= htmlspecialchars($data['payment']->client_name ?? 'N/A') ?></div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Email</div>
                    <div class="detail-value"><?= htmlspecialchars($data['payment']->client_email ?? 'N/A') ?></div>
                </div>

                <?php if(isset($data['payment']->client_phone)): ?>
                <div class="detail-group">
                    <div class="detail-label">Phone</div>
                    <div class="detail-value"><?= htmlspecialchars($data['payment']->client_phone) ?></div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Site Information -->
            <h3 class="section-title">Site Information</h3>
            <div class="details-grid">
                <div class="detail-group">
                    <div class="detail-label">Site Name</div>
                    <div class="detail-value">
                        <?php 
                        if (!empty($data['payment']->site_name)) {
                            echo htmlspecialchars($data['payment']->site_name);
                        } elseif (!empty($data['payment']->package_request_site_name)) {
                            echo htmlspecialchars($data['payment']->package_request_site_name) . ' <span style="color: #999; font-size: 12px;">(Package Request)</span>';
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </div>
                </div>

                <?php if(isset($data['payment']->site_address)): ?>
                <div class="detail-group">
                    <div class="detail-label">Address</div>
                    <div class="detail-value">
                        <?= htmlspecialchars($data['payment']->site_address) ?>, 
                        <?= htmlspecialchars($data['payment']->city ?? '') ?>, 
                        <?= htmlspecialchars($data['payment']->district ?? '') ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(!empty($data['payment']->package_request_package_name)): ?>
                <div class="detail-group">
                    <div class="detail-label">Package Requested</div>
                    <div class="detail-value"><?= htmlspecialchars($data['payment']->package_request_package_name) ?></div>
                </div>
                <?php endif; ?>
            </div>

            <?php if(isset($data['payment']->description) && !empty($data['payment']->description)): ?>
            <!-- Description -->
            <h3 class="section-title">Description</h3>
            <p style="color: #666; line-height: 1.6;"><?= nl2br(htmlspecialchars($data['payment']->description)) ?></p>
            <?php endif; ?>

            <!-- Actions -->
            <div class="actions-bar">
                <a href="<?= URL_ROOT ?>/admin/editPayment/<?= $data['payment']->id ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Payment
                </a>
                <?php if($data['payment']->status === 'paid'): ?>
                <a href="<?= URL_ROOT ?>/admin/downloadPaymentReceipt/<?= $data['payment']->id ?>" class="btn btn-success">
                    <i class="fas fa-download"></i> Download Receipt
                </a>
                <?php endif; ?>
                <a href="<?= URL_ROOT ?>/admin/clients_payments" class="btn btn-primary">
                    <i class="fas fa-list"></i> View All Payments
                </a>
            </div>
        </div>
    </div>
</main>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
