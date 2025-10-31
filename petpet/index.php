<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Pet Adoption</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Available Pets for Adoption</h1>
<div class="pet-list">
  <?php
    $result = $conn->query("SELECT * FROM pets WHERE adopted = FALSE");
    while ($row = $result->fetch_assoc()) {
        echo "<div class='pet-card'>";
        echo "<img src='uploads/" . $row['image'] . "' width='200'><br>";
        echo "<h2>" . $row['name'] . "</h2>";
        echo "<p>Type: " . $row['type'] . "</p>";
        echo "<p>Age: " . $row['age'] . " years</p>";
        echo "<a href='adopt.php?id=" . $row['id'] . "'>Adopt</a>";
        echo "</div>";
    }
  ?>
</div>
</body>
</html>
