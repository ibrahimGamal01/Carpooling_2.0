CREATE TABLE users_new (
  user_id INT(11) NOT NULL,
  unique_id INT PRIMARY KEY,
  fname VARCHAR(255) NOT NULL,
  lname VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  img VARCHAR(255) NOT NULL,
  status VARCHAR(255) NOT NULL,
  user_type ENUM('regular', 'admin') NOT NULL DEFAULT 'regular'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users_new
SELECT * FROM users;

RENAME TABLE users TO users_old, users_new TO users;

DROP TABLE users_old;

alter table users_old
add rank_id INT;


alter table users_old
add constraint rank_summoner_FK foreign key (rank_id) references ranks(rank_id);



-- Retrieve all users
SELECT * FROM users;

-- Retrieve specific columns from the rides table
SELECT ride_id, pickup_location, dropoff_location FROM rides;

-- Retrieve users who are admins
SELECT * FROM users WHERE user_type = 'regular';

-- Delete a specific user
DELETE FROM users WHERE user_id = 1;

-- Delete a specific message
DELETE FROM messages WHERE msg_id = 1;

-- Inserting data into Users table
INSERT INTO users (user_id, unique_id, fname, lname, email, password, img, status, user_type) 
VALUES (12, 123456, 'John', 'Doe', 'john@example.com', 'password123', 'avatar.jpg', 'active', 'regular');

-- Inserting data into Rides table
INSERT INTO rides (driver_id, pickup_location, dropoff_location, available_seats, ride_start_date, ride_start_time, status) 
VALUES (1, 'Location A', 'Location B', 4, '2024-01-07', '08:00:00', 'upcoming');

-- Select users whose status is 'active' and user_type is 'regular'
SELECT * FROM users WHERE status = 'active' AND user_type = 'regular';

-- Select users whose user_id is either 1 or 2
SELECT * FROM users WHERE user_id IN (1, 2);

-- Select rides where available seats are between 2 and 4
SELECT * FROM rides WHERE available_seats BETWEEN 2 AND 4;

-- Select rides with a pickup location like 'Location%'
SELECT * FROM rides WHERE pickup_location LIKE '%UN%' OR dropoff_location LIKE '%UN%';

-- Select rows from users that have matches in rides
SELECT users.*, rides.*
FROM users
LEFT JOIN rides ON users.unique_id = rides.driver_id
UNION
-- Select rows from rides that do not have matches in users
SELECT users.*, rides.*
FROM users
RIGHT JOIN rides ON users.unique_id = rides.driver_id;


-- Full Outer Join: Retrieve all information from both tables, joining on a condition
SELECT users.*, rides.*
FROM users FULL OUTER JOIN rides 
ON users.unique_id = rides.driver_id;

-- Inner Join: Retrieve rides with associated driver's information
SELECT rides.*, users.*
FROM rides INNER JOIN users 
ON rides.driver_id = users.unique_id;

-- Count the total number of rides
SELECT COUNT(*) AS total_rides FROM rides;

-- Calculate the maximum available seats in rides
SELECT MAX(available_seats) AS max_seats FROM rides;

-- Calculate the average available seats in rides
SELECT AVG(available_seats) AS avg_seats FROM rides;

-- Count the distinct user types in the users table
SELECT COUNT(DISTINCT user_type) AS distinct_user_types FROM users;


-- Update user's email
UPDATE users SET email = 'newemail@example.com' WHERE user_id = 1;

-- Update ride's status
UPDATE rides SET status = 'completed' WHERE ride_id = 1;















-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2024 at 07:22 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carpooling`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `creator_id`) VALUES
(2, 'Avengers', 1036814907);

-- --------------------------------------------------------

--
-- Table structure for table `group_chat_participants`
--

CREATE TABLE `group_chat_participants` (
  `participant_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_chat_participants`
--

INSERT INTO `group_chat_participants` (`participant_id`, `group_id`, `user_id`) VALUES
(1, 2, 1036814907);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(11) NOT NULL,
  `outgoing_msg_id` int(11) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `group_id`) VALUES
(1, 636131449, 1036814907, 'What', 0),
(2, 1036814907, 422536715, 'Need a new suit', 0),
(3, 422536715, 1036814907, 'You have to finish a job first.', 0),
(5, 1241725182, 1036814907, 'hi', 0),
(6, 2, 1036814907, 'hi', 0),
(7, 2, 1241725182, 'hi', 0),
(8, 1036814907, 1241725182, 'hi', 0),
(9, 1036814907, 1241725182, 'hh', 0),
(10, 1241725182, 1241725182, 'haha]', 0),
(12, 422536715, 1036814907, 'Eliminate Thanos', 0),
(13, 1036814907, 422536715, 'Done', 0),
(15, 852448273, 422536715, 'Hi there, I need to go to Bonn central Station', 0),
(16, 422536715, 852448273, 'I need you to do something.', 0),
(17, 422536715, 852448273, 'Eliminate Thanos and your ride will be secured.', 0),
(18, 852448273, 422536715, 'Done.', 0),
(19, 852448273, 422536715, 'Waiting for you in UNFCCC garage & get me a cleaner suit.', 0),
(20, 852448273, 1241725182, 'Hi Tony', 0),
(21, 852448273, 1241725182, 'Can I get a suit', 0),
(22, 852448273, 1241725182, 'sadas', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `ride_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `dropoff_location` varchar(255) NOT NULL,
  `pickup_latitude` decimal(10,8) DEFAULT NULL,
  `pickup_longitude` decimal(11,8) DEFAULT NULL,
  `dropoff_latitude` decimal(10,8) DEFAULT NULL,
  `dropoff_longitude` decimal(11,8) DEFAULT NULL,
  `available_seats` int(11) NOT NULL,
  `status` enum('upcoming','in progress','completed','canceled') NOT NULL,
  `ride_start_date` date NOT NULL,
  `ride_start_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`ride_id`, `driver_id`, `pickup_location`, `dropoff_location`, `pickup_latitude`, `pickup_longitude`, `dropoff_latitude`, `dropoff_longitude`, `available_seats`, `status`, `ride_start_date`, `ride_start_time`) VALUES
(30, 852448273, 'UNFCCC', 'Bonn Central Station', 50.71881500, 7.12522200, 50.73220000, 7.09610000, 2, 'upcoming', '2023-09-14', '15:00:00'),
(31, 1241725182, 'Restaurant Portugal Bonn', 'unfccc', 50.66901000, 7.18177300, 50.71881500, 7.12522200, 2, 'upcoming', '2023-09-14', '15:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `ride_passengers`
--

CREATE TABLE `ride_passengers` (
  `passenger_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `passenger_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `user_type` enum('regular','admin') NOT NULL DEFAULT 'regular'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `fname`, `lname`, `email`, `password`, `img`, `status`, `user_type`) VALUES
(2, 422536715, 'John', 'Wick', 'John.Wick@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '1693659259john.png', 'Offline now', 'regular'),
(5, 1241725182, 'Ibrahim', 'Gamal', 'ibrahim@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '1693660123Ibrahim_Gamal.jpg', 'Active now', 'regular'),
(6, 852448273, 'Tony', 'Stark', 'Tony.Stark@gmail.com', 'c98703aed69284552ffffea25a1706d9', '1694612422tony_stark.png', 'Active now', 'regular');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `group_chat_participants`
--
ALTER TABLE `group_chat_participants`
  ADD PRIMARY KEY (`participant_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`ride_id`);

--
-- Indexes for table `ride_passengers`
--
ALTER TABLE `ride_passengers`
  ADD PRIMARY KEY (`passenger_id`),
  ADD KEY `ride_id` (`ride_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `group_chat_participants`
--
ALTER TABLE `group_chat_participants`
  MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `ride_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `ride_passengers`
--
ALTER TABLE `ride_passengers`
  MODIFY `passenger_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_chat_participants`
--
ALTER TABLE `group_chat_participants`
  ADD CONSTRAINT `group_chat_participants_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`);

--
-- Constraints for table `ride_passengers`
--
ALTER TABLE `ride_passengers`
  ADD CONSTRAINT `ride_passengers_ibfk_1` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
