<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <!-- favicon  -->
  <link rel="shortcut icon" href="../../Component/assets/logo_mobile.png" type="image/x-icon">
  <title>Quản lý Album - Nhóm Phát Triển Ứng Dụng Web</title>
  <!-- import css file component  -->
  <link rel="stylesheet" href="../../GlobalStyle/style.css">
  <link rel="stylesheet" href="./UpdateListSong.css" />

</head>

<body>
  <div id="display-main">
    <?php require '../../Component/Navbar/Navbar.php' ?>
    <?php require_once "../../Config/configConnectDB.php" ?>
    <?php
    $id_user = $_SESSION["id_user"];
    $album_id = $_REQUEST["album_id"];
    $sql_get_album = $pdo->prepare("SELECT * FROM album WHERE album_id='$album_id' AND  artist_id = '$id_user'");
    $sql_get_album->execute();
    $album_info = $sql_get_album->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="right-container">

      <?php require '../../Component/Header/HeaderLayout.php' ?>

      <?php if (count($album_info)) { ?>
        <div class="content-container">
          <div class="content-container-left">
            <div class="avt-disk-play">
              <img src="<?php echo $album_info['thumbnail_album'] ?>" alt="Ảnh Album" />
            </div>
            <div class="media-content">
              <div class="content-top">
                <h3 class="title"><?php echo $album_info['title_album'] ?></h3>
                <div class="artists">
                  <div class="like">
                    <p><i class="fa-regular fa-heart"></i> <b class="like_count">0</b> người yêu thích</p>
                    <p><i class="fa-solid fa-headphones-simple"></i> <b class="listen_count">0</b> lượt nghe</p>
                  </div>
                  <span>Tác giả: <?php echo $album_info['name_artist'] ?> </span>
                </div>
              </div>
              <form class="actions" method="post" action="./ProcessDeleteAlbum.php">
                <button class="btn-play-all" tabindex="0">
                  <input type="text" value="<?php echo $album_id ?>" hidden name="album_id">
                  <i class="fa-solid fa-trash"></i> <span> Xóa Album</span>
                </button>

              </form>
            </div>
          </div>


          <div class="content-container-right">

            <div class="zing-recommend--item zing-recommend--title">
              <div class="zing-recommend--item-left">Bài hát</div>
              <div class="zing-recommend--item-center">Lượt tương tác</div>
              <div class="zing-recommend--item-right">Thời gian</div>
            </div>


            <div class="zing-recommend--list">
              <?php
              $sql_list_song = $pdo->prepare("SELECT song.like_count, song.type_song, song.listen_count,song.song_id, album.album_id, album.title_album, song.artist_id, song.song_thumbnail, song.title_song, song.duration, song.title_artist 
                                              FROM song INNER JOIN album ON song.album_id = album.album_id 
                                              WHERE album.album_id = $album_id AND song.artist_id= $id_user");
              $sql_list_song->execute();
              $list_song = $sql_list_song->fetchAll(PDO::FETCH_ASSOC);
              $total_count_listener = 0;
              $total_count_like = 0;
              ?>

              <?php for ($i = 0; $i < count($list_song); $i++) {
                $total_count_listener += $list_song[$i]["listen_count"];
                $total_count_like += $list_song[$i]["like_count"];
                ?>
                <div class="list-song">
                  <a href="../ListSongPages/ListSongPages.php?album_id=<?php echo $list_song[$i]['album_id'] ?>&song_id=<?php echo $list_song[$i]['song_id'] ?>"
                    class="play_song">
                    <div class="zing-recommend--item"   <?php
                                if ($list_song[$i]['type_song'] == 'vip') {
                                    echo 'style="background: #ffd70021"';
                                }
                            ?>>
                      <div class="zing-recommend--item-left">
                        <div class="img-avt-infor">
                          <?php
                          if ($list_song[$i]['type_song'] == 'vip') {
                            echo '<i class="fa-solid fa-crown"></i>';
                          }
                          ?>
                          <img src="<?php echo $list_song[$i]["song_thumbnail"] ?>" alt="Ảnh nền ảnh" />
                        </div>
                        <div class="zing-recommend--item-text">
                          <div class="name-song"><?php echo $list_song[$i]["title_song"] ?></div>
                          <div class="name-single"><?php echo $list_song[$i]["title_artist"] ?></div>
                        </div>
                      </div>
                      <div class="zing-recommend--item-center">
                        <span><i
                            class="fa-solid fa-headphones-simple"></i><?php echo $list_song[$i]['listen_count'] ?></span>
                        <span><i class="fa-regular fa-heart"></i><?php echo $list_song[$i]['like_count'] ?></span>
                      </div>
                      <div class="zing-recommend--item-right">
                        <span><?php echo $list_song[$i]["duration"] ?></span>
                      </div>
                    </div>
                  </a>
                  <div class="action_updateSong">
                    <a href="./ProcessDeleteSong.php?album_id=<?php echo $list_song[$i]['album_id'] ?>&song_id=<?php echo $list_song[$i]['song_id'] ?>"
                      class="delete_song">
                      <i class="fa-solid fa-trash" title="Xóa bài hát"></i>
                    </a>
                    <a href="../UpdateSong/UpdateSong.php?album_id=<?php echo $list_song[$i]['album_id'] ?>&song_id=<?php echo $list_song[$i]['song_id'] ?>"
                      class="update_song">
                      <i class="fa-solid fa-wrench" title="Sửa bài hát"></i>
                    </a>
                  </div>
                </div>
              <?php } ?>
              <a href="../Profile/AddSong/AddSong.php?album_id=<?php echo $album_id ?>" title="Thêm bài hát">
                <div class="zing-recommend--item add_song">
                  <i class="fa-solid fa-plus"></i>
                </div>
              </a>
            </div>


          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <script>
    //in ra số lượt nghe và tym
    document.querySelector(".artists b.like_count").innerHTML = <?php echo $total_count_like ?>;
    document.querySelector(".artists b.listen_count").innerHTML = <?php echo $total_count_listener ?>;
  </script>
</body>

</html>