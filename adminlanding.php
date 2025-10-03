<?php
include "myconnector.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
  <nav class="navbar">
    <div class="burger-menu" onclick="toggleSidebar()">
      <div></div>
      <div></div>
      <div></div>
    </div>
    <div class="nav-center">ClassXic</div>
    <div class="user-info">
      <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
      <img src="Images/user-svgrepo-com.svg" alt="User Icon">
    </div>
  </nav>

  <div class="sidebar" id="sidebar">
    <ul>
        <li><a href="landingpage.php"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
        <li><a href="calendar.php"><img src="Images/calendar-month-svgrepo-com.svg" alt="Calendar Icon"> Calendar</a></li>
        <li><a href="admin_modules.php"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
        <li><a href="adminlist.php"><img src="Images/user-svgrepo-com.svg" alt="Users Icon"> Users</a></li>
        <li><a href="progress.php"><img src="Images/progress-svgrepo-com.svg" alt="Progress Icon">Progress</a></li>
        <li>
            <a href="#" class="dropdown-toggle">-Option-</a>
            <ul class="dropdown-menu">
                <li><a href="landingpage.php"><img src="Images/idea-svgrepo-com.svg" alt="Features Icon">Features</a></li>
                <li><a href="landingpage.php"><img src="Images/about-filled-svgrepo-com.svg" alt="About-Us Icon">About Us</a></li>
                <li><a href="settings.php"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
            </ul>
        <li>
    </ul>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="script/adminlist.js"></script>
</body>
</html>


