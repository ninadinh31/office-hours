<?php
require_once("support.php");
require_once("dbLogin.php");
require_once("setupDB.php");

$title = "Registration";
if(isset($_POST["submitRegistration"])){
    $connection = initDBConnection($host, $user, $dbpassword, $database);
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $uid = $_POST["uid"];
    $email = $_POST["email"];
    $pass = $_POST["password"];
    $query = "insert into tblusers values('$firstname', '$lastname', '$uid', '$email', '$pass')";
    $connection->query($query);
}
$topPart = <<<EOBODY
<script src="validate.js"></script>
<style>
.form-group {
    width: 25%;
    margin: auto;
    margin-top: 3.5em;
    padding: 1.25em;
}
</style>
<div class="form-group panel panel-default">
    <h3><strong>Register</strong></h3><br/>
		<form action="{$_SERVER['PHP_SELF']}" method="post">
			<strong>First Name: </strong><input type="text" class="form-control" name="firstname" id="firstname" required/><br/><br/>
			<strong>Last Name: </strong><input type="text" class="form-control" name="lastname" id="lastname" required/><br/><br/>
			<strong>UID: </strong><input type="text" class="form-control" name="uid" id="uid" required/><br/><br/>
			<strong>Email: </strong><input type="email" class="form-control" name="email" id="email" required><br /><br />
            <strong>Password: </strong><input type="password" class="form-control" name="password" required/><br/><br/>

			<input type="submit" class="btn btn-info" name="submitRegistration" value="Register" style="display: table; margin: 0 auto;"/>
		</form>
		<br />
		<form action="login.php" method="post">
		    <input type="submit" class="btn btn-info" id="returnToLogin" name="returnToLogin"  value="Return to Login Page" style="...">
        </form>
</div>
        

EOBODY;


$body = $topPart;
$page = generatePage($body, $title);
echo $page;
?>