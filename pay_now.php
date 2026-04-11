<?php 
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');

  session_start();

  if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
  }

  // Validate that the logged-in user still exists in the database
  $user_check = select("SELECT `id` FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], "i");
  if(mysqli_num_rows($user_check) == 0){
    // User no longer exists (e.g. database was re-imported) - clear stale session
    session_unset();
    session_destroy();
    redirect('index.php');
  }

  if(isset($_POST['pay_now']))
  {
    $ORDER_ID = 'ORD_'.$_SESSION['uId'].random_int(11111,9999999);    
    $CUST_ID = (int)$_SESSION['uId'];
    $TXN_AMOUNT = $_SESSION['room']['payment'];
    $frm_data = filteration($_POST);

    // Insert booking with status='booked' directly (no pending)
    $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`, `order_id`, `booking_status`) VALUES (?,?,?,?,?,?)";
    insert($query1,[$CUST_ID,$_SESSION['room']['id'],$frm_data['checkin'],
      $frm_data['checkout'],$ORDER_ID,'booked'],'isssss');
    
    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`,
      `user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";
    insert($query2,[$booking_id,$_SESSION['room']['name'],$_SESSION['room']['price'],
      $TXN_AMOUNT,$frm_data['name'],$frm_data['phonenum'],$frm_data['address']],'issssss');
  }

  redirect('bookings.php');
?>