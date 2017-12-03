<?php

function initDBConnection($host, $user, $dbpassword, $database){

    $db_connection = new mysqli($host, $user, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }
    return $db_connection;
}

function createDBs($connection){
    $query = "create table tblusers (firstname varchar(25), lastname varchar(30), uid varchar(8) primary key not null, email varchar (60), password varchar(64))";
    $connection->query($query);
    $query = "create table tblqueue (uid varchar(8) primary key not null, courseid int(11) primary key not null, priority int(11), queuecheckintime datetime, tacheckintime datetime)";
    $connection->query($query);
    $query = "create table tblcourses (courseid int(2) primary key not null, coursename varchar(50), semester enum('F', 'S'),  year int(4))";
    $connection->query($query);
    $query = "create table tblregistered (uid varchar(8) primary key not null, usertype enum('TA', 'Student'), courseid int(2))";
    $connection->query($query);
}



?>
