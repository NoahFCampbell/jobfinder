<?php

require_once './../../db/db.php';

session_start();

if(!isset($_SESSION['logged_user_id']) || $_SESSION['logged_user_type'] != "job_seeker"){

    header("location: ./../auth/login.php");
}



$user_id = $_SESSION["logged_user_id"];

$user_account = find("user_account", $user_id);
$seeker_profile = raw("select * from seeker_profile where user_account_id = '$user_id'")[0];

$firstName = $seeker_profile->first_name;
$lastName = $seeker_profile->last_name;
$date_of_birth = $user_account->date_of_birth;
$gender = $user_account->gender;
$email = $user_account->email;
$contact_number = $user_account->contact_number;
$user_image = $user_account->user_image;

$education_detail = raw("select * from education_detail where user_account_id = '$user_id'")[0];
$major = $education_detail->major;
$certificate_degree_name = $education_detail->certificate_degree_name;
$university_name = $education_detail->university_name;
$starting_date = $education_detail->starting_date;
$completion_date = $education_detail->completion_date;
$gpa = $education_detail->gpa;

$experience_detail = raw("select * from experience_detail where user_account_id = '$user_id'")[0];
$start_date = $experience_detail->start_date;
$end_date = $experience_detail->end_date;
$job_title = $experience_detail->job_title;
$company_name = $experience_detail->company_name;
$job_location_city = $experience_detail->job_location_city;
$job_location_provin = $experience_detail->job_location_provin;
$job_location_countr = $experience_detail->job_location_countr;

$seeker_skill_set = raw("select * from seeker_skill_set, skill_set where seeker_skill_set.skill_set_id = skill_set.id and user_account_id = '$user_id'");
$skill_set = all("skill_set");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['form_type'] == "personal_information") {
        
        $errs = [];
        $gender = '';
        $user_type_id = 3;
        $email = $_POST['email'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        $date_of_birth = $_POST['date_of_birth'];
        $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
        $contact_number = $_POST['contact_number'];
        

        if (empty($email)) {
            $errs[] = "*email is required";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errs[] = "*Invalid Email Address";
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

        $uploadDir = false;

        if (count($errs) == 0) {

            if (!empty($_FILES['profilePicture']["name"])) {
                $uploadStatus = uploadImage();
                $uploadDir = "./../../uploads/" . time() . basename($_FILES["profilePicture"]["name"]);
                
            } else {

            }

            $datas = [
                'email' => $email,
                'date_of_birth' => $date_of_birth,
                'gender' => $gender,
                'contact_number' => $contact_number,
                'user_image' => $uploadDir != false ? $uploadDir : $user_image,
            ];
            $user_image = $uploadDir != false ? $uploadDir : $user_image;
            $action = update("user_account", $datas, $user_id);
            rawExec("update seeker_profile set first_name = '$firstName', last_name = '$lastName' where user_account_id = '$user_id'");

        }

    } else if ($_POST['form_type'] == "education_detail") {
        $major = $_POST['major'];
        $certificate_degree_name = $_POST['certificate_degree_name'];
        $university_name = $_POST['university_name'];
        $starting_date = $_POST['starting_date'];
        $completion_date = $_POST['completion_date'];
        $gpa = $_POST['gpa'];

        rawExec("update education_detail set certificate_degree_name = '$certificate_degree_name', major = '$major', university_name = '$university_name', starting_date = '$starting_date', completion_date = '$completion_date', gpa = '$gpa' where user_account_id = '$user_id'");

    } else if ($_POST['form_type'] == "experience_detail") {
     
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $job_title = $_POST['job_title'];
        $company_name = $_POST['company_name'];
        $job_location_city = $_POST['job_location_city'];
        $job_location_provin = $_POST['job_location_provin'];
        $job_location_countr = $_POST['job_location_countr'];

        rawExec("UPDATE experience_detail set start_date = '$start_date', end_date = '$end_date', job_title = '$job_title', company_name = '$company_name', job_location_city = '$job_location_city', job_location_provin = '$job_location_provin', job_location_countr = '$job_location_countr' where user_account_id = '$user_id'");

    } else if ($_POST['form_type'] == "skill_set") {
    
        if( isset($_POST['skill_set_id']) and isset($_POST['skill_set_level']) ) {

            $skill_set_id = $_POST['skill_set_id'];
            $skill_set_level = $_POST['skill_set_level'];
            
            create("seeker_skill_set", [
                "user_account_id" => $user_id,
                "skill_set_id"	=> $skill_set_id,
                "skill_level" => $skill_set_level
            ]);

            $seeker_skill_set = raw("SELECT * from seeker_skill_set, skill_set where seeker_skill_set.skill_set_id = skill_set.id and user_account_id = '$user_id'");
        }
        
    } else if ($_POST['form_type'] == "skill_delete") {
    
        $skill_id = $_POST["delete_skill_id"];

        rawExec("DELETE FROM seeker_skill_set WHERE skill_set_id = '$skill_id'");

        
        $seeker_skill_set = raw("SELECT * from seeker_skill_set, skill_set where seeker_skill_set.skill_set_id = skill_set.id and user_account_id = '$user_id'");
    }

}

function uploadImage()
{
    $target_dir = "./../../uploads/";
    $target_file = $target_dir . time() . basename($_FILES["profilePicture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $imageErr = [];

// Check if image file is a actual image or fake image
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
    <title>Update Profile</title>
    <!-- Required Links -->
    <?php
        include './../layout/bootstrap.php';
    ?>
</head>

<body>
    <?php
include "./../layout/jobseekerheader.php"
?>

    <div class="container">
        <br />
        <center>
            <h2>Update Profile</h2>
        </center>
        <div class="row">
            <div class="card-body">
                <div class="table">
                    <div class="row">
                        <div class="container">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#myProfile">Personal
                                        Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#educationalDetails">Education</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#experience">Experience</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#skills">Skills</a>
                                </li>
                            </ul>
                            <!--Tab Panes-->
                            <div class="tab-content">
                                <!-- My Profile -->
                                <div id="myProfile" class="container tab-pane active"><br>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="container ">

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

                                                        <option <?php echo $gender == "m" ? "selected" : ""?>>Male
                                                        </option>
                                                        <option <?php echo $gender == "f" ? "selected" : ""?>>Female
                                                        </option>
                                                    </select>

                                                    <label>Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="<?=$email?>">

                                                    <label>Contact No</label>
                                                    <input type="number" class="form-control" name="contact_number"
                                                        value="<?=$contact_number?>">

                                                    <input type="hidden" name="form_type" value="personal_information">

                                                    <br />

                                                    <center>
                                                        <a href="#">
                                                            <input type="submit" value="Save" class="btn btn-info">
                                                        </a>
                                                    </center>


                                                </div>
                                            </div>
                                            <!-- //Profile Picture -->
                                            <div class="col-sm-4">
                                                <div class="card shadow mb-4">
                                                    <div class="card-header py-3">
                                                        <h6 class="m-0 font-weight-bold text-info">Profile Picture</h6>
                                                    </div>
                                                    <div class="card-body">

                                                        <center>
                                                            <img class="rounded-circle z-depth-2" height="200px"
                                                                width="200px" src="<?=$user_image?>">
                                                        </center>
                                                    </div>
                                                    <div class="card-footer">
                                                        <center>
                                                            <input type="file" class="btn btn-info"
                                                                name="profilePicture">
                                                        </center>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="#">

                                                    </a>
                                                </div>

                                            </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Educational Details -->
                            <div id="educationalDetails" class="container tab-pane"><br>
                                <center>
                                    <h5>Educational Details</h5>
                                </center>
                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-8">
                                        <form action="" method="post">
                                            <label>Major Name</label>
                                            <input type="text" class="form-control" name="major" value="<?=$major?>">

                                            <label>Degree Name</label>
                                            <input type="text" class="form-control" name="certificate_degree_name"
                                                value="<?=$certificate_degree_name?>">

                                            <label>University</label>
                                            <input type="text" class="form-control" name="university_name"
                                                value="<?=$university_name?>">


                                            <lable>Starting Date</lable>
                                            <input type="date" class="form-control" name="starting_date"
                                                value="<?=$starting_date?>">


                                            <lable>Completion Date</lable>
                                            <input type="date" class="form-control" name="completion_date"
                                                value="<?=$completion_date?>">

                                            <lable>GPA</lable>
                                            <input type="text" class="form-control" name="gpa" value="<?=$gpa?>">

                                            <input type="hidden" name="form_type" value="education_detail">

                                            <br />

                                            <center>
                                                <input type="submit" name="btnSave" class="btn btn-info" value="Update"
                                                    value="">
                                            </center>
                                        </form>
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>
                            </div>

                            <!-- Experience Tab -->
                            <div id="experience" class="container tab-pane">
                                <center>
                                    <h5>Experience Details</h5>
                                </center>
                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-8">
                                        <form action="" method="post">

                                            <lable>Start Date</lable>
                                            <input type="date" class="form-control" name="start_date"
                                                value="<?=$start_date?>">

                                            <lable>End Date</lable>
                                            <input type="date" class="form-control" name="end_date"
                                                value="<?=$end_date?>">

                                            <lable>Job Title</lable>
                                            <input type="text" class="form-control" name="job_title"
                                                value="<?=$job_title?>">

                                            <label>Company Name</label>
                                            <input type="text" class="form-control" name="company_name"
                                                value="<?=$company_name?>">

                                            <label>City</label>
                                            <input type="text" class="form-control" name="job_location_city"
                                                value="<?=$job_location_city?>">

                                            <label>Provin</label>
                                            <input type="text" class="form-control" name="job_location_provin"
                                                value="<?=$job_location_provin?>">

                                            <label>Country</label>
                                            <input type="text" class="form-control" name="job_location_countr"
                                                value="<?=$job_location_countr?>">

                                            <input type="hidden" name="form_type" value="experience_detail">

                                            <br />

                                            <center>
                                                <input type="submit" name="btnSave" class="btn btn-info" value="Update">
                                            </center>

                                        </form>
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>
                            </div>

                            <!-- Skills Tab -->
                            <div id="skills" class="container tab-pane">
                                <center>
                                    <h5>Skills</h5>
                                </center>
                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-8">
                                        <form action="" method="post">

                                            <label>Skill Name</label>
                                            <select class="form-control" name="skill_set_id">
                                                <option selected disabled>Select Level</option>
                                                <?php 
                                                    if($skill_set != null) {
                                                        foreach ($skill_set as $s) {
                                                            echo " <option value='$s->id'>$s->skill_set_na</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>

                                            <label>Skill Level</label>
                                            <select class="form-control" name="skill_set_level">
                                                <option selected disabled>Select Level</option>
                                                <option value="Basic">Basic</option>
                                                <option value="Intermediate">Intermediate</option>
                                                <option value="Expert">Expert</option>
                                            </select>

                                            <input type="hidden" name="form_type" value="skill_set">
                                            <br />

                                            <center>
                                                <input type="submit" name="btnSave" class="btn btn-info"
                                                    value="Add Skill">
                                            </center>
                                        </form>

                                        <br />

                                        <center>
                                            <h5>
                                                <text class="text-muted">My Skills</text>
                                            </h5>
                                        </center>
                                        <hr />

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Level</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody id="skill_set_tbody">
                                                <?php 
                                                    if($seeker_skill_set != null) {
                                                        foreach ($seeker_skill_set as $s) {
                                                            echo "<tr>";
                                                            echo "<td>$s->skill_set_na</td>";
                                                            echo "<td>$s->skill_level</td>";
                                                            echo "<td><button type='button' id='$s->skill_set_id' class='btn btn-danger'> Delete </button></td>";
                                                            echo "</tr>";
                                                        }
                                                    }                                             
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Skills Modal -->
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel"> Confirm </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="" method="post">
                                                    <div class="modal-body">
                                                        <p>Are you sure?</p>
                                                        <input type="hidden" name="form_type" value="skill_delete">
                                                        <input type="hidden" name="delete_skill_id" id="delete_skill_id">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger">Yes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="./../../js/seekerupdateprofile.js"></script>
</body>

</html>