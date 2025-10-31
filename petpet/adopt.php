<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "UPDATE pets SET adopted = TRUE WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Pet adopted successfully. <a href='index.php'>Go back</a>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No pet ID provided.";
}
?>
