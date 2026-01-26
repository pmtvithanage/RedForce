<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>

<style>
/* Page Container */
.page-container {
  margin: 20px;
}

/* Stats Container */
.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  padding: 30px;
  margin-bottom: 30px;
}

/* Stat Card */
.stat-card {
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 30px;
  border-left: 5px solid #ccc;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:nth-child(1) { border-left-color: #9333ea; } /* Purple */
.stat-card:nth-child(2) { border-left-color: #22c55e; } /* Green */

.stat-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
}

/* Icons */
.stat-icon {
  font-size: 48px;
  transition: transform 0.3s ease;
  color: #555;
}

.stat-card:nth-child(1) .stat-icon { color: #9333ea; }
.stat-card:nth-child(2) .stat-icon { color: #22c55e; }

.stat-card:hover .stat-icon {
  transform: scale(1.15);
}

/* Stat text */
.stat-content {
  flex: 1;
}

.stat-value {
  font-size: 36px;
  font-weight: bold;
  color: #1f2937;
  line-height: 1;
  margin-bottom: 8px;
}

.stat-label {
  font-size: 15px;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Route Info Section */
.route-info-section {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  margin-bottom: 30px;
  
  display: flex;
  gap: 30px;
  align-items: flex-start;
}

.route-header {
  flex: 1;
}

.route-title {
  font-size: 28px;
  font-weight: bold;
  color: #1f2937;
  margin-bottom: 10px;
}

.route-description {
  font-size: 16px;
  color: #6b7280;
  margin-bottom: 20px;
  line-height: 1.6;
}

.route-status {
  display: inline-block;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
  text-transform: uppercase;
}

.status-active {
  background: #d1fae5;
  color: #065f46;
}

.status-inactive {
  background: #fee2e2;
  color: #991b1b;
}

.route-stats {
  display: flex;
  flex-direction: row;
  gap: 15px;
  min-width: 600px;
}

@media (max-width: 968px) {
  .route-info-section {
    flex-direction: column;
  }
  
  .route-stats {
    width: 100%;
    min-width: auto;
    flex-direction: column;
  }
}

/* Content Grid */
.content-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px;
  margin-bottom: 30px;
}

@media (max-width: 1024px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
}

/* Map Section */
.map-section {
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.section-title {
  font-size: 20px;
  font-weight: bold;
  color: #1f2937;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

#route-map {
  width: 100%;
  height: 500px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

/* Sites Table Section */
.sites-section {
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.sites-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.sites-table thead {
  background: #f9fafb;
}

.sites-table th {
  padding: 12px 16px;
  text-align: left;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
}

.sites-table td {
  padding: 12px 16px;
  font-size: 14px;
  color: #1f2937;
  border-bottom: 1px solid #e5e7eb;
}

.sites-table tbody tr:hover {
  background: #f9fafb;
}

.no-sites {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-sites .material-symbols-outlined {
  font-size: 48px;
  margin-bottom: 16px;
  opacity: 0.5;
}

.no-route {
  background: #fff3cd;
  color: #856404;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  margin: 20px;
  border: 1px solid #ffeaa7;
}

.no-route .material-symbols-outlined {
  font-size: 48px;
  margin-bottom: 10px;
}

/* Visit Button */
.visit-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: #f3f4f6;
  color: #374151;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.visit-btn:hover {
  background: #e5e7eb;
  transform: translateY(-2px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.visit-btn .material-symbols-outlined {
  font-size: 18px;
}

.visit-btn.visited {
  background: #22c55e;
  color: white;
  border-color: #22c55e;
  cursor: default;
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.3);
}

.visit-btn.visited:hover {
  background: #22c55e;
  transform: none;
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.3);
}

/* Confirmation Modal */
.confirm-modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 10000;
  justify-content: center;
  align-items: center;
  animation: fadeIn 0.3s ease;
}

.confirm-modal.show {
  display: flex;
}

.confirm-modal-content {
  background: white;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  max-width: 400px;
  width: 90%;
  animation: slideUp 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.confirm-modal-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
}

.confirm-modal-icon {
  font-size: 32px;
  color: #22c55e;
}

.confirm-modal-title {
  font-size: 20px;
  font-weight: bold;
  color: #1f2937;
  margin: 0;
}

.confirm-modal-body {
  margin-bottom: 24px;
  color: #6b7280;
  line-height: 1.6;
  font-size: 15px;
}

.confirm-modal-site-name {
  font-weight: 600;
  color: #1f2937;
}

.confirm-modal-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.confirm-btn {
  padding: 10px 24px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.confirm-btn-cancel {
  background: #f3f4f6;
  color: #374151;
}

.confirm-btn-cancel:hover {
  background: #e5e7eb;
}

.confirm-btn-confirm {
  background: #22c55e;
  color: white;
}

.confirm-btn-confirm:hover {
  background: #16a34a;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}
</style>

<?php require_once APP_ROOT . '/views/components/showNotification.php'; ?>

<div class="page-container">
  <?php if (isset($data['route']) && $data['route']): ?>
    <!-- Route Info Section -->
    <div class="route-info-section">
      <div class="route-header">
        <h2 class="route-title"><?php echo htmlspecialchars($data['route']->route_name); ?></h2>
        <?php if (!empty($data['route']->description)): ?>
          <p class="route-description"><?php echo htmlspecialchars($data['route']->description); ?></p>
        <?php endif; ?>
        <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
          <span class="route-status status-<?php echo strtolower($data['route']->status ?? 'active'); ?>">
            <?php echo htmlspecialchars($data['route']->status ?? 'Active'); ?>
          </span>
          <span style="background: #e0e7ff; color: #4338ca; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">
            <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">calendar_today</span>
            <?php echo date('F j, Y'); ?> (Today's visits)
          </span>
        </div>
      </div>

      <!-- Stats Container -->
      <div class="route-stats">
        <div class="stat-card">
          <span class="material-symbols-outlined stat-icon">location_city</span>
          <div class="stat-content">
            <div class="stat-value"><?php echo isset($data['total_sites']) ? $data['total_sites'] : '0'; ?></div>
            <div class="stat-label">Total Sites</div>
          </div>
        </div>

        <div class="stat-card">
          <span class="material-symbols-outlined stat-icon">task_alt</span>
          <div class="stat-content">
            <div class="stat-value"><?php echo isset($data['sites_visited']) ? $data['sites_visited'] : '0'; ?></div>
            <div class="stat-label">Sites Visited</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Grid: Map and Sites Table -->
    <div class="content-grid">
      <!-- Map Section -->
      <div class="map-section">
        <div class="section-title">
          <span class="material-symbols-outlined">map</span>
          Route Map
        </div>
        <div id="route-map"></div>
      </div>

      <!-- Sites Table Section -->
      <div class="sites-section">
        <div class="section-title">
          <span class="material-symbols-outlined">location_on</span>
          Assigned Sites
          <span class="sites-count" style="background: #a40000; color: white; padding: 2px 8px; border-radius: 12px; font-size: 14px; margin-left: auto;">
            <?php echo count($data['routeSites']); ?>
          </span>
        </div>

        <?php if (empty($data['routeSites'])): ?>
          <div class="no-sites">
            <span class="material-symbols-outlined">location_off</span>
            <h3>No sites assigned</h3>
            <p>This route doesn't have any sites assigned yet.</p>
          </div>
        <?php else: ?>
          <table class="sites-table">
            <thead>
              <tr>
                <th>Site Name</th>
                <th>Location</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data['routeSites'] as $site): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($site->site_name); ?></strong></td>
                  <td><?php echo htmlspecialchars($site->address ?? 'N/A'); ?></td>
                  <td>
                    <button 
                      class="visit-btn <?php echo isset($site->visited_today) && $site->visited_today ? 'visited' : ''; ?>" 
                      onclick="markAsVisited(<?php echo $site->id; ?>, this)"
                      <?php echo isset($site->visited_today) && $site->visited_today ? 'disabled' : ''; ?>>
                      <span class="material-symbols-outlined">check_circle</span>
                      <?php echo isset($site->visited_today) && $site->visited_today ? 'Visited' : 'Mark'; ?>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>
    <!-- No Route Assigned -->
    <div class="no-route">
      <span class="material-symbols-outlined">error</span>
      <h3><?php echo isset($data['error_message']) ? 'Profile Not Set Up' : 'No Route Assigned'; ?></h3>
      <p><?php echo isset($data['error_message']) ? htmlspecialchars($data['error_message']) : "You don't have a route assigned yet. Please contact your administrator."; ?></p>
    </div>
  <?php endif; ?>
</div>

<!-- Site Visit Form Modal -->
<div id="confirmModal" class="confirm-modal">
  <div class="confirm-modal-content" style="max-width: 600px;">
    <div class="confirm-modal-header">
      <span class="material-symbols-outlined confirm-modal-icon">fact_check</span>
      <h3 class="confirm-modal-title">Site Visit Report</h3>
    </div>
    <div class="confirm-modal-body">
      <p style="margin-bottom: 20px; font-weight: 600;">
        Site: <span class="confirm-modal-site-name" id="confirmSiteName"></span>
      </p>
      
      <form id="visitForm" style="display: flex; flex-direction: column; gap: 20px;">
        <!-- Officer Attendance Satisfactory -->
        <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: #f9fafb; border-radius: 8px;">
          <input type="checkbox" id="attendanceSatisfactory" name="attendance_satisfactory" 
                 style="width: 20px; height: 20px; cursor: pointer; accent-color: #22c55e;">
          <label for="attendanceSatisfactory" style="cursor: pointer; font-weight: 600; color: #1f2937; margin: 0;">
            Officer attendance is satisfactory
          </label>
        </div>

        <!-- Site Condition -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <label for="siteCondition" style="font-weight: 600; color: #1f2937;">
            Site Condition <span style="color: #ef4444;">*</span>
          </label>
          <select id="siteCondition" name="site_condition" required
                  style="padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: #1f2937;">
            <option value="Excellent">Excellent</option>
            <option value="Good" selected>Good</option>
            <option value="Fair">Fair</option>
            <option value="Poor">Poor</option>
          </select>
        </div>

        <!-- Officer Activities -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <label for="officerActivities" style="font-weight: 600; color: #1f2937;">
            Officer Activities <span style="color: #ef4444;">*</span>
          </label>
          <select id="officerActivities" name="officer_activities" required
                  style="padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: #1f2937;">
            <option value="">Select activity...</option>
            <option value="Patrolling">Patrolling</option>
            <option value="Monitoring entrance">Monitoring entrance</option>
            <option value="Access control">Access control</option>
            <option value="Perimeter check">Perimeter check</option>
            <option value="Surveillance monitoring">Surveillance monitoring</option>
            <option value="Vehicle inspection">Vehicle inspection</option>
            <option value="Visitor management">Visitor management</option>
            <option value="Emergency response">Emergency response</option>
            <option value="On break">On break</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <!-- Issues Found -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <label for="issuesFound" style="font-weight: 600; color: #1f2937;">
            Issues or Concerns
          </label>
          <select id="issuesFound" name="issues_found"
                  style="padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: #1f2937;">
            <option value="">No issues</option>
            <option value="Officer absent">Officer absent</option>
            <option value="Unauthorized access">Unauthorized access</option>
            <option value="Equipment malfunction">Equipment malfunction</option>
            <option value="Uniform not proper">Uniform not proper</option>
            <option value="Officer sleeping">Officer sleeping</option>
            <option value="Security breach">Security breach</option>
            <option value="Maintenance required">Maintenance required</option>
            <option value="Lighting issues">Lighting issues</option>
            <option value="Gate/door damage">Gate/door damage</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <!-- Additional Notes -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <label for="additionalNotes" style="font-weight: 600; color: #1f2937;">
            Additional Notes
          </label>
          <textarea id="additionalNotes" name="notes"
                    placeholder="Any additional observations or comments (optional)"
                    rows="2"
                    style="padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: #1f2937; resize: vertical; font-family: inherit;"></textarea>
        </div>
      </form>
    </div>
    <div class="confirm-modal-actions">
      <button class="confirm-btn confirm-btn-cancel" onclick="closeConfirmModal()">Cancel</button>
      <button class="confirm-btn confirm-btn-confirm" onclick="confirmVisit()">Submit Report</button>
    </div>
  </div>
</div>

<!-- Google Maps Script -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGwijY64zQTmizwDN6omOoI9nzxb1MQog&libraries=drawing,geometry,places"></script>
<script>
let map;
let routePolygon = null;
let markers = [];

function initRouteMap() {
    <?php if (isset($data['route']) && $data['route']): ?>
        const routeData = <?php echo json_encode($data['route']); ?>;
        const sitesData = <?php echo json_encode($data['routeSites']); ?>;

        // Calculate center from sites or use default
        let centerLat = 6.9271; // Default: Colombo, Sri Lanka
        let centerLng = 79.8612;
        let hasCoords = false;

        if (sitesData && sitesData.length > 0) {
            let latSum = 0;
            let lngSum = 0;
            let count = 0;

            sitesData.forEach(site => {
                if (site.latitude && site.longitude) {
                    latSum += parseFloat(site.latitude);
                    lngSum += parseFloat(site.longitude);
                    count++;
                }
            });

            if (count > 0) {
                centerLat = latSum / count;
                centerLng = lngSum / count;
                hasCoords = true;
            }
        }

        // Initialize map
        map = new google.maps.Map(document.getElementById('route-map'), {
            zoom: hasCoords ? 12 : 11,
            center: { lat: centerLat, lng: centerLng },
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true
        });

        // Draw route boundary if available
        if (routeData.location) {
            try {
                const boundaryCoords = JSON.parse(routeData.location);
                if (Array.isArray(boundaryCoords) && boundaryCoords.length > 0) {
                    const polygonPath = boundaryCoords.map(coord => ({
                        lat: parseFloat(coord.lat),
                        lng: parseFloat(coord.lng)
                    }));

                    routePolygon = new google.maps.Polygon({
                        paths: polygonPath,
                        strokeColor: '#a40000',
                        strokeOpacity: 0.8,
                        strokeWeight: 3,
                        fillColor: '#a40000',
                        fillOpacity: 0.2,
                        editable: false,
                        draggable: false
                    });

                    routePolygon.setMap(map);

                    // Fit map to polygon bounds
                    const bounds = new google.maps.LatLngBounds();
                    polygonPath.forEach(point => bounds.extend(point));
                    map.fitBounds(bounds);
                }
            } catch (e) {
                console.log('No valid route boundary data');
            }
        }

        // Add markers for sites
        if (sitesData && sitesData.length > 0) {
            sitesData.forEach((site, index) => {
                if (site.latitude && site.longitude) {
                    const marker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(site.latitude),
                            lng: parseFloat(site.longitude)
                        },
                        map: map,
                        title: site.site_name,
                        label: {
                            text: (index + 1).toString(),
                            color: 'white',
                            fontSize: '14px',
                            fontWeight: 'bold'
                        },
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 20,
                            fillColor: '#a40000',
                            fillOpacity: 1,
                            strokeColor: 'white',
                            strokeWeight: 2
                        }
                    });

                    // Add info window
                    const infoWindow = new google.maps.InfoWindow({
                        content: `
                            <div style="padding: 10px; min-width: 200px;">
                                <h3 style="margin: 0 0 8px 0; color: #1f2937; font-size: 16px;">${site.site_name}</h3>
                                <p style="margin: 4px 0; color: #6b7280; font-size: 14px;">
                                    <strong>Address:</strong> ${site.address || 'N/A'}
                                </p>
                                <p style="margin: 4px 0; color: #6b7280; font-size: 14px;">
                                    <strong>Client:</strong> ${site.client_name || site.client_user_name || 'N/A'}
                                </p>
                                <a href="https://www.google.com/maps/search/?api=1&query=${site.latitude},${site.longitude}" 
                                   target="_blank" 
                                   style="display: inline-block; margin-top: 10px; padding: 8px 16px; background: #a40000; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: background 0.3s ease;"
                                   onmouseover="this.style.background='#8b0000'" 
                                   onmouseout="this.style.background='#a40000'">
                                    <span style="vertical-align: middle;">View in Google Maps</span>
                                </a>
                            </div>
                        `
                    });

                    marker.addListener('click', () => {
                        // Close all other info windows
                        markers.forEach(m => {
                            if (m.infoWindow) m.infoWindow.close();
                        });
                        infoWindow.open(map, marker);
                    });

                    // Store marker with site ID for later reference
                    markers.push({ 
                        marker, 
                        infoWindow, 
                        siteId: site.id,
                        isVisited: site.visited_today || false
                    });
                    
                    // If already visited, set marker to green
                    if (site.visited_today) {
                        marker.setIcon({
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 20,
                            fillColor: '#22c55e',
                            fillOpacity: 1,
                            strokeColor: 'white',
                            strokeWeight: 2
                        });
                    }
                }
            });

            // Fit bounds to include all markers if no polygon
            if (!routePolygon && markers.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                markers.forEach(m => bounds.extend(m.marker.getPosition()));
                map.fitBounds(bounds);
            }
        }
    <?php else: ?>
        // No route - show default map
        map = new google.maps.Map(document.getElementById('route-map'), {
            zoom: 11,
            center: { lat: 6.9271, lng: 79.8612 } // Colombo, Sri Lanka
        });
    <?php endif; ?>
}

// Initialize map when page loads
google.maps.event.addDomListener(window, 'load', initRouteMap);

// Function to update marker color to green
function updateMarkerToVisited(siteId) {
    const markerData = markers.find(m => m.siteId === siteId);
    if (markerData && !markerData.isVisited) {
        markerData.marker.setIcon({
            path: google.maps.SymbolPath.CIRCLE,
            scale: 20,
            fillColor: '#22c55e',
            fillOpacity: 1,
            strokeColor: 'white',
            strokeWeight: 2
        });
        markerData.isVisited = true;
    }
}

// Modal variables
let currentSiteId = null;
let currentButton = null;

function showConfirmModal(siteId, siteName, button) {
    currentSiteId = siteId;
    currentButton = button;
    document.getElementById('confirmSiteName').textContent = siteName;
    
    // Reset form
    document.getElementById('visitForm').reset();
    
    document.getElementById('confirmModal').classList.add('show');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('show');
    
    // Reset form
    document.getElementById('visitForm').reset();
    
    currentSiteId = null;
    currentButton = null;
}

function confirmVisit() {
    if (!currentSiteId || !currentButton) {
        return;
    }
    
    // Validate form
    const form = document.getElementById('visitForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Collect form data
    const formData = {
        site_id: currentSiteId,
        officer_attendance_satisfactory: document.getElementById('attendanceSatisfactory').checked ? 1 : 0,
        officer_activities: document.getElementById('officerActivities').value.trim(),
        site_condition: document.getElementById('siteCondition').value,
        issues_found: document.getElementById('issuesFound').value.trim() || null,
        notes: document.getElementById('additionalNotes').value.trim() || null
    };
    
    // Store values before closing modal
    const siteId = currentSiteId;
    const button = currentButton;
    
    closeConfirmModal();
    processVisit(siteId, button, formData);
}

// Mark site as visited
function markAsVisited(siteId, button) {
    if (button.classList.contains('visited') || button.disabled) {
        return; // Already visited
    }
    
    // Get site name from button's row
    const siteName = button.closest('tr').querySelector('td strong').textContent;
    showConfirmModal(siteId, siteName, button);
}

// Process the visit after confirmation
function processVisit(siteId, button, formData) {
        console.log('Processing visit for site:', siteId);
        console.log('Form data:', formData);
        
        // Disable button to prevent double-clicks
        button.disabled = true;
        const originalText = button.innerHTML;

        // Send AJAX request to backend
        fetch('<?php echo URL_ROOT; ?>/MobileRider/markSiteVisited', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Update button to visited state
                button.classList.add('visited');
                button.innerHTML = '<span class="material-symbols-outlined">check_circle</span> Visited';
                
                // Update marker color on map
                updateMarkerToVisited(siteId);
                
                // Update sites visited count
                const sitesVisitedElements = document.querySelectorAll('.stat-value');
                if (sitesVisitedElements.length > 1) {
                    const currentCount = parseInt(sitesVisitedElements[1].textContent);
                    sitesVisitedElements[1].textContent = currentCount + 1;
                }
                
                // Show success notification if available
                if (typeof showNotification === 'function') {
                    showNotification(data.message, 'success');
                }
            } else {
                // Re-enable button on error
                button.disabled = false;
                button.innerHTML = originalText;
                alert(data.message || 'Failed to mark site as visited');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Re-enable button on error
            button.disabled = false;
            button.innerHTML = originalText;
            alert('An error occurred. Please try again.');
        });
}

// Close modal when clicking outside (wait for DOM to be ready)
window.addEventListener('load', function() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });
    }
});
</script>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>