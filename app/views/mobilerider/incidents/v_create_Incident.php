<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>

<style>
/* ---------- Page Layout ---------- */
.page {
  width:900px;
  margin: 40px auto;
  margin-top:0;
  padding: 0 20px;
}

.card-section {
  margin-bottom: 40px;
}

.section-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 15px;
}

.section-head h2 {
  font-size: 20px;
  color: var(--primary-color);
  margin: 0;
}

/* ---------- Cards ---------- */
.card {
  background-color: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(164, 0, 0, 0.1);
}

/* ---------- Form Section ---------- */
.application-form {
  background-color: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
}

.field {
  margin-bottom: 15px;
}

.field-label {
  margin-bottom: 6px;
  font-weight: 500;
  color: var(--text-color);
}

.field-input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  font-size: 15px;
}

.field-input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
}

textarea.field-input {
  resize: vertical;
  min-height: 100px;
  font-family: inherit;
}

/* ---------- Custom File Upload ---------- */
.custom-file {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.btn-upload {
  background-color: var(--primary-color);
  color: var(--secondary-color);
  border: none;
  width: 120px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-upload:hover {
  background-color: #b50000;
}

.btn-upload:active {
  background-color: #800000;
  transform: scale(0.97);
}

.file-name {
  font-size: 14px;
  color: #555;
  font-style: italic;
}

/* Photo preview */
.photo-preview {
  margin-top: 10px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.photo-preview img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 8px;
  border: 2px solid var(--border-color);
}

/* ---------- Buttons ---------- */
.btn {
  display: inline-block;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  border: none;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: var(--primary-color);
  color: var(--secondary-color);
  box-shadow: 0 4px 10px rgba(164, 0, 0, 0.3);
}

.btn-primary:hover {
  background-color: #b50000;
}

.btn-primary:active {
  background-color: #800000;
  transform: scale(0.97);
}

.btn-light {
  background-color: #f1f1f1;
  color: #333;
}

.btn-light:hover {
  background-color: #e0e0e0;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}

.form-input-error {
  color: red;
  font-size: 13px;
  margin-top: 5px;
}

/* ---------- Map Section ---------- */
#map {
  width: 100%;
  height: 350px;
  border-radius: 8px;
  border: 1px solid var(--border-color);
  margin-bottom: 10px;
}

.map-section {
  margin-top: 10px;
}

.map-instructions {
  color: #666;
  font-size: 14px;
  margin-bottom: 10px;
  padding: 10px;
  background-color: #f8f9fa;
  border-radius: 6px;
}

/* ---------- Form Grid ---------- */
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .page {
    width: 100%;
    margin: 20px auto;
    padding: 0 10px;
  }
  
  .application-form {
    padding: 15px;
  }
  
  .section-head h2 {
    font-size: 18px;
  }
  
  #map {
    height: 250px;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .btn {
    width: 100%;
    justify-content: center;
  }
  
  .photo-preview img {
    width: 80px;
    height: 80px;
  }
}

@media (max-width: 480px) {
  .page {
    margin: 10px auto;
  }
  
  .card {
    padding: 15px;
  }
  
  .field-label {
    font-size: 14px;
  }
  
  .field-input {
    font-size: 14px;
    padding: 8px 10px;
  }
  
  .btn-upload {
    width: 100%;
  }
  
  .custom-file {
    flex-direction: column;
  }
}
</style>

<?php require_once APP_ROOT . '/views/components/showNotification.php'; ?>

<!-- Content will be loaded here -->
<button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/MobileRider/viewIncident'"> 
    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
    Back
</button>

<main class="page">
  <section class="card-section">
    <div class="section-head">
      <h2>Report New Incident</h2>
    </div>

    <form class="application-form" method="POST" action="<?php echo URL_ROOT; ?>/MobileRider/createIncident" id="incidentForm" enctype="multipart/form-data" onsubmit="return validateForm(event)">

      <div class="form-grid">
        <div class="field">
          <div class="field-label">Incident Type: <span style="color: red;">*</span></div>
          <select class="field-input" name="incident_type" id="incident_type">
            <option value="">Select incident type...</option>
            <option value="Security Breach">Security Breach</option>
            <option value="Unauthorized Access">Unauthorized Access</option>
            <option value="Equipment Malfunction">Equipment Malfunction</option>
            <option value="Medical Emergency">Medical Emergency</option>
            <option value="Fire Alarm">Fire Alarm</option>
            <option value="Vandalism">Vandalism</option>
            <option value="Theft">Theft</option>
            <option value="Suspicious Activity">Suspicious Activity</option>
            <option value="Officer Absence">Officer Absence</option>
            <option value="Safety Hazard">Safety Hazard</option>
            <option value="Other">Other</option>
          </select>
          <span class="form-input-error"><?php echo $data['incident_type_err'] ?? ''; ?></span>
        </div>

        <div class="field">
          <div class="field-label">Site: <span style="color: red;">*</span></div>
          <select class="field-input" name="site_id" id="site_id">
            <option value="">Select site...</option>
            <?php if(isset($data['sites']) && !empty($data['sites'])): ?>
              <?php foreach($data['sites'] as $site): ?>
                <option value="<?php echo $site->id; ?>" <?php echo (isset($data['site_id']) && $data['site_id'] == $site->id) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($site->site_name); ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="" disabled>No sites assigned to you</option>
            <?php endif; ?>
          </select>
          <span class="form-input-error"><?php echo $data['site_id_err'] ?? ''; ?></span>
        </div>
      </div>

      <div class="form-grid">
        <div class="field">
          <div class="field-label">Incident Date: <span style="color: red;">*</span></div>
          <input type="date" class="field-input" name="incident_date" id="incident_date" 
                 value="<?php echo $data['incident_date'] ?? date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
          <span class="form-input-error"><?php echo $data['incident_date_err'] ?? ''; ?></span>
        </div>

        <div class="field">
          <div class="field-label">Incident Time: <span style="color: red;">*</span></div>
          <input type="time" class="field-input" name="incident_time" id="incident_time" 
                 value="<?php echo $data['incident_time'] ?? date('H:i'); ?>">
          <span class="form-input-error"><?php echo $data['incident_time_err'] ?? ''; ?></span>
        </div>
      </div>

      <div class="field">
        <div class="field-label">Priority: <span style="color: red;">*</span></div>
        <select class="field-input" name="priority" id="priority">
          <option value="Low" <?php echo (isset($data['priority']) && $data['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
          <option value="Medium" <?php echo (!isset($data['priority']) || $data['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
          <option value="High" <?php echo (isset($data['priority']) && $data['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
          <option value="Critical" <?php echo (isset($data['priority']) && $data['priority'] == 'Critical') ? 'selected' : ''; ?>>Critical</option>
        </select>
        <span class="form-input-error"><?php echo $data['priority_err'] ?? ''; ?></span>
      </div>

      <div class="field">
        <div class="field-label">Description: <span style="color: red;">*</span></div>
        <textarea class="field-input" name="description" id="description" placeholder="Provide detailed description of the incident..."><?php echo $data['description'] ?? ''; ?></textarea>
        <span class="form-input-error"><?php echo $data['description_err'] ?? ''; ?></span>
      </div>

      <div class="field">
        <div class="field-label">Actions Taken:</div>
        <textarea class="field-input" name="actions_taken" id="actions_taken" placeholder="Describe any immediate actions taken..."><?php echo $data['actions_taken'] ?? ''; ?></textarea>
      </div>

      <div class="field">
        <div class="field-label">People Involved:</div>
        <input type="text" class="field-input" name="people_involved" id="people_involved" 
               value="<?php echo $data['people_involved'] ?? ''; ?>" 
               placeholder="Names of people involved (if any)">
      </div>

      <div class="field">
        <div class="field-label">Evidence/Photos:</div>
        <div class="custom-file">
          <input type="file" id="evidence_files" name="evidence_files[]" accept="image/*" multiple hidden onchange="handleFileSelect(this)">
          <button type="button" class="btn-upload" onclick="document.getElementById('evidence_files').click()">Choose Files</button>
          <span class="file-name" id="file-name">No files chosen</span>
        </div>
        <div class="photo-preview" id="photo-preview"></div>
      </div>

      <div class="field">
        <div class="field-label">Incident Location :</div>
        <div class="map-section">
          <div id="map"></div>
          <input type="hidden" id="latitude" name="latitude" value="<?php echo $data['latitude'] ?? ''; ?>">
          <input type="hidden" id="longitude" name="longitude" value="<?php echo $data['longitude'] ?? ''; ?>">
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit Report</button>
      </div>
    </form>
  </section>
</main>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGwijY64zQTmizwDN6omOoI9nzxb1MQog&libraries=places"></script>

<script>
// Form validation
function validateForm(event) {
  event.preventDefault();
  
  const incidentType = document.getElementById('incident_type').value.trim();
  const siteId = document.getElementById('site_id').value;
  const incidentDate = document.getElementById('incident_date').value;
  const incidentTime = document.getElementById('incident_time').value;
  const priority = document.getElementById('priority').value;
  const description = document.getElementById('description').value.trim();
  
  // Reset previous error styling
  document.getElementById('incident_type').style.borderColor = '';
  document.getElementById('site_id').style.borderColor = '';
  document.getElementById('incident_date').style.borderColor = '';
  document.getElementById('incident_time').style.borderColor = '';
  document.getElementById('description').style.borderColor = '';
  
  let errors = [];
  let firstErrorField = null;
  
  if (!incidentType) {
    errors.push('Incident Type is required');
    document.getElementById('incident_type').style.borderColor = '#ef4444';
    if (!firstErrorField) firstErrorField = document.getElementById('incident_type');
  }
  
  if (!siteId) {
    errors.push('Site is required');
    document.getElementById('site_id').style.borderColor = '#ef4444';
    if (!firstErrorField) firstErrorField = document.getElementById('site_id');
  }
  
  if (!incidentDate) {
    errors.push('Incident Date is required');
    document.getElementById('incident_date').style.borderColor = '#ef4444';
    if (!firstErrorField) firstErrorField = document.getElementById('incident_date');
  }
  
  if (!incidentTime) {
    errors.push('Incident Time is required');
    document.getElementById('incident_time').style.borderColor = '#ef4444';
    if (!firstErrorField) firstErrorField = document.getElementById('incident_time');
  }
  
  if (!description) {
    errors.push('Description is required');
    document.getElementById('description').style.borderColor = '#ef4444';
    if (!firstErrorField) firstErrorField = document.getElementById('description');
  }
  
  if (errors.length > 0) {
    errors.forEach((error, index) => {
      setTimeout(() => {
        showNotification(error, 'error');
      }, index * 100);
    });
    
    if (firstErrorField) {
      firstErrorField.focus();
    }
    
    return false;
  }
  
  // All validations passed - submit the form
  event.target.submit();
  return true;
}

// Clear error styling on input
document.addEventListener('DOMContentLoaded', function() {
  const fields = ['incident_type', 'site_id', 'incident_date', 'incident_time', 'description'];
  fields.forEach(fieldId => {
    const field = document.getElementById(fieldId);
    if (field) {
      field.addEventListener('input', function() {
        this.style.borderColor = '';
      });
      field.addEventListener('change', function() {
        this.style.borderColor = '';
      });
    }
  });
});

// File selection handler
function handleFileSelect(input) {
  const files = input.files;
  const fileNameDisplay = document.getElementById('file-name');
  const photoPreview = document.getElementById('photo-preview');
  
  if (files.length === 0) {
    fileNameDisplay.textContent = 'No files chosen';
    photoPreview.innerHTML = '';
    return;
  }
  
  fileNameDisplay.textContent = files.length === 1 ? files[0].name : `${files.length} files selected`;
  
  // Clear previous previews
  photoPreview.innerHTML = '';
  
  // Show image previews
  Array.from(files).forEach(file => {
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        photoPreview.appendChild(img);
      };
      reader.readAsDataURL(file);
    }
  });
}

// Map functionality
let map;
let marker;
let sitesData = [];

// Load sites data with coordinates
<?php if(isset($data['sites']) && !empty($data['sites'])): ?>
sitesData = <?php echo json_encode($data['sites']); ?>;
<?php endif; ?>

function initMap() {
  // Default center (Sri Lanka)
  const defaultCenter = { lat: 6.9271, lng: 79.8612 };
  
  // Check if we have saved coordinates
  const savedLat = document.getElementById('latitude').value;
  const savedLng = document.getElementById('longitude').value;
  const center = (savedLat && savedLng) ? 
    { lat: parseFloat(savedLat), lng: parseFloat(savedLng) } : 
    defaultCenter;
  
  map = new google.maps.Map(document.getElementById('map'), {
    center: center,
    zoom: savedLat && savedLng ? 15 : 12,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  
  // Add marker if we have saved coordinates
  if (savedLat && savedLng) {
    marker = new google.maps.Marker({
      position: center,
      map: map,
      draggable: true,
      title: 'Incident Location'
    });
    
    // Update coordinates when marker is dragged
    google.maps.event.addListener(marker, 'dragend', function(event) {
      document.getElementById('latitude').value = event.latLng.lat();
      document.getElementById('longitude').value = event.latLng.lng();
    });
  }
  
  // Add listener for site selection
  const siteSelect = document.getElementById('site_id');
  if (siteSelect) {
    siteSelect.addEventListener('change', function() {
      const selectedSiteId = this.value;
      if (selectedSiteId && sitesData.length > 0) {
        // Find the selected site in sitesData
        const selectedSite = sitesData.find(site => site.id == selectedSiteId);
        
        if (selectedSite && selectedSite.latitude && selectedSite.longitude) {
          const siteLocation = {
            lat: parseFloat(selectedSite.latitude),
            lng: parseFloat(selectedSite.longitude)
          };
          
          // Center map on site location
          map.setCenter(siteLocation);
          map.setZoom(16);
          
          // Add or move marker to site location
          if (marker) {
            marker.setPosition(siteLocation);
            marker.setTitle(selectedSite.site_name);
          } else {
            marker = new google.maps.Marker({
              position: siteLocation,
              map: map,
              draggable: true,
              title: selectedSite.site_name
            });
            
            // Update coordinates when marker is dragged
            google.maps.event.addListener(marker, 'dragend', function(event) {
              document.getElementById('latitude').value = event.latLng.lat();
              document.getElementById('longitude').value = event.latLng.lng();
            });
          }
          
          // Update hidden coordinate fields
          document.getElementById('latitude').value = selectedSite.latitude;
          document.getElementById('longitude').value = selectedSite.longitude;
        }
      }
    });
  }
}

// Initialize map when page loads
google.maps.event.addDomListener(window, 'load', initMap);
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>