<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ZingMP3/Component/SongTrending/SongTrending.css">
</head>

<body>
    <!-- Thịnh hành -->
    <div class="trending-container">
        <h3 class="title-item--home">
            Dành cho bạn
        </h3>
        <ul class="trending-list">
            <?php
            $id_user = $_SESSION["id_user"];
            $sql_song_trending = $pdo->prepare("
                                                    -- Bước 1: Lấy top 3 thể loại bài hát được yêu thích nhất
                                                    WITH top_kinds AS (
                                                        SELECT kindof
                                                        FROM (
                                                            -- Favorite songs with trọng số 0.4
                                                            SELECT s.kindof, COUNT(*) * 0.4 AS weight
                                                            FROM favorite_list f
                                                            JOIN song s ON f.song_id = s.song_id
                                                            WHERE f.user_id = :id_user
                                                            GROUP BY s.kindof

                                                            UNION ALL

                                                            -- Search history với trọng số 0.2 (lấy tối đa 10 lượt tìm kiếm gần nhất)
                                                            SELECT s.kindof, COUNT(*) * 0.2 AS weight
                                                            FROM (
                                                                SELECT * 
                                                                FROM search_history 
                                                                WHERE user_id = :id_user
                                                                ORDER BY date_search DESC
                                                                LIMIT 10
                                                            ) sh
                                                            JOIN song s ON s.title_song LIKE CONCAT('%', sh.search_query, '%')
                                                            GROUP BY s.kindof

                                                            UNION ALL

                                                            -- Bài hát do người dùng đăng tải với trọng số 0.1
                                                            SELECT s.kindof, COUNT(*) * 0.1 AS weight
                                                            FROM song s
                                                            WHERE s.artist_id = :id_user
                                                            GROUP BY s.kindof

                                                            UNION ALL

                                                            -- History list với trọng số 0.3 (lấy tối đa 100 bản ghi gần nhất)
                                                            SELECT s.kindof, COUNT(*) * 0.3 AS weight
                                                            FROM (
                                                                SELECT * 
                                                                FROM history_list 
                                                                WHERE user_id = :id_user
                                                                ORDER BY created_at DESC
                                                                LIMIT 10
                                                            ) h
                                                            JOIN song s ON h.song_id = s.song_id
                                                            GROUP BY s.kindof
                                                        ) AS weighted_kinds
                                                        GROUP BY kindof
                                                        ORDER BY SUM(weight) DESC
                                                        LIMIT 3  -- Lấy top 3 thể loại được yêu thích nhất
                                                    )

                                                    -- Bước 2: Lấy 10 bài hát ngẫu nhiên từ top 3 thể loại
                                                    SELECT s.*
                                                    FROM song s
                                                    JOIN top_kinds tk ON s.kindof = tk.kindof
                                                    ORDER BY RAND()
                                                    LIMIT 10;
        ");
        
            $sql_song_trending->bindParam(":id_user", $id_user);
            $sql_song_trending->execute();
            $list_song_trending = $sql_song_trending->fetchAll(PDO::FETCH_ASSOC);

            $missing_count = 10 - count($list_song_trending);

            if ($missing_count > 0) {
                // Nếu không đủ 10 bài, lấy thêm bài thịnh hành
                $query = "
                        SELECT s.*
                        FROM song s
                        WHERE s.kindof = (
                            SELECT kindof 
                            FROM song 
                            GROUP BY kindof 
                            ORDER BY SUM(listen_count) DESC 
                            LIMIT 1
                        )
                        ORDER BY RAND()
                        LIMIT :missing_count
                        ";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':missing_count', $missing_count, PDO::PARAM_INT);
                $stmt->execute();
                $additional_songs = $stmt->fetchAll();

                // Gộp hai mảng kết quả lại với nhau
                $final_songs = array_merge($list_song_trending, $additional_songs);
            } else {
                // Nếu đủ 10 bài từ lịch sử, chỉ dùng danh sách từ lịch sử
                $final_songs = $list_song_trending;
            }

            // Sau đó, bạn có thể sử dụng $final_songs để hiển thị kết quả
            
            for ($i = 0; $i < count($final_songs); $i++) {
                ?>
                <a <?php
                if ($final_songs[$i]['type_song'] == 'vip') {
                    echo 'style="background: #ffd70021"';
                }
                ?>
                    href="../ListSongPages/ListSongPages.php?album_id=<?php echo $final_songs[$i]['album_id'] ?>&song_id=<?php echo $final_songs[$i]['song_id'] ?>">
                    <li class="trending-list--item">
                        <div class="song-info">
                            <div class="img-thumbnail">
                                <?php
                                if ($final_songs[$i]['type_song'] == 'vip') {
                                    echo '<i class="fa-solid fa-crown"></i>';
                                }
                                ?>

                                <img src="<?php echo $final_songs[$i]['song_thumbnail'] ?>" alt="">
                                <i class="fa-solid fa-circle-play"></i>
                            </div>
                            <div class="info-song">
                                <div class="name-song" <?php
                                if ($final_songs[$i]['type_song'] == 'vip') {
                                    echo 'style="color: gold;"';
                                }
                                ?>><?php echo $final_songs[$i]['title_song'] ?>
                                </div>
                                <div class="author-song" <?php
                                if ($final_songs[$i]['type_song'] == 'vip') {
                                    echo 'style="color: gold;"';
                                }
                                ?>><?php echo $final_songs[$i]['title_artist'] ?></div>
                                <div class="heart-quantity" <?php
                                if ($final_songs[$i]['type_song'] == 'vip') {
                                    echo 'style="color: gold;"';
                                }
                                ?>>
                                    <p><i
                                            class="fa-solid fa-headphones-simple"></i><?php echo $final_songs[$i]['listen_count'] ?>
                                        <i class="fa-solid fa-heart"></i><?php echo $final_songs[$i]['like_count'] ?>
                                    </p>

                                </div>
                            </div>
                        </div>
                        <span>
                            <p class="song-duration">5:22</p>

                        </span>
                    </li>
                </a>

            <?php } ?>
        </ul>


    </div>

</body>

</html>