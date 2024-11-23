<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ZingMP3/Component/AlbumHot/AlbumHot.css">
</head>

<body>

    <div class="trending-album">
        <h3 class="title-item--home">
            album hot
        </h3>
        <ul class="trending-album--list">
            <?php
            $sql_get_album_hot = $pdo->prepare("SELECT
                                                    album.album_id,
                                                    album.thumbnail_album,
                                                    album.title_album,
                                                    user.user_name AS artist_name,
                                                    SUM(song.listen_count) AS total_listen_count
                                                FROM album
                                                INNER JOIN song ON album.album_id = song.album_id
                                                INNER JOIN user ON album.artist_id = user.id_user
                                                GROUP BY album.album_id, album.title_album, artist_name
                                                ORDER BY total_listen_count DESC
                                                LIMIT 5;");
            $sql_get_album_hot->execute();
            $list_album_hot = $sql_get_album_hot->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($list_album_hot); $i++) {
            ?>
                <a href="../ListSongPages/ListSongPages.php?album_id=<?php echo $list_album_hot[$i]['album_id'] ?>">
                    <li><i class="fa-regular fa-circle-play"></i><img src="<?php echo $list_album_hot[$i]['thumbnail_album'] ?>" alt=""></li>
                    <p class="title-album"><?php echo $list_album_hot[$i]['title_album'] ?>
                    </p>
                    <p class="name-album">
                        <?php echo $list_album_hot[$i]['artist_name'] ?>
                    </p>
                </a>
            <?php } ?>
        </ul>
    </div>

</body>

</html>