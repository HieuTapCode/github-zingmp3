<?php
session_start();
$id_user = $_SESSION["id_user"];
require_once "../../../Config/Cloudinary/configCloudinary.php";
require_once "../../../Config/configConnectDB.php";


echo "<pre>";
var_dump($_POST);

// tạo truy vấn INSERT để lưu trữ ảnh trong cơ sở dữ liệu

if (count($_POST) > 0) {
    $user_name = $_POST['user_name'];
    $account_name = $_POST['account_name'];
    $password = $_POST['password'];
    $sql_info = $pdo->prepare("UPDATE user SET account_name = '$account_name', user_name = '$user_name', password = '$password' WHERE id_user = '$id_user';");
    $sql_info->execute();
    if (count($_FILES) > 0) {
        // lấy thông tin về tệp ảnh
        $file_name = $user_name.$account_name;
        $file_size = $_FILES["avatar_link"]["size"];
        $file_tmp = $_FILES["avatar_link"]["tmp_name"];
        $file_type = $_FILES["avatar_link"]["type"];

        $avatar_link_cloud =  getLinkMedia($cloudinary, $file_tmp, "avatar_link", $file_name);
        $sql_avt = $pdo->prepare("UPDATE user SET avatar_link = '$avatar_link_cloud' WHERE id_user = '$id_user';");
        $sql_avt->execute();
        echo "Thành công";
    } else {
        echo "Thất bại";
    }
    header("Location:"."./InfoUser.php");
} else {
    echo "Lỗi dữ liệu bất định";
}
