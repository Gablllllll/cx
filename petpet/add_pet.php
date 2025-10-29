<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Add New Pet</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 40px;
      text-align: center;
    }

    h1 {
      color: #333;
    }

    form {
      display: inline-block;
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      margin-top: 20px;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"] {
      display: block;
      width: 300px;
      padding: 10px;
      margin: 10px auto;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    button {
      background-color: #007bff;
      color: white;
      padding: 10px 25px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 15px;
    }

    button:hover {
      background-color: #0056b3;
    }

    .message {
      margin-top: 20px;
      color: green;
      font-weight: bold;
    }

    a {
      display: inline-block;
      margin-top: 10px;
      color: #007bff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<h1>Add a New Pet for Adoption</h1>

<form action="add_pet.php" method="post" enctype="multipart/form-data">
  <input type="text" name="name" placeholder="Pet Name" required>
  <input type="text" name="type" placeholder="Pet Type (e.g., Dog, Cat)" required>
  <input type="number" name="age" placeholder="Pet Age" required>
  <input type="file" name="image" accept="image/*" required>
  <button type="submit" name="submit">Add Pet</button>
</form>

<?php
if (isset($_POST['submit'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $type = $conn->real_escape_string($_POST['type']);
    $age = intval($_POST['age']);

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $target = "uploads/" . basename($image);

    echo "<div class='message'>";
    if (move_uploaded_file($tmp, $target)) {
        $sql = "INSERT INTO pets (name, type, age, image) VALUES ('$name', '$type', $age, '$image')";
        if ($conn->query($sql) === TRUE) {
            echo "Pet added successfully! <br><a href='index.php'>View Pets</a>";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Image upload failed.";
    }
    echo "</div>";
}
?>

</body>
</html>
