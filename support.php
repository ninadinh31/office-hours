<?php

function generatePage($body, $title="Example") {
    $page = <<<EOPAGE
<!doctype html>
<html>
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
        
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <title>$title</title>	
    </head>
            
    <body>
        $body
    </body>
</html>
EOPAGE;

    return $page;
}
?>