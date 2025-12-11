<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/caretaker/notes_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<main class="main-content">
    <div class="notes-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><span class="material-symbols-outlined">edit_note</span></h1>
                <div class="live-datetime" id="liveDateTime"></div>
            </div>
            <a href="<?php echo URL_ROOT; ?>/caretaker/notes" class="btn-secondary">
                <span class="material-symbols-outlined">arrow_back</span> Back to Notes
            </a>
        </div>

        <!-- Flash Messages -->
        <?php flash('note_error'); ?>

        <!-- Edit Note Form -->
        <div class="form-card">
            <form method="POST" action="<?php echo URL_ROOT; ?>/caretaker/updateNote/<?php echo $data['note']->id; ?>" id="noteForm">
                <div class="form-grid">
                    
                    <!-- Title -->
                    <div class="form-group full-width">
                        <label>Note Title <span class="required">*</span></label>
                        <input 
                            type="text" 
                            name="title" 
                            placeholder="e.g., Check main gate lock, Submit monthly report"
                            value="<?php echo htmlspecialchars($data['note']->title); ?>"
                            required
                            maxlength="255"
                        >
                        <small class="form-hint">Brief title for your note (max 255 characters)</small>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label>Category <span class="required">*</span></label>
                        <select name="category" required>
                            <option value="General" <?php echo $data['note']->category == 'General' ? 'selected' : ''; ?>>General</option>
                            <option value="Important" <?php echo $data['note']->category == 'Important' ? 'selected' : ''; ?>>Important</option>
                            <option value="Reminder" <?php echo $data['note']->category == 'Reminder' ? 'selected' : ''; ?>>Reminder</option>
                            <option value="Observation" <?php echo $data['note']->category == 'Observation' ? 'selected' : ''; ?>>Observation</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="form-group">
                        <label>Priority <span class="required">*</span></label>
                        <select name="priority" required>
                            <option value="Low" <?php echo $data['note']->priority == 'Low' ? 'selected' : ''; ?>>Low</option>
                            <option value="Medium" <?php echo $data['note']->priority == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="High" <?php echo $data['note']->priority == 'High' ? 'selected' : ''; ?>>High</option>
                        </select>
                    </div>

                    <!-- Reminder Date -->
                    <div class="form-group full-width">
                        <label>Reminder Date <span class="optional">(Optional)</span></label>
                        <input 
                            type="date" 
                            name="reminder_date"
                            value="<?php echo $data['note']->reminder_date ?? ''; ?>"
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
                        ><?php echo htmlspecialchars($data['note']->note_content); ?></textarea>
                        <small class="form-hint">Detailed note content (minimum 10 characters)</small>
                    </div>
                </div>

                <!-- Note Metadata -->
                <div class="note-metadata">
                    <span><strong>Created:</strong> <?php echo date('F d, Y \a\t g:i A', strtotime($data['note']->created_at)); ?></span>
                    <span><strong>Last Updated:</strong> <?php echo date('F d, Y \a\t g:i A', strtotime($data['note']->updated_at)); ?></span>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="<?php echo URL_ROOT; ?>/caretaker/notes" class="btn-cancel">
                        <span class="material-symbols-outlined">close</span> Cancel
                    </a>
                    <button type="submit" class="btn-submit">
                        <span class="material-symbols-outlined">save</span> Update Note
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
