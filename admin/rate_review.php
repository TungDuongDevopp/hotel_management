<?php
  require('inc/essentials.php');
  require('inc/db_config.php');
  adminLogin();

  // UC20: Xem danh sách đánh giá
  // UC21: Phản hồi đánh giá
  // UC22: Xóa đánh giá

  // Mark as read
  if(isset($_GET['seen'])){
    $frm_data = filteration($_GET);
    if($frm_data['seen']=='all'){
      $q = "UPDATE `rating_review` SET `seen`=?";
      if(update($q,[1],'i')){ alert('success','Đã xem tất cả đánh giá!'); }
      else{ alert('error','Thao tác thất bại!'); }
    } else {
      $q = "UPDATE `rating_review` SET `seen`=? WHERE `sr_no`=?";
      if(update($q,[1,$frm_data['seen']],'ii')){ alert('success','Đã xem đánh giá!'); }
      else{ alert('error','Thao tác thất bại!'); }
    }
  }

  // UC22: Xóa đánh giá
  if(isset($_GET['del'])){
    $frm_data = filteration($_GET);
    if($frm_data['del']=='all'){
      if(mysqli_query($con,"DELETE FROM `rating_review`")){ alert('success','Đã xoá tất cả đánh giá!'); }
      else{ alert('error','Thao tác thất bại!'); }
    } else {
      if(delete("DELETE FROM `rating_review` WHERE `sr_no`=?",[$frm_data['del']],'i')){ alert('success','Đã xoá đánh giá!'); }
      else{ alert('error','Thao tác thất bại!'); }
    }
  }

  // UC21: Phản hồi đánh giá
  if(isset($_POST['reply_review'])){
    $frm_data = filteration($_POST);
    if(empty($frm_data['admin_reply'])){
      alert('error','Vui lòng nhập nội dung phản hồi!');
    } else {
      $q = "UPDATE `rating_review` SET `admin_reply`=?, `seen`=1 WHERE `sr_no`=?";
      if(update($q,[$frm_data['admin_reply'],$frm_data['review_id']],'si')){
        alert('success','Phản hồi đánh giá thành công!');
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
  <title>Trang quản lý - Đánh giá</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">Quản lý Đánh giá</h3>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="text-end mb-4">
              <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                <i class="bi bi-check-all"></i> Đã xem tất cả
              </a>
              <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xoá tất cả đánh giá?')">
                <i class="bi bi-trash"></i> Xoá tất cả
              </a>
            </div>

            <div class="table-responsive-md" style="height: 550px; overflow-y: scroll;">
              <table class="table table-hover border">
                <thead class="sticky-top">
                  <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">Tên phòng</th>
                    <th scope="col">Người đánh giá</th>
                    <th scope="col">Đánh giá</th>
                    <th scope="col" width="25%">Nội dung</th>
                    <th scope="col">Phản hồi</th>
                    <th scope="col">Ngày</th>
                    <th scope="col">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $q = "SELECT rr.*,uc.name AS uname, r.name AS rname FROM `rating_review` rr
                      INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                      INNER JOIN `rooms` r ON rr.room_id = r.id
                      ORDER BY `sr_no` DESC";
                    $data = mysqli_query($con,$q);
                    $i=1;

                    while($row = mysqli_fetch_assoc($data)){
                      $date = date('d-m-Y',strtotime($row['datentime']));
                      $stars = str_repeat('⭐', $row['rating']);
                      
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
                      $actions .= "<button class='btn btn-sm rounded-pill btn-success mb-1' data-bs-toggle='modal' data-bs-target='#replyModal' onclick=\"setReplyData($row[sr_no], '".addslashes($row['review'])."')\"><i class='bi bi-reply'></i> Phản hồi</button><br>";
                      $actions .= "<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger' onclick=\"return confirm('Xác nhận xóa đánh giá này?')\"><i class='bi bi-trash'></i> Xóa</a>";

                      echo<<<query
                        <tr>
                          <td>$i</td>
                          <td>$row[rname]</td>
                          <td>$row[uname]</td>
                          <td>$stars</td>
                          <td>$row[review]</td>
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

  <!-- UC21: Modal Phản hồi đánh giá -->
  <div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-reply"></i> Phản hồi đánh giá</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-bold">Nội dung đánh giá:</label>
              <p id="review_content" class="text-muted"></p>
            </div>
            <input type="hidden" name="review_id" id="reply_review_id">
            <div class="mb-3">
              <label class="form-label fw-bold">Phản hồi của quản trị viên:</label>
              <textarea name="admin_reply" class="form-control shadow-none" rows="3" required placeholder="Nhập nội dung phản hồi..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" name="reply_review" class="btn btn-success">Gửi phản hồi</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php require('inc/scripts.php'); ?>
  <script>
    function setReplyData(id, review) {
      document.getElementById('reply_review_id').value = id;
      document.getElementById('review_content').textContent = review;
    }
  </script>
</body>
</html>