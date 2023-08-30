<?php
function getRouteData($apiKey, $startLat, $startLng, $endLat, $endLng)
{
    $apiUrl = "https://api.openrouteservice.org/v2/directions/driving-car";

    // Create cURL resource
    $ch = curl_init();

    // Set cURL options
    $url = "{$apiUrl}?api_key={$apiKey}&start={$startLng},{$startLat}&end={$endLng},{$endLat}";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request and get the response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    // Close cURL resource
    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Return the route data
    return $data;
}
function getIntersections($route1Data, $route2Data)
{
    $intersections = [];

    $route1Geometry = $route1Data['features'][0]['geometry']['coordinates'];
    $route2Geometry = $route2Data['features'][0]['geometry']['coordinates'];

    foreach ($route1Geometry as $point1) {
        foreach ($route2Geometry as $point2) {
            if (compareCoordinates($point1, $point2)) {
                $intersections[] = $point1;
            }
        }
    }

    return $intersections;
}

function compareCoordinates($coord1, $coord2)
{
    // Define a tolerance threshold for comparison
    $tolerance = 0.0001;

    return abs($coord1[0] - $coord2[0]) < $tolerance && abs($coord1[1] - $coord2[1]) < $tolerance;
}

$apiKey = "5b3ce3597851110001cf6248be0e4460401f440e838f122fa8bab5da";

// Test route coordinates for rides 1 and 2
$ride1StartLatitude = 50.704129514100735;
$ride1StartLongitude = 7.16153100070237;
$ride1EndLatitude = 50.712129514100735;
$ride1EndLongitude = 7.17153100070237;

$ride2StartLatitude = 50.707129514100735;
$ride2StartLongitude = 7.15953100070237;
$ride2EndLatitude = 50.716129514100735;
$ride2EndLongitude = 7.16953100070237;

// Get the route data for rides 1 and 2
$ride1Data = getRouteData($apiKey, $ride1StartLatitude, $ride1StartLongitude, $ride1EndLatitude, $ride1EndLongitude);
$ride2Data = getRouteData($apiKey, $ride2StartLatitude, $ride2StartLongitude, $ride2EndLatitude, $ride2EndLongitude);

// Find the intersection points between the two routes
$intersectionPoints = getIntersections($ride1Data, $ride2Data);

// Display the intersection points
if (count($intersectionPoints) > 0) {
    foreach ($intersectionPoints as $point) {
        echo "Intersection point latitude: {$point[1]}\n";
        echo "Intersection point longitude: {$point[0]}\n";
        echo "---------------------------------------------\n";
    }
} else {
    echo "No intersection points found between the routes.\n";
}
?>
