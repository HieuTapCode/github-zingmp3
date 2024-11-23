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
    <link rel="shortcut icon" href="../../Component/assets/logo_mobile.png" type="image/x-icon">
    <title>Chỉnh sửa bài hát - Nhóm Phát Triển Ứng Dụng Web</title>
    <!-- import link css file component  -->
    <link rel="stylesheet" href="../../GlobalStyle/style.css">
    <link rel="stylesheet" href="./UpdateSong.css">
    <!-- Choices.js CSS -->

</head>

<body>
    <?php require_once "../../Config/configConnectDB.php" ?>
    <?php
    $artist_id = $_SESSION["id_user"];
    $album_id = $_REQUEST["album_id"];
    $song_id = $_REQUEST["song_id"];
    $sql_song = $pdo->prepare("SELECT * FROM song WHERE song_id = :song_id");
    $sql_song->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $sql_song->execute();
    $info_song = $sql_song->fetch(PDO::FETCH_ASSOC);

    // Lấy danh sách thể loại từ cơ sở dữ liệu
    $sql_genres = $pdo->prepare("SELECT DISTINCT kindof FROM song");
    $sql_genres->execute();
    $genres = $sql_genres->fetchAll(PDO::FETCH_COLUMN);
    ?>
    <div id="update-song--main">
        <?php require "../../Component/Navbar/Navbar.php" ?>
        <div class="update-song--right">
            <div class="update-song--container">
                <h1>Chỉnh sửa Bài Hát</h1>
                <form action="./ProcessUpdateSong.php?song_id=<?= htmlspecialchars($song_id) ?>" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" id="album_id" name="album_id"
                        value="<?= htmlspecialchars($_REQUEST['album_id']) ?>">
                    <div class="form-group">
                        <label for="title_song">Tên Bài Hát:</label>
                        <input type="text" id="title_song" name="title_song"
                            value="<?= htmlspecialchars($info_song['title_song']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="title_artist">Tên Nghệ Sĩ:</label>
                        <input type="text" id="title_artist" name="title_artist"
                            value="<?= htmlspecialchars($info_song['title_artist']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="kindof">Thể loại:</label>
                        <input list="genres" id="kindof" name="kindof"
                            value="<?= htmlspecialchars($info_song['kindof']) ?>" required autocomplete="off">
                        <datalist id="genres">
                            <?php foreach ($genres as $genre): ?>
                                <option value="<?= htmlspecialchars($genre) ?>">
                                <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-group" style="display: flex; align-items: center;">
                        <label style="margin-right: 10px;">Bản quyền:</label>
                        <span style="display: flex; align-items: center; margin-right: 10px;">
                            <input type="radio" id="type_song_vip" value="vip" name="type_song" <?php
                            if ($info_song['type_song'] === "vip") {
                                echo "checked";
                            }
                            ?> style="margin-right: 5px;">
                            <label for="type_song_vip">Bản quyền</label>
                        </span>
                        <span style="display: flex; align-items: center;">
                            <input type="radio" id="type_song_free" value="free" <?php
                            if ($info_song['type_song'] === "free") {
                                echo "checked";
                            }
                            ?> name="type_song"
                                style="margin-right: 5px;">
                            <label for="type_song_free">Miễn phí</label>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="mp3_link">Đăng bài hát:</label>
                        <input style="cursor: not-allowed;" title="Không thể thay thế bài hát đã đăng" type="text"
                            id="mp3_link" value="<?= htmlspecialchars($info_song['mp3_link']) ?>" disabled
                            name="mp3_link" required>
                    </div>

                    <div class="form-group">
                        <label for="song_thumbnail">Ảnh Bìa:</label>
                        <label for="song_thumbnail">
                            <div class="img-preview">
                                <img src="<?= htmlspecialchars($info_song['song_thumbnail']) ?>" alt="">
                            </div>
                        </label>
                        <input accept="image/*" hidden type="file" id="song_thumbnail" name="song_thumbnail"
                            accept="image/*">
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Cập nhật bài hát">
                    </div>
                </form>
                <a href="../UpdateListSong/UpdateListSong.php?album_id=<?= htmlspecialchars($album_id) ?>"><button
                        class="turn-back">Quay lại</button></a>
            </div>
        </div>
    </div>
</body>

</html>