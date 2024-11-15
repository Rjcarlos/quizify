<?php
 
include_once 'connect.php';

    $sql = "CREATE TABLE users (
id INT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(30) NOT NULL,
pwd VARCHAR(30) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);";
    $result = mysqli_query($conn, $sql);

    header("Location: ../index.php?signup=success");
    
?>