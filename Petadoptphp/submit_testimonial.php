<?php
// Database connection
$servername = "localhost";
$username = "root"; // Update with your DB username
$password = "";     // Update with your DB password
$dbname = "pets_and_oranges"; // Update with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert testimonial into database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $testimonial = $conn->real_escape_string($_POST['testimonial']);

    $sql = "INSERT INTO testimonials (name, testimonial, created_at) VALUES ('$name', '$testimonial', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Testimonial submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
    