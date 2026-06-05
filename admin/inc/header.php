<div class="admin-topbar">
  <span class="brand h-font">
    <i class="bi bi-building me-1"></i> VietChill Admin
  </span>
  <a href="logout.php" class="btn btn-logout">
    <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
  </a>
</div>

<div class="col-lg-2" id="dashboard-menu">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid flex-lg-column align-items-stretch p-0">
      <button class="navbar-toggler shadow-none d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border:1px solid rgba(255,255,255,.15);margin:12px;">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse flex-column align-items-stretch" id="adminDropdown">
        <h6 class="sidebar-title">Tổng quan</h6>
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="bi bi-grid-1x2-fill"></i> Bảng theo dõi
            </a>
          </li>
        </ul>

        <div class="sidebar-divider"></div>
        <h6 class="sidebar-title">Đặt phòng</h6>
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="bookings.php">
              <i class="bi bi-calendar-check-fill"></i> Quản lý đặt phòng
            </a>
          </li>
        </ul>

        <div class="sidebar-divider"></div>
        <h6 class="sidebar-title">Quản lý</h6>
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="users.php">
              <i class="bi bi-people-fill"></i> Người dùng
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user_queries.php">
              <i class="bi bi-envelope-fill"></i> Liên hệ
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="rate_review.php">
              <i class="bi bi-star-fill"></i> Đánh giá
            </a>
          </li>
        </ul>

        <div class="sidebar-divider"></div>
        <h6 class="sidebar-title">Nội dung</h6>
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="rooms.php">
              <i class="bi bi-door-open-fill"></i> Phòng
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="features_facilities.php">
              <i class="bi bi-grid-3x3-gap-fill"></i> Không gian & Tiện nghi
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="carousel.php">
              <i class="bi bi-images"></i> Trình chiếu
            </a>
          </li>
        </ul>

        <div class="sidebar-divider"></div>
        <h6 class="sidebar-title">Hệ thống</h6>
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="settings.php">
              <i class="bi bi-gear-fill"></i> Cài đặt trang
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>