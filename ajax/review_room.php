<?php
// UC9: Đánh giá phòng
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

session_start();

if(isset($_POST['review_room'])){
    $data = filteration($_POST);

    if(empty($data['rating']) || empty($data['review'])){
        echo 'missing_fields';
        exit;
    }

    // Check if user already reviewed this booking
    $check_q = "SELECT * FROM `rating_review` WHERE `booking_id`=? AND `user_id`=? LIMIT 1";
    $check_r = select($check_q, [$data['booking_id'], $_SESSION['uId']], 'ii');
    if(mysqli_num_rows($check_r) > 0){
        echo 'already_reviewed';
        exit;
    }

    // Insert review
    $query = "INSERT INTO `rating_review` (`booking_id`, `room_id`, `user_id`, `rating`, `review`) VALUES (?, ?, ?, ?, ?)";
    $values = [$data['booking_id'], $data['room_id'], $_SESSION['uId'], $data['rating'], $data['review']];
    $res = insert($query, $values, 'iiiis');

    // Update booking to mark as reviewed
    if($res > 0){
        $upd_q = "UPDATE `booking_order` SET `rate_review`=1 WHERE `booking_id`=?";
        update($upd_q, [$data['booking_id']], 'i');
        echo 1;
    } else {
        echo 0;
    }
    exit;
}
?>