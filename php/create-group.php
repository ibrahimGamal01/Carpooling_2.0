<?php
// Path: php/create-group.php
session_start();
include_once "config.php";

$group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
$creator_id = $_SESSION['unique_id'];

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
        $insert_sql = "INSERT INTO groups (group_name, creator_id) VALUES ('{$group_name}', {$creator_id})";
        $insert_result = mysqli_query($conn, $insert_sql);

        if ($insert_result) {
            $group_id = mysqli_insert_id($conn); // Get the ID of the newly created group
            // Add the creator as a participant in the group
            $add_creator_sql = "INSERT INTO group_chat_participants (group_id, user_id) VALUES ({$group_id}, {$creator_id})";
            $add_creator_result = mysqli_query($conn, $add_creator_sql);

            if ($add_creator_result) {
                $response['success'] = "Group created successfully";
            } else {
                $response['error'] = "Error adding creator to the group";
            }
        } else {
            $response['error'] = "Error creating group";
        }
    }
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
