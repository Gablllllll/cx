<?php
$servername = "localhost";
$username = "u481076545_wmad_db";
$password = "cImuF@[tL1^";
$db = "u481076545_wmad_db";
// Create connection
$conn = new mysqli($servername, $username, $password,$db);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//  echo "Connected kana";
?>

