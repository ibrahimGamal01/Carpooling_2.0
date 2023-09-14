<?php
// Path: php/join-group.php
session_start();
include_once "config.php";

$group_id = mysqli_real_escape_string($conn, $_POST['group_id']);
$user_id = $_SESSION['unique_id'];

$response = array();

if (empty($group_id)) {
    $response['error'] = "Group ID is required!";
} else {
    // Check if the user is already a member of the group
    $check_membership_sql = "SELECT * FROM group_chat_participants WHERE group_id = {$group_id} AND user_id = {$user_id}";
    $check_membership_result = mysqli_query($conn, $check_membership_sql);

    if (mysqli_num_rows($check_membership_result) > 0) {
        $response['error'] = "You are already a member of this group";
    } else {
        // Add the user to the group
        $join_group_sql = "INSERT INTO group_chat_participants (group_id, user_id) VALUES ({$group_id}, {$user_id})";
        $join_group_result = mysqli_query($conn, $join_group_sql);

        if ($join_group_result) {
            $response['success'] = "You have successfully joined the group";
        } else {
            $response['error'] = "Error joining the group";
        }
    }
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
