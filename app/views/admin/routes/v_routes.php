<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<style>
  /* Stat Cards Container */
.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin: 20px;
}

/* Stat Card */
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

.stat-card:nth-child(1) { border-left-color: #9333ea; } /* Purple */
.stat-card:nth-child(2) { border-left-color: #22c55e; } /* Green */
.stat-card:nth-child(3) { border-left-color: #f59e0b; } /* Gold */
.stat-card:nth-child(4) { border-left-color: #ef4444; } /* Red */

.stat-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
}

/* Icons */
.stat-icon {
  font-size: 40px;
  transition: transform 0.3s ease;
  color: #555;
}

.stat-card:nth-child(1) .stat-icon { color: #9333ea; }
.stat-card:nth-child(2) .stat-icon { color: #22c55e; }
.stat-card:nth-child(3) .stat-icon { color: #f59e0b; }
.stat-card:nth-child(4) .stat-icon { color: #ef4444; }

.stat-card:hover .stat-icon {
  transform: scale(1.15);
}

/* Stat text */
.stat-content {
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

/* Route Cards */
.routes-section {
  position: relative;
  margin: 20px;
}

.routes-container {
  display: flex;
  gap: 20px;
  overflow-x: auto;
  scroll-behavior: smooth;
  padding: 10px 60px;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

.routes-container::-webkit-scrollbar {
  display: none;
}

.route-card {
  flex: 0 0 300px;
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  border-top: 4px solid #a40000;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.route-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
}

/* Navigation Arrows */
.scroll-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 40px;
  height: 40px;
  background: #a40000;
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  transition: all 0.3s ease;
  z-index: 10;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.scroll-arrow:hover {
  background: #bd0909;
  transform: translateY(-50%) scale(1.1);
}

.scroll-arrow.left {
  left: 10px;
}

.scroll-arrow.right {
  right: 10px;
}

.scroll-arrow:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Search Bar */
.search-section {
  margin: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
}

.search-container {
  position: relative;
  flex: 1;
  max-width: 500px;
}

.search-input {
  width: 100%;
  padding: 12px 45px 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 25px;
  font-size: 16px;
  background: #fff;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-input:focus {
  outline: none;
  border-color: #a40000;
  box-shadow: 0 4px 16px rgba(164, 0, 0, 0.2);
}

.search-icon {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #6b7280;
  font-size: 20px;
  pointer-events: none;
}

.clear-search {
  position: absolute;
  right: 45px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #6b7280;
  font-size: 18px;
  cursor: pointer;
  display: none;
  transition: color 0.3s ease;
}

.clear-search:hover {
  color: #a40000;
}

/* Add Route Button */
.add-route-btn {
  background: linear-gradient(135deg, #a40000, #bd0909);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 25px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
  white-space: nowrap;
}

.add-route-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(164, 0, 0, 0.4);
}

.add-route-btn:active {
  transform: translateY(0);
}

.add-route-btn .material-symbols-outlined {
  font-size: 20px;
}

@media (max-width: 768px) {
  .search-section {
    flex-direction: column;
    gap: 15px;
  }

  .search-container {
    width: 100%;
  }

  .add-route-btn {
    width: 100%;
    justify-content: center;
  }
}

/* Unassigned Sites Table */
.unassigned-sites-section {
  margin: 30px 20px;
}

.unassigned-sites-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 20px;
  text-align: center;
}

.sites-table-container {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  border: 1px solid #e5e7eb;
}

.sites-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.sites-table thead {
  background: linear-gradient(135deg, #a40000, #bd0909);
  color: white;
}

.sites-table th {
  padding: 16px 12px;
  text-align: left;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 12px;
}

.sites-table td {
  padding: 14px 12px;
  border-bottom: 1px solid #f1f5f9;
  color: #374151;
}

.sites-table tbody tr {
  transition: background-color 0.3s ease;
}

.sites-table tbody tr:hover {
  background-color: #f8fafc;
}

.sites-table tbody tr:last-child td {
  border-bottom: none;
}

/* Status badges */
.status-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-unassigned {
  background: #fef3c7;
  color: #d97706;
}

.status-pending {
  background: #dbeafe;
  color: #2563eb;
}

/* Action buttons in table */
.table-action-btn {
  background: #a40000;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-right: 8px;
}

.table-action-btn:hover {
  background: #bd0909;
  transform: translateY(-1px);
}

.table-action-btn.assign {
  background: #059669;
}

.table-action-btn.assign:hover {
  background: #047857;
}

.table-action-btn.view {
  background: #6b7280;
}

.table-action-btn.view:hover {
  background: #4b5563;
}

/* Route Select Dropdown */
.route-select {
  width: 100%;
  padding: 8px 12px;
  border: 2px solid #e5e7eb;
  border-radius: 6px;
  font-size: 13px;
  background: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.route-select:focus {
  outline: none;
  border-color: #a40000;
  box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
}

.route-select:disabled {
  background: #f3f4f6;
  cursor: not-allowed;
}

.route-select option {
  padding: 8px;
}

.route-select option:disabled {
  color: #9ca3af;
}

/* Empty state */
.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.empty-state .material-symbols-outlined {
  font-size: 48px;
  margin-bottom: 16px;
  opacity: 0.5;
}

.empty-state h3 {
  margin: 0 0 8px 0;
  font-size: 18px;
  color: #374151;
}

.empty-state p {
  margin: 0;
  font-size: 14px;
}

.route-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.route-name {
  font-size: 18px;
  font-weight: bold;
  color: #1f2937;
}

.route-status {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  background: #ffd3d3;
  color: #a40000;
}

.route-info {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 15px;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.info-label {
  font-size: 12px;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.info-value {
  font-size: 16px;
  font-weight: 600;
  color: #1f2937;
}

.route-actions {
  display: flex;
  justify-content: center;
  padding-top: 15px;
  border-top: 1px solid #e5e7eb;
}

.route-actions button {
  flex: none;
  padding: 8px 24px;
  border: none;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-view {
  background: #a40000;
  color: #fff;
}

.btn-view:hover {
  background: #bd0909;
}

</style>
    <?php require_once APP_ROOT . '/views/components/showNotification.php'; ?>

    <!-- Content will be loaded here -->
    <div class="stats-container">
      <div class="stat-card">
        <span class="material-symbols-outlined stat-icon">two_wheeler</span>
        <div class="stat-content">
          <div class="stat-value"><?php echo $data['totalRiders']; ?></div>
          <div class="stat-label">Total Riders</div>
        </div>
      </div>

      <div class="stat-card">
        <span class="material-symbols-outlined stat-icon">route</span>
        <div class="stat-content">
          <div class="stat-value"><?php echo $data['totalRoutes']; ?></div>
          <div class="stat-label">Total Routes</div>
        </div>
      </div>

      <div class="stat-card">
        <span class="material-symbols-outlined stat-icon">location_on</span>
        <div class="stat-content">
          <div class="stat-value"><?php echo $data['totalSites']; ?></div>
          <div class="stat-label">Total Sites</div>
        </div>
      </div>

      <div class="stat-card">
        <span class="material-symbols-outlined stat-icon">assignment</span>
        <div class="stat-content">
          <div class="stat-value"><?php echo $data['unassignedSitesCount']; ?></div>
          <div class="stat-label">To be assigned</div>
        </div>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="search-section">
      <div class="search-container">
        <input type="text" id="routeSearch" class="search-input" placeholder="Search routes by name, rider, or sites...">
        <span class="material-symbols-outlined search-icon">search</span>
        <button class="clear-search" id="clearSearch">×</button>
      </div>
      <button class="add-route-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/addroute'">
        <span class="material-symbols-outlined">add</span>
        Add Route
      </button>
    </div>

    <!-- Route Cards Section -->
    <div class="routes-section">
      <button class="scroll-arrow left" id="scrollLeft">
        <span class="material-symbols-outlined">chevron_left</span>
      </button>
      <div class="routes-container" id="routesContainer">
        <?php if(empty($data['routes'])): ?>
        <div class="no-routes">
          <span class="material-symbols-outlined">route</span>
          <p>No routes found. Create your first route!</p>
        </div>
        <?php else: ?>
        <?php foreach($data['routes'] as $route): ?>
        <div class="route-card">
          <div class="route-header">
            <div class="route-name"><?php echo htmlspecialchars($route->route_name); ?></div>
            <span class="route-status status-<?php echo strtolower($route->status); ?>"><?php echo $route->status; ?></span>
          </div>
          
          <div class="route-info">
            <div class="info-item">
              <div class="info-label">Mobile Rider</div>
              <div class="info-value"><?php echo $route->rider_name ? htmlspecialchars($route->rider_name) : 'Not Assigned'; ?></div>
            </div>
            <div class="info-item">
              <div class="info-label">Number of Sites</div>
              <div class="info-value"><?php echo $route->site_count ?? 0; ?></div>
            </div>
           
          </div>

          <div class="route-actions">
            <button class="btn-view" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/viewroute/<?php echo $route->id; ?>'">View</button>
          </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <button class="scroll-arrow right" id="scrollRight">
        <span class="material-symbols-outlined">chevron_right</span>
      </button>
    </div>

    <!-- Unassigned Sites Table -->
    <div class="unassigned-sites-section">
      <h2 class="unassigned-sites-title">Unassigned Sites</h2>
      <div class="sites-table-container">
        <table class="sites-table">
          <thead>
            <tr>
              <th>Site ID</th>
              <th>Site Name</th>
              <th>Location</th>
              <th>Status</th>
              <th>Priority</th>
              <th>Available Routes</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($data['sites'])): ?>
            <tr>
              <td colspan="7" style="text-align: center; padding: 20px;">
                <span class="material-symbols-outlined" style="font-size: 48px; color: #ccc;">location_off</span>
                <p>No sites found.</p>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach($data['sites'] as $site): ?>
            <tr data-site-id="<?php echo $site->id; ?>" data-site-address="<?php echo htmlspecialchars($site->address ?? ''); ?>">
              <td><?php echo htmlspecialchars($site->id); ?></td>
              <td><?php echo htmlspecialchars($site->site_name); ?></td>
              <td><?php echo htmlspecialchars($site->address ?? 'N/A'); ?></td>
              <td>
                <span class="status-badge status-unassigned">
                  Unassigned
                </span>
              </td>
              <td>Medium</td>
              <td>
                <select class="route-select" id="routeSelect_<?php echo $site->id; ?>" data-site-id="<?php echo $site->id; ?>">
                  <option value="">Select a route...</option>
                  <?php if(!empty($site->matching_routes)): ?>
                    <?php foreach($site->matching_routes as $route): ?>
                      <option value="<?php echo $route->id; ?>">
                        <?php echo htmlspecialchars($route->route_name); ?>
                        <?php if($route->distance_to_site > 0): ?>
                          (<?php echo number_format($route->distance_to_site, 1); ?> km)
                        <?php endif; ?>
                      </option>
                    <?php endforeach; ?>
                    
                    <?php if(count($site->matching_routes) < count($data['routes'])): ?>
                      <option disabled>--- Other Routes ---</option>
                      <?php 
                        $matchingIds = array_column($site->matching_routes, 'id');
                        foreach($data['routes'] as $route):
                          if(!in_array($route->id, $matchingIds)):
                      ?>
                        <option value="<?php echo $route->id; ?>" style="color: #9ca3af;">
                          <?php echo htmlspecialchars($route->route_name); ?> (Outside area)
                        </option>
                      <?php 
                          endif;
                        endforeach; 
                      ?>
                    <?php endif; ?>
                  <?php else: ?>
                    <option value="" disabled>No routes cover this area</option>
                    <?php foreach($data['routes'] as $route): ?>
                      <option value="<?php echo $route->id; ?>" style="color: #9ca3af;">
                        <?php echo htmlspecialchars($route->route_name); ?> (Outside area)
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </td>
              <td>
                <button class="table-action-btn assign" onclick="assignSiteToRoute('<?php echo $site->id; ?>')">Assign</button>
                
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
    <script>
      // Horizontal scrolling for route cards
      document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('routesContainer');
        const scrollLeft = document.getElementById('scrollLeft');
        const scrollRight = document.getElementById('scrollRight');
        
        // Only run this if the elements exist
        if (!container || !scrollLeft || !scrollRight) {
          console.log('Route scroll elements not found, skipping horizontal scroll setup');
          return;
        }
        
        const cardWidth = 320; // 300px card + 20px gap
        const visibleCards = Math.floor(container.offsetWidth / cardWidth);
        
        function updateArrowVisibility() {
          const maxScrollLeft = container.scrollWidth - container.clientWidth;
          
          scrollLeft.disabled = container.scrollLeft <= 0;
          scrollRight.disabled = container.scrollLeft >= maxScrollLeft - 1;
          
          scrollLeft.style.opacity = container.scrollLeft <= 0 ? '0.5' : '1';
          scrollRight.style.opacity = container.scrollLeft >= maxScrollLeft - 1 ? '0.5' : '1';
        }
        
        scrollLeft.addEventListener('click', function() {
          container.scrollBy({
            left: -cardWidth,
            behavior: 'smooth'
          });
        });
        
        scrollRight.addEventListener('click', function() {
          container.scrollBy({
            left: cardWidth,
            behavior: 'smooth'
          });
        });
        
        container.addEventListener('scroll', updateArrowVisibility);
        window.addEventListener('resize', updateArrowVisibility);
        
        // Initial check
        updateArrowVisibility();
      });

      // Search functionality
      const searchInput = document.getElementById('routeSearch');
      const clearSearch = document.getElementById('clearSearch');
      const routeCards = document.querySelectorAll('.route-card');

      function filterRoutes(searchTerm) {
        const term = searchTerm.toLowerCase();
        let hasVisibleCards = false;

        routeCards.forEach(card => {
          const routeName = card.querySelector('.route-name').textContent.toLowerCase();
          const riderName = card.querySelector('.info-value').textContent.toLowerCase();
          const startEnd = card.querySelectorAll('.info-value')[3]?.textContent.toLowerCase() || '';

          const matches = routeName.includes(term) ||
                         riderName.includes(term) ||
                         startEnd.includes(term);

          card.style.display = matches ? 'block' : 'none';
          if (matches) hasVisibleCards = true;
        });

        // Show/hide clear button
        if (clearSearch) {
          clearSearch.style.display = searchTerm ? 'block' : 'none';
        }

        // Update scroll arrows visibility
        setTimeout(() => {
          const container = document.getElementById('routesContainer');
          if (!container) return;
          const maxScrollLeft = container.scrollWidth - container.clientWidth;
          const scrollLeft = document.getElementById('scrollLeft');
          const scrollRight = document.getElementById('scrollRight');

          if (scrollLeft) scrollLeft.style.opacity = container.scrollLeft <= 0 ? '0.5' : '1';
          if (scrollRight) scrollRight.style.opacity = container.scrollLeft >= maxScrollLeft - 1 ? '0.5' : '1';
        }, 100);
      }

      if (searchInput) {
        searchInput.addEventListener('input', function() {
          filterRoutes(this.value);
        });
      }

      if (clearSearch) {
        clearSearch.addEventListener('click', function() {
          if (searchInput) {
            searchInput.value = '';
            filterRoutes('');
            searchInput.focus();
          }
        });
      }

      // Enter key support
      if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
          }
        });
      }

      // Add Route button functionality
      const addRouteBtn = document.getElementById('addRouteBtn');
      if (addRouteBtn) {
        addRouteBtn.addEventListener('click', function() {
          // You can replace this with actual functionality like opening a modal or redirecting
          // Example: window.location.href = '<?php echo URL_ROOT; ?>/admin/routes/add';
        });
      }

      // Assign site to selected route
      function assignSiteToRoute(siteId) {
        const selectElement = document.getElementById(`routeSelect_${siteId}`);
        const selectedRouteId = selectElement.value;
        
        if (!selectedRouteId) {
          showNotification('Please select a route first', 'warning');
          return;
        }
        
        // Confirm assignment
        const routeName = selectElement.options[selectElement.selectedIndex].text;
        if (!confirm(`Assign this site to ${routeName}?`)) {
          return;
        }
        
        // Send assignment request to server
        fetch('<?php echo URL_ROOT; ?>/admin/assignSiteToRoute', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            site_id: siteId,
            route_id: selectedRouteId
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showNotification('Site assigned to route successfully!', 'success');
            // Refresh the page after a short delay
            setTimeout(() => location.reload(), 1500);
          } else {
            showNotification('Error: ' + (data.message || 'Failed to assign site'), 'error');
            console.error('Error assigning site:', data.message || 'Unknown error');
          }
        })
        .catch(error => {
          showNotification('Error: Unable to assign site to route', 'error');
          console.error('Error:', error);
        });
      }

      function viewSite(siteId) {
        // You can implement actual view logic here
      }

      // Initialize on page load
      document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded');
        console.log('Routes data:', routesData);
        console.log('Dropdowns are pre-populated from server');
      });
    </script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>