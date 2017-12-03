<?php

function initDBConnection($host, $user, $dbpassword, $database){

    @$db_connection = new mysqli($host, $user, $dbpassword, $database);
    if ($db_connection->connect_error && $db_connection->connect_errno == 1049) {
        $db_connection = new mysqli($host, $user, $dbpassword);
        createDatabase($db_connection);

        $db_connection = new mysqli($host, $user, $dbpassword, $database);
        createTables($db_connection);
    }
    return $db_connection;
}

function createDatabase($connection) {
    $query = "create database officehours";
    $connection->query($query);
}
function createTables($connection){
    $query = "create table tblusers (firstname varchar(25), lastname varchar(30), uid varchar(8) primary key not null, email varchar (60), password varchar(64))";
    $connection->query($query);
    $query = "create table tblqueue (uid varchar(8, courseid int(11), priority int(11), queuecheckintime datetime, tacheckintime datetime, primary key (uid, courseid))";
    $connection->query($query);
    $query = "create table tblcourses (courseid int(2) primary key not null auto_increment, coursename varchar(50))";
    $connection->query($query);
    $query = "create table tblregistered (uid varchar(8), usertype enum('TA', 'Student'), courseid int(2), primary key(uid, courseid))";
    $connection->query($query);

    $query = "insert into tblcourses (coursename) values ('CMSC389N'), ('CMSC216'), ('CMSC420'), ('CMSC433'), ('CMSC330'), ('CMSC351'), ('CMSC417'), ('CMSC434'), ('CMSC453')";
    $connection->query($query);
    $query = "insert into tblusers (firstname, lastname, uid, email, password) values ('testfirst', 'testlast', '1234', 'email@gmail.com', '1234')";
    $connection->query($query);
}

?>
