<?php
session_start();

require_once '../../../Config/configConnectDB.php';
require_once '../../../Config/Cloudinary/configCloudinary.php';


if (count($_POST) > 0) {
    $artist_id = $_SESSION["id_user"];
    $title_album = trim($_POST["title_album"]);
    $name_artist = trim($_POST["name_artist"]);

    $file_name = $_FILES["thumbnail_album"]["name"] . $title_album . $name_artist;
    $file_tmp =  $_FILES["thumbnail_album"]["tmp_name"];
    $thumbnail_album = getLinkMedia($cloudinary, $file_tmp, "thumbnail_album", $file_name);


    $sql_info = $pdo->prepare("INSERT INTO album (artist_id, title_album, thumbnail_album, name_artist) VALUES ('$artist_id','$title_album','$thumbnail_album','$name_artist')");
    $sql_info->execute();

    header("Location:" . "../MyAlbum/MyAlbum.php");
} else {
    echo "Lỗi dữ liệu bất định";
}
