<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/ZingMP3/Component/PlayingBar/PlayingBar.css?v=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <?php
  $sql_get_song = $pdo->prepare("SELECT * FROM song WHERE song_id = '$song_id'");
  $sql_get_song->execute();
  $info_song = $sql_get_song->fetch(PDO::FETCH_ASSOC);

  $id_user = NULL;
  if (isset($_SESSION["id_user"])) {
    $id_user = $_SESSION["id_user"];
  }
  $sql_check_hearted = $pdo->prepare("SELECT * FROM favorite_list WHERE song_id = '$song_id' AND user_id = '$id_user' ");
  $sql_check_hearted->execute();
  $check_hearted = $sql_check_hearted->fetch(PDO::FETCH_ASSOC);

  // lấy index bài hát hiện tại
  $currentIndex = 0;
  for ($i = 0; $i < count($list_song); $i++) {
    if ($list_song[$i]['song_id'] == $song_id) {
      $currentIndex = $i;
    }
  }
  ?>
  <div class="playing-bar">
    <div class="player_controls-main">
      <div class="player_controls-left">
        <div class="player_controls-media">
          <div class="media_left">
            <img class="media-avatar" src="<?php echo $info_song['song_thumbnail'] ?>" alt="" />
            <div class="media_center">
              <div class="media_music">
                <p><?php echo $info_song['title_song'] ?></p>
              </div>
              <div class="media_name">
                <span><?php echo $info_song['title_artist'] ?></span>
              </div>
            </div>
          </div>
          <div class="media_right">
            <div class="media_right-btn player_btn" title="Thêm vào mục Yêu thích">
              <?php if ($check_hearted) {
                echo '<i class="fa-solid fa-heart "></i>';
              } else {
                echo '<i class="fa-regular fa-heart"></i>';
              }
              ?>
            </div>

          </div>
        </div>
      </div>
      <div class="player_controls-center">
        <div class="player_top">
          <div id="randomMusic" class="player_btn playing_random">
            <i class="fa-solid fa-shuffle"></i>
          </div>
          <a href="/ZingMP3/Pages/ListSongPages/ListSongPages.php?album_id=<?php echo $album_id ?>&song_id=<?php echo $list_song[$currentIndex - 1]['song_id'] ?>"
            id="prevMusic" class="player_btn playing_back">
            <i class="fa-solid fa-backward-step"></i>
          </a>
          <div class="player_playing-input player_btn relative">
            <i class="fa-solid fa-pause"></i>
          </div>
          <a href="/ZingMP3/Pages/ListSongPages/ListSongPages.php?album_id=<?php echo $album_id ?>&song_id=<?php echo $list_song[$currentIndex + 1]['song_id'] ?>"
            id="nextMusic" class="player_btn playing_next">
            <i class="fa-solid fa-forward-step"></i>
          </a>
          <div id="loopMusic" class="player_btn playing_replay">
            <i class="fa-solid fa-repeat active"></i>
          </div>
        </div>
        <div class="player_bottom">
          <p class="playing_time-left">0:00</p>
          <div class="playing_time-up2 progress-area">
            <div class="progress-bar" style="width: 0%"></div>
          </div>
          <p class="playing_time-right"><?php echo $info_song['duration'] ?></p>
        </div>
      </div>
      <div class="player_controls-right">
        <div class="player_volume playing_volume">
          <div class="player_btn volume-icon"><i class="fa-solid fa-volume-high"></i></div>
          <div class="playing_volume-input">
            <input class="transition-all" id="inputVolume" type="range" min="0" max="100" value="100" />
          </div>
        </div>
      </div>
    </div>
  </div>
  <audio id="SongPlaying" hidden loop autoplay src="<?php echo $info_song['mp3_link'] ?>"></audio>

  <script>
    // Lấy các thẻ HTML cần thao tác với
    const timeLeft = document.querySelector(".playing_time-left");
    const timeRight = document.querySelector(".playing_time-right");
    const btnPlay = document.querySelector(".player_btn.relative i");
    const btnRandom = document.querySelector(".player_btn.playing_random i");
    const btnLoop = document.querySelector(".player_btn.playing_replay i");
    const songPlaying = document.querySelector("#SongPlaying");
    const btnVolume = document.querySelector("#inputVolume");
    const volumeIcon = document.querySelector(".player_btn.volume-icon");
    const progressArea = document.querySelector(".progress-area");
    const progressBar = document.querySelector(".progress-bar");
    const diskAvtAlbum = document.querySelector(".avt-disk-play");
    // Biến để theo dõi trạng thái kéo thanh progress bar
    let isDragging = false;
    let isRandom = localStorage.getItem("isRandom") === "true";

    // Hàm cập nhật trạng thái nút Loop và Random
    function updateButtonState() {
      if (isRandom) {
        songPlaying.loop = false;
        btnLoop.classList.remove("active");
        btnRandom.classList.add("active");
      } else {
        songPlaying.loop = true;
        btnLoop.classList.add("active");
        btnRandom.classList.remove("active");
      }
    }
    // Cập nhật trạng thái ban đầu
    updateButtonState();

    // Bắt sự kiện click trên nút Loop
    btnLoop.addEventListener("click", function () {
      isRandom = !isRandom;
      updateButtonState();
      // Lưu giá trị isRandom vào localStorage
      localStorage.setItem("isRandom", JSON.stringify(isRandom));
    });

    // Bắt sự kiện click trên nút Random
    btnRandom.addEventListener("click", function () {
      isRandom = !isRandom;
      updateButtonState();
      // Lưu giá trị isRandom vào localStorage
      localStorage.setItem("isRandom", JSON.stringify(isRandom));
    });



    // Bắt sự kiện "ended" để khi nhạc kết thúc, nút chuyển thành "pause"
    songPlaying.addEventListener("ended", function () {
      if (isRandom) {
        window.location = "/ZingMP3/Pages/ListSongPages/ListSongPages.php?album_id=<?php echo $album_id ?>&song_id=<?php echo $list_song[rand(0, count($list_song) - 1)]['song_id'] ?>"
      }
    });

    // Bắt sự kiện click trên nút phát/pause
    let isPlaying = true; // Thêm biến để theo dõi trạng thái phát/nghỉ
    btnPlay.addEventListener("click", function () {
      if (!isPlaying) {
        // Nếu đang nghỉ, bắt đầu phát nhạc
        songPlaying.play();
        isPlaying = true;
        btnPlay.classList.remove("pause");
        diskAvtAlbum.classList.add("spin");
      } else {
        // Nếu đang phát, dừng nhạc
        songPlaying.pause();
        isPlaying = false;
        btnPlay.classList.add("pause");
        diskAvtAlbum.classList.remove("spin");
      }
    });



    // Hàm định dạng thời gian bài hát theo mm:ss
    function formatTime(time) {
      const minutes = Math.floor(time / 60);
      const seconds = Math.floor(time % 60);
      return `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
    }

    // Bắt sự kiện timeupdate của audio để cập nhật progress-bar và thời gian
    songPlaying.addEventListener("timeupdate", function () {
      const currentTime = songPlaying.currentTime;
      const duration = songPlaying.duration;
      const progressPercentage = (currentTime / duration) * 100;
      progressBar.style.width = `${progressPercentage}%`;

      timeLeft.innerHTML = formatTime(currentTime);
      timeRight.innerHTML = formatTime(duration);
    });



    // hàm sử lý khi có nút volume thay đổi
    function UpdateVolume(volumeValue) {
      localStorage.setItem("defaultVolume", volumeValue)
      btnVolume.style.background = `linear-gradient(90deg, #fff ${volumeValue - 1}%, hsla(0, 0%, 100%, 0.3) ${volumeValue - 1}%)`;
      btnVolume.value = volumeValue;
      songPlaying.volume = volumeValue / 100;
      if (volumeValue == 0) {
        volumeIcon.innerHTML = '<i class="fa-solid fa-volume-xmark"></i>';
      } else if (volumeValue > 0 && volumeValue <= 70) {
        volumeIcon.innerHTML = '<i class="fa-solid fa-volume-low"></i>';
      } else if (volumeValue > 70) {
        volumeIcon.innerHTML = '<i class="fa-solid fa-volume-high"></i>';
      }
    }


    let defaultVolume = localStorage.getItem("defaultVolume") || 1;
    UpdateVolume(defaultVolume);
    // Bắt sự kiện thay đổi âm lượng
    btnVolume.addEventListener("input", function (e) {
      let volume = parseFloat(e.target.value);
      UpdateVolume(volume);
    });

    // Bắt sự kiện chuột khi kéo thanh progress bar
    progressArea.addEventListener("mousedown", (event) => {
      isDragging = true;
      updateProgressBar(event);
    });

    document.addEventListener("mousemove", (event) => {
      if (isDragging) {
        updateProgressBar(event);
      }
    });

    document.addEventListener("mouseup", () => {
      isDragging = false;
    });

    // Bắt sự kiện click chuột để nhảy đến vị trí cụ thể trên progress bar
    progressArea.addEventListener("click", (event) => {
      if (!isDragging) {
        updateProgressBar(event);
      }
    });

    // Hàm cập nhật thanh progress bar và thời gian khi kéo
    function updateProgressBar(event) {
      const mouseX = event.clientX - progressArea.getBoundingClientRect().left;
      const progressBarWidth = progressArea.clientWidth;
      const percentage = (mouseX / progressBarWidth) * 100;

      if (percentage >= 0 && percentage <= 100) {
        progressBar.style.width = `${percentage}%`;

        const duration = songPlaying.duration;
        const seekTime = (percentage / 100) * duration;
        songPlaying.currentTime = seekTime;
      }
    }
  </script>
</body>

</html>