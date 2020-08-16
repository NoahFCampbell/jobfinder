<?php 
require_once './../../db/db.php';


$type = $_GET['job_post_type'];
$text = $_GET['job_post_text'];
$date = $_GET['job_post_date'];

$query = "";

if(!empty($type)){
    $query.= " and job_type.job_type = '$type'";
}

if(!empty($text)){
    $query.= " and job_post.job_title like '$text%' ";
}

$jobs = raw("SELECT job_post.id, job_post.job_title, job_type.job_type, company.company_name, job_location.city, job_location.province, job_location.country from job_post,job_location,job_type,company where job_post.job_location_id = job_location.id and job_post.job_type_id = job_type.id and job_post.company_id = company.id $query and job_post.is_active = '0'");

echo json_encode($jobs);
?>