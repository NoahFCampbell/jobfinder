<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <?php
        include './../layout/bootstrap.php';
    ?>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="./../../images/logo.png" alt="logo" style="width:60px; height:auto">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../../index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                   
                    <a class="nav-link" href="./../auth/login.php">Find Jobs</a>

                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./../auth/login.php">Post a Job</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./../auth/login.php">Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./../auth/middlepage.php">Signup</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="card-header">
                    <center>
                        <a href="./../jobseeker/createprofile.php">
                            <input type="submit" class="btn btn-primary" value="I am a Job Seeker">
                        </a>
                    </center>
                </div>

                <div class="card-body">
                    <img src="../../images/jobposter1.jpg" alt= "job openings sign" height="400px" width="100%">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card-header">
                    <center>
                        <a href="./../jobposter/createprofile.php">
                            <input type="submit" class="btn btn-primary" value="I am a Job Poster">
                        </a>
                    </center>
                </div>

                <div class="card-body">
                    <img src="../../images/jobseeker.png" alt="post your job ad sign" height="400px" width="100%">
                </div>
            </div>
        </div>
    </div>
</body>
</html>