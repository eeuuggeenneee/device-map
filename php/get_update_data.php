<?php
include("../includes/db.php");
session_start();


$view = "SELECT * FROM user_duration WHERE user_id = '" . $_SESSION['user_id'] . "' ORDER BY id DESC;";
$show = sqlsrv_query($conn, $view);

// Build HTML for the updated data
$html = '';
while ($show2 = sqlsrv_fetch_array($show, SQLSRV_FETCH_ASSOC)) {
    $html .= "<tr>
                 <td>" . $show2['date'] . "</td>
                <td>" . $show2['name']  . ": " . $show2['description']  . " </td>
                <td>" . $show2['formatted_start_time'] . "</td>
                <td>" . $show2['formatted_end_time'] . "</td>
                <td>" . $show2['duration'] . "</td>
              </tr>";
}
// Close the database connection
sqlsrv_close($conn);

// Send the HTML response
echo $html;
?>
