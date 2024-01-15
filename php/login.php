<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <?php

    include("../includes/db.php");
    if (isset($_SESSION['user'])) {
        header('Location: dashboard.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];


        if ($conn) {
            $tsql = "SELECT * FROM user_tbl WHERE username = ? AND password = ?";
            $params = array($username, $password);

            $stmt = sqlsrv_query($conn, $tsql, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            if ($row) {
                $_SESSION['user'] = $username;
                session_start();
                header('Location: ../index.php');
                exit();
            } else {
                echo '<p style="color: red;">Invalid credentials</p>';
            }

            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn);
        } else {
            echo "Connection failed.";
        }
    }
    ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
