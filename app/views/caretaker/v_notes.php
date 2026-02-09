<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/caretaker/notes_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<main class="main-content">
    <div class="notes-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><span class="material-symbols-outlined">note</span></h1>
                <div class="live-datetime" id="liveDateTime"></div>
            </div>
            <a href="<?php echo URL_ROOT; ?>/caretaker/addNotePage" class="btn-add">
                <span class="material-symbols-outlined">add</span> Add Note
            </a>
        </div>

        <!-- Flash Messages -->
        <?php flash('note_message'); ?>
        <?php flash('note_error'); ?>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon total">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->total ?? 0; ?></h3>
                    <p>Total Notes</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon important">
                    <span class="material-symbols-outlined">priority_high</span>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->important ?? 0; ?></h3>
                    <p>Important</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon priority">
                    <span class="material-symbols-outlined">flag</span>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->highPriority ?? 0; ?></h3>
                    <p>High Priority</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon reminder">
                    <span class="material-symbols-outlined">notifications_active</span>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->pendingReminders ?? 0; ?></h3>
                    <p>Pending Reminders</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <h3><span class="material-symbols-outlined">filter_alt</span> Filters</h3>
            <form method="GET" action="<?php echo URL_ROOT; ?>/caretaker/notes" class="filters-form">
                <div class="filter-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="All" <?php echo ($data['filters']['category'] ?? 'All') == 'All' ? 'selected' : ''; ?>>All Categories</option>
                        <option value="General" <?php echo ($data['filters']['category'] ?? '') == 'General' ? 'selected' : ''; ?>>General</option>
                        <option value="Important" <?php echo ($data['filters']['category'] ?? '') == 'Important' ? 'selected' : ''; ?>>Important</option>
                        <option value="Reminder" <?php echo ($data['filters']['category'] ?? '') == 'Reminder' ? 'selected' : ''; ?>>Reminder</option>
                        <option value="Observation" <?php echo ($data['filters']['category'] ?? '') == 'Observation' ? 'selected' : ''; ?>>Observation</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Priority</label>
                    <select name="priority">
                        <option value="All" <?php echo ($data['filters']['priority'] ?? 'All') == 'All' ? 'selected' : ''; ?>>All Priorities</option>
                        <option value="Low" <?php echo ($data['filters']['priority'] ?? '') == 'Low' ? 'selected' : ''; ?>>Low</option>
                        <option value="Medium" <?php echo ($data['filters']['priority'] ?? '') == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="High" <?php echo ($data['filters']['priority'] ?? '') == 'High' ? 'selected' : ''; ?>>High</option>
                    </select>
                </div>

                <div class="filter-group search-group">
                    <label>Search</label>
                    <input type="text" name="search" placeholder="Search notes..." value="<?php echo htmlspecialchars($data['filters']['search'] ?? ''); ?>">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter primary">
                        <span class="material-symbols-outlined">search</span> Apply
                    </button>
                    <a href="<?php echo URL_ROOT; ?>/caretaker/notes" class="btn-filter secondary">
                        <span class="material-symbols-outlined">refresh</span> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Notes Grid -->
        <div class="notes-grid">
            <?php if (!empty($data['notes'])): ?>
                <?php foreach ($data['notes'] as $note): ?>
                    <div class="note-card <?php echo $note->is_pinned ? 'pinned' : ''; ?>">
                        <?php if ($note->is_pinned): ?>
                            <div class="pin-badge">
                                <span class="material-symbols-outlined">push_pin</span> Pinned
                            </div>
                        <?php endif; ?>
                        
                        <div class="note-header">
                            <h4><?php echo htmlspecialchars($note->title); ?></h4>
                            <div class="note-badges">
                                <span class="priority-badge priority-<?php echo strtolower($note->priority); ?>">
                                    <?php echo $note->priority; ?>
                                </span>
                                <span class="category-badge category-<?php echo strtolower($note->category); ?>">
                                    <?php echo $note->category; ?>
                                </span>
                            </div>
                        </div>

                        <div class="note-content">
                            <?php 
                            $content = htmlspecialchars($note->note_content);
                            echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
                            ?>
                        </div>

                        <div class="note-footer">
                            <div class="note-meta">
                                <span class="note-date">
                                    <span class="material-symbols-outlined">schedule</span>
                                    <?php echo date('M d, Y', strtotime($note->created_at)); ?>
                                </span>
                                <?php if ($note->reminder_date): ?>
                                    <span class="reminder-date <?php echo (strtotime($note->reminder_date) < time()) ? 'overdue' : ''; ?>">
                                        <span class="material-symbols-outlined">alarm</span>
                                        <?php echo date('M d, Y', strtotime($note->reminder_date)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="note-actions">
                                <form method="POST" action="<?php echo URL_ROOT; ?>/caretaker/togglePin/<?php echo $note->id; ?>" style="display: inline;">
                                    <button type="submit" class="btn-action" title="<?php echo $note->is_pinned ? 'Unpin' : 'Pin'; ?>">
                                        <span class="material-symbols-outlined"><?php echo $note->is_pinned ? 'keep_off' : 'keep'; ?></span>
                                    </button>
                                </form>
                                
                                <a href="<?php echo URL_ROOT; ?>/caretaker/editNotePage/<?php echo $note->id; ?>" class="btn-action" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                
                                <form method="POST" action="<?php echo URL_ROOT; ?>/caretaker/deleteNote/<?php echo $note->id; ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this note?\n\nTitle: <?php echo htmlspecialchars($note->title); ?>\n\nThis action cannot be undone.');">
                                    <button type="submit" class="btn-action" title="Delete">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <span class="material-symbols-outlined">note</span>
                    <p>No notes found. Click "Add Note" to create your first note.</p>
                </div>
            <?php endif; ?>
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

// Auto-hide flash messages
setTimeout(function() {
    const flashMessages = document.querySelectorAll('.alert');
    flashMessages.forEach(function(msg) {
        msg.style.opacity = '0';
        setTimeout(function() { msg.style.display = 'none'; }, 300);
    });
}, 5000);
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
