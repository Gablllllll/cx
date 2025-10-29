<?php
session_start(); // Start the session to use session variables

$conn = new mysqli("localhost", "root", "", "pets_and_oranges");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    // Store a success message in the session
    $_SESSION['success_message'] = "Your message has been sent successfully!";
    header("Location: index.php#contact"); // Redirect back to the contact section
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
