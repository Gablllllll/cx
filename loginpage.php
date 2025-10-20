<?php
include "myconnector.php";
session_start();

$signup_error = $signup_success = $login_error = "";

// SIGNUP HANDLER
if (isset($_POST['register'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $secretkey = '';
    $phonenumber = trim($_POST['phonenumber']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $birthday = trim($_POST['birthday']);
    $role = 'student';

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $signup_error = "Username or email already exists.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, role, first_name, last_name, email, password_hash, secret_key, contact_number, address, date_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $username, $role, $firstname, $lastname, $email, $password_hash, $secretkey, $phonenumber, $address, $birthday);
        if ($stmt->execute()) {
            $signup_success = "Registration successful! You can now log in.";
        } else {
            $signup_error = "Registration failed. Please try again.";
        }
    }
    $stmt->close();
}

// LOGIN HANDLER
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role, first_name FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $db_username, $db_password_hash, $role, $first_name);
        $stmt->fetch();
        if (password_verify($password, $db_password_hash)) {
            // Login success
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $role;
            $_SESSION['first_name'] = $first_name;

            if ($role === 'admin') {
                header("Location: admin.php"); // Redirect to admin dashboard
            } elseif ($role === 'student') {
                header("Location: landingpage.php"); // Redirect to student dashboard
            } else {
                header("Location: student_dashboard.php"); // Redirect to student dashboard
            }
            exit();
        } else {
            $login_error = "Incorrect password.";
        }
    } else {
        $login_error = "Username not found.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ClassXic</title>
    <link rel="stylesheet" href="css/loginpage.css">
</head>
<body>
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
        <!-- Main Content -->
        <main class="main">
        <div class="left-section">
            <div class="hero-badge">
                <span> Welcome to ClassXic</span>
            </div>
            <h2>
                Let's <span class="green">Grow</span> Together and <br /> 
                <span class="highlight">Learn From Each Other</span>
            </h2>
            <p>
                Our innovative learning space empowers students to share ideas,
                build essential skills, and grow side by side — because learning 
                is better when we work as a team. Experience education reimagined 
                for the modern learner.
            </p>
            <div class="cta-buttons">
                <button class="hire-button primary-btn">
                    <span>Get Started</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button class="secondary-btn">
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
                        <p>Assistive technologies</p>
                    </div>
                </div>
            </div>
            <h3 class="curve-text">Learning <span class="accent">Connects</span> Us All.</h3>
            <p class="subtitle">Empowering every student to reach their full potential</p>
            
            <!-- Sliding Paragraph Section -->
            <div class="sliding-section">
                <div class="sliding-container">
                    <div class="sliding-content">
                        <div class="slide active">
                            <p>Dyslexia is a learning difference that affects reading, writing, and language processing. It’s not linked to intelligence but to how the brain interprets words and sounds. Raising awareness helps people understand that dyslexia can be managed with the right support.</p>
                        </div>
                        <div class="slide">
                            <p>Students with dyslexia often face difficulties in traditional classrooms. With awareness, teachers can use tools like dyslexia-friendly fonts, assistive technology, and multisensory teaching to help them learn better.</p>
                        </div>
                        <div class="slide">
                            <p>Awareness is also about recognizing the strengths of people with dyslexia. Many are creative thinkers and problem solvers who excel in art, innovation, and design.</p>
                        </div>
                        <div class="slide">
                            <p>By spreading awareness and understanding, we can create a more inclusive environment where people with dyslexia feel supported, confident, and valued.</p>
                        </div>
                    </div>
                    <!-- Invisible navigation hit areas -->
                    <div class="slide-nav-btn prev" id="prevSlideBtn" aria-label="Previous slide"></div>
                    <div class="slide-nav-btn next" id="nextSlideBtn" aria-label="Next slide"></div>
                    <div class="sliding-indicators">
                        <div class="indicator active" data-slide="0"></div>
                        <div class="indicator" data-slide="1"></div>
                        <div class="indicator" data-slide="2"></div>
                        <div class="indicator" data-slide="3"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
  <!-- Sidebar -->
    <div class="sidebar active" id="sidebar">
        <ul>

            <li><a href="#main-page"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
            <li><a href="#features-section"><img src="Images/idea-svgrepo-com.svg" alt="Features Icon">Features</a></li>
            <li><a href="#about-us"><img src="Images/about-filled-svgrepo-com.svg" alt="About-Us Icon">About Us</a></li>
            <br>
            <li>
                <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <img src="Images/login-3-svgrepo-com.svg" alt="Login Icon">
                    Log In
                </a>
            </li>
            <li>
                <a href="#" data-bs-toggle="modal" data-bs-target="#signupModal">
                    <img src="Images/sign-add-svgrepo-com.svg" alt="Sign Up Icon" >
                    Sign Up
                </a>
            </li>
        </ul>
    </div>
    <div id="sidebarBackdrop" class="sidebar-backdrop"></div>
    <!-- Role selection removed: default to Student sign up -->

 <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal-content-login">
                <div class="modal-header">
                    <h1 class="modal-title typing" id="animatedText">Sign up!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if ($signup_error): ?>
                        <div class="alert alert-danger"><?php echo $signup_error; ?></div>
                    <?php elseif ($signup_success): ?>
                        <div class="alert alert-success"><?php echo $signup_success; ?></div>
                    <?php endif; ?>
                    
                    <!-- Progress Bar -->
                    <div class="signup-progress mb-4">
                        <div class="progress">
                            <div class="progress-bar" id="signupProgress" role="progressbar" style="width: 50%"></div>
                        </div>
                        <div class="progress-text">
                            <span class="step-indicator active" id="step1Indicator">Step 1</span>
                            <span class="step-indicator" id="step2Indicator">Step 2</span>
                        </div>
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data" action="" id="signupForm">
                        <input type="hidden" id="signupRole" name="role" value="student">
                        
                        <!-- Step 1: Basic Information -->
                        <div id="step1" class="signup-step active">
                            <h5 class="mb-3 text-center">Basic Information</h5>
                            <div class="mb-3">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Type Here" required>
                            </div>
                            <div class="mb-3">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Type Here" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Type Here" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Type Here" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Type Here" required>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" id="nextStep">Next</button>
                            </div>
                        </div>
                        
                        <!-- Step 2: Additional Information -->
                        <div id="step2" class="signup-step" style="display: none;">
                            <h5 class="mb-3 text-center">Additional Information</h5>
                            <div class="mb-3">
                                <label for="phonenumber" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phonenumber" name="phonenumber" placeholder="Type Here">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Type Here">
                            </div>
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" placeholder="Select your birthday">
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                <label class="form-check-label" for="flexCheckChecked">
                                    Agree to terms and conditions.
                                </label>
                                <p>
                                    By clicking <a href="">Sign up</a> and <a href="">agree</a> to our <a href="">Terms of Service</a> and that you have read our <a href="">Privacy Policy</a>
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" id="prevStep">Previous</button>
                                <button type="submit" name="register" class="btn btn-success">Sign Up</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="">
                    <p class="text-center mb-4">
                        If you already have an account <a href="" data-bs-toggle="modal" data-bs-target="#loginModal">sign in here.</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal-content-login">
                <div class="modal-header">
                    <h1 class="modal-title typing" id="animatedText2">Welcome Back!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if ($login_error): ?>
                        <div class="alert alert-danger"><?php echo $login_error; ?></div>
                    <?php endif; ?>
                    
                    <!-- Login Progress Bar (for visual consistency) -->
                    <div class="login-progress mb-4">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%"></div>
                        </div>
                        <div class="progress-text">
                            <span class="step-indicator active">Login</span>
                        </div>
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data" action="" id="loginForm">
                        <div class="login-step active">
                            <h5 class="mb-3 text-center">Sign In to Your Account</h5>
                            <div class="mb-3">
                                <label for="loginUsername" class="form-label">Username</label>
                                <input type="text" class="form-control" id="loginUsername" name="username" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter your password" required>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" id="loginAgree" checked>
                                <label class="form-check-label" for="loginAgree">
                                    Remember me
                                </label>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" name="login" class="btn btn-success w-100">Sign In</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <p class="text-center mb-2">
                        <a href="#" class="forgot-password-link">Forgot Password?</a>
                    </p>
                    <p class="text-center mb-0">
                        Don't have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#roleModal" data-bs-dismiss="modal">Sign up here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
       <!-- Classix's Features -->
       <div class="features-section" id="features-section">
        <h1>ClassXic's Features</h1>
       <div class="features-container" id="features-box">

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
        <!-- Feature Modal - Modal 2 -->
        <div id="featureModal" class="feature-modal">
            <div class="feature-modal-content">
                <span id="close-feature-modal">&times;</span>
                <h2 id="feature-modal-title"></h2>
                <img id="feature-modal-img" src="" alt="" style="max-width:300px; display:block; margin:0 auto 16px auto;">
                <p id="feature-modal-text"></p>
            </div>
        </div>
    </div>
    <div class="about-section" id="about-us">
        <div class="about-text">
            <h1>About Us.</h1>
            <p>
            ClassXic is a dyslexic-friendly LMS is an accessible learning platform designed to support students with dyslexia. It features readable fonts, clear navigation, minimal distractions, high-contrast themes, and text-to-speech options to enhance comprehension and create an inclusive educational environment.
            </p>
        </div>
        <div class="about-image">
            <img src="Images/main.png" alt="OpenDyslexic Font Example">
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-left">
            © ClassXic
        </div>
        <div class="footer-right">
            <a href="#"><img src="Images/facebook.png" alt="Facebook"></a>
            <a href="#"><img src="Images/social.png" alt="Instagram"></a>
            <a href="#"><img src="Images/linkedin.png" alt="LinkedIn"></a>
            <a href="#"><img src="Images/twitter.png" alt="X"></a>
            <label class="footer-directory">SOCIAL MEDIA PAGES</label>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php if ($signup_error || $signup_success): ?>
        <script>
            var signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
            signupModal.show();
        </script>
        <?php endif; ?>

        <?php if ($login_error): ?>
        <script>
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        </script>
        <?php endif; ?>

    <script src="script/loginpage.js"></script>
</body>
</html>