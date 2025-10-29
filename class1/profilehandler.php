<?php
include "myconnector.php";
session_start();

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$birthday = $_POST['birthday'];
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

// Check for duplicate email (excluding current user)
$check = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$check->bind_param("si", $email, $user_id);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    header("Location: settings.php?error=Email already exists");
    exit;
}

// Handle profile picture upload
$profile_pic = null;
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir);
    $filename = uniqid() . "_" . basename($_FILES["profile_pic"]["name"]);
    $target_file = $target_dir . $filename;
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        $profile_pic = $target_file;
    }
}

// Build SQL dynamically
$sql = "UPDATE users SET first_name=?, email=?, date_of_birth=?";
$params = [$name, $email, $birthday];
$types = "sss";

if ($password) {
    $sql .= ", password=?";
    $params[] = $password;
    $types .= "s";
}
if ($profile_pic) {
    $sql .= ", profile_pic=?";
    $params[] = $profile_pic;
    $types .= "s";
}
$sql .= " WHERE user_id=?";
$params[] = $user_id;
$types .= "i";

// Prepare and execute
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();

header("Location: settings.php?success=1");
exit;
?>