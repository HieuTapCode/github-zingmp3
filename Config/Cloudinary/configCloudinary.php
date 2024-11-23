<?php
require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([
  'cloud' => [
    'cloud_name' => 'dnhps1jbn',
    'api_key' => '649256297695521',
    'api_secret' => 'SASuY-4crMkOU0GBxzKxNYC9EQo'
  ],
  'url' => [
    'secure' => true
  ]
]);


function getLinkMedia($cloudinary, $link, $folder, $nameFile)
{

  $response = $cloudinary->uploadApi()->upload(
    $link,
    [
      "public_id" => $nameFile, // Đặt public_id của tệp âm thanh
      "resource_type" => "auto", // Xác định loại tài nguyên (auto tự động phát hiện)
      "folder" => $folder, // Đặt thư mục lưu trữ
      "overwrite" => true // Ghi đè lên tệp nếu đã tồn tại
    ]
  );

  return $response["secure_url"];
}

?>


<!-- #         echo getLinkMedia($cloudinary, "../../Component/assets/y2mate.com - Grow Up  Guhancci Remix  Exclusive Team  Nhạc Nền Gậy Nghiện Hot Tik Tok Việt Nam .mp3", "song1", "audio"); -->