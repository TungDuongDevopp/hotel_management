<?php
  require('inc/essentials.php');
  require('inc/db_config.php');
  adminLogin();

  // ======== SERVER-SIDE ACTIONS ========

  // Thêm đặt phòng mới (Admin)
  if(isset($_POST['add_booking'])){
    $frm_data = filteration($_POST);
    
    if(empty($frm_data['user_id']) || empty($frm_data['room_id']) || empty($frm_data['check_in']) || empty($frm_data['check_out'])){
      alert('error','Vui lòng nhập đầy đủ thông tin!');
    } else {
      // Validate dates
      $checkin = new DateTime($frm_data['check_in']);
      $checkout = new DateTime($frm_data['check_out']);
      
      if($checkout <= $checkin){
        alert('error','Ngày trả phòng phải sau ngày nhận phòng!');
      } else {
        // Get room info
        $room_r = mysqli_fetch_assoc(select("SELECT * FROM `rooms` WHERE `id`=?",$frm_data['room_id'],'i'));
        // Get user info
        $user_r = mysqli_fetch_assoc(select("SELECT * FROM `user_cred` WHERE `id`=?",$frm_data['user_id'],'i'));
        
        if(!$room_r || !$user_r){
          alert('error','Phòng hoặc người dùng không tồn tại!');
        } else {
          // Check availability
          $tb_q = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order`
            WHERE booking_status='booked' AND room_id=?
            AND check_out > ? AND check_in < ?";
          $tb_fetch = mysqli_fetch_assoc(select($tb_q,[$frm_data['room_id'],$frm_data['check_in'],$frm_data['check_out']],'iss'));
          
          if($tb_fetch['total_bookings'] >= $room_r['quantity']){
            alert('error','Phòng đã hết trong khoảng thời gian này!');
          } else {
            $ORDER_ID = 'ORD_'.random_int(10000000,99999999);
            $days = date_diff($checkin,$checkout)->days;
            $total_pay = $room_r['price'] * $days;
            
            // Insert booking as 'booked' directly
            $q1 = "INSERT INTO `booking_order`(`user_id`,`room_id`,`check_in`,`check_out`,`order_id`,`booking_status`) VALUES (?,?,?,?,?,?)";
            insert($q1,[$frm_data['user_id'],$frm_data['room_id'],$frm_data['check_in'],$frm_data['check_out'],$ORDER_ID,'booked'],'isssss');
            $booking_id = mysqli_insert_id($con);
            
            // Insert booking details
            $room_no = !empty($frm_data['room_no']) ? $frm_data['room_no'] : null;
            $q2 = "INSERT INTO `booking_details`(`booking_id`,`room_name`,`price`,`total_pay`,`room_no`,`user_name`,`phonenum`,`address`) VALUES (?,?,?,?,?,?,?,?)";
            insert($q2,[$booking_id,$room_r['name'],$room_r['price'],$total_pay,$room_no,$user_r['name'],$user_r['phonenum'],$user_r['address']],'isiissss');
            
            alert('success','Thêm đặt phòng thành công! Mã đơn: '.$ORDER_ID);
          }
        }
      }
    }
  }

  // Sửa đặt phòng
  if(isset($_POST['edit_booking'])){
    $frm_data = filteration($_POST);
    
    if(empty($frm_data['booking_id'])){
      alert('error','Không tìm thấy đơn đặt phòng!');
    } else {
      $checkin = new DateTime($frm_data['check_in']);
      $checkout = new DateTime($frm_data['check_out']);
      
      if($checkout <= $checkin){
        alert('error','Ngày trả phòng phải sau ngày nhận phòng!');
      } else {
        // Update booking_order
        $q1 = "UPDATE `booking_order` SET `check_in`=?,`check_out`=?,`arrival`=? WHERE `booking_id`=?";
        update($q1,[$frm_data['check_in'],$frm_data['check_out'],$frm_data['arrival'],$frm_data['booking_id']],'ssii');
        
        // Recalculate total_pay
        $room_q = select("SELECT r.price FROM `booking_order` bo INNER JOIN `rooms` r ON bo.room_id=r.id WHERE bo.booking_id=?",[$frm_data['booking_id']],'i');
        $room_price = mysqli_fetch_assoc($room_q);
        $days = date_diff($checkin,$checkout)->days;
        $total_pay = $room_price['price'] * $days;
        
        // Update booking_details
        $room_no = !empty($frm_data['room_no']) ? $frm_data['room_no'] : null;
        $q2 = "UPDATE `booking_details` SET `total_pay`=?,`room_no`=? WHERE `booking_id`=?";
        update($q2,[$total_pay,$room_no,$frm_data['booking_id']],'isi');
        
        alert('success','Cập nhật đặt phòng thành công!');
      }
    }
  }

  // Hủy đặt phòng
  if(isset($_GET['cancel_booking'])){
    $frm_data = filteration($_GET);
    $q = "UPDATE `booking_order` SET `booking_status`='cancelled', `refund`=0 WHERE `booking_id`=?";
    if(update($q,[$frm_data['cancel_booking']],'i')){
      alert('success','Đã hủy đơn đặt phòng!');
    } else {
      alert('error','Hủy thất bại!');
    }
  }

  // Xác nhận đã nhận phòng
  if(isset($_GET['confirm_arrival'])){
    $frm_data = filteration($_GET);
    $q = "UPDATE `booking_order` SET `arrival`=1 WHERE `booking_id`=?";
    if(update($q,[$frm_data['confirm_arrival']],'i')){
      alert('success','Đã xác nhận nhận phòng!');
    } else {
      alert('error','Thao tác thất bại!');
    }
  }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang quản lý - Quản lý Đặt phòng</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h3>Quản lý Đặt phòng</h3>
          <button class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#addBookingModal">
            <i class="bi bi-plus-circle"></i> Thêm đặt phòng
          </button>
        </div>

        <!-- Filter Tabs -->
        <ul class="nav nav-pills mb-4" id="bookingTabs">
          <li class="nav-item">
            <a class="nav-link active" href="?filter=all">Tất cả</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?filter=booked">Đã đặt</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?filter=cancelled">Đã hủy</a>
          </li>
        </ul>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
              <table class="table table-hover border" style="min-width: 1100px;">
                <thead class="sticky-top">
                  <tr class="bg-dark text-light">
                    <th>#</th>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Phòng</th>
                    <th>Ngày nhận</th>
                    <th>Ngày trả</th>
                    <th>Tổng tiền</th>
                    <th>Số phòng</th>
                    <th>Trạng thái</th>
                    <th>Nhận phòng</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // Build filter query
                    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
                    
                    if($filter == 'booked'){
                      $q = "SELECT bo.*, bd.*, uc.name AS customer_name FROM `booking_order` bo
                        INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
                        INNER JOIN `user_cred` uc ON bo.user_id = uc.id
                        WHERE bo.booking_status='booked'
                        ORDER BY bo.booking_id DESC";
                      $data = mysqli_query($con, $q);
                    } else if($filter == 'cancelled'){
                      $q = "SELECT bo.*, bd.*, uc.name AS customer_name FROM `booking_order` bo
                        INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
                        INNER JOIN `user_cred` uc ON bo.user_id = uc.id
                        WHERE bo.booking_status='cancelled'
                        ORDER BY bo.booking_id DESC";
                      $data = mysqli_query($con, $q);
                    } else {
                      $q = "SELECT bo.*, bd.*, uc.name AS customer_name FROM `booking_order` bo
                        INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
                        INNER JOIN `user_cred` uc ON bo.user_id = uc.id
                        ORDER BY bo.booking_id DESC";
                      $data = mysqli_query($con, $q);
                    }

                    // Set active tab
                    echo "<script>
                      document.querySelectorAll('#bookingTabs .nav-link').forEach(el => {
                        el.classList.remove('active');
                        if(el.href.includes('filter=$filter') || ('$filter'=='all' && el.href.includes('filter=all'))){
                          el.classList.add('active');
                        }
                      });
                    </script>";
                    
                    $i = 1;
                    while($row = mysqli_fetch_assoc($data)){
                      $checkin = date('d-m-Y', strtotime($row['check_in']));
                      $checkout = date('d-m-Y', strtotime($row['check_out']));
                      $total = number_format($row['total_pay'],0,',','.');
                      $room_no = $row['room_no'] ? $row['room_no'] : '<span class="text-muted">-</span>';
                      
                      // Status badge
                      if($row['booking_status'] == 'booked'){
                        $status = "<span class='badge bg-success'>Đã đặt</span>";
                      } else if($row['booking_status'] == 'cancelled'){
                        $status = "<span class='badge bg-danger'>Đã hủy</span>";
                      } else {
                        $status = "<span class='badge bg-secondary'>$row[booking_status]</span>";
                      }

                      // Arrival badge
                      if($row['arrival'] == 1){
                        $arrival = "<span class='badge bg-success'>Đã nhận</span>";
                      } else {
                        if($row['booking_status'] == 'booked'){
                          $arrival = "<a href='?confirm_arrival=$row[booking_id]' class='btn btn-sm btn-outline-success' onclick=\"return confirm('Xác nhận khách đã nhận phòng?')\"><i class='bi bi-check-lg'></i> Xác nhận</a>";
                        } else {
                          $arrival = "<span class='text-muted'>-</span>";
                        }
                      }

                      // Actions
                      $actions = "";
                      if($row['booking_status'] == 'booked'){
                        // Prepare data for edit modal
                        $row_json = htmlspecialchars(json_encode([
                          'booking_id' => $row['booking_id'],
                          'check_in' => $row['check_in'],
                          'check_out' => $row['check_out'],
                          'room_no' => $row['room_no'],
                          'arrival' => $row['arrival']
                        ]), ENT_QUOTES, 'UTF-8');
                        
                        $actions .= "<button class='btn btn-sm btn-warning rounded-pill mb-1' onclick='editBooking($row_json)' data-bs-toggle='modal' data-bs-target='#editBookingModal'><i class='bi bi-pencil'></i> Sửa</button> ";
                        $actions .= "<a href='?cancel_booking=$row[booking_id]' class='btn btn-sm btn-danger rounded-pill mb-1' onclick=\"return confirm('Bạn có chắc chắn muốn hủy đơn đặt phòng này?')\"><i class='bi bi-x-circle'></i> Hủy</a>";
                      }

                      echo<<<data
                        <tr>
                          <td>$i</td>
                          <td><span class="badge bg-primary">$row[order_id]</span></td>
                          <td>
                            <b>$row[customer_name]</b><br>
                            <small class="text-muted">$row[phonenum]</small>
                          </td>
                          <td>$row[room_name]</td>
                          <td>$checkin</td>
                          <td>$checkout</td>
                          <td class="fw-bold">{$total} VND</td>
                          <td>$room_no</td>
                          <td>$status</td>
                          <td>$arrival</td>
                          <td>$actions</td>
                        </tr>
                      data;
                      $i++;
                    }

                    if($i == 1){
                      echo "<tr><td colspan='11' class='text-center text-muted py-4'><i class='bi bi-inbox fs-3'></i><br>Không có đơn đặt phòng nào</td></tr>";
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

  <!-- Modal Thêm đặt phòng -->
  <div class="modal fade" id="addBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="POST">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Thêm đặt phòng mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Khách hàng *</label>
                <select name="user_id" class="form-select shadow-none" required>
                  <option value="">-- Chọn khách hàng --</option>
                  <?php 
                    $users = mysqli_query($con,"SELECT `id`,`name`,`email`,`phonenum` FROM `user_cred` WHERE `status`=1 ORDER BY `name`");
                    while($u = mysqli_fetch_assoc($users)){
                      echo "<option value='$u[id]'>$u[name] ($u[email]) - $u[phonenum]</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Phòng *</label>
                <select name="room_id" class="form-select shadow-none" required>
                  <option value="">-- Chọn phòng --</option>
                  <?php 
                    $rooms = mysqli_query($con,"SELECT `id`,`name`,`price` FROM `rooms` WHERE `status`=1 AND `removed`=0 ORDER BY `name`");
                    while($r = mysqli_fetch_assoc($rooms)){
                      $rp = number_format($r['price'],0,',','.');
                      echo "<option value='$r[id]'>$r[name] - {$rp} VND/đêm</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Ngày nhận phòng *</label>
                <input name="check_in" type="date" class="form-control shadow-none" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Ngày trả phòng *</label>
                <input name="check_out" type="date" class="form-control shadow-none" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Số phòng</label>
                <input name="room_no" type="text" class="form-control shadow-none" placeholder="VD: P201">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" name="add_booking" class="btn btn-dark">Thêm đặt phòng</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Sửa đặt phòng -->
  <div class="modal fade" id="editBookingModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-pencil"></i> Sửa đặt phòng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="booking_id" id="edit_booking_id">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ngày nhận phòng</label>
                <input name="check_in" id="edit_check_in" type="date" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ngày trả phòng</label>
                <input name="check_out" id="edit_check_out" type="date" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Số phòng</label>
                <input name="room_no" id="edit_room_no" type="text" class="form-control shadow-none" placeholder="VD: P201">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Đã nhận phòng</label>
                <select name="arrival" id="edit_arrival" class="form-select shadow-none">
                  <option value="0">Chưa nhận</option>
                  <option value="1">Đã nhận</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" name="edit_booking" class="btn btn-warning">Lưu thay đổi</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php require('inc/scripts.php'); ?>
  <script>
    function editBooking(jsonStr) {
      let data = JSON.parse(jsonStr);
      document.getElementById('edit_booking_id').value = data.booking_id;
      document.getElementById('edit_check_in').value = data.check_in;
      document.getElementById('edit_check_out').value = data.check_out;
      document.getElementById('edit_room_no').value = data.room_no || '';
      document.getElementById('edit_arrival').value = data.arrival;
    }
  </script>
</body>
</html>
