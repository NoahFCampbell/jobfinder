<?php
require_once './../../db/db.php';

session_start();

$user_type_id = $email = $password = $date_of_birth = $gender = $contact_number = $firstName = $lastName = "";
$success = false;
$errs = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['form_type'] == "personal_information") {

        $gender = '';
        $user_type_id = 2;
        $email = $_POST['email'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $password = $_POST['password'];
        $date_of_birth = $_POST['date_of_birth'];
        $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
        $contact_number = $_POST['contact_number'];
        $user_image = $_FILES['profilePicture']["name"];

        if (empty($email)) {
            $errs[] = "*Email is required";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errs[] = "*Invalid Email Address";
        }

        if (empty($password)) {
            $errs[] = "*Password is required";
        }

        if (empty($contact_number)) {
            $errs[] = "*Contact Number is required";
        }

        if (empty($gender)) {
            $errs[] = "*Gender is required";
        } else {
            $gender = $gender == "Male" ? "m" : "f";
        }

        if (empty($firstName)) {
            $errs[] = "*First Name is required";
        }

        if (empty($lastName)) {
            $errs[] = "*Last Name is required";
        }

        if (empty($user_image)) {
            $errs[] = "*Image is required";
        }

        if (count($errs) == 0) {

            $uploadStatus = uploadIamge();
            $uploadDir = "./../../uploads/" . time() . basename($_FILES["profilePicture"]["name"]);

            if ($uploadStatus == "OK") {

                $datas = [
                    'user_type_id' => $user_type_id,
                    'email' => $email,
                    'password' => md5($password),
                    'date_of_birth' => $date_of_birth,
                    'gender' => $gender,
                    'contact_number' => $contact_number,
                    'user_image' => $uploadDir,
                ];

                $action = create("user_account", $datas);

                if ($action) {

                    $result = raw("SELECT * FROM user_account ORDER BY ID DESC LIMIT 1");

                    create("seeker_profile", [
                        'user_account_id' => $result[0]->id,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                    ]);

                    create("education_detail", [
                        'user_account_id' => $result[0]->id,
                    ]);

                    create("experience_detail", [
                        'user_account_id' => $result[0]->id,
                    ]);

                    $success = true;

                }

            }else {
                $errs = array_merge($errs, $uploadStatus);
            }

        }

    }

}

function uploadIamge()
{
    $target_dir = "./../../uploads/";
    $target_file = $target_dir . time() . basename($_FILES["profilePicture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $imageErr = [];

// Check if image file is an actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
        if ($check !== false) {
            
            $uploadOk = 1;
        } else {
            $imageErr[] = "File is not an image.";
            $uploadOk = 0;
        }
    }

// Check if file already exists
    if (file_exists($target_file)) {
        $imageErr[] = "Sorry, file already exists.";
        $uploadOk = 0;
    }

// Check file size
    if ($_FILES["profilePicture"]["size"] > 500000) {
        $imageErr[] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

// Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $imageErr[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $imageErr[] = "Sorry, your file was not uploaded.";
// If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {

        } else {
            $imageErr[] = "Sorry, there was an error uploading your file.";
        }
    }

    if (count($imageErr) != 0) {
        return $imageErr;
    } else {
        return "OK";
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>

    <!-- Required Links -->
    <?php
include './../layout/bootstrap.php';
?>

</head>

<body>

    <?php
// include "./../layout/indexHeader.php"
?>

    <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="../../images/logo.png" alt="logo" style="width:60px; height:auto">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../../index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <!-- <a class="nav-link" href="views/auth/login.php">Find Jobs</a> -->
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

    <br />
    <div class="container">
        <center>
            <h1>Create Your JobFinder Account</h1>
        </center>
        <div class="row">
            <div class="card-body">
                <div class="table">
                    <div class="row">
                        <div class="container">

                            <!--Tab Panes-->
                            <div class="tab-content">
                                <!-- My Profile -->
                                <div id="myProfile" class="container tab-pane active"><br>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-8">
                                                <div class="container ">
                                                    <?php
                                                    if ($success) {
                                                        echo '<div class="alert alert-success" role="alert">
                                                                                                            Job Finder Account Created
                                                                                                            </div>';
                                                    } else if (count($errs) > 0) {
                                                        echo '<div class="alert alert-danger" role="alert">';
                                                        foreach ($errs as $e) {
                                                            echo $e . "</br>";
                                                        }
                                                        echo '</div>';
                                                    }

                                                    ?>


                                                    <label>First Name</label>
                                                    <input type="text" class="form-control" name="firstName"
                                                        value="<?=$firstName?>">

                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control" name="lastName"
                                                        value="<?=$lastName?>">

                                                    <label>Date of Birth</label>
                                                    <input type="date" class="form-control" name="date_of_birth"
                                                        value="<?=$date_of_birth?>">

                                                    <label>Gender</label>
                                                    <select class="form-control" name="gender">
                                                        <option selected disabled>Select your Gender</option>
                                                        <option>Male</option>
                                                        <option>Female</option>
                                                    </select>

                                                    <label>Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="<?=$email?>">

                                                    <label>Contact No</label>
                                                    <input type="number" class="form-control" name="contact_number"
                                                        value="<?=$contact_number?>">



                                                    <label>Password</label>
                                                    <input type="password" class="form-control" name="password"
                                                        value="<?=$password?>">

                                                    <input type="hidden" name="form_type" value="personal_information">

                                                    <br />
                                                    <label>Profile Picture</label>
                                                    <input type="file" class="btn btn-info" name="profilePicture">

                                                    <br /><br />


                                                    <input type="submit" value="Register" class="btn btn-primary">




                                                </div>
                                                <div class="col-sm-2"></div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>