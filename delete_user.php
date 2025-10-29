<?php
include "myconnector.php";
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if user_id is provided
if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit();
}

$user_id = (int)$_POST['user_id'];

// Prevent admin from deleting themselves
if ($user_id == $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'You cannot delete your own account']);
    exit();
}

// Check if the user to be deleted is an admin
$check_role_query = "SELECT role FROM users WHERE user_id = ?";
$check_stmt = $conn->prepare($check_role_query);
$check_stmt->bind_param("i", $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    $check_stmt->close();
    $conn->close();
    exit();
}

$user_data = $result->fetch_assoc();
$check_stmt->close();

// Prevent deletion of admin users
if ($user_data['role'] === 'admin') {
    echo json_encode(['success' => false, 'message' => 'Cannot delete admin users']);
    $conn->close();
    exit();
}

// Delete the user (cascading deletes will handle related records if foreign keys are set up)
$delete_query = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete user: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
