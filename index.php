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

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <!-- <link rel="stylesheet" href="assets/css/util.css" />
   
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/select2.css" />
    <link rel="stylesheet" href="assets/css/select2.min.css" />
    <link rel="stylesheet" href="assets/css/datatables.min.css" /> -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <!-- <link rel="stylesheet" href="assets/css/theme.min.css" /> -->
    <link rel="stylesheet" href="css/bootstrap1.min.css" />
    <link rel="stylesheet" href="css/metisMenu.css">
    <link rel="stylesheet" href="css/style1.css" />
    <link rel="stylesheet" href="css/colors/default.css" id="colorSkinCSS">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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


        .todo-list {
            -webkit-user-select: none;
            /* Safari */
            -ms-user-select: none;
            /* IE 10 and IE 11 */
            user-select: none;
            /* Standard syntax */
            max-height: 500px;
            /* Set your desired max height */
            overflow-y: auto;
            display: flex;
            flex-wrap: wrap;
        }

        /* Hide scrollbar in WebKit browsers */
        .todo-list::-webkit-scrollbar {
            width: 10;
        }

        .todo-item {
            flex: 1 1 48%;
            /* Equal-width columns with a little margin between them */
            margin: 1%;
            /* Adjust the margin as needed */
        }

        .todo-list::-webkit-scrollbar-thumb {
            background-color: #888;
        }

        .todo-list .todo-item {
            padding: 5px;
            margin: 5px 0;
            border-radius: 0;
            background: white;
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


        .card-select {
            text-align: center;
            transition: all 0.5s ease;
        }

        .card-select .card-divider {
            background-color: #fefefe;
            letter-spacing: 1px;
            font-weight: 500;
            text-transform: uppercase;
            border: 1px solid #cacaca;
        }

        .card-select .button {
            padding: 1rem;
            background-color: #cacaca;
        }

        .card-select .button:after {
            content: 'Select';
        }

        .card-select .button:hover {
            background-color: #1779ba;
        }

        .card-select .button:focus {
            background-color: #1779ba;
        }

        .card-select.is-selected {
            border: 1px solid #1779ba;
            box-shadow: 0 0 10px #e6e6e6;
            transition: all 0.5s ease;
        }

        .card-select.is-selected .button {
            background-color: #1779ba;
        }

        .card-select.is-selected .button:after {
            content: 'Selected';
        }

        .card-section {
            height: 110px;
            /* Set a fixed height */
            text-size-adjust: 10px;
        }

        .card.disabled {
            opacity: 0.5;

        }

        .table-container {
            overflow-x: auto;
        }
    </style>

</head>

<body>
    <div id="map" style="display: none;"></div>
    <p id="info" style="display: none;">Distance: 0 meters</p>
    <div class="container mt-4">
        <h2 class="mb-3">Forklift Position Monitoring </h2>
        <div class="row">
            <div class="col-md-12" id="completedActivityCard">
                <div class="card rounded-3 shadow mb-5 bg-body">

                    <div class="card-header">
                        <h3 class="py-2 px-2">List of Activity</h3>
                    </div>
                    <div class="card-body">
                        <!-- <form action="javascript:void(0);">
                            <input type="text" class="form-control add-task" placeholder="New Task...">
                        </form> -->
                        <!-- <ul class="nav nav-pills todo-nav">
                            <li role="presentation" class="nav-item all-task active"><a href="#" class="nav-link">All</a></li>
                            <li role="presentation" class="nav-item active-task"><a href="#" class="nav-link">Active</a></li>
                            <li role="presentation" class="nav-item completed-task"><a href="#" class="nav-link">Completed</a></li>
                        </ul> -->
                        <div class="">
                            <div class="todo-list gap-3">
                                <?php
                                $check = "SELECT * FROM user_activity WHERE end_time IS NULL AND user_id = '" . $_SESSION['user_id'] . "'";
                                $checkif = sqlsrv_query($conn, $check);

                                $sql = "SELECT * FROM activity WHERE fl_type = 'Others' OR fl_type = '" . $_SESSION['fl_type'] . "' ";
                                $stmt = sqlsrv_query($conn, $sql);
                                $yesnull = false;
                                if (sqlsrv_has_rows($checkif)) {
                                    $result = sqlsrv_fetch_array($checkif, SQLSRV_FETCH_ASSOC);
                                    $activeActivityId = $result['activity_id'];
                                    $yesnull = true;
                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                        if ($row['id'] == 1) {
                                            continue;
                                        }

                                        $isDisabled = $row['id'] != $activeActivityId;
                                        echo '<label class="card todo-item col-6 ' . ($isDisabled ? 'disabled' : '') . '" data-cardSelect style="' . ($isDisabled ? '' : 'background-color: lightblue;') . '">
                                                <div class="card-section px-1 py-1 total_blance mt_20 mb_10">
                                                    <span class="f_s_13 f_w_700 color_gray ">' . $row['name'] . '</span>
                                                    <div class="total_blance_inner d-flex align-items-center flex-wrap justify-content-between">
                                                        <div>
                                                            <span class="f_s_20 f_w_700  d-block">' . $row['description'] . '</span>
                                                        </div>
                                                        <div class="checker">
                                                            <div class="card-section px-3 py-3" style="' . ($isDisabled ? '' : 'background-color: lightblue;') . ' min-height: 70px; box-sizing: border-box;" data-id="' . $row['id'] . '">
                                                                <p class="text-black">' . $row['name'] . '</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>';
                                    }
                                } else {
                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                        if ($row['id'] == 1) {
                                            continue;
                                        }
                                        echo '<label class="card todo-item col-6" data-cardSelect>
                                                        <div class=" card-section px-1 py-1 total_blance mt_20 mb_10" data-id="' . $row['id'] . '" data-id="' . $row['id'] . '">
                                                            <span class="f_s_13 f_w_700 color_gray ">' . $row['name'] . '</span>
                                                            <div class="total_blance_inner d-flex align-items-center flex-wrap justify-content-between">
                                                                <div>
                                                                    <span class="f_s_20 f_w_700  d-block">' . $row['description'] . '</span>
                                                                </div>
                                                                <div class="checker">
                                                                    <span class="">
                                                                        <input type="checkbox" style="display: none;" value="' . $row['id'] . '" class="todo-checkbox">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>';
                                    }
                                }
                                ?>


                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="startEndButtons" class="ms-3" style="display: <?php echo isset($yesnull) && $yesnull ? 'block' : 'none'; ?>;   padding-right: 20px; padding-left: 20px;">
                        <label for="">

                        </label>
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-success col-12 btn-block" <?php echo isset($yesnull) && $yesnull ? 'disabled' : ''; ?> id="startBtn">Start</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-warning col-12 btn-block" <?php echo isset($yesnull) && $yesnull ? '' : 'disabled'; ?> id="endBtn">End</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card rounded-3 shadow mb-5 bg-body" id="completedActivityCard">
            <div class="card-header">
                <h3 class="py-2 px-2">Completed Activity</h3>
            </div>
            <div class="card-body ">
                <table class="table table-striped" id="completedActivityCard">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Activity Name</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Duration</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

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
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        var tempselectedValue = 1;
        var selectedValue = 1;
        var whatf = "";
        var checkboxes = document.querySelectorAll('.todo-checkbox');
        var textinside = document.getElementById('textinside');
        var tbody = document.getElementById('tbody');
        $(document).ready(function() {
            $('#completedActivityTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true
            });
        });

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

        function updateTable() {
            $.ajax({
                url: 'php/get_update_data.php', // Replace with the actual server-side script to fetch data
                type: 'GET',
                success: function(data) {
                    tbody.innerHTML = data;
                },
                error: function() {}
            });
        }


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
                headingloc: heading ?? 'N/A',
                radiusloc: radius,
                activityl: selectedValue,
                timestamp: e.timestamp,
                fltype: <?php echo "'" . strval($_SESSION['fl_type']) . "'"; ?>,
            }).done(function(response) {
                console.log(response); // Log the response from the server//
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
                        resetTodoItemStyles(otherCheckbox);
                    }
                });

                tempselectedValue = checkbox.value;
                console.log("Checkbox checked. Value: " + tempselectedValue);

                // Change the background color of the checked checkbox
                let todoItem = checkbox.closest('.todo-item');
                let cardSection = todoItem.querySelector('.card-section');

                if (checkbox.checked) {
                    todoItem.style.backgroundColor = 'lightblue'; // Change to your desired background color
                    cardSection.style.backgroundColor = 'lightblue';
                } else {
                    resetTodoItemStyles(checkbox);
                }
            });
        });

        function resetTodoItemStyles(checkbox) {
            let todoItem = checkbox.closest('.todo-item');
            let cardSection = todoItem.querySelector('.card-section');

            todoItem.style.backgroundColor = ''; // Reset background color if unchecked
            cardSection.style.backgroundColor = '';
        }

        document.getElementById("startBtn").addEventListener("click", function() {
            selectedValue = tempselectedValue;
            $.post('php/add_activity.php', {
                user: <?php echo $_SESSION['user_id'] ?>,
                activity: selectedValue,
            }).done(function(response) {
                console.log(response); // Log the response from the server
                updateTable();
            }).fail(function(error) {
                console.error("Error sending data to the server:", error);
            });
        });

        document.getElementById("endBtn").addEventListener("click", function() {
            selectedValue = tempselectedValue;
            $.post('php/update_activity.php', {
                user: <?php echo $_SESSION['user_id'] ?>,
            }).done(function(response) {
                document.querySelectorAll('.card').forEach(function(cardSelect) {
                    cardSelect.classList.remove('disabled');
                });
                updateTable();
            }).fail(function(error) {
                // console.error("Error sending data to the server:", error);
            });

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                checkbox.disabled = false;
                // Reset background color of the card section
                let cardSection = checkbox.closest('.todo-item').querySelector('.card-section');


                cardSection.style.backgroundColor = '';
                resetTodoItemStyles(checkbox);
            });
            whatf = "";
            selectedValue = 1;
        });

        updateTable();
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
                enableHighAccuracy: true,
                timeout: 100,
                maximumAge: 150
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
            //console.error('Error getting location:', error.message);
        }
    </script>

    <script src="js/js_repo.js"></script>
    <script src="js/todo.js"></script>

</body>

</html>