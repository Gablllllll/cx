<?php
include "myconnector.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/admin_modules.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <!-- Burger Menu -->
    <div class="burger-menu" onclick="toggleSidebar()">
      <div></div>
      <div></div>
      <div></div>
    </div>
    <!-- Title -->
    <div class="nav-center">Classix</div>
    <!-- User Info -->
    <div class="user-info">
      <img src="Images/user-svgrepo-com.svg" alt="User Icon">
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <ul>
        <li><a href="#"><img src="Images/dashboard-svgrepo-com.svg" alt="Home Icon">Dashboard</a></li>
        <li><a href="#"><img src="Images/user-svgrepo-com.svg" alt="Features Icon">Users</a></li>
        <li><a href="#"><img src="Images/book-svgrepo-com.svg" alt="About-Us Icon">Modules</a></li>
        <li><a href="#"><img src="Images/users-svgrepo-com.svg" alt="About-Us Icon">Applicants</a></li>
     </ul>
  </div>
    <div class="container">
        <h1>Upload Learning Materials</h1>
        <form action="upload_material.php" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            
            <label for="file">Upload File (PDF):</label>
            <input type="file" name="file" id="file" accept=".pdf" required>
            
            <button type="submit">Upload</button>
        </form>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="script/admin_modules.js"></script>
</body>
</html>