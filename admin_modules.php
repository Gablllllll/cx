<?php
include "myconnector.php";
session_start();

// Admin-only access guard
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: loginpage.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Modules - ClassXic</title>

    <link rel="stylesheet" href="css/admin_modules.css">
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
      <span><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
      <img src="Images/user-svgrepo-com.svg" alt="User Icon">
    </div>
  </nav>

  <div class="sidebar" id="sidebar">
    <div class="sidebar-content">
      <ul class="sidebar-nav">
        <li><a href="admin.php"><img src="Images/dashboard-svgrepo-com.svg" alt="Home Icon">Dashboard</a></li>
        <li><a href="admin_users.php"><img src="Images/user-svgrepo-com.svg" alt="Features Icon">Users</a></li>
        <li><a href="admin_modules.php"><img src="Images/book-svgrepo-com.svg" alt="About-Us Icon">Modules</a></li>
      </ul>
      <div class="sidebar-bottom">
        <ul class="sidebar-options">
          <li>
            <a href="#" class="dropdown-toggle"><img src="Images/option.png" alt="Option Icon">Option</a>
            <ul class="dropdown-menu">
              <li><a href="settings.php"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
              <li><a href="logout.php"><img src="Images/logout-svgrepo-com.svg" alt="Logout Icon">Log out</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <main class="main-content">
    <div class="dashboard-wrapper">
      <div class="page-title" >Upload Learning Materials</div>
      <br><br>
      <?php if (isset($_GET['upload'])): ?>
      <?php
        $statusClass = 'neutral';
        if ($_GET['upload'] === 'success' || $_GET['upload'] === 'deleted') {
          $statusClass = 'success';
        } elseif ($_GET['upload'] === 'forbidden') {
          $statusClass = 'warning';
        } elseif ($_GET['upload'] === 'fail') {
          $statusClass = 'error';
        }
      ?>
      <div class="upload-status <?php echo 'status-' . $statusClass; ?>">
        <?php
          if ($_GET['upload'] === 'success') echo 'Upload Successful';
          elseif ($_GET['upload'] === 'fail') echo 'Upload Failed';
          elseif ($_GET['upload'] === 'forbidden') echo 'You are not allowed to upload. Admins only.';
          elseif ($_GET['upload'] === 'deleted') echo 'Module deleted successfully.';
        ?>
      </div>
      <?php endif; ?>
        <form action="upload_material.php" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            
            <label for="file">Upload File (PDF):</label>
            <input type="file" name="file" id="file" accept=".pdf" required>
            
            <button type="submit">Upload</button>
        </form>
        <br><br>
      <div style="height:16px"></div>
      <div class="page-title">All Learning Materials</div>
      <br><br>
      <?php
        $result = mysqli_query($conn, "SELECT material_id, title, description, file_url, uploaded_by, upload_date, is_approved, approved_by, approved_at FROM learning_materials ORDER BY approved_at DESC, upload_date DESC");
        if ($result && mysqli_num_rows($result) > 0):
      ?>
      <div class="module-list">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="module-item">
            <div class="header">
              <div class="title"><?php echo htmlspecialchars($row['title']); ?></div>
              <div class="meta">
                <?php echo $row['approved_at'] ? date("M j, Y", strtotime($row['approved_at'])) : date("M j, Y", strtotime($row['upload_date'])); ?>
              </div>
            </div>
            <div class="description">
              <?php echo htmlspecialchars($row['description']); ?>
            </div>
            <div class="footer">
              <div class="meta">
                Uploaded by: <?php echo htmlspecialchars($row['uploaded_by']); ?>
                <?php if ((int)$row['is_approved'] === 1): ?>
                  · Approved by: <?php echo htmlspecialchars($row['approved_by']); ?>
                <?php else: ?>
                  · Pending approval
                <?php endif; ?>
              </div>
              <div class="actions">
                <a href="modules.php?file_url=<?php echo urlencode($row['file_url']); ?>">View</a>
                <a href="delete_module.php?id=<?php echo (int)$row['material_id']; ?>" onclick="return confirm('Delete this module?');" class="danger">Delete</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      <?php else: ?>
        <p>No learning materials found.</p>
      <?php endif; ?>
    </div>
  </main>

  <script src="script/admin.js"></script>
</body>
</html>