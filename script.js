let menu = document.querySelector('#menu-icon');
let navList = document.querySelector('.nav-list');

menu.onclick = () => {
  menu.classList.toggle('bx-x');
  navList.classList.toggle('open');
}

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


////////////////////////////////////////// Driver //////////////////////////////////////////


//  // Function to fetch and update ride details
//  function updateRideDetails() {
//   fetch('php/get_ride_details.php', {
//     method: 'POST',
//   })
//     .then(response => response.json())
//     .then(data => {
//       const rideDetails = document.getElementById('ride-details');

//       if (data && data.length > 0) {
//         rideDetails.innerHTML = '';

//         data.forEach(rideData => {
//           const rideDiv = document.createElement('div');
//           rideDiv.classList.add('ride-box-container');

//           const driverInfo = document.createElement('p');
//           driverInfo.classList.add('driver');
//           driverInfo.textContent = `Driver: ${rideData.driver}`;
//           rideDiv.appendChild(driverInfo);

//           const seatsInfo = document.createElement('p');
//           seatsInfo.classList.add('seats');
//           seatsInfo.textContent = `Seats: ${rideData.available_seats}`;
//           rideDiv.appendChild(seatsInfo);

//           const pickupInfo = document.createElement('p');
//           pickupInfo.textContent = `Pickup: ${rideData.pickup_location}`;
//           rideDiv.appendChild(pickupInfo);

//           const dropoffInfo = document.createElement('p');
//           dropoffInfo.textContent = `Dropoff: ${rideData.dropoff_location}`;
//           rideDiv.appendChild(dropoffInfo);

//           // Display the passenger list
//           if (rideData.passengers && rideData.passengers.length > 0) {
//             const passengersHeading = document.createElement('h2');
//             passengersHeading.textContent = 'Passengers:';
//             rideDiv.appendChild(passengersHeading);

//             const passengersList = document.createElement('ul');
//             rideData.passengers.forEach(passenger => {
//               const passengerItem = document.createElement('li');
//               passengerItem.textContent = `Name: ${passenger.name}, ID: ${passenger.id}`;
//               passengersList.appendChild(passengerItem);
//             });
//             rideDiv.appendChild(passengersList);
//           }

//           rideDetails.appendChild(rideDiv);
//         });
//       } else {
//         rideDetails.innerHTML = '<p>No ride available.</p>';
//       }
//     })
//     .catch(error => {
//       console.error('Error:', error);
//     });
// }

// // Call updateRideDetails initially and then every 10 seconds (10000 milliseconds)
// updateRideDetails();
// setInterval(updateRideDetails, 10000); // Adjust the interval as needed


function findSuggestedRides() {
  const pickup = document.getElementById('pickup').value;
  const dropoff = document.getElementById('dropoff').value;

  fetch('php\match_rides.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify({ pickup, dropoff }),
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
                  driverInfo.textContent = `Driver: ${ride.driver_id}`;
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
                      const passengerName = prompt('Please enter your name:');
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
