<?php
session_start();
require_once "../../Config/configConnectDB.php";
$song_id = $_REQUEST["song_id"];
$id_user = $_SESSION["id_user"];
$isFavorite = $_REQUEST["isFavorite"];

if ($isFavorite == "true") {
    // Kiểm tra xem bài hát đã tồn tại trong danh sách yêu thích của người dùng hay chưa
    $sql_check_favorite = $pdo->prepare("SELECT * FROM favorite_list WHERE song_id = :song_id AND user_id = :id_user");
    $sql_check_favorite->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $sql_check_favorite->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $sql_check_favorite->execute();

    if ($sql_check_favorite->rowCount() == 0) {
        // Nếu bài hát chưa tồn tại trong danh sách yêu thích, thì thêm nó vào
        $sql_add_to_favorite = $pdo->prepare("INSERT INTO favorite_list (song_id, user_id) VALUES (:song_id, :id_user)");
        $sql_add_to_favorite->bindParam(':song_id', $song_id, PDO::PARAM_INT);
        $sql_add_to_favorite->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $sql_add_to_favorite->execute();

        // Tăng lượt thích (like_count) của bài hát
        $sql_incre_like = $pdo->prepare("UPDATE song SET like_count = like_count + 1 WHERE song_id = :song_id");
        $sql_incre_like->bindParam(':song_id', $song_id, PDO::PARAM_INT);
        $sql_incre_like->execute();
    }
} else {
    // Xóa bài hát khỏi danh sách yêu thích của người dùng
    $sql_remove_from_favorite = $pdo->prepare("DELETE FROM favorite_list WHERE song_id = :song_id AND user_id = :id_user");
    $sql_remove_from_favorite->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $sql_remove_from_favorite->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $sql_remove_from_favorite->execute();

    // Giảm lượt thích (like_count) của bài hát
    $sql_decre_like = $pdo->prepare("UPDATE song SET like_count = like_count - 1 WHERE song_id = :song_id");
    $sql_decre_like->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $sql_decre_like->execute();
}
// Bước 1: Lấy giá trị kindof và title_artist từ song_id
$sql_get_song_info = "SELECT kindof, title_artist FROM song WHERE song_id = :song_id";
$stmt_get_song_info = $pdo->prepare($sql_get_song_info);
$stmt_get_song_info->execute(['song_id' => $song_id]);
$song_info = $stmt_get_song_info->fetch(PDO::FETCH_ASSOC);

if ($song_info) {
    $kindof = $song_info['kindof'];
    $title_artist = $song_info['title_artist'];

    // Bước 2: Chèn bản ghi vào bảng history_list
    $sql_insert = "INSERT INTO history_list (user_id, song_id, kindof, title_artist) 
                    VALUES (:user_id, :song_id, :kindof, :title_artist)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([
        'user_id' => $id_user,
        'song_id' => $song_id,
        'kindof' => $kindof,
        'title_artist' => $title_artist,
    ]);
}