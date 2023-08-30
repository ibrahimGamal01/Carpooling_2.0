<?php
session_start();
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>
