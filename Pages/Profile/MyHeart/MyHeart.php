<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- cdn fontawesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- favicon  -->
    <link rel="shortcut icon" href="../../../Component/assets/logo_mobile.png" type="image/x-icon">
    <title>Bài hát yêu thích - Nhóm Phát Triển Ứng Dụng Web</title>
    <!-- import link css file component  -->
    <link rel="stylesheet" href="../../../GlobalStyle/style.css">
    <link rel="stylesheet" href="../Profile.css">
    <link rel="stylesheet" href="./MyHeart.css">
</head>

<body>
    <div id="profile-main">
        <?php require_once "../../../Component/Navbar/Navbar.php" ?>
        <?php require_once "../../../Config/configConnectDB.php" ?>

        <?php
        // Hiện thông tin người dùng 
        $id_user = $_SESSION["id_user"];
        $statement = $pdo->prepare("SELECT * FROM user WHERE id_user = '$id_user'");
        $statement->execute();
        $info_user = $statement->fetch(PDO::FETCH_ASSOC);


        $statementTotal = $pdo->prepare("SELECT 
                            COUNT(DISTINCT album.album_id) AS total_albums,
                            COUNT(DISTINCT song.song_id) AS total_songs,
                            SUM(song.listen_count) AS total_listens,
                            SUM(song.like_count) AS total_likes
                            FROM 
                                user
                            JOIN 
                                album ON user.id_user = album.artist_id
                            JOIN 
                                song ON album.album_id = song.album_id
                            WHERE 
                            id_user = '$id_user'");
        $statementTotal->execute();
        $info_analyst = $statementTotal->fetch(PDO::FETCH_ASSOC);

        ?>
        <div class="profile-right">
            <?php require "../../../Component/Header/HeaderLayout.php" ?>
            <div class="container-profile">
                <div class="profile-header">

                    <div class="img-container" <?php if ($info_user["type_account"] == "vip") {
                        echo 'style="border: 5px solid gold;"';
                    } ?>>
                        <img src="<?php echo $info_user["avatar_link"] ?>" alt="" />
                        <?php if ($info_user["type_account"] == "vip") {
                            echo '<i class="fa-solid fa-crown"></i>';
                        } ?>
                    </div>
                    <?php if ($info_user["type_account"] == "free") {
                        echo '<a href="/zingmp3/vnpay_php/vnpay_pay.php"><div class="buy-vip">Mua VIP</div></a>';
                    } ?>
                    <div class="profile-name"><?php echo $info_user["user_name"] ?></div>
                    <span style="display: flex;align-items: center;justify-content: center;font-size: 12px;">
                        <?= $info_analyst["total_likes"] ? $info_analyst["total_likes"] : 0 ?> <i
                            class="fa-solid fa-heart" style="margin-left: 3px"></i>
                        <span style="padding: 0 5px"></span>
                        <?= $info_analyst["total_listens"] ? $info_analyst["total_listens"] : 0 ?> <i
                            class="fa-solid fa-headphones" style="margin-left: 3px"></i>
                    </span>
                </div>
                <ul class="profile-navbar">
                    <a href="../MyHeart/MyHeart.php">
                        <li class="active">Yêu thích</li>
                    </a>
                    <a href="../MyAlbum/MyAlbum.php">
                        <li>Album</li>
                    </a>
                    <a href="../InfoUser/InfoUser.php">
                        <li>Cá nhân</li>
                    </a>
                </ul>
            </div>
            <div class="profile-content">
                <h3>Danh sách yêu thích</h3>
                <?php
                $sql_get_my_heart = $pdo->prepare("SELECT * FROM favorite_list INNER JOIN song ON favorite_list.song_id = song.song_id WHERE favorite_list.user_id = '$id_user' ORDER BY favorite_list_id DESC");
                $sql_get_my_heart->execute();
                $list_my_heart = $sql_get_my_heart->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <ul class="list-my--heart">
                    <?php for ($i = 0; $i < count($list_my_heart); $i++) { ?>
                        <a
                            href="../../ListSongPages/ListSongPages.php?album_id=<?php echo $list_my_heart[$i]['album_id'] ?>&song_id=<?php echo $list_my_heart[$i]['song_id'] ?>">
                            <li <?php
                            if ($list_my_heart[$i]['type_song'] == 'vip') {
                                echo 'style="background: #ffd70021"';
                            }
                            ?> class="item-my--heart">
                                <div class="song-info">
                                    <div class="img-thumbnail">
                                        <?php
                                        if ($list_my_heart[$i]['type_song'] == 'vip') {
                                            echo '<i class="fa-solid fa-crown"></i>';
                                        }
                                        ?>
                                        <img src="<?php echo $list_my_heart[$i]["song_thumbnail"] ?>" alt="">
                                        <i class="fa-solid fa-circle-play btn-play"></i>
                                    </div>
                                    <div class="info-song">
                                        <div class="name-song" <?php
                                        if ($list_my_heart[$i]['type_song'] == 'vip') {
                                            echo 'style="color: gold;"';
                                        }
                                        ?>><?php echo $list_my_heart[$i]["title_song"] ?>
                                        </div>
                                        <div class="author-song" <?php
                                        if ($list_my_heart[$i]['type_song'] == 'vip') {
                                            echo 'style="color: gold;"';
                                        }
                                        ?>><?php echo $list_my_heart[$i]["title_artist"] ?>
                                        </div>
                                        <div class="heart-quantity" <?php
                                        if ($list_my_heart[$i]['type_song'] == 'vip') {
                                            echo 'style="color: gold;"';
                                        }
                                        ?>><?php echo $list_my_heart[$i]["listen_count"] ?> <i
                                                class="fa-solid fa-headphones-simple"></i> </div>
                                    </div>
                                </div>
                                <span>
                                    <i class="fa-solid fa-heart"></i>
                                    <p class="song-duration"><?php echo $list_my_heart[$i]["duration"] ?></p>
                                </span>
                            </li>
                        </a>

                    <?php } ?>
                </ul>

            </div>
        </div>

    </div>
</body>

</html>