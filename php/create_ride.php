<?php
// Path: php/create_ride.php
session_start();
include_once "config.php";

// CREATE TABLE `rides` (
//   `ride_id` INT AUTO_INCREMENT PRIMARY KEY,
//   `driver_id` INT NOT NULL,
//   `pickup_location` VARCHAR(255) NOT NULL,
//   `dropoff_location` VARCHAR(255) NOT NULL,
//   `pickup_latitude` DECIMAL(10, 8),
//   `pickup_longitude` DECIMAL(11, 8),
//   `dropoff_latitude` DECIMAL(10, 8),
//   `dropoff_longitude` DECIMAL(11, 8),
//   `available_seats` INT NOT NULL,
//   `ride_start_date` DATE NOT NULL,
//   `ride_start_time` TIME NOT NULL,
//   `status` ENUM('upcoming', 'in progress', 'completed', 'canceled') NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// Retrieve ride details from the request
$requestData = json_decode(file_get_contents('php://input'), true);
$pickupLocation = isset($requestData['pickupLocation']) ? $requestData['pickupLocation'] : '';
$dropoffLocation = isset($requestData['dropoffLocation']) ? $requestData['dropoffLocation'] : '';
$seats = isset($requestData['seats']) ? $requestData['seats'] : '';
$rideStartDate = isset($requestData['rideStartDate']) ? $requestData['rideStartDate'] : '';
$rideStartTime = isset($requestData['rideStartTime']) ? $requestData['rideStartTime'] : '';

// Validate the required fields
if (empty($pickupLocation) || empty($dropoffLocation) || empty($seats)) {
  die("Error: Required fields are missing.");
}

// Function to get coordinates using PositionStack Geocoding API
function getCoordinates($query){
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

// Get the driver's ID (You can modify this part to match your authentication system)
$driverId = $_SESSION['unique_id'];

// Get coordinates for pickup and dropoff locations
$pickupCoordinates = getCoordinates($pickupLocation);
$dropoffCoordinates = getCoordinates($dropoffLocation);

if (empty($pickupLocation) || empty($dropoffLocation) || empty($seats) || empty($rideStartDate) || empty($rideStartTime)) {
  die("Error: Required fields are missing.");
}

// Prepare and execute the SQL query to create a new ride
$stmt = $conn->prepare("INSERT INTO rides (driver_id, pickup_location, pickup_latitude, pickup_longitude, dropoff_location, dropoff_latitude, dropoff_longitude, available_seats, ride_start_date, ride_start_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'upcoming')");
$stmt->bind_param("isssssssss", $driverId, $pickupLocation, $pickupCoordinates['latitude'], $pickupCoordinates['longitude'], $dropoffLocation, $dropoffCoordinates['latitude'], $dropoffCoordinates['longitude'], $seats, $rideStartDate, $rideStartTime);

if ($stmt->execute()) {
  // Get the newly created ride's ID
  $rideId = $stmt->insert_id;

  // Close the database connection
  $stmt->close();
  $conn->close();

  // Return the ride details as JSON response
  $response = array(
    'rideId' => $rideId,
    'pickup' => $pickupLocation,
    'pickupLatitude' => $pickupCoordinates['latitude'],
    'pickupLongitude' => $pickupCoordinates['longitude'],
    'dropoff' => $dropoffLocation,
    'dropoffLatitude' => $dropoffCoordinates['latitude'],
    'dropoffLongitude' => $dropoffCoordinates['longitude'],
    'seats' => $seats,
    'rideStartDate' => $rideStartDate,
    'rideStartTime' => $rideStartTime,
  );

  // Output the JSON response
  echo json_encode($response);
} else {
  // Output an error response as JSON
  echo json_encode(array('error' => 'Error creating ride'));
}
?>
