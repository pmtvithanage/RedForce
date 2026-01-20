<!DOCTYPE html>
<html>
<head>
    <title>Google Maps Integration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/map.css">
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
        .location-form {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Google Maps Integration</h1>
        
        <!-- Map Container -->
        <div id="map"></div>
        
        <!-- Location Form -->
        <div class="location-form">
            <h3>Add New Location</h3>
            <form id="locationForm">
                <input type="text" id="name" placeholder="Location Name" required>
                <input type="text" id="address" placeholder="Address" required>
                <input type="hidden" id="lat">
                <input type="hidden" id="lng">
                <button type="button" onclick="geocodeAddress()">Geocode Address</button>
                <button type="submit">Save Location</button>
            </form>
        </div>
        
        <!-- Locations List -->
        <div id="locationsList">
            <h3>Saved Locations</h3>
            <ul id="locations">
                <!-- Locations will be loaded here -->
            </ul>
        </div>
    </div>
    
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>&libraries=places&callback=initMap" async defer></script>
    
    <!-- Custom JavaScript -->
    <script src="/public/js/map.js"></script>
    <script>
        // Pass PHP data to JavaScript
        var locations = <?php echo json_encode($locations); ?>;
        var apiKey = '<?php echo $apiKey; ?>';
    </script>
</body>
</html>