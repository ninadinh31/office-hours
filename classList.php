<?php
require_once("support.php");
require_once("dbLogin.php");
session_start();
$title = "TA Office Hours Class List";
$errorMessage = "";

// add courses panel
$addCourse= <<<EOBODY
<div class="form-group panel panel-default">
    <h3><strong>Add a Course:</strong></h3><br/>
		<form action="{$_SERVER['PHP_SELF']}" method="post">
		    <p><strong>Course:</strong></p>
            <select class="form-control" name="selectCourse" required>
                <option value="" disabled selected>Select your course</option>
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
            $addCourse .= "<option value=\"{$row['courseid']}\">{$row['coursename']}</option>";
        }
        $addCourse .= "</select><br/>";
    }
}

$addCourse .= <<<EOBODY
<p><strong>User Type:</strong></p>
<select class="form-control" name="selectUserType" required>
    <option value="" disabled selected>Select your user type</option>
    <option value="Student">Student</option>
    <option value="TA">TA</option>
</select><br/>

<input type="submit" class="btn btn-info" name="submitAddCourse" value="Add Course" style="display: table; margin: 0 auto;"/>
</form>
EOBODY;

if (isset($_POST["submitAddCourse"])) {
    $query = sprintf("select * from tblregistered where uid='%s' and courseid='%s'", $_SESSION['uid'], $_POST['selectCourse']);
    $result = $db_connection->query($query);
    if (!$result) {
        die("Insertion failed: " . $db_connection->error);
    } else {
        $num_rows = $result->num_rows;
        if ($num_rows > 0) {
            $errorMessage = "<p>This course is already in your list</p>";
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
}

$addCourse .= "<p>{$errorMessage}</p></div>";

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
		<form action="{$_SERVER['PHP_SELF']}" method="post">
			<table class="table table-bordered" style="margin-right: 1.2em;">
            <tr>
                <th>Course</th>
                <th>User Type</th>
            </tr>	
EOBODY;

$query = sprintf("select * from tblregistered join tblcourses on tblregistered.courseid = tblcourses.courseid where uid=%s", $_SESSION['uid']);
$result = $db_connection->query($query);

if (!$result) {
    die("Retrieval failed: ". $db_connection->error);
} else {
    /* Number of rows found */
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        $result->data_seek(0);
        $row = $result->fetch_array(MYSQLI_ASSOC);

        for ($row_index = 0; $row_index < $num_rows; $row_index++) {
            $result->data_seek($row_index);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $currentCourses .= "<tr><td>{$row['coursename']}</td><td>{$row['usertype']}</td>";
            $currentCourses .= "<td><a onclick=\"confirmDelete('{$row['courseid']}')\" title=\"Delete Course\"><span class=\"glyphicon glyphicon-trash\" style=\"font-size: 18px;\"></span></a></td></tr>";
        }
    }
}

$currentCourses .= <<<EOBODY
        </table>
	</form>
</div>
EOBODY;


$body = $addCourse . $currentCourses;
echo generatePage($body, $title);
?>