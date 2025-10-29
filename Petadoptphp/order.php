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

// Handle form submission for contact
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['pet_name'];
    $email = $_POST['adopter_name'];
    $message = $_POST['adopter_email'];

    // Insert into contacts table
    $sql = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        //echo "Message sent successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle adoption submission
if (isset($_POST['adopt'])) {
    $pet_name = $_POST['pet_name'];
    $adopter_name = $_POST['adopter_name'];
    $adopter_email = $_POST['adopter_email'];

    // Insert into adoptions table
    $sql = "INSERT INTO adoptions (pet_name, adopter_name, adopter_email) VALUES ('$pet_name', '$adopter_name', '$adopter_email')";

    if ($conn->query($sql) === TRUE) {
        //echo "Adoption recorded successfully!";
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
    <title>Order Item</title>
    <link rel="stylesheet" href="order.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"/>
</head>
<body>
    <header>
        <section class="header-content" id="home">
            <nav class="main-navs">
                <div class="menu-toggle" id="mobileMenuToggle">
                    <i class="fas fa-bars"></i>
                </div>
                <ul class="nav-lists" id="navMenu">
                    <li><a href="#top">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
            <h1 class="ctitle">Pets and <span>Oranges</span></h1>
            <div class="main-cart">
                <a href="" style="color: #000; font-size: 20px;"><i class="fas fa-shopping-paw"></i></a>
            </div>
            <div class="logo">
                <img src="img/logo.png" alt="Cats and Oranges Logo">
            </div>
        </section>
    </header>

    <section class="order" id="home">
        <div class="about-contentorder" id="home">
            <div class="about-order">
                <img id="order-image" src="img/cat1.jpg" alt="Order" class="about-order-img">
            </div>      
            <div class="about-text-content">
                <h2 class="about-title">Adaption <span>Process</span></h2>
                <p class="about-text">Classic charm with a playful edge. The Signature Collection blends comfort and style, perfect for those who embrace subtle sophistication with a hint of mischief.At our Pet Adoption Center, we believe every animal deserves a loving home. Whether you're looking for a playful pup or a cuddly kitten, we're here to help you find your perfect companion. Adopting not only changes a pet's life—it changes yours too. Visit us today and meet your new best friend.</p>

        </div>       
        </div>
    </section>
    
    <section>
        <div class="order-main">
            <div class="order-content">
                <h2 id="order-name">Pet Name</h2>
            </div>
            <div class="order-button-container">
                <button class="order-button" onclick="showAdoptionForm()">ADOPT NOW!</button> 
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <h2 class="contact-title">Contact</h2>
        <div class="contact-container">
            <div class="contact-info">
                <h3 class="contact-subtitle"><a href="https://mail.google.com/mail/?view=cm&fs=1&to=markgabrielmagdaong@gmail.com&su=Hello%20Mark&body=I%20wanted%20to%20get%20in%20touch%20with%20you%20regarding..." target="_blank">Email</a></h3>
                <span class="contact-text">catsandoranges@gmail.com</span>
                <h3 class="contact-subtitle">Phone</h3>
                <span class="contact-text">+63 999999999</span>
                <h3 class="contact-subtitle"><a href="https://maps.app.goo.gl/k1SrrpZcb9eSyKd79" target="_blank">Address</a></h3>
                <span class="contact-text">Municipality of Pililla, Rizal</span>
            </div>
            <form action="" method="POST" class="contact-form" id="contactForm">
                <div class="contact-inputs">
                    <input type="text" placeholder="Name" class="contact-input" id="name" name="name" required>
                    <input type="email" placeholder="Email" class="contact-input" id="email" name="email" required>
                </div>
                <textarea name="message" id="message" placeholder="Message" rows="10" class="contact-input" required></textarea>
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
    </footer>

    <script src="js.js"></script>
    <script>
        function showAdoptionForm() {
            const petName = document.getElementById('order-name').textContent;
            const formHtml = `
                <form action="" method="POST" class="adoption-form">
                    <input type="hidden" name="pet_name" value="${petName}">
                    <input type="text" name="adopter_name" placeholder="Your Name" required>
                    <input type="email" name="adopter_email" placeholder="Your Email" required>
                    <input type="submit" name="adopt" value="Confirm Adoption" class="adopt-button">
                </form>
            `;
            document.querySelector('.order-main').innerHTML += formHtml;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            const itemName = params.get('name');
            const itemImage = params.get('image');

            if (itemName) {
                document.getElementById('order-name').textContent = itemName;
            }

            if (itemImage) {
                document.getElementById('order-image').src = itemImage;
            }
        });
    </script>
</body>
</html>