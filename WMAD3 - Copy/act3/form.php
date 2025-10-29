<?php
include "../php/connect3.php";

if (isset($_POST['submitBtn'])){
  $firstName = $_POST['firstName'];
  echo $firstName ."<br>"; 
  $lastName = $_POST['lastName'];
  echo $lastName."<br>" ;
  $email = $_POST['email'];
  echo $email ."<br>";  
  $phoneNumber = $_POST['phoneNumber'];
  echo $phoneNumber ."<br>";
  $address = $_POST ['address'];
  echo $address."<br>";
  $user_id = "user-".time();



  // Corrected SQL Query
  $sql_save_user = "INSERT INTO users (user_id, firstName, lastName, contactNumber, email, address) 
  VALUES ('$user_id', '$firstName', '$lastName', '$phoneNumber', '$email', '$address')";

    if (mysqli_query($conn, $sql_save_user)) {
    echo "Malubulul";
    } else {
    echo "Error: " . mysqli_error($conn);
    }
    } 


    if (isset($_POST['submitupdateBtn'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $user_id = $_POST['user_id'];
    
        // Insert data into the users table
        $sql = mysqli_query($conn, "UPDATE users SET firstName = '$firstName', lastName = '$lastName', contactNumber = '$phoneNumber', email = '$email', address = '$address' WHERE user_id = '$user_id'");
    
        if (!$sql) {
            echo "bonak mali eto error:" . mysqli_error($conn);
        } else {
            echo "sumakses";
        }
    }
    ?>



  

<br>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
 integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<div class="container"  style="border: 1px solid black; width: 50%;">
    <form method="POST" enctype="multipart/form-data" action="">
        <h2 class="text-center">Basic Form</h2>
        <div class="form-group">
            <label for="">First Name:</label>
            <input type="text" placeholder="Insert First Name"  name="firstName" class="form-control"><br>
        </div>
        <div class="form-group">
            <label for="">Last Name:</label>
            <input type="text" placeholder="Insert Last Name"  name="lastName" class="form-control"><br>
        </div>
         <div class="form-group">
            <label for="">Email:</label>
            <input type="email" placeholder="Insert Email" class=" form-control" id="email" name="email"><br>
        </div>
        <div class="form-group">
            <label for="phoneNumber">Contact Number:</label>
            <input type="number" placeholder="Insert Phone Number" class=" form-control" id="phoneNumber" name="phoneNumber"><br>
        </div>
        <div class="form-group">
            <label for="">Address:</label>
            <input type="text" placeholder="Insert Address" class=" form-control" id="" name="address"><br>
        </div>
        
        <!-- <div class="form-group">
            <label for="">Date:</label>
            <input type="date" value="2025-03-01" class="form-control" name="submittedDate"><br>
        </div> -->
    <div class="form-group" >
        <input type="submit" value="Submit Form" class="btn btn-dark " name="submitBtn">
</div>
    </form>
</div>

<br>

<h3 style="text-align: center;">User Data</h3 >
<br>

<table class="table" border="1">
    <thead>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th> 
            <th>Email</th>
            <th>Contact Number</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM users");

    while ($row = mysqli_fetch_assoc($result)) {
      $user_id = $row['user_id'];
      echo "<tr>
          <td>{$row['user_id']}</td>
          <td>{$row['firstName']}</td>
          <td>{$row['lastName']}</td>
          <td>{$row['email']}</td>
          <td>{$row['contactNumber']}</td>
          <td>{$row['address']}</td>
          <td><a href = 'delete.php?user_id=$user_id' >DELETE ME <td>
          <td><a href = 'edit.php?user_id=$user_id'>EDIT <td>
        </tr>";
        }
        ?>
    </tbody>
</table>

