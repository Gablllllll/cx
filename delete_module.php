<?php
include "myconnector.php";
session_start();

if (!isset($_GET['id'])) {
    die("No module ID specified.");
}

$material_id = intval($_GET['id']);

// Optional: Check if the user is allowed to delete this module (security)
$stmt = $conn->prepare("DELETE FROM learning_materials WHERE material_id = ? AND uploaded_by = ?");
$stmt->bind_param("is", $material_id, $_SESSION['first_name']);
if ($stmt->execute()) {
    header("Location: admin_modules.php?upload=deleted");
    exit;
} else {
    echo "Failed to delete module. Error: " . $conn->error;
}
?>