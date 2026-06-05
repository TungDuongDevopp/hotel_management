<?php
require('inc/vnpay_config.php');

if(isset($_POST['redirect'])){
    $vnp_TxnRef = $_POST['booking_id'] . '_' . date("His"); // Mã đơn hàng + giờ để tránh trùng khi test
    $vnp_OrderInfo = "Thanh toan hoa don phong: " . $_POST['booking_id'];
    $vnp_OrderType = "billpayment";
    $vnp_Amount = $_POST['total_pay'] * 100; // VNPAY yêu cầu nhân 100
    $vnp_Locale = "vn";
    $vnp_BankCode = "NCB"; // Sử dụng ngân hàng NCB để test như email hướng dẫn
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef
    );

    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }
    header('Location: ' . $vnp_Url);
    die();
}
?>