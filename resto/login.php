<?php
session_start(); // Start the session

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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT name, password FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($stored_name, $stored_password);

    // Fetch the result
    if ($stmt->fetch()) {
        // Remove password hashing, directly compare passwords
        if ($stored_password === $password) { // Direct string comparison
            $_SESSION['username'] = $stored_name;
            $_SESSION['email'] = $email;
            header("Location: index.php"); // Redirect to main page
            exit();
        } else {
            $error_message = "Incorrect email or password.";
        }
    } else {
        $error_message = "Incorrect email or password.";
    }

    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PastaSalad</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <!-- Modal for login error message -->
    <div id="modal" class="modal <?php echo isset($error_message) ? '' : 'hidden'; ?>">
        <div class="modal-content">
        <span id="closeModal" class="close" onclick="closeModal()" style="position: absolute; top: 10px; right: 15px; font-size: 18px; cursor: pointer;">&times;</span>

            <h2>Login Error</h2>
            <p><?php echo isset($error_message) ? $error_message : ''; ?></p>
        </div>
    </div>

    <div class="login-wrapper">
        <div class="login-container">
            <form class="login-form" action="login.php" method="POST">
                <h1>Login</h1>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" class="toggle-password">&#128065;</button>
                    </div>
                </div>

                <a href="#" class="forgot-password">Forgot password?</a>

                <button type="submit" class="login-button">Login</button>

                <p class="signup-text">Don’t have an account? <a href="signup.php">Sign up</a></p>
            </form> 
        </div>
    </div>

    <script>
        // Function to close the modal when the close button is clicked
        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }

        // Display the modal if there's an error message
        <?php if (isset($error_message)): ?>
            document.getElementById("modal").style.display = "flex"; // Show modal
        <?php endif; ?>
    </script>

    <script src="script.js"></script>
</body>
</html>