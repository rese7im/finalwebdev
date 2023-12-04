<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "phpbasics";
$con = mysqli_connect($host, $username, $password, $db_name);


$userID = $_GET["userID"];

$sql = "SELECT * FROM users WHERE id = '$userID'";
$select = mysqli_query($con, $sql);

if (mysqli_num_rows($select) != 0) {
    $row = mysqli_fetch_array($select);

    $result = json_encode($row);

    echo $result;


} else {
    $result = json_encode("No users found");

    echo $result;
}


?>