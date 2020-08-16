<?php
require_once './../../db/db.php';

session_start();

if (!isset($_SESSION['logged_user_id']) || $_SESSION['logged_user_type'] != "job_poster") {

    header("location: ./../auth/login.php");
}

$user_id = $_SESSION['logged_user_id'];



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['close_job_id'];

    $action = update('job_post', [
        'is_active' => '1',
    ], $job_id);

}

$jobs = raw("SELECT job_post.*, job_type.job_type from job_post,job_type where job_post.job_type_id = job_type.id and posted_by_id = '$user_id' and job_post.is_active = '0' ");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Applications</title>
    <?php
        include './../layout/bootstrap.php';
    ?>
</head>

<body>
            <?php
                include './../layout/jobposterheader.php';
            ?>

    <div class="container mt-3">
        <center>
            <h5>
                <text class="text-muted">
                    Posted Jobs
                </text>
            </h5>
        </center>
        <hr />

        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Job Type</th>
                            <th>Published Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            if ($jobs != null) {
                                foreach ($jobs as $j) {
                                    echo "<tr>";
                                    echo "<td>$j->job_title</td>";
                                    echo "<td>$j->job_type</td>";
                                    echo "<td>$j->created_date</td>";
                                    echo "<td><a class='btn btn-primary p-1' href='jobapplieddetails.php?job_post_id=$j->id'>Details</a>";
                                    echo "<button id='$j->id' class='btn btn-danger p-1'>Close Job</button> </td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> Close This Job </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="" method="post">
                            <div class="modal-body">
                                <p>Are you sure you want to close this job?</p>
                                <input type="hidden" name="close_job_id" id="close_job_id">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                    <button type="submit" class="btn btn-danger">Yes</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>

    <script src="./../../js/seekerappliedreview.js"></script>

</body>

</html>