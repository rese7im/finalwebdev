<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "phpbasics";
$con = mysqli_connect($host, $username, $password, $db_name);

$userID = $_GET["userID"];
$email = $_GET["userEmail"];

$sql = "UPDATE users set email = '$email' WHERE id = '$userID'";
$update = mysqli_query($con, $sql);

if ($update) {

    $sql = "SELECT * FROM users";
    $selectAll = mysqli_query($con, $sql);

    if (mysqli_num_rows($selectAll) != 0) {

        $users = array();

        while ($row = mysqli_fetch_array($selectAll)) {

            array_push($users,$row);

        }

        $result = json_encode($users);

        echo $result;
    }




} else {
    $result = json_encode("Failed to update user Info");

    echo $result;
}


?>