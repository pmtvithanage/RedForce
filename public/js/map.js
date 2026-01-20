// ====================================================================
// Common Google Maps Utilities for RedForce Application
// ====================================================================

let map;
let markers = [];
let infoWindow;

// Site Map Variables (for site add/edit forms)
let siteMap;
let siteMarker;
let geocoder;
let autocomplete;
let searchBox;

// ====================================================================
// Site Location Map Functions (for Add/Edit Site)
// ====================================================================

function initSiteMap() {
    // Default center - Sri Lanka
    const defaultCenter = { lat: 6.9271, lng: 79.8612 }; // Colombo, Sri Lanka
    
    // Get existing coordinates if available
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    const existingLat = latInput ? parseFloat(latInput.value) : null;
    const existingLng = lngInput ? parseFloat(lngInput.value) : null;
    
    const mapCenter = (existingLat && existingLng) ? 
        { lat: existingLat, lng: existingLng } : defaultCenter;
    
    // Initialize map
    siteMap = new google.maps.Map(document.getElementById('map'), {
        zoom: existingLat && existingLng ? 15 : 12,
        center: mapCenter,
        mapTypeControl: true,
        streetViewControl: true,
        fullscreenControl: true
    });
    
    geocoder = new google.maps.Geocoder();
    
    // Add existing marker if coordinates exist
    if (existingLat && existingLng) {
        addSiteMarker(mapCenter);
    }
    
    // Add click listener to map
    siteMap.addListener('click', (event) => {
        addSiteMarker(event.latLng);
        updateCoordinates(event.latLng);
        updateAddressFromLocation(event.latLng);
    });
    
    // Initialize search box
    initLocationSearchBox();
    
    // Initialize autocomplete for address field
    initSiteAutocomplete();
}

function initLocationSearchBox() {
    const searchInput = document.getElementById('location-search');
    
    if (!searchInput) return;
    
    // Create SearchBox
    searchBox = new google.maps.places.SearchBox(searchInput);
    
    // Bias the SearchBox results towards current map's viewport
    siteMap.addListener('bounds_changed', () => {
        searchBox.setBounds(siteMap.getBounds());
    });
    
    // Listen for when user selects a prediction
    searchBox.addListener('places_changed', () => {
        const places = searchBox.getPlaces();
        
        if (places.length === 0) {
            return;
        }
        
        // Get the first place
        const place = places[0];
        
        if (!place.geometry || !place.geometry.location) {
            console.log("Place has no geometry");
            return;
        }
        
        // Add marker at the selected location
        addSiteMarker(place.geometry.location);
        updateCoordinates(place.geometry.location);
        
        // Update address field with the place's formatted address
        const addressInput = document.getElementById('site_address');
        if (place.formatted_address && addressInput) {
            addressInput.value = place.formatted_address;
        }
        
        // Update site name if empty
        const siteNameInput = document.getElementById('site-name');
        if (siteNameInput && !siteNameInput.value && place.name) {
            siteNameInput.value = place.name;
        }
        
        // Adjust map to show the place
        if (place.geometry.viewport) {
            siteMap.fitBounds(place.geometry.viewport);
        } else {
            siteMap.setCenter(place.geometry.location);
            siteMap.setZoom(17);
        }
        
        // Clear the search box
        searchInput.value = '';
    });
}

function addSiteMarker(location) {
    // Remove existing marker if any
    if (siteMarker) {
        siteMarker.setMap(null);
    }
    
    // Add new marker
    siteMarker = new google.maps.Marker({
        position: location,
        map: siteMap,
        draggable: true,
        animation: google.maps.Animation.DROP,
        title: 'Site Location'
    });
    
    // Update coordinates when marker is dragged
    siteMarker.addListener('dragend', (event) => {
        updateCoordinates(event.latLng);
        updateAddressFromLocation(event.latLng);
    });
    
    siteMap.panTo(location);
}

function updateCoordinates(latLng) {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    if (latInput) latInput.value = latLng.lat().toFixed(8);
    if (lngInput) lngInput.value = latLng.lng().toFixed(8);
}

function updateAddressFromLocation(latLng) {
    if (!geocoder) return;
    
    geocoder.geocode({ location: latLng }, (results, status) => {
        if (status === 'OK' && results[0]) {
            const addressInput = document.getElementById('site_address');
            if (addressInput && (!addressInput.value || addressInput.value.length < 5)) {
                addressInput.value = results[0].formatted_address;
            }
        }
    });
}

function initSiteAutocomplete() {
    const addressInput = document.getElementById('site_address');
    
    if (!addressInput) return;
    
    autocomplete = new google.maps.places.Autocomplete(addressInput, {
        types: ['address'],
        componentRestrictions: { country: 'lk' } // Restrict to Sri Lanka, change as needed
    });
    
    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        
        if (!place.geometry) {
            return;
        }
        
        const location = place.geometry.location;
        addSiteMarker(location);
        updateCoordinates(location);
        
        // Update address field with formatted address
        if (place.formatted_address) {
            addressInput.value = place.formatted_address;
        }
    });
    
    // Geocode when address is manually entered
    addressInput.addEventListener('blur', function() {
        const address = this.value;
        if (address && address.length > 10) {
            geocodeAddress(address);
        }
    });
}

function geocodeAddress(address) {
    if (!geocoder) return;
    
    geocoder.geocode({ address: address }, (results, status) => {
        if (status === 'OK' && results[0]) {
            const location = results[0].geometry.location;
            addSiteMarker(location);
            updateCoordinates(location);
        }
    });
}

// ====================================================================
// Generic Map Functions (for location management)
// ====================================================================

function initMap() {
    // Default center (can be dynamic)
    const defaultCenter = { lat: 40.7128, lng: -74.0060 }; // New York
    
    // Initialize map
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: defaultCenter,
        mapTypeControl: true,
        streetViewControl: true
    });
    
    // Initialize info window
    infoWindow = new google.maps.InfoWindow();
    
    // Add click listener to map
    map.addListener('click', (event) => {
        addMarker(event.latLng);
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        if (latInput) latInput.value = event.latLng.lat();
        if (lngInput) lngInput.value = event.latLng.lng();
    });
    
    // Load existing locations if function exists
    if (typeof loadLocations === 'function') {
        loadLocations();
    }
    
    // Initialize autocomplete for address field
    initGenericAutocomplete();
}

function loadLocations() {
    // Fetch locations from API
    fetch('/map/getLocations')
        .then(response => response.json())
        .then(locations => {
            locations.forEach(location => {
                addMarkerToMap(location);
                addLocationToList(location);
            });
        })
        .catch(error => console.error('Error loading locations:', error));
}

function addMarkerToMap(location) {
    const position = {
        lat: parseFloat(location.lat),
        lng: parseFloat(location.lng)
    };
    
    const marker = new google.maps.Marker({
        position: position,
        map: map,
        title: location.name
    });
    
    // Add click listener to marker
    marker.addListener('click', () => {
        infoWindow.setContent(`
            <div>
                <h3>${location.name}</h3>
                <p>${location.address}</p>
                <small>Lat: ${location.lat}, Lng: ${location.lng}</small>
            </div>
        `);
        infoWindow.open(map, marker);
    });
    
    markers.push(marker);
}

function addMarker(location) {
    // Clear existing markers if needed
    markers.forEach(marker => marker.setMap(null));
    markers = [];
    
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        draggable: true
    });
    
    marker.addListener('dragend', (event) => {
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        if (latInput) latInput.value = event.latLng.lat();
        if (lngInput) lngInput.value = event.latLng.lng();
    });
    
    markers.push(marker);
    map.panTo(location);
}

function geocodeGenericAddress() {
    const addressInput = document.getElementById('address');
    if (!addressInput) return;
    
    const address = addressInput.value;
    const geocoder = new google.maps.Geocoder();
    
    geocoder.geocode({ address: address }, (results, status) => {
        if (status === 'OK') {
            const location = results[0].geometry.location;
            map.setCenter(location);
            addMarker(location);
            
            // Update form fields
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            if (latInput) latInput.value = location.lat();
            if (lngInput) lngInput.value = location.lng();
        } else {
            alert('Geocode was not successful: ' + status);
        }
    });
}

function initGenericAutocomplete() {
    const input = document.getElementById('address');
    if (!input) return;
    
    const autocomplete = new google.maps.places.Autocomplete(input);
    
    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (!place.geometry) return;
        
        map.setCenter(place.geometry.location);
        addMarker(place.geometry.location);
        
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        if (latInput) latInput.value = place.geometry.location.lat();
        if (lngInput) lngInput.value = place.geometry.location.lng();
    });
}

// Form submission (if locationForm exists)
document.addEventListener('DOMContentLoaded', function() {
    const locationForm = document.getElementById('locationForm');
    if (locationForm) {
        locationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('name').value,
                address: document.getElementById('address').value,
                lat: document.getElementById('lat').value,
                lng: document.getElementById('lng').value
            };
            
            fetch('/map/saveLocation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Location saved successfully!');
                    location.reload();
                } else {
                    alert('Error saving location');
                }
            });
        });
    }
});

function addLocationToList(location) {
    const list = document.getElementById('locations');
    if (!list) return;
    
    const listItem = document.createElement('li');
    listItem.innerHTML = `
        <strong>${location.name}</strong><br>
        ${location.address}<br>
        <small>Coordinates: ${location.lat}, ${location.lng}</small>
    `;
    list.appendChild(listItem);
}