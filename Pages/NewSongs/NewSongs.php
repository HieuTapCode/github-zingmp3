<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- favicon  -->
    <link rel="shortcut icon" href="../../Component/assets/logo_mobile.png" type="image/x-icon">
    <title>Bài hát mới phát hành - Nhóm Phát Triển Ứng Dụng Web</title>
    <!-- import css file component  -->
    <link rel="stylesheet" href="../../GlobalStyle/style.css">
    <link rel="stylesheet" href="./NewSongs.css">
</head>

<body>
    <div id="newsongs">
        <?php require '../../Component/Navbar/Navbar.php' ?>
        <?php require_once "../../Config/configConnectDB.php" ?>

        <div class="newsongs-main">
            <?php require '../../Component/Header/HeaderLayout.php' ?>
            <div class="newsongs-container">
                <h1 class="title">
                    <i class="fa-solid fa-music"></i> Nhạc mới
                </h1>
                <ul class="newsongs-list">
                    <?php
                    $sql_new_song = $pdo->prepare("SELECT * FROM song ORDER BY release_date DESC LIMIT 15");
                    $sql_new_song->execute();
                    $list_new_song = $sql_new_song->fetchAll(PDO::FETCH_ASSOC);
                    for ($i = 0; $i < count($list_new_song); $i++) {
                        ?>
                        <a 
                            href="../ListSongPages/ListSongPages.php?album_id=<?php echo $list_new_song[$i]['album_id'] ?>&song_id=<?php echo $list_new_song[$i]['song_id'] ?>">
                            <li
                            <?php
                                if ($list_new_song[$i]['type_song'] == 'vip') {
                                    echo 'style="background: #ffd70021"';
                                }
                            ?>
                                class="newsongs-list_item">
                                <div class="song-info">
                                    <div class="song_thumbnail">
                                        <?php
                                        if ($list_new_song[$i]['type_song'] == 'vip') {
                                            echo '<i class="fa-solid fa-crown"></i>';
                                        }
                                        ?>
                                        <img src="<?php echo $list_new_song[$i]["song_thumbnail"] ?>" alt="">
                                        <i class="fa-solid fa-circle-play"></i>
                                    </div>
                                    <div class="song_details">
                                        <div class="song-name" <?php
                                        if ($list_new_song[$i]['type_song'] == 'vip') {
                                            echo 'style="color: gold;"';
                                        }
                                        ?>><?php echo $list_new_song[$i]["title_song"] ?>
                                        </div>
                                        <div class="song-author" <?php
                                        if ($list_new_song[$i]['type_song'] == 'vip') {
                                            echo 'style="color: gold;"';
                                        }
                                        ?>>
                                            <?php echo $list_new_song[$i]["title_artist"] ?></div>
                                        <div class="heart-quantity" <?php
                                        if ($list_new_song[$i]['type_song'] == 'vip') {
                                            echo 'style="color: gold;"';
                                        }
                                        ?>>
                                            <i class="fa-solid fa-headphones-simple"></i>
                                            <?php echo $list_new_song[$i]["listen_count"] ?>
                                            <!-- đổi chuỗi sang timestamp rồi format date   -->
                                            <p><?php echo date("d/m/Y", strtotime($list_new_song[$i]['release_date'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <span>
                                    <p class="song-duration"><?php echo $list_new_song[$i]["duration"] ?></p>
                                </span>
                            </li>
                        </a>
                    <?php } ?>

                </ul>
            </div>
        </div>
</body>

</html>