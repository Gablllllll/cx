<?php
include "../php/connect3.php";

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Retrieve user data
    $sql_select_user_data = mysqli_query($conn, "SELECT * FROM users WHERE user_id ='$user_id'");
    $row_select_user_data = mysqli_fetch_assoc($sql_select_user_data);

    if ($row_select_user_data) {
        $firstName = $row_select_user_data['firstName'];
        $lastName = $row_select_user_data['lastName'];
        $email = $row_select_user_data['email'];
        $contactNumber = $row_select_user_data['contactNumber'];
        $address = $row_select_user_data['address'];
    } else {
        // Redirect if user not found
        echo "<script>alert('User not found!'); window.location.href='users.php';</script>";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
</head>
<body>
<div class="container" style="border: 1px solid black; width: 50%; margin-top: 20px;">
    
    <form method="POST" action="form.php">
        <h2 class="text-center">Update Form</h2>

        <div>
            
        </div>
        <div class="form-group">
            <input type="hidden" value="<?php echo $user_id;?>" name="user_id">
            <label for="fname">First Name:</label>
            <input type="text" placeholder="Insert First Name" id="fname" name="firstName" class="form-control" value="<?php echo isset($firstName) ? $firstName : ''; ?>" required><br>
        </div>
        <div class="form-group">
            <label for="lname">Last Name:</label>
            <input type="text" placeholder="Insert Last Name" id="lname" name="lastName" class="form-control" value="<?php echo isset($lastName) ? $lastName : ''; ?>" required><br>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" placeholder="Insert Email" id="email" name="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" required><br>
        </div>
        <div class="form-group">
            <label for="phoneNumber">Contact Number:</label>
            <input type="number" placeholder="Insert Phone Number" id="phoneNumber" name="phoneNumber" class="form-control" value="<?php echo isset($contactNumber) ? $contactNumber : ''; ?>" required><br>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" placeholder="Insert Address" id="address" name="address" class="form-control" value="<?php echo isset($address) ? $address : ''; ?>" required><br>
        </div>


        <div class="form-group">
            <input type="submit" value="Update User" class="btn btn-dark" name="submitupdateBtn">
        </div>
    </form>
</div>
</body>
</html>
