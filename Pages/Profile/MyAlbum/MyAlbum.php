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
    <title>Album của tôi - Nhóm Phát Triển Ứng Dụng Web</title>
    <!-- import link css file component  -->
    <link rel="stylesheet" href="../../../GlobalStyle/style.css">
    <link rel="stylesheet" href="../Profile.css">
    <link rel="stylesheet" href="./MyAlbum.css">
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
        // print_r($info_user);
        
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
                        <li>Yêu thích</li>
                    </a>
                    <a href="../MyAlbum/MyAlbum.php">
                        <li class="active">Album</li>
                    </a>
                    <a href="../InfoUser/InfoUser.php">
                        <li>Cá nhân</li>
                    </a>
                </ul>
            </div>
            <div class="profile-content">
                <?php
                $sql_my_album = $pdo->prepare("SELECT * FROM album WHERE artist_id = $id_user");
                $sql_my_album->execute();
                $my_album = $sql_my_album->fetchAll(PDO::FETCH_ASSOC);

                ?>
                <h3>Album của bạn </h3>
                <div style="margin-bottom: 5px;font-size: 13px;"> Album: <?= $info_analyst["total_albums"] ?> <i
                        style="margin-right: 5px" class="fa-solid fa-compact-disc"></i></div>
                <div style="margin-bottom: 5px;font-size: 13px;">Bài hát: <?= $info_analyst["total_songs"] ?> <i
                        style="margin-right: 5px" class="fa-solid fa-music"></i></div>
                <ul class="list-my--album">


                    <?php for ($i = 0; $i < count($my_album); $i++) { ?>
                        <a href="../../UpdateListSong/UpdateListSong.php?album_id=<?php echo $my_album[$i]["album_id"] ?>">
                            <li class="item-my--album">
                                <i class="fa-regular fa-circle-play"></i><img
                                    src="<?php echo $my_album[$i]["thumbnail_album"] ?>" alt="Ảnh thumbnail Album">
                            </li>
                            <p class="title-author"><?php echo $my_album[$i]["title_album"] ?>
                            </p>
                            <p class="name-author">
                                <?php echo $my_album[$i]["name_artist"] ?>
                            </p>
                        </a>
                    <?php } ?>

                    <a href="../AddAlbum/AddAlbum.php" class="add-album">
                        <i class="fa-solid fa-circle-plus"></i>
                    </a>
                </ul>
            </div>
        </div>

    </div>
</body>

</html>