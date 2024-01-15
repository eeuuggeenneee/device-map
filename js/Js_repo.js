


// $(document).ready(function () {
//     // Create a Leaflet map
//     var map = L.map('map');

//     // Set up the tile layer, e.g., OpenStreetMap
//     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//         attribution: '© OpenStreetMap contributors'
//     }).addTo(map);

//     // Define a marker for the user's location
//     var marker = L.marker();

//     // Add an event listener for the locationfound event
//     map.on('locationfound', function (e) {
//         // Update the marker's position
//         marker.setLatLng(e.latlng).addTo(map);

//         // Display a popup with the user's coordinates
//         marker.bindPopup("You are here: " + e.latlng.lat.toFixed(6) + ", " + e.latlng.lng.toFixed(6)).openPopup();
//     });

//     // Add an event listener for the locationerror event
//     map.on('locationerror', function (e) {
//         alert("Error getting your location: " + e.message);
//     });

//     var movementThreshold = 5;
//     // Function to update marker position
//     function updateLocation(position) {
//         // Check if the device has moved significantly
//         if (marker.getLatLng().distanceTo([position.coords.latitude, position.coords.longitude]) > movementThreshold) {
//             var latlng = [position.coords.latitude, position.coords.longitude];
//             marker.setLatLng(latlng);
//             // Update the marker's popup content with the latest coordinates and other information
//             marker.bindPopup("You are here: " + latlng[0].toFixed(6) + ", " + latlng[1].toFixed(6));
//             $('#info').text("You are here: " + latlng[0].toFixed(6) + ", " + latlng[1].toFixed(6))
//             console.log("You are here: " + latlng[0].toFixed(6) + ", " + latlng[1].toFixed(6))
//             // Open the popup (optional)
//             marker.openPopup();
//             map.setView(latlng, map.getZoom());
//         }
//     }

//     // Request the user's location
//     map.locate({ setView: true, maxZoom: 18 });

//     // Watch the user's location for continuous updates
//     var watchID = navigator.geolocation.watchPosition(updateLocation, function (error) {
//         alert("Error getting your location: " + error.message);
//     });

// });
