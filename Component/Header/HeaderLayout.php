<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/ZingMP3/Component/Header/HeaderLayout.css">
  <link rel="stylesheet" href="../../../zingmp3/Component/Header/ProcessSearch.php">
</head>

<body>
  <div class="header">
    <div class="header-content">
      <div class="header_content-left">
        <button onclick="window.history.back()">
          <i class="fa-solid fa-arrow-left icon-btn-prev"></i>
        </button>
        <button onclick="window.history.forward()">
          <i class="fa-solid fa-arrow-right icon-btn-next"></i>
        </button>

        <form class="search-form-header" action="/ZingMP3/Pages/ResultSearch/ResultSearch.php" method="get">
          <button><i class="fa-solid fa-magnifying-glass"></i></button>
          <input onkeyup="loadData()" type="text" class="header-search" name="keyword"
            placeholder="Tìm kiếm bài hát, nghệ sĩ, lời bài hát..." />
          <div class="box-suggest">
            <p class="title-box">Gợi ý bài hát: </p>
            <div id="result"></div>
            <ul></ul>
          </div>
        </form>

        <script>
          var inputField = document.querySelector(".header-search");
          var box = document.querySelector(".box-suggest");
          var resultDiv = document.querySelector(".box-suggest ul");

          inputField.addEventListener("focus", function () {
            box.style.display = "block";
          });

          inputField.addEventListener("blur", function (e) {
            // Kiểm tra xem sự kiện có được kích hoạt bởi thẻ a không
            if (!e.relatedTarget || e.relatedTarget.tagName.toLowerCase() !== 'a') {
              box.style.display = "none";
            }
          });


          function loadData() {
            var inputValue = inputField.value;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
              if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText); // Giả sử kết quả trả về là JSON

                // Xóa nội dung hiện tại của div
                resultDiv.innerHTML = "";

                for (let index = 0; index < response.length; index++) {
                  var songData = response[index]; // Lấy thông tin bài hát từ kết quả

                  // Tạo phần tử HTML sử dụng template strings
                  var html = `<a href="/ZingMP3/Pages/ListSongPages/ListSongPages.php?album_id=${songData.album_id}&song_id=${songData.song_id}">
                                <i class="fa-solid fa-arrow-trend-up"></i>
                                <li>
                                  <img src="${songData.song_thumbnail}" alt="">
                                  <p><strong>${songData.title_song}</strong> <i>${songData.title_artist}</i></p>
                                </li>
                              </a>
                            `;
                  // Gắn phần tử HTML vào phần tử div
                  resultDiv.innerHTML += html;
                }
              }
            };


            // Thay đổi tên file PHP và phương thức HTTP tùy theo yêu cầu của bạn.
            xhr.open("GET", "/zingmp3/Component/Header/ProcessSearch.php?keyword=" + inputValue, true);
            xhr.send();
          }
        </script>

      </div>
      <div class="header-content-right">
        <a href="/ZingMP3/Component/Header/ProcessLogout.php" class="log_out">Đăng xuất <i
            class="fa-solid fa-right-from-bracket"></i></a>
        <?php
        // Hiện thông tin người dùng 
        if (isset($_SESSION["id_user"])) {
          $id_user = $_SESSION["id_user"];

          // Sử dụng prepared statement để bảo mật
          $statement = $pdo->prepare("SELECT avatar_link, type_account FROM user WHERE id_user = :id_user");
          $statement->bindParam(':id_user', $id_user, PDO::PARAM_INT);
          $statement->execute();
          $result = $statement->fetch(PDO::FETCH_ASSOC);

          if ($result) {
            $avatar_link = $result["avatar_link"];
            $type_account = $result["type_account"];
            $border_style = ($type_account == "vip") ? 'style="border: 2px solid gold;"' : '';

            echo "<button class='btn-user' $border_style><img src='$avatar_link' alt='ảnh avt' /></button>";
          }
        }
        ?>

      </div>
    </div>
  </div>
</body>
<script>
  const avtArea = document.querySelector('.btn-user');
  const btnLogout = document.querySelector('.log_out');
  avtArea.addEventListener('click', function () {
    btnLogout.classList.toggle("show");
  })
</script>

</html>