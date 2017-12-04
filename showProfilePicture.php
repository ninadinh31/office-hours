<?php

    require_once("support.php");
<<<<<<< HEAD
    require_once("dbLogin.php");
    require_once("setupDB.php");
    $db_connection = initDBConnection($host, $user, $dbpassword, $database);
=======
    $dbconnection = initDBConnection($host, $user, $dbpassword, $database);
>>>>>>> origin/master
    $fileToRetrieve = $_POST['ta_name'].".jpg";
    $body = $fileToRetrieve;

<<<<<<< HEAD
    $query = "select picture, docMimeType from tbltas where picture = '{$fileToRetrieve}'";
    $result = $db_connection->query($query);
=======
    $sqlQuery = "select picture, docMimeType from tbltas where pictureName = '{$fileToRetrieve}'";
    $result = mysqli_query($db, $sqlQuery);
>>>>>>> origin/master
    if ($result) {
        $result->data_seek(0);
        $recordArray = $result->fetch_array(MYSQLI_ASSOC);
        header("Content-type: "."{$recordArray['docMimeType']}");
<<<<<<< HEAD
        $body .= $recordArray['picture'];
        mysqli_free_result($result);
    } else { 				   
        $body = "<h3>Failed to retrieve document $fileToRetrieve: ".mysqli_error($db_connection)." </h3>";
=======
        echo $recordArray['picture'];
        mysqli_free_result($result);
    } else {
        $body = "<h3>Failed to retrieve document $fileToRetrieve: ".mysqli_error($db)." </h3>";
>>>>>>> origin/master
    }

    /* Closing */
    mysqli_close($db_connection);

    echo generatePage($body);

?>