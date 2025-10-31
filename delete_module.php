<?php
include "myconnector.php";
session_start();

// Admin-only access guard for safety even if called directly
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("No module ID specified.");
}

$material_id = intval($_GET['id']);

// Find the file path first so we can unlink after deletion
$filePath = null;
if ($stmt = $conn->prepare("SELECT file_url FROM learning_materials WHERE material_id = ?")) {
    $stmt->bind_param("i", $material_id);
    if ($stmt->execute()) {
        $stmt->bind_result($fileUrl);
        if ($stmt->fetch()) {
            $filePath = $fileUrl;
        }
    }
    $stmt->close();
}

// Delete the database record (admin can delete any material by id)
if ($stmt = $conn->prepare("DELETE FROM learning_materials WHERE material_id = ?")) {
    $stmt->bind_param("i", $material_id);
    if ($stmt->execute()) {
        $stmt->close();

        // Best-effort file cleanup if it lives under uploads/
        if (!empty($filePath)) {
            // Normalize directory separator and restrict deletion to uploads directory
            $normalizedPath = str_replace(['\\\\', '\\'], '/', $filePath);
            if (strpos($normalizedPath, 'uploads/') === 0) {
                $absolutePath = __DIR__ . '/' . $normalizedPath;
                if (is_file($absolutePath)) {
                    @unlink($absolutePath);
                }
            }
        }

        header("Location: admin_modules.php?upload=deleted");
        exit();
    } else {
        $stmt->close();
        echo "Failed to delete module. Error: " . $conn->error;
    }
} else {
    echo "Failed to prepare delete statement. Error: " . $conn->error;
}
?>