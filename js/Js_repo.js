
$(document).ready(function() {
 
    $("#activityList").change(function() {
        var selectedActivity = $(this).val();

        if (selectedActivity != null ) {
            $("#loadingUnloadingButtons").show();
            $("#startEndButtons").hide();
        } else {
            $("#loadingUnloadingButtons").hide();
            $("#startEndButtons").show();
        }
    });

    $("#loadingBtn, #unloadingBtn").click(function() {
        // Enable the "Start" button and disable the "End" button
        $("#startBtn").prop("disabled", false);
        $("#endBtn").prop("disabled", true);

        // For now, just showing the start and end buttons
        $("#loadingUnloadingButtons").hide();
        $("#startEndButtons").show();
    });

    $("#startBtn").click(function() {
        // Disable the "Start" button and enable the "End" button
        $("#activityList").prop("disabled", true);

        $("#startBtn").prop("disabled", true);
        $("#endBtn").prop("disabled", false);

        lc.start();
    });

    $("#endBtn").click(function() {
        // Disable the "End" button
        $(this).prop("disabled", true);
        $("#activityList").prop("disabled", false);

        $("#activityList").prop("selectedIndex", 0);
        $("#startBtn").prop("disabled", false);

        $("#loadingUnloadingButtons").hide();
        $("#startEndButtons").hide();
    });
});