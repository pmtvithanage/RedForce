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
        max-width: 900px;
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

    .edit-form-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        padding: 24px;
    }

    .card-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--border-color);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-actions {
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
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-light);
    }

    .btn-secondary {
        background: var(--bg-light);
        color: #333;
        border: 1px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    .help-text {
        font-size: 12px;
        color: #666;
        margin-top: 4px;
    }
</style>

<main class="main-content">
    <a href="<?= URL_ROOT ?>/admin/viewPaymentDetails/<?= $data['payment']->id ?>" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Details
    </a>

    <?php flash('payment_error'); ?>

    <div class="edit-form-card">
        <div class="card-header">
            <h2><i class="fas fa-edit"></i> Edit Payment</h2>
        </div>

        <div class="card-body">
            <form action="<?= URL_ROOT ?>/admin/editPayment/<?= $data['payment']->id ?>" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="amount">Amount (Rs.) *</label>
                        <input type="number" step="0.01" id="amount" name="amount" 
                               value="<?= $data['payment']->amount ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="pending" <?= $data['payment']->status === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= $data['payment']->status === 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="overdue" <?= $data['payment']->status === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="payment_date">Payment Date</label>
                        <input type="date" id="payment_date" name="payment_date" 
                               value="<?= $data['payment']->payment_date ?>">
                        <div class="help-text">Leave empty if not yet paid</div>
                    </div>

                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" id="due_date" name="due_date" 
                               value="<?= $data['payment']->due_date ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select id="payment_method" name="payment_method">
                            <option value="">Select method</option>
                            <option value="Cash" <?= $data['payment']->payment_method === 'Cash' ? 'selected' : '' ?>>Cash</option>
                            <option value="Bank Transfer" <?= $data['payment']->payment_method === 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                            <option value="Cheque" <?= $data['payment']->payment_method === 'Cheque' ? 'selected' : '' ?>>Cheque</option>
                            <option value="Online Payment" <?= $data['payment']->payment_method === 'Online Payment' ? 'selected' : '' ?>>Online Payment</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="transaction_reference">Transaction Reference</label>
                        <input type="text" id="transaction_reference" name="transaction_reference" 
                               value="<?= htmlspecialchars($data['payment']->transaction_reference ?? '') ?>"
                               placeholder="e.g., Cheque number, transaction ID">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" 
                              placeholder="Additional notes or description"><?= htmlspecialchars($data['payment']->description ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Payment
                    </button>
                    <a href="<?= URL_ROOT ?>/admin/viewPaymentDetails/<?= $data['payment']->id ?>" 
                       class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
