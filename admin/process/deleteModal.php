<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "phpbasics";
$con = mysqli_connect($host, $username, $password, $db_name);

$userID = $_GET["userID"];

$sql = "DELETE FROM users WHERE id = '$userID' LIMIT 1";
$delete = mysqli_query($con, $sql);

if ($delete) {

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
    $result = json_encode("Failed to delete user Info");

    echo $result;
}


?>