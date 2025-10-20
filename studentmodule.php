<?php
include "myconnector.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Modules - ClassXic</title>

    <link rel="stylesheet" href="css/landingpage.css">
    <link rel="stylesheet" href="css/studentmodule.css">
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
        <div class="nav-center">ClassXic</div>
        <!-- User Info -->
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
            <img src="Images/user-svgrepo-com.svg" alt="User Icon">
        </div>
        
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-wrapper">
            <div class="page-header">
                <h1>Student Modules</h1>
                <p>Access approved learning materials and resources</p>
            </div>

            <!-- Module List Container -->
            <?php
            $query = "SELECT material_id, title, uploaded_by, approved_at, file_url FROM learning_materials WHERE is_approved = 1 ORDER BY approved_at DESC";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<div class="module-list">';
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="module-item">';
                    
                    // Module Header
                    echo '<div class="module-header">';
                    echo '  <div class="module-user">';
                    echo '      <img src="Images/user-svgrepo-com.svg" alt="User Icon" class="module-user-icon">';
                    echo '      <span class="module-user-name">' . htmlspecialchars($row['uploaded_by']) . '</span>';
                    echo '      <img src="Images/verified.png" alt="Verified" class="module-verified-icon">';
                    echo '  </div>';
                    echo '  <div class="module-date">' . date("F j, Y", strtotime($row['approved_at'])) . '</div>';
                    echo '</div>';
                    
                    // Module Body
                    echo '<div class="module-body">';
                    echo '  <div class="module-title">' . htmlspecialchars($row['title']) . '</div>';
                    echo '  <div class="module-actions">';
                    echo '      <a href="modules.php?file_url=' . urlencode($row['file_url']) . '" class="module-view-btn">View Module</a>';
                    echo '  </div>';
                    echo '</div>';

                    echo '</div>'; // .module-item
                }

                echo '</div>'; // .module-list
            } else {
                echo '<div class="empty-state">';
                echo '  <div class="empty-icon">📚</div>';
                echo '  <h3>No Modules Available</h3>';
                echo '  <p>No approved learning materials are currently available. Check back later for new content.</p>';
                echo '</div>';
            }
            ?>
        </div>
    </main>


    <div class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <ul class="sidebar-nav">
                <li><a href="landingpage.php"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
             
                <li><a href="studentmodule.php" class="active"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
            </ul>
            
      
            <div class="sidebar-calendar">
                <div class="calendar-header">
                    <button class="calendar-nav" id="prevMonth">&lt;</button>
                    <span class="calendar-month-year" id="currentMonthYear"></span>
                    <button class="calendar-nav" id="nextMonth">&gt;</button>
                </div>
                <div class="calendar-grid" id="calendarGrid">
      
                </div>
            </div>
            
            <div class="sidebar-bottom">
                <ul class="sidebar-options">
                    <li>
                        <a href="#" class="dropdown-toggle"><img src="Images/option.png" alt="Option Icon">Option</a>
                        <ul class="dropdown-menu">
                            <li><a href="#settings"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
                            <li><a href="logout.php"><img src="Images/logout-svgrepo-com.svg" alt="Logout Icon">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script src="script/landingpage.js"></script>

</body>
</html>