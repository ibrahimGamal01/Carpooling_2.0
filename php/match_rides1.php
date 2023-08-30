<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carpooling";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Table
// CREATE TABLE `rides` (
//     `ride_id` INT AUTO_INCREMENT PRIMARY KEY,
//     `driver_id` INT NOT NULL,
//     `pickup_location` VARCHAR(255) NOT NULL,
//     `dropoff_location` VARCHAR(255) NOT NULL,
//     `pickup_latitude` DECIMAL(10, 8),
//     `pickup_longitude` DECIMAL(11, 8),
//     `dropoff_latitude` DECIMAL(10, 8),
//     `dropoff_longitude` DECIMAL(11, 8),
//     `available_seats` INT NOT NULL,
//     `status` ENUM('upcoming', 'in progress', 'completed', 'canceled') NOT NULL
//   );
  
function getRouteData($apiKey, $startLat, $startLng, $endLat, $endLng)
{
    $apiUrl = "https://api.openrouteservice.org/v2/directions/driving-car";

    $ch = curl_init();

    $url = "{$apiUrl}?api_key={$apiKey}&start={$startLng},{$startLat}&end={$endLng},{$endLat}";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    curl_close($ch);

    $data = json_decode($response, true);

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
    $tolerance = 0.0001;

    return abs($coord1[0] - $coord2[0]) < $tolerance && abs($coord1[1] - $coord2[1]) < $tolerance;
}

function getCoordinates($query)
{
    $apiKey = '54852a7462e1c40cc8fca727234d19cf';
    $apiUrl = 'http://api.positionstack.com/v1/forward?access_key=' . $apiKey . '&query=' . urlencode($query);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    if (isset($responseData['data'][0]['latitude']) && isset($responseData['data'][0]['longitude'])) {
        return array(
            'latitude' => $responseData['data'][0]['latitude'],
            'longitude' => $responseData['data'][0]['longitude']
        );
    }

    return null; 
}

$apiKey = "5b3ce3597851110001cf6248be0e4460401f440e838f122fa8bab5da";

$passengerPickupLocation = isset($_POST['pickupLocation']) ? $_POST['pickupLocation'] : '';
$passengerDropoffLocation = isset($_POST['dropoffLocation']) ? $_POST['dropoffLocation'] : '';

$passengerPickupCoordinates = getCoordinates($passengerPickupLocation);
$passengerDropoffCoordinates = getCoordinates($passengerDropoffLocation);

if (!$passengerPickupCoordinates || !$passengerDropoffCoordinates) {
    die("Error: Failed to get coordinates for passenger pickup or drop-off location.");
}

$sql = "SELECT * FROM rides";
$result = $conn->query($sql);
$ridesWithIntersections = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rideStartLatitude = $row['pickup_latitude'];
        $rideStartLongitude = $row['pickup_longitude'];
        $rideEndLatitude = $row['dropoff_latitude'];
        $rideEndLongitude = $row['dropoff_longitude'];

        $passengerRouteData = @getRouteData($apiKey, $passengerPickupCoordinates['latitude'], $passengerPickupCoordinates['longitude'], $passengerDropoffCoordinates['latitude'], $passengerDropoffCoordinates['longitude']);
        $rideRouteData = @getRouteData($apiKey, $rideStartLatitude, $rideStartLongitude, $rideEndLatitude, $rideEndLongitude);

        if ($passengerRouteData && $rideRouteData) {
            $intersectionPoints = @getIntersections($passengerRouteData, $rideRouteData);

            if (count($intersectionPoints) > 5) {
                $ridesWithIntersections[] = $row['ride_id'];
            }
        }
    }
}

// Fetch rides with matched ride IDs from the database
$matchingRideIds = implode(',', $ridesWithIntersections);
if (!empty($matchingRideIds)) {
    $matchingRidesSql = "SELECT * FROM rides WHERE ride_id IN ($matchingRideIds)";
    $matchingRidesResult = $conn->query($matchingRidesSql);
    $matchingRides = [];

    if ($matchingRidesResult && $matchingRidesResult->num_rows > 0) {
        while ($row = $matchingRidesResult->fetch_assoc()) {
            $matchingRides[] = $row;
        }
    }

    // Close the database connection
    $conn->close();

    // Return matching rides as JSON response
    header('Content-Type: application/json');
    echo json_encode($matchingRides);
} else {
    // No matching rides found
    echo "No matching rides found.";
}
?>
