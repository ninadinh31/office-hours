<?php

    require_once("support.php");
    require_once("dbLogin.php");
    require_once("setupDB.php");
    $db_connection = initDBConnection($host, $user, $dbpassword, $database);
    $fileToRetrieve = $_POST['ta_name'].".jpg";
    $body = $fileToRetrieve;

    $query = "select picture, docMimeType from tbltas where picture = '{$fileToRetrieve}'";
    $result = $db_connection->query($query);
    if ($result) {
        $result->data_seek(0);
        $recordArray = $result->fetch_array(MYSQLI_ASSOC);
        header("Content-type: "."{$recordArray['docMimeType']}");
        $body .= $recordArray['picture'];
        mysqli_free_result($result);
    } else { 				   
        $body = "<h3>Failed to retrieve document $fileToRetrieve: ".mysqli_error($db_connection)." </h3>";
    }

    /* Closing */
    mysqli_close($db_connection);

    echo generatePage($body);

?>