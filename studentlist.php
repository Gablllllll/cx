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
    <link rel="stylesheet" href="css/admin.css">
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
        <div class="user-info" onclick="toggleUserDropdown()" style="cursor: pointer;">
            <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
            <img src="Images/user-svgrepo-com.svg" alt="User Icon">
            <div class="user-dropdown" id="userDropdown">
                <a href="#" onclick="showSettings()">Settings</a>
                <a href="#" onclick="showProfileInfo()">Profile Information</a>
            </div>
        </div>
  </nav>

  <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="adminlanding.php"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
            <li><a href="tutorcalendar.php"><img src="Images/calendar-month-svgrepo-com.svg" alt="Calendar Icon"> Calendar</a></li>
            <li><a href="admin_modules.php"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
            <li><a href="studentlist.php"><img src="Images/user-svgrepo-com.svg" alt="Tutors Icon"> Students</a></li>

            <li>
                <a href="#" class="dropdown-toggle">Here</a>
                <ul class="dropdown-menu">
                    <li><a href="#"><img src="Images/idea-svgrepo-com.svg" alt="Features Icon">Features</a></li>
                    <li><a href="#"><img src="Images/about-filled-svgrepo-com.svg" alt="About-Us Icon">About Us</a></li>
                    <li><a href="#"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
                </ul>
            <li>
        </ul>
    </div>

    <!-- Settings Modal -->
    <div id="settingsModal" class="modal-overlay" style="display: none;">
        <div class="modal-content" style="background: white; padding: 20px; border-radius: 10px; max-width: 400px;">
            <span class="close-modal" onclick="closeSettingsModal()" style="float: right; cursor: pointer; font-size: 24px;">&times;</span>
            <h3>Accessibility Settings</h3>
            <div class="setting-group">
                <label for="fontSize">Font Size:</label>
                <select id="fontSize" onchange="changeFontSize(this.value)">
                    <option value="small">Small</option>
                    <option value="medium" selected>Medium</option>
                    <option value="large">Large</option>
                    <option value="extra-large">Extra Large</option>
                </select>
            </div>
            <div class="setting-group">
                <label for="lineSpacing">Line Height:</label>
                <select id="lineSpacing" onchange="changeLineSpacing(this.value)">
                    <option value="tight">Tight</option>
                    <option value="normal" selected>Normal</option>
                    <option value="relaxed">Relaxed</option>
                    <option value="loose">Loose</option>
                </select>
            </div>
            <button class="save-settings-btn" onclick="saveSettings()" style="background: #00e200; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; margin-top: 20px;">Save Settings</button>
        </div>
    </div>

    <!-- Profile Information Modal -->
    <div id="profileModal" class="modal-overlay" style="display: none;">
        <div class="modal-content" style="background: white; padding: 20px; border-radius: 10px; max-width: 500px;">
            <span class="close-modal" onclick="closeProfileModal()" style="float: right; cursor: pointer; font-size: 24px;">&times;</span>
            <h3>Profile Information</h3>
            <div class="profile-info">
                <div class="profile-field">
                    <label><strong>Name:</strong></label>
                    <span><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></span>
                </div>
                <div class="profile-field">
                    <label><strong>Email:</strong></label>
                    <span><?php echo htmlspecialchars($_SESSION['email'] ?? 'Not provided'); ?></span>
                </div>
                <div class="profile-field">
                    <label><strong>User ID:</strong></label>
                    <span><?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
                </div>
                <div class="profile-field">
                    <label><strong>Role:</strong></label>
                    <span><?php echo htmlspecialchars($_SESSION['role'] ?? 'Student'); ?></span>
                </div>
            </div>
            <div style="margin-top: 20px; text-align: center;">
                <button onclick="closeProfileModal()" style="background: #00e200; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Close</button>
            </div>
        </div>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="script/studentlist.js"></script>
</body>
</html>