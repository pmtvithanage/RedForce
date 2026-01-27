<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>

<style>
/* Incident Detail Page Styles */
.incident-detail-page {
  width: 100%;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 30px;
}

.back-btn {
  display: flex;
  align-items: center;
  gap: 5px;
  background: #fff;
  border: 1px solid #e5e7eb;
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #374151;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
}

.back-btn:hover {
  background: #f9fafb;
  border-color: #d1d5db;
  transform: translateX(-3px);
}

.detail-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  margin-bottom: 20px;
  border: 1px solid #e5e7eb;
}

.detail-header {
  background: linear-gradient(135deg, #a40000, #bd0909);
  color: white;
  padding: 25px;
  display: flex;
  justify-content: space-between;
  align-items: start;
}

.detail-header-content h1 {
  margin: 0 0 10px 0;
  font-size: 24px;
  font-weight: bold;
}

.detail-header-meta {
  display: flex;
  gap: 20px;
  margin-top: 10px;
  font-size: 14px;
  opacity: 0.95;
}

.detail-header-meta span {
  display: flex;
  align-items: center;
  gap: 5px;
}

.detail-badges {
  display: flex;
  gap: 10px;
  flex-direction: column;
  align-items: flex-end;
}

.priority-badge, .status-badge {
  display: inline-block;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.priority-low { background: #dbeafe; color: #1e40af; }
.priority-medium { background: #fef3c7; color: #b45309; }
.priority-high { background: #fee2e2; color: #991b1b; }
.priority-critical { background: #fecaca; color: #7f1d1d; }

.status-pending { background: #fef3c7; color: #d97706; }
.status-in-progress { background: #dbeafe; color: #2563eb; }
.status-resolved { background: #d1fae5; color: #065f46; }
.status-closed { background: #e5e7eb; color: #4b5563; }

.detail-body {
  padding: 30px;
}

.detail-section {
  margin-bottom: 30px;
}

.detail-section:last-child {
  margin-bottom: 0;
}

.section-title {
  font-size: 16px;
  font-weight: 600;
  color: #111827;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
  padding-bottom: 10px;
  border-bottom: 2px solid #f3f4f6;
}

.section-title .material-symbols-outlined {
  color: #a40000;
  font-size: 22px;
}

.detail-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.detail-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.detail-label {
  font-size: 12px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.detail-value {
  font-size: 15px;
  color: #111827;
  font-weight: 500;
}

.detail-description {
  background: #f9fafb;
  padding: 20px;
  border-radius: 8px;
  border-left: 4px solid #a40000;
  line-height: 1.6;
  color: #374151;
}

.evidence-gallery {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 15px;
  margin-top: 15px;
}

.evidence-item {
  position: relative;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.evidence-item:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.evidence-item img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  display: block;
}

.no-evidence {
  text-align: center;
  padding: 40px;
  color: #9ca3af;
  font-style: italic;
}

.location-map {
  width: 100%;
  height: 300px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  margin-top: 15px;
}

.action-buttons {
  display: flex;
  gap: 10px;
  padding: 20px;
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

.action-btn {
  padding: 10px 20px;
  border-radius: 8px;
  border: none;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.3s ease;
}

.btn-delete {
  background: #ef4444;
  color: white;
}

.btn-delete:hover {
  background: #dc2626;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-print {
  background: #ef4444;
  color: white;
}

.btn-print:hover {
  background: #dc2626;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

@media print {
  .back-btn, .action-buttons, .timeline-section {
    display: none;
  }
  .detail-card {
    box-shadow: none;
  }
}

/* Review Modal Styles */
.review-modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  z-index: 1000;
  align-items: center;
  justify-content: center;
}

.review-modal.active {
  display: flex;
}

.review-modal-content {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
  from {
    transform: translateY(-50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.review-modal-header {
  padding: 20px 25px;
  background: linear-gradient(135deg, #a40000, #bd0909);
  color: white;
  border-radius: 12px 12px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.review-modal-header h3 {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
}

.review-modal-close {
  background: none;
  border: none;
  color: white;
  font-size: 28px;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  transition: background 0.3s ease;
}

.review-modal-close:hover {
  background: rgba(255, 255, 255, 0.2);
}

.review-modal-body {
  padding: 25px;
}

.review-form-group {
  margin-bottom: 20px;
}

.review-form-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.review-form-input,
.review-form-textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
  font-family: inherit;
  transition: border-color 0.3s ease;
}

.review-form-input:focus,
.review-form-textarea:focus {
  outline: none;
  border-color: #a40000;
  box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
}

.review-form-textarea {
  resize: vertical;
  min-height: 120px;
}

.review-modal-footer {
  padding: 20px 25px;
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  border-radius: 0 0 12px 12px;
}

.review-btn {
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  display: flex;
  align-items: center;
  gap: 6px;
}

.review-btn-cancel {
  background: #e5e7eb;
  color: #374151;
}

.review-btn-cancel:hover {
  background: #d1d5db;
}

.review-btn-submit {
  background: #a40000;
  color: white;
}

.review-btn-submit:hover {
  background: #bd0909;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
}

.btn-add-review {
  background: #ef4444;
  color: white;
}

.btn-add-review:hover {
  background: #dc2626;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Timeline Styles */
.timeline-section {
  padding: 40px 30px 30px 30px;
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

.timeline-header {
  text-align: center;
  margin-bottom: 40px;
}

.timeline-header h2 {
  font-size: 20px;
  font-weight: 600;
  color: #111827;
  margin: 0 0 8px 0;
}

.timeline-header p {
  font-size: 14px;
  color: #6b7280;
  margin: 0;
}

.timeline-container {
  position: relative;
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px 0;
}

.timeline-line {
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(to right, #22c55e 0%, #22c55e 25%, #e5e7eb 25%, #e5e7eb 100%);
  transform: translateY(-50%);
  z-index: 1;
  transition: background 0.5s ease;
}

.timeline-items {
  display: flex;
  justify-content: space-between;
  position: relative;
  z-index: 2;
}

.timeline-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  flex: 1;
}

.timeline-circle {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: #fff;
  border: 4px solid #22c55e;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.timeline-item.pending .timeline-circle {
  border-color: #e5e7eb;
  background: #f9fafb;
}

.timeline-item.completed .timeline-circle {
  border-color: #22c55e;
  background: #fff;
}

.timeline-circle:hover {
  transform: scale(1.15);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.timeline-circle img {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  object-fit: cover;
}

.timeline-circle .material-symbols-outlined {
  font-size: 32px;
  color: #22c55e;
}

.timeline-item.pending .timeline-circle .material-symbols-outlined {
  color: #9ca3af;
}

.timeline-label {
  margin-top: 15px;
  text-align: center;
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}

.timeline-date {
  font-size: 11px;
  color: #6b7280;
  margin-top: 4px;
}

.timeline-item.pending .timeline-label {
  color: #9ca3af;
}

.timeline-item.pending .timeline-date {
  color: #d1d5db;
}

/* Tooltip/Popup */
.timeline-tooltip {
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%) translateY(-10px);
  background: #1f2937;
  color: white;
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 13px;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  z-index: 10;
}

.timeline-tooltip::after {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border: 6px solid transparent;
  border-top-color: #1f2937;
}

.timeline-circle:hover .timeline-tooltip {
  opacity: 1;
  transform: translateX(-50%) translateY(-15px);
}

.tooltip-title {
  font-weight: 600;
  margin-bottom: 4px;
}

.tooltip-detail {
  font-size: 12px;
  opacity: 0.9;
}

/* Reviews Section */
.reviews-section {
  margin-top: 30px;
  padding: 25px;
  background: #f9fafb;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
}

.reviews-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.reviews-header h2 {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 20px;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

.reviews-header h2 .material-symbols-outlined {
  font-size: 24px;
  color: #3b82f6;
}

.review-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #ef4444;
  color: white;
  font-size: 14px;
  font-weight: 600;
  min-width: 28px;
  height: 28px;
  border-radius: 14px;
  padding: 0 8px;
}

.btn-add-review-inline {
  display: flex;
  align-items: center;
  gap: 6px;
  background: #ef4444;
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-add-review-inline:hover {
  background: #dc2626;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-add-review-inline .material-symbols-outlined {
  font-size: 18px;
}

.reviews-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.review-item {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  padding: 20px;
  transition: all 0.3s ease;
}

.review-item:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transform: translateY(-2px);
}

.review-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 15px;
  padding-bottom: 12px;
  border-bottom: 1px solid #f3f4f6;
}

.review-user {
  display: flex;
  align-items: center;
  gap: 12px;
}

.review-avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.review-avatar img {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  object-fit: cover;
}

.review-avatar .material-symbols-outlined {
  font-size: 24px;
  color: white;
}

.review-user-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.review-name {
  font-size: 15px;
  font-weight: 600;
  color: #1f2937;
}

.review-date {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 12px;
  color: #6b7280;
}

.review-date .material-symbols-outlined {
  font-size: 14px;
}

.review-type-badge {
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.review-type-update {
  background: #dbeafe;
  color: #1e40af;
}

.review-type-action {
  background: #dcfce7;
  color: #166534;
}

.review-type-comment {
  background: #fef3c7;
  color: #92400e;
}

.review-type-follow-up {
  background: #fce7f3;
  color: #9f1239;
}

.review-body {
  padding-top: 8px;
}

.review-title {
  font-size: 16px;
  font-weight: 600;
  color: #111827;
  margin: 0 0 10px 0;
}

.review-content {
  font-size: 14px;
  color: #4b5563;
  line-height: 1.6;
  white-space: pre-wrap;
}

.no-reviews {
  text-align: center;
  padding: 60px 20px;
  color: #9ca3af;
}

.no-reviews .material-symbols-outlined {
  font-size: 64px;
  color: #d1d5db;
  margin-bottom: 15px;
  display: block;
}

.no-reviews p {
  font-size: 16px;
  font-weight: 500;
  margin: 0 0 8px 0;
  color: #6b7280;
}

.no-reviews small {
  font-size: 13px;
  color: #9ca3af;
}

/* Mobile Responsive Styles */
@media (max-width: 968px) {
  .incident-detail-page {
    padding: 10px;
  }
  
  .detail-header {
    flex-direction: column;
    gap: 15px;
  }
  
  .detail-badges {
    align-items: flex-start;
    flex-direction: row;
  }
  
  .detail-body {
    padding: 20px;
  }
  
  .detail-grid {
    grid-template-columns: 1fr;
  }
  
  .timeline-items {
    gap: 10px;
  }
  
  .timeline-circle {
    width: 50px;
    height: 50px;
  }
  
  .timeline-circle img {
    width: 42px;
    height: 42px;
  }
  
  .timeline-circle .material-symbols-outlined {
    font-size: 24px;
  }
  
  .timeline-label {
    font-size: 11px;
  }
  
  .timeline-date {
    font-size: 10px;
  }
  
  .evidence-gallery {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  }
  
  .review-modal-content {
    width: 95%;
  }
  
  .action-buttons {
    flex-direction: column;
  }
  
  .action-btn {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 640px) {
  .page-header {
    margin-bottom: 15px;
  }
  
  .detail-header-content h1 {
    font-size: 20px;
  }
  
  .detail-header-meta {
    flex-direction: column;
    gap: 8px;
  }
  
  .detail-body {
    padding: 15px;
  }
  
  .section-title {
    font-size: 14px;
  }
  
  .detail-value {
    font-size: 14px;
  }
  
  .timeline-section {
    padding: 20px 15px;
  }
  
  .timeline-header h2 {
    font-size: 18px;
  }
  
  .timeline-container {
    padding: 10px 0;
  }
  
  .timeline-circle {
    width: 40px;
    height: 40px;
  }
  
  .timeline-circle img {
    width: 32px;
    height: 32px;
  }
  
  .timeline-circle .material-symbols-outlined {
    font-size: 20px;
  }
  
  .timeline-label {
    font-size: 10px;
  }
  
  .timeline-tooltip {
    display: none;
  }
  
  .reviews-section {
    padding: 15px;
  }
  
  .reviews-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .btn-add-review-inline {
    width: 100%;
    justify-content: center;
  }
  
  .review-item {
    padding: 15px;
  }
  
  .review-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .evidence-gallery {
    grid-template-columns: 1fr;
  }
  
  .location-map {
    height: 200px;
  }
}

</style>

<?php require_once APP_ROOT . '/views/components/showNotification.php'; ?>

<div class="incident-detail-page">
  <div class="page-header">
    <button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/MobileRider/viewIncident'"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
  </div>

  <?php if(isset($data['incident'])): 
    $incident = $data['incident'];
  ?>
  
  <div class="detail-card">
    <div class="detail-header">
      <div class="detail-header-content">
        <h1>Incident <?php echo htmlspecialchars($incident->id); ?></h1>
        <div class="detail-header-meta">
          <span>
            <span class="material-symbols-outlined">calendar_today</span>
            <?php echo date('F d, Y', strtotime($incident->incident_date)); ?>
          </span>
          <span>
            <span class="material-symbols-outlined">schedule</span>
            <?php echo date('h:i A', strtotime($incident->incident_time)); ?>
          </span>
          <span>
            <span class="material-symbols-outlined">person</span>
            <?php echo htmlspecialchars($incident->officer_name ?? 'Unknown'); ?>
          </span>
        </div>
      </div>
      <div class="detail-badges">
        <span class="priority-badge priority-<?php echo strtolower($incident->priority ?? $incident->severity ?? 'medium'); ?>">
          <?php echo htmlspecialchars($incident->priority ?? $incident->severity ?? 'Medium'); ?> Priority
        </span>
        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $incident->status ?? 'pending')); ?>">
          <?php echo htmlspecialchars($incident->status ?? 'Pending'); ?>
        </span>
      </div>
    </div>

    <div class="detail-body">
      <!-- Basic Information -->
      <div class="detail-section">
        <h2 class="section-title">
          <span class="material-symbols-outlined">info</span>
          Basic Information
        </h2>
        <div class="detail-grid">
          <div class="detail-item">
            <div class="detail-label">Incident Type</div>
            <div class="detail-value"><?php echo htmlspecialchars($incident->incident_type ?? 'N/A'); ?></div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Site Location</div>
            <div class="detail-value"><?php echo htmlspecialchars($incident->site_name ?? 'N/A'); ?></div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Reporter Role</div>
            <div class="detail-value">
              <?php 
                $role = $incident->officer_role ?? 'N/A';
                // Display "Supervisor" if role contains "premise officer"
                if (stripos($role, 'premise officer') !== false) {
                  echo 'Supervisor';
                } else {
                  echo htmlspecialchars($role);
                }
              ?>
            </div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Reported At</div>
            <div class="detail-value"><?php echo date('M d, Y h:i A', strtotime($incident->created_at)); ?></div>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="detail-section">
        <h2 class="section-title">
          <span class="material-symbols-outlined">description</span>
          Incident Description
        </h2>
        <div class="detail-description">
          <?php echo nl2br(htmlspecialchars($incident->incident_description ?? 'No description provided.')); ?>
        </div>
      </div>

      <!-- Actions Taken -->
      <?php if(!empty($incident->action_taken)): ?>
      <div class="detail-section">
        <h2 class="section-title">
          <span class="material-symbols-outlined">check_circle</span>
          Actions Taken
        </h2>
        <div class="detail-description">
          <?php echo nl2br(htmlspecialchars($incident->action_taken)); ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- People Involved -->
      <?php if(!empty($incident->people_involved)): ?>
      <div class="detail-section">
        <h2 class="section-title">
          <span class="material-symbols-outlined">group</span>
          People Involved
        </h2>
        <div class="detail-description">
          <?php echo nl2br(htmlspecialchars($incident->people_involved)); ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Additional Details -->
      <?php if(!empty($incident->additional_details)): ?>
      <div class="detail-section">
        <h2 class="section-title">
          <span class="material-symbols-outlined">notes</span>
          Additional Details
        </h2>
        <div class="detail-description">
          <?php echo nl2br(htmlspecialchars($incident->additional_details)); ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Evidence/Photos -->
      <?php if(!empty($incident->media_files)): ?>
      <div class="detail-section">
        <h2 class="section-title">
          <span class="material-symbols-outlined">photo_library</span>
          Evidence & Photos
        </h2>
        <div class="evidence-gallery">
          <?php 
          $files = explode(',', $incident->media_files);
          foreach($files as $file): 
            $file = trim($file);
            if(!empty($file)):
          ?>
            <div class="evidence-item">
              <img src="<?php echo URL_ROOT; ?>/uploads/evidence/<?php echo htmlspecialchars($file); ?>" 
                   alt="Evidence" 
                   onclick="window.open(this.src, '_blank')" 
                   style="cursor: pointer;">
            </div>
          <?php 
            endif;
          endforeach; 
          ?>
        </div>
      </div>
      <?php else: ?>
      <div class="detail-section">
        <h2 class="section-title">
          <span class="material-symbols-outlined">photo_library</span>
          Evidence & Photos
        </h2>
        <div class="no-evidence">
          <span class="material-symbols-outlined" style="font-size: 48px; display: block; margin-bottom: 10px;">hide_image</span>
          No evidence files attached
        </div>
      </div>
      <?php endif; ?>

      <!-- Location Map -->
      <?php if(!empty($incident->latitude) && !empty($incident->longitude)): ?>
      <div class="detail-section">
        <h2 class="section-title">
          <span class="material-symbols-outlined">location_on</span>
          Incident Location
        </h2>
        <div id="map" class="location-map"></div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Timeline Section -->
    <div class="timeline-section">
      <div class="timeline-header">
        <h2>Incident Timeline</h2>
        <p>Track the progress and actions taken on this incident</p>
      </div>
      
      <?php
      // Calculate timeline progress based on status
      $status = $incident->status ?? 'Pending';
      
      // Determine which stages are completed
      $reportedCompleted = true; // Always completed
      // Under review if there are any reviews OR status is not pending
      $underReviewCompleted = (!empty($data['reviews']) || $status != 'Pending');
      $inProgressCompleted = ($status == 'In Progress' || $status == 'Resolved' || $status == 'Closed');
      $resolvedCompleted = ($status == 'Resolved' || $status == 'Closed');
      
      // Count completed stages for progress bar
      $completedStages = 1; // Reported
      if ($underReviewCompleted) $completedStages++;
      if ($inProgressCompleted) $completedStages++;
      if ($resolvedCompleted) $completedStages++;
      
      $progressPercentage = ($completedStages / 4) * 100;
      ?>
      
      <div class="timeline-container">
        <div class="timeline-line" style="background: linear-gradient(to right, #22c55e 0%, #22c55e <?php echo $progressPercentage; ?>%, #e5e7eb <?php echo $progressPercentage; ?>%, #e5e7eb 100%);"></div>
        <div class="timeline-items">
          
          <!-- Reported -->
          <div class="timeline-item completed">
            <div class="timeline-circle">
              <?php if(!empty($incident->profile_image)): ?>
                <img src="<?php echo URL_ROOT; ?>/<?php echo htmlspecialchars($incident->profile_image); ?>" alt="Reporter">
              <?php else: ?>
                <span class="material-symbols-outlined">flag</span>
              <?php endif; ?>
              <div class="timeline-tooltip">
                <div class="tooltip-title">Incident Reported</div>
                <div class="tooltip-detail">By: <?php echo htmlspecialchars($incident->officer_name ?? 'Unknown'); ?></div>
                <div class="tooltip-detail">Date: <?php echo date('M d, Y h:i A', strtotime($incident->created_at)); ?></div>
              </div>
            </div>
            <div class="timeline-label">Reported</div>
            <div class="timeline-date"><?php echo date('M d, Y', strtotime($incident->created_at)); ?></div>
          </div>

          <!-- Under Review -->
          <div class="timeline-item <?php echo $underReviewCompleted ? 'completed' : 'pending'; ?>">
            <div class="timeline-circle">
              <span class="material-symbols-outlined">visibility</span>
              <div class="timeline-tooltip">
                <div class="tooltip-title">Under Review</div>
                <div class="tooltip-detail">Status: <?php echo $status; ?></div>
                <?php if($status != 'Pending'): ?>
                  <div class="tooltip-detail">Reviewed by supervisor</div>
                <?php else: ?>
                  <div class="tooltip-detail">Awaiting review</div>
                <?php endif; ?>
              </div>
            </div>
            <div class="timeline-label">Under Review</div>
            <div class="timeline-date">
              <?php echo $underReviewCompleted ? date('M d, Y', strtotime($incident->updated_at)) : 'Pending'; ?>
            </div>
          </div>

          <!-- In Progress -->
          <div class="timeline-item <?php echo $inProgressCompleted ? 'completed' : 'pending'; ?>">
            <div class="timeline-circle">
              <span class="material-symbols-outlined">engineering</span>
              <div class="timeline-tooltip">
                <div class="tooltip-title">In Progress</div>
                <?php if(!empty($incident->action_taken)): ?>
                  <div class="tooltip-detail">Actions: <?php echo htmlspecialchars(substr($incident->action_taken, 0, 50)) . '...'; ?></div>
                <?php else: ?>
                  <div class="tooltip-detail">No actions recorded yet</div>
                <?php endif; ?>
              </div>
            </div>
            <div class="timeline-label">In Progress</div>
            <div class="timeline-date">
              <?php echo $inProgressCompleted ? date('M d, Y', strtotime($incident->updated_at)) : 'Pending'; ?>
            </div>
          </div>

          <!-- Resolved -->
          <div class="timeline-item <?php echo $resolvedCompleted ? 'completed' : 'pending'; ?>">
            <div class="timeline-circle">
              <span class="material-symbols-outlined">check_circle</span>
              <div class="timeline-tooltip">
                <div class="tooltip-title">Resolved</div>
                <?php if(isset($incident->status) && ($incident->status == 'Resolved' || $incident->status == 'Closed')): ?>
                  <div class="tooltip-detail">Incident successfully resolved</div>
                  <div class="tooltip-detail">Closed on: <?php echo date('M d, Y', strtotime($incident->updated_at)); ?></div>
                <?php else: ?>
                  <div class="tooltip-detail">Not yet resolved</div>
                <?php endif; ?>
              </div>
            </div>
            <div class="timeline-label">Resolved</div>
            <div class="timeline-date">
              <?php echo (isset($incident->status) && ($incident->status == 'Resolved' || $incident->status == 'Closed')) ? date('M d, Y', strtotime($incident->updated_at)) : 'Pending'; ?>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
      <div class="reviews-header">
        <h2>
          <span class="material-symbols-outlined">rate_review</span>
          Reviews & Updates
          <?php if(!empty($data['reviews'])): ?>
            <span class="review-count"><?php echo count($data['reviews']); ?></span>
          <?php endif; ?>
        </h2>
        <button class="btn-add-review-inline" onclick="openReviewModal()">
          <span class="material-symbols-outlined">add</span>
          Add Review
        </button>
      </div>

      <?php if(!empty($data['reviews'])): ?>
        <div class="reviews-list">
          <?php foreach($data['reviews'] as $review): ?>
            <div class="review-item">
              <div class="review-header">
                <div class="review-user">
                  <div class="review-avatar">
                    <?php if(!empty($review->profile_image)): ?>
                      <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo htmlspecialchars($review->profile_image); ?>" alt="<?php echo htmlspecialchars($review->reviewer_name); ?>">
                    <?php else: ?>
                      <span class="material-symbols-outlined">person</span>
                    <?php endif; ?>
                  </div>
                  <div class="review-user-info">
                    <div class="review-name">
                      <?php echo htmlspecialchars($review->reviewer_name); ?>
                      <?php if(!empty($review->role)): ?>
                        <span style="color: #6b7280; font-weight: 400; font-size: 13px;"> - 
                          <?php 
                            // Display "Supervisor" if role contains "premise officer"
                            if (stripos($review->role, 'premise officer') !== false) {
                              echo 'Supervisor';
                            } else {
                              echo htmlspecialchars($review->role);
                            }
                          ?>
                        </span>
                      <?php endif; ?>
                    </div>
                    <div class="review-date">
                      <span class="material-symbols-outlined">schedule</span>
                      <?php echo date('M d, Y \a\t h:i A', strtotime($review->created_at)); ?>
                    </div>
                  </div>
                </div>
                <div class="review-type-badge review-type-<?php echo strtolower($review->review_type); ?>">
                  <?php echo htmlspecialchars($review->review_type); ?>
                </div>
              </div>
              <div class="review-body">
                <h3 class="review-title"><?php echo htmlspecialchars($review->review_title); ?></h3>
                <div class="review-content"><?php echo nl2br(htmlspecialchars($review->review_details)); ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="no-reviews">
          <span class="material-symbols-outlined">rate_review</span>
          <p>No reviews yet</p>
          <small>Be the first to add a review or update for this incident</small>
        </div>
      <?php endif; ?>
    </div>

    <div class="action-buttons">

      <button class="action-btn btn-print" onclick="window.print()">
        <span class="material-symbols-outlined">print</span>
        Print Report
      </button>
    </div>
  </div>

  <!-- Review Modal -->
  <div class="review-modal" id="reviewModal">
    <div class="review-modal-content">
      <div class="review-modal-header">
        <h3>Add Review to Incident</h3>
        <button class="review-modal-close" onclick="closeReviewModal()">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <form id="reviewForm" method="POST" action="<?php echo URL_ROOT; ?>/MobileRider/addIncidentReview">
        <div class="review-modal-body">
          <input type="hidden" name="incident_id" value="<?php echo $incident->id ?? ''; ?>">
          
          <div class="review-form-group">
            <label class="review-form-label">Review Title <span style="color: #ef4444;">*</span></label>
            <input type="text" name="review_title" class="review-form-input" placeholder="Brief title for your review" required>
          </div>

          <div class="review-form-group">
            <label class="review-form-label">Review Type</label>
            <select name="review_type" class="review-form-input">
              <option value="Update">Status Update</option>
              <option value="Action">Action Taken</option>
              <option value="Comment">General Comment</option>
              <option value="Follow-up">Follow-up Required</option>
            </select>
          </div>

          <div class="review-form-group">
            <label class="review-form-label">Review Details <span style="color: #ef4444;">*</span></label>
            <textarea name="review_details" class="review-form-textarea" placeholder="Provide detailed review or update about the incident..." required></textarea>
          </div>
        </div>
        
        <div class="review-modal-footer">
          <button type="button" class="review-btn review-btn-cancel" onclick="closeReviewModal()">
            Cancel
          </button>
          <button type="submit" class="review-btn review-btn-submit">
            <span class="material-symbols-outlined">send</span>
            Submit Review
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php else: ?>
  <div class="detail-card">
    <div class="detail-body" style="text-align: center; padding: 60px;">
      <span class="material-symbols-outlined" style="font-size: 64px; color: #9ca3af;">error</span>
      <h2 style="margin: 20px 0 10px 0; color: #374151;">Incident Not Found</h2>
      <p style="color: #6b7280; margin-bottom: 20px;">The requested incident could not be found.</p>
      <a href="<?php echo URL_ROOT; ?>/MobileRider/incidents" class="back-btn">
        <span class="material-symbols-outlined">arrow_back</span>
        Back to Incidents
      </a>
    </div>
  </div>
  <?php endif; ?>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php if(isset($incident) && !empty($incident->latitude) && !empty($incident->longitude)): ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGwijY64zQTmizwDN6omOoI9nzxb1MQog"></script>
<script>
function initMap() {
  const location = {
    lat: <?php echo floatval($incident->latitude); ?>,
    lng: <?php echo floatval($incident->longitude); ?>
  };
  
  const map = new google.maps.Map(document.getElementById('map'), {
    center: location,
    zoom: 16,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  
  new google.maps.Marker({
    position: location,
    map: map,
    title: 'Incident Location'
  });
}

google.maps.event.addDomListener(window, 'load', initMap);
</script>
<?php endif; ?>

<script>
// Review Modal Functions
function openReviewModal() {
  document.getElementById('reviewModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeReviewModal() {
  document.getElementById('reviewModal').classList.remove('active');
  document.body.style.overflow = '';
  document.getElementById('reviewForm').reset();
}

// Close modal when clicking outside
document.getElementById('reviewModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeReviewModal();
  }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeReviewModal();
  }
});
</script>


<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
