<?php
require_once './../../db/db.php';
session_start();

if(!isset($_GET['job_post_id'])) {
    header("location: ./searchjob.php");
    return;
}

$job_id = $_GET['job_post_id'];
$job = raw("select * from job_post,job_location,job_type,company where job_post.job_location_id = job_location.id and job_post.job_type_id = job_type.id and job_post.company_id = company.id and job_post.id = '$job_id'")[0];
$skill_sets = raw("select * from job_post_skill_set, skill_set where skill_set.id = job_post_skill_set.skill_set_id and job_post_skill_set.job_post_id = $job_id");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Description</title>

    <!-- Required Links -->
    <?php
        include './../layout/bootstrap.php';
    ?>
</head>

<body>
    <?php
        include './../layout/jobseekerheader.php';
    ?>

    <div class="container">
        <br />
        <h4>Job Details</h4>

        <div class="row">
            <div class="col-sm-7">
                <text class="text tex-primary">
                    <strong>Job Title: <?=$job->job_title?></strong> <br>
                    <text>Job Type: <?=$job->job_type?><text>
                        </text>
                        <br />
                        <text>Company: <?=$job->company_name?></text>


                        <!-- Job Responsibilities -->

                        <div>
                            <h5 class="mt-3">Job Description</h5>

                            <p><?=$job->job_description?></p>
                        </div>

                        <!-- Skill Sets -->
                        <div>
                            <h5>Skill Sets</h5>
                            <ul>
                                <?php foreach ($skill_sets as $s) {
                           echo "<li>$s->skill_set_na</li>";
                        }?>
                            </ul>
                        </div>

                        <!-- Job Location -->
                        <div>
                            <h5>Job Location</h5>
                            <ul>
                                <li><?="$job->city, $job->province, $job->country"?></li>
                            </ul>
                        </div>

            </div>

            <!-- Job Summary -->
            <div class="col-sm-5">
                <div class="card-body alert alert-primary">
                    <center>Job Summary</center>
                </div>

                <div class="card-body">
                    <table>
                        <tr>
                            <td>
                                <b>Published on </b>
                            </td>
                            <td><?=$job->created_date?></td>
                        </tr>



                        <tr>
                            <td>
                                <b>Job Location </b>
                            </td>
                            <td><?="$job->city, $job->province, $job->country"?></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

        <!-- Read Before Apply -->
        <div class="row">
            <div class="col-sm-12">
                <center>
                    <h5>Read Before Applying</h5>
                </center>
                <hr>
                <!-- Small Description -->
                <p>
                    To apply, please ensure you have your resume ready and that you meet at least 60% of the job requirements. If you are selected for the next round of the application process, you will be contacted via email.
                </p>
                <center>
                    <!-- Apply Procedure -->
                    <div>
                        <br />
                        <!-- Apply Button -->
                        <a class="btn btn-primary" href="./jobapply.php?job_post_id=<?=$job_id?>">
                            Apply For This Job
                        </a>
                        <br />
                        <br />

                    </div>
                </center>
            </div>
        </div>

        <!-- Company Information -->
        <div class="row">
            <div class="col-sm-12">
                <h5>Published on</h5>
                <text class="text-muted"><?=$job->created_date?></text>

                <br />
                <h5 class="mt-3">Company Information</h5>
                <text><?=$job->company_name?></text>
                <br />
                <text><?=$job->industry?></text>
                <br />
                <text><?=$job->company_website?></text>
                <br />
                <text><?=$job->profile_description?></text>
            </div>
        </div>
    </div>
</body>

</html>