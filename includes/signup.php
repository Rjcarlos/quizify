<?php
 
include_once 'connect.php';

$username = $_POST['username'];
$pwd = $_POST['password'];


    $sql = "INSERT INTO users (username,pwd) VALUES
    ('$username', '$pwd');";
    $result = mysqli_query($conn, $sql);

    header("Location: ../login.php?signup=success");
    
?>