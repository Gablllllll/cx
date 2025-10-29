<?php
include "myconnector.php";
session_start();

// Handle profile updates
$profile_update_success = "";
$profile_update_error = "";

if (isset($_POST['update_profile'])) {
    $user_id = $_SESSION['user_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $date_of_birth = trim($_POST['date_of_birth']);
    
    // Handle password change if provided
    $password_update = "";
    if (!empty($_POST['new_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Verify current password
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if (!password_verify($current_password, $user['password_hash'])) {
            $profile_update_error = "Current password is incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $profile_update_error = "New passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $profile_update_error = "New password must be at least 6 characters long.";
        } else {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $password_update = $password_hash;
        }
    }
    
    // Update user profile
    if (empty($profile_update_error)) {
        if (!empty($password_update)) {
            $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, contact_number=?, address=?, date_of_birth=?, password_hash=? WHERE user_id=?");
            $stmt->bind_param("sssssssi", $first_name, $last_name, $email, $contact_number, $address, $date_of_birth, $password_update, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, contact_number=?, address=?, date_of_birth=? WHERE user_id=?");
            $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $contact_number, $address, $date_of_birth, $user_id);
        }
        
        if ($stmt->execute()) {
            // Update session data
            $_SESSION['first_name'] = $first_name;
            if (!empty($password_update)) {
                $profile_update_success = "Profile and password updated successfully!";
            } else {
                $profile_update_success = "Profile updated successfully!";
            }
        } else {
            $profile_update_error = "Failed to update profile. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch user data for profile display
$user_data = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT username, first_name, last_name, email, contact_number, address, date_of_birth FROM users WHERE user_id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Modules - ClassXic</title>

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
        <div class="user-info" onclick="toggleUserDropdown()" style="cursor: pointer;">
            <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
            <img src="Images/user-svgrepo-com.svg" alt="User Icon">
            <div class="user-dropdown" id="userDropdown">
                <a href="#" onclick="openProfileModal()">Profile Information</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-wrapper">
            <div class="page-header">
                <h1>Student Modules</h1>
                <p>Access learning materials and resources</p>
            </div>

            <!-- Module List Container -->
            <?php
            $query = "SELECT l.material_id, l.title, l.description, l.file_url, l.upload_date, u.first_name, u.last_name 
                      FROM learning_materials l 
                      LEFT JOIN users u ON l.uploaded_by_id = u.user_id 
                      ORDER BY l.upload_date DESC";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<div class="module-list">';
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="module-item">';
                    
                    // Module Header
                    echo '<div class="module-header">';
                    echo '  <div class="module-user">';
                    echo '      <img src="Images/user-svgrepo-com.svg" alt="User Icon" class="module-user-icon">';
                    echo '      <span class="module-user-name">' . htmlspecialchars($row['first_name']) . '</span>';
                    echo '      <img src="Images/verified.png" alt="Verified" class="module-verified-icon">';
                    echo '  </div>';
                    echo '  <div class="module-date">' . date("F j, Y", strtotime($row['upload_date'])) . '</div>';
                    echo '</div>';
                    
                    // Module Body
                    echo '<div class="module-body">';
                    echo '  <div class="module-title">' . htmlspecialchars($row['title']) . '</div>';
                    if (!empty($row['description'])) {
                        echo '  <div class="module-description">' . htmlspecialchars($row['description']) . '</div>';
                    }
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
                echo '  <p>No learning materials are currently available. Check back later for new content.</p>';
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
                            <li><a href="#" onclick="showSettings()"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
                            <li><a href="logout.php"><img src="Images/logout-svgrepo-com.svg" alt="Logout Icon">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Profile Information Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content profile-modal-content">
            <span id="close-profile-modal">&times;</span>
            <h2>My Profile</h2>
            
            <?php if ($profile_update_success): ?>
                <div class="alert alert-success"><?php echo $profile_update_success; ?></div>
            <?php endif; ?>
            
            <?php if ($profile_update_error): ?>
                <div class="alert alert-danger"><?php echo $profile_update_error; ?></div>
            <?php endif; ?>
            
            <div class="profile-tabs">
                <button class="tab-button active" onclick="showTab('view', this)">View Profile</button>
                <button class="tab-button" onclick="showTab('edit', this)">Edit Profile</button>
            </div>
            
            <!-- View Profile Tab -->
            <div id="view-tab" class="tab-content active">
                <div class="profile-info">
                    <div class="info-row">
                        <label>Username:</label>
                        <span><?php echo htmlspecialchars($user_data['username'] ?? ''); ?></span>
                    </div>
                    <div class="info-row">
                        <label>First Name:</label>
                        <span><?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?></span>
                    </div>
                    <div class="info-row">
                        <label>Last Name:</label>
                        <span><?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?></span>
                    </div>
                    <div class="info-row">
                        <label>Email:</label>
                        <span><?php echo htmlspecialchars($user_data['email'] ?? ''); ?></span>
                    </div>
                    <div class="info-row">
                        <label>Contact Number:</label>
                        <span><?php echo htmlspecialchars($user_data['contact_number'] ?? ''); ?></span>
                    </div>
                    <div class="info-row">
                        <label>Address:</label>
                        <span><?php echo htmlspecialchars($user_data['address'] ?? ''); ?></span>
                    </div>
                    <div class="info-row">
                        <label>Date of Birth:</label>
                        <span><?php echo htmlspecialchars($user_data['date_of_birth'] ?? ''); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Edit Profile Tab -->
            <div id="edit-tab" class="tab-content">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_number">Contact Number:</label>
                        <input type="tel" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user_data['contact_number'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($user_data['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user_data['date_of_birth'] ?? ''); ?>">
                    </div>
                    
                    <!-- Password Change Section -->
                    <div class="password-section">
                        <h4>Change Password (Optional)</h4>
                        
                        <div class="form-group">
                            <label for="current_password">Current Password:</label>
                            <input type="password" id="current_password" name="current_password" placeholder="Enter current password">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password:</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Enter new password (min 6 characters)">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="showTab('view')">Cancel</button>
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="script/landingpage.js"></script>
    <script src="script/studentmodule.js"></script>
    
    <script>
        // Profile Modal Functions
        function openProfileModal() {
            document.getElementById('profileModal').style.display = 'flex';
        }
        
        function closeProfileModal() {
            document.getElementById('profileModal').style.display = 'none';
        }
        
        function showTab(tabName, button) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab content
            const targetTab = document.getElementById(tabName + '-tab');
            if (targetTab) targetTab.classList.add('active');
            
            // Add active class to the corresponding tab button
            const targetButton = (button && button.classList && button.classList.contains('tab-button'))
                ? button
                : document.querySelector('.tab-button[onclick*="' + tabName + '"]');
            if (targetButton) targetButton.classList.add('active');
        }
        
        // Close modal when clicking on X
        document.getElementById('close-profile-modal').onclick = closeProfileModal;
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const profileModal = document.getElementById('profileModal');
            if (event.target === profileModal) {
                closeProfileModal();
            }
        }
        
        // Show profile modal if there are update messages
        <?php if ($profile_update_success || $profile_update_error): ?>
            document.addEventListener('DOMContentLoaded', function() {
                openProfileModal();
                
                // Auto-hide success message after 3 seconds
                const successAlert = document.querySelector('.alert-success');
                if (successAlert) {
                    setTimeout(function() {
                        successAlert.style.opacity = '0';
                        successAlert.style.transform = 'translateY(-10px)';
                        setTimeout(function() {
                            successAlert.style.display = 'none';
                        }, 300);
                    }, 3000);
                }
                
                // Auto-hide error message after 5 seconds
                const errorAlert = document.querySelector('.alert-danger');
                if (errorAlert) {
                    setTimeout(function() {
                        errorAlert.style.opacity = '0';
                        errorAlert.style.transform = 'translateY(-10px)';
                        setTimeout(function() {
                            errorAlert.style.display = 'none';
                        }, 300);
                    }, 5000);
                }
            });
        <?php endif; ?>
        
        // Password validation
        document.addEventListener('DOMContentLoaded', function() {
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (confirmPassword) {
                confirmPassword.addEventListener('input', function() {
                    if (newPassword.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity('Passwords do not match');
                        confirmPassword.style.borderColor = '#f56565';
                    } else {
                        confirmPassword.setCustomValidity('');
                        confirmPassword.style.borderColor = '#e2e8f0';
                    }
                });
            }
            
            if (newPassword) {
                newPassword.addEventListener('input', function() {
                    if (newPassword.value.length > 0 && newPassword.value.length < 6) {
                        newPassword.setCustomValidity('Password must be at least 6 characters long');
                        newPassword.style.borderColor = '#f56565';
                    } else {
                        newPassword.setCustomValidity('');
                        newPassword.style.borderColor = '#e2e8f0';
                    }
                    
                    // Re-validate confirm password if it has a value
                    if (confirmPassword && confirmPassword.value) {
                        confirmPassword.dispatchEvent(new Event('input'));
                    }
                });
            }
        });
    </script>

    <!-- Settings Modal -->
    <div id="settingsModal" class="modal">
        <div class="modal-content settings-modal-content">
            <span id="close-settings-modal">&times;</span>
            <h2>Settings</h2>
            <div class="settings-section">
                <h3>Font Settings</h3>
                <div class="setting-group">
                    <label for="fontSize">Font Size:</label>
                    <input type="range" id="fontSize" min="12" max="24" value="16" onchange="updateFontSize(this.value)">
                    <span id="fontSizeValue">16px</span>
                </div>
                <div class="setting-group">
                    <label for="lineHeight">Line Height:</label>
                    <input type="range" id="lineHeight" min="1.2" max="2.0" step="0.1" value="1.6" onchange="updateLineHeight(this.value)">
                    <span id="lineHeightValue">1.6</span>
                </div>
            </div>
            
            <div class="settings-actions">
                <button class="btn btn-secondary" onclick="resetSettings()">Reset to Default</button>
                <button class="btn btn-primary" onclick="saveSettings()">Save Settings</button>
            </div>
        </div>
    </div>

    <script>
        // Settings Modal Functions
        function showSettings() {
            document.getElementById('settingsModal').style.display = 'flex';
        }


        function updateFontSize(value) {
            document.documentElement.style.setProperty('--font-size', value + 'px');
            document.getElementById('fontSizeValue').textContent = value + 'px';
        }

        function updateLineHeight(value) {
            document.documentElement.style.setProperty('--line-height', value);
            document.getElementById('lineHeightValue').textContent = value;
        }


        function resetSettings() {
            document.documentElement.style.setProperty('--font-size', '16px');
            document.documentElement.style.setProperty('--line-height', '1.6');
            
            document.getElementById('fontSize').value = 16;
            document.getElementById('lineHeight').value = 1.6;
            
            document.getElementById('fontSizeValue').textContent = '16px';
            document.getElementById('lineHeightValue').textContent = '1.6';
        }

        function saveSettings() {
            const fontSize = document.getElementById('fontSize').value;
            const lineHeight = document.getElementById('lineHeight').value;
            
            // Save to localStorage
            localStorage.setItem('fontSize', fontSize);
            localStorage.setItem('lineHeight', lineHeight);
            
            alert('Settings saved successfully!');
            document.getElementById('settingsModal').style.display = 'none';
        }

        // Close settings modal
        document.getElementById('close-settings-modal').onclick = function() {
            document.getElementById('settingsModal').style.display = 'none';
        }

        // Load saved settings on page load
        window.onload = function() {
            const savedFontSize = localStorage.getItem('fontSize');
            const savedLineHeight = localStorage.getItem('lineHeight');
            
            if (savedFontSize) {
                document.documentElement.style.setProperty('--font-size', savedFontSize + 'px');
                document.getElementById('fontSize').value = savedFontSize;
                document.getElementById('fontSizeValue').textContent = savedFontSize + 'px';
            }
            
            if (savedLineHeight) {
                document.documentElement.style.setProperty('--line-height', savedLineHeight);
                document.getElementById('lineHeight').value = savedLineHeight;
                document.getElementById('lineHeightValue').textContent = savedLineHeight;
            }
        }
    </script>

</body>
</html>