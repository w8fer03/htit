<?php
session_start();
include("dbconn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['userPassword'];

    // Use prepared statements to avoid SQL injection
    $stmt = $connect->prepare("SELECT * FROM user WHERE username = ? AND userPassword = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $query = $stmt->get_result();

    // Check if the user exists
    if ($query->num_rows === 1) {
        $sid = $query->fetch_assoc();
        $_SESSION['userID'] = $sid['userID'];
        $_SESSION['username'] = $sid['username'];
        $_SESSION['userRole'] = $sid['userRole'];

        // Redirect based on user role
        if ($sid['username'] === 'admin') {
            echo "<script>alert('Login successful!'); window.location.href='admin_dashboard.php';</script>";
        } elseif ($sid['username'] === 'Afnan' || $sid['username'] === 'Irfan') {
            echo "<script>alert('Login successful!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Access denied.'); window.location.href='index.html';</script>";
        }
        exit();
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href='index.html';</script>";
    }
}
?>