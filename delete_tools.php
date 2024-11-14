<?php
include("dbconn.php");

if (isset($_GET['record_ID'])) {
    $id = $_GET['record_ID'];
    
    // Debugging: Ensure userID is received
    error_log("User ID to delete: " . $id);

    $query = "DELETE FROM tool WHERE record_ID = '$id'"; // Ensure the column name matches your database

    $result = mysqli_query($connect, $query);

    if (!$result) {
        // Log the error for debugging
        error_log("Query Failed: " . mysqli_error($connect));
        die("Query Failed: " . mysqli_error($connect));
    } else {
        echo "<script type='text/javascript'>
              alert('Tools deleted successfully!');
              window.location.href = 'tools.php';
              </script>";
    }
} else {
    echo "No userID parameter provided."; // Debugging line
}
?>
