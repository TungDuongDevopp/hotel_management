<?php
  require('inc/essentials.php');
  require('inc/db_config.php');
  adminLogin();

  // UC26: Xem danh sách người dùng
  // UC27: Thêm người dùng
  // UC28: Sửa người dùng
  // UC29: Xóa người dùng

  // UC27: Thêm người dùng
  if(isset($_POST['add_user'])){
    $frm_data = filteration($_POST);
    
    if(empty($frm_data['name']) || empty($frm_data['email']) || empty($frm_data['phonenum']) || empty($frm_data['password'])){
      alert('error','Vui lòng nhập đầy đủ thông tin bắt buộc!');
    } else {
      // Check duplicate
      $check = select("SELECT * FROM `user_cred` WHERE `email`=? OR `phonenum`=? LIMIT 1", [$frm_data['email'],$frm_data['phonenum']], 'ss');
      if(mysqli_num_rows($check) > 0){
        alert('error','Email hoặc số điện thoại đã tồn tại!');
      } else {
        $q = "INSERT INTO `user_cred` (`name`,`email`,`phonenum`,`address`,`pincode`,`dob`,`password`,`status`) VALUES (?,?,?,?,?,?,?,1)";
        $vals = [$frm_data['name'],$frm_data['email'],$frm_data['phonenum'],$frm_data['address'],$frm_data['pincode'],$frm_data['dob'],$frm_data['password']];
        if(insert($q,$vals,'sssssss')){
          alert('success','Thêm người dùng thành công!');
        } else {
          alert('error','Thêm người dùng thất bại!');
        }
      }
    }
  }

  // UC28: Sửa người dùng
  if(isset($_POST['edit_user'])){
    $frm_data = filteration($_POST);
    
    if(empty($frm_data['name']) || empty($frm_data['email'])){
      alert('error','Vui lòng nhập đầy đủ thông tin!');
    } else {
      $q = "UPDATE `user_cred` SET `name`=?,`email`=?,`phonenum`=?,`address`=?,`pincode`=?,`dob`=?,`status`=? WHERE `id`=?";
      $vals = [$frm_data['name'],$frm_data['email'],$frm_data['phonenum'],$frm_data['address'],$frm_data['pincode'],$frm_data['dob'],$frm_data['status'],$frm_data['user_id']];
      if(update($q,$vals,'ssssssii')){
        alert('success','Cập nhật người dùng thành công!');
      } else {
        alert('error','Cập nhật thất bại!');
      }
    }
  }

  // UC29: Xóa người dùng
  if(isset($_GET['del_user'])){
    $frm_data = filteration($_GET);
    // Check if user has bookings
    $check_bookings = select("SELECT COUNT(*) AS cnt FROM `booking_order` WHERE `user_id`=?", [$frm_data['del_user']], 'i');
    $cnt = mysqli_fetch_assoc($check_bookings);
    
    if($cnt['cnt'] > 0){
      // Just deactivate instead of deleting
      if(update("UPDATE `user_cred` SET `status`=0 WHERE `id`=?",[$frm_data['del_user']],'i')){
        alert('success','Đã vô hiệu hóa tài khoản người dùng (người dùng có lịch sử đặt phòng)!');
      } else {
        alert('error','Thao tác thất bại!');
      }
    } else {
      if(delete("DELETE FROM `user_cred` WHERE `id`=?",[$frm_data['del_user']],'i')){
        alert('success','Đã xóa người dùng thành công!');
      } else {
        alert('error','Xóa người dùng thất bại!');
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
  <title>Trang quản lý - Người dùng</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h3>Quản lý Người dùng</h3>
          <button class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> Thêm người dùng
          </button>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="table-responsive" style="height: 550px; overflow-y: scroll;">
              <table class="table table-hover border">
                <thead class="sticky-top">
                  <tr class="bg-dark text-light">
                    <th>#</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $q = "SELECT * FROM `user_cred` ORDER BY `id` DESC";
                    $data = mysqli_query($con,$q);
                    $i=1;
                    while($row = mysqli_fetch_assoc($data)){
                      $date = date('d-m-Y',strtotime($row['datentime']));
                      $status_badge = ($row['status']==1) ? "<span class='badge bg-success'>Hoạt động</span>" : "<span class='badge bg-danger'>Ngưng</span>";
                      $row_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                      
                      echo<<<data
                        <tr>
                          <td>$i</td>
                          <td>$row[name]</td>
                          <td>$row[email]</td>
                          <td>$row[phonenum]</td>
                          <td>$row[address]</td>
                          <td>$status_badge</td>
                          <td>$date</td>
                          <td>
                            <button class="btn btn-sm btn-warning rounded-pill mb-1" onclick='editUser($row_json)' data-bs-toggle="modal" data-bs-target="#editUserModal">
                              <i class="bi bi-pencil"></i> Sửa
                            </button>
                            <a href="?del_user=$row[id]" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác.')">
                              <i class="bi bi-trash"></i> Xóa
                            </a>
                          </td>
                        </tr>
                      data;
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

  <!-- UC27: Modal Thêm người dùng -->
  <div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="POST">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-person-plus"></i> Thêm người dùng mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Họ tên *</label>
                <input name="name" type="text" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Email *</label>
                <input name="email" type="email" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Số điện thoại *</label>
                <input name="phonenum" type="text" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Mật khẩu *</label>
                <input name="password" type="text" class="form-control shadow-none" required>
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label">Địa chỉ</label>
                <textarea name="address" class="form-control shadow-none" rows="1"></textarea>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Mã định danh</label>
                <input name="pincode" type="number" class="form-control shadow-none" value="0">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Ngày sinh</label>
                <input name="dob" type="date" class="form-control shadow-none" value="2000-01-01">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" name="add_user" class="btn btn-dark">Lưu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- UC28: Modal Sửa người dùng -->
  <div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="POST">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-pencil"></i> Sửa thông tin người dùng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Họ tên *</label>
                <input name="name" id="edit_name" type="text" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Email *</label>
                <input name="email" id="edit_email" type="email" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Số điện thoại</label>
                <input name="phonenum" id="edit_phonenum" type="text" class="form-control shadow-none">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" id="edit_status" class="form-select shadow-none">
                  <option value="1">Hoạt động</option>
                  <option value="0">Ngưng hoạt động</option>
                </select>
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label">Địa chỉ</label>
                <textarea name="address" id="edit_address" class="form-control shadow-none" rows="1"></textarea>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Mã định danh</label>
                <input name="pincode" id="edit_pincode" type="number" class="form-control shadow-none">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Ngày sinh</label>
                <input name="dob" id="edit_dob" type="date" class="form-control shadow-none">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" name="edit_user" class="btn btn-warning">Lưu thay đổi</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php require('inc/scripts.php'); ?>
  <script>
    function editUser(data) {
      let user = typeof data === 'string' ? JSON.parse(data) : data;
      document.getElementById('edit_user_id').value = user.id;
      document.getElementById('edit_name').value = user.name;
      document.getElementById('edit_email').value = user.email;
      document.getElementById('edit_phonenum').value = user.phonenum;
      document.getElementById('edit_address').value = user.address;
      document.getElementById('edit_pincode').value = user.pincode;
      document.getElementById('edit_dob').value = user.dob;
      document.getElementById('edit_status').value = user.status;
    }
  </script>
</body>
</html>