<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobFinder Home</title>

    <?php
        include './php/layout/bootstrap.php';
    ?>
</head>
<body>
    <!-- Header Layout -->
    <?php
        include './php/layout/indexheader.php';
    ?>
  
    <br/>

    <center>
        <h1>Welcome to JobFinder!</h1>
    </center>

    <br/>
    <div class="container">
        <!-- Buttons -->
        <div class="row">
            <div class="col-sm-12">
                <center>
                    <a href="php/auth/login.php"> <button class="btn btn-success">Find Jobs</button> </a> &nbsp;&nbsp;&nbsp;
                    <a href="php/auth/login.php"> <button class="btn btn-success">Post a Job</button> </a> 
                </center>
            </div>
        </div>
        
        <br/>
        <div class="row">
            <div class="col-sm-12">
                <center>
                  <p>JobFinder is the home of your next great talent connection!</p>
                  <p>Whether you are a Job Seeker looking for your next great role or a Job Poster looking to connect with top tech talent, JobFinder has something for you!</p>
                  <p>Get started by choosing an option above.</p>
                </center>
            </div>
        </div>

        <!-- Bottom -->
        <div class="row">
            <div class="col-sm-12 mt-3 pt-3">
                <div class="d-flex">
                </div>
            </div>
        </div>
    </div>
</body>
</html>