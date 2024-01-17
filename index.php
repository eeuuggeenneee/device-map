<!DOCTYPE html>
<html lang="en">
<?php
include("./includes/db.php");
include("./includes/auth_session.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forklift Position Tracking</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="assets/css/util.css" />
   
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/select2.css" />
    <link rel="stylesheet" href="assets/css/select2.min.css" />
    <link rel="stylesheet" href="assets/css/datatables.min.css" /> -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <!-- <link rel="stylesheet" href="assets/css/theme.min.css" /> -->

    <style>
        #map {
            height: 400px;
            /* height: calc(20vh - 20px); */
        }

        /* Apply styles only when the screen width is less than or equal to 767px (typical mobile devices) */
        @media (max-width: 767px) {
            #activityList {
                height: auto;
                /* Reset the height */
                max-height: 350px;
                /* Set a maximum height for the dropdown list */
                overflow-y: auto;
                /* Enable vertical scrolling if needed */
            }
        }

        .todo-nav {
            margin-top: 10px
        }

        .todo-list {
            margin: 10px 0;
            max-height: 500px;
            /* Set your desired max height */
            overflow-y: auto;
        }

        /* Hide scrollbar in WebKit browsers */
        .todo-list::-webkit-scrollbar {
            width: 0;
        }

        .todo-list::-webkit-scrollbar-thumb {
            background-color: #888;
        }

        .todo-list .todo-item {
            padding: 15px;
            margin: 5px 0;
            border-radius: 0;
            background: #f7f7f7
        }

        .todo-list.only-active .todo-item.complete {
            display: none
        }

        .todo-list.only-active .todo-item:not(.complete) {
            display: block
        }

        .todo-list.only-complete .todo-item:not(.complete) {
            display: none
        }

        .todo-list.only-complete .todo-item.complete {
            display: block
        }

        .todo-list .todo-item.complete span {
            text-decoration: line-through
        }

        .remove-todo-item {
            color: #ccc;
            visibility: hidden
        }

        .remove-todo-item:hover {
            color: #5f5f5f
        }

        .todo-item:hover .remove-todo-item {
            visibility: visible
        }

        div.checker {
            width: 18px;
            height: 18px
        }

        div.checker input,
        div.checker span {
            width: 18px;
            height: 18px
        }

        div.checker span {
            display: -moz-inline-box;
            display: inline-block;
            zoom: 1;
            text-align: center;
            background-position: 0 -260px;
        }

        div.checker,
        div.checker input,
        div.checker span {
            width: 19px;
            height: 19px;
        }

        div.checker,
        div.radio,
        div.uploader {
            position: relative;
        }

        div.button,
        div.button *,
        div.checker,
        div.checker *,
        div.radio,
        div.radio *,
        div.selector,
        div.selector *,
        div.uploader,
        div.uploader * {
            margin: 0;
            padding: 0;
        }

        div.button,
        div.checker,
        div.radio,
        div.selector,
        div.uploader {
            display: -moz-inline-box;
            display: inline-block;
            zoom: 1;
            vertical-align: middle;
        }

        .card {

            padding: 25px;
            margin-bottom: 20px;
            border: initial;
            background: #fff;
            border-radius: calc(.15rem - 5px);
            box-shadow: 0 1px 15px rgba(0, 0, 0, 0.04), 0 1px 6px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>




<body>
    <div id="map" style="display: none;"></div>

    <p id="info" style="display: none;">Distance: 0 meters</p>


    <div class="container mt-4">
        <h2 class="mb-3">Forklift Position Monitoring </h2>
        <div class="row">
            <div class="col-md-12">
                <div class="card rounded-3 shadow mb-5 bg-body">
                    <h3>List of Activity</h3>
                    <div class="card-body">
                        <!-- <form action="javascript:void(0);">
                            <input type="text" class="form-control add-task" placeholder="New Task...">
                        </form> -->
                        <!-- <ul class="nav nav-pills todo-nav">
                            <li role="presentation" class="nav-item all-task active"><a href="#" class="nav-link">All</a></li>
                            <li role="presentation" class="nav-item active-task"><a href="#" class="nav-link">Active</a></li>
                            <li role="presentation" class="nav-item completed-task"><a href="#" class="nav-link">Completed</a></li>
                        </ul> -->
                        <div class="todo-list">
                            <?php
                            $sql = "SELECT * FROM activity WHERE fl_type = '" . $_SESSION['fl_type'] . "'";
                            $stmt = sqlsrv_query($conn, $sql);

                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                if ($row['id'] == 1) {
                                    continue;
                                }
                                echo '<label class="todo-item col-12">
                   <div class="checker">
                       <span class=""><input type="checkbox" value="' . $row['id'] . '" class="todo-checkbox"></span>
                   </div>
                   <span>' . $row['name'] . '</span>
                   <a href="javascript:void(0);" class="float-right remove-todo-item"><i class="icon-close"></i></a>
               </label><br>';
                            }
                            ?>
                        </div>
                    </div>



                    <div class="mb-3" id="loadingUnloadingButtons" style="display: none;">
                        <label for="loadingUnloadingButtons">
                            <h3>Label</h3>
                        </label>
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-primary col-12 btn-block" id="loadingBtn">Loading</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-danger col-12 btn-block" id="unloadingBtn">Unloading</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="startEndButtons" style="display: none;">
                        <label for="">

                        </label>
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-success col-12 btn-block" id="startBtn">Start</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-warning col-12 btn-block" disabled id="endBtn">End</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card rounded-3 shadow mb-5 bg-body">
            <h3>Completed Activity</h3>

            <div class="card-body ">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Activity Name</th>
                            <th scope="col">Event</th>
                            <th scope="col">Timestamp</th>

                        </tr>
                    </thead>
                    <tbody>
            </div>
            <?php
            $query = "SELECT
                    a.id,
                    a.user_id,
                    a.activity_id,
                    b.name,
                    a.what,
                    a.event,
                    a.timestamp
                    FROM
                    user_activity a
                    JOIN activity b
                    ON a.activity_id = b.id where a.user_id = ?";
            $params = array($_SESSION['user_id']);
            $stmt = sqlsrv_query($conn, $query, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $formattedTimestamp = $row['timestamp']->format('Y-m-d H:i:s');

                echo '<tr>
                                <th scope="row">' . $row['id'] . '</th>
                                <td>' . $row['name'] . '</td>
                                <td>' . $row['event'] . '</td>
                                <td>' . $formattedTimestamp . '</td>
                              </tr>';
            }


            ?>

            </tbody>
            </table>
        </div>

    </div>
    <a style="float: right;" href="./php/logout.php">Logout</a>

    </div>


    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/@turf/turf"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>

    <script>
        var tempselectedValue = 1;
        var selectedValue = 1;
        var whatf = "";
        var checkboxes = document.querySelectorAll('.todo-checkbox');
        var label = document.getElementById('label');


        var map = L.map('map');
        var check = 0;
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker, circle;
        var previousPosition;

        var lc = L.control.locate({
            position: 'topleft',
            strings: {
                title: "Geoloc"

            },

        }).addTo(map);

        var current_position, current_accuracy;



        function onLocationFound(e) {
            // if (current_position) {
            //     map.removeLayer(current_position);
            //     map.removeLayer(current_accuracy);
            // }

            // current_position = L.marker(e.latlng).addTo(map)
            //     .bindPopup("You are within " + radius + " meters from this point").openPopup();

            // current_accuracy = L.circle(e.latlng, radius).addTo(map);
            var radius = e.accuracy / 2;
            var heading = e.heading;

            var mlat = e.latlng.lat;
            var mlong = e.latlng.lng;
            $.post('php/Php_repo.php', {
                userd: <?php echo $_SESSION['user_id']; ?>,
                latloc: mlat,
                longloc: mlong,
                headingloc: heading,
                radiusloc: radius,
                activityl: selectedValue,
                timestamp: e.timestamp,
                fltype: <?php echo "'" . strval($_SESSION['fl_type']) . "'"; ?>,
            }).done(function(response) {
                console.log(response); // Log the response from the server
            }).fail(function(error) {
                console.error("Error sending data to the server:", error);
            });
        }

        function onLocationError(e) {
            alert(e.message);
        }
        //lc.start();
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Uncheck all checkboxes
                checkboxes.forEach(function(otherCheckbox) {
                    if (otherCheckbox !== checkbox) {
                        otherCheckbox.checked = false;
                        otherCheckbox.closest('.todo-item').style.backgroundColor = ''; // Reset background color
                    }
                });
                tempselectedValue = checkbox.value;
                console.log("Checkbox checked. Value: " + tempselectedValue);
                // Change the background color of the checked checkbox
                if (checkbox.checked) {
                    checkbox.closest('.todo-item').style.backgroundColor = 'lightblue'; // Change to your desired background color
                } else {
                    checkbox.closest('.todo-item').style.backgroundColor = ''; // Reset background color if unchecked
                }

                // You can do further processing with the checkbox value here
            });
        });


        document.getElementById("startBtn").addEventListener("click", function() {
            selectedValue = tempselectedValue;
            $.post('php/add_activity.php', {
                user: <?php echo $_SESSION['user_id'] ?>,
                activity: selectedValue,
                event: "Start"
            }).done(function(response) {
                console.log(response); // Log the response from the server
            }).fail(function(error) {
                console.error("Error sending data to the server:", error);
            });
        });
        document.getElementById("endBtn").addEventListener("click", function() {
            selectedValue = tempselectedValue;
            $.post('php/add_activity.php', {
                user: <?php echo $_SESSION['user_id'] ?>,
                activity: selectedValue,
                event: "End"
            }).done(function(response) {
                console.log(response); // Log the response from the server
            }).fail(function(error) {
                console.error("Error sending data to the server:", error);
            });
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });

            whatf = "";
            selectedValue = 1;
        });


        map.on('locationfound', onLocationFound);

        function locate() {
            map.locate({
                setView: true,
                maxZoom: 18,
                watch: true,
                enableHighAccuracy: true
            });
        }

        setInterval(function() {
            map.locate({
                setView: true,
                maxZoom: 18,
                watch: true,
                enableHighAccuracy: true
            });
        }, 100);

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

    <script src="js/js_repo.js"></script>
    <script src="js/todo.js"></script>

</body>

</html>