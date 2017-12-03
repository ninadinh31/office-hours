<?php
require_once("dbLogin.php");
require_once("setupDB.php");
session_start();

$db_connection = initDBConnection($host, $user, $dbpassword, $database);
$query = sprintf("delete from tblregistered where uid='%s' and courseid='%s'", $_SESSION['uid'], $_GET['courseid']);

/* Executing query */
$result = $db_connection->query($query);
if (!$result) {
    die("Deletion failed: ". $db_connection->error);
} else {
    header("Location: classList.php");
}
?>