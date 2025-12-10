<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/caretaker/notes_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<main class="main-content">
    <div class="notes-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><span class="material-symbols-outlined">note_add</span></h1>
                <div class="live-datetime" id="liveDateTime"></div>
            </div>
            <a href="<?php echo URL_ROOT; ?>/caretaker/notes" class="btn-secondary">
                <span class="material-symbols-outlined">arrow_back</span> Back to Notes
            </a>
        </div>

        <!-- Flash Messages -->
        <?php flash('note_error'); ?>

        <!-- Add Note Form -->
        <div class="form-card">
            <form method="POST" action="<?php echo URL_ROOT; ?>/caretaker/addNote" id="noteForm">
                <div class="form-grid">
                    
                    <!-- Title -->
                    <div class="form-group full-width">
                        <label>Note Title <span class="required">*</span></label>
                        <input 
                            type="text" 
                            name="title" 
                            placeholder="e.g., Check main gate lock, Submit monthly report"
                            required
                            maxlength="255"
                        >
                        <small class="form-hint">Brief title for your note (max 255 characters)</small>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label>Category <span class="required">*</span></label>
                        <select name="category" required>
                            <option value="General">General</option>
                            <option value="Important">Important</option>
                            <option value="Reminder">Reminder</option>
                            <option value="Observation">Observation</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="form-group">
                        <label>Priority <span class="required">*</span></label>
                        <select name="priority" required>
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    <!-- Reminder Date -->
                    <div class="form-group full-width">
                        <label>Reminder Date <span class="optional">(Optional)</span></label>
                        <input 
                            type="date" 
                            name="reminder_date"
                            min="<?php echo date('Y-m-d'); ?>"
                        >
                        <small class="form-hint">Set a reminder date for this note</small>
                    </div>

                    <!-- Note Content -->
                    <div class="form-group full-width">
                        <label>Note Content <span class="required">*</span></label>
                        <textarea 
                            name="note_content" 
                            rows="8" 
                            placeholder="Enter your note details here..."
                            required
                            minlength="10"
                            maxlength="5000"
                        ></textarea>
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
                    <a href="<?php echo URL_ROOT; ?>/caretaker/notes" class="btn-cancel">
                        <span class="material-symbols-outlined">close</span> Cancel
                    </a>
                    <button type="submit" class="btn-submit">
                        <span class="material-symbols-outlined">save</span> Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// Live Date and Time
function updateDateTime() {
    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    const dateTimeString = now.toLocaleDateString('en-US', options);
    document.getElementById('liveDateTime').textContent = dateTimeString;
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
