<?php
require "../../../zingmp3/Config/configConnectDB.php";

// Lấy giá trị keyword từ GET và đảm bảo nó tồn tại
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : '';

// Kiểm tra keyword có rỗng không
if (!empty($keyword)) {
    // Câu truy vấn tìm kiếm
    $sql_search = $pdo->prepare("
        SELECT s.*, a.title_album, u.user_name
        FROM song s
        INNER JOIN album a ON s.album_id = a.album_id
        INNER JOIN user u ON s.artist_id = u.id_user
        WHERE 
            s.title_song LIKE :keyword OR
            a.title_album LIKE :keyword OR
            s.kindof LIKE :keyword OR
            s.title_artist LIKE :keyword OR
            u.user_name LIKE :keyword
    ");

    // Bind giá trị keyword với ký tự wildcard %
    $sql_search->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
    $sql_search->execute();

    // Lấy kết quả tìm kiếm
    $results_search = $sql_search->fetchAll(PDO::FETCH_ASSOC);

    // Trả về kết quả dạng JSON
    echo json_encode($results_search);
} else {
    // Nếu không có từ khóa tìm kiếm
    echo json_encode([]);
}
?>
