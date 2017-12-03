<?php
require_once("support.php");
require_once("dbLogin.php");
session_start();
$title = "TA Office Hours Class List";
$errorMessage = "";

// current courses panel
$currentCourses = <<<EOBODY
<style>
.form-group {
    width: 30%;
    margin: auto;
    margin-top: 3.5em;
    padding: 1.25em;
}
a {
    color: #5bc0de;
}
</style>
<div class="form-group panel panel-default">
    <h3><strong>Current Courses:</strong></h3><br/>
    <form action="addCourse.php" name="addCourse" id="addCourse" method="POST">
			<table class="table table-bordered" style="margin-right: 1.2em;">
            <tr>
                <th>Course</th>
                <th>User Type</th>
            </tr>	
EOBODY;

$db_connection = new mysqli($host, $user, $password, $database);
if ($db_connection->connect_error) {
    die($db_connection->connect_error);
}

$query = sprintf("select * from tblregistered join tblcourses on tblregistered.courseid = tblcourses.courseid where uid=%s order by tblcourses.coursename ASC", $_SESSION['uid']);
$result = $db_connection->query($query);

if (!$result) {
    die("Retrieval failed: ". $db_connection->error);
} else {
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        $result->data_seek(0);
        $row = $result->fetch_array(MYSQLI_ASSOC);

        for ($row_index = 0; $row_index < $num_rows; $row_index++) {
            $result->data_seek($row_index);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $currentCourses .= "<tr><td>{$row['coursename']}</td><td>{$row['usertype']}</td>";
            $currentCourses .= "<td><a onclick=\"confirmDelete('{$row['courseid']}')\" title=\"Delete Course\"><span class=\"glyphicon glyphicon-minus-sign\" style=\"font-size: 18px;\"></span></a></td></tr>";
        }
    }
}

$currentCourses .= <<<EOBODY
<td>            
    <select class="form-control" name="selectCourse" required>
    <option value="" disabled selected>Select course</option>
EOBODY;

/* Connecting to the database */
$db_connection = new mysqli($host, $user, $password, $database);
if ($db_connection->connect_error) {
    die($db_connection->connect_error);
}

$query = sprintf("select * from tblcourses");
$result = $db_connection->query($query);

if (!$result) {
    die("Retrieval failed: ". $db_connection->error);
} else {
    $num_rows = $result->num_rows;
    if ($num_rows === 0) {
        echo "Empty Table<br>";
    } else {
        $result->data_seek(0);
        $row = $result->fetch_array(MYSQLI_ASSOC);

        for ($row_index = 0; $row_index < $num_rows; $row_index++) {
            $result->data_seek($row_index);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $currentCourses .= "<option value=\"{$row['courseid']}\">{$row['coursename']}</option>";
        }
        $currentCourses .= "</select></td>";
    }
}

$currentCourses .= <<<EOBODY
    <td><select class="form-control" name="selectUserType" required>
        <option value="" disabled selected>Select user type</option>
        <option value="Student">Student</option>
        <option value="TA">TA</option>
    </select></td>
    <td>
        <a onclick="document.getElementById('addCourse').submit();" title='Add Course'>
            <span class='glyphicon glyphicon-plus-sign' style='font-size: 18px;'></span>
        </a>
    </td>
    </tr>
        
    </table>
	</form>
	
	<a class="btn btn-info" href="#" role="button" style="display: table; margin: 0 auto;">Show Queues</a>
</div>
EOBODY;

$body = $currentCourses;
echo generatePage($body, $title);
?>