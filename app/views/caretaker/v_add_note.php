<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<!-- Import global stylesheet -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<!-- Import Google Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
    /* ---------- Form Section ---------- */
    .application-form {
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
    }

    .field {
        margin-bottom: 15px;
    }

    .field-label {
        margin-bottom: 6px;
        font-weight: 500;
        color: var(--text-color);
    }

    .field-input,
    .field-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
    }

    .field-input:focus,
    .field-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
    }

    .field-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }

    .field-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
    }

    /* ---------- Buttons ---------- */
    .btn {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: var(--secondary-color);
        box-shadow: 0 4px 10px rgba(164, 0, 0, 0.3);
    }

    .btn-primary:hover {
        background-color: #b50000;
    }

    .btn-primary:active {
        background-color: #800000;
        transform: scale(0.97);
    }

    .btn-light {
        background-color: #f1f1f1;
        color: #333;
    }

    .btn-light:hover {
        background-color: #e0e0e0;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .form-input-error {
        color: red;
        font-size: 13px;
        margin-top: 4px;
    }

    /* ---------- Page Layout ---------- */
    .page {
        max-width: 1200px;
        margin-right: 15vw;
        margin-left: 15vw;
        padding: 0 20px;
    }

    .back-btn-container {
        margin: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .info-box {
        background: #e8f4fd;
        border-left: 4px solid #2196f3;
        padding: 15px;
        border-radius: 6px;
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }

    .info-box .material-symbols-outlined {
        color: #2196f3;
        font-size: 24px;
    }

    .info-box ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
    }

    .info-box li {
        margin-bottom: 4px;
        font-size: 14px;
        color: #333;
    }

    .required {
        color: red;
    }

    .optional {
        color: #666;
        font-size: 12px;
    }

    small.form-hint {
        color: #666;
        font-size: 13px;
        display: block;
        margin-top: 4px;
    }
</style>

<div class="back-btn-container">
    <button class="tertiary-btn" style="display:flex; width:100px; align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/notes'">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
</div>

<main class="page">
    <!-- Flash Messages -->
    <?php flash('note_error'); ?>

    <div class="application-form" style="flex: 1 1 100%; max-width: 800px; margin: 0 auto;">
        <h3 style="color: var(--primary-color); margin-top: 0;">Add New Note</h3>

        <form method="POST" action="<?php echo URL_ROOT; ?>/caretaker/addNote" id="noteForm">
            <div class="form-grid">

                <!-- Title -->
                <div class="field full-width">
                    <div class="field-label">Note Title <span class="required">*</span></div>
                    <input
                        class="field-input"
                        type="text"
                        name="title"
                        placeholder="e.g., Check main gate lock, Submit monthly report"
                        required
                        maxlength="255">
                    <small class="form-hint">Brief title for your note (max 255 characters)</small>
                </div>

                <!-- Category -->
                <div class="field">
                    <div class="field-label">Category <span class="required">*</span></div>
                    <select class="field-select" name="category" required>
                        <option value="General">General</option>
                        <option value="Important">Important</option>
                        <option value="Reminder">Reminder</option>
                        <option value="Observation">Observation</option>
                    </select>
                </div>

                <!-- Priority -->
                <div class="field">
                    <div class="field-label">Priority <span class="required">*</span></div>
                    <select class="field-select" name="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <!-- Reminder Date -->
                <div class="field full-width">
                    <div class="field-label">Reminder Date <span class="optional">(Optional)</span></div>
                    <input
                        class="field-input"
                        type="date"
                        name="reminder_date"
                        min="<?php echo date('Y-m-d'); ?>">
                    <small class="form-hint">Set a reminder date for this note</small>
                </div>

                <!-- Note Content -->
                <div class="field full-width">
                    <div class="field-label">Note Content <span class="required">*</span></div>
                    <textarea
                        class="field-textarea"
                        name="note_content"
                        rows="8"
                        placeholder="Enter your note details here..."
                        required
                        minlength="10"
                        maxlength="5000"></textarea>
                    <small class="form-hint">Detailed note content (minimum 10 characters)</small>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <span class="material-symbols-outlined">lightbulb</span>
                <div>
                    <strong>Tips:</strong>
                    <ul>
                        <li>Use <strong>Important</strong> category for critical notes</li>
                        <li>Set reminder dates for time-sensitive tasks</li>
                        <li>Use <strong>Observation</strong> for site inspection notes</li>
                        <li>Keep notes clear and concise for easy reference</li>
                    </ul>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-light" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/notes'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Note</button>
            </div>
        </form>
    </div>
</main>

<?php flash('msg') ?>

<script>
    // Flash message auto-remove
    document.addEventListener('DOMContentLoaded', function() {
        const flashMessage = document.getElementById('msg-flash');

        if (flashMessage) {
            setTimeout(function() {
                flashMessage.classList.add('fade-out');

                setTimeout(function() {
                    if (flashMessage.parentNode) {
                        flashMessage.parentNode.removeChild(flashMessage);
                    }
                }, 300);
            }, 5000);
        }
    });
</script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>