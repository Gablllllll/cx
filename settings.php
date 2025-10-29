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
    <link rel="stylesheet" href="css/settings.css">
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
      <img src="Images/user-svgrepo-com.svg" alt="User Icon">
      <div class="user-dropdown" id="userDropdown">
        <a href="#" onclick="showSettings()">Settings</a>
        <a href="#" onclick="showProfile()">Profile Information</a>
      </div>
    </div>
  </nav>

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


  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="script/settings.js"></script>
</body>
</html>