<?php
session_start();
include("dbconn.php");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (!isset($_POST['record_ID']) || !isset($_POST['quantity']) || !is_numeric($_POST['record_ID']) || !is_numeric($_POST['quantity'])) {
        header("Location: tools_inuse.php?message=Invalid input");
        exit();
    }

    $recordID = intval($_POST['record_ID']);
    $checkoutQuantity = intval($_POST['quantity']);

    // Check if user is logged in and has permission
    if (!isset($_SESSION['userID'])) {
        header("Location: login.html");
        exit();
    }

    $userID = $_SESSION['userID'];

    // Get the tool information using record_ID
    $toolSql = "SELECT tool_Quantity, tool_Status FROM tool WHERE record_ID = ?";
    $toolStmt = $connect->prepare($toolSql);
    $toolStmt->bind_param("i", $recordID);
    $toolStmt->execute();
    $toolResult = $toolStmt->get_result();

    if ($toolResult->num_rows > 0) {
        $tool = $toolResult->fetch_assoc();

        if ($tool['tool_Status'] === 'Available' && $checkoutQuantity > 0 && $checkoutQuantity <= $tool['tool_Quantity']) {
            // Update the tool quantity and status
            $newQuantity = $tool['tool_Quantity'] - $checkoutQuantity;
            $newStatus = ($newQuantity > 0) ? 'Available' : 'In Use';

            $updateToolSql = "UPDATE tool SET tool_Quantity = ?, tool_Status = ? WHERE record_ID = ?";
            $updateToolStmt = $connect->prepare($updateToolSql);
            $updateToolStmt->bind_param("isi", $newQuantity, $newStatus, $recordID);
            $updateToolStmt->execute();

            // Insert a new record into the usage history
            $insertUsageSql = "INSERT INTO usagehistory (record_ID, userID, tool_Qty, checkout_Date, checkout_Time) VALUES (?, ?, ?, NOW(), NOW())";
            $insertUsageStmt = $connect->prepare($insertUsageSql);
            $insertUsageStmt->bind_param("iii", $recordID, $userID, $checkoutQuantity);
            $insertUsageStmt->execute();

            header("Location: tools_inuse.php?message=Checked out successfully");
            exit();
        } else {
            header("Location: tools_inuse.php?message=Invalid check-out request");
            exit();
        }
    } else {
        header("Location: tools_inuse.php?message=Tool not found");
        exit();
    }

    // Close database connections
    $toolStmt->close();
    $updateToolStmt->close();
    $insertUsageStmt->close();
    $connect->close();
}
?>
