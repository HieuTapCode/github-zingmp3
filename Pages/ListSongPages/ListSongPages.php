<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <!-- favicon  -->
  <link rel="shortcut icon" href="../../Component/assets/logo_mobile.png" type="image/x-icon">
  <title>Trình phát nhạc - Nhóm Phát Triển Ứng Dụng Web</title>
  <!-- import css file component  -->
  <link rel="stylesheet" href="../../GlobalStyle/style.css">
  <link rel="stylesheet" href="./ListSongPages.css" />

</head>

<body>
  <div id="display-main">
    <?php require '../../Component/Navbar/Navbar.php' ?>
    <?php require_once "../../Config/configConnectDB.php" ?>
    <div class="right-container">
      <?php require '../../Component/Header/HeaderLayout.php' ?>
      <div class="content-container">
        <div class="content-container-left">
          <?php
          $Author = true;
          $user = null;
          $album_id = $_REQUEST["album_id"];
          $song_id = isset($_REQUEST["song_id"]) ? $_REQUEST["song_id"] : null;
          //Lấy album và list nhạc
          $sql_get_album = null;

          if (isset($_SESSION["id_user"])) {
            $id_user = $_SESSION["id_user"];
            // Sử dụng prepared statement để bảo mật
            $statement = $pdo->prepare("SELECT type_account FROM user WHERE id_user = :id_user");
            $statement->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
          }


          if ($song_id) {
            $sql_get_album = $pdo->prepare("SELECT * FROM album INNER JOIN song on album.album_id = song.album_id WHERE album.album_id = '$album_id' AND song.song_id = '$song_id'");
            $sql_get_album->execute();
            $album_info = $sql_get_album->fetch(PDO::FETCH_ASSOC);
          } else {
            $sql_get_album = $pdo->prepare("SELECT * FROM album INNER JOIN song on album.album_id = song.album_id WHERE album.album_id = '$album_id' LIMIT 1");
            $sql_get_album->execute();
            $album_info = $sql_get_album->fetch(PDO::FETCH_ASSOC);
            $song_id = $album_info["song_id"];
          }

          if ($album_info['type_song'] === 'vip') {
            if ($user) {
              if ($user['type_account'] === 'vip') {
                $Author = true;
              } else {
                $Author = false;
              }
            } else {
              $Author = false;
            }
          }


          ?>

          <?php
          // Lấy list nhạc 
          $sql_list_song = $pdo->prepare("SELECT song.listen_count, song.type_song  , song.like_count, song.song_id, album.album_id, album.title_album, song.artist_id, song.song_thumbnail, song.title_song, song.duration, song.title_artist 
                                          FROM song INNER JOIN album ON song.album_id = album.album_id 
                                          WHERE album.album_id = $album_id");
          $sql_list_song->execute();
          $list_song = $sql_list_song->fetchAll(PDO::FETCH_ASSOC);
          $total_count_listener = 0;
          $total_count_like = 0;


          ?>


          <div class="avt-disk-play <?= $Author ? 'spin' : '' ?>">
            <img src="<?php echo $album_info['song_thumbnail'] ?>" alt="" />
          </div>
          <div class="media-content">
            <div class="content-top">
              <h2 class="title"><?php echo $album_info['title_album'] ?></h2>
              <div class="artists">
                <div class="like">
                  <p><i class="fa-regular fa-heart"></i> <b class="like_count">0</b> người yêu thích</p>
                  <p><i class="fa-solid fa-headphones-simple"></i> <b class="listen_count">0</b> lượt nghe</p>
                </div>
                <span><?php echo $album_info['name_artist'] ?></span>
              </div>
            </div>
            <div class="actions">
              <button class="btn-play-all" tabindex="0">
                <i class="fa-solid fa-play"></i><span>Phát Album</span>
              </button>
            </div>
          </div>
        </div>


        <div class="content-container-right">
          <div class="description">
            <span>Chủ đề: </span> <?php echo $album_info['kindof'] ?>
          </div>

          <div class="zing-recommend--item zing-recommend--title">
            <div class="zing-recommend--item-left">BÀI HÁT</div>
            <div class="zing-recommend--item-center">LƯỢT TƯƠNG TÁC</div>
            <div class="zing-recommend--item-right gap-3">THỜI GIAN</div>
          </div>


          <div class="list-recommend">


            <?php for ($i = 0; $i < count($list_song); $i++) {
              $total_count_listener += $list_song[$i]["listen_count"];
              $total_count_like += $list_song[$i]["like_count"];
              ?>
              <a href="./ListSongPages.php?album_id=<?php echo $list_song[$i]['album_id'] ?>&song_id=<?php echo $list_song[$i]['song_id'] ?>"
                class="zing-recommend--list">
                <!-- active nhạc đang phát -->
                <div class="zing-recommend--item 
                <?php echo ($list_song[$i]["song_id"] == $song_id) ? "active" : "" ?> " <?php
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
                      <img src="../../Component/assets/wave.gif" class="wave_music " alt="">
                      <img src="<?php echo $list_song[$i]['song_thumbnail'] ?>" alt="" />
                    </div>
                    <div class="zing-recommend--item-text">
                      <div class="name-song" <?php
                      if ($list_song[$i]['type_song'] == 'vip') {
                        echo 'style="color: gold;"';
                      }
                      ?>><?php echo $list_song[$i]['title_song'] ?></div>
                      <div class="name-single" <?php
                      if ($list_song[$i]['type_song'] == 'vip') {
                        echo 'style="color: gold;"';
                      }
                      ?>><?php echo $list_song[$i]['title_artist'] ?>
                        <div class="zing-recommend--item-center mobile" <?php
                        if ($list_song[$i]['type_song'] == 'vip') {
                          echo 'style="color: gold;"';
                        }
                        ?>>
                          <span><i
                              class="fa-solid fa-headphones-simple"></i><?php echo $list_song[$i]['listen_count'] ?></span>
                          <span><i class="fa-regular fa-heart"></i><?php echo $list_song[$i]['like_count'] ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="zing-recommend--item-center">
                    <span><i class="fa-solid fa-headphones-simple"></i><?php echo $list_song[$i]['listen_count'] ?></span>
                    <span><i class="fa-regular fa-heart"></i><?php echo $list_song[$i]['like_count'] ?></span>
                  </div>
                  <div class="zing-recommend--item-right">
                    <span><?php echo $list_song[$i]['duration'] ?></span>
                  </div>
                </div>
              </a>
            <?php } ?>

          </div>
        </div>
      </div>
      <?php
      $id_user = $_SESSION["id_user"] ?? NULL;
      if (isset($id_user)) {
        require "../../Component/SongForYou/SongForYou.php";
      }

      ?>
      <?php require "../../Component/AlbumHot/AlbumHot.php" ?>
    </div>
    <?php

    if ($Author) {
      require '../../Component/PlayingBar/PlayingBar.php';
    } else {
      echo '<h3 class="errorAuth">Nhạc chứa bản quyền sở hữu của tác giả. Vui lòng <a href="/Zingmp3/vnpay_php/vnpay_pay.php">nâng cấp VIP</a> để nghe</h3>';

    }
    ?>
  </div>

  <script>
    if (<?= json_encode($Author) ?> == false) {
      console.log(123);
      
      setTimeout(() => {
        window.location = "/ZingMP3/Pages/ListSongPages/ListSongPages.php?album_id=<?php echo $album_id ?>&song_id=<?php echo $list_song[rand(0, count($list_song) - 1)]['song_id'] ?>"
      }, 4000)
    }
    var activeItem = document.querySelector('.zing-recommend--item.active');

    if (activeItem) {
      // Cuộn đến thẻ li active
      document.querySelector('.list-recommend').scrollTop = activeItem.offsetTop - (activeItem.clientHeight * 3);
    }

    //in ra số lượt nghe và tym
    document.querySelector(".artists b.like_count").innerHTML = <?php echo $total_count_like ?>;
    document.querySelector(".artists b.listen_count").innerHTML = <?php echo $total_count_listener ?>;
    // Hàm để gửi yêu cầu AJAX để cập nhật listen_count
    function updateListenCount() {
      var xhr = new XMLHttpRequest();
      var songId = "<?php echo $song_id; ?>";

      var params = "song_id=" + encodeURIComponent(songId);
      xhr.open("POST", "./ProcessIncreListener.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send(params);

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Xử lý phản hồi từ server nếu cần
          console.log(xhr.responseText);
        }
      };
    }

    // Gọi hàm cập nhật listen_count sau 20 giây sau khi trang web được tải
    setTimeout(updateListenCount, 20000);


    //xử lý tym 
    // Lấy phần tử nút yêu thích và các biểu tượng trái tim
    const favoriteBtn = document.querySelector(".media_right-btn");

    // Biến để theo dõi trạng thái yêu thích
    let isFavorite = document.querySelector(".media_right-btn i.fa-solid.fa-heart");

    // Xử lý sự kiện khi người dùng nhấp vào nút yêu thích
    favoriteBtn.addEventListener("click", function () {
      // Đảo ngược trạng thái yêu thích
      isFavorite = !isFavorite;

      // Tạo phần tử trái tim mới
      const heartIcon = document.createElement("i");
      heartIcon.className = isFavorite ? "fa-solid fa-heart" : "fa-regular fa-heart";

      // Xóa các phần tử trái tim hiện có trong favoriteBtn (nếu có)
      while (favoriteBtn.firstChild) {
        favoriteBtn.removeChild(favoriteBtn.firstChild);
      }

      // Thêm phần tử trái tim mới vào favoriteBtn
      favoriteBtn.appendChild(heartIcon);

      // window.location = `./ProcessHeart.php?song_id=<?php echo $song_id ?>&isFavorite=${isFavorite}`
      var xhr = new XMLHttpRequest();
      xhr.open("POST", `./ProcessHeart.php?song_id=<?php echo $song_id ?>&isFavorite=${isFavorite}`, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send();
    });
  </script>
</body>

</html>