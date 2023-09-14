SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Create the `users` table
CREATE TABLE `users` (
  `user_id` INT PRIMARY KEY AUTO_INCREMENT,
  `unique_id` INT NOT NULL,
  `fname` VARCHAR(255) NOT NULL,
  `lname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `img` VARCHAR(255) NOT NULL,
  `status` VARCHAR(255) NOT NULL,
  `user_type` ENUM('regular', 'admin') NOT NULL DEFAULT 'regular'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the `rides` table
CREATE TABLE `rides` (
  `ride_id` INT AUTO_INCREMENT PRIMARY KEY,
  `driver_id` INT NOT NULL,
  `pickup_location` VARCHAR(255) NOT NULL,
  `dropoff_location` VARCHAR(255) NOT NULL,
  `fname` VARCHAR(255),
  `pickup_latitude` DECIMAL(10, 8),
  `pickup_longitude` DECIMAL(11, 8),
  `dropoff_latitude` DECIMAL(10, 8),
  `dropoff_longitude` DECIMAL(11, 8),
  `available_seats` INT NOT NULL,
  `ride_start_date` DATE NOT NULL,
  `ride_start_time` TIME NOT NULL,
  `status` ENUM('upcoming', 'in progress', 'completed', 'canceled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the `messages` table
CREATE TABLE `messages` (
  `msg_id` INT PRIMARY KEY AUTO_INCREMENT,
  `incoming_msg_id` INT NOT NULL,
  `outgoing_msg_id` INT NOT NULL,
  `msg` VARCHAR(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Create a new table for groups
CREATE TABLE `groups` (
  `group_id` INT PRIMARY KEY AUTO_INCREMENT,
  `group_name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Modify the `messages` table to include `group_id`
ALTER TABLE `messages` ADD `group_id` INT NOT NULL;

-- Add a `creator_id` to the `groups` table
ALTER TABLE `groups` ADD `creator_id` INT NOT NULL;

-- Create a table for group participants (members)
CREATE TABLE `group_chat_participants` (
  `participant_id` INT PRIMARY KEY AUTO_INCREMENT,
  `group_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `ride_passengers` (
  `passenger_id` INT PRIMARY KEY AUTO_INCREMENT,
  `ride_id` INT NOT NULL,
  `passenger_name` VARCHAR(255) NOT NULL,
  FOREIGN KEY (`ride_id`) REFERENCES `rides`(`ride_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- -----------------------------------------------------------------------------

-- -- Create the `ride_passengers` table
-- CREATE TABLE `ride_passengers` (
--   `ride_id` INT NOT NULL,
--   `passenger_id` INT NOT NULL,
--   PRIMARY KEY (`ride_id`, `passenger_id`),
--   FOREIGN KEY (`ride_id`) REFERENCES `rides`(`ride_id`),
--   FOREIGN KEY (`passenger_id`) REFERENCES `users`(`user_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -- Create the `passengers` table
-- CREATE TABLE `passengers` (
--   `passenger_id` INT AUTO_INCREMENT PRIMARY KEY,
--   `ride_id` INT NOT NULL,
--   `name` VARCHAR(255) NOT NULL,
--   FOREIGN KEY (`ride_id`) REFERENCES `rides`(`ride_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the `bookings` table
-- CREATE TABLE `bookings` (
--   `booking_id` INT PRIMARY KEY AUTO_INCREMENT,
--   `ride_id` INT NOT NULL,
--   `passenger_id` INT NOT NULL,
--   `booking_date` DATE NOT NULL,
--   `booking_time` TIME NOT NULL,
--   FOREIGN KEY (`ride_id`) REFERENCES `rides`(`ride_id`),
--   FOREIGN KEY (`passenger_id`) REFERENCES `users`(`user_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the `cars` table
-- CREATE TABLE `cars` (
--   `car_id` INT PRIMARY KEY AUTO_INCREMENT,
--   `driver_id` INT NOT NULL,
--   `make` VARCHAR(50) NOT NULL,
--   `model` VARCHAR(50) NOT NULL,
--   `year` INT NOT NULL,
--   `color` VARCHAR(50) NOT NULL,
--   `seating_capacity` INT NOT NULL,
--   FOREIGN KEY (`driver_id`) REFERENCES `users`(`user_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

