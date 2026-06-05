<?php
ob_start();
require('inc/links.php');
$head_links = ob_get_clean();

if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)){
  redirect('index.php');
}

$notifications = [];

$review_q = "SELECT rr.*, r.name AS room_name FROM `rating_review` rr JOIN `rooms` r ON rr.room_id = r.id WHERE rr.user_id = ? AND rr.seen = ? ORDER BY rr.datentime DESC";
$review_res = select($review_q, [$_SESSION['uId'], 1], 'ii');
if($review_res){
  while($row = mysqli_fetch_assoc($review_res)){
    $review_message = !empty($row['admin_reply'])
      ? $row['admin_reply']
      : 'Quản trị viên đã xem đánh giá của bạn.';
    $review_title = !empty($row['admin_reply'])
      ? "Phản hồi đánh giá phòng {$row['room_name']}"
      : "Đã xem đánh giá phòng {$row['room_name']}";

    $notifications[] = [
      'type' => 'review',
      'title' => $review_title,
      'message' => $review_message,
      'detail' => "Đánh giá: {$row['review']}",
      'date' => $row['datentime'],
    ];
  }
}

$contact_q = "SELECT * FROM `user_queries` WHERE `email` = ? AND `seen` = ? ORDER BY `datentime` DESC";
$contact_res = select($contact_q, [$_SESSION['uEmail'], 1], 'si');
if($contact_res){
  while($row = mysqli_fetch_assoc($contact_res)){
    $contact_message = !empty($row['admin_reply'])
      ? $row['admin_reply']
      : 'Quản trị viên đã xem tin nhắn của bạn.';
    $contact_title = !empty($row['admin_reply'])
      ? "Phản hồi liên hệ: {$row['subject']}"
      : "Đã xem liên hệ: {$row['subject']}";

    $notifications[] = [
      'type' => 'contact',
      'title' => $contact_title,
      'message' => $contact_message,
      'detail' => "Nội dung: {$row['message']}",
      'date' => $row['datentime'],
    ];
  }
}

usort($notifications, function($a, $b){
  return strtotime($b['date']) - strtotime($a['date']);
});
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thông báo - <?php echo $settings_r['site_title'] ?></title>
  <?php echo $head_links; ?>
</head>
<body class="bg-light">
  <?php include('inc/header.php'); ?>

  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h3 class="mb-1">Thông báo</h3>
        <p class="text-muted mb-0">Các phản hồi từ quản trị viên cho đánh giá và yêu cầu liên hệ của bạn.</p>
      </div>
      <a href="profile.php" class="btn btn-outline-dark shadow-none">Quay lại hồ sơ</a>
    </div>

    <?php if(empty($notifications)): ?>
      <div class="card shadow-sm border-0 p-4 text-center">
        <div class="mb-3">
          <i class="bi bi-bell-slash fs-1 text-muted"></i>
        </div>
        <h5 class="mb-2">Bạn chưa có thông báo mới.</h5>
        <p class="text-muted mb-0">Khi quản trị viên trả lời yêu cầu hoặc đánh giá của bạn, bạn sẽ nhận được thông báo tại đây.</p>
      </div>
    <?php else: ?>
      <div class="row gy-3">
        <?php foreach($notifications as $note): ?>
          <div class="col-12">
            <div class="card shadow-sm border-0">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <div>
                    <h5 class="card-title mb-1"><?php echo $note['title'] ?></h5>
                    <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($note['date'])) ?></small>
                  </div>
                  <span class="badge bg-info text-dark text-uppercase"><?php echo $note['type'] === 'review' ? 'Đánh giá' : 'Liên hệ' ?></span>
                </div>
                <p class="mb-2"><strong>Phản hồi:</strong> <?php echo $note['message'] ?></p>
                <p class="mb-0 text-muted"><?php echo $note['detail'] ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php include('inc/footer.php'); ?>
</body>
</html>
