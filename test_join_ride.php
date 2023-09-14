<?php
include_once "auth_check.php";
include_once "php/config.php";
?>
<!-- test_join_ride.php -->
<?php include_once "header.php"; ?>

<body>
    <h1>Join Ride Test</h1>
    <form action="php/join_ride.php" method="POST">
        <label for="rideId">Ride ID:</label>
        <input type="text" id="rideId" name="rideId" required>
        <br>
        <input type="submit" value="Join Ride">
    </form>
</body>
