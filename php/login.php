<?php

session_start();
include("../includes/db.php");

if (isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fltype = $_POST['fl_type'];
    if ($conn) {
        $tsql = "SELECT * FROM user_tbl WHERE username = ? AND password = ?";
        $params = array($username, $password);
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row) {
            // Authentication successful
            $_SESSION['user'] = $username;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['fl_type'] = $fltype;

            setcookie(md5("user"), md5($_SESSION['user']), time() + 3600 * 24 * 365, '/');

            $device_id = uniqid('device_', true);
            $_SESSION['dev_id'] = $device_id;
            $cookieParams = session_get_cookie_params();
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="../css/bootstrap1.min.css" />
    <link rel="stylesheet" href="../css/metisMenu.css">
    <link rel="stylesheet" href="../css/style1.css" />
    <link rel="stylesheet" href="../css/colors/default.css" id="colorSkinCSS">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body>


    <div class="container">
        <!-- <div class="row justify-content-center align-items-center vh-100">
            <div class="col-6 col-sm-10 col-md-10 col-xl-5">
                <div class="card">
                    <div class="py-2 px-2 text-center">
                        <h3 class="mt-3">LOGIN</h3>
                    </div>
                    <div class="card-body">
                     
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                             <div class="form-group" hidden>
                                <label for="fl">Forklift Type:</label>
                                <select class="form-control" id="fl" name="fl_type">
                                    <option value="Forklift operator">Forklift operator</option>
                                </select>
                            </div>
                            <button type="submit" class="col-12 btn btn-primary mt-3">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="white_box mb_30">
            <div class="row justify-content-center">

                <div class="col-lg-6">
                    <!-- sign_in  -->
                    <div class="modal-content cs_modal">

                        <div class="modal-header justify-content-center theme_bg_1">
                            <h5 class="modal-title text_white">Forklift Activity Monitoring</h5>
                        </div>
                        <div class="modal-body">
                            <h2 class="text-center">Log in</h2>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            </div>
                             <div class="form-group" hidden>
                                <label for="fl">Forklift Type:</label>
                                <select class="form-control" id="fl" name="fl_type">
                                    <option value="Forklift operator">Forklift operator</option>
                                </select>
                            </div>
                            <button type="submit" class="col-12 btn btn-info mt-3">Login</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



</body>

</html>