<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Tạo mới đơn hàng</title>
    <!-- Bootstrap core CSS -->
    <link href="/zingmp3/vnpay_php/assets/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="/zingmp3/vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">
    <script src="/zingmp3/vnpay_php/assets/jquery-1.11.3.min.js"></script>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .header h3 {
            color: #007bff;
            font-weight: 700;
            font-size: 24px;
            margin: 0;
        }

        .form-group {
            margin: 15px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.5);
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header clearfix">

            <h3 class="text-muted">VNPAY DEMO</h3>
        </div>
        <div class="form-group">
            <button onclick="pay()">Giao dịch thanh toán</button><br>
        </div>
        <div class="form-group">
            <button onclick="querydr()">API truy vấn kết quả thanh toán</button><br>
        </div>
        <div class="form-group">
            <button onclick="refund()">API hoàn tiền giao dịch</button><br>
        </div>
        <p>
            &nbsp;
        </p>
        <footer class="footer">
            <p>&copy; VNPAY <?php echo date('Y') ?></p>
        </footer>
    </div>
    <script>
        function pay() {
            window.location.href = "/zingmp3/vnpay_php/vnpay_pay.php";
        }
        function querydr() {
            window.location.href = "/zingmp3/vnpay_php/vnpay_querydr.php";
        }
        function refund() {
            window.location.href = "/zingmp3/vnpay_php/vnpay_refund.php";
        }
    </script>
</body>

</html>