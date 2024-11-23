<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN SLICK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

    <link rel="stylesheet" href="/ZingMP3/Component/Slider/Slider.css">
</head>

<body>
    <div class="slider-container">
        <div class="slider">
            <?php
            $sql_album = $pdo->prepare("SELECT * FROM album ORDER BY album_id DESC LIMIT 5");
            $sql_album->execute();
            $list_album_suggest = $sql_album->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($list_album_suggest); $i++) {
            ?>
                <div class="slider-item" title="<?php echo $list_album_suggest[$i]['title_album']?>">
                    <a href="/ZingMP3/Pages/ListSongPages/ListSongPages.php?album_id=<?php echo $list_album_suggest[$i]['album_id']?>">
                        <img src="<?php echo $list_album_suggest[$i]['thumbnail_album']?>" alt="">
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        // Kích hoạt Slick Slider
        $(document).ready(function() {
            $('.slider').slick({
                autoplay: true,
                autoplaySpeed: 1000,
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                            infinite: true,
                            autoplay: true,
                            autoplaySpeed: 1000,
                            infinite: true,
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            autoplay: true,
                            autoplaySpeed: 1000,
                            infinite: true,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            autoplay: true,
                            autoplaySpeed: 1000,
                            infinite: true,
                        }
                    }
                ]
            });
        });
    </script>


</body>

</html>