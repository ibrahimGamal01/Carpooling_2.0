<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carpooling";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$passengerPickupLocation = isset($_POST['pickupLocation']) ? $_POST['pickupLocation'] : '';
$passengerDropoffLocation = isset($_POST['dropoffLocation']) ? $_POST['dropoffLocation'] : '';

$sql = "SELECT * FROM rides WHERE pickup_location = '$passengerPickupLocation' OR dropoff_location = '$passengerDropoffLocation'";
$result = $conn->query($sql);
$matchingRides = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $matchingRides[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return matching rides as JSON response
header('Content-Type: application/json');
echo json_encode($matchingRides);
?>
