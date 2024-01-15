<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablet Position Tracking</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />


    <style>
        #map {
            height: 400px;
            /* height: calc(20vh - 20px); */
        }
    </style>
</head>


<?php
include("./includes/db.php");
?>

<body>
    <div id="map"></div>
    <p id="info">Distance: 0 meters</p>



    <script>

    </script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/@turf/turf"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
    <script src="js/Js_repo.js"></script>

    <script>
        var map = L.map('map');

        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker, circle;
        var previousPosition;

        if (!navigator.geolocation) {
            console.log("Your browser doesn't support geolocation feature!")
        } else {
            // Use watchPosition for continuous tracking
            navigator.geolocation.watchPosition(getPosition, handleError, {
                enableHighAccuracy: true,
                maximumAge: 10000, // Maximum age of a cached position in milliseconds
            });
        }
        var lc = L.control.locate({
            position: 'topleft',
            strings: {
                title: "Geoloc"

            },

        }).addTo(map);

        function getPosition(position) {
            var lat = position.coords.latitude;
            var long = position.coords.longitude;
            var accuracy = position.coords.accuracy;
            var distance = 0.00;
            // Check if the marker and circle already exist
            if (!marker) {
                marker = L.marker([lat, long]).addTo(map);
                // circle = L.circle([lat, long], { radius: accuracy }).addTo(map);

                // Add a popup to the marker
                marker.bindPopup("Your coordinate is Lat: " + lat + "<br>Long: " + long + "<br>Distance: " + distance + " meters").openPopup();
            } else {
                // Update the positions of the existing marker and circle
                marker.setLatLng([lat, long]);
                // circle.setLatLng([lat, long]);
                // circle.setRadius(accuracy);

                // Calculate distance from previous position
                if (previousPosition) {
                    distance = calculateDistance(previousPosition, [lat, long]);
                    console.log("Distance from previous position: " + distance.toFixed(2) + " meters");

                    // Update the popup content with distance
                    $.post('php/Php_repo.php', {
                        latloc: lat,
                        longloc: long,
                        distance: distance.toFixed(2)
                    }).done(function(response) {
                        console.log(response); // Log the response from the server
                    }).fail(function(error) {
                        console.error("Error sending data to the server:", error);
                    });
                    marker.getPopup().setContent("Your coordinate is Lat: " + lat + "<br>Long: " + long + "<br>Distance: " + distance.toFixed(2) + " meters").update();
                }

                // Center the map on the updated position
                map.panTo([lat, long]);
            }

            // map.on('locationfound', function(evt) {
            //     savedLatLng = evt.latlng;
            //     $.post('php/Php_repo.php', {
            //         latloc: lat,
            //         longloc: long,
            //         distance: distance.toFixed(2)
            //     }).done(function(response) {
            //         console.log(response); // Log the response from the server
            //     }).fail(function(error) {
            //         console.error("Error sending data to the server:", error);
            //     });
            //     console.log(savedLatLng);

            // });
            console.log("Your coordinate is Lat: " + lat + " Long: " + long);

            // Store the current position as the previous position for the next iteration
            previousPosition = [lat, long];


            // $.ajax({
            //     type: "POST",
            //     dataType: "dataType",
            //     url: "php/Php_repo.php",
            //     data: {
            //         action: 'getCoordinateData',
            //         lat: lat,
            //         long: long,
            //         distance: distance
            //     },
            //     success: function(data) {

            //     }
            // });
        }

        map.locate({
            setView: true,
            maxZoom: 18
        });

        function calculateDistance(point1, point2) {
            const earthRadius = 6371; // Earth radius in kilometers

            // Convert latitude and longitude from degrees to radians
            const lat1Rad = toRadians(point1[0]);
            const lon1Rad = toRadians(point1[1]);
            const lat2Rad = toRadians(point2[0]);
            const lon2Rad = toRadians(point2[1]);

            // Calculate the change in coordinates
            const dLat = lat2Rad - lat1Rad;
            const dLon = lon2Rad - lon1Rad;

            // Haversine formula for distance calculation
            const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1Rad) * Math.cos(lat2Rad) * Math.sin(dLon / 2) ** 2;
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            // Distance in kilometers
            const distance = earthRadius * c;

            // Convert distance to meters
            return distance * 1000;
        }

        function put(evt) {
            if (check == 1) {


                var e = document.getElementById("ddlViewBy");
                markerType = e.value;
                if (parseInt(markerType) === 1) {
                    var markT = waterIcon;
                }
                if (parseInt(markerType) === 2) {
                    var markT = roadIcon;
                }
                if (parseInt(markerType) === 3) {
                    var markT = disasterIcon;
                }
                var coord = [evt.latlng.lat, evt.latlng.lng];
                markerTemp.setLatLng(coord);
                markerTemp.setIcon(markT);
                markerTemp.addTo(map);
                savedMarkers.push(coord);

            } else {
                var popup = L.popup();

                popup
                    .setLatLng(evt.latlng)
                    .setContent("You clicked the map at " + evt.latlng.toString())
                    .openOn(map);

            }
            check = 0;
        };

 

        map.on('click', put);

        function toRadians(degrees) {
            return degrees * (Math.PI / 180);
        }

        function handleError(error) {
            console.error('Error getting location:', error.message);
        }
    </script>
</body>

</html>