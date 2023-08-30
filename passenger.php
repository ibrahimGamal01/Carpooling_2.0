<?php
include_once "auth_check.php"; // Include the authentication check
include_once "php/config.php"; // Include other necessary files
?>

<?php include_once "header.php"; ?>

<style>
    body{
        background-image: url("src/UN_Campus.jpg");
    }
    .map {
        color: black;
    }

    /* Add this style to change color on hover */
    .ride-box-container:hover {
        background-color: lightgray;
        /* Change to the color you prefer */
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
        <a href="#" class="logo"><img src="src/unfccc_logo.png" alt="Logo" class="logo-image"></a>
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
                <!-- <section class="suggested-rides" id="suggested-rides">
                    Suggested rides will be displayed here
                </section> -->
                <form id="findRidesForm">
                    <button type="submit" class="btn Check_all_btn">Check All Rides</button>
                </form>
            </div>
        </div>
    </section>


    <div id="loading-indicator" style="display: none;">
        <p>Loading...</p>
        <!-- You can use an actual loading animation here -->
    </div>
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
<script>

    const passengerName = "Ibrahim";

    let rideCoordinates = [];
    let rideDetails = [];

    let menu = document.querySelector('#menu-icon');
    let navList = document.querySelector('.nav-list');

    menu.onclick = () => {
        menu.classList.toggle('bx-x');
        navList.classList.toggle('open');
    }

    // function joinRide(rideId, passengerName) {
    //     const formData = new FormData();
    //     formData.append('ride_id', rideId);
    //     formData.append('passenger_name', passengerName);

    //     fetch('php/join_ride.php', {
    //         method: 'POST',
    //         body: formData,
    //     })
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log(data);
    //         })
    //         .catch(error => {
    //             console.error('Error:', error);
    //         });
    // }
    function joinRide(rideId) {
        const formData = new FormData();
        formData.append('ride_id', rideId);

        fetch('php/join_ride.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                const successMessage = document.querySelector('.success-message');
                successMessage.textContent = 'You successfully joined the ride! You can call the driver at +49 1522 343333';
                successMessage.style.display = 'block';
                
                // Scroll to the map
                const mapContainer = document.getElementById('map');
                mapContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                // Update map coordinates
                updateMapCoordinates(rideId);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // ****************************** Suggested Rides **********************************//

    // Add an event listener to the "Find Suggested Rides" form
    const findSuggestedRidesForm = document.getElementById('find-suggested-rides');
    findSuggestedRidesForm.addEventListener('submit', event => {
        event.preventDefault();
        findSuggestedRides();
    });

    // Update the event listener to prevent the default form submission behavior
    findSuggestedRidesForm.addEventListener('submit', event => {
        event.preventDefault();
        findSuggestedRides();
    });

    // Modify the fetch request to send form data
    function findSuggestedRides() {
        const formData = new FormData();
        formData.append('pickupLocation', document.getElementById('pickupLocation').value);
        formData.append('dropoffLocation', document.getElementById('dropoffLocation').value);

        fetch('php/match_rides.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                const rideDetails = document.getElementById('ride-details');
                rideDetails.innerHTML = '';

                if (data && data.length > 0) {
                    data.forEach(ride => {
                        const rideDiv = document.createElement('div');
                        rideDiv.classList.add('ride-box-container');

                        const driverInfo = document.createElement('p');
                        driverInfo.classList.add('driver');
                        // driverInfo.textContent = `Driver: ${ride.driver_id}`;
                        driverInfo.textContent = `Driver: ${"Anton"}`;
                        rideDiv.appendChild(driverInfo);

                        const seatsInfo = document.createElement('p');
                        seatsInfo.classList.add('seats');
                        seatsInfo.textContent = `Seats: ${ride.available_seats}`;
                        rideDiv.appendChild(seatsInfo);

                        const pickupInfo = document.createElement('p');
                        pickupInfo.textContent = `Pickup: ${ride.pickup_location}`;
                        rideDiv.appendChild(pickupInfo);

                        const dropoffInfo = document.createElement('p');
                        dropoffInfo.textContent = `Dropoff: ${ride.dropoff_location}`;
                        rideDiv.appendChild(dropoffInfo);

                        const joinButton = document.createElement('button');
                        joinButton.classList.add('btn');
                        joinButton.textContent = 'Join Ride';
                        rideDiv.appendChild(joinButton);

                        joinButton.addEventListener('click', () => {
                            // const passengerName = prompt('Please enter your name:');
                            if (passengerName) {
                                joinRide(ride.ride_id, passengerName);
                            }
                        });

                        const rideCoordinatesObj = {
                            ride_id: ride.ride_id,
                            pickup_latitude: ride.pickup_latitude,
                            pickup_longitude: ride.pickup_longitude,
                            dropoff_latitude: ride.dropoff_latitude,
                            dropoff_longitude: ride.dropoff_longitude
                        };
                        rideCoordinates.push(rideCoordinatesObj);

                        rideDiv.addEventListener('click', () => {
                            updateMapCoordinates(ride.ride_id);
                        });

                        rideDetails.appendChild(rideDiv);
                    });
                    const heroSection = document.querySelector('.hero');
                    heroSection.innerHTML = '';
                    heroSection.appendChild(rideDetails);
                } else {
                    const noRidesMsg = document.createElement('p');
                    noRidesMsg.textContent = 'No rides available.';
                    rideDetails.appendChild(noRidesMsg);
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }


    // ************************************* All Rides *************************************** //
    const findRidesForm = document.getElementById('findRidesForm');
    findRidesForm.addEventListener('submit', event => {
        event.preventDefault();
        findRides();
    });

    function findRides() {
        fetch('php/fetch_rides.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({}),
        })
            .then(response => response.json())
            .then(data => {
                const rideDetails = document.getElementById('ride-details');
                rideDetails.innerHTML = '';

                if (data && data.length > 0) {
                    data.forEach(ride => {
                        const rideDiv = document.createElement('div');
                        rideDiv.classList.add('ride-box-container');

                        const driverInfo = document.createElement('p');
                        driverInfo.classList.add('driver');
                        // driverInfo.textContent = `Driver: ${ride.driver_id}`;
                        driverInfo.textContent = `Driver: ${"Anton"}`;
                        rideDiv.appendChild(driverInfo);

                        const seatsInfo = document.createElement('p');
                        seatsInfo.classList.add('seats');
                        seatsInfo.textContent = `Seats: ${ride.available_seats}`;
                        rideDiv.appendChild(seatsInfo);

                        const pickupInfo = document.createElement('p');
                        pickupInfo.textContent = `Pickup: ${ride.pickup_location}`;
                        rideDiv.appendChild(pickupInfo);

                        const dropoffInfo = document.createElement('p');
                        dropoffInfo.textContent = `Dropoff: ${ride.dropoff_location}`;
                        rideDiv.appendChild(dropoffInfo);

                        const joinButton = document.createElement('button');
                        joinButton.classList.add('btn');
                        joinButton.textContent = 'Join Ride';
                        rideDiv.appendChild(joinButton);

                        joinButton.addEventListener('click', () => {
                            // const passengerName = prompt('Please enter your name:');
                            if (passengerName) {
                                joinRide(ride.ride_id, passengerName);
                            }
                        });

                        const rideCoordinatesObj = {
                            ride_id: ride.ride_id,
                            pickup_latitude: ride.pickup_latitude,
                            pickup_longitude: ride.pickup_longitude,
                            dropoff_latitude: ride.dropoff_latitude,
                            dropoff_longitude: ride.dropoff_longitude
                        };
                        rideCoordinates.push(rideCoordinatesObj);

                        rideDiv.addEventListener('click', () => {
                            updateMapCoordinates(ride.ride_id);
                        });

                        rideDetails.appendChild(rideDiv);
                    });
                    const heroSection = document.querySelector('.hero');
                    heroSection.innerHTML = '';
                    heroSection.appendChild(rideDetails);
                } else {
                    const noRidesMsg = document.createElement('p');
                    noRidesMsg.textContent = 'No rides available.';
                    rideDetails.appendChild(noRidesMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // ****************************** Map **********************************//
    // function updateMapCoordinates(rideId) {
    //     const selectedRide = rideCoordinates.find(ride => ride.ride_id === rideId);

    //     if (selectedRide) {
    //         const pickupLatLng = L.latLng(selectedRide.pickup_latitude, selectedRide.pickup_longitude);
    //         const dropoffLatLng = L.latLng(selectedRide.dropoff_latitude, selectedRide.dropoff_longitude);

    //         map.setView(pickupLatLng, zoom); // Update map view to pickup coordinates
    //         // You can also add markers or routes to indicate the selected ride on the map
    //     }
    // }

    let routingControl;

    function updateMapCoordinates(rideId) {
        const selectedRide = rideCoordinates.find(ride => ride.ride_id === rideId);

        if (selectedRide) {
            const pickupLatLng = L.latLng(selectedRide.pickup_latitude, selectedRide.pickup_longitude);
            const dropoffLatLng = L.latLng(selectedRide.dropoff_latitude, selectedRide.dropoff_longitude);

            // Clear previous route and markers if any
            if (routingControl) {
                map.removeControl(routingControl);
            }
            map.eachLayer(layer => {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            routingControl = L.Routing.control({
                waypoints: [
                    pickupLatLng,
                    dropoffLatLng
                ],
                routeWhileDragging: true,
                routeDragInterval: 500,
                collapsible: true,
                reverseWaypoints: false,
                showAlternatives: false,
                createMarker: function (i, wp, nWps) {
                    // ... (marker creation code)
                },
            }).addTo(map);

            // Change the view to fit the route
            map.fitBounds([pickupLatLng, dropoffLatLng]);

            // Add pickup and dropoff markers
            L.marker(pickupLatLng, { icon: svgpin_Icon2 }).addTo(map).bindPopup("Pickup Location");
            L.marker(dropoffLatLng, { icon: svgpin_Icon }).addTo(map).bindPopup("Dropoff Location");

            // Fit the map bounds to include pickup and dropoff locations
            const bounds = L.latLngBounds([pickupLatLng, dropoffLatLng]);
            map.fitBounds(bounds);

            // Scroll to the map
            const mapContainer = document.getElementById('map');
            mapContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });

        }
    }



    //This is the center of the map
    const cntr_loc = L.latLng(50.704129514100735, 7.16153100070237);

    //This is the location of the marker
    const orig_loc = L.latLng(50.7322, 7.0961);
    const dest_loc = L.latLng(50.718815, 7.125222);

    var zoom = 16;

    //This is the map
    var map = L.map("map", {
        preferCanvas: false,
    }).setView(cntr_loc, zoom);

    //This map tiles is simple and no hassles.
    L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "Map data &copy; OpenStreetMap contributors",
    }).addTo(map);

    //This is the marker
    const checkmk = `<svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg"><path stroke="black" stroke-width="1.5%" opacity="0.8" fill="brown" d="M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z"></path></svg>`;

    function checkmk_mk(color) {
        // assume checkmk has `brown` only 1 place
        return checkmk.replace(/brown/g, color);
    }

    const svgpin_Url = encodeURI("data:image/svg+xml;utf-8," + checkmk_mk("red"));
    const svgpin_Url2 = encodeURI(
        "data:image/svg+xml;utf-8," + checkmk_mk("green")
    );
    const svgpin_Url3 = encodeURI(
        "data:image/svg+xml;utf-8," + checkmk_mk("black")
    );

    const svgpin_Icon = L.icon({
        iconUrl: svgpin_Url,
        iconSize: [24, 24],
        iconAnchor: [12, 24],
        popupAnchor: [0, -22],
    });
    const svgpin_Icon2 = L.icon({
        iconUrl: svgpin_Url2,
        iconSize: [24, 24],
        iconAnchor: [12, 24],
        popupAnchor: [0, -22],
    });
    const svgpin_Icon3 = L.icon({
        iconUrl: svgpin_Url3,
        iconSize: [24, 24],
        iconAnchor: [12, 24],
        popupAnchor: [0, -22],
    });
</script>
</body>

</html>