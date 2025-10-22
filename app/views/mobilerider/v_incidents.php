<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>


<!-- Content will be loaded here -->

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
                <div class="stat-value">4</div>
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

            <div class="incident-item">
                <div class="incident-header">
                    <div class="incident-title">Database Performance Degradation</div>
                    <span class="severity-badge severity-medium">Medium</span>
                </div>
                <div class="incident-description">Customer database showing slow query responses</div>
                <div class="incident-meta">
                    <div class="meta-item">
                        <span>🕐</span>
                        <span>4/17/2023 01:20 PM</span>
                    </div>
                    <div class="meta-item">
                        <span>📍</span>
                        <span>Database Cluster</span>
                    </div>
                    <span class="status-badge status-open">Open</span>
                </div>
            </div>

            <div class="incident-item">
                <div class="incident-header">
                    <div class="incident-title">Network Connectivity Issues</div>
                    <span class="severity-badge severity-medium">Medium</span>
                </div>
                <div class="incident-description">Users in Building B reporting intermittent connectivity</div>
                <div class="incident-meta">
                    <div class="meta-item">
                        <span>🕐</span>
                        <span>4/16/2023 10:45 AM</span>
                    </div>
                    <div class="meta-item">
                        <span>📍</span>
                        <span>Building B</span>
                    </div>
                    <span class="status-badge status-resolved">Resolved</span>
                </div>
            </div>

            <div class="incident-item">
                <div class="incident-header">
                    <div class="incident-title">Server Outage</div>
                    <span class="severity-badge severity-high">High</span>
                </div>
                <div class="incident-description">Main production server went down unexpectedly</div>
                <div class="incident-meta">
                    <div class="meta-item">
                        <span>🕐</span>
                        <span>4/15/2023 02:30 PM</span>
                    </div>
                    <div class="meta-item">
                        <span>📍</span>
                        <span>Data Center A</span>
                    </div>
                    <span class="status-badge status-resolved">Resolved</span>
                </div>
            </div>

            <div class="incident-item">
                <div class="incident-header">
                    <div class="incident-title">Security Breach Attempt</div>
                    <span class="severity-badge severity-critical">Critical</span>
                </div>
                <div class="incident-description">Multiple failed login attempts detected from unknown IP</div>
                <div class="incident-meta">
                    <div class="meta-item">
                        <span>🕐</span>
                        <span>4/14/2023 09:15 AM</span>
                    </div>
                    <div class="meta-item">
                        <span>📍</span>
                        <span>Authentication System</span>
                    </div>
                    <span class="status-badge status-resolved">Resolved</span>
                </div>
            </div>
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
            <a href="#" class="view-all" onclick="viewAllReports()">
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

        <form id="incident-form" method="POST" enctype="multipart/form-data">
            <div class="form-body">
                <!-- Officer Information -->
                <div class="form-section">
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

                <!-- Location Verification -->
                <!-- <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">📍</span>
                        <span>Location Verification (Optional)</span>
                    </div>
                    <button type="button" class="location-btn" id="capture-location">
                        <span>📍</span>
                        <span>Capture Current Location</span>
                    </button>
                    <div class="location-text">
                        By clicking 'Capture', you'll share your<br>
                        current location (GPS or IP address)
                    </div>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <div class="form-row single">
                        <div class="form-group">
                            <label>Or enter location manually</label>
                            <input type="text" name="location_manual" placeholder="Tiger Zone">
                        </div>
                    </div>
                </div> -->

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
                            <input type="checkbox" id="minor" name="severity[]" value="minor">
                            <label for="minor">Minor</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="moderate" name="severity[]" value="moderate">
                            <label for="moderate">Moderate</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="major" name="severity[]" value="major">
                            <label for="major">Major</label>
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

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<script>

// Incident Report Functions
function reportIncident() {
    console.log('Report incident clicked'); // Debug log
    const popup = document.getElementById('incident-popup');
    if (popup) {
        popup.classList.add('show');
        document.body.style.overflow = 'hidden';
    } else {
        console.error('Incident popup not found!');
    }
}

function closePopup() {
    console.log('Close popup clicked'); // Debug log
    const popup = document.getElementById('incident-popup');
    if (popup) {
        popup.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded - initializing incident report'); // Debug log
    
    // Add click event to report incident button
    const reportBtn = document.querySelector('.report-incident');
    if (reportBtn) {
        reportBtn.addEventListener('click', reportIncident);
        console.log('Report button found and event listener added');
    } else {
        console.error('Report incident button not found!');
    }
    
    // Close popup when clicking outside
    const popup = document.getElementById('incident-popup');
    if (popup) {
        popup.addEventListener('click', function(e) {
            if (e.target === this) {
                closePopup();
            }
        });
    }
    
    // Close with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePopup();
        }
    });
    
    // File upload handling
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
    
    // Form submission
    const incidentForm = document.getElementById('incident-form');
    if (incidentForm) {
        incidentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Incident report submitted successfully!');
            closePopup();
            this.reset();
            const fileList = document.getElementById('file-list');
            if (fileList) fileList.innerHTML = '';
        });
    }
});

function viewAllReports() {
    alert('View All Reports clicked!');
}

// Add hover effects to incident items
document.querySelectorAll('.incident-item').forEach(item => {
    item.addEventListener('click', function() {
        const title = this.querySelector('.incident-title').textContent;
        console.log(`Selected incident: ${title}`);

        // Add visual feedback
        this.style.backgroundColor = 'rgba(236, 72, 153, 0.1)';
        setTimeout(() => {
            this.style.backgroundColor = '';
        }, 300);
    });
});

// Animate severity bars on load
window.addEventListener('load', function() {
    setTimeout(() => {
        document.querySelectorAll('.severity-bar').forEach((bar, index) => {
            bar.style.transition = 'width 1s ease';
            bar.style.width = bar.style.width || '0%';
        });
    }, 500);
});
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>