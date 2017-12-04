<?php
    include_once('support.php');
    require_once("dbLogin.php");
    require_once("setupDB.php");
    session_start();
    $connection = initDBConnection($host, $user, $dbpassword, $database);

    $temp = $_FILES["fileToUpload"]["name"];
    $uid = $_SESSION['uid'];
    $filename = $uid . '.jpg';
    $docMimeType = "image/jpeg";

    $fileToInsert = $_FILES['fileToUpload']['tmp_name'];
    $fileData = addslashes(file_get_contents($fileToInsert));
    $query = "REPLACE INTO tbltas (uid, pictureName, docMimeType, picture) VALUES ($uid, '$filename', '$docMimeType', '$temp')";
    $connection->query($query);
    header("Location: classList.php");

?>