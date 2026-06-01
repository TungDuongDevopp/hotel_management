<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Thông tin từ email VNPAY của bạn
$vnp_TmnCode = "Q2V53C6L";
$vnp_HashSecret = "YI1FOATQMAD8A1RBYN8T0SWJ8JQ5JHTJ";
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

// URL trang nhận kết quả trả về sau khi thanh toán xong
// LƯU Ý: Thay 'ten_du_an_cua_ban' bằng tên thư mục gốc chứa project trên localhost của bạn
$vnp_Returnurl = "http://localhost/hotel_booking/vnpay_return.php";
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
