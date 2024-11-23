<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>VNPAY RESPONSE</title>
    <link href="/zingmp3/vnpay_php/assets/bootstrap.min.css" rel="stylesheet" />
    <link href="/zingmp3/vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">
    <script src="/zingmp3/vnpay_php/assets/jquery-1.11.3.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #343a40;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .header {
            margin-bottom: 20px;
        }

        h3.text-muted {
            font-weight: 600;
            color: #007bff;
            text-align: center;
        }

        .table-responsive {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 10px;
        }

        .form-group label:first-child {
            font-weight: bold;
            margin-right: 15px;
            min-width: 150px;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            color: #868e96;
        }

        .footer p {
            margin: 0;
        }

        span {
            font-weight: bold;
        }

        span[style='color:blue'] {
            color: #28a745 !important;
        }

        span[style='color:red'] {
            color: #dc3545 !important;
        }
    </style>
</head>

<body>
    <?php
    require_once("./config.php");
    $vnp_SecureHash = $_GET['vnp_SecureHash'];
    $inputData = array();
    foreach ($_GET as $key => $value) {
        if (substr($key, 0, 4) == "vnp_") {
            $inputData[$key] = $value;
        }
    }

    unset($inputData['vnp_SecureHash']);
    ksort($inputData);
    $i = 0;
    $hashData = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
    }

    $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

    // Lấy id_user từ session
    session_start();
    $id_user = $_SESSION["id_user"];

    require_once "../Config/configConnectDB.php";
    // Thêm bản ghi vào bảng payment
    // Xác thực mã bảo mật
    if ($secureHash == $vnp_SecureHash) {
        // Chuẩn bị câu lệnh SQL
        $stmt = $pdo->prepare("INSERT INTO payment (id_user, txn_ref, amount, order_info, response_code, transaction_no, bank_code, pay_date, status) 
                           VALUES (:id_user, :txn_ref, :amount, :order_info, :response_code, :transaction_no, :bank_code, :pay_date, :status)");

        // Gán giá trị cho các tham số
        $txn_ref = $_GET['vnp_TxnRef'];
        $amount = ($_GET['vnp_Amount']/100);
        $order_info = $_GET['vnp_OrderInfo'];
        $response_code = $_GET['vnp_ResponseCode'];
        $transaction_no = $_GET['vnp_TransactionNo'];
        $bank_code = $_GET['vnp_BankCode'];
        $pay_date = date('Y-m-d H:i:s', strtotime($_GET['vnp_PayDate']));
        $status = ($_GET['vnp_ResponseCode'] == '00') ? 'success' : 'failed';

        // Thực thi câu lệnh
        $stmt->execute([
            ':id_user' => $id_user,
            ':txn_ref' => $txn_ref,
            ':amount' => $amount,
            ':order_info' => $order_info,
            ':response_code' => $response_code,
            ':transaction_no' => $transaction_no,
            ':bank_code' => $bank_code,
            ':pay_date' => $pay_date,
            ':status' => $status
        ]);


    }



    ?>
    <!--Begin display -->
    <div class="container">
        <div class="header clearfix">
            <h3 class="text-muted">VNPAY RESPONSE</h3>
        </div>
        <div class="table-responsive">
            <div class="form-group">
                <label>Mã đơn hàng:</label>
                <label><?php echo $_GET['vnp_TxnRef'] ?></label>
            </div>
            <div class="form-group">
                <label>Số tiền:</label>
                <label><?php echo $amount . " VND" ?></label>
            </div>
            <div class="form-group">
                <label>Nội dung thanh toán:</label>
                <label><?php echo $_GET['vnp_OrderInfo'] ?></label>
            </div>
            <div class="form-group">
                <label>Mã phản hồi (vnp_ResponseCode):</label>
                <label><?php echo $_GET['vnp_ResponseCode'] ?></label>
            </div>
            <div class="form-group">
                <label>Mã GD Tại VNPAY:</label>
                <label><?php echo $_GET['vnp_TransactionNo'] ?></label>
            </div>
            <div class="form-group">
                <label>Mã Ngân hàng:</label>
                <label><?php echo $_GET['vnp_BankCode'] ?></label>
            </div>
            <div class="form-group">
                <label>Thời gian thanh toán:</label>
                <label><?php echo $_GET['vnp_PayDate'] ?></label>
            </div>
            <div class="form-group">
                <label>Kết quả:</label>
                <label>
                    <?php
                    if ($secureHash == $vnp_SecureHash) {
                        if ($_GET['vnp_ResponseCode'] == '00') {
                            $stmtUser = $pdo->prepare("UPDATE user
                            SET type_account = 'vip'
                            WHERE id_user = :id_user;
                            ");
                            $stmtUser->execute([
                                ':id_user' => $id_user,
                            ]);

                            echo "<span style='color:blue'>GD Thanh cong</span>";
                        } else {
                            echo "<span style='color:red'>GD Khong thanh cong</span>";
                        }
                    } else {
                        echo "<span style='color:red'>Chu ky khong hop le</span>";
                    }
                    ?>
                </label>
            </div>
            <a href="/ZingMP3/Pages/Profile/InfoUser/InfoUser.php">
                < Trở về trang chủ</a>
        </div>
        <p>&nbsp;</p>
        <footer class="footer">
            <p>&copy; VNPAY <?php echo date('Y') ?></p>
        </footer>
    </div>
</body>

</html>