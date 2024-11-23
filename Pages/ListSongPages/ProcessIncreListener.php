<?php
session_start();
require_once "../../Config/configConnectDB.php";

// Lấy song_id, kindof, title_artist từ yêu cầu AJAX
$song_id = $_POST["song_id"] ?? null;


// Log dữ liệu để kiểm tra
error_log("Received data - song_id: $song_id, kindof: $kindof, title_artist: $title_artist");

if ($song_id) {
    $id_user = $_SESSION["id_user"];
    // Cập nhật listen_count cho bài hát
    $sql_incre_listener = $pdo->prepare("UPDATE song SET listen_count = listen_count + 1 WHERE song_id = :song_id");
    $sql_incre_listener->execute(['song_id' => $song_id]);

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
}
