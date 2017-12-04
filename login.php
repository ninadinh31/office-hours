<?php
require_once("support.php");
require_once("dbLogin.php");
require_once("setupDB.php");

$title = "TA Office Hours Login";
$topPart = <<<EOBODY
<style>
.form-group {
    width: 25%;
    margin: auto;
    margin-top: 3.5em;
    padding: 1.25em;
}
</style>
<div class="form-group panel panel-default">
    <h3><strong>Login</strong></h3><br/>
		<form action="{$_SERVER['PHP_SELF']}" method="post">
			<strong>UID: </strong><input type="uid" class="form-control" name="uid" required/><br/><br/>
            <strong>Password: </strong><input type="password" class="form-control" name="password" required/><br/><br/>

			<input type="submit" class="btn btn-info" name="submitLogin" value="Login" style="display: table; margin: 0 auto;"/>
		</form>
</div>
EOBODY;

$bottomPart = "";
if (isset($_POST["submitLogin"])) {
    /* Connecting to the database */
    $db_connection = initDBConnection($host, $user, $dbpassword, $database);

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
            if ($password === $row['password']) {

                        session_start();
                        $_SESSION['uid'] = $uid;
                        header("Location: classList.php");

            } else {
                echo "Password verification failed.<br>";
            }
        }
    }
}

$body = $topPart.$bottomPart;
$page = generatePage($body, $title);
echo $page;
?>