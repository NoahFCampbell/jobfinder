<?php 
require_once './../../db/db.php';

session_start();

if (!isset($_SESSION['logged_user_id']) || $_SESSION['logged_user_type'] != "job_poster") {

    header("location: ./../auth/login.php");
}

if(!isset($_GET['job_post_id']) || $_GET['job_post_id'] == null) {
    header("location: appliedreview.php");
}

$job_post_id = $_GET['job_post_id'];
$applicant_total = raw("SELECT count(job_post_id) as total from job_post_activity where job_post_id = '$job_post_id' ")[0]->total;
$applicants = raw("SELECT * from user_account,job_post_activity,seeker_profile where user_account.id = job_post_activity.user_account_id and user_account.id = seeker_profile.user_account_id and job_post_activity.job_post_id = '$job_post_id'");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Details</title>
    <?php
        include './../layout/bootstrap.php';
    ?>
</head>

<body>
    <?php
        include './../layout/jobposterheader.php';
    ?>

    <div class="container">
        <br />
        <center>
            <text class="text-muted">
                <h5>Job Applicant Details</h5>
            </text>
        </center>
        <hr />

        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <div class="card-header alert alert-info">
                    <center>
                        Total Number of Candidates Who Applied
                    </center>
                </div>

                <div class="card-body bg card">
                    <center>
                        <h4> <?=$applicant_total?> </h4>
                    </center>
                </div>
            </div>
            <div class="col-sm-4"></div>
        </div>

        <br />

        <div class="row">
            <div class="col-sm-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Apply Date</th>
                            <th>Email</th>
                            <th>Resume</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if($applicants != null) {
                            foreach ($applicants as $a) {
                                echo "<tr>";
                                echo "<td>$a->first_name, $a->last_name</td>";
                                echo "<td>$a->apply_date</td>";
                                echo "<td>$a->email</td>";
                                echo "<td><a class='btn btn-primary' href='$a->resume' download>Download</a></td>"; 
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>