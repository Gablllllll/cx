<?php
include "myconnector.php";
session_start();

// Initialize filters
$filter_rating = isset($_GET['rating']) ? $_GET['rating'] : '';
$filter_material = isset($_GET['material']) ? $_GET['material'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Build the WHERE clause based on filters
$where_conditions = [];
$params = [];
$types = "";

if (!empty($filter_rating)) {
	$where_conditions[] = "f.rating = ?";
	$params[] = $filter_rating;
	$types .= "i";
}

if (!empty($filter_material)) {
	$where_conditions[] = "f.material_id = ?";
	$params[] = $filter_material;
	$types .= "i";
}

if (!empty($search_query)) {
	$where_conditions[] = "(u.first_name LIKE ? OR u.last_name LIKE ? OR u.username LIKE ? OR f.comment LIKE ?)";
	$search_param = "%{$search_query}%";
	$params[] = $search_param;
	$params[] = $search_param;
	$params[] = $search_param;
	$params[] = $search_param;
	$types .= "ssss";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Fetch feedback with user and material details
$feedback_query = "SELECT f.feedback_id, f.rating, f.comment, f.created_at,
                   u.username, u.first_name, u.last_name,
                   lm.title as material_title, f.material_id, f.user_id
                   FROM feedback f
                   JOIN users u ON f.user_id = u.user_id
                   JOIN learning_materials lm ON f.material_id = lm.material_id
                   {$where_clause}
                   ORDER BY f.created_at DESC";

$feedback_list = [];
if (!empty($where_clause)) {
	$stmt = $conn->prepare($feedback_query);
	if (!empty($params)) {
		$stmt->bind_param($types, ...$params);
	}
	$stmt->execute();
	$feedback_result = $stmt->get_result();
	while ($row = $feedback_result->fetch_assoc()) {
		$feedback_list[] = $row;
	}
	$stmt->close();
} else {
	$feedback_result = mysqli_query($conn, $feedback_query);
	if ($feedback_result) {
		while ($row = mysqli_fetch_assoc($feedback_result)) {
			$feedback_list[] = $row;
		}
		mysqli_free_result($feedback_result);
	}
}

// Calculate statistics
$total_feedback = count($feedback_list);
$avg_rating = 0;
$rating_distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

if ($total_feedback > 0) {
	$total_rating = array_sum(array_column($feedback_list, 'rating'));
	$avg_rating = round($total_rating / $total_feedback, 1);
	
	foreach ($feedback_list as $feedback) {
		$rating_distribution[$feedback['rating']]++;
	}
}

// Fetch all materials for filter dropdown
$materials_query = "SELECT material_id, title FROM learning_materials ORDER BY title";
$materials_result = mysqli_query($conn, $materials_query);
$materials_list = [];
if ($materials_result) {
	while ($row = mysqli_fetch_assoc($materials_result)) {
		$materials_list[] = $row;
	}
	mysqli_free_result($materials_result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Feedback Management - ClassXic</title>
	<link rel="stylesheet" href="css/modules.css">
	<link rel="stylesheet" href="css/admin.css">
	<link rel="stylesheet" href="css/admin_feedback.css">
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
				<li><a href="admin_users.php"><img src="Images/user-svgrepo-com.svg" alt="Features Icon">Users</a></li>
				<li><a href="admin_modules.php"><img src="Images/book-svgrepo-com.svg" alt="About-Us Icon">Modules</a></li>
				<li><a href="admin_feedback.php" class="active"><img src="Images/about-filled-svgrepo-com.svg" alt="Feedback Icon">Feedback</a></li>
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
		<div class="feedback-wrapper">
			<div class="page-header">
				<div>
					<h1 class="page-title">Feedback Management</h1>
					<p class="page-subtitle">Monitor and analyze student feedback on learning materials</p>
				</div>
			</div>

			<!-- Statistics Cards -->
			<div class="stats-row">
				<div class="stat-box">
					<div class="stat-box-icon total">
						<img src="Images/about-filled-svgrepo-com.svg" alt="Total">
					</div>
					<div class="stat-box-content">
						<div class="stat-box-number"><?php echo $total_feedback; ?></div>
						<div class="stat-box-label">Total Feedback</div>
					</div>
				</div>
				<div class="stat-box">
					<div class="stat-box-icon average">
						<span class="star-emoji">⭐</span>
					</div>
					<div class="stat-box-content">
						<div class="stat-box-number"><?php echo $avg_rating > 0 ? $avg_rating : 'N/A'; ?></div>
						<div class="stat-box-label">Average Rating</div>
					</div>
				</div>
				<div class="stat-box">
					<div class="stat-box-icon high">
						<span class="emoji">😊</span>
					</div>
					<div class="stat-box-content">
						<div class="stat-box-number"><?php echo $rating_distribution[5] + $rating_distribution[4]; ?></div>
						<div class="stat-box-label">Positive (4-5★)</div>
					</div>
				</div>
				<div class="stat-box">
					<div class="stat-box-icon low">
						<span class="emoji">😟</span>
					</div>
					<div class="stat-box-content">
						<div class="stat-box-number"><?php echo $rating_distribution[1] + $rating_distribution[2]; ?></div>
						<div class="stat-box-label">Needs Attention (1-2★)</div>
					</div>
				</div>
			</div>

			<!-- Rating Distribution Chart -->
			<div class="rating-chart-card">
				<h3 class="chart-title">Rating Distribution</h3>
				<div class="rating-bars">
					<?php for ($i = 5; $i >= 1; $i--): ?>
						<?php 
							$count = $rating_distribution[$i];
							$percentage = $total_feedback > 0 ? ($count / $total_feedback) * 100 : 0;
						?>
						<div class="rating-bar-row">
							<div class="rating-bar-label">
								<?php for ($j = 0; $j < $i; $j++): ?>
									<span class="star-small">★</span>
								<?php endfor; ?>
							</div>
							<div class="rating-bar-container">
								<div class="rating-bar-fill" style="width: <?php echo $percentage; ?>%"></div>
							</div>
							<div class="rating-bar-count"><?php echo $count; ?></div>
						</div>
					<?php endfor; ?>
				</div>
			</div>

			<!-- Filters and Search -->
			<div class="filters-card">
				<form method="GET" action="admin_feedback.php" class="filters-form" id="filterForm">
					<div class="filter-group">
						<label for="search">Search</label>
						<input type="text" id="search" name="search" placeholder="Search by name or comment..." value="<?php echo htmlspecialchars($search_query); ?>">
					</div>
					<div class="filter-group">
						<label for="rating">Filter by Rating</label>
						<select id="rating" name="rating">
							<option value="">All Ratings</option>
							<?php for ($i = 5; $i >= 1; $i--): ?>
								<option value="<?php echo $i; ?>" <?php echo $filter_rating == $i ? 'selected' : ''; ?>>
									<?php echo str_repeat('★', $i); ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="filter-group">
						<label for="material">Filter by Material</label>
						<select id="material" name="material">
							<option value="">All Materials</option>
							<?php foreach ($materials_list as $material): ?>
								<option value="<?php echo $material['material_id']; ?>" <?php echo $filter_material == $material['material_id'] ? 'selected' : ''; ?>>
									<?php echo htmlspecialchars($material['title']); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="filter-actions">
						<button type="submit" class="btn-filter">Apply Filters</button>
						<a href="admin_feedback.php" class="btn-reset">Reset</a>
					</div>
				</form>
			</div>

			<!-- Feedback List -->
			<div class="feedback-list-section">
				<div class="section-header">
					<h2 class="section-title">All Feedback (<?php echo $total_feedback; ?>)</h2>
				</div>

				<?php if ($total_feedback > 0): ?>
					<div class="feedback-table-container">
						<table class="feedback-table">
							<thead>
								<tr>
									<th>Student</th>
									<th>Material</th>
									<th>Rating</th>
									<th>Comment</th>
									<th>Date</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($feedback_list as $feedback): ?>
									<tr class="feedback-row" data-feedback-id="<?php echo $feedback['feedback_id']; ?>">
										<td>
											<div class="student-info">
												<div class="student-avatar">
													<img src="Images/user-svgrepo-com.svg" alt="Student">
												</div>
												<div class="student-details">
													<div class="student-name">
														<?php 
															$name = trim($feedback['first_name'] . ' ' . $feedback['last_name']);
															echo htmlspecialchars($name ?: $feedback['username']); 
														?>
													</div>
													<div class="student-username">@<?php echo htmlspecialchars($feedback['username']); ?></div>
												</div>
											</div>
										</td>
										<td>
											<div class="material-info">
												<?php echo htmlspecialchars($feedback['material_title']); ?>
											</div>
										</td>
										<td>
											<div class="rating-stars">
												<?php for ($i = 1; $i <= 5; $i++): ?>
													<span class="star <?php echo $i <= $feedback['rating'] ? 'filled' : ''; ?>">★</span>
												<?php endfor; ?>
												<span class="rating-number">(<?php echo $feedback['rating']; ?>)</span>
											</div>
										</td>
										<td>
											<div class="comment-cell">
												<?php if (!empty(trim($feedback['comment']))): ?>
													<p class="comment-text"><?php echo htmlspecialchars($feedback['comment']); ?></p>
												<?php else: ?>
													<span class="no-comment">No comment</span>
												<?php endif; ?>
											</div>
										</td>
										<td>
											<div class="date-cell">
												<?php 
													$date = new DateTime($feedback['created_at']);
													echo $date->format('M d, Y');
												?>
												<div class="time-cell"><?php echo $date->format('g:i A'); ?></div>
											</div>
										</td>
										<td>
											<div class="action-buttons">
												<button class="btn-action btn-view" onclick="viewFeedback(<?php echo $feedback['feedback_id']; ?>)" title="View Details">
													<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
														<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
														<circle cx="12" cy="12" r="3"></circle>
													</svg>
												</button>
												<button class="btn-action btn-delete" onclick="deleteFeedback(<?php echo $feedback['feedback_id']; ?>)" title="Delete">
													<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
														<polyline points="3 6 5 6 21 6"></polyline>
														<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
													</svg>
												</button>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php else: ?>
					<div class="empty-state">
						<img src="Images/about-filled-svgrepo-com.svg" alt="No Feedback" class="empty-icon">
						<h3>No Feedback Found</h3>
						<p>
							<?php if (!empty($search_query) || !empty($filter_rating) || !empty($filter_material)): ?>
								Try adjusting your filters or search query.
							<?php else: ?>
								No feedback has been submitted yet.
							<?php endif; ?>
						</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</main>

	<!-- View Feedback Modal -->
	<div id="viewModal" class="modal-overlay">
		<div class="modal-container">
			<div class="modal-header">
				<h3>Feedback Details</h3>
				<button class="modal-close" onclick="closeModal()">&times;</button>
			</div>
			<div class="modal-body" id="modalContent">
				<!-- Content will be loaded dynamically -->
			</div>
		</div>
	</div>

	<script src="script/admin.js"></script>
	<script src="script/admin_feedback.js"></script>
</body>
</html>

