<?php
include_once "auth_check.php"; // Include the authentication check
include_once "php/config.php"; // Include other necessary files
?>

<?php include_once "header.php"; ?>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
</head>

<style>
    body {
        margin: 0;
        padding: 0;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: url('src/UN_Campus.jpg') center/cover no-repeat;
        position: relative;
        overflow-x: hidden;

    }

    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: linear-gradient(to bottom right, rgba(125, 204, 197, 0.9), #1F4E5A);
        /* Adjusted second color to a darker shade */

        pointer-events: none;
        z-index: -1;
    }


    .sidebar-container {
        display: flex;
        height: 80vh;
        width: 100%;
        max-width: 100%;
    }

    .sidebar {
        flex: 1;
        padding: 10px;
        display: flex;
        flex-direction: column;
        background-color: rgba(113, 112, 112, 0.21);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    .main-content {
        flex: 1;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        margin: 0 10px;
    }

    .map {
        height: calc(100vh - 100px);
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
        color: black;
        border-radius: 10px;
        background-color: rgba(255, 255, 255, 0);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);

    }

    h1 {
        font-family: 'Arial', sans-serif;
        font-size: 35px;
    }

    @media (max-width: 860px) {
        h1 {
            font-size: 25px;
        }

        .sidebar-container {
            flex-direction: column;
            align-items: center;
            width: 100%;
            height: max-content;
            max-width: 100%;
            overflow-x: hidden;
        }

        .sidebar,
        .main-content {
            width: 100%;
            padding: 5px;
        }

        .map {
            height: 400px;
            width: 100%;
        }

        .car-icon {
            display: none;
        }

        #pickupLocation,
        #dropoffLocation,
        #seats {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            font-size: 14px;
        }
    }

    #driver_page {
        background-color: darkcyan;
        color: white;
    }

    /* CSS for small form inputs */
    .form_small_inputs {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
    }

    .form-group {
        flex: 1;
        max-width: calc(33.33% - 20px);
        /* Adjust the width as needed */
    }

    .form-group label {
        font-family: 'Arial', sans-serif;
        font-size: 20px;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .ride-box-container {
        width: 90%;
    }

    /* Update the ride-box-container class */
    .ride-box-container {
        /* TODO: */
        width: 100%;
        /* width: calc(50% - 5px); */
        height: 160px;
        /* Adjust the height to 160px */
        background: linear-gradient(135deg, rgba(26, 26, 29, 0.8), rgba(51, 52, 56, 0.8));
        box-shadow: 0 0 10px rgba(16, 218, 212, 0.4);
        /* Adjust the box-shadow */
        transition: max-width 0.4s;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        float: right;
        margin-bottom: 10px;
        border-radius: 10px;
        /* Add border-radius */
        padding: 10px;
        /* Add padding for inner elements */
    }

    /* Style the elements inside the ride-box-container */
    .ride-box-container .driver {
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 5px;
        color: #fff;
        /* Text color */
    }

    .ride-box-container .seats {
        font-size: 16px;
        color: #fff;
        /* Text color */
    }

    .ride-box-container .pickup-location,
    .ride-box-container .dropoff-location {
        font-size: 16px;
        color: #fff;
        /* Text color */
    }

    /* Add styling for the join button */
    .ride-box-container .btn {
        background-color: #1f4e5a;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .ride-box-container .btn:hover {
        background-color: #16748f;
    }
    .ride-box-container:hover {
        background-color: lightgray;
        cursor: pointer;
    }
</style>


<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                    if (mysqli_num_rows($sql) > 0) {
                        $row = mysqli_fetch_assoc($sql);
                    }
                    ?>
                    <img src="php/images/<?php echo $row['img']; ?>" alt="">
                    <div class="details">
                        <span>
                            <?php echo $row['fname'] . " " . $row['lname'] ?>
                        </span>
                        <p>
                            <?php echo $row['status']; ?>
                        </p>
                    </div>
                </div>
                <h1> Create a Ride</h1>
                <div class="navigate">
                    <div class="bx bx-menu" id="menu-icon" style="position: relative;"></div>
                    <ul class="nav_items">
                        <li><a href="Home.php" class="logout">Home</a></li>
                        <li><a href="passenger.php" class="logout">Passenger</a></li>
                        <li><a href="driver.php" class="logout" id="driver_page">Driver</a></li>
                        <li><a href="users.php" class="logout">Chat</a></li>
                        <li><a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout"
                                id="logout">Logout</a></li>
                    </ul>
                </div>
            </header>
        </section>
    </div>


    <div class="sidebar-container">
        <div class="main-content">
            <div id="map" class="map"></div>
        </div>

        <div class="sidebar">
            <form id="create-ride-form" action="php/create_ride.php" method="POST">
                <img src="src/car-svgrepo-com.svg" alt="Car Icon" class="car-icon">

                <label for="pickupLocation" style="font-family: 'Arial', sans-serif; font-size: 20px;">Pickup
                    Location</label>
                <input type="text" id="pickupLocation" name="pickupLocation" placeholder="Pickup location">

                <label for="dropoffLocation" style="font-family: 'Arial', sans-serif; font-size: 20px;">Drop-off
                    Location</label>
                <input type="text" id="dropoffLocation" name="dropoffLocation" placeholder="Drop-off location">

                <div class="form_small_inputs">
                    <div class="form-group">
                        <label for="seats" style="font-family: 'Arial', sans-serif; font-size: 20px;">Seats</label>
                        <input type="number" id="seats" name="seats" placeholder="Available Seats" min="1">
                    </div>

                    <div class="form-group">
                        <label for="rideStartDate" style="font-family: 'Arial', sans-serif; font-size: 20px;">Start
                            Date</label>
                        <input type="date" id="rideStartDate" name="rideStartDate" value="<?php echo date('Y-m-d'); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="rideStartTime" style="font-family: 'Arial', sans-serif; font-size: 20px;">Start
                            Time</label>
                        <input type="time" id="rideStartTime" name="rideStartTime" required>
                    </div>
                </div>

                <button type="submit" class="btn">Create Ride</button>

                <div class="rides-container">
                    <div class="ride-details" id="ride-details"></div>
                </div>
            </form>



        </div>

    </div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    // ***************************** Navbar ***************************** //

    let menu = document.querySelector('#menu-icon');
    let navList = document.querySelector('.nav_items');

    menu.onclick = () => {
        menu.classList.toggle('bx-x');
        navList.classList.toggle('open');
    }

    // ***************************** Create Ride ***************************** //

    const createRideForm = document.getElementById('create-ride-form');
    createRideForm.addEventListener('submit', event => {
        event.preventDefault();
        createRide();
        // updateMapCoordinates(pickupLocation, dropoffLocation);
    });

    function createRide() {
        const pickupLocation = document.getElementById('pickupLocation').value;
        const dropoffLocation = document.getElementById('dropoffLocation').value;
        const seats = document.getElementById('seats').value;
        const rideStartDate = document.getElementById('rideStartDate').value;
        const rideStartTime = document.getElementById('rideStartTime').value;

        // Send a request to the server to create a new ride
        fetch('php/create_ride.php', {
            method: 'POST',
            body: JSON.stringify({ pickupLocation, dropoffLocation, seats, rideStartDate, rideStartTime }),
        })
            .then(response => response.json())
            .then(data => {
                // Show the ride details on success
                const rideDetails = document.getElementById('ride-details');
                rideDetails.innerHTML = '';

                if (data && data.rideId) {
                    const rideDiv = document.createElement('div');
                    rideDiv.classList.add('ride-box-container');
                    rideDiv.addEventListener('click', () => {
                        window.location.href = 'users.php';
                    });

                    const driverInfo = document.createElement('p');
                    driverInfo.classList.add('driver');
                    // driverInfo.textContent = `Driver: ${data.driverName}`;
                    driverInfo.textContent = `Waiting for passengers`;
                    rideDiv.appendChild(driverInfo);

                    const seatsInfo = document.createElement('p');
                    seatsInfo.classList.add('seats');
                    seatsInfo.textContent = `Seats: ${data.seats}`;
                    rideDiv.appendChild(seatsInfo);

                    const pickupInfo = document.createElement('p');
                    pickupInfo.textContent = `Pickup: ${data.pickup}`;
                    rideDiv.appendChild(pickupInfo);

                    const dropoffInfo = document.createElement('p');
                    dropoffInfo.textContent = `Dropoff: ${data.dropoff}`;
                    rideDiv.appendChild(dropoffInfo);

                    // Display the passenger list
                    if (data.passengers && data.passengers.length > 0) {
                        const passengersDiv = document.createElement('div');
                        passengersDiv.classList.add('passengers-box');

                        const passengersHeading = document.createElement('h2');
                        passengersHeading.textContent = 'Passengers:';
                        passengersDiv.appendChild(passengersHeading);

                        const passengersList = document.createElement('ul');
                        data.passengers.forEach(passenger => {
                            const passengerItem = document.createElement('li');
                            passengerItem.textContent = `Name: ${passenger.name}, ID: ${passenger.id}`;
                            passengersList.appendChild(passengerItem);
                        });
                        passengersDiv.appendChild(passengersList);

                        rideDiv.appendChild(passengersDiv);
                    }

                    rideDetails.appendChild(rideDiv);
                } else {
                    const noRidesMsg = document.createElement('p');
                    noRidesMsg.textContent = 'Failed to create a ride.';
                    rideDetails.appendChild(noRidesMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // ***************************** Map ***************************** //
    // Initialize the map
    const map = L.map('map').setView([50.704129514100735, 7.16153100070237], 16);

    // Add tile layer to the map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add Geocoder control
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        collapsed: false,
        placeholder: "Search...",
        errorMessage: "Nothing found.",
        geocoder: L.Control.Geocoder.nominatim()
    })
        .on('markgeocode', function (event) {
            const result = event.geocode;
            map.fitBounds(result.bbox);
            L.marker(result.center).addTo(map).bindPopup(result.name || result.properties.formatted).openPopup();
        })
        .addTo(map);

    // ***************************** Time Formating ***************************** //
    function formatTwoDigits(num) {
        return num < 10 ? '0' + num : num;
    }

    // Get the current date and time
    const now = new Date();

    // Add 10 minutes to the current time
    now.setMinutes(now.getMinutes() + 10);

    // Extract the updated time (hours and minutes) as a string in "HH:MM" format
    const updatedTime = `${formatTwoDigits(now.getHours())}:${formatTwoDigits(now.getMinutes())}`;

    // Set the value of the "Ride Start Time" input field to the updated time
    document.getElementById('rideStartTime').value = updatedTime;
</script>


</body>

</html>