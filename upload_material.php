<?php
include "myconnector.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $uploaded_by = 'tutor'; // Default for now
    $upload_date = date('Y-m-d H:i:s');
    $is_approved = 1; // Default to approved
    $approved_by = 'admin'; // Default for now
    $approved_at = $upload_date;

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        // Ensure the file is a PDF
        if ($file_ext === 'pdf') {
            $file_url = 'uploads/' . uniqid() . '_' . $file_name;
            move_uploaded_file($file_tmp, $file_url);

            // Insert into database
            $query = "INSERT INTO learning_materials (title, description, file_url, uploaded_by, upload_date, is_approved, approved_by, approved_at)
                      VALUES ('$title', '$description', '$file_url', '$uploaded_by', '$upload_date', '$is_approved', '$approved_by', '$approved_at')";
            if (mysqli_query($conn, $query)) {
                header("Location: tutormodule.php?upload=success");
                exit();
            } else {
                header("Location: tutormodule.php?upload=fail");
                exit();
            }
        } else {
            echo "Only PDF files are allowed.";
        }
    } else {
        echo "Error uploading file.";
    }
}
?>