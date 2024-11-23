<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- favicon  -->
    <link rel="shortcut icon" href="../../Component/assets/logo_mobile.png" type="image/x-icon">
    <title>Kết quả tìm kiếm - Nhóm Phát Triển Ứng Dụng Web</title>
    <!-- import css file component  -->
    <link rel="stylesheet" href="../../GlobalStyle/style.css">
    <link rel="stylesheet" href="./ResultSearch.css">
</head>

<body>
    <div id="search-main">
        <?php require '../../Component/Navbar/Navbar.php' ?>
        <?php require_once "../../Config/configConnectDB.php" ?>

        <div class="right-search">
            <?php require '../../Component/Header/HeaderLayout.php' ?>
            <div class="search-container">
                <?php
                // Lấy từ khóa tìm kiếm từ URL
                $keyword = $_GET["keyword"];

                // Thực hiện truy vấn tìm kiếm
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
                $sql_search->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
                $sql_search->execute();

                // Lấy kết quả tìm kiếm
                $results_search = $sql_search->fetchAll(PDO::FETCH_ASSOC);

                // Hiển thị số lượng kết quả
                ?>
                <h1 class="title-item--home">
                    <i class="fa-solid fa-magnifying-glass"></i>Có <?php echo count($results_search) ?> kết quả tìm kiếm
                    cho: "<?php echo $keyword ?>"
                </h1>
                <ul class="search-list">
                    <?php
                    // Hiển thị kết quả tìm kiếm
                    for ($i = 0; $i < count($results_search); $i++) {
                        ?>
                        <a
                            href="../ListSongPages/ListSongPages.php?album_id=<?php echo $results_search[$i]['album_id'] ?>&song_id=<?php echo $results_search[$i]['song_id'] ?>">
                            <li class="search-list--item" <?php
                            if ($results_search[$i]['type_song'] == 'vip') {
                                echo 'style="background: #ffd70021"';
                            }
                            ?>>
                                <div class="song-info">
                                    <div class="img-thumbnail">
                                        <?php
                                        if ($results_search[$i]['type_song'] == 'vip') {
                                            echo '<i class="fa-solid fa-crown"></i>';
                                        }
                                        ?>
                                        <img src="<?php echo $results_search[$i]['song_thumbnail'] ?>" alt="">
                                        <i class="fa-solid fa-circle-play"></i>
                                    </div>
                                    <div class="info-song">
                                        <div class="name-song" <?php
                                        if ($results_search[$i]['type_song'] == 'vip') {
                                            echo 'style="color: gold;"';
                                        }
                                        ?>><?php echo $results_search[$i]['title_song'] ?>
                                        </div>
                                        <div class="author-song" <?php
                                        if ($results_search[$i]['type_song'] == 'vip') {
                                            echo 'style="color: gold;"';
                                        }
                                        ?>><?php echo $results_search[$i]['title_artist'] ?>
                                        </div>
                                        <div class="heart-quantity" <?php
                                        if ($results_search[$i]['type_song'] == 'vip') {
                                            echo 'style="color: gold;"';
                                        }
                                        ?>>
                                            <p><i
                                                    class="fa-solid fa-headphones-simple"></i><?php echo $results_search[$i]['listen_count'] ?>
                                                <i
                                                    class="fa-solid fa-heart"></i><?php echo $results_search[$i]['like_count'] ?>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                                <span>
                                    <p class="song-duration"><?php echo $results_search[$i]['duration'] ?></p>
                                </span>
                            </li>
                        </a>
                    <?php } ?>
                </ul>
                
                <?php
                // Nếu có session user_id và kết quả tìm kiếm lớn hơn 0, lưu từ khóa tìm kiếm vào bảng search_history
                if (isset($_SESSION['id_user']) && count($results_search) > 0) {
                    $user_id = $_SESSION['id_user'];
                    // Chuẩn bị câu lệnh để lưu lịch sử tìm kiếm
                    $sql_insert_search_history = $pdo->prepare("
                        INSERT INTO search_history (user_id, search_query) 
                        VALUES (:user_id, :search_query)
                    ");
                    $sql_insert_search_history->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                    $sql_insert_search_history->bindValue(':search_query', $keyword, PDO::PARAM_STR);
                    
                    // Thực thi câu lệnh
                   $sql_insert_search_history->execute(); 
                }
                ?>
            </div>
        </div>
</body>

</html>
