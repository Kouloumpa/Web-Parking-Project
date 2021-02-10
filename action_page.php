<?php
$server_name = "localhost";
$username = "root";
$password = "";
$dbname = "parking";
$conn = mysqli_connect($server_name, $username , $password, $dbname);
mysqli_set_charset($conn, "utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    $Pdemand = mysqli_real_escape_string($conn, $_REQUEST['demand']);
    $Pseats  = mysqli_real_escape_string($conn, $_REQUEST['seats']);
    $Pname   = mysqli_real_escape_string($conn, $_REQUEST['bname']);

    $sql = "UPDATE kml SET parkseats='$Pseats', alloc='$Pdemand' WHERE name='$Pname'";

        if(mysqli_query($conn, $sql)){
        } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }

?>
