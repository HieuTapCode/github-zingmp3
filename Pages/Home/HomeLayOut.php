<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- favicon  -->
    <link rel="shortcut icon" href="../../Component/assets/logo_mobile.png" type="image/x-icon">
    <title>Khám phá - Nhóm Phát Triển Ứng Dụng Web</title>

    <!-- import css file component  -->
    <link rel="stylesheet" href="../../GlobalStyle/style.css">
    <link rel="stylesheet" href="./HomeLayout.css">

</head>

<body>
    <?php require_once "../../Config/configConnectDB.php" ?>
    <div id="home-main">
        <!-- NavBar  -->
        <?php require '../../Component/Navbar/Navbar.php' ?>
        <div class="home-right">

            <!-- Header  -->
            <?php require "../../Component/Header/HeaderLayout.php" ?>

            <!-- Slider  -->
            <?php require "../../Component/Slider/Slider.php" ?>

            <!-- Dành cho bạn -->
            <?php

            $id_user = $_SESSION["id_user"] ?? NULL;
            if (isset($id_user)) {
                require "../../Component/SongForYou/SongForYou.php";
            }
            ?>
            <!-- Thịnh hành -->
            <?php require "../../Component/SongTrending/SongTrending.php" ?>


            <!-- Nghệ sỹ thịnh hành  -->
            <?php require "../../Component/AuthorTrending/AuthorTrending.php" ?>

            <!-- Album hot  -->
            <?php require "../../Component/AlbumHot/AlbumHot.php" ?>


            <!-- Đối tác  -->
            <?php require "../../Component/CounterParty/CounterParty.php" ?>
        </div>
    </div>
    <!-- PlayingBar -->

</body>

</html>