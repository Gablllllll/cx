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
    <title>ClassXic</title>
    <link rel="stylesheet" href="css/landingpage.css">
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

    <main class="main">
        <div class="left-section">
            <div class="hero-badge">
                <span> Welcome to ClassXic Learning</span>
            </div>
            <h2>
                <span class="green">Welcome to</span> <span style="color: #333;">ClassXic</span> <span class="green">learning</span><br /> 
                <span class="highlight"> Management System</span>
            </h2>
            <p>
                ClassXic is a specialized Learning Management System designed to support 
                students with dyslexia and other learning differences. Our platform 
                provides accessible learning materials, assistive technologies, and 
                dyslexia-friendly features to help every student succeed in their 
                educational journey.
            </p>
            <div class="cta-buttons">
                <a class="hire-button primary-btn" href="studentmodule.php">
                    <span>Get Started</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <button class="secondary-btn" id="learnMoreBtn">
                    <span>Learn More</span>
                </button>
            </div>
        </div>
        <div class="right-section">
            <div class="image-container">
                <img src="Images/main.png" alt="Students learning together" class="students-image" />
                <div class="floating-card card-1">
                    <div class="card-icon">📚</div>
                    <div class="card-content">
                        <h4>Interactive Learning</h4>
                        <p>Engage with dynamic content</p>
                    </div>
                </div>
                <div class="floating-card card-2">
                    <div class="card-icon">🎯</div>
                    <div class="card-content">
                        <h4>Personalized Path</h4>
                        <p>Tailored to your needs</p>
                    </div>
                </div>
                <div class="floating-card card-3">
                    <div class="card-icon">🤝</div>
                    <div class="card-content">
                        <h4>Accessible</h4>
                        <p>Assistive Technologies</p>
                    </div>
                </div>
            </div>
            <h3 class="curve-text">Learning <span class="accent">Connects</span> Us All.</h3>
            <p class="subtitle">Empowering every student to reach their full potential</p>
        </div>
    </main>
    <!-- Classix's Features -->
    <div class="features-section" id="features-section">
        <h1>ClassXic's Features</h1>
       <div class="features-container">
            <div class="feature-box"
                 data-title="Dyslexic Friendly Formatting"
                 data-description="Uses OpenDyslexic fonts, increased line spacing, and appropriate color scheme to reduce visual stress for dyslexic students."
                 data-image="Images/opend.jpeg">
                <img src="Images/bald-head-with-puzzle-brain-svgrepo-com.svg" alt="Dyslexia Icon" class="feature-icon">
                <div class="feature-title">Dyslexic Friendly-<br>Formatting</div>
            </div>
            <div class="feature-box"
                 data-title="Text to Speech Technology"
                 data-description="Reads aloud on-screen text to assist with reading comprehension and reduce the difficulty of decoding written content."
                 data-image="Images/text.png">
                <img src="Images/text-to-speech.png" alt="Text to Speech Icon" class="feature-icon">
                <div class="feature-title">Text to Speech<br>Technology</div>
            </div>
            <div class="feature-box"
                 data-title="Phonetics"
                 data-description="Provides visual and audio cues for correct pronunciation and sound breakdown of words, helping improve reading fluency and vocabulary imbued a dictionary and its meaning."
                 data-image="Images/phone.jpg">
                <img src="Images/pronunciation.png" alt="Phonetics Icon" class="feature-icon">
                <div class="feature-title">Phonetics</div>
            </div>
        </div>
        <!-- Modal -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span id="close-modal">&times;</span>
                <h2 id="modal-title"></h2>
                <img id="modal-img" src="" alt="" style="max-width:300px; display:block; margin:0 auto 16px auto;">
                <p id="modal-text"></p>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
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
                <button class="tab-button active" onclick="showTab('view')">View Profile</button>
                <button class="tab-button" onclick="showTab('edit')">Edit Profile</button>
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
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user_data['contact_number'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_data['address'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user_data['date_of_birth'] ?? ''); ?>">
                    </div>

                    <div class="password-section">
                        <h4>Change Password (optional)</h4>
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" placeholder="Enter current password">
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="At least 6 characters">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-type new password">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="about-section" id="about-us">
        <div class="about-text">
            <h1>About Us.</h1>
            <p>
            ClassXic is a dyslexic-friendly LMS is an accessible learning platform designed to support students with dyslexia.
             It features readable fonts, clear navigation, minimal distractions, high-contrast themes, and text-to-speech options to enhance 
             comprehension and create an inclusive educational environment.
            </p>
        </div>
        <div class="about-image">
            <img src="Images/main.png" alt="OpenDyslexic Font Example">
        </div>
    </div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-burger" onclick="toggleSidebar()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-nav">
                <li><a href="landingpage.php"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
                <li><a href="studentmodule.php"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
            </ul>
            
            <!-- Small Calendar -->
            <div class="sidebar-calendar">
                <div class="calendar-header">
                    <button class="calendar-nav" id="prevMonth">&lt;</button>
                    <span class="calendar-month-year" id="currentMonthYear"></span>
                    <button class="calendar-nav" id="nextMonth">&gt;</button>
                </div>
                <div class="calendar-grid" id="calendarGrid">
                    <!-- Calendar days will be generated by JavaScript -->
                </div>
            </div>
            
            <div class="sidebar-bottom">
                <ul class="sidebar-options">
                    <li>
                        <a href="#" class="dropdown-toggle"><img src="Images/option.png" alt="Option Icon">Option</a>
                        <ul class="dropdown-menu">
                            <li><a href="#features-section"><img src="Images/idea-svgrepo-com.svg" alt="Features Icon">Features</a></li>
                            <li><a href="#about-us"><img src="Images/about-filled-svgrepo-com.svg" alt="About-Us Icon">About Us</a></li>
                       
                            <li><a href="logout.php"><img src="Images/logout-svgrepo-com.svg" alt="Logout Icon">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <footer class="site-footer">
        <div class="footer-left">
            ClassXic
        </div>
        <div class="footer-right">
            <a href="#"><img src="Images/facebook.png" alt="Facebook"></a>
            <a href="#"><img src="Images/social.png" alt="Instagram"></a>
            <a href="#"><img src="Images/linkedin.png" alt="LinkedIn"></a>
            <a href="#"><img src="Images/twitter.png" alt="X"></a>
            <span class="footer-directory">SOCIAL MEDIA PAGES</span>
        </div>
    </footer>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="script/landingpage.js"></script>
  
  <script>
    // Profile Modal Functions
    function openProfileModal() {
        document.getElementById('profileModal').style.display = 'flex';
    }
    
    function closeProfileModal() {
        document.getElementById('profileModal').style.display = 'none';
    }
    
    function showTab(tabName) {
        // Hide all tab contents
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });
        
        // Remove active class from all tab buttons
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.classList.remove('active');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-tab').classList.add('active');
        
        // Add active class to clicked tab button
        event.target.classList.add('active');
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
 
</body>
</html>