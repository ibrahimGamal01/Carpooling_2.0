<?php
session_start();
include_once "config.php";

// Path: php/check_session.php

$driverId = $_SESSION['unique_id'];
$fname = $_SESSION['fname'];

// Echo the session variables
echo "Unique ID: $driverId<br>";
echo "First Name: $fname";
?>
