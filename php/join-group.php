<?php
session_start();
include_once "config.php";

if (isset($_SESSION['unique_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $group_id = mysqli_real_escape_string($conn, $_POST['groupId']);
        $user_id = $_SESSION['unique_id'];

        // Check if the user is already a member of the group
        $check_sql = "SELECT * FROM groups WHERE group_id = {$group_id} AND FIND_IN_SET({$user_id}, members)";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            echo json_encode(array('error' => 'You are already a member of this group'));
            exit;
        }

        // Add the user to the group
        $update_sql = "UPDATE groups SET members = CONCAT(members, ',', {$user_id}) WHERE group_id = {$group_id}";
        $update_result = mysqli_query($conn, $update_sql);

        if ($update_result) {
            echo json_encode(array('success' => 'You have joined the group successfully'));
        } else {
            echo json_encode(array('error' => 'Error joining the group'));
        }
    } else {
        echo json_encode(array('error' => 'Invalid request method'));
    }
} else {
    echo json_encode(array('error' => 'Unauthorized access'));
}
?>
