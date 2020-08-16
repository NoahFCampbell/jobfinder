<?php
require_once './../../db/db.php';
session_start();

if (!isset($_SESSION['logged_user_id']) || $_SESSION['logged_user_type'] != "job_seeker") {

    header("location: ./../auth/login.php");
}

if (!isset($_GET['job_post_id'])) {
    header("location: ./searchjob.php");
    return;
}

$job_id = $_GET['job_post_id'];

$job = raw("SELECT * from job_post where id= '$job_id' ")[0];
$errs = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $resume = $_FILES['resume']["name"];

    if (empty($resume)) {
        $errs[] = "*Resume is required";
    }

    if (count($errs) == 0) {
        $uploadStatus = uploadImage("resume");
        $uploadDir = "./../../uploads/" . time() . basename($_FILES["resume"]["name"]);

        
      
        if($uploadStatus == "OK")
        {
            $action = create("job_post_activity", [
                'user_account_id' => $_SESSION["logged_user_id"],
                'job_post_id' => $job_id,
                'apply_date' => date("Y/m/d"),
                'resume' => $uploadDir,
            ]);
            $success = $action;
        }else {
            $errs = $uploadStatus;
        }
    
    }

}

function uploadImage($image_name)
{
    $target_dir = "./../../uploads/";
    $target_file = $target_dir . time() . basename($_FILES[$image_name]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $errs = [];
// Check if file already exists
    if (file_exists($target_file)) {
        $errs[] = "Sorry, file already exists.";
        $uploadOk = 0;
    }

// Check file size
    if ($_FILES[$image_name]["size"] > 500000) {
        $errs[] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

// Allow certain file formats
    if ($imageFileType != "pdf") {
        $errs[] = "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $errs[] = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES[$image_name]["tmp_name"], $target_file)) {

        } else {
            $errs[] = "Sorry, there was an error uploading your file.";
        }
    }

    

    if (count($errs) != 0) {
        return $errs;
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
    <title>Apply Job</title>

    <?php
include './../layout/bootstrap.php';
?>
</head>

<body>
    <?php
include "./../layout/jobseekerheader.php"
?>
    <div class="container bg card mt-4">
        <br />
        <center>
            <h4>
                <text class="text-muted">
                    Apply Job Online
                </text>
            </h4>
        </center>
        <hr>

        <div class="row">
            <div class="col-sm-2">
                <!-- Right Empty Div -->
            </div>
            <div class="col-sm-8">
                <?php
                    if ($success) {
                        echo '<div class="alert alert-success" role="alert">
                                        Job Application Succesful
                                        </div>';
                    } else if (count($errs) > 0) {
                        echo '<div class="alert alert-danger" role="alert">';
                        foreach ($errs as $e) {
                            echo $e . "</br>";
                        }
                        echo '</div>';
                    }
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <table width="100%">
                        <tr>
                            <td>Job Title</td>
                            <td>
                                <input type="text" class="form-control" name="jobTitle" disabled
                                    value="<?=$job->job_title?>">
                            </td>
                        </tr>

                        <tr>
                            <td>Upload Resume</td>
                            <td>
                                <input type="file" class="ml-0 pl-0 btn btn-light" name="resume">
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" class="btn btn-primary" value="Apply">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="col-sm-2">
                <!-- Left Empty Div -->
            </div>
        </div>
    </div>
</body>

</html>