<?php 
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Thay 3 dòng cũ bằng 3 dòng này:
require('../admin/inc/PHPMailer/src/Exception.php');
require('../admin/inc/PHPMailer/src/PHPMailer.php');
require('../admin/inc/PHPMailer/src/SMTP.php');

// Viết hàm gửi email
function send_new_password_email($email, $new_pass) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'cuongct989@gmail.com'; 
        $mail->Password   = 'gbgo inxh nerp ljsb'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587; 

        $mail->setFrom('cuongct989@gmail.com', 'He Thong Dat Phong');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Cap lai mat khau moi';
        
        // Gửi thẳng mật khẩu vào nội dung mail
        $mail->Body = "Mật khẩu mới của bạn là: <b>$new_pass</b> <br> Vui lòng đăng nhập và đổi lại mật khẩu để đảm bảo an toàn.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
// UC1: Đăng ký
if(isset($_POST['register'])) {
    $data = filteration($_POST);

    // Validate required fields
    if(empty($data['name']) || empty($data['email']) || empty($data['phonenum']) || empty($data['pass']) || empty($data['cpass'])){
        echo 'missing_fields';
        exit;
    }

    // Match password and confirm password
    if($data['pass'] != $data['cpass']) {
        echo 'pass_mismatch';
        exit;
    }

    // Check if user already exists
    $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1", [$data['email'], $data['phonenum']], "ss");
    if(mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
        exit;
    }

    // Upload profile image if provided
    $img = 'chill-guy.png';
    if(isset($_FILES['profile']) && $_FILES['profile']['size'] > 0){
        $img_result = uploadUserImage($_FILES['profile']);
        if($img_result == 'inv_img'){
            echo 'inv_img';
            exit;
        }
        else if($img_result == 'upd_failed'){
            echo 'upd_failed';
            exit;
        }
        $img = $img_result;
    }

    // Insert user information
    $query = "INSERT INTO `user_cred` (`name`, `email`, `phonenum`, `address`, `pincode`, `dob`, `password`, `profile`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $values = [$data['name'], $data['email'], $data['phonenum'], $data['address'], $data['pincode'], $data['dob'], $data['pass'], $img];
    if(insert($query, $values, 'ssssssss')) {
        echo 'registration_success';
    } else {
        echo 'ins_failed';
    }
    exit;
}

// UC2: Đăng nhập
if(isset($_POST['login'])) {
    $data = filteration($_POST);

    if(empty($data['email_mob']) || empty($data['pass'])){
        echo 'missing_fields';
        exit;
    }

    $query = "SELECT * FROM `user_cred` WHERE (`email` = ? OR `phonenum` = ?) AND `status` = 1 LIMIT 1";
    $values = [$data['email_mob'], $data['email_mob']];
    $res = select($query, $values, "ss");

    if(mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        if($data['pass'] == $row['password']) {
            session_start();
            $_SESSION['login'] = true;
            $_SESSION['uId'] = $row['id'];
            $_SESSION['uName'] = $row['name'];
            $_SESSION['uPic'] = $row['profile'];
            $_SESSION['uEmail'] = $row['email'];
            echo 'login_success';
        } else {
            echo 'invalid_password';
        }
    } else {
        echo 'invalid_email_mob';
    }
    exit;
}

// UC3: Quên mật khẩu
if(isset($_POST['forgot_pass'])) {
    $data = filteration($_POST);

    if(empty($data['email'])){
        echo 'missing_fields';
        exit;
    }

    $query = "SELECT * FROM `user_cred` WHERE `email` = ? LIMIT 1";
    $res = select($query, [$data['email']], "s");

    if(mysqli_num_rows($res) == 0) {
        echo 'inv_email';
        exit;
    }

    $row = mysqli_fetch_assoc($res);
    
    if($row['status'] == 0) {
        echo 'inactive';
        exit;
    }

    // 1. Tự động tạo mật khẩu mới (8 ký tự ngẫu nhiên)
    $new_pass = substr(md5(time()), 0, 8); 
    
    // 2. Cập nhật thẳng mật khẩu mới này vào database (Cột password)
    $update_q = "UPDATE `user_cred` SET `password` = ? WHERE `email` = ?";
    $update_res = update($update_q, [$new_pass, $data['email']], "ss");
    
    if($update_res > 0) {
        // 3. Gọi ĐÚNG tên hàm gửi email ở trên, truyền mật khẩu mới vào
        if(send_new_password_email($data['email'], $new_pass)) {
            echo 'mail_sent'; 
        } else {
            echo 'mail_failed';
        }
    } else {
        echo 'upd_failed';
    }
    exit;
}
?>
?>