<?php
include_once('support.php');


$title = "TA Office Hours Waiting Room";
$body = <<<EOBODY
<style>
.jumbotron {
    background-color: #00aa88;
    color: white;
    font-size: 2em;
    padding: 0.75em;
}
.block {
padding: 0em 1.25em 0em 1.25em;
}
</style>
	<div class="jumbotron">
        <h1 style="display: inline-block; width: 60%;">$title</h1>
        <h4 style="display: inline-block; width: 5%; float: right; text-align: right; margin-top: 1.75em;">Logout</h4>
        <h4 style="display: inline-block; width: 10%; float: right; text-align: right; margin-top: 1.75em;">Switch Classes &nbsp;&nbsp;&nbsp;| </h4>
        
        <h3>Course: </h3>
        <h3>TA(s): </h3>
    </div>
	
	<div class="block" style="display: inline-block; width: 50%;">
        <h1>Queue:</h1><br/>
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
        <h1>Current:</h1><br/>
        <h4>Name: </h4>
        <h4>Timer: </h4>
	</div>
REST;


# Generating final page
echo generatePage($body, $title);
?>