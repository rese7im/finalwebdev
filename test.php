<?php

include "config/dbCon.php";


// Remove the unnecessary session_start() after the database connection

if (isset($_SESSION['user_id'])) {
    header("Location: admin/dashboard.php");
    exit();
}

if(isset($_POST['login'])) {

    echo "Try";

}

?>

