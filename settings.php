<?php
include "myconnector.php";
session_start();
$user_id = $_SESSION['user_id'];
$sql = "SELECT first_name,last_name, email, date_of_birth, profile_pic FROM users WHERE user_id = 26";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$name = $row['first_name'];
$last_name = $row['last_name'];
$email = $row['email'];
$birthday = $row['date_of_birth'];
$profile_pic = $row['profile_pic'];
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
    <div class="user-info">
      <img src="Images/user-svgrepo-com.svg" alt="User Icon">
    </div>
  </nav>

  <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="landingpage.php"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
            <li><a href="calendar.php"><img src="Images/calendar-month-svgrepo-com.svg" alt="Calendar Icon"> Calendar</a></li>
            <li><a href="studentmodule.php"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
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

    <!-- Profile Section -->
    <div class="profile-container">
        <div class="profile-card">
            <img src="<?php echo $profile_pic ? $profile_pic : 'Images/default-user.png'; ?>" alt="Profile Picture" class="profile-pic">
            <div class="profile-name"><?php echo htmlspecialchars($name); ?></div>
            <label for="profile-upload" class="profile-upload-label">Add Profile picture</label>
            <input type="file" id="profile-upload" name="profile_pic" style="display:none;">
        </div>
        <div class="profile-info-card">
            <div class="profile-info-title">Profile</div>
            <div class="profile-info-row"><b>Name:</b> <span><?php echo htmlspecialchars($name); ?></span></div>
            <div class="profile-info-row"><b>Email:</b> <span><?php echo htmlspecialchars($email); ?></span></div>
            <div class="profile-info-row"><b>Birthday:</b> <span><?php echo htmlspecialchars($birthday); ?></span></div>
            <div class="profile-info-row"><b>Password:</b> <span>**********</span></div>
            <button class="edit-profile-btn" onclick="openEditModal()">Edit Profile</button>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal-overlay">
      <div class="modal-content">
        <span class="close-modal" onclick="closeEditModal()">&times;</span>
        <h3>Edit Profile</h3>
        <form action="profilehandler.php" method="POST" enctype="multipart/form-data">
          <label>Name:</label>
          <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
          <label>Email:</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
          <label>Birthday:</label>
          <input type="date" name="birthday" value="<?php echo htmlspecialchars($birthday); ?>">
          <label>Password:</label>
          <input type="password" name="password" placeholder="Enter new password">
          <label>Profile Picture:</label>
          <input type="file" name="profile_pic">
          <button type="submit" class="save-btn">Save Changes</button>
        </form>
      </div>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="script/settings.js"></script>
</body>
</html>