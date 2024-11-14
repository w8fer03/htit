<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

var_dump($_POST); // Debugging line

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve username, new password, and confirmation password from the form
    $username = $_POST['username']; // Assuming the input field is named "username"
    $new_password = $_POST['new-password']; // Assuming the input field is named "new-password"
    $confirm_password = $_POST['confirm-password']; // Assuming the input field is named "confirm-password"

    // Validate that the new password and confirm password fields match
    if ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirm password do not match. Please try again.'); window.location.href = 'forgot-password.php';</script>";
        exit;
    }

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'htitdb'); // Adjust database credentials as needed
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Connected successfully"; // Debugging line
    }

    // Check if a user with the provided username exists in the database
    $check_sql = "SELECT * FROM user WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows == 0) {
        // User with the provided username does not exist
        echo "<script>alert('User with the provided username does not exist. Please check the username and try again.'); window.location.href = 'forgotPass.php';</script>";
        exit;
    } else {
        echo "User found: " . $username . "<br>"; // Debugging line
    }

    // Debug: Print the new password hash to verify it
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    echo "Hashed password: " . $hashed_password . "<br>"; // Debugging line

    // Update the password in the database (with prepared statement to prevent SQL injection)
    $update_sql = "UPDATE user SET userPassword = ? WHERE username = ?";
    $update_stmt = $conn->prepare($update_sql);

    if ($update_stmt === false) {
        die("Failed to prepare the update query: " . $conn->error); // Debugging line
    }

    $update_stmt->bind_param("ss", $hashed_password, $username); // Hash the password

    // Execute the update
    if ($update_stmt->execute()) {
        // Password updated successfully
        echo "<script>alert('Password updated successfully!'); window.location.href = 'index.html';</script>";
        exit; // Exit to prevent further execution
    } else {
        // Error occurred during execution
        echo "<script>alert('Error updating password. Please try again later.');</script>";
        // Debug: Output the error message
        echo "Error updating password: " . $update_stmt->error;
    }

    // Close the statements and database connection
    $check_stmt->close();
    $update_stmt->close();
    $conn->close();
} else {
    // Redirect back to the index page if accessed directly without POST method
    header("Location: index.html");
    exit;
}
