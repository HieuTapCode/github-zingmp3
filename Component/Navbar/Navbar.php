<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ZingMP3/Component/Navbar/Navbar.css">
</head>

<body>
    <div id="navbar-main">
        <div class="logo-main">
            <img id="myImage" src="/ZingMP3/Component/assets/Logo.png" alt="">
        </div>
        <?php
        // Lấy đường dẫn hiện tại
        $currentUrl = $_SERVER['REQUEST_URI'];
        // Tách thành mảng bằng dấu / để lấy phần tên pages
        $urlParts = explode('/', $currentUrl)[3];
        ?>

        <ul class="list-menu">
            <a href=<?php echo (isset($_SESSION["id_user"]) ? "/ZingMP3/Pages/Profile/MyHeart/MyHeart.php" :  "/ZingMP3/Pages/Login/Login.php") ?>>
                <li class=<?php echo $urlParts === "Profile" ? 'active' : '' ?>><i class="fa-solid fa-book-open-reader"></i>
                    <p>Cá nhân</p>
                </li>
            </a>
            <a href="/ZingMP3/Pages/Home/HomeLayOut.php">
                <li class=<?php echo $urlParts === "Home" ? 'active' : '' ?>><i class="fa-solid fa-compact-disc"></i></i>
                    <p>Khám phá</p>
                </li>
            </a>
            <a href="/ZingMP3/Pages/NewSongs/NewSongs.php">
                <li class=<?php echo $urlParts === "NewSongs" ? 'active' : '' ?>><i class="fa-solid fa-music"></i>
                    <p>Nhạc mới</p>
                </li>
            </a>
            <a href="/ZingMP3/Pages/TopSong/TopSong.php">
                <li class=<?php echo $urlParts === "TopSong" ? 'active' : '' ?>><i class="fa-solid fa-ranking-star"></i>
                    <p>BXH</p>
                </li>
            </a>
        </ul>

        <?php
        if (!isset($_SESSION["id_user"])) {
        ?>
            <div class="login-vip--container">
                <div class="login-update--vip">
                    <p>Đăng nhập để khám phá playlist dành riêng cho bạn</p>
                    <a href="/ZingMP3/Pages/Login/Login.php"><button>Đăng Nhập</button></a>
                    <i class="fa-solid fa-crown"></i>
                </div>
            </div>

        <?php } ?>
    </div>



</body>

</html>