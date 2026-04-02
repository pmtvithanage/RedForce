<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/admin/dashboard_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  .dashboard {
    width: 90%;
    margin: 40px auto;
    padding: 0;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
  }

  .dashboard .card.stat-card {
    border-left: none;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 16px;
    transform: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .dashboard .card.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .dashboard .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    flex-shrink: 0;
  }

  .dashboard .stat-icon.total {
    background: #e3f2fd;
    color: #1976d2;
  }

  .dashboard .stat-icon.onduty {
    background: #e8f5e9;
    color: #2e7d32;
  }

  .dashboard .stat-icon.active {
    background: #fff3e0;
    color: #f57c00;
  }

  .dashboard .stat-icon.incidents {
    background: #ffebee;
    color: #c62828;
  }

  .dashboard .stat-info {
    display: flex;
    flex-direction: column;
  }

  .dashboard .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.1;
  }

  .dashboard .stat-label {
    font-size: 14px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-top: 6px;
  }

  .dashboard .card.section {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    transform: none;
  }

  .dashboard .card.section:hover {
    transform: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  @media (max-width: 768px) {
    .dashboard {
      width: calc(100% - 30px);
      margin: 15px;
      padding: 0;
      grid-template-columns: 1fr;
    }

    .dashboard .card.stat-card {
      padding: 16px;
    }

    .dashboard .stat-icon {
      width: 48px;
      height: 48px;
      font-size: 22px;
    }

    .dashboard .stat-value {
      font-size: 22px;
    }
  }
</style>

<div class="dashboard">
  <!-- Stats -->
  <div class="card stat-card">
    <div class="stat-icon total"><i class="fas fa-users"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['total_officers']; ?></div>
      <div class="stat-label">Total Officers</div>
    </div>
  </div>

  <div class="card stat-card">
    <div class="stat-icon onduty"><i class="fas fa-user-check"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['on_duty']; ?></div>
      <div class="stat-label">Officers On Duty</div>
    </div>
  </div>

  <div class="card stat-card">
    <div class="stat-icon active"><i class="fas fa-user-shield"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['active']; ?></div>
      <div class="stat-label">Active Officers</div>
    </div>
  </div>

  <div class="card stat-card">
    <div class="stat-icon incidents"><i class="fas fa-exclamation-circle"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['incidents']; ?></div>
      <div class="stat-label">Incidents</div>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="card section recent">
    <h3>Recent Activities</h3>

    <?php if (!empty($data['recent_activities'])): ?>
      <div class="activity-list">
        <?php foreach ($data['recent_activities'] as $activity): ?>
          <div class="activity-item <?php echo $activity->activity_type; ?>">
            <div class="activity-header">
              <span class="material-symbols-outlined activity-icon">
                <?php
                $icon_map = [
                  'attendance' => 'how_to_reg',
                  'visit' => 'location_on',
                  'incident' => 'report',
                  'patrol' => 'local_police',
                  'task' => 'task_alt',
                  'break' => 'free_breakfast',
                  'message' => 'mail',
                  'shift' => 'schedule'
                ];
                echo $icon_map[$activity->activity_type] ?? 'notifications';
                ?>
              </span>
              <div class="activity-title">
                <strong><?php echo htmlspecialchars($activity->activity_titel); ?></strong>
              </div>
            </div>
            <div class="activity-details">
              <p><?php echo htmlspecialchars($activity->activity_details); ?></p>
            </div>
            <?php if (!empty($activity->created_at)): ?>
              <div class="activity-user">
                <small><?php echo date('M d, Y   |   h:i A', strtotime($activity->created_at)); ?></small>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-activity">
        <span class="material-symbols-outlined">inbox</span>
        <p>No recent activity to display.</p>
        <small>Officer attendance activities will appear here.</small>
      </div>
    <?php endif; ?>
  </div>

  <!-- Advertisements -->
  <?php require_once APP_ROOT . '/views/components/advertisements.php'; ?>

</div>

<!-- Reminder Modal -->
<div id="reminderModal" class="reminder-modal" style="display: none;">
  <div class="reminder-modal-content">
    <div class="reminder-modal-header">
      <h2><span class="material-symbols-outlined">notifications_active</span> Reminders</h2>
      <button class="reminder-close-btn" onclick="closeReminderModal()">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <div class="reminder-modal-body">
      <!-- Today's Reminders -->
      <div id="todayRemindersSection" style="display: none;">
        <h3 class="reminder-section-title">
          <span class="material-symbols-outlined">today</span>
          Today's Reminders
        </h3>
        <div id="todayRemindersList" class="reminder-list"></div>
      </div>

      <!-- Overdue Reminders -->
      <div id="overdueRemindersSection" style="display: none;">
        <h3 class="reminder-section-title overdue">
          <span class="material-symbols-outlined">schedule</span>
          Missed Reminders
        </h3>
        <div id="overdueRemindersList" class="reminder-list"></div>
      </div>

      <!-- No Reminders -->
      <div id="noRemindersMessage" style="display: none; text-align: center; padding: 30px;">
        <span class="material-symbols-outlined" style="font-size: 48px; color: #ccc;">check_circle</span>
        <p style="color: #666; margin-top: 10px;">No pending reminders</p>
      </div>
    </div>

    <div class="reminder-modal-footer">
      <button class="btn-reminder-close" onclick="closeReminderModal()">Close</button>
    </div>
  </div>
</div>

<style>
  .reminder-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  .reminder-modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.3s;
  }

  @keyframes slideDown {
    from {
      transform: translateY(-50px);
      opacity: 0;
    }

    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  .reminder-modal-header {
    background: linear-gradient(135deg, #ff1100 0%, #b63333 100%);
    color: white;
    padding: 20px 25px;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .reminder-modal-header h2 {
    margin: 0;
    font-size: 22px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .reminder-close-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
  }

  .reminder-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
  }

  .reminder-modal-body {
    padding: 25px;
    max-height: 60vh;
    overflow-y: auto;
  }

  .reminder-section-title {
    font-size: 18px;
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
    padding-bottom: 10px;
    border-bottom: 2px solid #667eea;
  }

  .reminder-section-title.overdue {
    color: #e74c3c;
    border-bottom-color: #e74c3c;
  }

  .reminder-list {
    margin-bottom: 25px;
  }

  .reminder-item {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 15px;
    margin-bottom: 12px;
    border-radius: 8px;
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .reminder-item:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .reminder-item.overdue {
    border-left-color: #e74c3c;
    background: #fff5f5;
  }

  .reminder-item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
  }

  .reminder-title {
    font-weight: 600;
    font-size: 16px;
    color: #333;
    flex: 1;
  }

  .reminder-badges {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }

  .reminder-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .reminder-badge.priority-high {
    background: #fee;
    color: #e74c3c;
  }

  .reminder-badge.priority-medium {
    background: #fff4e6;
    color: #f39c12;
  }

  .reminder-badge.priority-low {
    background: #e8f5e9;
    color: #27ae60;
  }

  .reminder-badge.overdue-badge {
    background: #e74c3c;
    color: white;
  }

  .reminder-content {
    color: #555;
    font-size: 14px;
    margin-bottom: 10px;
    line-height: 1.5;
  }

  .reminder-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e0e0e0;
  }

  .reminder-date {
    font-size: 13px;
    color: #666;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .reminder-actions {
    display: flex;
    gap: 8px;
  }

  .btn-complete-reminder {
    background: #27ae60;
    color: white;
    border: none;
    padding: 6px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: background 0.3s;
  }

  .btn-complete-reminder:hover {
    background: #229954;
  }

  .btn-view-note {
    background: #3498db;
    color: white;
    border: none;
    padding: 6px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    transition: background 0.3s;
  }

  .btn-view-note:hover {
    background: #2980b9;
  }

  .reminder-modal-footer {
    padding: 15px 25px;
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
    display: flex;
    justify-content: flex-end;
  }

  .btn-reminder-close {
    background: #95a5a6;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.3s;
  }

  .btn-reminder-close:hover {
    background: #7f8c8d;
  }

  @media (max-width: 768px) {
    .reminder-modal-content {
      width: 95%;
      margin: 10% auto;
    }

    .reminder-modal-header h2 {
      font-size: 18px;
    }

    .reminder-item {
      padding: 12px;
    }

    .reminder-item-header {
      flex-direction: column;
      gap: 8px;
    }
  }
</style>

<script>
  // Check for reminders on page load - shows every time user logs in
  document.addEventListener('DOMContentLoaded', function() {
    // Always check for incomplete reminders on dashboard load
    fetchReminders();
  });

  function fetchReminders() {
    fetch('<?php echo URL_ROOT; ?>/caretaker/getReminders', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const hasToday = data.today && data.today.length > 0;
          const hasOverdue = data.overdue && data.overdue.length > 0;

          // Show modal if there are any incomplete reminders
          if (hasToday || hasOverdue) {
            displayReminders(data.today, data.overdue);
            showReminderModal();
          }
        }
      })
      .catch(error => {
        console.error('Error fetching reminders:', error);
      });
  }

  function displayReminders(todayReminders, overdueReminders) {
    const todaySection = document.getElementById('todayRemindersSection');
    const overdueSection = document.getElementById('overdueRemindersSection');
    const noRemindersMsg = document.getElementById('noRemindersMessage');
    const todayList = document.getElementById('todayRemindersList');
    const overdueList = document.getElementById('overdueRemindersList');

    // Clear previous content
    todayList.innerHTML = '';
    overdueList.innerHTML = '';

    // Display today's reminders
    if (todayReminders && todayReminders.length > 0) {
      todaySection.style.display = 'block';
      todayReminders.forEach(reminder => {
        todayList.innerHTML += createReminderHTML(reminder, false);
      });
    } else {
      todaySection.style.display = 'none';
    }

    // Display overdue reminders
    if (overdueReminders && overdueReminders.length > 0) {
      overdueSection.style.display = 'block';
      overdueReminders.forEach(reminder => {
        overdueList.innerHTML += createReminderHTML(reminder, true);
      });
    } else {
      overdueSection.style.display = 'none';
    }

    // Show no reminders message if both are empty
    if ((!todayReminders || todayReminders.length === 0) &&
      (!overdueReminders || overdueReminders.length === 0)) {
      noRemindersMsg.style.display = 'block';
    } else {
      noRemindersMsg.style.display = 'none';
    }
  }

  function createReminderHTML(reminder, isOverdue) {
    const priorityClass = reminder.priority.toLowerCase();
    const overdueBadge = isOverdue ? '<span class="reminder-badge overdue-badge">OVERDUE</span>' : '';

    const categoryIcons = {
      'Important': 'priority_high',
      'Reminder': 'alarm',
      'Observation': 'visibility',
      'General': 'description'
    };

    const icon = categoryIcons[reminder.category] || 'description';

    return `
    <div class="reminder-item ${isOverdue ? 'overdue' : ''}">
      <div class="reminder-item-header">
        <div class="reminder-title">
          <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">${icon}</span>
          ${escapeHtml(reminder.title)}
        </div>
        <div class="reminder-badges">
          ${overdueBadge}
          <span class="reminder-badge priority-${priorityClass}">${reminder.priority}</span>
        </div>
      </div>
      <div class="reminder-content">
        ${escapeHtml(reminder.note_content)}
      </div>
      <div class="reminder-footer">
        <div class="reminder-date">
          <span class="material-symbols-outlined" style="font-size: 16px;">event</span>
          ${formatDate(reminder.reminder_date)}
          ${isOverdue ? '(Missed)' : '(Today)'}
        </div>
        <div class="reminder-actions">
          <button class="btn-complete-reminder" onclick="completeReminder(${reminder.id})">
            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
            Complete
          </button>
          <a href="<?php echo URL_ROOT; ?>/caretaker/editNotePage/${reminder.id}" class="btn-view-note">
            <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
            View
          </a>
        </div>
      </div>
    </div>
  `;
  }

  function completeReminder(noteId) {


    const formData = new FormData();
    formData.append('note_id', noteId);

    fetch('<?php echo URL_ROOT; ?>/caretaker/completeReminder', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Remove the reminder item from display
          const reminderItems = document.querySelectorAll('.reminder-item');
          reminderItems.forEach(item => {
            if (item.querySelector('.btn-complete-reminder').getAttribute('onclick').includes(noteId)) {
              item.style.opacity = '0';
              setTimeout(() => item.remove(), 300);
            }
          });

          // Check if modal should be closed
          setTimeout(() => {
            const remainingItems = document.querySelectorAll('.reminder-item');
            if (remainingItems.length === 0) {
              closeReminderModal();
            }
          }, 400);
        } else {
          alert('Failed to complete reminder: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error completing reminder:', error);
        alert('An error occurred while completing the reminder');
      });
  }

  function showReminderModal() {
    document.getElementById('reminderModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeReminderModal() {
    document.getElementById('reminderModal').style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function formatDate(dateString) {
    const date = new Date(dateString);
    const options = {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    };
    return date.toLocaleDateString('en-US', options);
  }

  // Close modal when clicking outside
  window.onclick = function(event) {
    const modal = document.getElementById('reminderModal');
    if (event.target === modal) {
      closeReminderModal();
    }
  }
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>