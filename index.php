<?php

include "config/dbCon.php";


// Remove the unnecessary session_start() after the database connection

if (isset($_SESSION['user_id'])) {
    header("Location: admin/dashboard.php");
    exit();
}

if(isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $user = mysqli_fetch_array($result);
            // Verify the entered password against the hashed password in the database
            if ($password == $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");


                exit();
            } else {
                echo "Failed to login. Please check your credentials.";
                echo "Test";
            }
        } else {
            echo "Failed to login. Please check your credentials.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error in preparing the SQL statement.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP BASICS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/style.css" rel="stylesheet">
    <style>
        /* Add CSS styling for the container box */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            margin-bottom: 10px;
            padding: 8px;
        }

        button {
            padding: 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>LOGIN</h1>
        <form action="index.php" method="POST">
            <input type="email" name="email" placeholder="Email" required autofocus>
            <input type="password" name="password" placeholder="Password" required autofocus>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>

</html>