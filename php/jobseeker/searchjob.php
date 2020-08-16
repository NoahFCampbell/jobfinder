<?php
require_once './../../db/db.php';

session_start();


if(!isset($_SESSION['logged_user_id']) || $_SESSION['logged_user_type'] != "job_seeker"){

    header("location: ./../auth/login.php");
}

$job_type = all('job_type');

$jobs = raw("select job_post.job_title, job_post.id, job_type.job_type, company.company_name, job_location.city, job_location.province, job_location.country from job_post,job_location,job_type,company where job_post.job_location_id = job_location.id and job_post.job_type_id = job_type.id and job_post.company_id = company.id and job_post.is_active = '0'");

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Jobs</title>
    <!-- Required Links -->
    <?php
include '../layout/bootstrap.php';
?>
</head>

<body>
    <?php
        include "../layout/jobseekerheader.php"
    ?>
    <br />
    <div class="container">
        <center>
            <h2>Search Jobs</h2>
        </center>
        <div class="row">
            <!-- Search Input Field -->
            <div class="col-sm-4">
                <input type="text" id="searchText" class="form-control" name="search" placeholder="Search Job Title...">
            </div>

            <!-- ComboBox -->
            <div class="col-sm-4">
                <select class="form-control" name="cmbo" id="cmbo">
                    <option selected disabled>Select Job Type</option>
                    <?php foreach ($job_type as $e) {
                                echo "<option value='$e->job_type'>$e->job_type</option>";
                               }
                                ?>
                </select>
            </div>

            <!-- Date -->
            <div class="col-sm-4">
                <input id="date" type="date" name="date" class="form-control">
            </div>


        </div>

        <!-- SubTitle Row -->
        <div class="row">
            <div class="col-sm-12">
                <br />
                <strong>Your Next Opportunity Awaits You!</strong> <br />
                <br />
                Welcome to JobFinder's easy to use job search dashboard! Feel free to search by job title, work type, or location to find your next dream career!
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table id="table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Job Type</th>
                            <th>Company Name</th>
                            <th>Location</th>
                        </tr>
                    </thead>

                    <tbody id="tbody">

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

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="./../../js/searchjob.js"></script>

</body>

</html>