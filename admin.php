<?php
include "myconnector.php";
session_start();

// Fetch counts
$user_count = 0;
$materials_count = 0;

if ($result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users")) {
	$row = mysqli_fetch_assoc($result);
	$user_count = (int)($row['c'] ?? 0);
	mysqli_free_result($result);
}

if ($result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM learning_materials")) {
	$row = mysqli_fetch_assoc($result);
	$materials_count = (int)($row['c'] ?? 0);
	mysqli_free_result($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard - ClassXic</title>
	<link rel="stylesheet" href="css/modules.css">
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
			<span><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
			<img src="Images/user-svgrepo-com.svg" alt="User Icon">
		</div>
	</nav>

	<!-- Sidebar -->
	<div class="sidebar" id="sidebar">
		<div class="sidebar-content">
			<ul class="sidebar-nav">
            <li><a href="admin.php"><img src="Images/dashboard-svgrepo-com.svg" alt="Home Icon">Dashboard</a></li>
        <li><a href=""><img src="Images/user-svgrepo-com.svg" alt="Features Icon">Users</a></li>
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

	<!-- Main Content -->
	<main class="main-content">
		<div class="dashboard-wrapper">
			<div class="page-title">Dashboard</div>
			<div class="stats-grid">
				<div class="stat-card">
					<div class="stat-icon">
						<img src="Images/user-svgrepo-com.svg" alt="User Count">
					</div>
					<div class="stat-content">
						<div class="stat-number"><?php echo number_format($user_count); ?></div>
						<div class="stat-label">User</div>
					</div>
				</div>
				<div class="stat-card">
					<div class="stat-icon books">
						<img src="Images/book-svgrepo-com.svg" alt="Materials Count">
					</div>
					<div class="stat-content">
						<div class="stat-number"><?php echo number_format($materials_count); ?></div>
						<div class="stat-label">Learning Materials</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<script src="script/admin.js"></script>
</body>
</html>

