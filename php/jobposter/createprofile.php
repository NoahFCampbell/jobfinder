<?php
require_once './../../db/db.php';

session_start();

$user_type_id = $email = $password = $date_of_birth = $gender = $contact_number = "";
$company_name = $company_website = $profile_description = $industry = "";
$errs = [];
$success = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $gender = '';
    $user_type_id = 1;
    $email = $_POST['email'];
    $password = $_POST['password'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
    $contact_number = $_POST['contact_number'];
    $user_image = $_FILES['profilePicture']["name"];

    //company
    $company_logo = $_FILES['company_logo']["name"];
    $company_website = $_POST['company_website'];
    $company_name = $_POST['company_name'];
    $profile_description = $_POST['profile_description'];
    $industry = $_POST['industry'];


    if (empty($email)) {
        $errs[] = "*email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errs[] = "*Invalid Email Address";
    }

    if (empty($password)) {
        $errs[] = "*password is required";
    }

    if (empty($contact_number)) {
        $errs[] = "*Contact Number is required";
    }

    if (empty($gender)) {
        $errs[] = "*Gender is required";
    } else {
        $gender = $gender == "Male" ? "m" : "f";
    }

    if (empty($user_image)) {
        $errs[] = "*User Image is required";
    }

    if (empty($company_logo)) {
        $errs[] = "*Company Logo is required";
    }

    if (empty($company_name)) {
        $errs[] = "*Company Name is required";
    }

    if (empty($profile_description)) {
        $errs[] = "*Profile Description is required";
    }

    if (empty($company_website)) {
        $errs[] = "*Company Website is required";
    }

    if (empty($industry)) {
        $errs[] = "*Industry is required";
    }

    if (count($errs) == 0) {

        $uploadStatus = uploadImage("profilePicture");
        $uploadDir = "./../../uploads/" . time() . basename($_FILES["profilePicture"]["name"]);

        if ($uploadStatus != true) {
            var_dump($uploadStatus);
        } else {

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

                $uploadStatus = uploadImage("company_logo");
                $uploadDir = "./../../uploads/" . time() . basename($_FILES["company_logo"]["name"]);
                create("company", [
                    'company_name' => $company_name,
                    'profile_description' => $profile_description,
                    'industry' => $industry,
                    'company_website' => $company_website,
                    'company_logo' => $uploadDir,
                    'user_account_id' => $result[0]->id       
                ]);

                $success = true;

            }

        }

    }

}

function uploadImage($image_name)
{
    $target_dir = "./../../uploads/";
    $target_file = $target_dir . time() . basename($_FILES[$image_name]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $imageErr = [];

// Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$image_name]["tmp_name"]);
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
    if ($_FILES[$image_name]["size"] > 500000) {
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
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES[$image_name]["tmp_name"], $target_file)) {

        } else {

        }
    }

    if (count($imageErr) != 0) {
        return $imageErr;
    } else {
        return true;
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobPoster Registration</title>
    <?php
include './../layout/bootstrap.php';
?>
</head>

<body>
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
        <br />
        <center>
            <text class="text-muted">
                <h4>Create Your Job Poster Profile</h4>
            </text>
        </center>
        <hr />

        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                <?php
                if($success) {
                    echo '<div class="alert alert-success" role="alert">
                    Job Poster Account Created
                    </div>';
                }else if(count($errs) > 0) {
                    echo '<div class="alert alert-danger" role="alert">';
                    foreach($errs as $e){
                        echo $e."</br>";
                    }
                    echo '</div>';
                }

                ?>
                </div>
                <div class="col-sm-2"></div>
                <div class="col-sm-1"></div>
                <div class="col-sm-5 bg card">
                    <strong class="pt-3">Personal Info</strong><br>

                    <label >Date of Birth</label>

                    <input type="date" class="form-control" name="date_of_birth" value="<?=$date_of_birth?>">

                    <label>Gender</label>
                    <select class="form-control" name="gender">
                        <option selected disabled>Select your Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>

                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="<?=$email?>">

                    <label>Contact No</label>
                    <input type="number" class="form-control" name="contact_number" value="<?=$contact_number?>">

                    <label>Password</label>
                    <input type="password" class="form-control" name="password" value="<?=$password?>">

                    <br />
                    <label>Profile Picture</label>
                    <input type="file" class="btn btn-info" name="profilePicture">
                    <br/>
                </div>

                <div class="col-sm-1"></div>

                <div class="col-sm-5 bg card">
                    <strong class="pt-3">Company Information</strong> <br>
                    <label >Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?=$company_name?>">
                    
                    <label class="">Profile Description</label>
                    <textarea class="form-control" name="profile_description"><?=$profile_description?></textarea>

                    <label>Industry</label>
                    <input type="text" class="form-control" name="industry" value="<?=$industry?>">

                    <label>Company Website</label>
                    <input type="link" class="form-control" name="company_website" value="<?=$company_website?>">

                    <label class="mt-4">Company Logo</label>
                    <input type="file" class="btn btn-info" name="company_logo"> <br />

                </div>

            </div>

            <div class="row">
                <div class="col-sm-12">
                    <center>
                        <input type="submit" class="ml-3 mt-3 btn btn-primary" value="Register As Job Poster">
                    </center>
                </div>
            </div>
        </form>

    </div>
    </div>


</body>

</html>