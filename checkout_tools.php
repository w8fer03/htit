<?php
session_start();
include("dbconn.php");

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (!isset($_POST['record_ID']) || !isset($_POST['quantity'])) {
        $response['message'] = 'Record ID and quantity are required.';
        echo json_encode($response);
        exit;
    }

    $recordID = intval($_POST['record_ID']); // Ensure it's an integer
    $quantity = intval($_POST['quantity']);

    // Check for valid quantity
    if ($quantity <= 0) {
        $response['message'] = 'Quantity must be greater than zero.';
        echo json_encode($response);
        exit;
    }

    // Get current tool data using record_ID
    $sql = "SELECT tool_Quantity FROM tool WHERE record_ID = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $recordID); // Assuming record_ID is an integer

    if (!$stmt->execute()) {
        $response['message'] = 'Execution failed: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }

    $result = $stmt->get_result();
    $tool = $result->fetch_assoc();

    if ($tool) {
        if ($quantity <= $tool['tool_Quantity']) {
            // Update the tool quantity
            $newQuantity = $tool['tool_Quantity'] - $quantity;
            $updateSql = "UPDATE tool SET tool_Quantity = ? WHERE record_ID = ?";
            $updateStmt = $connect->prepare($updateSql);
            $updateStmt->bind_param("ii", $newQuantity, $recordID);

            if (!$updateStmt->execute()) {
                $response['message'] = 'Execution failed: ' . $updateStmt->error;
                echo json_encode($response);
                exit;
            }

            // Update tool status to 'In Use' if quantity becomes 0
            if ($newQuantity == 0) {
                $statusUpdateSql = "UPDATE tool SET tool_Status = 'In Use' WHERE record_ID = ?";
                $statusUpdateStmt = $connect->prepare($statusUpdateSql);
                $statusUpdateStmt->bind_param("i", $recordID);
                if (!$statusUpdateStmt->execute()) {
                    $response['message'] = 'Failed to update status: ' . $statusUpdateStmt->error;
                    echo json_encode($response);
                    exit;
                }
            }

            // We will set the status to 'In Use' for the checked-out quantity by adding a new record in the usagehistory table.
            $insertUsageSql = "INSERT INTO usagehistory (record_ID, userID, checkout_Date,checkout_Time, tool_Qty) VALUES (?, ?, NOW(), NOW(), ?)";
            $insertUsageStmt = $connect->prepare($insertUsageSql);
            $insertUsageStmt->bind_param("sii", $recordID, $_SESSION['userID'], $quantity); // Assuming userID is in session

            if (!$insertUsageStmt->execute()) {
                $response['message'] = 'Failed to insert usage history: ' . $insertUsageStmt->error;
                echo json_encode($response);
                exit;
            }

            // Prepare the response
            $response['success'] = true;
            $response['message'] = 'Checked out successfully';
            $response['newQuantity'] = $newQuantity;
            $response['isAvailable'] = $newQuantity > 0;
        } else {
            $response['message'] = 'Invalid quantity';
        }
    } else {
        $response['message'] = 'Tool not found';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>