<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magdaong_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $contact_number = $_POST['contact-number'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check_email = "SELECT email FROM customer WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Email already exists. Please use a different email.');</script>";
        } else {
            // Insert data into the database with hashed password
            $sql = "INSERT INTO customer (name, age, sex, Phone, address, email, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sisssss", $name, $age, $sex, $contact_number, $address, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php"); // Redirect to login page after successful registration
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - PastaSalad</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div id="modal" class="modal hidden">
        <div class="modal-content">
            <span id="closeModal" class="close-button" onclick="closeModal()">&times;</span>
            <h2 id="modal-title"></h2>
            <p id="modal-body"></p>
        </div>
    </div>
    <div class="login-wrapper">
        <div class="login-container">
            <form class="login-form" action="signup.php" method="POST" id="signupForm">
                <!-- Step 1 -->
                <div class="form-step" id="step1">
                    <h1>Step 1: Personal Information</h1>
                    <div class="input-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="input-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" placeholder="Enter your age" required>
                    </div>
                    <div class="input-group">
                        <label for="sex">Sex</label>
                        <select id="sex" name="sex" required>
                            <option value="">Select your sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="contact-number">Contact Number</label>
                        <input type="text" id="contact-number" name="contact-number" placeholder="Enter your contact number" required>
                    </div>
                    <div class="input-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Enter your address" required>
                    </div>
                    <button type="button" class="next-button" onclick="nextStep()">Next</button>
                </div>

                <!-- Step 2 -->
                <div class="form-step hidden" id="step2">
                    <h1>Step 2: Account Information</h1>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Create Password</label>
                        <input type="password" id="password" name="password" placeholder="Create password" required>
                    </div>
                    <div class="input-group">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm password" required>
                    </div>
                    <button type="button" class="back-button" onclick="previousStep()">Back</button>
                    <button type="submit" class="login-button">Sign Up</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>