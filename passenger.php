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
    
    .ride-box-container:hover {
        background-color: lightgray;
        cursor: pointer;
    }

    .Check_all_btn {
        background-color: #4CAF50;
        color: black;
    }

    .Check_all_btn:hover {
        background-color: lightgray;
        border-color: #4CAF50;
        color: black;
    }
</style>

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
            <img src="src/car2-svgrepo-com.svg" alt="Car Icon" class="car-icon">
            <div class="sidebar">
                <h1>Find a Ride</h1>
                <form action="php/match_rides.php" method="post" id="find-suggested-rides">
                    <label for="pickupLocation">Pickup Location:</label>
                    <input type="text" id="pickupLocation" name="pickupLocation" placeholder="Enter pickup location"
                        required>

                    <label for="dropoffLocation">Drop-off Location:</label>
                    <input type="text" id="dropoffLocation" name="dropoffLocation" placeholder="Enter drop-off location"
                        required>

                    <button type="submit" class="btn">Find Matching Rides</button>
                </form>
                <form id="findRidesForm">
                    <button type="submit" class="btn Check_all_btn">Check All Rides</button>
                </form>
            </div>
        </div>
    </section>
    <div class="rides-container">
        <div class="ride-details ride-details-container" id="ride-details" data-pickup-lat="..." data-pickup-lon="..."
            data-dropoff-lat="..." data-dropoff-lon="..."></div>
    </div>

    <div class="main-content">
        <h1 class="success-message" style="display: none;"></h1>
        <div id="map" class="map"></div>
    </div>
</body>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>

    <script src="javascript\passenger.js"></script>


</body>

</html>