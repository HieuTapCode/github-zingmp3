<?php
session_start();


require_once("../../Config/configConnectDB.php");

if (isset($_POST['submit'])) {
    if (!empty($_POST['account_name']) && !empty($_POST['password'])) {
        $acc_name = $_POST['account_name'];
        $password = $_POST['password'];
        $statement = $pdo->prepare("SELECT * FROM user where account_name = :account_name AND password = :password");
        $statement->bindValue(':account_name', $acc_name, PDO::PARAM_STR);
        $statement->bindValue(':password', $password, PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $id_user = $user['id_user'];
            $_SESSION['id_user'] = $id_user;
            header('Location: ../Home/HomeLayOut.php');
            exit();
        } else {
            $message[] = 'Please check your information again!';
        }
    } else {
        $message[] = 'Please fill in the information!';
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
    <title>Đăng nhập tài khoản - Nhóm Phát Triển Ứng Dụng Web</title>
    <link rel="stylesheet" href="./Login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../GlobalStyle/style.css">
</head>


<body>
    <div class="login-container">
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
            <input type="text" name="account_name" placeholder="Account Name..." id="AccountName">

            <label for="Password">Password</label>
            <input type="password" name="password" placeholder="Password..." id="Password">

            <button type="submit" name="submit">Login Now</button>
            <div class="direct-signup">
                <p>Don't have an account? <a href="/ZingMP3/Pages/Signup/Signup.php">Signup now</a></p>
            </div>
        </form>
    </div>
</body>

</html>
</body>

</html>