<?php
include "../php/connect3.php";

if (isset($_POST['submitBtn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $contactNumber = $_POST['contactNumber'];
    $address = $_POST['address'];
    $customer_id = "customer-" . time();

    // Corrected INSERT query
    $sql_save_user = "INSERT INTO customer (customer_id, name, password, Phone, email, address) 
                      VALUES ('$customer_id', '$username', '$password', '$contactNumber', '$email', '$address')";

    if (mysqli_query($conn, $sql_save_user)) {
        echo "Malubulul";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_POST['submitupdateBtn'])) {
    $customer_id = $_POST['customer_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $contactNumber = $_POST['contactNumber'];
    $address = $_POST['address'];

    // Corrected UPDATE query
    $sql = mysqli_query($conn, "UPDATE customer SET name = '$username', password = '$password', 
                               Phone = '$contactNumber', email = '$email', address = '$address' 
                               WHERE customer_id = '$customer_id'");

    if (!$sql) {
        echo "bonak mali eto error:" . mysqli_error($conn);
    } else {
        echo "sumakses";
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<div class="container" style="border: 1px solid black; width: 50%;">
    <form method="POST" enctype="multipart/form-data" action="">
        <h2 class="text-center">Basic Form</h2>
        <div class="form-group">
            <label for="">Username:</label>
            <input type="text" placeholder="Insert Username" name="username" class="form-control"><br>
        </div>
        <div class="form-group">
            <label for="">Password:</label>
            <input type="password" placeholder="Insert Password" name="password" class="form-control"><br>
        </div>
        <div class="form-group">
            <label for="">Email:</label>
            <input type="email" placeholder="Insert Email" class="form-control" id="email" name="email"><br>
        </div>
        <div class="form-group">
            <label for="contactNumber">Contact Number:</label>
            <input type="number" placeholder="Insert Contact Number" class="form-control" id="contactNumber" name="contactNumber"><br>
        </div>
        <div class="form-group">
            <label for="">Address:</label>
            <input type="text" placeholder="Insert Address" class="form-control" name="address"><br>
        </div>
        <div class="form-group">
            <input type="submit" value="Submit Form" class="btn btn-dark" name="submitBtn">
        </div>
    </form>
</div>

<h3 style="text-align: center;">User Data</h3>

<table class="table" border="1">
    <thead>
        <tr>
            <th>Customer ID</th>
            <th>Name</th>
            <th>Password</th> 
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $result = mysqli_query($conn, "SELECT * FROM customer");

    while ($row = mysqli_fetch_assoc($result)) {
        $customer_id = $row['customer_id'];
        echo "<tr>
                <td>{$row['customer_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['password']}</td>
                <td>{$row['email']}</td>
                <td>{$row['Phone']}</td>
                <td>{$row['address']}</td>
                <td>
                    <a href='delete.php?customer_id=$customer_id' class='btn btn-danger btn-sm'>DELETE</a>
                    <a href='edit.php?customer_id=$customer_id' class='btn btn-warning btn-sm'>EDIT</a>
                </td>
            </tr>";
    }
    ?>
    </tbody>
</table>
