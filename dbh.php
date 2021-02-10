<?php

$server_name = "localhost";
$username = "root";
$password = "";
$dbname = "parking";
$conn = mysqli_connect($server_name, $username , $password, $dbname);
mysqli_set_charset($conn, "utf8");

?>
