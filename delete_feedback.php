<?php
include "myconnector.php";
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if feedback_id is provided
if (!isset($_POST['feedback_id']) || empty($_POST['feedback_id'])) {
    echo json_encode(['success' => false, 'message' => 'Feedback ID is required']);
    exit();
}

$feedback_id = (int)$_POST['feedback_id'];

// Delete the feedback
$delete_query = "DELETE FROM feedback WHERE feedback_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $feedback_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Feedback deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Feedback not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

