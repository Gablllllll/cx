<?php

include "../php/connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Document</title>
</head>
<body>
        
    <table>
        <h1>Customer Table</h1>
        <tr>
            <th>Customer ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>

    <?php $sql_query_get_table1 = mysqli_query($conn, "SELECT customer_id, name, email, phone FROM customer");
    while($row_table1 = mysqli_fetch_assoc($sql_query_get_table1)){

        $customer_id = $row_table1['customer_id'];
        $name = $row_table1['name'];
        $email = $row_table1['email'];
        $phone = $row_table1['phone'];

        echo "<tr>
            <td>$customer_id</td>
            <td>$name</td>
            <td>$email</td>
            <td>$phone</td>
        </tr>";
        
    }
    ?>
    </table>
    <br>

    <table>
        <h1>Menu Table</h1>
        <tr>
            <th>Item ID</th>
            <th>Name</th>
            <th>Price</th>

        </tr>

    <?php $sql_query_get_table2 = mysqli_query($conn, "SELECT item_id, name, Price FROM menu");
    while($row_table2 = mysqli_fetch_assoc($sql_query_get_table2)){

        $item_id = $row_table2['item_id'];
        $name = $row_table2['name'];
        $Price = $row_table2['Price'];
    

            echo "<tr>
            <td>$item_id</td>
            <td>$name</td>
            <td>$Price</td>
        </tr>";
    }
    ?>  
    </table>
    <br>
    <table>
        <tr>
        <h1>Order Table</h1>
            <th>Order ID</th>
            <th>Customer ID</th>
            <th>Item ID</th>
            <th>quantity</th>
            <th>Order Date</th>
        </tr>
        
    <?php $sql_query_get_table3 = mysqli_query($conn, "SELECT order_id, customer_id, item_id, quantity, order_date FROM orders");
    while($row_table3 = mysqli_fetch_assoc($sql_query_get_table3)){

        $order_id = $row_table3['order_id'];
        $customer_id = $row_table3['customer_id'];
        $item_id = $row_table3['item_id'];
        $quantity = $row_table3['quantity'];
        $order_date = $row_table3['order_date'];

        echo "<tr>
            <td>$order_id</td>
            <td>$customer_id</td>
            <td>$item_id</td>
            <td>$quantity</td>
            <td>$order_date</td>
        </tr>";
    }   
    ?>
    </table>



    
</body>
</html>
