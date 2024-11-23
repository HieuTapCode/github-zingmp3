<?php

require_once("../../Config/configConnectDB.php");


if (isset($_POST['submit'])) {
    $inputs = array(
        'name_account' => $_POST['name_account'],
        'name_user' => $_POST['name_user'],
        'password_hash' => $_POST['password_hash'],
        'confirm_password' => $_POST['confirm_password'],
    );

    if (count(array_filter($inputs)) !== count($inputs)) {
        $message[] = 'Please enter your full details!';
    } else {
        if ($inputs["password_hash"] != $inputs['confirm_password']) {
            $message[] = "Passwords don't match!";
        } else {
            $checkUserAccount = $pdo->prepare("SELECT * FROM user where account_name = :name_account");
            $checkUserAccount->bindParam(':name_account', $inputs['name_account'], PDO::PARAM_STR);
            $checkUserAccount->execute();

            $checkResult = $checkUserAccount->fetch(PDO::FETCH_ASSOC);
            if (!$checkResult) {
                $sql = $pdo->prepare("INSERT INTO user (user_name, account_name, password) VALUES (:name_user, :name_account, :password_hash);");
                $sql->bindParam(':name_user', $inputs['name_user'], PDO::PARAM_STR);
                $sql->bindParam(':name_account', $inputs['name_account'], PDO::PARAM_STR);
                $sql->bindParam(':password_hash', $inputs['password_hash'], PDO::PARAM_STR);
                $sql->execute();
                header('Location: ../Login/Login.php');
            } else {
                $message[] = 'The account was in existence!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon  -->
    <link rel="shortcut icon" href="../../Component/assets/logo_mobile.png" type="image/x-icon">
    <title>Đăng ký tài khoản - Nhóm Phát Triển Ứng Dụng Web</title>
    <link rel="stylesheet" href="./Signup.css">
    <link rel="stylesheet" href="../../GlobalStyle/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>


<body>
    <div class="signup-container">
        <form method="post">
            <img src="../../Component/assets/Logo.png" alt="">
            <a href="/ZingMP3/Pages/Home/HomeLayOut.php" class="icon-home"><i class="fa-solid fa-house" style="color: #eee;"></i></a>

            <?php
            if (isset($message)) {
                foreach ($message as $singleMessage) {
                    echo '<div class="singleMessage">' . $singleMessage . '</div>';
                }
            }
            ?>

            <label for="AccountName">Account Name</label>
            <input type="text" name="name_account" placeholder="Nickname..." id="AccountName">

            <label for="Fullname">Fullname</label>
            <input type="text" name="name_user" placeholder="Name..." id="Fullname">

            <label for="Password">Password</label>
            <input type="Password" name="password_hash" placeholder="Password..." id="Password">

            <label for="confirm_password">Confirm Password</label>
            <input type="Password" name="confirm_password" placeholder="Confirm..." id="confirm_password">

            <button type="submit" name="submit">Signup Now</button>
            <div class="direct-login">
                <p>Already have an account? <a href="/ZingMP3/Pages/Login/Login.php">Login now</a></p>
            </div>
        </form>
    </div>
</body>

</html>