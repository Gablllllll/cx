<?php
// Database connection
$servername = "localhost"; // Change this to your server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "pets_and_oranges"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $adoption_date = date("Y-m-d H:i:s");

    // Insert into database
    $sql = "INSERT INTO adoptions (adopter_name, adopter_email, message, adoption_date) VALUES ('$name', '$email', '$message', '$adoption_date')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoomies Collection</title>
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
      
    <main>
        <section class="collection-section" id="features">
            <h1 class="collection-title">Reptile <span>Available<span></h1>
            <p class="collection-subtitle">Scales, tails, and second chances—give a rescued reptile the forever home they deserve.</p>
            
            <div class="button-grid">
                <a href="order.php?name=T rex%20&image=img/rep1.jpg" class="collection-item">
                    <p class="item-name">T rex</p>
                    <img src="img/rep1.jpg" alt="Silver Whiskers" class="collection-image">
                    
                </a>
                <a href="order.php?name=Venomous%20&image=img/rep2.jpg" class="collection-item">
                    <p class="item-name">Venomous</p>
                    <img src="img/rep2.jpg" alt="Silver Whiskers" class="collection-image">
                    
                </a>
                <a href="order.php?name=Diabolical%20&image=img/rep3.jpg" class="collection-item">
                    <p class="item-name">Diabolical</p>
                    <img src="img/rep3.jpg" alt="Playful Swipe" class="collection-image">
                    
                </a>
                <a href="order.php?name=Raizor%20&image=img/rep4.jpg" class="collection-item">
                    <p class="item-name">Raizor</p>
                    <img src="img/rep4.jpg" alt="Gentle Purr" class="collection-image">
                    
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
            <p class="about-text">At our Pet Adoption Center, we believe every animal deserves a loving home. Whether you're looking for a playful pup or a cuddly kitten, we're here to help you find your perfect companion. Adopting not only changes a pet's life—it changes yours too. Visit us today and meet your new best friend.</p>
            
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
                <h2 class="footer-emblem">Pets and Oranges</h2>
    
                <p>Adoption Center for the free-spirited</p>
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

