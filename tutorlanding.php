<?php
include "myconnector.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            <h2>
                Lets <span class="green">Grow</span> Together and <br /> Learn From Each Other.
            </h2>
            <p>
                Our learning space helps students share ideas,
                build skills, and grow side by side —
                because learning is better when we work as a team.
            </p>
            <button class="hire-button">Hire Us!</button>
        </div>
        <div class="right-section">
            <img src="Images/main.png" alt="Students learning together" class="students-image" />
            <h3 class="curve-text">Learning Connect Us All.</h3>
        </div>
    </main>
    <!-- Classix's Features -->
    <div class="features-section">
        <h1>ClassXic's Features</h1>
       <div class="features-container">
            <div class="feature-box"
                 data-title="Customized Learning Management System"
                 data-description="A customized learning platform designed to meet the unique needs of dyslexic learners, offering tools and features that support easier reading, comprehension, and learning."
                 data-image="Images/custom.png">
                 
                <img src="Images/dashboard.png" alt="LMS Icon" class="feature-icon">
                <div class="feature-title">Customized Learning<br>Management System</div>
            </div>
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
    <div class="about-section">
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


  <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="landingpage.php"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>
            <li><a href="calendar.php"><img src="Images/calendar-month-svgrepo-com.svg" alt="Calendar Icon"> Calendar</a></li>
            <li><a href="tutormodule.php"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
            <li><a href="tutorlist.php"><img src="Images/user-svgrepo-com.svg" alt="Tutors Icon"> Tutor</a></li>
            <li><a href="progress.php"><img src="Images/progress-svgrepo-com.svg" alt="Progress Icon">Progress</a></li>
            <li>
                <a href="#" class="dropdown-toggle">-Option-</a>
                <ul class="dropdown-menu">
                    <li><a href="#features-section"><img src="Images/idea-svgrepo-com.svg" alt="Features Icon">Features</a></li>
                    <li><a href="#about-us"><img src="Images/about-filled-svgrepo-com.svg" alt="About-Us Icon">About Us</a></li>
                    <li><a href="#settings"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
                    <li><a href="logout.php"><img src="Images/logout-svgrepo-com.svg" alt="Logout Icon">Log out</a></li>
                </ul>
            <li>
        </ul>
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
            <span class="footer-directory">SOCIAL MEDIA PAGES&rarr;</span>
        </div>
    </footer>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="script/tutorlanding.js"></script>
</body>
</html>