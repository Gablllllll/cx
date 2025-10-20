<?php
include "myconnector.php";
session_start();
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
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
            <img src="Images/user-svgrepo-com.svg" alt="User Icon">
        </div>
    </nav>

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
    <div class="sidebar" id="sidebar">
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
                            <li><a href="#settings"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
                            <li><a href="logout.php"><img src="Images/logout-svgrepo-com.svg" alt="Logout Icon">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
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
            <span class="footer-directory">SOCIAL MEDIA PAGES</span>
        </div>
    </footer>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="script/landingpage.js"></script>
 
</body>
</html>