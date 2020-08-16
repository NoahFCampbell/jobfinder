<?php 

require_once './../../db/db.php';   

session_start();

if(!isset($_SESSION['logged_user_id']) || $_SESSION['logged_user_type'] != "job_poster"){

    header("location: ./../auth/login.php");
}

$total_job_poster = raw("SELECT count(id) as total from user_account where user_type_id = '1' ");
$total_job_poster = $total_job_poster[0]->total;

$total_job_seeker = raw("SELECT count(id) as total from user_account where user_type_id = '2' ");
$total_job_seeker = $total_job_seeker[0]->total;

$total_job_post = raw("SELECT count(id) as total from job_post ");
$total_job_post = $total_job_post[0]->total;




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Poster Home</title>
    <?php
        include './../layout/bootstrap.php';
    ?>
    <!-- Script source to pull gstatic table from server-->
    <script src="https://www.gstatic.com/charts/loader.js"></script>

</head>

<body>
    <?php
        include "./../layout/jobposterheader.php"
    ?>
    <div class="container">
        <center>
            <div class="display-4 m-4">Welcome to the Job Poster Dashboard</div>
        </center>

        <br />

        <div class="row">
            <div class="col-sm-4">
                <div class="card-header alert alert-primary">
                    <center>Total Job Posts</center>
                </div>
                <div class="card-body bg card">
                    <center>
                        <h4><?=$total_job_post?></h4>
                    </center>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card-header alert alert-info">
                    <center>Total Job Posters</center>
                </div>
                <div class="card-body bg card">
                    <center>
                        <h4><?=$total_job_poster?></h4>
                    </center>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card-header alert alert-secondary">
                    <center>Total Job Seekers</center>
                </div>
                <div class="card-body bg card">
                    <center>
                        <h4><?=$total_job_seeker?></h4>
                    </center>
                </div>
            </div>
        </div>

        <div class="row mt-3 pt-3">
            <div class="col-md-6">
                <div class="card-header alert alert-success">
                    <center>Top 3 Skills In Demand</center>
                </div>
                <div class="card card-body">
                    <div id="skillChart"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-header alert alert-danger">
                    <center>Top 3 Company Posting Jobs</center>
                </div>
                <div class="card card-body">
                    <div id="companyChart"></div>
                </div>
            </div>
        </div>

    </div>

    <script>
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var skillData = google.visualization.arrayToDataTable([
            ['Category', 'Count'],
            <?php
                $datas = raw("SELECT skill_set.skill_set_na, COUNT(job_post_skill_set.skill_set_id) AS total FROM skill_set,job_post_skill_set WHERE skill_set.id = job_post_skill_set.skill_set_id GROUP BY skill_set.skill_set_na ORDER BY total DESC LIMIT 3");
                if($datas != null) {
                    foreach ($datas as $d) {
                        echo "['$d->skill_set_na', $d->total], ";
                    }
                }
            ?>
        ]);

        var skillOptions = {
            title: 'Top 3 Skills',
            height: 400
        };

        var skillChart = new google.visualization.PieChart(document.getElementById('skillChart'));
        skillChart.draw(skillData, skillOptions);


        var companyData = google.visualization.arrayToDataTable([
            ['Category', 'Count'],
            <?php
                $datas = raw("SELECT company.company_name, COUNT(job_post.company_id) AS total FROM company,job_post WHERE company.id = job_post.company_id GROUP BY company.company_name ORDER BY total DESC LIMIT 3");
                if($datas != null) {
                    foreach ($datas as $d) {
                        echo "['$d->company_name', $d->total], ";
                    }
                }
            ?>
        ]);

        var companyOptions = {
            title: 'Top 3 Companies',
            height: 400
        };

        var companyChart = new google.visualization.PieChart(document.getElementById('companyChart'));
        companyChart.draw(companyData, companyOptions);
    }
    </script>

</body>

</html>