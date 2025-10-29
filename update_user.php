<?php
include "myconnector.php";
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Validate required fields
$required_fields = ['user_id', 'first_name', 'last_name', 'email', 'role'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        exit();
    }
}

$user_id = (int)$_POST['user_id'];
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$email = trim($_POST['email']);
$role = $_POST['role'];
$contact_number = isset($_POST['contact_number']) ? trim($_POST['contact_number']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

// Validate role
if (!in_array($role, ['student', 'admin'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit();
}

// Check if email already exists for another user
$check_query = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("si", $email, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already exists for another user']);
    $check_stmt->close();
    $conn->close();
    exit();
}
$check_stmt->close();

// Update user
$update_query = "UPDATE users SET 
                 first_name = ?, 
                 last_name = ?, 
                 email = ?, 
                 role = ?, 
                 contact_number = ?, 
                 address = ?
                 WHERE user_id = ?";

$stmt = $conn->prepare($update_query);
$stmt->bind_param("ssssssi", $first_name, $last_name, $email, $role, $contact_number, $address, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update user: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

