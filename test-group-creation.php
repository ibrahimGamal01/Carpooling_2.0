<?php
// Path: test-group-creation.php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
}
?>
<?php include_once "header.php"; ?>

<!-- Add your testing content here -->
<div class="group-creation-form">
  <h2>Create a New Group</h2>
  <form action="php/create-group.php" method="POST">
    <label for="group_name">Group Name:</label>
    <input type="text" id="group_name" name="group_name" required>
    <button type="submit">Create Group</button>
  </form>
</div>
