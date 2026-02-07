<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/caretaker/equipment_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
/* Enhanced Stat Cards - Matching Incidents Style */
.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 15px;
  border-left: 5px solid #ccc;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card.yellow { border-left-color: #f59e0b; }
.stat-card.green { border-left-color: #10b981; }
.stat-card.red { border-left-color: #ef4444; }
.stat-card.purple { border-left-color: #8b5cf6; }

.stat-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
}

.stat-icon {
  font-size: 40px !important;
  transition: transform 0.3s ease;
  color: #555;
}

.stat-card.yellow .stat-icon { color: #f59e0b; }
.stat-card.green .stat-icon { color: #10b981; }
.stat-card.red .stat-icon { color: #ef4444; }
.stat-card.purple .stat-icon { color: #8b5cf6; }

.stat-card:hover .stat-icon {
  transform: scale(1.15);
}

.stat-text {
  flex: 1;
}

.stat-value {
  font-size: 32px;
  font-weight: bold;
  color: #1f2937;
  line-height: 1;
  margin-bottom: 5px;
}

.stat-label {
  font-size: 14px;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-sublabel {
  font-size: 12px;
  color: #9ca3af;
  margin-top: 2px;
}

/* Supervisor Approved Status */
.status-supervisor-approved {
  background: linear-gradient(135deg, #3b82f6, #60a5fa);
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.status-supervisor-approved:hover {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.status-approved:hover,
.status-rejected:hover {
  cursor: pointer;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>

<main class="main-content">
    <div class="equipment-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                
            </div>
            <a href="<?php echo URL_ROOT; ?>/caretaker/addEquipmentPage" class="btn-add">
                <span class="material-symbols-outlined">add</span> Request Equipment
            </a>
        </div>

        <!-- Flash Messages -->
        <?php flash('equipment_message'); ?>
        <?php flash('equipment_error'); ?>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <!-- Pending Requests -->
            <div class="stat-card yellow">
                <span class="material-symbols-outlined stat-icon">pending_actions</span>
                <div class="stat-text">
                    <div class="stat-value"><?php echo $data['stats']->pending ?? 0; ?></div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-sublabel">Rs. <?php echo number_format($data['stats']->pending_cost ?? 0, 2); ?></div>
                </div>
            </div>

            <!-- Approved Requests -->
            <div class="stat-card green">
                <span class="material-symbols-outlined stat-icon">task_alt</span>
                <div class="stat-text">
                    <div class="stat-value"><?php echo $data['stats']->approved ?? 0; ?></div>
                    <div class="stat-label">Approved</div>
                    <div class="stat-sublabel">Rs. <?php echo number_format($data['stats']->approved_cost ?? 0, 2); ?></div>
                </div>
            </div>

            <!-- Rejected Requests -->
            <div class="stat-card red">
                <span class="material-symbols-outlined stat-icon">cancel</span>
                <div class="stat-text">
                    <div class="stat-value"><?php echo $data['stats']->rejected ?? 0; ?></div>
                    <div class="stat-label">Rejected</div>
                </div>
            </div>

            <!-- Total Requests -->
            <div class="stat-card purple">
                <span class="material-symbols-outlined stat-icon">inventory</span>
                <div class="stat-text">
                    <div class="stat-value"><?php echo $data['stats']->total ?? 0; ?></div>
                    <div class="stat-label">Total Requests</div>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="table-container">
            <table class="equipment-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Equipment</th>
                        <th>Qty</th>
                        <th>Est. Cost</th>
                        <th>Actual Cost</th>
                        <th>Total</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['requests'])): ?>
                        <?php foreach ($data['requests'] as $request): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($request->requested_date)); ?></td>
                                <td><strong><?php echo htmlspecialchars($request->equipment_name); ?></strong></td>
                                <td><?php echo $request->quantity; ?></td>
                                
                                <!-- Estimated Cost -->
                                <td class="cost-cell">Rs. <?php echo number_format($request->estimated_cost, 2); ?></td>
                                
                                <!-- Actual Cost -->
                                <td class="cost-cell">
                                    <?php if ($request->status == 'Approved' && isset($request->actual_cost) && $request->actual_cost > 0): ?>
                                        <span class="actual-cost">Rs. <?php echo number_format($request->actual_cost, 2); ?></span>
                                    <?php else: ?>
                                        <span class="pending-text">-</span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Total Cost -->
                                <td class="cost-cell total-cost">
                                    <?php if ($request->status == 'Approved' && isset($request->total_cost) && $request->total_cost > 0): ?>
                                        <strong>Rs. <?php echo number_format($request->total_cost, 2); ?></strong>
                                    <?php else: ?>
                                        <span class="estimated-total">
                                            ~Rs. <?php echo number_format($request->quantity * $request->estimated_cost, 2); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Priority -->
                                <td>
                                    <span class="priority-badge priority-<?php echo strtolower($request->priority); ?>">
                                        <?php echo $request->priority; ?>
                                    </span>
                                </td>
                                
                                <!-- Status -->
                                <td>
                                    <?php if (in_array($request->status, ['Approved', 'Rejected', 'Supervisor Approved'])): ?>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $request->status)); ?>"
                                              onclick="viewStatusDetails(<?php echo htmlspecialchars(json_encode($request)); ?>)"
                                              style="cursor: pointer;"
                                              title="Click to view details">
                                            <?php echo $request->status; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-<?php echo strtolower($request->status); ?>">
                                            <?php echo $request->status; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Actions -->
                                <td class="action-buttons">
                                    <?php if ($request->status == 'Pending'): ?>
                                        <!-- Edit Button -->
                                        <a href="<?php echo URL_ROOT; ?>/caretaker/editEquipmentPage/<?php echo $request->id; ?>" 
                                           class="btn-edit" 
                                           title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <form method="POST" 
                                              action="<?php echo URL_ROOT; ?>/caretaker/deleteEquipmentRequest/<?php echo $request->id; ?>" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to delete this equipment request?\n\nEquipment: <?php echo htmlspecialchars($request->equipment_name); ?>\nQuantity: <?php echo $request->quantity; ?>\n\nThis action cannot be undone.')">
                                            <button type="submit" class="btn-delete" title="Delete">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <!-- View Details (for approved/rejected) -->
                                        <button class="btn-view" 
                                                title="View Details"
                                                onclick="viewDetails(<?php echo htmlspecialchars(json_encode($request)); ?>)">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="no-data">
                                <span class="material-symbols-outlined">inventory</span>
                                <p>No equipment requests found. Click "Request Equipment" to submit a request.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <span class="material-symbols-outlined">info</span>
            <div>
                <strong>Note:</strong>
                <ul>
                    <li>You can only edit or delete <strong>Pending</strong> requests</li>
                    <li>Once approved or rejected by the client, requests cannot be modified</li>
                    <li>Provide accurate cost estimates for faster approval</li>
                    <li>High priority requests are processed first</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<!-- View Details Modal -->
<div id="detailsModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">Request Details</h2>
        <div id="modalBody">
            <!-- Details will be loaded here -->
        </div>
    </div>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script>
// View Status Details (for Supervisor decisions)
function viewStatusDetails(request) {
    const modal = document.getElementById('detailsModal');
    const modalBody = document.getElementById('modalBody');
    const modalTitle = document.getElementById('modalTitle');
    
    modalTitle.textContent = request.status + ' - Details';
    
    let detailsHTML = `
        <div class="details-grid">
            <div class="detail-item">
                <span class="detail-label">Equipment Name:</span>
                <span class="detail-value"><strong>${request.equipment_name}</strong></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Quantity:</span>
                <span class="detail-value">${request.quantity}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-badge status-${request.status.toLowerCase().replace(' ', '-')}">${request.status}</span>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date:</span>
                <span class="detail-value">${new Date(request.requested_date).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'})}</span>
            </div>
    `;
    
    if (request.approved_date) {
        detailsHTML += `
            <div class="detail-item">
                <span class="detail-label">${request.status === 'Rejected' ? 'Rejected' : 'Approved'} Date:</span>
                <span class="detail-value">${new Date(request.approved_date).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'})}</span>
            </div>
        `;
    }
    
    detailsHTML += `
            <div class="detail-item full-width">
                <span class="detail-label">Request Reason:</span>
                <span class="detail-value">${request.reason}</span>
            </div>
    `;
    
    if (request.supervisor_notes) {
        detailsHTML += `
            <div class="detail-item full-width" style="background: ${request.status === 'Rejected' ? '#fee2e2' : '#dbeafe'}; padding: 15px; border-radius: 8px; border-left: 4px solid ${request.status === 'Rejected' ? '#ef4444' : '#3b82f6'};">
                <span class="detail-label" style="color: ${request.status === 'Rejected' ? '#991b1b' : '#1e40af'}; font-weight: 600;">
                    <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">note</span>
                    ${request.status === 'Rejected' ? 'Rejection Reason' : 'Supervisor Notes'}:
                </span>
                <span class="detail-value" style="color: ${request.status === 'Rejected' ? '#7f1d1d' : '#1e3a8a'}; margin-top: 8px; display: block; font-style: italic;">
                    "${request.supervisor_notes}"
                </span>
            </div>
        `;
    }
    
    detailsHTML += `</div>`;
    
    modalBody.innerHTML = detailsHTML;
    modal.style.display = 'block';
}

// View Details Modal
function viewDetails(request) {
    const modal = document.getElementById('detailsModal');
    const modalBody = document.getElementById('modalBody');
    const modalTitle = document.getElementById('modalTitle');
    
    modalTitle.textContent = 'Request Details';

    
    // Build details HTML
    let detailsHTML = `
        <div class="details-grid">
            <div class="detail-item">
                <span class="detail-label">Equipment Name:</span>
                <span class="detail-value">${request.equipment_name}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Quantity:</span>
                <span class="detail-value">${request.quantity}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Priority:</span>
                <span class="detail-value">
                    <span class="priority-badge priority-${request.priority.toLowerCase()}">${request.priority}</span>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-badge status-${request.status.toLowerCase()}">${request.status}</span>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Requested Date:</span>
                <span class="detail-value">${new Date(request.requested_date).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'})}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Estimated Cost:</span>
                <span class="detail-value">Rs. ${parseFloat(request.estimated_cost).toFixed(2)}</span>
            </div>
    `;
    
    if (request.actual_cost && request.actual_cost > 0) {
        detailsHTML += `
            <div class="detail-item">
                <span class="detail-label">Actual Cost:</span>
                <span class="detail-value">Rs. ${parseFloat(request.actual_cost).toFixed(2)}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Total Cost:</span>
                <span class="detail-value"><strong>Rs. ${parseFloat(request.total_cost).toFixed(2)}</strong></span>
            </div>
        `;
    }
    
    detailsHTML += `
            <div class="detail-item full-width">
                <span class="detail-label">Reason:</span>
                <span class="detail-value">${request.reason}</span>
            </div>
    `;
    
    if (request.admin_remarks) {
        detailsHTML += `
            <div class="detail-item full-width">
                <span class="detail-label">Admin Remarks:</span>
                <span class="detail-value">${request.admin_remarks}</span>
            </div>
        `;
    }
    
    detailsHTML += `</div>`;
    
    modalBody.innerHTML = detailsHTML;
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('detailsModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>