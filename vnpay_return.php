<?php
  require('inc/links.php');
  require('inc/vnpay_config.php');

  $vnp_SecureHash = $_GET['vnp_SecureHash'];
  $inputData = array();
  foreach ($_GET as $key => $value) {
      if (substr($key, 0, 4) == "vnp_") {
          $inputData[$key] = $value;
      }
  }
  
  unset($inputData['vnp_SecureHash']);
  ksort($inputData);
  $hashData = "";
  $i = 0;
  foreach ($inputData as $key => $value) {
      if ($i == 1) {
          $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
      } else {
          $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
          $i = 1;
      }
  }

  $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

  if ($secureHash == $vnp_SecureHash) {
      if ($_GET['vnp_ResponseCode'] == '00') {
          echo "<script>alert('Thanh toán thành công!'); window.location.href='bookings.php';</script>";
      } else {
          echo "<script>alert('Thanh toán không thành công hoặc đã bị hủy.'); window.location.href='bookings.php';</script>";
      }
  } else {
      echo "Chữ ký không hợp lệ!";
  }
?>