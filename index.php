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

        .progress-section {
            flex: 2;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .progress-section h2 {
            margin-bottom: 1rem;
            color: #003366;
        }

        .progress-bar-container {
            margin-bottom: 1rem;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background-color: #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar .progress {
            height: 100%;
            background-color: #007acc;
            width: 0;
            transition: width
        }

        p {
            color: black !important;
        }

        .task-section {
            flex: 3;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .task-section h2 {
            margin-bottom: 1rem;
            color: #003366;
        }

        .task {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .task:last-child {
            border-bottom: none;
        }

        .task-details {
            flex: 1;
        }

        .task button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 0.5rem;
            transition: background-color 0.3s;
        }

        .task button.skip {
            background-color: #ffc107;
        }

        .task button:hover {
            opacity: 0.9;
        }

        .timeline-steps {
            display: flex;
            justify-content: center;
            flex-wrap: wrap
        }

        .timeline-steps .timeline-step {
            align-items: center;
            display: flex;
            flex-direction: column;
            position: relative;
            margin: 1rem
        }

        @media (min-width:768px) {

            .timeline-steps .timeline-step:not(:last-child):after {
                content: "";
                display: block;
                border-top: .25rem dotted #3b82f6;
                width: 2rem;
                position: absolute;
                left: 3rem;
                top: .3125rem
            }

            .timeline-steps .timeline-step:not(:first-child):before {
                content: "";
                display: block;
                border-top: .25rem dotted #3b82f6;
                width: 4rem;
                position: absolute;
                right: 3rem;
                top: .3125rem
            }
        }

        .timeline-steps .timeline-content {
            width: 4rem;
            text-align: center
        }

        .timeline-steps .timeline-content .inner-circle {
            border-radius: 1.5rem;
            height: 10px;
            width: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #3b82f6
        }

        .timeline-steps .timeline-content .inner-circle:before {
            content: "";
            background-color: #3b82f6;
            display: inline-block;
            height: 15px;
            width: 15px;
            min-width: 15px;
            border-radius: 6.25rem;
            opacity: .5
        }
    </style>

</head>

<body>
    <div id="map" style="display: none;"></div>
    <p id="info" style="display: none;">Distance: 0 meters</p>
    <div class="px-3 py-3">
        <div class="row">
            <div class="col-6">
                <!-- <div class="task">
                        <div class="task-details">
                            <strong>Pick up Skid:</strong> Align forks and lift the skid.
                        </div>
                        <button>START</button>
                        <button class="skip">SKIP</button>
                    </div> -->
                <div class="progress-section">
                    <div class="card-body px-1 py-2">
                        <div class="d-flex justify-content-center">
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <h2 class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Loading Tasks</button>
                                </h2>
                                <h2 class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Unloading Tasks</button>
                                </h2>
                            </ul>
                        </div>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="px-3 py-3">
                                    <?php
                                    $query = "SELECT * FROM activity WHERE move_type = 'Loading'";
                                    $result = sqlsrv_query($conn, $query);
                                    if ($result === false) {
                                        die(print_r(sqlsrv_errors(), true));
                                    }
                                    // Loop through results and display tasks
                                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                    ?> <div class="task">
                                            <div class="task-details">
                                                <h6><strong><?php echo htmlspecialchars($row['name']); ?>:</strong> <?php echo htmlspecialchars($row['description']); ?></h6>
                                            </div>
                                            <button>START</button>
                                            <button class="skip">SKIP</button>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="px-3 py-3">
                                    <?php
                                    $query = "SELECT * FROM activity WHERE move_type = 'Unloading'";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result === false) {
                                        die(print_r(sqlsrv_errors(), true));
                                    }

                                    // Loop through results and display tasks
                                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                    ?> <div class="task">
                                            <div class="task-details">
                                                <h6><strong><?php echo htmlspecialchars($row['name']); ?>:</strong> <?php echo htmlspecialchars($row['description']); ?></h6>
                                            </div>
                                            <button>START</button>
                                            <button class="skip">SKIP</button>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-6">
                <div class="progress-section">
                    <div class="d-flex">
                        <h2>Current Activity</h2>
                        <h5 class="ms-auto">Elapse Time <span>1:20 seconds</span></h5>
                    </div>


                    <p><strong>Activity:</strong> Unloading Skids</p>
                    <p><strong>Total Duration:</strong> 35 mins</p>
                    <div class="progress-bar-container">
                        <div class="progress-bar">
                            <div class="progress" style="width: 50%;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-warning col-12 btn-block" <?php echo isset($yesnull) && $yesnull ? 'disabled' : ''; ?> id="startBtn">Pause</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-danger col-12 btn-block" <?php echo isset($yesnull) && $yesnull ? '' : 'disabled'; ?> id="endBtn">End</button>
                        </div>
                    </div>
                </div>

                <!-- <table class="table table-striped table-bordered mt-2">
                    <thead>
                        <tr>
                            <th scope="col">Duration</th>
                            <th scope="col">Timeline</th>

                        </tr>
                    </thead>
                    <tbody class="align-middle text-middle ">
                        <tr class="align-middle">
                            <th scope="row" class="align-middle">30 Mins</th>
                            <td>
                                <div class="row">
                                    <div class="col">
                                        <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                            <div class="timeline-step">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2003</p>
                                                </div>
                                            </div>
                                            <div class="timeline-step">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2004">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2004</p>
                                                </div>
                                            </div>
                                            <div class="timeline-step">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2005">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2004</p>
                                                </div>
                                            </div>
                                            <div class="timeline-step">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2010">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2010</p>
                                                </div>
                                            </div>
                                            <div class="timeline-step mb-0">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2020</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th scope="row" class="align-middle">30 Mins</th>
                            <td>
                                <div class="row">
                                    <div class="col">
                                        <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                            <div class="timeline-step">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2003</p>
                                                </div>
                                            </div>
                                            <div class="timeline-step">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2004">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2004</p>
                                                </div>
                                            </div>
                                            <div class="timeline-step">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2005">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2004</p>
                                                </div>
                                            </div>
                                            <div class="timeline-step">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2010">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2010</p>
                                                </div>
                                            </div>
                                            <div class="timeline-step mb-0">
                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                    <div class="inner-circle"></div>
                                                    <p class="h6 mt-3 mb-1">2020</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table> -->
                <div class="progress-section mt-2">
                    <h4>Timeline</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">First</th>
                                <th scope="col">Last</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td colspan="2">Larry the Bird</td>
                                <td>@twitter</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td colspan="2">Larry the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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