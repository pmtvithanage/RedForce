<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>

<?php
$incident_reports = $data['incident_reports'];
// Sort incidents by created date (newest first)
usort($incident_reports, function ($a, $b) {
    return strtotime($b->created_at) - strtotime($a->created_at);
});

// Keep only the 4 most recent
$recent_incidents = array_slice($incident_reports, 0, 4);
?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/mobilerider/incident.css">

<!-- Dashboard Content -->
<div class="container">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon incidents">📋</div>
            <div class="stat-content">
                <div class="stat-label">Total Incidents</div>
                <div class="stat-value"><?php echo count($incident_reports); ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon open">⚠️</div>
            <div class="stat-content">
                <div class="stat-label">Open</div>
                <div class="stat-value">1</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon progress">📊</div>
            <div class="stat-content">
                <div class="stat-label">In Progress</div>
                <div class="stat-value">0</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon resolved">✅</div>
            <div class="stat-content">
                <div class="stat-label">Resolved</div>
                <div class="stat-value">3</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-grid">
        <!-- Recent Incidents -->
        <div class="section">
            <h2 class="section-title">Recent Incidents</h2>

            <?php if (empty($recent_incidents)): ?>
                <p>No incidents reported yet.</p>
            <?php else: ?>
                <?php foreach ($recent_incidents as $incident): ?>
                    <?php
                    $severityClass = 'severity-medium';
                    if (stripos($incident->severity, 'critical') !== false) $severityClass = 'severity-critical';
                    elseif (stripos($incident->severity, 'high') !== false) $severityClass = 'severity-high';
                    elseif (stripos($incident->severity, 'low') !== false) $severityClass = 'severity-low';

                    $formattedDate = date("m/d/Y h:i A", strtotime($incident->incident_date . ' ' . $incident->incident_time));
                    ?>

                    <div class="incident-item">
                        <div class="incident-header">
                            <div class="incident-title">
                                <?= htmlspecialchars(ucfirst($incident->incident_type)) ?>
                            </div>
                            <span class="severity-badge <?= $severityClass ?>">
                                <?= ucfirst($incident->severity) ?>
                            </span>
                        </div>

                        <div class="incident-description">
                            <?= htmlspecialchars($incident->incident_description) ?>
                        </div>

                        <div class="incident-meta">
                            <div class="meta-item">
                                <span>🕐</span>
                                <span><?= $formattedDate ?></span>
                            </div>
                            <div class="meta-item">
                                <span>📍</span>
                                <span><?= htmlspecialchars($incident->property_site ?: 'Unknown Location') ?></span>
                            </div>
                            <span class="status-badge status-open">Open</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <!-- Report Incident -->
            <div class="report-incident" onclick="reportIncident()">
                <span class="report-icon">🔺</span>
                <div class="report-text">Report Incident</div>
            </div>

            <!-- Incident Severity Distribution -->
            <div class="section">
                <h2 class="section-title">Incident Severity Distribution</h2>

                <div class="severity-item">
                    <div class="severity-info">
                        <div class="severity-label">Critical</div>
                        <div class="severity-bar critical" style="width: 25%;"></div>
                    </div>
                    <div class="severity-count">1</div>
                </div>

                <div class="severity-item">
                    <div class="severity-info">
                        <div class="severity-label">High</div>
                        <div class="severity-bar high" style="width: 25%;"></div>
                    </div>
                    <div class="severity-count">1</div>
                </div>

                <div class="severity-item">
                    <div class="severity-info">
                        <div class="severity-label">Medium</div>
                        <div class="severity-bar medium" style="width: 50%;"></div>
                    </div>
                    <div class="severity-count">2</div>
                </div>

                <div class="severity-item">
                    <div class="severity-info">
                        <div class="severity-label">Low</div>
                        <div class="severity-bar low" style="width: 0%;"></div>
                    </div>
                    <div class="severity-count">0</div>
                </div>
            </div>

            <!-- View All Reports -->
            <a href="#" class="view-all" onclick="viewAllReports(); return false;">
                <span class="view-all-icon">📂</span>
                <div class="view-all-text">View All Incident Reports</div>
            </a>
        </div>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<!-- Incident Report Popup -->
<div id="incident-popup" class="popup">
    <div class="popup-content">
        <div class="form-header">
            <button type="button" class="back-btn" onclick="closePopup()">←</button>
            <h2>Security Incident Report</h2>
            <button type="button" class="close-btn" onclick="closePopup()">×</button>
        </div>

        <form id="incident-form" method="POST" action="<?= URL_ROOT ?>/MobileRider/addIncident" enctype="multipart/form-data">
            <div class="form-body">
                <!-- Officer Information -->
                <div class="form-section">
                    <input type="hidden" name="incident_id" id="incident_id">

                    <div class="section-header">
                        <span class="section-icon">👤</span>
                        <span>Officer Information</span>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="officer_name" value="Amal Rathnayake" readonly>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" name="officer_role" value="Premise Officer" readonly>
                        </div>
                    </div>
                    <div class="form-row single">
                        <div class="form-group">
                            <label>Property / Leading Site</label>
                            <select name="property_site">
                                <option value="">--</option>
                                <option value="site1">Site 1</option>
                                <option value="site2">Site 2</option>
                                <option value="site3">Site 3</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Incident Type -->
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">⚠️</span>
                        <span>Incident Type</span>
                    </div>
                    <div class="form-row single">
                        <div class="form-group">
                            <label>Incident Type</label>
                            <select name="incident_type" required>
                                <option value="">-- Select Type --</option>
                                <option value="security">Security Breach</option>
                                <option value="network">Network Issue</option>
                                <option value="server">Server Outage</option>
                                <option value="physical">Physical Security</option>
                                <option value="fire">Fire Incident</option>
                                <option value="medical">Medical Emergency</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">📅</span>
                        <span>Date & Time</span>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="incident_date" required>
                        </div>
                        <div class="form-group">
                            <label>Time</label>
                            <input type="time" name="incident_time" required>
                        </div>
                    </div>
                </div>

                <!-- Incident Description -->
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">📝</span>
                        <span>Incident Description</span>
                    </div>
                    <div class="form-row single">
                        <div class="form-group">
                            <textarea name="incident_description" rows="4" placeholder="Describe the incident, including relevant details and circumstances" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Upload Media -->
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">📎</span>
                        <span>Upload Media (Optional)</span>
                    </div>
                    <div class="upload-area" onclick="document.getElementById('file-input').click()">
                        <div class="upload-icon">📁</div>
                        <div class="upload-text">Click to upload or drag and drop files</div>
                        <div class="upload-subtext">PNG, JPG or PDF (Max. 10 MB each)</div>
                    </div>
                    <input type="file" id="file-input" name="media_files[]" class="file-input-hidden" multiple accept="image/*,.pdf">
                    <div id="file-list" style="margin-top: 10px; font-size: 12px; color: #666;"></div>
                </div>

                <!-- Action Taken -->
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">✅</span>
                        <span>Action Taken</span>
                    </div>
                    <div class="form-row single">
                        <div class="form-group">
                            <textarea name="action_taken" rows="3" placeholder="Describe the immediate action taken in response to the incident"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">ℹ️</span>
                        <span>Additional Details</span>
                    </div>
                    <div class="form-row single">
                        <div class="checkbox-group">
                            <input type="checkbox" id="low" name="severity[]" value="low">
                            <label for="low">Low</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="medium" name="severity[]" value="medium">
                            <label for="medium">Medium</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="high" name="severity[]" value="high">
                            <label for="high">High</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="critical" name="severity[]" value="critical">
                            <label for="critical">Critical</label>
                        </div>
                    </div>
                    <div class="form-row single">
                        <div class="form-group">
                            <label>Follow-Up Investigation</label>
                            <input type="text" name="follow_up_id" placeholder="e.g., 202501210123">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="submit-section">
                <button type="submit" class="submit-btn">
                    <span>📋</span>
                    <span>Submit Incident Report</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View All Incidents Modal -->
<div class="modal-overlay" id="modalOverlay" hidden>
    <div class="modal-content">
        <div class="modal-header">
            <h2>Incident Reports</h2>
        </div>

        <div class="modal-body">
            <div class="controls">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" placeholder="Search incidents...">
                </div>
                <div class="filter-group">
                    <button type="button" class="filter-btn" id="statusFilter">
                        <span>⬇️</span>
                        <span>All Statuses</span>
                    </button>
                    <button type="button" class="filter-btn" id="severityFilter">
                        <span>⬇️</span>
                        <span>All Severities</span>
                    </button>
                    <button type="button" class="sort-btn" id="sortBtn">
                        <span>↕️</span>
                        <span>Latest First</span>
                    </button>
                </div>
            </div>

            <div class="table-container" id="tableContainer">
                <table id="incidentsTable">
                    <thead>
                        <tr>
                            <th>Incident</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Severity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>

            <!-- Single Incident Detail View -->
            <div id="incidentDetailView" class="incident-detail-view" hidden>
                <button type="button" class="back-btn" onclick="showAllIncidents()">← Back to All Incidents</button>

                <div class="incident-detail-content">
                    <h3 id="detailTitle">Incident Title</h3>
                    <p id="detailDescription">Full description goes here...</p>

                    <div class="detail-info">
                        <p><strong>Date:</strong> <span id="detailDate"></span></p>
                        <p><strong>Location:</strong> <span id="detailLocation"></span></p>
                        <p><strong>Severity:</strong> <span id="detailSeverity"></span></p>
                        <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                    </div>
                </div>

                <div class="detail-actions">
                    <button type="button" class="edit-btn" onclick="editIncident()">✏️ Edit</button>
                    <button type="button" class="delete-btn" onclick="deleteIncident()">🗑️ Delete</button>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="close-btn" onclick="closeAllReportsModal()">Close</button>
        </div>
    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<script>
    <?php
    $formatted_incidents = [];

    foreach ($incident_reports as $incident) {
        $formatted_incident = [
            'id' => (int) $incident->id,
            'title' => ucfirst($incident->incident_type),
            'description' => $incident->incident_description,
            'date' => date("m/d/Y h:i A", strtotime($incident->incident_date . ' ' . $incident->incident_time)),
            'location' => $incident->property_site ?? 'N/A',
            'severity' => strtolower($incident->severity ?? 'unknown'),
            'status' => 'open',
            'action_taken' => $incident->action_taken ?? '',
            'follow_up_id' => $incident->follow_up_id ?? '',
        ];
        $formatted_incidents[] = $formatted_incident;
    }

    $formatted_recent_incidents = array_slice($formatted_incidents, 0, 4);
    ?>

    const incidents = <?php echo json_encode($formatted_incidents, JSON_PRETTY_PRINT); ?>;
    const recentIncidents = <?php echo json_encode($formatted_recent_incidents, JSON_PRETTY_PRINT); ?>;

    let filteredIncidents = [...incidents];
    let currentSort = "latest";
    let currentNoteId = null;

    function renderTable(data) {
        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = data.map((incident, index) => `
        <tr data-id="${incident.id}" style="cursor: pointer;">
            <td class="incident-col">
                <span class="incident-title">${incident.title}</span>
                <span class="incident-desc">${incident.description}</span>
            </td>
            <td>${incident.date}</td>
            <td>${incident.location}</td>
            <td>
                <span class="severity-badge severity-${incident.severity}">
                    ${incident.severity.charAt(0).toUpperCase() + incident.severity.slice(1)}
                </span>
            </td>
            <td>
                <span class="status-badge status-${incident.status}">
                    ${incident.status.charAt(0).toUpperCase() + incident.status.slice(1)}
                </span>
            </td>
        </tr>
    `).join("");

        document.querySelectorAll('#tableBody tr').forEach((row, i) => {
            row.addEventListener('click', () => {
                showIncidentDetail(data[i]);
            });
        });
    }

    function showIncidentDetail(incident) {
        const tableContainer = document.getElementById('tableContainer');
        const detailView = document.getElementById('incidentDetailView');

        tableContainer.hidden = true;
        detailView.hidden = false;

        document.getElementById('detailTitle').textContent = incident.title;
        document.getElementById('detailDescription').textContent = incident.description;
        document.getElementById('detailDate').textContent = incident.date;
        document.getElementById('detailLocation').textContent = incident.location;
        document.getElementById('detailSeverity').textContent = incident.severity;
        document.getElementById('detailStatus').textContent = incident.status;

        detailView.dataset.currentId = incident.id;
    }

    function showAllIncidents() {
        const tableContainer = document.getElementById('tableContainer');
        const detailView = document.getElementById('incidentDetailView');
        detailView.hidden = true;
        tableContainer.hidden = false;
    }

    function editIncident() {
        const id = document.getElementById('incidentDetailView').dataset.currentId;
        const incident = incidents.find(i => i.id == id);
        if (!incident) return;

        closeAllReportsModal();

        const popup = document.getElementById('incident-popup');
        popup.classList.add('show');
        document.body.style.overflow = 'hidden';

        document.getElementById('incident_id').value = incident.id;

        document.querySelector('select[name="property_site"]').value = incident.location || '';
        document.querySelector('select[name="incident_type"]').value = incident.title.toLowerCase();

        const dateObj = new Date(incident.date);
        const isoDate = dateObj.toISOString().split('T')[0];
        document.querySelector('input[name="incident_date"]').value = isoDate;

        const timeParts = incident.date.match(/(\d{1,2}):(\d{2})\s(AM|PM)/i);
        if (timeParts) {
            let hours = parseInt(timeParts[1]);
            const minutes = timeParts[2];
            const period = timeParts[3].toUpperCase();

            if (period === 'PM' && hours !== 12) hours += 12;
            if (period === 'AM' && hours === 12) hours = 0;

            const timeString = `${String(hours).padStart(2, '0')}:${minutes}`;
            document.querySelector('input[name="incident_time"]').value = timeString;
        }

        document.querySelector('textarea[name="incident_description"]').value = incident.description;
        document.querySelector('textarea[name="action_taken"]').value = incident.action_taken || '';
        document.querySelector('input[name="follow_up_id"]').value = incident.follow_up_id || '';

        const severityMap = {
            'moderate': 'medium',
            'low': 'low',
            'high': 'high',
            'critical': 'critical'
        };
        const severityLevel = severityMap[incident.severity.toLowerCase()] || '';
        ['low', 'medium', 'high', 'critical'].forEach(level => {
            const checkbox = document.querySelector(`input[name="severity[]"][value="${level}"]`);
            checkbox.checked = level === severityLevel;
        });

        document.querySelector('#incident-form .submit-btn span:last-child').textContent = 'Update Incident';
    }

    function deleteIncident() {
        const id = document.getElementById('incidentDetailView').dataset.currentId;
        if (confirm('Are you sure you want to delete this incident?')) {
            window.location.href = `<?php echo URL_ROOT; ?>/MobileRider/deleteIncident?id=${id}`;
        }
    }

    function filterAndRender() {
        const searchTerm = document.getElementById("searchInput").value.toLowerCase();
        const statusFilter = document.getElementById("statusFilter").dataset.filter || "all";
        const severityFilter = document.getElementById("severityFilter").dataset.filter || "all";

        filteredIncidents = incidents.filter(incident => {
            const matchesSearch = incident.title.toLowerCase().includes(searchTerm) ||
                incident.description.toLowerCase().includes(searchTerm);
            const matchesStatus = statusFilter === "all" || incident.status === statusFilter;
            const matchesSeverity = severityFilter === "all" || incident.severity === severityFilter;
            return matchesSearch && matchesStatus && matchesSeverity;
        });

        if (currentSort === "latest") {
            filteredIncidents.sort((a, b) => new Date(b.date) - new Date(a.date));
        } else {
            filteredIncidents.sort((a, b) => new Date(a.date) - new Date(b.date));
        }

        renderTable(filteredIncidents);
    }

    function reportIncident() {
        const popup = document.getElementById('incident-popup');
        popup.classList.add('show');
        document.body.style.overflow = 'hidden';
        document.getElementById('incident_id').value = '';
        document.querySelector('#incident-form .submit-btn span:last-child').textContent = 'Submit Incident Report';
        document.getElementById('incident-form').reset();
    }

    function closePopup() {
        const popup = document.getElementById('incident-popup');
        popup.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function viewAllReports() {
        const modal = document.getElementById('modalOverlay');
        modal.removeAttribute('hidden');
        document.body.style.overflow = 'hidden';

        const tableContainer = document.getElementById('tableContainer');
        const detailView = document.getElementById('incidentDetailView');
        tableContainer.hidden = false;
        detailView.hidden = true;

        renderTable(incidents);
    }

    function closeAllReportsModal() {
        const modal = document.getElementById('modalOverlay');
        modal.setAttribute('hidden', '');
        document.body.style.overflow = 'auto';
    }

    function openIncidentModal(incident) {
        const modal = document.getElementById('modalOverlay');
        modal.removeAttribute('hidden');
        document.body.style.overflow = 'hidden';

        const tableContainer = document.getElementById('tableContainer');
        const detailView = document.getElementById('incidentDetailView');
        tableContainer.hidden = true;
        detailView.hidden = false;

        document.getElementById('detailTitle').textContent = incident.title;
        document.getElementById('detailDescription').textContent = incident.description;
        document.getElementById('detailDate').textContent = incident.date;
        document.getElementById('detailLocation').textContent = incident.location;
        document.getElementById('detailSeverity').textContent = incident.severity;
        document.getElementById('detailStatus').textContent = incident.status;
        detailView.dataset.currentId = incident.id;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.incident-item').forEach((item, index) => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                if (index < recentIncidents.length) {
                    const incident = recentIncidents[index];
                    openIncidentModal(incident);
                }
            });
        });

        const popup = document.getElementById('incident-popup');
        if (popup) {
            popup.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePopup();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePopup();
                closeAllReportsModal();
            }
        });

        const fileInput = document.getElementById('file-input');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const files = e.target.files;
                const fileList = document.getElementById('file-list');

                if (files.length > 0) {
                    let fileNames = [];
                    for (let i = 0; i < files.length; i++) {
                        fileNames.push(files[i].name);
                    }
                    fileList.innerHTML = `<strong>Selected files:</strong> ${fileNames.join(', ')}`;
                } else {
                    fileList.innerHTML = '';
                }
            });
        }

        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', filterAndRender);
        }

        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('click', function() {
                const statuses = ["all", "open", "resolved", "progress"];
                const current = this.dataset.filter || "all";
                const nextIndex = (statuses.indexOf(current) + 1) % statuses.length;
                const next = statuses[nextIndex];

                this.dataset.filter = next;
                this.innerHTML = `<span>⬇️</span><span>${next === "all" ? "All Statuses" : next.charAt(0).toUpperCase() + next.slice(1)}</span>`;
                filterAndRender();
            });
        }

        const severityFilter = document.getElementById('severityFilter');
        if (severityFilter) {
            severityFilter.addEventListener('click', function() {
                const severities = ["all", "critical", "high", "medium", "low"];
                const current = this.dataset.filter || "all";
                const nextIndex = (severities.indexOf(current) + 1) % severities.length;
                const next = severities[nextIndex];

                this.dataset.filter = next;
                this.innerHTML = `<span>⬇️</span><span>${next === "all" ? "All Severities" : next.charAt(0).toUpperCase() + next.slice(1)}</span>`;
                filterAndRender();
            });
        }

        const sortBtn = document.getElementById('sortBtn');
        if (sortBtn) {
            sortBtn.addEventListener('click', function() {
                currentSort = currentSort === "latest" ? "oldest" : "latest";
                this.innerHTML = `<span>↕️</span><span>${currentSort === "latest" ? "Latest First" : "Oldest First"}</span>`;
                filterAndRender();
            });
        }

        const modalOverlay = document.getElementById('modalOverlay');
        if (modalOverlay) {
            modalOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAllReportsModal();
                }
            });
        }

        document.getElementById('incidentDetailView').hidden = true;

        const incidentForm = document.getElementById('incident-form');
        if (incidentForm) {
            incidentForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const incidentId = document.getElementById('incident_id').value;
                const isUpdate = incidentId && incidentId !== '';

                const actionUrl = isUpdate ?
                    `<?php echo URL_ROOT; ?>/MobileRider/updateIncident` :
                    `<?php echo URL_ROOT; ?>/MobileRider/addIncident`;

                incidentForm.action = actionUrl;
                incidentForm.submit();
            });
        }

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('report') === 'open') {
            reportIncident();

            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    });

    document.querySelectorAll('.incident-item').forEach(item => {
        item.addEventListener('click', function() {
            this.style.backgroundColor = 'rgba(236, 72, 153, 0.1)';
            setTimeout(() => {
                this.style.backgroundColor = '';
            }, 300);
        });
    });

    window.addEventListener('load', function() {
        setTimeout(() => {
            document.querySelectorAll('.severity-bar').forEach((bar) => {
                bar.style.transition = 'width 1s ease';
            });
        }, 500);
    });
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>