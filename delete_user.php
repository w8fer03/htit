<?php
include("dbconn.php");

if (isset($_GET['userID'])) {
    $id = $_GET['userID'];
    
    // Debugging: Ensure userID is received
    error_log("User ID to delete: " . $id);

    $query = "DELETE FROM user WHERE userID = '$id'"; // Ensure the column name matches your database

    $result = mysqli_query($connect, $query);

    if (!$result) {
        // Log the error for debugging
        error_log("Query Failed: " . mysqli_error($connect));
        die("Query Failed: " . mysqli_error($connect));
    } else {
        echo "<script type='text/javascript'>
              alert('User deleted successfully!');
              window.location.href = 'users.php';
              </script>";
    }
} else {
    echo "No userID parameter provided."; // Debugging line
}
?>
