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

// Fetch feedback with user and material details (limit to 6 for dashboard)
$feedback_query = "SELECT f.feedback_id, f.rating, f.comment, f.created_at,
                   u.username, u.first_name, u.last_name,
                   lm.title as material_title
                   FROM feedback f
                   JOIN users u ON f.user_id = u.user_id
                   JOIN learning_materials lm ON f.material_id = lm.material_id
                   ORDER BY f.created_at DESC
                   LIMIT 6";
$feedback_result = mysqli_query($conn, $feedback_query);
$feedback_list = [];
if ($feedback_result) {
	while ($row = mysqli_fetch_assoc($feedback_result)) {
		$feedback_list[] = $row;
	}
	mysqli_free_result($feedback_result);
}

// Get total feedback count for "Show More" functionality
$total_feedback_query = "SELECT COUNT(*) as total FROM feedback";
$total_feedback_result = mysqli_query($conn, $total_feedback_query);
$total_feedback_count = 0;
if ($total_feedback_result) {
	$row = mysqli_fetch_assoc($total_feedback_result);
	$total_feedback_count = (int)$row['total'];
	mysqli_free_result($total_feedback_result);
}

// Calculate average rating if feedback exists
$avg_rating = 0;
if (count($feedback_list) > 0) {
	$total_rating = array_sum(array_column($feedback_list, 'rating'));
	$avg_rating = round($total_rating / count($feedback_list), 1);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard - ClassXic</title>
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
				<li><a href="admin_users.php"><img src="Images/user-svgrepo-com.svg" alt="Users Icon">Users</a></li>
				<li><a href="admin_modules.php"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon">Modules</a></li>
				<li><a href="admin_feedback.php"><img src="Images/about-filled-svgrepo-com.svg" alt="Feedback Icon">Feedback</a></li>
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
				<a href="admin_users.php" class="stat-card stat-card-link">
					<div class="stat-icon">
						<img src="Images/user-svgrepo-com.svg" alt="User Count">
					</div>
					<div class="stat-content">
						<div class="stat-number"><?php echo number_format($user_count); ?></div>
						<div class="stat-label">User</div>
					</div>
				</a>
				<a href="admin_modules.php" class="stat-card stat-card-link">
					<div class="stat-icon books">
						<img src="Images/book-svgrepo-com.svg" alt="Materials Count">
					</div>
					<div class="stat-content">
						<div class="stat-number"><?php echo number_format($materials_count); ?></div>
						<div class="stat-label">Learning Materials</div>
					</div>
				</a>
				<a href="admin_feedback.php" class="stat-card stat-card-link">
					<div class="stat-icon feedback">
						<img src="Images/about-filled-svgrepo-com.svg" alt="Feedback Count">
					</div>
					<div class="stat-content">
						<div class="stat-number"><?php echo count($feedback_list); ?></div>
						<div class="stat-label">Total Feedback</div>
					</div>
				</a>
				<a href="admin_feedback.php" class="stat-card stat-card-link">
					<div class="stat-icon rating">
						<span class="star-icon">⭐</span>
					</div>
					<div class="stat-content">
						<div class="stat-number"><?php echo $avg_rating > 0 ? $avg_rating : 'N/A'; ?></div>
						<div class="stat-label">Average Rating</div>
					</div>
				</a>
			</div>

			<!-- Feedback Section -->
			<div class="feedback-section">
				<div class="section-header">
					<h2 class="section-title">Student Feedback</h2>
					<?php if ($total_feedback_count > 6): ?>
						<a href="admin_feedback.php" class="view-all-btn">
							View All Feedback
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M7 17L17 7M17 7H7M17 7V17"></path>
							</svg>
						</a>
					<?php endif; ?>
				</div>
				<?php if (count($feedback_list) > 0): ?>
					<div class="feedback-grid">
						<?php foreach ($feedback_list as $feedback): ?>
							<div class="feedback-card" onclick="openFeedbackModal(<?php echo htmlspecialchars(json_encode($feedback)); ?>)">
								<div class="feedback-header">
									<div class="feedback-user">
										<img src="Images/user-svgrepo-com.svg" alt="User" class="user-avatar">
										<div class="user-details">
											<div class="user-name">
												<?php 
													$name = trim($feedback['first_name'] . ' ' . $feedback['last_name']);
													echo htmlspecialchars($name ?: $feedback['username']); 
												?>
											</div>
											<div class="material-name"><?php echo htmlspecialchars($feedback['material_title']); ?></div>
										</div>
									</div>
									<div class="feedback-rating">
										<?php for ($i = 1; $i <= 5; $i++): ?>
											<span class="star <?php echo $i <= $feedback['rating'] ? 'filled' : ''; ?>">★</span>
										<?php endfor; ?>
									</div>
								</div>
								<?php if (!empty(trim($feedback['comment']))): ?>
									<div class="feedback-comment">
										<p><?php echo htmlspecialchars(strlen($feedback['comment']) > 100 ? substr($feedback['comment'], 0, 100) . '...' : $feedback['comment']); ?></p>
										<?php if (strlen($feedback['comment']) > 100): ?>
											<span class="read-more">Click to read more</span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<div class="feedback-date">
									<?php 
										$date = new DateTime($feedback['created_at']);
										echo $date->format('M d, Y \a\t g:i A');
									?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<?php if ($total_feedback_count > 6): ?>
						<div class="show-more-section">
							<p class="showing-info">Showing 6 of <?php echo $total_feedback_count; ?> feedback entries</p>
							<a href="admin_feedback.php" class="show-more-btn">
								Show More Feedback
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M7 17L17 7M17 7H7M17 7V17"></path>
								</svg>
							</a>
						</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="no-feedback">
						<img src="Images/about-filled-svgrepo-com.svg" alt="No Feedback" class="no-feedback-icon">
						<p>No feedback received yet.</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</main>

	<!-- Feedback Modal -->
	<div class="modal-overlay" id="feedbackModal">
		<div class="modal-container">
			<div class="modal-header">
				<h3>Feedback Details</h3>
				<button class="modal-close" onclick="closeFeedbackModal()">&times;</button>
			</div>
			<div class="modal-body">
				<div class="feedback-modal-content">
					<div class="feedback-modal-header">
						<div class="feedback-modal-user">
							<img src="Images/user-svgrepo-com.svg" alt="User" class="modal-user-avatar">
							<div class="modal-user-details">
								<div class="modal-user-name" id="modalUserName"></div>
								<div class="modal-material-name" id="modalMaterialName"></div>
							</div>
						</div>
						<div class="feedback-modal-rating" id="modalRating"></div>
					</div>
					<div class="feedback-modal-comment">
						<h4>Comment:</h4>
						<p id="modalComment"></p>
					</div>
					<div class="feedback-modal-date" id="modalDate"></div>
				</div>
			</div>
		</div>
	</div>

	<script src="script/admin.js"></script>
</body>
</html>

