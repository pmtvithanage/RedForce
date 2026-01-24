<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

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

.due-badge {
  background-color: var(--primary-color);
  color: var(--secondary-color);
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 14px;
}

/* ---------- Cards ---------- */
.card {
  background-color: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(164, 0, 0, 0.1);
}

.large-card {
  min-height: 120px;
}

.placeholder {
  color: #555;
  font-size: 15px;
}

/* ---------- Form Section ---------- */
.form-and-photo {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
}

.photo-upload {
  flex: 1 1 200px;
  text-align: center;
  justify-items: center;
}

.photo-frame {
  width: 180px;
  height: 180px;
  border: 2px dashed var(--border-color);
  border-radius: 12px;
  margin: 0 auto 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background-color: #fff;
}

.imagePlaceholder{
 
  height: 180px;
  border: 2px solid var(--border-color);
  border-radius: 12px;
  margin: 0 auto 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background-color: #fff;
}

.application-form {
  flex: 2 1 400px;
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

/* ---------- Custom File Upload ---------- */
.custom-file {
  display: flex;
  align-items: center;
  gap: 10px;
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
}

/* ---------- Map Section ---------- */
#map {
  width: 100%;
  height: 400px;
  border-radius: 8px;
  border: 1px solid var(--border-color);
  margin-bottom: 20px;
}

.map-section {
  margin-top: 20px;
}

.map-instructions {
  color: #666;
  font-size: 14px;
  margin-bottom: 10px;
  padding: 10px;
  background-color: #f8f9fa;
  border-radius: 6px;
}

.location-search-box {
  margin-bottom: 15px;
}

#location-search {
  width: 100%;
  padding: 12px 45px 12px 15px;
  border: 2px solid var(--border-color);
  border-radius: 8px;
  font-size: 15px;
  transition: all 0.3s ease;
  background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="%23666" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>');
  background-repeat: no-repeat;
  background-position: right 12px center;
}

#location-search:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
}

#location-search::placeholder {
  color: #999;
}

.pac-container {
  border-radius: 8px;
  margin-top: 5px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  font-family: inherit;
}

</style>
    <?php require_once APP_ROOT . '/views/components/showNotification.php'; ?>

    <!-- Content will be loaded here -->
    <button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="history.back()"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
    <main class="page">
  <section class="card-section">
    <div class="section-head">
      <h2>Add Route</h2>
    </div>

    <div class="form-and-photo">

      <!-- Route Form -->
      <form class="application-form" method="POST" action="" id="routeForm" onsubmit="return validateForm(event)">

        <div class="field">
          <div class="field-label">Route Name: <span style="color: red;">*</span></div>
          <input type="text" class="field-input" name="route_name" id="route_name" value="<?php echo $data['route_name']; ?>" placeholder="Enter route name">
          <span class="form-input-error"><?php echo $data['route_name_err'];?></span>
        </div>

        <div class="field">
          <div class="field-label">Description: <span style="color: red;">*</span></div>
          <textarea class="field-input" name="description" id="description" placeholder="Enter route description"><?php echo $data['description']; ?></textarea>
        </div>

        <div class="field">
          <div class="field-label">Location (Select area on map): <span style="color: red;">*</span></div>
          <div class="map-section">
            <div class="map-instructions">
              <strong>Instructions:</strong> Search for a location below or click and drag on the map to select the route area. The selected area will be saved as the route location.
            </div>
            <div class="location-search-box">
              <input id="location-search" type="text" placeholder="Search for a location...">
            </div>
            <div id="map" style="width: 100%; height: 400px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 15px;"></div>
            <input type="hidden" id="location_data" name="location" value="<?php echo $data['location']; ?>">
            <div id="selected-area-info" style="padding: 10px; background-color: #f8f9fa; border-radius: 6px; display: none;">
              <strong>Selected Area:</strong> <span id="area-coordinates"></span>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Route</button>
        </div>
      </form>
    </div>
  </section>

  
</main>

    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    


<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGwijY64zQTmizwDN6omOoI9nzxb1MQog&libraries=drawing,geometry,places"></script>


<script>

// Form validation
function validateForm(event) {
  // Prevent default form submission first
  event.preventDefault();
  
  const routeName = document.getElementById('route_name').value.trim();
  const description = document.getElementById('description').value.trim();
  const locationData = document.getElementById('location_data').value.trim();
  
  // Reset previous error styling
  document.getElementById('route_name').style.borderColor = '';
  document.getElementById('description').style.borderColor = '';
  
  let errors = [];
  let firstErrorField = null;
  
  if (!routeName) {
    errors.push('Route Name is required');
    document.getElementById('route_name').style.borderColor = '#ef4444';
    if (!firstErrorField) firstErrorField = document.getElementById('route_name');
  }
  
  if (!description) {
    errors.push('Description is required');
    document.getElementById('description').style.borderColor = '#ef4444';
    if (!firstErrorField) firstErrorField = document.getElementById('description');
  }
  
  if (!locationData) {
    errors.push('Please select a route area on the map');
  }
  
  if (errors.length > 0) {
    // Show all errors
    errors.forEach((error, index) => {
      setTimeout(() => {
        showNotification(error, 'error');
      }, index * 100);
    });
    
    // Focus first error field
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
  const routeNameInput = document.getElementById('route_name');
  const descriptionInput = document.getElementById('description');
  
  if (routeNameInput) {
    routeNameInput.addEventListener('input', function() {
      this.style.borderColor = '';
    });
  }
  
  if (descriptionInput) {
    descriptionInput.addEventListener('input', function() {
      this.style.borderColor = '';
    });
  }
});
  
let map;
let drawingManager;
let selectedArea = null;
let searchBox;

function initMap() {
    // Initialize map centered on Sri Lanka
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 7.8731, lng: 80.7718 }, // Center of Sri Lanka
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    // Initialize Places Autocomplete
    const input = document.getElementById('location-search');
    const autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: 'lk' }, // Restrict to Sri Lanka
        fields: ['geometry', 'name', 'formatted_address']
    });

    // Bind autocomplete to map
    autocomplete.bindTo('bounds', map);

    // Listen for place selection
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();

        if (!place.geometry || !place.geometry.location) {
            console.log('No details available for: ' + place.name);
            return;
        }

        // If the place has a viewport, fit the map to it
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(15);
        }

        // Optional: Add a marker at the searched location
        new google.maps.Marker({
            map: map,
            position: place.geometry.location,
            title: place.name,
            animation: google.maps.Animation.DROP
        });
    });

    // Prevent form submission when Enter is pressed in search box
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    // Initialize drawing manager for area selection
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
                google.maps.drawing.OverlayType.POLYGON
            ]
        },
        polygonOptions: {
            fillColor: '#FF0000',
            fillOpacity: 0.3,
            strokeWeight: 2,
            strokeColor: '#FF0000',
            clickable: true,
            editable: true,
            zIndex: 1
        }
    });

    drawingManager.setMap(map);

    // Listen for polygon completion
    google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
        // Remove previous selection
        if (selectedArea) {
            selectedArea.setMap(null);
        }

        selectedArea = polygon;
        
        // Get polygon coordinates
        const path = polygon.getPath();
        const coordinates = [];
        
        for (let i = 0; i < path.getLength(); i++) {
            const point = path.getAt(i);
            coordinates.push({
                lat: point.lat(),
                lng: point.lng()
            });
        }

        // Store coordinates as JSON string
        document.getElementById('location_data').value = JSON.stringify(coordinates);
        
        // Show selected area info
        document.getElementById('selected-area-info').style.display = 'block';
        document.getElementById('area-coordinates').textContent = 
            `${coordinates.length} points selected`;
        
        // Switch back to hand mode after drawing
        drawingManager.setDrawingMode(null);
        
        // Add listeners for polygon editing
        google.maps.event.addListener(polygon.getPath(), 'set_at', updateCoordinates);
        google.maps.event.addListener(polygon.getPath(), 'insert_at', updateCoordinates);
        google.maps.event.addListener(polygon.getPath(), 'remove_at', updateCoordinates);
    });

    // Load existing area if editing
    const existingLocation = document.getElementById('location_data').value;
    if (existingLocation) {
        try {
            const coordinates = JSON.parse(existingLocation);
            if (coordinates && coordinates.length > 0) {
                const polygon = new google.maps.Polygon({
                    paths: coordinates,
                    fillColor: '#FF0000',
                    fillOpacity: 0.3,
                    strokeWeight: 2,
                    strokeColor: '#FF0000',
                    clickable: true,
                    editable: true,
                    zIndex: 1
                });
                
                polygon.setMap(map);
                selectedArea = polygon;
                
                // Show selected area info
                document.getElementById('selected-area-info').style.display = 'block';
                document.getElementById('area-coordinates').textContent = 
                    `${coordinates.length} points selected`;
                
                // Add listeners for polygon editing
                google.maps.event.addListener(polygon.getPath(), 'set_at', updateCoordinates);
                google.maps.event.addListener(polygon.getPath(), 'insert_at', updateCoordinates);
                google.maps.event.addListener(polygon.getPath(), 'remove_at', updateCoordinates);
                
                // Fit map to polygon bounds
                const bounds = new google.maps.LatLngBounds();
                coordinates.forEach(coord => {
                    bounds.extend(new google.maps.LatLng(coord.lat, coord.lng));
                });
                map.fitBounds(bounds);
            }
        } catch (e) {
            console.error('Error loading existing location:', e);
        }
    }
}

function updateCoordinates() {
    if (selectedArea) {
        const path = selectedArea.getPath();
        const coordinates = [];
        
        for (let i = 0; i < path.getLength(); i++) {
            const point = path.getAt(i);
            coordinates.push({
                lat: point.lat(),
                lng: point.lng()
            });
        }
        
        document.getElementById('location_data').value = JSON.stringify(coordinates);
        document.getElementById('area-coordinates').textContent = 
            `${coordinates.length} points selected`;
    }
}

// Initialize map when page loads
google.maps.event.addDomListener(window, 'load', initMap);
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>