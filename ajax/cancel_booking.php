<?php
// UC8: Hủy đặt phòng (User)
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

session_start();

if(isset($_POST['cancel_booking'])){
    $data = filteration($_POST);

    // Check if user owns this booking  
    $check_q = "SELECT * FROM `booking_order` WHERE `booking_id`=? AND `user_id`=? LIMIT 1";
    $check_r = select($check_q, [$data['booking_id'], $_SESSION['uId']], 'ii');
    
    if(mysqli_num_rows($check_r) == 0){
        echo 'not_found';
        exit;
    }

    $booking = mysqli_fetch_assoc($check_r);

    // Check if booking can be cancelled (not already cancelled, check_in date not passed)
    if($booking['booking_status'] == 'cancelled'){
        echo 'already_cancelled';
        exit;
    }

    if(strtotime($booking['check_in']) < time()){
        echo 'expired';
        exit;
    }

    // Update booking status
    $query = "UPDATE `booking_order` SET `booking_status`='cancelled', `refund`=1 WHERE `booking_id`=?";
    $res = update($query, [$data['booking_id']], 'i');

    if($res > 0){
        echo 1;
    } else {
        echo 0;
    }
    exit;
}
?>