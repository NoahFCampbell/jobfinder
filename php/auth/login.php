<?php
require_once './../../db/db.php';
session_start();

$errs = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $errs[] = "*Email is required";
    }

    if (empty($password)) {
        $errs[] = "*Password is required";
    } else {
        $password = md5($password);
    }

    if (count($errs) == 0) {

        $user = raw("select user_account.id, user_type.user_type_nam, user_account.email, user_account.password from user_account, user_type where user_account.user_type_id = user_type.id and email ='$email' and password ='$password'");


        if ($user != null) {
            $user = $user[0];
            $_SESSION['logged_user_id'] = $user->id;
            $_SESSION['logged_user_type'] = $user->user_type_nam;

            if ($user->user_type_nam == "job_poster") {
                header("Location: ./../jobposter/home.php");
            } else if ($user->user_type_nam == "job_seeker") {
                header("Location: ./../jobseeker/home.php");
            }
        } else {
            $errs[] = "Email and password does not match!";
        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <?php
		include './../layout/bootstrap.php';
	?>

    <style>
    .lgButton {
        height: 45px;
        width: 60%;
        border:none;
        border-radius: 5px;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
        font-size: larger;
        cursor: pointer;
        background: green;
        color: white;
        transition-duration: 0.5s;
    }

    .lgButton:hover {
        border-radius: 15px;
        font-size: larger;
    }
    </style>
</head>

<body>
	  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="../../images/logo.png" alt="logo" style="width:60px; height:auto">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../../index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
              
                    <a class="nav-link" href="./../auth/login.php">Find Jobs</a>

                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./../auth/login.php">Post a Job</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./../auth/login.php">Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./../auth/middlepage.php">Signup</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div class="m-5 pb-5">
                    <div class="m-5 pb-5">
                        <div class="card-header alert alert-info">
                            <center>
                                User Login
                            </center>
                        </div>
                        <br />
                        <div class="card-body">
                            <?php 
                            if(count($errs) != 0)
                                echo '<div class="alert alert-danger" role="alert">';
                                foreach ($errs as $e) {
                                    echo "$e </br>";
                                }
                                echo '</div>';
                            ?>
                            <form action="" method="post">
                                <input type="text" class="form-control" name="email" placeholder="Email" required>
                                <br>
                                <input type="password" class="form-control" name="password" placeholder="Password"
                                    required>

                                <br />
                                <div>
                                    <center>
                                        <input type="submit" class="lgButton" value="Login">
                                    </center>
                                </div>

                            </form>
                            <br />
                            <center>
                                <div>
                                    <a href="./../auth/middlepage.php">Create Account</a>
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</body>

</html>