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

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-duotone-thin.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-duotone-solid.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-duotone-regular.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-duotone-light.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-thin.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-solid.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-regular.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-light.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/duotone-thin.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/duotone-regular.css">

    <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/duotone-light.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <!-- <link rel="stylesheet" href="assets/css/theme.min.css" /> -->
    <link rel="stylesheet" href="css/bootstrap1.min.css" />
    <link rel="stylesheet" href="css/metisMenu.css">
    <link rel="stylesheet" href="css/style1.css" />
    <link rel="stylesheet" href="css/colors/default.css" id="colorSkinCSS">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            padding: 0.5rem;
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
            position: relative;
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
            position: relative;
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

        .task_selected {
            background-color: lightblue;
        }

        .task_ongoing {
            background-color: #f49025;
        }

        .task_skipped {
            background-color: #62a0fc;
        }

        .task_completed {
            background-color: #43bf57;
        }

        a.logout-link {
            color: inherit;
            /* Remove color */
            text-decoration: none;
            /* Optional: Removes underline */
        }

        .task-btns {
            position: absolute;
            left: 50%;
            top: 10%;
            transform: translateX(-50%);
            /* Center buttons horizontally */
            display: flex;
            gap: 10px;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(244, 144, 37, 255);
            opacity: 0.5;
            z-index: 0;
        }

        /* Style for the nav-pills to give a clean toggle effect */
        .nav-pills {
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .nav-pills .nav-item {
            margin: 0 10px;
        }

        .nav-pills .nav-link {
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 10px 20px;
            border-radius: 30px;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Active state for the tabs */
        .nav-pills .nav-link.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        /* Hover state for the tabs */
        .nav-pills .nav-link:hover {
            background-color: #0056b3;
            color: white;
        }

        /* Optional: Add a subtle shadow to make it stand out */
        .nav-pills .nav-link {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Add a border between tabs to make them look more like a toggle switch */
        .nav-pills .nav-item:not(:last-child) .nav-link {
            border-right: 2px solid #ccc;
        }
    </style>

</head>

<body>
    <div id="map" style="display: none;"></div>
    <p id="info" style="display: none;">Distance: 0 meters</p>
    <div class="py-2 px-4 bg-primary d-flex justify-content-between align-items-center">
        <h2 class="text-white mt-2">Forklift Activity Monitoring</h2>

        <a href="php/logout.php" class="logout-link text-middle">
            <svg width="30" height="30" aria-hidden="true" focusable="false" data-prefix="far" data-icon="right-to-bracket" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="initial-icon svg-inline--fa fa-right-to-bracket fa-xl">
                <path fill="currentColor" d="M192 365.8L302 256 192 146.2l0 53.8c0 13.3-10.7 24-24 24L48 224l0 64 120 0c13.3 0 24 10.7 24 24l0 53.8zM352 256c0 11.5-4.6 22.5-12.7 30.6L223.2 402.4c-8.7 8.7-20.5 13.6-32.8 13.6c-25.6 0-46.4-20.8-46.4-46.4l0-33.6-96 0c-26.5 0-48-21.5-48-48l0-64c0-26.5 21.5-48 48-48l96 0 0-33.6c0-25.6 20.8-46.4 46.4-46.4c12.3 0 24.1 4.9 32.8 13.6L339.3 225.4c8.1 8.1 12.7 19.1 12.7 30.6zm-8 176l80 0c22.1 0 40-17.9 40-40l0-272c0-22.1-17.9-40-40-40l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l80 0c48.6 0 88 39.4 88 88l0 272c0 48.6-39.4 88-88 88l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z" class=""></path>
            </svg>
        </a>
    </div>
    <div class="px-3 py-3">
        <div class="row">
            <div class="col-7">
                <!-- <div class="task">
                        <div class="task-details">
                            <strong>Pick up Skid:</strong> Align forks and lift the skid.
                        </div>
                        <button>START</button>
                        <button class="skip">SKIP</button>
                    </div> -->
                <div class="progress-section">
                    <div class="card-body px-1 py-2">
                        <h5 class="fw-bold">Select Workflow</h5>
                        <div class="d-flex justify-content-center">
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <h3 class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Loading Tasks</button>
                                </h3>
                                <h3 class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Unloading Tasks</button>
                                </h3>
                            </ul>
                        </div>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="px-3 py-3" id="loading_container">

                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="px-3 py-3" id="unloading_container">

                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="progress-section py-4 px-3">
                    <div class="d-flex">
                        <h3 class="fw-semibold">Current Activity</h3>
                    </div>
                    <h5 class="ms-auto"><span class="fw-bold" id="c_activity">No activity selected</span></h5>

                    <h5 class="ms-auto">Activity Elapsed Time: <br><span class="fw-bold" id="atime_lapse"></span></h5>
                    <h5 class="ms-auto">Total Elapsed Time: <br><span class="fw-bold" id="time_lapse"></span></h5>
                    <h5 class="text-center fw-bold border-top" id="skid_count"></h5>

                    <div class="progress-bar-container">
                        <div class="progress-bar">
                            <div class="progress" id="progress_bar"></div>
                        </div>
                    </div>
                    <div class="row" id="start_run">
                        <div class="col-12">
                            <button class="btn btn-success col-12 btn-block text-white" id="startBtn">Start Run</button>
                        </div>
                    </div>
                    <div class="row d-none" id="buttonActivity">
                        <div class="col-6">
                            <button class="btn btn-info col-12 btn-block text-white" id="pauseBtn">Pause Run</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-danger col-12 btn-block" id="endBtn">End Run</button>
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
                <!-- <div class="progress-section mt-2">
                    <h4>Timeline</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Run type</th>
                                <th scope="col">Run start</th>
                                <th scope="col">Run end</th>
                                <th scope="col">Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Loading</th>
                                <td>2025-01-30 00:04:00</td>
                                <td>2025-01-30 01:04:00 </td>
                                <td>1 Hour</td>
                            </tr>
                            <tr>
                                <th scope="row">Loading</th>
                                <td>2025-01-30 00:04:00</td>
                                <td>2025-01-30 01:04:00 </td>
                                <td>1 Hour</td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->
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
        var buttonActivity = document.getElementById('buttonActivity');
        var start_run = document.getElementById('start_run')
        var time_lapse = document.getElementById('time_lapse');
        var atime_lapse = document.getElementById('atime_lapse');
        var progress_bar = document.getElementById('progress_bar')
        var skid_count_element = document.getElementById('skid_count');
        var activity_sequence = "";
        var run_id = null;
        var current_activity = document.getElementById('c_activity');
        var move_type = null;
        var activity_id = null;
        var run_start_time = null;
        var mount = false;
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
        let skid_count = 0;
      
        function fetch_skid() {
            var user_id = <?php echo $_SESSION['user_id'] ?>;
            let text = '';
            $.ajax({
                url: 'php/fetch_user.php?user_id=' + user_id + '&run_id=' + run_id, // Replace with the actual server-side script to fetch data
                type: 'GET',
                success: function(data) {
                    skid_count = 0;
                    let parsedData = JSON.parse(data); // Parse the JSON string back into an object/array
                    parsedData.forEach(element => {
                        if(move_type == "Loading"){
                            text = 'Loaded';
                            if (element.activity_id == 6) {
                                skid_count++;  
                            }
                        }else{
                            text = 'Unloaded';
                            if (element.activity_id == 15) {
                                skid_count++;  
                            }
                        }
                    });
                    skid_count_element.innerHTML = skid_count + ' Skid ' + text;
                },
                error: function() {}
            });
        }
        fetch_skid();
        function task_selected(value, activity_sequence, type, btn_click) {
            activity_sequence = activity_sequence;
            if (activity_sequence == 1) {
                start_run.classList.remove('d-none');
            } else {
                buttonActivity.classList.remove('d-none');
            }
            $.post('php/add_activity.php', {
                user: <?php echo $_SESSION['user_id'] ?>,
                activity: value,
                activity_sequence: activity_sequence,
                run_id: run_id ?? '0',
                remarks: btn_click,
            }).done(function(response) {
                console.log(response);
            }).fail(function(error) {
                console.error("Error sending data to the server:", error);
            });

            if (type == 'loading') {
                progress_bar.style.width = (activity_sequence / 5) * 100 + '%';
            } else {
                progress_bar.style.width = (activity_sequence / 9) * 100 + '%';
            }



            Swal.fire({
                title: 'Success!',
                text: 'Moving to the next step...',
                icon: 'success',
                showConfirmButton: false, // No need for a confirm button
                timer: 1500 // 1.5 seconds
            }).then(() => {
                fetchActivities(activity_sequence, type);
                fetch_skid();
            });

            selectedValue = value;
            console.log('Selected Value is ' + value);
            const allTasks = document.querySelectorAll('.task');
            allTasks.forEach(task => {
                task.classList.remove('task_selected');
            });
            // document.getElementById('task_' + type + '_' + value).classList.add('task_selected')

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

        function resetTodoItemStyles(checkbox) {
            let todoItem = checkbox.closest('.todo-item');
            let cardSection = todoItem.querySelector('.card-section');
            todoItem.style.backgroundColor = ''; // Reset background color if unchecked
            cardSection.style.backgroundColor = '';
        }
        let alertShown = false;

        function getLapseTime(run_start_time) {
            // Convert `run_start_time` to a Date object if it's not already
            if (typeof run_start_time === "string") {
                run_start_time = new Date(run_start_time);
            }

            // Get current time in New York
            const now = new Date().toLocaleString("en-US", {
                timeZone: "America/New_York"
            });
            const newYorkTime = new Date(now); // Convert to Date object

            // Calculate time difference in milliseconds
            const diffMs = newYorkTime - run_start_time;

            // Convert milliseconds to a human-readable format
            const diffSeconds = Math.floor(diffMs / 1000) % 60;
            const diffMinutes = Math.floor(diffMs / (1000 * 60)) % 60;
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60)) % 24;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

            // Format the difference
            let humanDiff = "";
            if (diffDays > 0) humanDiff += diffDays + " days ";
            if (diffHours > 0) humanDiff += diffHours + " hours ";
            if (diffMinutes > 0) humanDiff += diffMinutes + " minutes ";
            if (diffSeconds > 0) humanDiff += diffSeconds + " seconds";
            return humanDiff.trim();
        }

        function fetchUserActivity() {
            $.ajax({
                url: 'php/fetch_user_activity.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        run_id = 0
                    } else {
                        current_activity.innerHTML = data.activity_name + ': ' + data.description + '';
                        start_run.classList.add('d-none')
                        buttonActivity.classList.remove('d-none');
                        if (data.activity_sequence == 0) {
                            const allTasks = document.querySelectorAll('.task');
                            allTasks.forEach(task => {
                                task.classList.remove('task_selected');
                            });
                        }
                        run_id = data.run_id;
                        move_type = data.move_type;
                        activity_sequence = data.activity_sequence;
                        activity_id = data.id;
                        run_start_time = data.run_start_time['date'];
                        time_lapse.innerHTML = getLapseTime(run_start_time);
                        atime_lapse.innerHTML = getLapseTime(data.start_time['date']);

                        if (data.pause_id && !alertShown) {
                            Swal.fire({
                                title: 'Pause Detected!',
                                text: 'This alert cannot be closed until it is resumed.',
                                icon: 'warning',
                                showConfirmButton: true,
                                confirmButtonText: 'Resume',
                                allowOutsideClick: false, // Disable closing by clicking outside
                                allowEscapeKey: false, // Disable closing by pressing the escape key
                            }).then((result) => {
                                alertShown = true;

                                if (result.isConfirmed) { // Only trigger if the user clicks "Resume"
                                    resume_activity();
                                }
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        function resume_activity() {
            alertShown = false;

            $.ajax({
                url: 'php/resume_activity.php', // Your PHP script
                type: 'POST',
                dataType: 'json',
                data: {
                    user_id: <?php echo $_SESSION['user_id']; ?>,
                },
                success: function(response) {
                    // Handle the response (you can show a success message or handle other logic)
                    if (response.message) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });
                    } else if (response.error) {
                        Swal.fire({
                            title: 'Error!',
                            text: response.error,
                            icon: 'error',
                            confirmButtonText: 'Okay'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    console.error("Error:", error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while updating the pause history.',
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    });
                }
            });
        }

        document.getElementById("startBtn").addEventListener("click", function() {
            var taskBtns = document.querySelectorAll(".task_btns");
            taskBtns.forEach(function(taskBtn) {
                taskBtn.classList.remove("d-none");
            });

            mount = true;
            $.post('php/add_activity.php', {
                user: <?php echo $_SESSION['user_id'] ?>,
                activity: 1,
                activity_sequence: 0,
                run_id: 0,
                remarks: 'Start',
            }).done(function(response) {
                console.log(response); // Log the response from the server

            }).fail(function(error) {
                console.error("Error sending data to the server:", error);
            });

        });
        document.getElementById("pauseBtn").addEventListener("click", function() {
            var data = {
                run_id: run_id,
                user_id: <?php echo $_SESSION['user_id'] ?>,
                activity_id: activity_id,
            };
            $.ajax({
                url: 'php/pause_activity.php', // Adjust the URL to your PHP file
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    // Handle the success response
                    console.log("Response:", response);
                    if (response.message) {
                        alert(response.message); // Show success message
                    } else if (response.error) {
                        alert("Error: " + response.error); // Show error message
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
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
            }).fail(function(error) {});
        });

        function fetchActivities(activity_sequence, type) {
            $.ajax({
                url: 'php/fetch_activity.php?activity_sequence=' + activity_sequence + '&move_type=' + type, // Path to your PHP file
                type: 'GET',
                dataType: 'json', // Expecting a JSON response
                success: function(data) {
                    $('#' + type + '_container').html('');

                    if (data.length > 0) {

                        data.forEach(function(activity, index) {
                            let btns = ``;
                            let overlay = '';


                            if (activity_sequence == 0) {
                                if (activity.activity_sequence == 1) {
                                    btns = `<button id="btnstart_${activity.move_type.toLowerCase()}_${activity.id}_${activity.activity_sequence}" onclick="task_selected(${activity.id}, ${activity.activity_sequence}, '${activity.move_type.toLowerCase()}','Completed')">START</button>
                                    <button id="btnstart_${activity.move_type.toLowerCase()}_${activity.id}_${activity.activity_sequence}" class="skip">Skip</button>`;
                                }
                            } else if (activity.activity_sequence == (parseInt(activity_sequence)) && activity.move_type == move_type) {
                                console.log(data[index + 1]);
                                overlay = `<div class="overlay"></div>`;
                                btns = `<div class="task-btns d-flex">
                                    <div class="blur"></div>
                                    <button class="btn-success task-btn" onclick="task_selected(${data[index + 1]['id']}, ${data[index + 1]['activity_sequence']},'${activity.move_type.toLowerCase()}','Completed')">Complete Task</button>
                                    <button id="" onclick="task_selected(${data[index + 1]['id']}, ${data[index + 1]['activity_sequence']},'${activity.move_type.toLowerCase()}','Skipped')" class="skip">Skip</button>
                                </div>`;
                            }
                            let show = '';
                            if (activity_sequence == 0) {
                                show = 'd-none'
                            }
                            let activityHtml = `
                                <div class="task py-3 px-3" id="task_${activity.move_type.toLowerCase()}_${activity.id}">
                                    ${overlay}
                                    <div class="task-details">
                                        <h6><strong>${activity.name}:</strong> ${activity.description}</h6>
                                    </div>
                                    <div class="task_btns ${show}">
                                        ${btns}
                                    </div>
                                </div>
                            `;
                            $('#' + type + '_container').append(activityHtml); // Append to the container
                        });
                    } else {
                        console.log('No activities found');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching activities:', error);
                }
            });
        }


        // updateTable();
        map.on('locationfound', onLocationFound);

        function locate() {
            map.locate({
                setView: true,
                maxZoom: 18,
                watch: true,
                enableHighAccuracy: true
            });
        }
        fetchActivities(0, 'loading');
        fetchActivities(0, 'unloading');
        setInterval(function() {
            map.locate({
                enableHighAccuracy: true,
                timeout: 100,
                maximumAge: 150
            });
            fetchUserActivity();
            console.log('Run id ' + run_id);
            console.log('Move Type ' + move_type);
            console.log('Activity Sequence ' + activity_sequence);

            if (!mount && activity_sequence) {
                fetchActivities(activity_sequence, 'loading');
                fetchActivities(activity_sequence, 'unloading');
                mount = true;
            }
        }, 1000);

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