<?php
include_once('support.php');
$title = "TA Office Hours Waiting Room";
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

</style>
	<div class="jumbotron">
        <h3 style="display: inline-block; width: 60%;">$title</h3>
        <a href="logout.php" class="nav"><h4>Logout</h4></a>
        <a href="classList.php" class="nav"><h4>Edit Classes</h4></a>
    </div>
	
	<div class="block" style="display: inline-block; width: 50%;">
        <h3>Queue:</h3><br/>
        <table class="table table-bordered" style="margin-right: 1.2em;">
            <tr>
                <th style="width: 10%;">Priority</th>
                <th style="width: 30%;">Check-in Time</th>
                <th style="width: 30%;">Wait Time</th>
                <th style="width: 30%;">Name</th>
            </tr>	
EOBODY;
$body .= <<<REST
        </table><br/><br/>
        <input type="submit" class="btn btn-default" value="Add Name to Queue"/>
    </div>
    
	<div class="block" style="display: inline-block; width: 50%; float: right; border-left: 1px solid black;">
        <h3>Current:</h3><br/>
        <h4>Name: </h4>
        <h4>Timer: </h4>
	</div>
REST;
# Generating final page
echo generatePage($body, $title);
?>