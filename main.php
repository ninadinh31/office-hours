<?php
include_once('support.php');
require_once("dbLogin.php");
require_once("setupDB.php");
session_start();

$title = "TA Office Hours Waiting Room - " . $_SESSION["uid"];
$body = <<<EOBODY
<style>
.jumbotron {
    background-color: #5bc0de;
    color: white;
    font-size: 2em;
    padding: 0.75em;
}
.block {
    padding: 0em 1.25em 0em 1.25em;
}
.nav {
    color: white;
    display: inline-block;
    text-align: right; 
    margin: .5em 1em 0em 0em;
    float: right;
    /*border: 1px solid black;*/
}
a:hover{
    text-decoration: none;
}

.form-group {
    width: 40%;
    margin: auto;
    margin-top: 1.5em;
    padding: 1.25em;
}
.subheader {
    margin: auto;
    display: table;
}


</style>

    <!-- <meta http-equiv="refresh" content="10"/> -->
	<div class="jumbotron">
        <h3 style="display: inline-block; width: 60%;"><strong>$title</strong></h3>
        <a href="logout.php" class="nav"><h4>Logout</h4></a>
        <a href="classList.php" class="nav"><h4>Edit Classes</h4></a>
    </div>
	
EOBODY;

$db_connection = initDBConnection($host, $user, $dbpassword, $database);
date_default_timezone_set('America/New_York');

// TA VIEW
$query = sprintf("select * from tblregistered join tblcourses on tblregistered.courseid = tblcourses.courseid where uid='%s' and usertype='%s' order by tblcourses.coursename ASC", $_SESSION['uid'], "TA");
$result = $db_connection->query($query);

if (!$result) {
    die("Retrieval failed: two ". $db_connection->error);
} else {
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        $result->data_seek(0);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $body .= "<h3 class='subheader'><strong>TA</strong></h3>";



        // for each course that the user is a TA for...
        for ($row_index = 0; $row_index < $num_rows; $row_index++) {
            $result->data_seek($row_index);
            $row = $result->fetch_array(MYSQLI_ASSOC);

            // get ta name
            $queryGetTaName = sprintf("select * from tblcourses where courseid='%s'", $row['courseid']);
            $resultGetTaName = $db_connection->query($queryGetTaName);
            $resultGetTaName->data_seek(0);
            $rowGetTaName = $resultGetTaName->fetch_array(MYSQLI_ASSOC);

            if ($rowGetTaName['currenttaid'] != "") {
                $taName = $rowGetTaName['currenttaid'];
                $queryGetTaName = sprintf("select * from tblusers where uid='%s'", $taName);
                $resultGetTaName = $db_connection->query($queryGetTaName);
                $resultGetTaName->data_seek(0);
                $rowGetTaName = $resultGetTaName->fetch_array(MYSQLI_ASSOC);
                $firstname = $rowGetTaName['firstname'];
                $lastname = $rowGetTaName['lastname'];
                $TAPicture = $taName.".jpg";
                /*$queryGetTAPic = sprintf("select * from tbltas where uid='%s'", $rowGetTaName['currenttaid']);
                $resultGetTaPic = $db_connection->query($queryGetTAPic);
                $resultGetTaPic->data_seek(0);
                $rowGetTaPic = $resultGetTaPic->fetch_array(MYSQLI_ASSOC);
                $TAPicture =*/
            } else {
                $firstname = "Currently None";
                $lastname = "";
                $TAPicture="";
                $taName = "";
            }

            $currStudId = "currStud" . $row['courseid'];

            $body .= <<<EOBODY

            
            <div class="form-group panel panel-default">
            <h4><strong>Course:</strong> {$row['coursename']}</h4>
            <h4><strong>TA:</strong> {$firstname} {$lastname}</h4><br/>
EOBODY;
/*            if ($taName !== "") {
                $body .= <<<EOBODY
            <form action = "showProfilePicture.php" method = "post" >
                <input type = "hidden" name = "ta_name" value = "$taName" >
                <input type = "submit" id = "submit" name = "submit" class="btn btn-info" value = "Show TA Picture" style = "display: table; margin: 0 auto;" >
            </form >

            <br />
EOBODY;
            }*/
            $body .= <<<EOBODY
            <h4><strong>Queue:</strong></h4>
                <table class="table table-hover table-striped" style="margin-right: 1.2em;">
                    <tr>
                        <th style="width: 10%;">Priority</th>
                        <th style="width: 30%;">Check-in Time</th>
                        <th style="width: 30%;">Wait Time</th>
                        <th style="width: 30%;">Name</th>
                    </tr>
EOBODY;

            // show course queue
            $query2 = sprintf("select * from tblqueue join tblusers on tblqueue.uid = tblusers.uid where courseid='%s' order by tblqueue.queuecheckintime ASC", $row['courseid']);
            $result2 = $db_connection->query($query2);
            $topPriorityUid = "";
            $topPriorityCourseId = "";

            if (!$result2) {
                die("Retrieval failed: one " . $db_connection->error);
            } else {
                $num_rows2 = $result2->num_rows;
                if ($num_rows2 > 0) {
                    $result2->data_seek(0);
                    $row2 = $result2->fetch_array(MYSQLI_ASSOC);

                    for ($row_index2 = 0; $row_index2 < $num_rows2; $row_index2++) {
                        if ($row_index2 === 0) {
                            $topPriorityUid = $row2['uid'];
                            $topPriorityCourseId = $row2['courseid'];
                        }
                        $result2->data_seek($row_index2);
                        $row2 = $result2->fetch_array(MYSQLI_ASSOC);
                        $priority = $row_index2 + 1;
                        $time_elapsed = floor((strtotime(date("Y-m-d H:i:s")) - strtotime($row2['queuecheckintime'])) / 60);
                        $check_in_time = date("g:i a", strtotime($row2['queuecheckintime']));
                        $body .= <<<EOBODY
                        <tr>
                            <td>{$priority}</td>
                            <td>{$check_in_time}</td>
                            <td>{$time_elapsed} min</td>
                            <td>{$row2['firstname']} {$row2['lastname']}  </td>
                        </tr>
EOBODY;
                    }
                }
                $body .= "</table><br/><form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\"><div id=\"taNav\">";

                if ($row['currenttaid'] == "") {
                    $body .= "<input type=\"submit\" name=\"startTaHours\" id=\"startTaHours\" class=\"btn btn-info\" value=\"Start TA Hours\" style=\"display: table; margin: 0 auto;\"/>";
                } else {
                    $body .= "<input type=\"submit\" name=\"endTaHours\" id=\"endTaHours\" class=\"btn btn-info\" value=\"End TA Hours\" style=\"display: table; margin: 0 auto;\"/>";
                }
                $body .= "</div> <input type=\"hidden\" name=\"courseid\" value=\"{$row['courseid']}\"/></form><div id=\"helpNext\">";
                if ($row['currenttaid'] !== "") {
                    $body .= "<br/><form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\"><input type=\"submit\" name=\"helpNextBtn\" class=\"btn btn-info\" value=\"Help Next\" style=\"display: table; margin: 0 auto;\"/>";
                    $body .= "<input type=\"hidden\" name=\"helpNextUid\" value=\"{$topPriorityUid}\"/><input type=\"hidden\" name=\"helpNextCourseId\" value=\"{$topPriorityCourseId}\"/></form>";
                }
                $body .= "</div></div>";

            }
        }
    }
}

// STUDENT VIEW
$query = sprintf("select * from tblregistered join tblcourses on tblregistered.courseid = tblcourses.courseid where uid='%s' and usertype='%s' order by tblcourses.coursename ASC", $_SESSION['uid'], "Student");
$result = $db_connection->query($query);



if (!$result) {
    die("Retrieval failed: three ". $db_connection->error);
} else {
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        $result->data_seek(0);
        $row = $result->fetch_array(MYSQLI_ASSOC);

        $body .= "<br/><br/><h3 class='subheader'><strong>Student</strong></h3>";

        // for each course that the user is a student for...
        for ($row_index = 0; $row_index < $num_rows; $row_index++) {
            $result->data_seek($row_index);
            $row = $result->fetch_array(MYSQLI_ASSOC);

            // get ta name
            $queryGetTaName = sprintf("select * from tblcourses where courseid='%s'", $row['courseid']);
            $resultGetTaName = $db_connection->query($queryGetTaName);
            $resultGetTaName->data_seek(0);
            $rowGetTaName = $resultGetTaName->fetch_array(MYSQLI_ASSOC);

            if ($rowGetTaName['currenttaid'] != "") {
                $taName = $rowGetTaName['currenttaid'];
                $queryGetTaName = sprintf("select * from tblusers where uid='%s'", $taName);
                $resultGetTaName = $db_connection->query($queryGetTaName);
                $resultGetTaName->data_seek(0);
                $rowGetTaName = $resultGetTaName->fetch_array(MYSQLI_ASSOC);
                $firstname = $rowGetTaName['firstname'];
                $lastname = $rowGetTaName['lastname'];
                $TAPicture = $taName.".jpg";
            } else {
                $firstname = "Currently None";
                $lastname = "";
                $TAPicture="";
                $taName = "";
            }


            $body .= <<<EOBODY

            
            <div class="form-group panel panel-default">
            <h4><strong>Course:</strong> {$row['coursename']}</h4>
            <h4><strong>TA:</strong> {$firstname} {$lastname}</h4><br/>
EOBODY;
            if ($taName !== "") {
                $body .= <<<EOBODY
            <form action = "showProfilePicture.php" method = "post" >
                <input type = "hidden" name = "ta_name" value = "$taName" >
                <input type = "submit" id = "submit" name = "submit" class="btn btn-info" value = "Show TA Picture" style = "display: table; margin: 0 auto;" >
            </form >

            <br />
EOBODY;
            }
            $body .= <<<EOBODY
            <form action="{$_SERVER['PHP_SELF']}" method="post">
            <h4><strong>Queue:</strong></h4>
                <table class="table table-hover table-striped" style="margin-right: 1.2em;">
                    <tr>
                        <th style="width: 10%;">Priority</th>
                        <th style="width: 30%;">Check-in Time</th>
                        <th style="width: 30%;">Wait Time</th>
                        <th style="width: 30%;">Name</th>
                    </tr>
EOBODY;

            // show course queue
            $query2 = sprintf("select * from tblqueue join tblusers on tblqueue.uid = tblusers.uid where courseid='%s' order by tblqueue.queuecheckintime ASC", $row['courseid']);
            $result2 = $db_connection->query($query2);

            if (!$result2) {
                die("Retrieval failed: four " . $db_connection->error);
            } else {
                $num_rows2 = $result2->num_rows;
                if ($num_rows2 > 0) {
                    $result2->data_seek(0);
                    $row2 = $result2->fetch_array(MYSQLI_ASSOC);

                    for ($row_index2 = 0; $row_index2 < $num_rows2; $row_index2++) {
                        $result2->data_seek($row_index2);
                        $row2 = $result2->fetch_array(MYSQLI_ASSOC);
                        $priority = $row_index2 + 1;
                        $time_elapsed = floor((strtotime(date("Y-m-d H:i:s")) - strtotime($row2['queuecheckintime'])) / 60) ;
                        $check_in_time = date("g:i a", strtotime($row2['queuecheckintime']));
                        $body .= <<<EOBODY
                        <tr>
                            <td>{$priority}</td>
                            <td>{$check_in_time}</td>
                            <td>{$time_elapsed} min</td>
                            <td>{$row2['firstname']} {$row2['lastname']}  </td>
                        </tr>
EOBODY;
                    }
                }
                $body .= <<<EOBODY
                 </table ><br/>
    
                 <input type="submit" name="addToQueue" class="btn btn-info" value="Add Name" style="display: table; margin: 0 auto;"/>
                 <input type="hidden" name="courseid" value="{$row['courseid']}"/>
            
        </form><br/>
</div>

EOBODY;
            }
        }
    }
}

if (isset($_POST["addToQueue"])) {
    $query = sprintf("insert into tblqueue (uid, courseid, priority, queuecheckintime) values ('%s', '%s', '%s', '%s')", $_SESSION['uid'], $_POST['courseid'], 1, date("Y-m-d H:i:s"));
    $result = $db_connection->query($query);
    header("Location: main.php");
}

if (isset($_POST["startTaHours"])) {

    $query = sprintf("update tblcourses set currenttaid='%s' where courseid='%s'", $_SESSION['uid'], $_POST['courseid']);
    $result = $db_connection->query($query);
    header("Location: main.php");
}

if (isset($_POST["endTaHours"])) {

    $query = sprintf("update tblcourses set currenttaid='' where courseid='%s'", $_POST['courseid']);
    $result = $db_connection->query($query);
    header("Location: main.php");
}
if (isset($_POST["helpNextBtn"])) {
echo ($_POST['helpNextUid']);
    $query = sprintf("select * from tblusers join tblqueue on tblusers.uid = tblqueue.uid where tblusers.uid='%s'", $_POST['helpNextUid']);
    $result = $db_connection->query($query);
    if (!$result) {
        die("Retrieval failed: " . $db_connection->error);
    } else {
        $num_rows = $result->num_rows;
        if ($num_rows === 0) {
            echo "Empty Table<br>";
        } else {
            $result->data_seek(0);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $query2 = sprintf("delete from tblqueue where uid='%s' and courseid='%s'", $_POST['helpNextUid'], $_POST['helpNextCourseId']);
            $result2 = $db_connection->query($query2);

        }
    }

    header("Location: main.php");
}

# Generating final page
echo generatePage($body, $title);
?>