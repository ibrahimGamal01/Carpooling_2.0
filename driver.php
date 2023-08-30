<?php
include_once "auth_check.php"; // Include the authentication check
include_once "php/config.php"; // Include other necessary files
?>

<?php include_once "header.php"; ?>

<style>
    body {
        background-image: url("src/UN_Campus.jpg");
    }

    .map {
        color: black;
    }
</style>

<body>

    <body>
        <nav class="navv">
            <div class="bx bx-menu" id="menu-icon"></div>

            <ul class="nav-list">
                <li><a href="Home.php">Home</a></li>
                <li><a href="passenger.php">Passenger</a></li>
                <li><a href="driver.php">Driver</a></li>
            </ul>
        </nav>

        <section class="hero">
            <div class="sidebar-container">
                <img src="src/car-svgrepo-com.svg" alt="Car Icon" class="car-icon">
                <div class="sidebar">
                    <h1 style="font-family: 'Arial', sans-serif; font-size: 45px;"> Create a Ride</h1>
                    <!-- <p style="font-family: 'Arial', sans-serif; font-size: 10px;">Fill out the form below to create a ride.</p> -->
                    <form id="create-ride-form" action="php/create_ride.php" method="POST">
                        <!-- <input type="hidden" name="driver" value="Driver Name"> -->

                        <label for="pickupLocation" style="font-family: 'Arial', sans-serif; font-size: 20px;">Pickup
                            Location</label>
                        <input type="text" id="pickupLocation" name="pickupLocation"
                            placeholder="Enter pickup location">

                        <label for="dropoffLocation" style="font-family: 'Arial', sans-serif; font-size: 20px;">Drop-off
                            Location</label>
                        <input type="text" id="dropoffLocation" name="dropoffLocation"
                            placeholder="Enter drop-off location">

                        <label for="seats" style="font-family: 'Arial', sans-serif; font-size: 20px;">Seats</label>
                        <input type="number" id="seats" name="seats" placeholder="Number of Available Seats" min="1">

                        <button type="submit" class="btn">Create Ride</button>
                    </form>
                </div>
            </div>
        </section>

        <div class="rides-container">
            <div class="ride-details" id="ride-details"></div>
        </div>
        <div class="main-content">
            <div id="map" class="map"></div>
            <!-- <div id="ride-details"></div> -->
        </div>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>
    <script src="javascript\driver.js"></script>
</body>

</html>