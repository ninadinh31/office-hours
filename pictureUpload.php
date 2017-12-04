<?php
    include_once('support.php');
    require_once("dbLogin.php");
    require_once("setupDB.php");
    session_start();

    $connection = initDBConnection($host, $user, $dbpassword, $database);
    $uid = $_SESSION['uid'];
    $filename = $uid . '.jpg';
    $docMimeType = "image/jpg";
    $fileToInsert = $_FILES['filename']['name'];

    $fileData = addslashes(file_get_contents($fileToInsert));
    $query = "insert into tbltas (uid, pictureName, docMimeType, picture) values ('$uid', 'test', '$docMimeType', '$fileData')";
    $connection->query($query);
    header("Location: classList.php");

?>