<?php
// Start the PHP file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature Collection</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <section class="header-content" id="home">
            <nav class="main-navs">
                <!-- Hamburger Icon for Small Screens -->
                <div class="menu-toggle" id="mobileMenuToggle">
                    <i class="fas fa-bars"></i>
                </div>
                <!-- Navigation Links -->
                <ul class="nav-lists" id="navMenu">
                    <li><a href="#top">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
            <h1 class="ctitle">Pets and <span>Oranges</span></h1>
            <div class="logo">
                <img src="img/logo.png" alt="Cats and Oranges Logo">
            </div>
        </section>
    </header>

    <section class="top" id="top">
    </section>
      
    <main>
        <section class="collection-section" id="pets">
            <h1 class="collection-title">Pets</h1>
            <p class="collection-subtitle">Our shelter is full of lovable pets ready to bring joy, loyalty, and companionship into your life. Come meet your new best friend—adopt today!</p>
            
            <div class="button-grid">
                <a href="whiskers.php" class="collection-item">
                    <p class="item-name">Cats</p>
                    <img src="img/catlogo.jpg" alt="Meow Mode" class="collection-image">
                </a>
                <a href="citrus.php" class="collection-item">
                    <p class="item-name">Dogs</p>
                    <img src="img/doglogo.jpg" alt="Citrus Burst" class="collection-image">
                </a>
                <a href="meow.php" class="collection-item">
                    <p class="item-name">Birds</p>
                    <img src="img/birdslogo.jpg" alt="Whiskers" class="collection-image">
                </a>
                <a href="zoomies.php" class="collection-item">
                    <p class="item-name">Reptiles</p>
                    <img src="img/reptileslogo.jpg" alt="Zoomies" class="collection-image">
                </a>
            </div>
        </section>
    </main>

    <section class="contact" id="contact">
    <h2 class="contact-title">Contact</h2>
    <div class="contact-container">
        <div class="contact-info">
            <h3 class="contact-subtitle"><a href="mailto:markgabrielmagdaong@gmail.com">Email</a></h3>
            <span class="contact-text">petsandoranges@gmail.com</span>
            <h3 class="contact-subtitle">Phone</h3>
            <span class="contact-text">+63 999999999</span>
            <h3 class="contact-subtitle"><a href="https://maps.app.goo.gl/k1SrrpZcb9eSyKd79" target="_blank">Address</a></h3>
            <span class="contact-text">Municipality of Pililla, Rizal</span>
        </div>

        <form action="submit_contact.php" method="POST" class="contact-form">
            <div class="contact-inputs">
                <input type="text" name="name" placeholder="Name" class="contact-input" required>
                <input type="email" name="email" placeholder="Email" class="contact-input" required>
            </div>
            <textarea name="message" placeholder="Message" rows="10" class="contact-input" required></textarea>
            <input type="submit" value="Submit" class="contact-button">
        </form>
    </div>
</section>

    <section class="about" id="about">
        <h2 class="about-title">About</h2>
    
        <div class="about-content">
            <p class="about-text">Welcome to Cats and Oranges, where fashion gets fun! Our collections blend comfy styles with quirky designs, giving you the freedom to express yourself effortlessly. Whether you’re rocking bold citrus colors or sleek cat-inspired looks, we’ve got the perfect pieces to match your mood. Dive in and discover your new favorite outfit!</p>
            
            <div class="about-logo">
                <img src="img/logo.png" alt="LOGO" class="about-logo-img">
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-container">
            <div>
                <h2 class="footer-title">Explore</h2>
                <ul>
                    <li><a href="#home" class="footer-link">Home</a></li>
                    <li><a href="#about" class="footer-link">About</a></li>
                    <li><a href="#contact" class="footer-link">Contact</a></li>
                </ul>
            </div>
    
            <div class="footer-title">
                <h2 class="footer-emblem">Cats and Oranges</h2>
                <p>Designed for the free-spirited.</p>
            </div>
    
            <div class="footer-section">
                <h2 class="footer-title">Follow Us</h2>
                <div class="social-media">
                    <a href="https://www.facebook.com/profile.php?id=100011651890209" class="social-link">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://twitter.com/yourtwitterhandle" class="social-link">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.instagram.com/gabllllllllllll/" class="social-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="js.js"></script>
</body>
</html>