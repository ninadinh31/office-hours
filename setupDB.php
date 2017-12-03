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
    $query = "create table tblqueues (courseid int(11) primary key not null, queueName varchar(30))";// (uid varchar(8) primary key not null, courseid int(11) primary key not null, priority int(11), queuecheckintime datetime, tacheckintime datetime)";
    $connection->query($query);
    $query = "create table tblcourses (courseid int(2) primary key not null, coursename varchar(50), semester enum('F', 'S'),  year int(4))";
    $connection->query($query);
    $query = "create table tblregistered (uid varchar(8) primary key not null, usertype enum('TA', 'Student'), courseid int(2))";
    $connection->query($query);
}

function createQueueDBs($connection){
    $query = "select * from tblcourses";
    $result = $connection->query($query);
    if (!$result) {
        die("Retrieval failed: ". $connection->error);
    } else {
        /* Number of rows found */
        $num_rows = $result->num_rows;
        if($num_rows === 0){
            echo "No courses have been created yet";
        } else {
            for($row_index=0; $row_index < $num_rows; $row_index++){
                $result->data_seek($row_index);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				$id = $row["courseid"];
				$query = "create table queue_$id (uid varchar(8) primary key not null, spot_in_line int, checkintime datetime)";
				$connection->query($query);
            }
        }
    }
    }


?>
