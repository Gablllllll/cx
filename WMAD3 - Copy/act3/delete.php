<?php
include "../php/connect3.php";

if(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];
    $sql_delete_users = mysqli_query($conn, "DELETE FROM users where user_id ='$user_id'");

    if(!$sql_delete_users){
        echo"error";
    }
    else {
        echo "lala";
    }
    
    
}

?>