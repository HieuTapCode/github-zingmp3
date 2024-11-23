<?php
session_start();
require_once "../../Config/configConnectDB.php";
$id_user = $_SESSION["id_user"];
$album_id = $_POST["album_id"];
$sql_delete_album = $pdo->prepare("DELETE FROM album WHERE album_id='$album_id' AND  artist_id = '$id_user'");
$sql_delete_album->execute();
header("Location:"."../Profile/MyAlbum/MyAlbum.php")
?>