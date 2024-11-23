<?php
session_start();
require_once "../../Config/configConnectDB.php";
$id_user = $_SESSION["id_user"];
$album_id = $_GET["album_id"];
$song_id = $_GET["song_id"];
echo $id_user."-" .$album_id."-". $song_id;
$sql_delete_song = $pdo->prepare("DELETE FROM song WHERE album_id='$album_id' AND  artist_id = '$id_user' AND song_id = '$song_id'");
$sql_delete_song->execute();
header("Location:"."../UpdateListSong/UpdateListSong.php?album_id=" . $album_id);
?>
