<?php
// Path: php/create-group.php

//     header("location: login.php");
//   }
// CREATE TABLE `messages` (
//     `msg_id` INT PRIMARY KEY AUTO_INCREMENT,
//     `incoming_msg_id` INT NOT NULL,
//     `outgoing_msg_id` INT NOT NULL,
//     `msg` VARCHAR(1000) NOT NULL
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


//   -- Create a new table for groups
//   CREATE TABLE `groups` (
//     `group_id` INT PRIMARY KEY AUTO_INCREMENT,
//     `group_name` VARCHAR(255) NOT NULL,
//     `members` VARCHAR(1000) NOT NULL
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

//   -- Modify the `messages` table to include `group_id`
//   ALTER TABLE `messages` ADD `group_id` INT NOT NULL;

session_start();
include_once "config.php";

$group_name = mysqli_real_escape_string($conn, $_POST['group_name']); // Assuming you're sending the group name from the frontend
$creator_id = $_SESSION['unique_id']; 
// $creator_id = mysqli_real_escape_string($conn, $_POST['creator_id']); // Assuming you're sending the creator id from the frontend
// $members = mysqli_real_escape_string($conn, $_POST['members']); // Assuming you're sending the members from the frontend
// $members = $creator_id; // Assuming you're sending the members from the frontend

// $creator_id = 1;

$response = array();

if (empty($group_name)) {
    $response['error'] = "Group name is required!";
} else {
    // Check if a group with the same name already exists
    $check_sql = "SELECT * FROM groups WHERE group_name = '{$group_name}'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $response['error'] = "Group with this name already exists!";
    } else {
        // Create the new group
        $insert_sql = "INSERT INTO groups (group_name, creator_id, members) 
                       VALUES ('{$group_name}', {$creator_id}, '{$creator_id}')";
        $insert_result = mysqli_query($conn, $insert_sql);

        if ($insert_result) {
            $response['success'] = "Group created successfully";
        } else {
            $response['error'] = "Error creating group";
        }
    }
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>