<?php
    $server = "localhost";
    $dbuser = "root";
    $dbpassword = "";
    $dbname = "bbq";

    // connect database
    $conn_create_db = new mysqli($server, $dbuser, $dbpassword);
    if ($conn_create_db->connect_error) {
       die ("database connection failed");
    }
    // If database is not exist create one
    if (!mysqli_select_db($conn_create_db, $dbname)){
        $sql = "CREATE DATABASE " . $dbname . " DEFAULT CHARSET utf8";
        echo($sql."<br/>");
        if ($conn_create_db->query($sql) === TRUE) {
            echo "Database created successfully";
        }else {
            die("Error creating database: " . $conn->error);
        }
    }
    $conn_create_db->close();
    // First, establish a new connection for creating table if not exists
    $conn_create_tbl = new mysqli($server, $dbuser, $dbpassword, $dbname);
    if ($conn_create_tbl->connect_error) {
        die ("database connection failed");
    }
    // Second, create table if not exists
    $sql  = "CREATE TABLE IF NOT EXISTS `tblbbq`(";
    $sql .= "name TEXT, district TEXT, district_cn TEXT, address TEXT, longitude TEXT, latitude TEXT) ";
    $sql .= "DEFAULT CHARSET=utf8";
    echo($sql."<br/>");
    if (!$result=$conn_create_tbl->query($sql)){
        die ("failed to create table");
    }
    $conn_create_tbl->close();
    // Third, delete all record(s) in this table
    $conn_del = new mysqli($server, $dbuser, $dbpassword, $dbname);
    if ($conn_del->connect_error) {
        die ("database connection failed");
    }
    $sql = "DELETE FROM `tblbbq`";
    echo ($sql."<br/>");
    if ($conn_del->query($sql) === FALSE) {
        die ("failed to delete table");    
    }
    $conn_del->close();
    // Fourth, establish a new connection for insertion
    $conn = new mysqli($server, $dbuser, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die ("database connection failed");
    }
?>