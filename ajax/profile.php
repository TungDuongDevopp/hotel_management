<?php
// UC12: Xem thông tin cá nhân
// UC13: Đổi thông tin cá nhân
// UC14: Đổi mật khẩu
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

session_start();

// UC13: Đổi thông tin cá nhân
if(isset($_POST['info_update'])){
    $data = filteration($_POST);

    if(empty($data['name']) || empty($data['email']) || empty($data['phonenum'])){
        echo 'missing_fields';
        exit;
    }

    // Check if email is already used by another user
    $check_q = "SELECT * FROM `user_cred` WHERE `email` = ? AND `id` != ? LIMIT 1";
    $check_r = select($check_q, [$data['email'], $_SESSION['uId']], "si");
    if(mysqli_num_rows($check_r) > 0){
        echo 'email_already';
        exit;
    }

    $query = "UPDATE `user_cred` SET `name`=?, `email`=?, `phonenum`=?, `address`=?, `pincode`=?, `dob`=? WHERE `id`=?";
    $values = [$data['name'], $data['email'], $data['phonenum'], $data['address'], $data['pincode'], $data['dob'], $_SESSION['uId']];
    $res = update($query, $values, 'ssssssi');

    if($res > 0){
        $_SESSION['uName'] = $data['name'];
        echo 1;
    } else {
        echo 0;
    }
    exit;
}

// UC14: Đổi mật khẩu
if(isset($_POST['pass_update'])){
    $data = filteration($_POST);

    if(empty($data['old_pass']) || empty($data['new_pass']) || empty($data['confirm_pass'])){
        echo 'missing_fields';
        exit;
    }

    if($data['new_pass'] != $data['confirm_pass']){
        echo 'pass_mismatch';
        exit;
    }

    // Verify old password
    $verify_q = "SELECT `password` FROM `user_cred` WHERE `id`=? LIMIT 1";
    $verify_r = select($verify_q, [$_SESSION['uId']], 'i');
    $verify_row = mysqli_fetch_assoc($verify_r);

    if($verify_row['password'] != $data['old_pass']){
        echo 'wrong_old_pass';
        exit;
    }

    $query = "UPDATE `user_cred` SET `password`=? WHERE `id`=?";
    $res = update($query, [$data['new_pass'], $_SESSION['uId']], 'si');

    if($res > 0){
        echo 1;
    } else {
        echo 0;
    }
    exit;
}

// Update profile image
if(isset($_POST['profile_update'])){
    if(!isset($_FILES['profile']) || $_FILES['profile']['size'] == 0){
        echo 'no_image';
        exit;
    }

    $img = uploadUserImage($_FILES['profile']);
    if($img == 'inv_img' || $img == 'upd_failed'){
        echo $img;
        exit;
    }

    $query = "UPDATE `user_cred` SET `profile`=? WHERE `id`=?";
    $res = update($query, [$img, $_SESSION['uId']], 'si');

    if($res > 0){
        $_SESSION['uPic'] = $img;
        echo 1;
    } else {
        echo 0;
    }
    exit;
}
?>