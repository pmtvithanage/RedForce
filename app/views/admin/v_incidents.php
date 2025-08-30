<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
  <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/incidents_style.css">


    <!-- Content will be loaded here -->
     <div class="container">
        <!-- Summary Cards Section -->
        <div class="summary-section">
            <div class="summary-card resolved">
                <div class="card-icon">
                    <i class="fas fa-file-alt"></i>
                    <i class="fas fa-check-circle check-icon"></i>
                </div>
                <div class="card-content">
                    <h3>Resolved Incidents</h3>
                    <p class="count">122</p>
                </div>
            </div>
            
            <div class="summary-card pending">
                <div class="card-icon">
                    <i class="fas fa-desktop"></i>
                    <i class="fas fa-exclamation-triangle warning-icon"></i>
                </div>
                <div class="card-content">
                    <h3>Pending Incidents</h3>
                    <p class="count">3</p>
                </div>
            </div>
        </div>

        <!-- Recent Incidents Table Section -->
        <div class="table-section">
            <div class="table-header">
                <h2>Recent Incidents</h2>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" placeholder="Search" class="search-input">
                </div>
            </div>
            
            <div class="table-container">
                <table id="incidentsTable">
                    <thead>
                        <tr>
                            <th>Incident ID</th>
                            <th>Site</th>
                            <th>Location</th>
                            <th>Officer</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody id="incidentsTableBody">
                        <!-- Table rows will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Incident Detail Modal -->
    <div id="incidentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Incident Details</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="incidentForm">
                    <div class="form-group">
                        <label for="modalIncidentId">Incident ID:</label>
                        <input type="text" id="modalIncidentId" name="incidentId" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalSite">Site:</label>
                        <input type="text" id="modalSite" name="site" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalLocation">Location:</label>
                        <textarea id="modalLocation" name="location" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalOfficer">Officer:</label>
                        <input type="text" id="modalOfficer" name="officer" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalStatus">Status:</label>
                        <select id="modalStatus" name="status" required>
                            <option value="Resolved">Resolved</option>
                            <option value="In View">In View</option>
                            <option value="Escalated">Escalated</option>
                            <option value="Pending Report">Pending Report</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalTime">Time:</label>
                        <input type="text" id="modalTime" name="time" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalDescription">Description:</label>
                        <textarea id="modalDescription" name="description" rows="4" placeholder="Enter incident description..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalNotes">Notes:</label>
                        <textarea id="modalNotes" name="notes" rows="3" placeholder="Enter additional notes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Save Changes</button>
            </div>
        </div>
    </div>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

      <script src="<?php echo URL_ROOT; ?>/js/admin/incidents.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>