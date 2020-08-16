<?php

function dbconnection(){
    
    //return $conn = mysqli_connect("localhost", "root", "", "job_portal");
    return $conn = mysqli_connect("localhost", "campb12s_jobfinder", "password", "campb12s_jobfinder");
}

//it takes table name & id and return result by ID
function find($table_name, $id)
{
    $conn = dbconnection();

    $query = "SELECT * FROM " . $table_name . " WHERE id = $id";

    $result = mysqli_query($conn, $query);

    $conn->close();

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    if (count($rows) > 0) {
        return (object) $rows[0];
    } else {
        return null;
    }

}

//it takes table name & returns all row
function all($table_name)
{
    $conn = dbconnection();

    $query = "SELECT * FROM " . $table_name;

    $result = mysqli_query($conn, $query);

    $conn->close();

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = (object) $row;
    }

    if (count($rows) > 0) {
        return $rows;
    } else {
        return null;
    }
}


//it function is for perfroming a raw SQL query & return result set
function raw($query)
{
    $conn = dbconnection();

    $result = mysqli_query($conn, $query);

    $conn->close();

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = (object) $row;
    }
    //echo $query;

    if (count($rows) > 0) {
        return $rows;
    } else {
        return null;
    }

}

//it function is for perfroming a raw SQL query & return status
function rawExec($query)
{
    $conn = dbconnection();

    $result = mysqli_query($conn, $query);

    if (mysqli_query($conn, $query)) {
        $flag = true;
    } else {
        return "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);

}

//it function insert row in any table
function create($table_name, $array)
{
    $conn = dbconnection();

    $query = "INSERT INTO " . $table_name . " (";
    $flag = false;

    foreach ($array as $key => $value) {
        $query .= $key . ' ,';
    }

    $query = rtrim($query, ',');
    $query .= ') values (';

    foreach ($array as $key => $value) {
        $query .= "'" . $value . "' ,";
    }

    $query = rtrim($query, ',');
    $query .= ') ';

    //echo $query;

    if (mysqli_query($conn, $query)) {
        $flag = true;
    } else {
        return "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);

    return $flag;
}

//this function update row in a specific table
function update($table_name, $array, $id)
{
    $conn = dbconnection();

    $query = "UPDATE " . $table_name . " SET ";
    $flag = false;
    foreach ($array as $key => $value) {
        $query .= $key . " = '$value' ,";
    }

    $query = rtrim($query, ',');
    $query .= " WHERE id = '$id'";
    //echo $query;

    if (mysqli_query($conn, $query)) {
        $flag = true;
    } else {
        return "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);

    return $flag;
}

//this function delete row in a specific table
function destroy($table_name, $id)
{
    $conn = dbconnection();

    $query = "DELETE FROM " . $table_name . " WHERE id=$id;";

    if (mysqli_query($conn, $query)) {
        $flag = true;
    } else {
        return "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);

    return $flag;
}
