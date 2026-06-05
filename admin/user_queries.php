<?php
  require('inc/essentials.php');
  require('inc/db_config.php');
  require('inc/PHPMailer/src/Exception.php');
  require('inc/PHPMailer/src/PHPMailer.php');
  require('inc/PHPMailer/src/SMTP.php');

  function send_admin_notification_email($to, $subject, $html_body) {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'cuongct989@gmail.com';
      $mail->Password = 'gbgo inxh nerp ljsb';
      $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;
      $mail->CharSet = 'UTF-8';

      $mail->setFrom('cuongct989@gmail.com', 'VietChill Hotel');
      $mail->addReplyTo('cuongct989@gmail.com', 'VietChill Hotel');
      $mail->addAddress($to);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $html_body;

      $mail->send();
      return true;
    } catch (\PHPMailer\PHPMailer\Exception $e) {
      return false;
    }
  }

  adminLogin();

  // UC23: Xem danh sách liên hệ
  // UC24: Phản hồi liên hệ
  // UC25: Xóa liên hệ

  // Mark as read
  if(isset($_GET['seen'])){
    $frm_data = filteration($_GET);
    if($frm_data['seen']=='all'){
      if(update("UPDATE `user_queries` SET `seen`=?",[1],'i')){ alert('success','Đã xem tất cả!'); }
      else{ alert('error','Thao tác thất bại!'); }
    } else {
      $contact_row = mysqli_fetch_assoc(select("SELECT * FROM `user_queries` WHERE `sr_no`=? LIMIT 1", [$frm_data['seen']], 'i'));
      if(update("UPDATE `user_queries` SET `seen`=? WHERE `sr_no`=?",[1,$frm_data['seen']],'ii')){
        if($contact_row && empty($contact_row['admin_reply'])){
          $mail_subject = 'VietChill: Tin nhắn liên hệ của bạn đã được quản trị viên xem';
          $mail_body = "<p>Xin chào <strong>" . htmlspecialchars($contact_row['name']) . "</strong>,</p>";
          $mail_body .= "<p>Quản trị viên đã xem tin nhắn liên hệ của bạn và sẽ phản hồi sớm nhất có thể.</p>";
          $mail_body .= "<p><strong>Chủ đề:</strong> " . htmlspecialchars($contact_row['subject']) . "</p>";
          $mail_body .= "<p><strong>Nội dung:</strong><br>" . nl2br(htmlspecialchars($contact_row['message'])) . "</p>";
          $mail_body .= "<p>Cảm ơn bạn đã liên hệ với VietChill.</p>";
          send_admin_notification_email($contact_row['email'], $mail_subject, $mail_body);
        }
        alert('success','Đã xem!');
      }
      else{ alert('error','Thao tác thất bại!'); }
    }
  }

  // UC25: Xóa liên hệ
  if(isset($_GET['del'])){
    $frm_data = filteration($_GET);
    if($frm_data['del']=='all'){
      if(mysqli_query($GLOBALS['con'],"DELETE FROM `user_queries`")){ alert('success','Đã xoá tất cả!'); }
      else{ alert('error','Thao tác thất bại!'); }
    } else {
      if(delete("DELETE FROM `user_queries` WHERE `sr_no`=?",[$frm_data['del']],'i')){ alert('success','Đã xoá!'); }
      else{ alert('error','Thao tác thất bại!'); }
    }
  }

  // UC24: Phản hồi liên hệ
  if(isset($_POST['reply_contact'])){
    $frm_data = filteration($_POST);
    if(empty($frm_data['admin_reply'])){
      alert('error','Vui lòng nhập nội dung phản hồi!');
    } else {
      $contact_row = mysqli_fetch_assoc(select("SELECT * FROM `user_queries` WHERE `sr_no`=? LIMIT 1", [$frm_data['contact_id']], 'i'));
      $q = "UPDATE `user_queries` SET `admin_reply`=?, `seen`=1 WHERE `sr_no`=?";
      if(update($q,[$frm_data['admin_reply'],$frm_data['contact_id']],'si')){
        if($contact_row){
          $mail_subject = 'VietChill: Phản hồi yêu cầu liên hệ của bạn';
          $mail_body = "<p>Xin chào <strong>" . htmlspecialchars($contact_row['name']) . "</strong>,</p>";
          $mail_body .= "<p>Quản trị viên đã phản hồi yêu cầu của bạn:</p>";
          $mail_body .= "<p><strong>Chủ đề:</strong> " . htmlspecialchars($contact_row['subject']) . "</p>";
          $mail_body .= "<p><strong>Nội dung phản hồi:</strong><br>" . nl2br(htmlspecialchars($frm_data['admin_reply'])) . "</p>";
          $mail_body .= "<p>Nếu bạn cần thêm hỗ trợ, vui lòng trả lời lại email này.</p>";
          send_admin_notification_email($contact_row['email'], $mail_subject, $mail_body);
        }
        alert('success','Phản hồi liên hệ thành công!');
      } else {
        alert('error','Phản hồi thất bại!');
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang quản lý - Liên hệ</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">Quản lý Liên hệ</h3>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="text-end mb-4">
              <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                <i class="bi bi-check-all"></i> Đã xem tất cả
              </a>
              <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xoá tất cả liên hệ?')">
                <i class="bi bi-trash"></i> Xoá tất cả
              </a>
            </div>

            <div class="table-responsive-md" style="height: 550px; overflow-y: scroll;">
              <table class="table table-hover border">
                <thead class="sticky-top">
                  <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col" width="15%">Chủ đề</th>
                    <th scope="col" width="20%">Tin nhắn</th>
                    <th scope="col" width="15%">Phản hồi</th>
                    <th scope="col">Ngày</th>
                    <th scope="col">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $q = "SELECT * FROM `user_queries` ORDER BY `sr_no` DESC";
                    $data = mysqli_query($GLOBALS['con'],$q);
                    $i=1;

                    while($row = mysqli_fetch_assoc($data)){
                      $date = date('d-m-Y',strtotime($row['datentime']));
                      
                      $reply_display = '';
                      if(!empty($row['admin_reply'])){
                        $reply_display = "<span class='text-success'><i class='bi bi-check-circle'></i> " . htmlspecialchars($row['admin_reply']) . "</span>";
                      } else {
                        $reply_display = "<span class='text-muted'>Chưa phản hồi</span>";
                      }

                      $actions = '';
                      if($row['seen']!=1){
                        $actions .= "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary mb-1'>Đã xem</a><br>";
                      }
                      $actions .= "<button class='btn btn-sm rounded-pill btn-success mb-1' data-bs-toggle='modal' data-bs-target='#replyContactModal' onclick=\"setContactReplyData($row[sr_no], '".addslashes($row['message'])."')\"><i class='bi bi-reply'></i> Phản hồi</button><br>";
                      $actions .= "<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger' onclick=\"return confirm('Xác nhận xóa liên hệ này?')\"><i class='bi bi-trash'></i> Xóa</a>";

                      echo<<<query
                        <tr>
                          <td>$i</td>
                          <td>$row[name]</td>
                          <td>$row[email]</td>
                          <td>$row[subject]</td>
                          <td>$row[message]</td>
                          <td>$reply_display</td>
                          <td>$date</td>
                          <td>$actions</td>
                        </tr>
                      query;
                      $i++;
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- UC24: Modal Phản hồi liên hệ -->
  <div class="modal fade" id="replyContactModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-reply"></i> Phản hồi liên hệ</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-bold">Nội dung tin nhắn:</label>
              <p id="contact_message" class="text-muted"></p>
            </div>
            <input type="hidden" name="contact_id" id="reply_contact_id">
            <div class="mb-3">
              <label class="form-label fw-bold">Phản hồi của quản trị viên:</label>
              <textarea name="admin_reply" class="form-control shadow-none" rows="3" required placeholder="Nhập nội dung phản hồi..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" name="reply_contact" class="btn btn-success">Gửi phản hồi</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php require('inc/scripts.php'); ?>
  <script>
    function setContactReplyData(id, message) {
      document.getElementById('reply_contact_id').value = id;
      document.getElementById('contact_message').textContent = message;
    }
  </script>
</body>
</html>