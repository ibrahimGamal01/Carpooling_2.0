let menu = document.querySelector('#menu-icon');
let navList = document.querySelector('.nav-list');

menu.onclick = () => {
    menu.classList.toggle('bx-x');
    navList.classList.toggle('open');
}

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

    // Send a request to the server to create a new ride
    fetch('php/create_ride.php', {
        method: 'POST',
        body: JSON.stringify({ pickupLocation, dropoffLocation, seats }), // Updated object keys
    })
        .then(response => response.json())
        .then(data => {
            // Show the ride details on success
            const rideDetails = document.getElementById('ride-details');
            rideDetails.innerHTML = '';

            if (data && data.rideId) {
                const rideDiv = document.createElement('div');
                rideDiv.classList.add('ride-box-container');

                const driverInfo = document.createElement('p');
                driverInfo.classList.add('driver');
                // driverInfo.textContent = `Driver: ${data.driverName}`;
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

/* Start routing control  */
function begin_routing() {
    L.Routing.control({
        waypoints: [
            orig_loc,
            dest_loc,
            //L.latLng(14.1688, 100.2918),
            //L.latLng(13.7042, 100.6032)
        ],
        routeWhileDragging: true,
        routeDragInterval: 500,
        collapsible: true, // hide/show panel routing
        reverseWaypoints: false,
        showAlternatives: false,
        createMarker: function (i, wp, nWps) {
            switch (i) {
                case 0:
                    return L.marker(wp.latLng, {
                        icon: svgpin_Icon,
                        draggable: true,
                    }).bindPopup("<b>" + "Origin" + "</b>");
                case nWps - 1:
                    return L.marker(wp.latLng, {
                        icon: svgpin_Icon2,
                        draggable: true,
                    }).bindPopup("<b>" + "Destination" + "</b>");
                default:
                    return L.marker(wp.latLng, {
                        icon: svgpin_Icon3,
                        draggable: true,
                    }).bindPopup("<b>" + "Waypoint" + "</b>");
            }
        },
    }).addTo(map);
}

begin_routing();
