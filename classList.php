<?php
require_once("support.php");
require_once("dbLogin.php");

$title = "TA Office Hours Class List";
$currentCourses = <<<EOBODY
<style>
.form-group {
    width: 30%;
    margin: auto;
    margin-top: 3.5em;
    padding: 1.25em;
}
</style>
<div class="form-group panel panel-default">
    <h1>Current Courses:</h1><br/>
		<form action="{$_SERVER['PHP_SELF']}" method="post">
			<table class="table table-bordered" style="margin-right: 1.2em;">
            <tr>
                <th>Course</th>
                <th>User Type</th>
            </tr>	
        </table>
		</form>
</div>
EOBODY;

$addCourse= <<<EOBODY
<div class="form-group panel panel-default">
    <h1>Add a Course:</h1><br/>
		<form action="{$_SERVER['PHP_SELF']}" method="post">
            <select class="form-control">
                <option value="" disabled selected>Select your option</option>
EOBODY;

/* Connecting to the database */
$db_connection = new mysqli($host, $user, $password, $database);
if ($db_connection->connect_error) {
    die($db_connection->connect_error);
}

$query = sprintf("select * from tblcourses");

/* Executing query */
$result = $db_connection->query($query);
if (!$result) {
    die("Retrieval failed: ". $db_connection->error);
} else {
    /* Number of rows found */
    $num_rows = $result->num_rows;
    if ($num_rows === 0) {
        echo "Empty Table<br>";
    } else {
        $result->data_seek(0);
        $row = $result->fetch_array(MYSQLI_ASSOC);

        for ($row_index = 0; $row_index < $num_rows; $row_index++) {
            $result->data_seek($row_index);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $addCourse .= "<option>{$row['coursename']}</option>";
        }
        $addCourse .= "</select><br/>";
    }
}

$addCourse .= <<<EOBODY
<input type="submit" class="btn btn-info" name="submitAddCourse" value="Add Course" style="display: table; margin: 0 auto;"/>
</form></div>
EOBODY;


$body = $currentCourses . $addCourse;
# Generating final page
echo generatePage($body, $title);
?>