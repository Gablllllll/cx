<?php
include "myconnector.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to submit feedback']);
    exit();
}

$user_id = $_SESSION['user_id'];
$material_id = $_POST['material_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$comment = trim($_POST['comment'] ?? '');

if (!$material_id || !$rating) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

// Validate rating
if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
    exit();
}

try {
    // Check if user already provided feedback for this material
    $check_stmt = $conn->prepare("SELECT feedback_id FROM feedback WHERE user_id = ? AND material_id = ?");
    $check_stmt->bind_param("ii", $user_id, $material_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing feedback
        $update_stmt = $conn->prepare("UPDATE feedback SET rating = ?, comment = ?, created_at = CURRENT_TIMESTAMP WHERE user_id = ? AND material_id = ?");
        $update_stmt->bind_param("isii", $rating, $comment, $user_id, $material_id);
        
        if ($update_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Feedback updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update feedback']);
        }
        $update_stmt->close();
    } else {
        // Insert new feedback
        $insert_stmt = $conn->prepare("INSERT INTO feedback (user_id, material_id, rating, comment) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("iiis", $user_id, $material_id, $rating, $comment);
        
        if ($insert_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Feedback submitted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to submit feedback']);
        }
        $insert_stmt->close();
    }
    
    $check_stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
