<?php
require_once("dbLogin.php");
session_start();
$db_connection = new mysqli($host, $user, $password, $database);
if ($db_connection->connect_error) {
    die($db_connection->connect_error);
}
$query = sprintf("delete from tblregistered where uid='%s' and courseid='%s'", $_SESSION['uid'], $_GET['courseid']);

/* Executing query */
$result = $db_connection->query($query);
if (!$result) {
    die("Deletion failed: ". $db_connection->error);
} else {
    header("Location: classList.php");
}
?>