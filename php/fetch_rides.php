<?php
// Path: php/fetch_rides.php
session_start();
include_once "config.php";

// -- Create the `rides` table
// CREATE TABLE `rides` (
//   `ride_id` INT AUTO_INCREMENT PRIMARY KEY,
//   `driver_id` INT NOT NULL,
//   `pickup_location` VARCHAR(255) NOT NULL,
//   `dropoff_location` VARCHAR(255) NOT NULL,
//   `fname` VARCHAR(255),
//   `pickup_latitude` DECIMAL(10, 8),
//   `pickup_longitude` DECIMAL(11, 8),
//   `dropoff_latitude` DECIMAL(10, 8),
//   `dropoff_longitude` DECIMAL(11, 8),
//   `available_seats` INT NOT NULL,
//   `ride_start_date` DATE NOT NULL,
//   `ride_start_time` TIME NOT NULL,
//   `status` ENUM('upcoming', 'in progress', 'completed', 'canceled') NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// Fetch all rides from the rides table
$sql = "SELECT * FROM rides";
$result = $conn->query($sql);
$rides = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rides[] = $row;
    }
}

// Return all rides as JSON response
header('Content-Type: application/json');
echo json_encode($rides);

// Close the database connection
$conn->close();
?>
