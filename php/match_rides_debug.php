<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carpooling";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

function getCoordinates($query)
{
    $apiKey = '54852a7462e1c40cc8fca727234d19cf';
    $apiUrl = 'http://api.positionstack.com/v1/forward?access_key=' . $apiKey . '&query=' . urlencode($query);

    // Make the API request using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Parse the API response to extract latitude and longitude
    $responseData = json_decode($response, true);
    if (isset($responseData['data'][0]['latitude']) && isset($responseData['data'][0]['longitude'])) {
        return array(
            'latitude' => $responseData['data'][0]['latitude'],
            'longitude' => $responseData['data'][0]['longitude']
        );
    }

    return null; // Return null if coordinates not found
}

$apiKey = "5b3ce3597851110001cf6248be0e4460401f440e838f122fa8bab5da";

// Fetch form inputs
$passengerPickupLocation = isset($_POST['pickupLocation']) ? $_POST['pickupLocation'] : '';
$passengerDropoffLocation = isset($_POST['dropoffLocation']) ? $_POST['dropoffLocation'] : '';



//   // Debug: Print passenger pickup and drop-off locations
//   $passengerPickupLocation = "cologne";
//   $passengerDropoffLocation = "unfccc";
// echo "Passenger Pickup Location: " . $passengerPickupLocation . "<br>";
// echo "Passenger Drop-off Location: " . $passengerDropoffLocation . "<br>";

// Get coordinates for passenger's pickup and dropoff locations
$passengerPickupCoordinates = getCoordinates($passengerPickupLocation);
$passengerDropoffCoordinates = getCoordinates($passengerDropoffLocation);

//   // Debug: Print passenger pickup and drop-off coordinates
//   echo "Passenger Pickup Coordinates: ";
//   print_r($passengerPickupCoordinates);
//   echo "<br>";

//   echo "Passenger Drop-off Coordinates: ";
//   print_r($passengerDropoffCoordinates);
//   echo "<br>";

if (!$passengerPickupCoordinates || !$passengerDropoffCoordinates) {
    die("Error: Failed to get coordinates for passenger pickup or drop-off location.");
}

//   // Debug: Print a message to indicate that coordinates were successfully fetched
//   echo "Coordinates for passenger pickup and drop-off locations were successfully fetched.<br>";

// Fetch all rides from the rides table
$sql = "SELECT * FROM rides";
$result = $conn->query($sql);
$ridesWithIntersections = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rideStartLatitude = $row['pickup_latitude'];
        $rideStartLongitude = $row['pickup_longitude'];
        $rideEndLatitude = $row['dropoff_latitude'];
        $rideEndLongitude = $row['dropoff_longitude'];

        // Get the route data for the passenger ride and the current database ride
        $passengerRouteData = getRouteData($apiKey, $passengerPickupCoordinates['latitude'], $passengerPickupCoordinates['longitude'], $passengerDropoffCoordinates['latitude'], $passengerDropoffCoordinates['longitude']);
        $rideRouteData = getRouteData($apiKey, $rideStartLatitude, $rideStartLongitude, $rideEndLatitude, $rideEndLongitude);

        // Check if the routes have intersection points
        $intersectionPoints = getIntersections($passengerRouteData, $rideRouteData);

        // Debug: Print messages to show the current ride's details being processed
        // echo "Processing Ride ID: " . $row['ride_id'] . "<br>";

        // Debug: Print the number of intersection points found
        // echo "Number of Intersection Points: " . count($intersectionPoints) . "<br>";

        // If there are intersection points, add the ride to the list
        if (!empty($intersectionPoints)) {
            $ridesWithIntersections[] = $row;
            // echo "Ride ID " . $row['ride_id'] . " has intersection points.<br>";
        }
    }
}

// Debug: Print the number of rides with intersection points
// echo "Number of Rides with Intersection Points: " . count($ridesWithIntersections) . "<br>";

// Return rides with intersection points as JSON response
header('Content-Type: application/json');
echo json_encode($ridesWithIntersections);

// Debug: Print a message to indicate that the JSON response has been sent
// echo "JSON response with rides and intersection points has been sent.<br>";

// Close the database connection
$conn->close();


// ****************************Successful Test Using Dummy Data *********************************//
// $apiKey = "5b3ce3597851110001cf6248be0e4460401f440e838f122fa8bab5da";

// Test route coordinates for rides 1 and 2
// $ride1StartLatitude = 50.704129514100735;
// $ride1StartLongitude = 7.16153100070237;
// $ride1EndLatitude = 50.712129514100735;
// $ride1EndLongitude = 7.17153100070237;

// $ride2StartLatitude = 50.707129514100735;
// $ride2StartLongitude = 7.15953100070237;
// $ride2EndLatitude = 50.716129514100735;
// $ride2EndLongitude = 7.16953100070237;

// // Get the route data for rides 1 and 2
// $ride1Data = getRouteData($apiKey, $ride1StartLatitude, $ride1StartLongitude, $ride1EndLatitude, $ride1EndLongitude);
// $ride2Data = getRouteData($apiKey, $ride2StartLatitude, $ride2StartLongitude, $ride2EndLatitude, $ride2EndLongitude);

// // Find the intersection points between the two routes
// $intersectionPoints = getIntersections($ride1Data, $ride2Data);

// // Display the intersection points
// if (count($intersectionPoints) > 0) {
// foreach ($intersectionPoints as $point) {
// echo "Intersection point latitude: {$point[1]}\n";
// echo "Intersection point longitude: {$point[0]}\n";
// echo "---------------------------------------------\n";
// }
// } else {
// echo "No intersection points found between the routes.\n";
// }
?>
