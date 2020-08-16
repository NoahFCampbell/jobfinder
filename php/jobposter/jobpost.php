<?php

require_once './../../db/db.php';

session_start();

if (!isset($_SESSION['logged_user_id']) || $_SESSION['logged_user_type'] != "job_poster") {

    header("location: ./../auth/login.php");
}

$job_types = all("job_type");
$skill_sets = all("skill_set");
$user_id = $_SESSION['logged_user_id'];
$company_id = raw("SELECT * from company where user_account_id = '$user_id' ")[0]->id;

$errs = [];

$job_type = $job_description = $skill_set = $city = $provience = $country = $skill_options = $job_title = "";
$success = false;

if ($skill_sets != null) {
    foreach ($skill_sets as $s) {
        $skill_options .= "<option value='$s->id' >$s->skill_set_na</option>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $job_title = $_POST['job_title'];
    $job_type = isset($_POST['job_type']) ? $_POST['job_type'] : null;
    $job_description = $_POST['job_description'];
    $skill_set = isset($_POST['skill_set']) ? $_POST['skill_set'] : null;
    $skill_set_level = isset($_POST['skill_set_level']) ? $_POST['skill_set_level'] : null;
    $city = $_POST['city'];
    $provience = $_POST['provience'];
    $country = $_POST['country'];

    if (empty($job_title)) {
        $errs[] = "*Job Title is required";
    }

    if ($job_type == null) {
        $errs[] = "*Job Type is required";
    }

    if (empty($job_description)) {
        $errs[] = "*Job Description is required";
    }

    if ($skill_set == null || $skill_set_level == null) {
        $errs[] = "*Skill is required";
    }

    if (empty($city)) {
        $errs[] = "*City is required";
    }

    if (empty($provience)) {
        $errs[] = "*Province is required";
    }

    if (empty($country)) {
        $errs[] = "*Country is required";
    }

    if(count($errs) == 0) {
        
        $action = create("job_location", [
            'city' => $city,
            'province' => $provience,
            'country' => $country         
        ]);

        $job_location_id = raw("SELECT * FROM job_location ORDER BY ID DESC LIMIT 1")[0]->id;

        $action = create("job_post", [
            'posted_by_id' => $user_id,
            'job_type_id' => $job_type,
            'company_id' => $company_id,
            'created_date' => date("Y/m/d"),
            'job_title' => $job_title,
            'job_description' => $job_description,
            'job_location_id' => $job_location_id,
            'is_active' => '0'
        ]);

        $job_post_id = raw("SELECT * FROM job_post ORDER BY ID DESC LIMIT 1")[0]->id;

        for ($i=0; $i < count($skill_set); $i++) { 
            $action = create("job_post_skill_set", [
                'skill_set_id' => $skill_set[$i],
                'job_post_id' => $job_post_id,
                'skill_level' => $skill_set_level[$i],  
            ]);
        }

        $success = true;


    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job Post</title>
    <?php
            include './../layout/bootstrap.php';
        ?>
</head>

<body>
    <?php
        include "./../layout/jobposterheader.php";
    ?>
    <div class="container mt-3">
        <center>
            <text class=" text-muted">
                <h5>Post a New Job</h5>
            </text>
        </center>
        <hr />
        <div class="row mx-auto">
        <div class="col-sm-2"></div>
            <div class="col-md-8">
                <?php
                if($success) {
                    echo '<div class="alert alert-success" role="alert">
                    Job Posted Successfully
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
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <form action="" method="post">

                    <div class="row">
                        <div class="col">
                            <label>Job Title</label>
                            <input type="text" class="form-control" name="job_title" value="<?=$job_title?>">
                        </div>
                        <div class="col">
                            <label>Job Type</label>
                            <select class="form-control" name="job_type">
                                <option selected disabled>Select Job Type</option>
                                <?php
                            if ($job_types != null) {
                                foreach ($job_types as $j) {
                                    echo "<option value='$j->id'>$j->job_type</option>";
                                }
                            }
                        ?>
                            </select>
                        </div>
                    </div>


                    <br />


                    <label>Job Description</label>
                    <textarea class="form-control" name="job_description"><?=$job_description?></textarea>

                    <div class="col-md-8 ml-0 pl-0">
                        <div class="table-repsonsive mt-3">
                            <table class="table table-bordered" id="item_table">
                                <thead>
                                    <tr>
                                        <td class="text-center p-4">Job Skill Set</td>
                                        <td class="text-center p-4">Job Skill Level</td>
                                        <td class="text-center p-3"><button type="button" name="add" id="add-btn"
                                                class="btn btn-success"><strong>+</strong></button></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Job skill will be appended here dynamically-->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="container bg card pt-3">
                        <label>Job Location</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="City" name="city" value="<?=$city?>">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Province" name='provience' value="<?=$provience?>">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Country" name='country' value="<?=$country?>">
                            </div>
                        </div>
                        <br />
                    </div>

                    <br />
                    <input type="submit" class="btn btn-primary" value="Post Job">
                </form>
            </div>
            <div class="col-sm-2"></div>
            </center>
        </div>
    </div>
    <script>
    $(document).ready(() => {

        $(document).on('click', '#add-btn', function() {

            var html = '';
            html += '<tr>';

            html +=
                `<td><select name="skill_set[]" class="form-control"><option selected disabled>Select Skill</option><?php echo $skill_options ?></select></td>`;

            html +=
                `<td><select name="skill_set_level[]" class="form-control">
                    <option>Basic</option>
                    <option>Intermediate</option>
                    <option>Expert</option>
                    </select></td>`;

            html +=
                '<td class="text-center" ><button type="button" name="remove" class="btn btn-danger remove"><strong>-</strong></button></td>';
            $('tbody').append(html);
        });

        $(document).on('click', '.remove', function() {
            $(this).closest('tr').remove();
        });

    });
    </script>

</body>

</html>