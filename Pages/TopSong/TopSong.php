<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- favicon  -->
  <link rel="shortcut icon" href="../../Component/assets/logo_mobile.png" type="image/x-icon">
  <title>Bảng xếp hạng bài hát thịnh hành - Nhóm Phát Triển Ứng Dụng Web</title>
  <link rel="stylesheet" href="./TopSong.css" />
  <link rel="stylesheet" href="../../GlobalStyle/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div id="topsong-main">
    <?php require "../../Component/Navbar/Navbar.php" ?>
    <?php require_once "../../Config/configConnectDB.php" ?>

    <div id="topsong-right">
      <?php require "../../Component/Header/HeaderLayout.php" ?>

      <div class="content-layout">
        <div class="header-titlepage">
          <div class="text-titlepage">
            <h1>Top 20 thịnh hành</h1>
          </div>
          <div class="control">
            <button class="icon-play">
              <i class="fa-solid fa-play"></i>
            </button>
          </div>
        </div>
        <ul class="topsong-list">
          <?php
          $sql_top_song = $pdo->prepare("SELECT * FROM song ORDER BY listen_count DESC, like_count DESC LIMIT 20;");
          $sql_top_song->execute();
          $list_top_song = $sql_top_song->fetchAll(PDO::FETCH_ASSOC);
          for ($i = 0; $i < count($list_top_song); $i++) {
            ?>

            <a
              href="../ListSongPages/ListSongPages.php?album_id=<?php echo $list_top_song[$i]['album_id'] ?>&song_id=<?php echo $list_top_song[$i]['song_id'] ?>">
              <li <?php
              if ($list_top_song[$i]['type_song'] == 'vip') {
                echo 'style="background: #ffd70021"';
              }
              ?>
                class="topsong-list--item">
                <div class="song-info">
                  <div class="playListSong-numbersong-dash">
                    <span><?php echo $i + 1 ?></span>
                  </div>
                  <div class="img-thumbnail">
                    <?php
                    if ($list_top_song[$i]['type_song'] == 'vip') {
                      echo '<i class="fa-solid fa-crown"></i>';
                    }
                    ?>
                    <img src="<?php echo $list_top_song[$i]["song_thumbnail"] ?>" alt="">
                    <i class="fa-solid fa-circle-play"></i>
                  </div>
                  <div class="info-song">
                    <div class="name-song" <?php
                    if ($list_top_song[$i]['type_song'] == 'vip') {
                      echo 'style="color: gold;"';
                    }
                    ?>><?php echo $list_top_song[$i]["title_song"] ?></div>
                    <div class="author-song" <?php
                    if ($list_top_song[$i]['type_song'] == 'vip') {
                      echo 'style="color: gold;"';
                    }
                    ?>><?php echo $list_top_song[$i]["title_artist"] ?></div>
                    <div class="heart-quantity" <?php
                    if ($list_top_song[$i]['type_song'] == 'vip') {
                      echo 'style="color: gold;"';
                    }
                    ?>>
                      <i class="fa-solid fa-headphones-simple"></i> <?php echo $list_top_song[$i]["listen_count"] ?>
                      <!-- đổi chuỗi sang timestamp rồi format date   -->
                      <p><?php echo date("d/m/Y", strtotime($list_top_song[$i]['release_date'])) ?></p>
                    </div>
                  </div>
                </div>
                <span>
                  <p class="song-duration"><?php echo $list_top_song[$i]["duration"] ?></p>
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