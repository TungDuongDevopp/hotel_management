<?php
  require('inc/essentials.php');
  require('inc/db_config.php');
  adminLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang Quản Lý</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php 
    require('inc/header.php'); 
    
    $is_shutdown = mysqli_fetch_assoc(mysqli_query($con,"SELECT `shutdown` FROM `settings`"));

    $current_bookings = mysqli_fetch_assoc(mysqli_query($con,"SELECT 
      COUNT(CASE WHEN booking_status='booked' AND arrival=0 THEN 1 END) AS `new_bookings`,
      COUNT(CASE WHEN booking_status='cancelled' AND refund=0 THEN 1 END) AS `refund_bookings`
      FROM `booking_order`"));

    $unread_queries = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count`
      FROM `user_queries` WHERE `seen`=0"));

    $unread_reviews = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count`
      FROM `rating_review` WHERE `seen`=0"));
    
    $current_users = mysqli_fetch_assoc(mysqli_query($con,"SELECT 
      COUNT(id) AS `total`,
      COUNT(CASE WHEN `status`=1 THEN 1 END) AS `active`,
      COUNT(CASE WHEN `status`=0 THEN 1 END) AS `inactive`,
      COUNT(CASE WHEN `is_verified`=0 THEN 1 END) AS `unverified`
      FROM `user_cred`"));

    // UC15: Thống kê doanh thu
    $revenue = mysqli_fetch_assoc(mysqli_query($con,"SELECT 
      COALESCE(SUM(CASE WHEN bo.trans_status='TXN_SUCCESS' THEN bd.total_pay ELSE 0 END), 0) AS total_revenue,
      COUNT(CASE WHEN bo.trans_status='TXN_SUCCESS' THEN 1 END) AS paid_bookings,
      COALESCE(SUM(CASE WHEN bo.booking_status='cancelled' THEN bd.total_pay ELSE 0 END), 0) AS cancelled_revenue
      FROM `booking_order` bo 
      LEFT JOIN `booking_details` bd ON bo.booking_id = bd.booking_id"));

    $monthly_revenue = mysqli_query($con,"SELECT 
      DATE_FORMAT(bo.datentime, '%Y-%m') AS month,
      COUNT(*) AS total_bookings,
      COALESCE(SUM(CASE WHEN bo.trans_status='TXN_SUCCESS' THEN bd.total_pay ELSE 0 END), 0) AS revenue
      FROM `booking_order` bo 
      LEFT JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      GROUP BY DATE_FORMAT(bo.datentime, '%Y-%m')
      ORDER BY month DESC
      LIMIT 12");
  ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h3>DASHBOARD</h3>
          <?php 
            if($is_shutdown['shutdown']){
              echo<<<data
                <h6 class="badge bg-danger py-2 px-3 rounded">Chế độ bảo trì đang hoạt động!</h6>
              data;
            }
          ?>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
          <div class="col-md-3 mb-4">
            <a href="bookings.php?filter=booked" class="text-decoration-none">
              <div class="card text-center text-success p-3">
                <h6>Đặt phòng mới</h6>
                <h1 class="mt-2 mb-0"><?php echo $current_bookings['new_bookings'] ?></h1>
              </div>
            </a>
          </div>
          <div class="col-md-3 mb-4">
            <a href="bookings.php?filter=cancelled" class="text-decoration-none">
              <div class="card text-center text-warning p-3">
                <h6>Yêu cầu hoàn tiền</h6>
                <h1 class="mt-2 mb-0"><?php echo $current_bookings['refund_bookings'] ?></h1>
              </div>
            </a>
          </div>
          <div class="col-md-3 mb-4">
            <a href="user_queries.php" class="text-decoration-none">
              <div class="card text-center text-info p-3">
                <h6>Liên hệ mới</h6>
                <h1 class="mt-2 mb-0"><?php echo $unread_queries['count'] ?></h1>
              </div>
            </a>
          </div>
          <div class="col-md-3 mb-4">
            <a href="rate_review.php" class="text-decoration-none">
              <div class="card text-center text-primary p-3">
                <h6>Đánh giá mới</h6>
                <h1 class="mt-2 mb-0"><?php echo $unread_reviews['count'] ?></h1>
              </div>
            </a>
          </div>
        </div>

        <!-- UC15: Thống kê doanh thu -->
        <h5 class="mb-3"><i class="bi bi-graph-up"></i> Thống kê Doanh thu</h5>
        <div class="row mb-4">
          <div class="col-md-4 mb-4">
            <div class="card text-center p-3 border-0 shadow-sm">
              <h6 class="text-muted">Tổng doanh thu</h6>
              <h3 class="text-success mt-2 mb-0"><?php echo number_format($revenue['total_revenue'],0,',','.') ?> VND</h3>
              <small class="text-muted"><?php echo $revenue['paid_bookings'] ?> đơn đã thanh toán</small>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card text-center p-3 border-0 shadow-sm">
              <h6 class="text-muted">Doanh thu bị hủy</h6>
              <h3 class="text-danger mt-2 mb-0"><?php echo number_format($revenue['cancelled_revenue'],0,',','.') ?> VND</h3>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card text-center p-3 border-0 shadow-sm">
              <h6 class="text-muted">Doanh thu thực tế</h6>
              <h3 class="text-primary mt-2 mb-0"><?php echo number_format($revenue['total_revenue'] - $revenue['cancelled_revenue'],0,',','.') ?> VND</h3>
            </div>
          </div>
        </div>

        <!-- Monthly Revenue Table -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h6 class="mb-3">Doanh thu theo tháng</h6>
            <div class="table-responsive">
              <table class="table table-hover border">
                <thead>
                  <tr class="bg-dark text-light">
                    <th>Tháng</th>
                    <th>Số đơn</th>
                    <th>Doanh thu</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    while($row = mysqli_fetch_assoc($monthly_revenue)){
                      $formatted_revenue = number_format($row['revenue'],0,',','.');
                      echo<<<data
                        <tr>
                          <td>{$row['month']}</td>
                          <td>{$row['total_bookings']}</td>
                          <td class="text-success fw-bold">{$formatted_revenue} VND</td>
                        </tr>
                      data;
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Booking Analytics -->
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5>Phân tích Đặt phòng</h5>
          <select class="form-select shadow-none bg-light w-auto" onchange="booking_analytics(this.value)">
            <option value="1">30 ngày gần đây</option>
            <option value="2">90 ngày gần đây</option>
            <option value="3">1 năm gần đây</option>
            <option value="4">Tất cả</option>
          </select>
        </div>

        <div class="row mb-3">
          <div class="col-md-4 mb-4">
            <div class="card text-center text-primary p-3">
              <h6>Tổng đơn</h6>
              <h1 class="mt-2 mb-0" id="total_bookings">0</h1>
              <h4 class="mt-2 mb-0" id="total_amt">0 VND</h4>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card text-center text-success p-3">
              <h6>Đơn hoạt động</h6>
              <h1 class="mt-2 mb-0" id="active_bookings">0</h1>
              <h4 class="mt-2 mb-0" id="active_amt">0 VND</h4>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card text-center text-danger p-3">
              <h6>Đơn bị hủy</h6>
              <h1 class="mt-2 mb-0" id="cancelled_bookings">0</h1>
              <h4 class="mt-2 mb-0" id="cancelled_amt">0 VND</h4>
            </div>
          </div>
        </div>

        <!-- User Stats -->
        <h5>Người dùng</h5>
        <div class="row mb-3">
          <div class="col-md-3 mb-4">
            <div class="card text-center text-info p-3">
              <h6>Tổng cộng</h6>
              <h1 class="mt-2 mb-0"><?php echo $current_users['total'] ?></h1>
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="card text-center text-success p-3">
              <h6>Hoạt động</h6>
              <h1 class="mt-2 mb-0"><?php echo $current_users['active'] ?></h1>
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="card text-center text-warning p-3">
              <h6>Ngưng hoạt động</h6>
              <h1 class="mt-2 mb-0"><?php echo $current_users['inactive'] ?></h1>
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="card text-center text-danger p-3">
              <h6>Chưa xác nhận</h6>
              <h1 class="mt-2 mb-0"><?php echo $current_users['unverified'] ?></h1>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php require('inc/scripts.php'); ?>
  <script src="scripts/dashboard.js"></script>
</body>
</html>