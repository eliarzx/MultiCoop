<?php

$host = "sql111.infinityfree.com";
$user = "if0_38828717"; 
$pass = "ezg9b2Qo4oeFA6d"; 
$dbname = "if0_38828717_db_hub"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>