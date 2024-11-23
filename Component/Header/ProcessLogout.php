<?php
session_start(); // Bắt đầu hoặc kết nối vào phiên làm việc đã tồn tại
session_destroy(); // Xóa tất cả các biến và kết thúc phiên làm việc
header("Location: "."../../../ZingMP3/Pages/Login/Login.php");
?>
