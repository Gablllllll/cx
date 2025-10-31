<?php

// Database connection
$conn = new mysqli("localhost", "root", "", "pets_and_oranges");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets and Oranges</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="img/logo.png" alt="Cats and Oranges Logo">
    </div>
    <nav class="main-nav">
        <ul class="nav-list">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#pets">Pets</a></li>
            <li><a href="#Testimonials">Testimonials</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
    <button class="menu-toggle" aria-label="Open Navigation">
        <i class="fas fa-bars"></i>
    </button>
    <div class="popup-menu">
        <button class="close-menu" aria-label="Close Navigation">
            <i class="fas fa-times"></i>
        </button>
        <ul class="popup-list">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#pets">Pets</a></li>
            <li><a href="#Testimonials">Testimonials</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>
</header>

<section class="main-content" id="home">
    <div class="main-cont">
        <div class="pic-container">
            <img src="img/logo.png" alt="">
        </div>
        <div class="text-container">
            <h1>Pets and <span>Oranges</span></h1>
            <h2>Adoption Center</h2>
            <p>Every pet deserves a loving home. Visit our center and find your perfect companion today.</p>
            <a href="shop.php" class="shop-button">ADOPT NOW!</a>
        </div>
    </div>
</section>

<section class="about" id="about">
    <h2 class="about-title">About</h2>
    <div class="about-content">
        <p class="about-text">At our Pet Adoption Center, we believe every animal deserves a loving home. Whether you're looking for a playful pup or a cuddly kitten, we're here to help you find your perfect companion.</p>
        <div class="about-logo">
            <img src="img/logo.png" alt="LOGO" class="about-logo-img">
        </div>
    </div>
</section>

<section class="collection-section" id="pets">
    <h1 class="collection-title">Pets</h1>
    <p class="collection-subtitle">Our shelter is full of lovable pets ready to bring joy, loyalty, and companionship into your life.</p>
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

<section class="testimonials" id="Testimonials">
    <h2 class="testimonial-title">Testimonials</h2>
    <div class="testimonial-list">
        <?php
        $result = $conn->query("SELECT name, testimonial, created_at FROM testimonials ORDER BY created_at DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<div class='testimonial-item'>";
            echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
            echo "<p>" . htmlspecialchars($row['testimonial']) . "</p>";
            echo "<small>" . $row['created_at'] . "</small>";
            echo "</div>";
        }
        ?>
    </div>

    <form action="submit_testimonial.php" method="POST" class="testimonial-form">
        <div class="testimonial-inputs">
            <input type="text" name="name" placeholder="Your Name" class="testimonial-input" required>
            <textarea name="testimonial" placeholder="Your Testimonial" class="testimonial-input" rows="4" required></textarea>
        </div>
        <input type="submit" value="Submit Testimonial" class="testimonial-button">
    </form>
</section>

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

<footer class="footer">
    <div class="footer-container">
        <div>
            <h2 class="footer-title">Explore</h2>
            <ul>
                <li><a href="#home" class="footer-link">Home</a></li>
                <li><a href="#about" class="footer-link">About</a></li>
                <li><a href="#pets" class="footer-link">Pets</a></li>
                <li><a href="#Testimonials" class="footer-link">Testimonials</a></li>
                <li><a href="#contact" class="footer-link">Contact</a></li>
            </ul>
        </div>
        <div class="footer-title">
            <h2 class="footer-emblem">Pets and Oranges</h2>
            <p>Adoption Center for the free-spirited.</p>
        </div>
        <div class="footer-section">
            <h2 class="footer-title">Follow Us</h2>
            <div class="social-media">
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</footer>
<script src="js.js"></script>
</body>
</html>
