<?php
include "myconnector.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress</title>
    <link rel="stylesheet" href="css/progress.css">
</head>
<body>
    <nav class="navbar">
        <!-- Burger Menu -->
        <div class="burger-menu" onclick="toggleSidebar()">
            <div></div>
            <div></div>
            <div></div>
            </div>
        <!-- Title -->
        <div class="nav-center">ClassXic</div>
        <!-- User Info -->
        <div class="user-info">
            <img src="Images/user-svgrepo-com.svg" alt="User Icon">
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="#"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
        <li><a href="#"><img src="Images/idea-svgrepo-com.svg" alt="Features Icon">Features</a></li>
            <li><a href="#"><img src="Images/about-filled-svgrepo-com.svg" alt="About-Us Icon">About Us</a></li>
            <li>
                <a href="#" class="dropdown-toggle">Here</a>
                <ul class="dropdown-menu">
                    <li><a href="#"><img src="Images/calendar-month-svgrepo-com.svg" alt="Calendar Icon"> Calendar</a></li>
                    <li><a href="#"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
                    <li><a href="#"><img src="Images/user-svgrepo-com.svg" alt="Tutors Icon"> Tutor</a></li>
                    <li><a href="#"><img src="Images/progress-svgrepo-com.svg" alt="Progress Icon">Progress</a></li>
                    <li><a href="#"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
                </ul>
            <li>
        </ul>
    </div>
    <div class="progress-container">
        <h1>Uploaded Files</h1>
        <?php
        $query = "SELECT material_id, title, uploaded_by, approved_at, file_url FROM learning_materials WHERE is_approved = 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="progress-item">';
                echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                echo '<p><strong>Uploaded By:</strong> ' . htmlspecialchars($row['uploaded_by']) . '</p>';
                echo '<p><strong>Approved At:</strong> ' . htmlspecialchars($row['approved_at']) . '</p>';
                echo '<a class="view-btn" href="modules.php?file_url=' . urlencode($row['file_url']) . '">View</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No materials found.</p>';
        }
        ?>
    </div>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="script/progress.js"></script>
</body>
</html>