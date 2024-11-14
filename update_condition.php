<?php
include("dbconn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $record_ID = $_POST['record_ID'];
    $new_condition = $_POST['new_condition'];

    // Get the current condition from the tool table
    $sql = "SELECT tool_Condition FROM tool WHERE record_ID = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $record_ID);
    $stmt->execute();
    $stmt->bind_result($old_condition);
    $stmt->fetch();
    $stmt->close();

    // Update the tool's condition
    $update_sql = "UPDATE tool SET tool_Condition = ?, last_condition_update = NOW() WHERE record_ID = ?";
    $update_stmt = $connect->prepare($update_sql);
    $update_stmt->bind_param("si", $new_condition, $record_ID);
    $update_stmt->execute();
    $update_stmt->close();

    // Insert the condition change into the history table
    $history_sql = "INSERT INTO tool_condition_history (record_ID, tool_ID, old_condition, new_condition) VALUES (?, (SELECT tool_ID FROM tool WHERE record_ID = ?), ?, ?)";
    $history_stmt = $connect->prepare($history_sql);
    $history_stmt->bind_param("iiss", $record_ID, $record_ID, $old_condition, $new_condition);
    $history_stmt->execute();
    $history_stmt->close();

    header("Location: tool.php"); // Redirect to the tools page
    exit();
}
?>
