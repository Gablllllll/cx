<?php
session_start();

include "../../connect.php";
  

// Process form submission (Registration)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $contact_number = $_POST['contact-number'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Check if email already exists
        $check_email = "SELECT email FROM customer WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("s", $email); 
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Email already exists. Please use a different email.');</script>";
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database with hashed password
            $sql = "INSERT INTO customer (name, age, sex, Phone, address, email, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sisssss", $name, $age, $sex, $contact_number, $address, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

// Process form submission (Login)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT name, password FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($stored_name, $stored_password);

    // Fetch the result
    if ($stmt->fetch()) {
        // Verify the password hash
        if (password_verify($password, $stored_password)) { 
            $_SESSION['username'] = $stored_name;
            $_SESSION['email'] = $email;
            header("Location: home.php"); 
            exit();
        } else {
            echo "<script>alert('Incorrect email or password.');</script>";
        }
    } else {
        echo "<script>alert('Incorrect email or password.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/index.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Pasta=Salad</title>
  </head>
  <body>
    
    

    <ul class="nav-flex-row">
      <li class="nav-item">
        <a href="#about">About</a>
      </li>
      <li class="nav-item">
        <a href="#reservation">Reservation</a>
      </li>
      <li class="nav-item">
        <a href="#menu">Menu</a>
      </li>
      <li class="nav-item">
        <a href="#shop">Shop</a>
      </li>
    </ul>
  </nav>

  <section class="section-intro">
    <header>
      <h1>Pasta=Salad</h1>
  </header>
    <div class="link-to-book-wrapper">
    <a class="link-to-book" href="#" data-toggle="modal" data-target="#loginSignupModal">Book a table</a>
   </div>

    <!-- ```html
    <!-- Modal -->
    <div class="modal fade" id="loginSignupModal" tabindex="-1" role="dialog" aria-labelledby="loginSignupModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginSignupModalLabel">Login / Signup</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="signup-tab" data-toggle="tab" href="#signup" role="tab" aria-controls="signup" aria-selected="false">Signup</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                <form method="POST" action="">
                  <div class="form-group">
                    <label for="loginEmail">Email</label>
                    <input type="email" class="form-control" id="loginEmail" name="email" required>
                  </div>
                  <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" class="form-control" id="loginPassword" name="password" required>
                  </div>
                  <button type="submit" name="login" class="btn btn-primary">Login</button>
                </form>
              </div>
              <div class="tab-pane fade" id="signup" role="tabpanel" aria-labelledby="signup-tab">
                <form method="POST" action="">
                  <div class="form-group">
                    <label for="signupName">Name</label>
                    <input type="text" class="form-control" id="signupName" name="name" required>
                  </div>
                  <div class="form-group">
                    <label for="signupAge">Age</label>
                    <input type="number" class="form-control" id="signupAge" name="age" required>
                  </div>
                  <div class="form-group">
                    <label for="signupSex">Sex</label>
                    <select class="form-control" id="signupSex" name="sex" required>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="signupContactNumber">Contact Number</label>
                    <input type="text" class="form-control" id="signupContactNumber" name="contact-number" required>
                  </div>
                  <div class="form-group">
                    <label for="signupAddress">Address</label>
                    <input type="text" class="form-control" id="signupAddress" name="address" required>
                  </div>
                  <div class="form-group">
                    <label for="signupEmail">Email</label>
                    <input type="email" class="form-control" id="signupEmail" name="email" required>
                  </div>
                  <div class="form-group">
                    <label for="signupPassword">Password</label>
                    <input type="password" class="form-control" id="signupPassword" name="password" required>
                  </div>
                  <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirm-password" required>
                  </div>
                  <button type="submit" name="register" class="btn btn-primary">Signup</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="about-section">
    <article>
      <h3>
        From Italy to Your Plate – Buon
        Appetito!
      </h3>
      <br>
      <p>
      The Associazione Verace Pizza Napoletana (lit. True Neapolitan Pizza Association) is a non-profit organization founded in 1984 with headquarters in Naples that aims to promote traditional Neapolitan pizza. In 2009,
      upon Italy's request, Neapolitan pizza was registered with the European Union as a Traditional Speciality Guaranteed dish, and in 2017 the art of its making was included on UNESCO's list of intangible cultural heritage.
      </p>
    </article>
  </section>

  <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="img/rachel-park-hrlvr2ZlUNk-unsplash.jpg" class="d-block w-100" alt="food">
      </div>
      <div class="carousel-item">
        <img src="img/lily-banse--YHSwy6uqvk-unsplash.jpg" class="d-block w-100" alt="food">
      </div>
      <div class="carousel-item">
        <img src="img/brooke-lark-aGjP08-HbYY-unsplash.jpg" class="d-block w-100" alt="food">
      </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <div class="container">
    <div class="row-flex">
      <div class="flex-column-form">
        <h3>
          Make a booking
        </h3>
      <form class="media-centered">
          <div class="form-group">
            <p>
              Please leave your number we will call to make a reservation
            </p>
            <input type="name" class="form-control" id="exampleInputName1" aria-describedby="nameHelp" placeholder="Enter your name">
          </div>
          <div class="form-group">
            <input type="number" class="form-control" id="exampleInputphoneNumber1" placeholder="Enter your phone number">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      </div>
      <div class="opening-time">
        <h3>
          Opening times
        </h3>
        <p>
         <span>Monday—Thursday: 08:00 — 22:00</span> 
         <span>Friday—Saturday: 09:00 — 23:00 </span>
         <span>Sunday: 10:00 — 17:00</span>
        </p>
      </div>
      <div class="contact-adress">
        <h3>
          Contact
        </h3>
        <p>
          <span>000 9283 8456</span>
          <span>15 Florida Ave</span>
          <span>Palo-Alto, 1111 CA</span>
        </p>
      </div>
    </div>
    </div>



  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 
  </body>
  
</html> 