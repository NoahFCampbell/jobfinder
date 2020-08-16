<?php 
require_once './../../db/db.php';   

session_start();

if(!isset($_SESSION['logged_user_id']) || $_SESSION['logged_user_type'] != "job_seeker"){

    header("location: ./../auth/login.php");
}

$user_id = $_SESSION['logged_user_id'];

$job_seeker = raw("SELECT * from seeker_profile where user_account_id = $user_id")[0];

$jobs = raw("select job_post.job_title, job_post.id, job_type.job_type, company.company_name, job_location.city, job_location.province, job_location.country from job_post,job_location,job_type,company where job_post.job_location_id = job_location.id and job_post.job_type_id = job_type.id and job_post.company_id = company.id and job_post.is_active = '0'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Home</title>
    <?php
        include './../layout/bootstrap.php';
    ?>
</head>
<body>
    <?php
        include './../layout/jobseekerheader.php';
    ?>

    <div class="container">
        <center>
            <h1>Welcome, Job Seeker, <?=$job_seeker->first_name ." ". $job_seeker->last_name?></h1>
        </center>
        
        <br/>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div class="card-header">
                    <span class="pt-3 pr-2">All Job Posts</span> | <a href="./searchjob.php"> <input type="submit" class="pt-1 btn btn-light" value="Find Jobs Here"> </a>
                </div>

                <div class="card-body">
                    <table id="table" class="table table-hover">
                        <tr>
                            <th>Job Title</th>
                            <th>Job Type</th>
                            <th>Company Name</th>
                            <th>Location</th>
                            
                        </tr>

                        <?php
                            if($jobs != null) {
                                foreach ($jobs as $j) {

                                    echo "<tr id='$j->id'>";
                                    echo "<td>$j->job_title</td>";
                                    echo "<td>$j->job_type</td>";
                                    echo "<td>$j->company_name</td>";
                                    echo "<td>$j->city, $j->province, $j->country</td>";
                                    echo "</tr>";
    
                                }
                            }
                        ?>
                    </table>
                </div>
            </div>
            <div class="col-sm-2"></div>
            
        </div>
    </div>
    <script src="./../../js/seekerhome.js"></script>
</body>
</html>