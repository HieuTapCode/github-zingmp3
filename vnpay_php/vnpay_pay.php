<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Tạo mới đơn hàng</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
    <link href="/zingmp3/vnpay_php/assets/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #2c3e50;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: #34495e;
        }

        input[type="number"],
        input[type="radio"],
        button {
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 16px;
            background-color: #ecf0f1;
            transition: all 0.3s ease;
        }

        input[type="number"]:focus,
        button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(41, 128, 185, 0.5);
            border-color: #2980b9;
        }

        input[type="radio"] {
            width: auto;
            margin-right: 10px;
        }

        h5 {
            font-size: 18px;
            color: #2980b9;
            margin-bottom: 10px;
        }

        button {
            background-color: #27ae60;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
        }

        button:hover {
            background-color: #1e8449;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            color: #7f8c8d;
            font-size: 14px;
        }

        .footer p {
            margin: 0;
        }

        .form-group input[type="radio"] {
            display: inline-block;
            width: auto;
            margin-right: 8px;
        }

        .form-group label {
            display: inline-block;
            font-weight: normal;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            button {
                padding: 12px;
            }
        }
    </style>
    <script src="/zingmp3/vnpay_php/assets/jquery-1.11.3.min.js"></script>
</head>
<?php if (isset($_SESSION["id_user"])) {
    $id_user = $_SESSION["id_user"];
} else {
    header('Location:' . '/ZingMP3/Pages/Login/Login.php');
} ?>

<body>
    <?php require_once("./config.php"); ?>
    <div class="container">
        <h3>Điền thông tin thanh toán</h3>
        <div class="table-responsive">
            <form action="/zingmp3/vnpay_php/vnpay_create_payment.php" id="frmCreateOrder" method="post">
                <div class="form-group">
                    <label for="amount">Số tiền</label>
                    <input class="form-control" data-val="true" data-val-number="The field Amount must be a number."
                        data-val-required="The Amount field is required." id="amount" max="100000000" min="1"
                        name="amount" type="number" value="100000" />
                </div>
                <h4>Chọn phương thức thanh toán</h4>
                <div class="form-group">

                    <input type="radio" id="bankCode" name="bankCode" value="VNBANK" checked>
                    <label for="bankCode">Thanh toán qua thẻ ATM/Tài khoản nội địa</label><br>


                </div>
                <div class="form-group">
                    <h5>Chọn ngôn ngữ giao diện thanh toán:</h5>
                    <input type="radio" id="language" Checked="True" name="language" value="vn">
                    <label for="language">Tiếng Việt</label><br>
                    <input type="radio" id="language" name="language" value="en">
                    <label for="language">Tiếng Anh</label><br>
                </div>
                <button type="submit" class="btn btn-primary">Thanh toán</button>
            </form>
        </div>
        <p>&nbsp;</p>
        <footer class="footer">
            <p>&copy; VNPAY 2024</p>
        </footer>
    </div>
</body>



</html>