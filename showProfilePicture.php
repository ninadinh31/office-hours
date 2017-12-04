<?php

    require_once("support.php");
    $dbconnection = initDBConnection($host, $user, $dbpassword, $database);
    $fileToRetrieve = $_POST['ta_name'].".jpg";

    $sqlQuery = "select picture, docMimeType from tbltas where pictureName = '{$fileToRetrieve}'";
    $result = mysqli_query($db, $sqlQuery);
    if ($result) {
        $recordArray = mysqli_fetch_assoc($result);
        header("Content-type: "."{$recordArray['docMimeType']}");
        echo $recordArray['picture'];
        mysqli_free_result($result);
    } else {
        $body = "<h3>Failed to retrieve document $fileToRetrieve: ".mysqli_error($db)." </h3>";
    }

    /* Closing */
    mysqli_close($db);

    echo generatePage($body);

?>