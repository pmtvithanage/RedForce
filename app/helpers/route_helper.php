<?php

/**
 * Calculate distance between two coordinates using Haversine formula
 * @param float $lat1 Latitude of point 1
 * @param float $lon1 Longitude of point 1
 * @param float $lat2 Latitude of point 2
 * @param float $lon2 Longitude of point 2
 * @return float Distance in kilometers
 */
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Earth's radius in km
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    
    return $earthRadius * $c;
}

/**
 * Check if a point is inside a polygon using ray casting algorithm
 * @param array $point ['lat' => float, 'lng' => float]
 * @param array $polygon Array of points [['lat' => float, 'lng' => float], ...]
 * @return bool
 */
function isPointInPolygon($point, $polygon) {
    $inside = false;
    $x = $point['lat'];
    $y = $point['lng'];
    
    $n = count($polygon);
    for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
        $xi = isset($polygon[$i]['lat']) ? $polygon[$i]['lat'] : $polygon[$i][0];
        $yi = isset($polygon[$i]['lng']) ? $polygon[$i]['lng'] : $polygon[$i][1];
        $xj = isset($polygon[$j]['lat']) ? $polygon[$j]['lat'] : $polygon[$j][0];
        $yj = isset($polygon[$j]['lng']) ? $polygon[$j]['lng'] : $polygon[$j][1];
        
        $intersect = (($yi > $y) != ($yj > $y)) &&
                     ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
        if ($intersect) $inside = !$inside;
    }
    
    return $inside;
}

/**
 * Check if a site is within a route's coverage area
 * @param array $siteCoords ['latitude' => float, 'longitude' => float]
 * @param object $route Route object with area definition
 * @return bool
 */
function isSiteInRouteArea($siteCoords, $route) {
    if (!isset($siteCoords['latitude']) || !isset($siteCoords['longitude'])) {
        return false;
    }
    
    $siteLat = floatval($siteCoords['latitude']);
    $siteLng = floatval($siteCoords['longitude']);
    
    // Method 1: Check polygon boundary if exists
    if (!empty($route->boundary_coords)) {
        $boundary = is_string($route->boundary_coords) 
            ? json_decode($route->boundary_coords, true) 
            : $route->boundary_coords;
            
        if (is_array($boundary) && count($boundary) > 0) {
            return isPointInPolygon(
                ['lat' => $siteLat, 'lng' => $siteLng],
                $boundary
            );
        }
    }
    
    // Method 2: Check radius from route center
    if (!empty($route->area_radius) && !empty($route->center_lat) && !empty($route->center_lng)) {
        $distance = calculateDistance(
            $siteLat,
            $siteLng,
            floatval($route->center_lat),
            floatval($route->center_lng)
        );
        return $distance <= floatval($route->area_radius);
    }
    
    // Method 3: Check proximity to existing route sites
    if (!empty($route->sites) && is_array($route->sites)) {
        $maxProximity = !empty($route->area_radius) ? floatval($route->area_radius) : 15; // Default 15km
        
        foreach ($route->sites as $routeSite) {
            if (!empty($routeSite->latitude) && !empty($routeSite->longitude)) {
                $distance = calculateDistance(
                    $siteLat,
                    $siteLng,
                    floatval($routeSite->latitude),
                    floatval($routeSite->longitude)
                );
                
                if ($distance <= $maxProximity) {
                    return true;
                }
            }
        }
        
        // If route has sites but none are close enough
        if (count($route->sites) > 0) {
            return false;
        }
    }
    
    // If no area definition exists, return false (don't match)
    return false;
}

/**
 * Find routes that cover a specific site location
 * @param array $siteCoords ['latitude' => float, 'longitude' => float]
 * @param array $routes Array of route objects
 * @return array Array of matching routes with distance info
 */
function findMatchingRoutesForSite($siteCoords, $routes) {
    $matchingRoutes = [];
    
    foreach ($routes as $route) {
        if (isSiteInRouteArea($siteCoords, $route)) {
            $routeData = clone $route;
            
            // Calculate distance to route center for sorting
            $distance = 0;
            if (!empty($route->center_lat) && !empty($route->center_lng)) {
                $distance = calculateDistance(
                    floatval($siteCoords['latitude']),
                    floatval($siteCoords['longitude']),
                    floatval($route->center_lat),
                    floatval($route->center_lng)
                );
            } elseif (!empty($route->sites) && is_array($route->sites)) {
                // Calculate average distance to all route sites
                $totalDistance = 0;
                $count = 0;
                foreach ($route->sites as $site) {
                    if (!empty($site->latitude) && !empty($site->longitude)) {
                        $totalDistance += calculateDistance(
                            floatval($siteCoords['latitude']),
                            floatval($siteCoords['longitude']),
                            floatval($site->latitude),
                            floatval($site->longitude)
                        );
                        $count++;
                    }
                }
                $distance = $count > 0 ? $totalDistance / $count : 0;
            }
            
            $routeData->distance_to_site = $distance;
            $matchingRoutes[] = $routeData;
        }
    }
    
    // Sort by distance (closest first)
    usort($matchingRoutes, function($a, $b) {
        return $a->distance_to_site <=> $b->distance_to_site;
    });
    
    return $matchingRoutes;
}

/**
 * Get all unassigned sites with their matching routes
 * @param array $sites Array of site objects
 * @param array $routes Array of route objects
 * @return array Sites with matching_routes property added
 */
function getSitesWithMatchingRoutes($sites, $routes) {
    $result = [];
    
    foreach ($sites as $site) {
        $siteData = clone $site;
        
        if (!empty($site->latitude) && !empty($site->longitude)) {
            $siteCoords = [
                'latitude' => $site->latitude,
                'longitude' => $site->longitude
            ];
            $siteData->matching_routes = findMatchingRoutesForSite($siteCoords, $routes);
        } else {
            $siteData->matching_routes = [];
        }
        
        $result[] = $siteData;
    }
    
    return $result;
}
