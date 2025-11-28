<?php
include "myconnector.php";
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Initialize filters and pagination
$filter_role = isset($_GET['role']) ? $_GET['role'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 6; // Show 6 users per page
$offset = ($page - 1) * $per_page;

// Build the WHERE clause based on filters
$where_conditions = [];
$params = [];
$types = "";

if (!empty($filter_role)) {
	$where_conditions[] = "role = ?";
	$params[] = $filter_role;
	$types .= "s";
}

if (!empty($search_query)) {
	$where_conditions[] = "(first_name LIKE ? OR last_name LIKE ? OR username LIKE ? OR email LIKE ?)";
	$search_param = "%{$search_query}%";
	$params[] = $search_param;
	$params[] = $search_param;
	$params[] = $search_param;
	$params[] = $search_param;
	$types .= "ssss";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM users {$where_clause}";
$total_users = 0;
if (!empty($where_clause)) {
	$count_stmt = $conn->prepare($count_query);
	if (!empty($params)) {
		$count_stmt->bind_param($types, ...$params);
	}
	$count_stmt->execute();
	$count_result = $count_stmt->get_result();
	$total_users = $count_result->fetch_assoc()['total'];
	$count_stmt->close();
} else {
	$count_result = mysqli_query($conn, $count_query);
	$total_users = mysqli_fetch_assoc($count_result)['total'];
	mysqli_free_result($count_result);
}

// Fetch users with pagination
$users_query = "SELECT user_id, username, role, first_name, last_name, email, 
                contact_number, address, date_of_birth, created_at
                FROM users
                {$where_clause}
                ORDER BY created_at DESC
                LIMIT {$per_page} OFFSET {$offset}";

$users_list = [];
if (!empty($where_clause)) {
	$stmt = $conn->prepare($users_query);
	if (!empty($params)) {
		$stmt->bind_param($types, ...$params);
	}
	$stmt->execute();
	$users_result = $stmt->get_result();
	while ($row = $users_result->fetch_assoc()) {
		$users_list[] = $row;
	}
	$stmt->close();
} else {
	$users_result = mysqli_query($conn, $users_query);
	if ($users_result) {
		while ($row = mysqli_fetch_assoc($users_result)) {
			$users_list[] = $row;
		}
		mysqli_free_result($users_result);
	}
}

// Calculate pagination
$total_pages = ceil($total_users / $per_page);

// Calculate statistics (always get all-time stats, not filtered)
$role_distribution = ['student' => 0, 'admin' => 0];

// Get all-time stats (without filters)
$stats_query = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN role = 'student' THEN 1 ELSE 0 END) as students,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins
                FROM users";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
mysqli_free_result($stats_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Management - ClassXic</title>
	<link rel="stylesheet" href="css/admin.css">
	<link rel="stylesheet" href="css/admin_users.css">
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
				<li><a href="admin_users.php" class="active"><img src="Images/user-svgrepo-com.svg" alt="Features Icon">Users</a></li>
				<li><a href="admin_modules.php"><img src="Images/book-svgrepo-com.svg" alt="About-Us Icon">Modules</a></li>
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
		<div class="users-wrapper">
			<div class="page-header">
				<div>
					<h1 class="page-title">User Management</h1>
					<p class="page-subtitle">Manage student and admin accounts</p>
				</div>
				<button class="btn-add-user" onclick="openAddUserModal()">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="8.5" cy="7" r="4"></circle>
						<line x1="20" y1="8" x2="20" y2="14"></line>
						<line x1="23" y1="11" x2="17" y2="11"></line>
					</svg>
					Add New User
				</button>
			</div>

			<!-- Statistics Cards -->
			<div class="stats-row">
				<div class="stat-box">
					<div class="stat-box-icon total">
						<img src="Images/users-svgrepo-com.svg" alt="Total">
					</div>
					<div class="stat-box-content">
						<div class="stat-box-number"><?php echo $stats['total']; ?></div>
						<div class="stat-box-label">Total Users</div>
					</div>
				</div>
				<div class="stat-box">
					<div class="stat-box-icon students">
						<img src="Images/user-svgrepo-com.svg" alt="Students">
					</div>
					<div class="stat-box-content">
						<div class="stat-box-number"><?php echo $stats['students']; ?></div>
						<div class="stat-box-label">Students</div>
					</div>
				</div>
				<div class="stat-box">
					<div class="stat-box-icon admins">
						<img src="Images/dashboard-svgrepo-com.svg" alt="Admins">
					</div>
					<div class="stat-box-content">
						<div class="stat-box-number"><?php echo $stats['admins']; ?></div>
						<div class="stat-box-label">Admins</div>
					</div>
				</div>
			</div>

			<!-- Filters and Search -->
			<div class="filters-card">
				<form method="GET" action="admin_users.php" class="filters-form" id="filterForm">
					<div class="filter-group">
						<label for="search">Search</label>
						<input type="text" id="search" name="search" placeholder="Search by name, username, or email..." value="<?php echo htmlspecialchars($search_query); ?>">
					</div>
					<div class="filter-group">
						<label for="role">Filter by Role</label>
						<select id="role" name="role">
							<option value="">All Roles</option>
							<option value="student" <?php echo $filter_role == 'student' ? 'selected' : ''; ?>>Student</option>
							<option value="admin" <?php echo $filter_role == 'admin' ? 'selected' : ''; ?>>Admin</option>
						</select>
					</div>
					<div class="filter-actions">
						<button type="submit" class="btn-filter">Apply Filters</button>
						<a href="admin_users.php" class="btn-reset">Reset</a>
					</div>
				</form>
			</div>

			<!-- Users List -->
			<div class="users-list-section">
				<div class="section-header">
					<h2 class="section-title">Users (<?php echo $total_users; ?> total)</h2>
					<p class="section-subtitle">Showing <?php echo count($users_list); ?> of <?php echo $total_users; ?> users</p>
				</div>

				<?php if (count($users_list) > 0): ?>
					<div class="users-grid">
						<?php foreach ($users_list as $user): ?>
							<div class="user-card" data-user-id="<?php echo $user['user_id']; ?>">
								<div class="user-card-header">
									<div class="user-avatar-large">
										<img src="Images/user-svgrepo-com.svg" alt="User">
									</div>
									<div class="user-badges">
										<span class="badge badge-<?php echo $user['role']; ?>">
											<?php echo ucfirst($user['role']); ?>
										</span>
									</div>
								</div>
								
								<div class="user-card-body">
									<h3 class="user-card-name">
										<?php 
											$name = trim($user['first_name'] . ' ' . $user['last_name']);
											echo htmlspecialchars($name ?: $user['username']); 
										?>
									</h3>
									<p class="user-card-username">@<?php echo htmlspecialchars($user['username']); ?></p>
									
									<div class="user-card-info">
										<div class="info-item">
											<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
												<polyline points="22,6 12,13 2,6"></polyline>
											</svg>
											<span><?php echo htmlspecialchars($user['email']); ?></span>
										</div>
										<?php if (!empty($user['contact_number'])): ?>
											<div class="info-item">
												<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
													<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
												</svg>
												<span><?php echo htmlspecialchars($user['contact_number']); ?></span>
											</div>
										<?php endif; ?>
										<div class="info-item">
											<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
												<line x1="16" y1="2" x2="16" y2="6"></line>
												<line x1="8" y1="2" x2="8" y2="6"></line>
												<line x1="3" y1="10" x2="21" y2="10"></line>
											</svg>
											<span>Joined <?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
										</div>
									</div>
								</div>
								
								<div class="user-card-actions">
									<button class="btn-action-card btn-view" onclick="viewUser(<?php echo $user['user_id']; ?>)" title="View Details">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
											<circle cx="12" cy="12" r="3"></circle>
										</svg>
										View
									</button>
									<?php if ($user['role'] !== 'admin'): ?>
										<button class="btn-action-card btn-edit" onclick="editUser(<?php echo $user['user_id']; ?>)" title="Edit User">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
												<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
											</svg>
											Edit
										</button>
									<?php endif; ?>
									<?php if ($user['role'] === 'student' && $user['user_id'] != $_SESSION['user_id']): ?>
										<button class="btn-action-card btn-delete" onclick="deleteUser(<?php echo $user['user_id']; ?>)" title="Delete User">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<polyline points="3 6 5 6 21 6"></polyline>
												<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
											</svg>
											Delete
										</button>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<!-- Pagination -->
					<?php if ($total_pages > 1): ?>
						<div class="pagination-container">
							<div class="pagination-info">
								Showing page <?php echo $page; ?> of <?php echo $total_pages; ?>
							</div>
							<div class="pagination">
								<?php if ($page > 1): ?>
									<a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="pagination-btn">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<polyline points="15 18 9 12 15 6"></polyline>
										</svg>
										Previous
									</a>
								<?php endif; ?>

								<?php
								$start_page = max(1, $page - 2);
								$end_page = min($total_pages, $page + 2);
								
								for ($i = $start_page; $i <= $end_page; $i++):
								?>
									<a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
									   class="pagination-btn <?php echo $i == $page ? 'active' : ''; ?>">
										<?php echo $i; ?>
									</a>
								<?php endfor; ?>

								<?php if ($page < $total_pages): ?>
									<a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="pagination-btn">
										Next
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<polyline points="9 18 15 12 9 6"></polyline>
										</svg>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="empty-state">
						<img src="Images/user-svgrepo-com.svg" alt="No Users" class="empty-icon">
						<h3>No Users Found</h3>
						<p>
							<?php if (!empty($search_query) || !empty($filter_role)): ?>
								Try adjusting your filters or search query.
							<?php else: ?>
								No users exist in the system yet.
							<?php endif; ?>
						</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</main>

	<!-- View User Modal -->
	<div id="viewModal" class="modal-overlay">
		<div class="modal-container">
			<div class="modal-header">
				<h3>User Details</h3>
				<button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
			</div>
			<div class="modal-body" id="viewModalContent">
				<!-- Content will be loaded dynamically -->
			</div>
		</div>
	</div>

	<!-- Edit User Modal -->
	<div id="editModal" class="modal-overlay">
		<div class="modal-container">
			<div class="modal-header">
				<h3>Edit User</h3>
				<button class="modal-close" onclick="closeModal('editModal')">&times;</button>
			</div>
			<div class="modal-body">
				<form id="editUserForm" onsubmit="submitEditUser(event)">
					<input type="hidden" id="edit_user_id" name="user_id">
					
					<div class="form-row">
						<div class="form-group">
							<label for="edit_first_name">First Name</label>
							<input type="text" id="edit_first_name" name="first_name" required>
						</div>
						<div class="form-group">
							<label for="edit_last_name">Last Name</label>
							<input type="text" id="edit_last_name" name="last_name" required>
						</div>
					</div>
					
					<div class="form-group">
						<label for="edit_email">Email</label>
						<input type="email" id="edit_email" name="email" required>
					</div>
					
					<div class="form-group">
						<label for="edit_role">Role</label>
						<select id="edit_role" name="role" required>
							<option value="student">Student</option>
							<option value="admin">Admin</option>
						</select>
					</div>
					
					<div class="form-group">
						<label for="edit_contact">Contact Number</label>
						<input type="text" id="edit_contact" name="contact_number">
					</div>
					
					<div class="form-group">
						<label for="edit_address">Address</label>
						<textarea id="edit_address" name="address" rows="3"></textarea>
					</div>
					
					<div class="form-actions">
						<button type="button" class="btn-cancel" onclick="closeModal('editModal')">Cancel</button>
						<button type="submit" class="btn-save">Save Changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="script/admin.js"></script>
	<script src="script/admin_users.js"></script>
</body>
</html>

