<?php
    include_once('support.php');
    require_once("dbLogin.php");
    require_once("setupDB.php");
    session_start();

    $connection = initDBConnection($host, $user, $dbpassword, $database);
    $uid = $_SESSION['uid'];
    $filename = $uid . '.jpg';
    $image = addslashes(file_get_contents($_FILES['fileToUpload']['tmp_name']));
    $image_name = addslashes($_FILES['fileToUpload']['name']);
    $docMimeType = "image/jpeg";
    $query = "REPLACE INTO tbltas (uid, pictureName, docMimeType, picture) VALUES ('$uid', '$filename', '$docMimeType', '$image')";
    $connection->query($query);
    header("Location: classList.php");

?>