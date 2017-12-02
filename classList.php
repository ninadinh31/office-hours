<?php
require_once("support.php");

$title = "TA Office Hours Class List";
$body = <<<EOBODY
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

# Generating final page
echo generatePage($body, $title);
?>