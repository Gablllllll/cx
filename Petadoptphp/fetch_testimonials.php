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

// Fetch testimonials
$sql = "SELECT name, testimonial, created_at FROM testimonials ORDER BY created_at DESC";
$result = $conn->query($sql);

$testimonials = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($testimonials);
?>
