<?php
require_once("dbLogin.php");
require_once("setupDB.php");
session_start();

$db_connection = initDBConnection($host, $user, $dbpassword, $database);

$query = sprintf("select * from tblregistered where uid='%s' and courseid='%s'", $_SESSION['uid'], $_POST['selectCourse']);
$result = $db_connection->query($query);
if (!$result) {
    die("Insertion failed: " . $db_connection->error);
} else {
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
?> <script>
    window.alert("This course is already in your list");
    window.location.href = "classList.php";
</script><?php
        echo "This course is already in your list";
    } else {
        // submit add course
        $query = sprintf("insert into tblregistered (uid, usertype, courseid) values ('%s', '%s', '%s')", $_SESSION['uid'], $_POST['selectUserType'], $_POST['selectCourse']);

        // Executing query
        $result = $db_connection->query($query);
        if (!$result) {
            die("Insertion failed: " . $db_connection->error);
        } else {
            header("Location: classList.php");
        }
    }
}
?>