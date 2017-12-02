<?php
require_once("support.php");
require_once("dbLogin.php");

$title = "TA Office Hours Login";
$topPart = <<<EOBODY
<style>
.form-group {
    width: 30%;
    margin: auto;
    margin-top: 3.5em;
    padding: 1.25em;
}
</style>
<div class="form-group panel panel-default">
    <h1>Login</h1><br/>
		<form action="{$_SERVER['PHP_SELF']}" method="post">
			<strong>UID: </strong><input type="uid" class="form-control" name="uid"/><br/><br/>
            <strong>Password: </strong><input type="password" class="form-control" name="password"/><br/><br/>

			<input type="submit" class="btn btn-default" name="submitLogin" value="Login"/>
		</form>
</div>
EOBODY;

$bottomPart = "";
if (isset($_POST["submitLogin"])) {
    /* Connecting to the database */
    $db_connection = new mysqli($host, $user, $password, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }

    $uid = mysqli_real_escape_string($db_connection, trim($_POST["uid"]));
    $password = mysqli_real_escape_string($db_connection, trim($_POST["password"]));

    $query = sprintf("select * from tblusers where uid='%s'", $uid);

    /* Executing query */
    $result = $db_connection->query($query);
    if (!$result) {
        die("Retrieval failed: ". $db_connection->error);
    } else {
        /* Number of rows found */
        $num_rows = $result->num_rows;
        if ($num_rows === 0) {
            echo "No User Found!";
        } else {
            $result->data_seek(0);
            $row = $result->fetch_array(MYSQLI_ASSOC);

            //if (password_verify($password, $row['password'])) {
            if ($password == $row['password']) {
                $query = sprintf("select * from tblregistered where uid='%s'", $uid);

                /* Executing query */
                $result = $db_connection->query($query);
                if (!$result) {
                    die("Retrieval failed: ". $db_connection->error);
                } else {
                    /* Number of rows found */
                    $num_rows = $result->num_rows;
                    if ($num_rows === 0) {
                        echo "No User Found!";
                    } else {
                        $result->data_seek(0);
                        $row = $result->fetch_array(MYSQLI_ASSOC);

                        $topPart = <<<EOBODY
                <style>
                .form-group {
                    width: 30%;
                    margin: auto;
                    margin-top: 3.5em;
                    padding: 1.25em;
                }
                </style>
                <div class="form-group panel panel-default">
                    <h1>Pick a Course:</h1><br/>
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
                    }
                }
            } else {
                echo "Password <strong>$password</strong> verification failed.<br>";
            }
        }
    }
}

$body = $topPart.$bottomPart;
$page = generatePage($body, $title);
echo $page;
?>