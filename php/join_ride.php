<?php
// Path: php/join_ride.php
session_start();
include_once "config.php"; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
  die("Error: User not logged in.");
}

// CREATE TABLE `ride_passengers` (
//     `passenger_id` INT PRIMARY KEY AUTO_INCREMENT,
//     `ride_id` INT NOT NULL,
//     `passenger_name` VARCHAR(255) NOT NULL,
//     FOREIGN KEY (`ride_id`) REFERENCES `rides`(`ride_id`)
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// Retrieve the ride ID and user ID from the request
$requestData = json_decode(file_get_contents('php://input'), true);
$rideId = isset($requestData['rideId']) ? $requestData['rideId'] : '';
$userId = $_SESSION['unique_id'];

// Validate the required fields
if (empty($rideId)) {
  die("Error: Required fields are missing.");
}

// Check if the user is already a passenger in this ride
$checkPassengerQuery = "SELECT * FROM ride_passengers WHERE ride_id = ? AND passenger_id = ?";
$checkPassengerStmt = $conn->prepare($checkPassengerQuery);
$checkPassengerStmt->bind_param("ii", $rideId, $userId);
$checkPassengerStmt->execute();
$existingPassenger = $checkPassengerStmt->fetch();
$checkPassengerStmt->close();

if ($existingPassenger) {
  // User is already a passenger in this ride
  echo json_encode(array('error' => 'You are already a passenger in this ride.'));
} else {
  // Add the user as a passenger to the ride
  $addPassengerQuery = "INSERT INTO ride_passengers (ride_id, passenger_id, passenger_name) VALUES (?, ?, ?)";
  $addPassengerStmt = $conn->prepare($addPassengerQuery);
  $passengerName = $_SESSION['fname'] . " " . $_SESSION['lname']; // Change to the actual name
  $addPassengerStmt->bind_param("iss", $rideId, $userId, $passengerName);

  if ($addPassengerStmt->execute()) {
    // Passenger added successfully
    echo json_encode(array('success' => true, 'message' => 'You have successfully joined the ride.'));
  } else {
    // Error adding passenger
    echo json_encode(array('error' => 'Error joining the ride.'));
  }

  $addPassengerStmt->close();
}

// Close the database connection
$conn->close();
?>
