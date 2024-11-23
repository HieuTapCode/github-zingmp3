<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ZingMP3/Component/AuthorTrending/AuthorTrending.css">
</head>

<body>
    <div class="trending-author">
        <h3 class="title-item--home">
            Nghệ sỹ thịnh hành
        </h3>

        <ul class="trending-author--list">
            <?php
            $sql_get_author = $pdo->prepare("SELECT user.id_user, user.user_name,user.avatar_link, SUM(song.listen_count) AS total_listen_count, SUM(song.like_count) AS total_like_count
                                            FROM user
                                            INNER JOIN song ON user.id_user = song.artist_id
                                            INNER JOIN album ON song.album_id = album.album_id
                                            GROUP BY user.id_user, user.user_name
                                            ORDER BY total_listen_count DESC, total_like_count DESC LIMIT 5");
            $sql_get_author->execute();
            $list_author_trending = $sql_get_author->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($list_author_trending); $i++) {
            ?>
                <a href="../ResultSearch/ResultSearch.php?keyword=<?php echo $list_author_trending[$i]['user_name'] ?>">
                    <li><i class="fa-regular fa-circle-play"></i><img src="<?php echo $list_author_trending[$i]['avatar_link'] ?>" alt=""></li>

                    <p class="title-author"><?php echo $list_author_trending[$i]['user_name'] ?>
                    </p>
                    <p class="name-author">
                        <i class="fa-solid fa-headphones-simple"></i><?php echo $list_author_trending[$i]['total_listen_count'] ?>
                        <i class="fa-solid fa-heart"></i><?php echo $list_author_trending[$i]['total_like_count'] ?>
                    </p>
                </a>
            <?php } ?>

        </ul>
    </div>



</body>

</html>