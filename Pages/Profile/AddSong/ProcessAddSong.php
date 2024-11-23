<?php 
session_start();
require_once "../../../Config/configConnectDB.php";
require_once "../../../Config/Cloudinary/configCloudinary.php";
echo "<pre>";
if(isset($_SESSION["id_user"])){
    $artist_id = $_SESSION["id_user"];
    $album_id= $_POST["album_id"];
    $title_song = trim($_POST["title_song"]);
    $duration = $_POST["duration"];
    $title_artist = trim($_POST["title_artist"]); 
    $type_song = $_POST["type_song"]; 
    $kindof = $_POST["kindof"]; 
    


    //song thumbnai
    $thumbnail_file_name = $title_song."in".$album_id."by".$title_artist;
    $thumbnail_file_tmp =  $_FILES["song_thumbnail"]["tmp_name"];
    $song_thumbnail = getLinkMedia($cloudinary, $thumbnail_file_tmp, "song_thumbnail", $thumbnail_file_name);

    //song mp3
    $mp3_link_name = $_FILES["mp3_link"]["name"]."by".$title_artist."in".$album_id;
    $mp3_link_tmp =  $_FILES["mp3_link"]["tmp_name"];
    $mp3_link = getLinkMedia($cloudinary, $mp3_link_tmp, "audio", $mp3_link_name);

    $release_date = date('Y-m-d H:i:s');

    $sql_up_song = $pdo->prepare("INSERT INTO song (album_id,artist_id, song_thumbnail, mp3_link, release_date,title_song,duration,title_artist,type_song,kindof) 
                                            VALUES ('$album_id','$artist_id','$song_thumbnail','$mp3_link','$release_date','$title_song','$duration','$title_artist','$type_song','$kindof')");
    $sql_up_song->execute();

    header("Location:"."../../UpdateListSong/UpdateListSong.php?album_id=".$album_id);
}else{
    header("Location:"."../../Login/Login.php");
}
