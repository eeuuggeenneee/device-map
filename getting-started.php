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
    <!-- Include DataTables CSS and JS -->
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons Extension JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

    <!-- JSZip for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <!-- FileSaver for saving files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>


    <!-- Include jQuery -->
    <!-- DataTables JS -->
    <style>
        .task_selected {
            background-color: lightblue;
        }

        .previous {
            display: none;
        }
    </style>

</head>

<body class="bg-light">
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Getting Started</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Select workflow</h4>
                    <select class="form-select mb-2" id="inputGroupSelect04" aria-label="Example select with button addon">
                        <option selected="">Choose...</option>
                        <option value="loading">Loading</option>
                        <option value="unloading">Unloading</option>
                    </select>

                    <h5 class="mt-2 border-top text-center d-none" id="ins_flow">Select the starting activity</h5>

                    <div id="container_activity" style="max-height: 250px; overflow-y: auto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" style="width: 100px;" onclick="startActivity()" class="btn btn-success">Start</button>
                </div>
            </div>
        </div>
    </div>
    <div class="py-2 px-4 bg-primary d-flex justify-content-between align-items-center">
        <h2 class="text-white mt-2">Forklift Activity Monitoring</h2>
        <div class="d-flex">
            <button type="button" class="btn btn-success me-3" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i class="ti-heart f_s_14 me-2"></i>START</button>

            <a href="php/logout.php" class="logout-link text-middle text-white mt-2">
                <svg width="30" height="30" aria-hidden="true" focusable="false" data-prefix="far" data-icon="right-to-bracket" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="initial-icon svg-inline--fa fa-right-to-bracket fa-xl">
                    <path fill="currentColor" d="M192 365.8L302 256 192 146.2l0 53.8c0 13.3-10.7 24-24 24L48 224l0 64 120 0c13.3 0 24 10.7 24 24l0 53.8zM352 256c0 11.5-4.6 22.5-12.7 30.6L223.2 402.4c-8.7 8.7-20.5 13.6-32.8 13.6c-25.6 0-46.4-20.8-46.4-46.4l0-33.6-96 0c-26.5 0-48-21.5-48-48l0-64c0-26.5 21.5-48 48-48l96 0 0-33.6c0-25.6 20.8-46.4 46.4-46.4c12.3 0 24.1 4.9 32.8 13.6L339.3 225.4c8.1 8.1 12.7 19.1 12.7 30.6zm-8 176l80 0c22.1 0 40-17.9 40-40l0-272c0-22.1-17.9-40-40-40l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l80 0c48.6 0 88 39.4 88 88l0 272c0 48.6-39.4 88-88 88l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z" class=""></path>
                </svg>
            </a>
        </div>
    </div>
    <div class="px-3 py-3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <div class="white_box_tittle ">
                                <h4 class="mb-2">Task History</h4>
                            </div>
                            <p>Filter by Date</p>
                            <div class="d-flex mb-2">
                                <div class="d-flex">
                                    <input type="date" class="form-control me-3" name="inputDate" id="filter-start-date">
                                    <input type="date" class="form-control me-3" name="inputDate" id="filter-end-date">
                                    <button type="button" class="btn btn-secondary" id="download-button">
                                        <i class="fa-solid fa-download"></i>
                                    </button>
                                </div>
                            </div>

                            <div id="DataTables_Table_0_wrapper" class="" style="overflow-y: auto">
                                <table class="table table-hover" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <tr role="row bg-primary">
                                            <th scope="col" class="text-primary" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 250px;" aria-sort="ascending" aria-label="title: activate to sort column descending">Task Title</th>
                                            <th scope="col" class="text-primary" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 50px;" aria-label="Category: activate to sort column ascending">Workflow</th>
                                            <th scope="col" class="text-primary" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 200px;" aria-label="Teacher: activate to sort column ascending">Start Time</th>
                                            <th scope="col" class="text-primary" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 200px;" aria-label="Lesson: activate to sort column ascending">End Time</th>
                                            <th scope="col" class="text-primary" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 200px;" aria-label="Enrolled: activate to sort column ascending">Total Duration</th>
                                            <th scope="col" class="text-primary" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 150px;" aria-label="Price: activate to sort column ascending">Total Pause</th>
                                            <th scope="col" class="text-primary" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 50px;" aria-label="Status: activate to sort column ascending">Forklift</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-activities">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/@turf/turf"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        var current_id = null;
        var move_type = null;
        var selected_sequence = null;

        document.getElementById("inputGroupSelect04").addEventListener("change", function() {
            let selectedValue = this.value;
            move_type = selectedValue;
            fetchActivities(0, selectedValue);
        });

        function startActivity() {
            console.log('selecte id ', current_id)
            console.log('seqceqeq  ', selected_sequence)
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to start to this step?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#198754",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, start!",
            }).then((result) => {

                if (result.isConfirmed) {
                    $.post('php/add_activity.php', {
                        user: <?php echo $_SESSION['user_id'] ?>,
                        activity: current_id,
                        activity_sequence: selected_sequence,
                        run_id: 0,
                        remarks: 'Start',
                    }).done(function(response) {
                        console.log(response);
                    }).fail(function(error) {
                        console.error("Error sending data to the server:", error);
                    });

                    Swal.fire({
                        title: 'Success!',
                        text: 'Moving to the main page',
                        icon: 'success',
                        showConfirmButton: false, // No need for a confirm button
                        timer: 1500 // 1.5 seconds
                    }).then(() => {
                        window.location.href = "index.php";
                    });
                }
            });
        }


        function fetchActivities(current_sequence, type, activity_sequence) {
            selected_sequence = activity_sequence;
            $.ajax({
                url: 'php/fetch_activity.php?activity_sequence=' + current_sequence + '&move_type=' + type, // Path to your PHP file
                type: 'GET',
                dataType: 'json', // Expecting a JSON response
                success: function(data) {
                    $('#container_activity').html('');

                    if (data.length > 0) {

                        data.forEach(function(activity, index) {
                            let btns = ``;
                            let overlay = '';
                            let playbtn = '';
                            let show = '';
                            console.log(activity);
                            if (current_sequence == 0) {
                                if (activity.activity_sequence == 1) {
                                    btns = `<button id="btnstart_${activity.move_type.toLowerCase()}_${activity.id}_${activity.activity_sequence}" onclick="task_selected(${activity.id}, ${activity.activity_sequence}, '${activity.move_type.toLowerCase()}','Completed')">START</button>
                                    `;
                                }
                            } else if (move_type == activity.move_type) {
                                playbtn = `<button onclick="task_selected(${activity.id}, ${activity.activity_sequence}, '${activity.move_type.toLowerCase()}','Skipped')" class="play_task me-2 position-relative ${show}"><i class="fa-duotone fa-regular fa-play fa-xl"></i></button>`;
                            }

                            if (current_sequence == 0) {
                                show = 'd-none'
                            }

                            let activityHtml = `
                                <div class="task py-3 px-3 border mb-2" onclick="task_selected_id('task_${activity.move_type.toLowerCase()}_${activity.id}',${activity.id},'${activity.activity_sequence}')" id="task_${activity.move_type.toLowerCase()}_${activity.id}">
                                    ${overlay}
                                    <div class="task-details d-flex">
                                        ${playbtn}<h6 style="color: black"><strong>${activity.name}:</strong> ${activity.description}</h6>
                                    </div>
                                    <div class="task_btns ${show}">
                                        ${btns}
                                    </div>
                                </div>
                            `;
                            $('#container_activity').append(activityHtml); // Append to the container

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

        function task_selected_id(id, selected_id, current_sequence) {
            selected_sequence = current_sequence;
            current_id = selected_id;
            document.getElementById('ins_flow').classList.remove('d-none');
            document.querySelectorAll('.task_selected').forEach(el => {
                el.classList.remove('task_selected');
            });
            document.getElementById(id).classList.add('task_selected');
        }

        function updateTable() {
            $.ajax({
                url: 'php/fecth_getting_started.php', // Replace with the actual server-side script to fetch data
                type: 'GET',
                success: function(data) {
                    console.log(data)
                    var tbody = $('#tbody-activities');
                    tbody.empty();
                    data.forEach(function(row) {
                        var tr = $('<tr>');
                        tr.append($('<td>').text(row.name));
                        tr.append($('<td>').text(row.move_type));
                        tr.append($('<td>').text(formatDateTime(row.start_time['date'])));
                        tr.append($('<td>').text(formatDateTime(row.end_time['date'])));
                        tr.append($('<td>').text(row.total_duration_human_readable));
                        tr.append($('<td>').text(row.total_pause_human_readable ?? 'N/A'));
                        tr.append($('<td>').text(row.first_name));
                        tbody.append(tr);
                    });

                    var table = $('#DataTables_Table_0').DataTable({
                        "pagingType": "numbers",
                        "lengthMenu": [
                            [5, 10, 20, 50],
                            [5, 10, 20, 50]
                        ],
                        "pageLength": 10,
                        "lengthChange": true,
                        dom: 'Bfrtip',
                        buttons: [
                            'csv', 'excel' // Enable CSV and Excel export buttons
                        ],
                        "columnDefs": [{
                            "targets": 2, // Column index for Start Time
                            "render": function(data, type, row) {
                                if (type === 'display' || type === 'filter') {
                                    var date = new Date(data); // Parse date
                                    var options = {
                                        year: 'numeric',
                                        month: 'short',
                                        day: 'numeric'
                                    };
                                    return date.toLocaleDateString('en-US', options); // Format as "Jan 30 2025"
                                }
                                return data;
                            }
                        }]
                    });
             
                    $('#download-button').on('click', function() {
                        var startDate = $('#filter-start-date').val();
                        var endDate = $('#filter-end-date').val();
                        var url = 'php/fetch_excel.php?start_date=' + startDate + '&end_date=' + endDate;
                        window.location.href = url;
                    });
                    $('#filter-start-date, #filter-end-date').on('change', function() {
                        var startDate = $('#filter-start-date').val();
                        var endDate = $('#filter-end-date').val();

                        // Check if both start and end date are set
                        if (startDate && endDate) {
                            // Convert the dates to Date objects and set time to midnight (00:00:00)
                            var startDateObj = new Date(startDate);
                            startDateObj.setHours(0, 0, 0, 0); // Normalize to midnight

                            var endDateObj = new Date(endDate);
                            endDateObj.setHours(23, 59, 59, 999); // Normalize to end of the day

                            // Apply the date range filter to the Start Time column
                            table.rows().every(function() {
                                var rowData = this.data();
                                var rowStartDate = new Date(rowData[2]); // Assuming the Start Time is in the 3rd column (index 2)
                                rowStartDate.setHours(0, 0, 0, 0); // Normalize the row's date to midnight

                                // Check if the row's Start Time is within the range
                                if (rowStartDate >= startDateObj && rowStartDate <= endDateObj) {
                                    this.nodes().to$().show(); // Show the row if it matches the date range
                                } else {
                                    this.nodes().to$().hide(); // Hide the row if it doesn't match
                                }
                            });
                        } else {
                            // If no date range is selected, show all rows
                            table.rows().every(function() {
                                this.nodes().to$().show();
                            });
                        }
                    });


                    // $('#DataTables_Table_0').DataTable({
                    //     "paging": true, // Enables pagination
                    //     "searching": true, // Enables search box
                    //     "ordering": true, // Enables sorting
                    // });
                },
                error: function() {}
            });
        }

        function formatDateTime(dateString) {
            if (!dateString) return "N/A"; // Handle null values

            // Convert to Date object
            let date = new Date(dateString);

            // Define options for formatting
            let options = {
                year: 'numeric',
                month: 'short', // Abbreviated month (e.g., "Jan")
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true // Use 12-hour format with AM/PM
            };

            // Format the date and return it
            return date.toLocaleString('en-US', options).replace(',', '');
        }

        updateTable();
    </script>

</body>

</html>