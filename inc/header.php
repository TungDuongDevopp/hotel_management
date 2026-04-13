<nav id="nav-bar" class="navbar navbar-expand-lg navbar-light px-lg-3 py-lg-2 sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="index.php"><?php echo $settings_r['site_title'] ?></a>
    <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link me-2" href="index.php">Trang chủ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="rooms.php">Danh sách phòng</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="facilities.php">Tiện ích</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="contact.php">Liên hệ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">Về chúng tôi</a>
        </li>
      </ul>
      <div class="d-flex">
        <?php 
          if(isset($_SESSION['login']) && $_SESSION['login']==true)
          {
            $path = USERS_IMG_PATH;
            echo<<<data
              <div class="btn-group">
                <button type="button" class="btn btn-outline-dark shadow-none dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" style="border-radius:var(--radius-sm);font-weight:500;">
                  <img src="$path$_SESSION[uPic]" style="width: 28px; height: 28px; object-fit:cover; border: 2px solid var(--primary);" class="me-1 rounded-circle">
                  $_SESSION[uName]
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end shadow-sm border-0" style="border-radius:var(--radius-sm);">
                  <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person me-2 text-muted"></i>Hồ sơ cá nhân</a></li>
                  <li><a class="dropdown-item py-2" href="bookings.php"><i class="bi bi-calendar-check me-2 text-muted"></i>Lịch sử đặt phòng</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                </ul>
              </div>
            data;
          }
          else
          {
            echo<<<data
              <button type="button" class="btn shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal" style="background:var(--primary-gradient);color:#fff;border-radius:var(--radius-sm);font-weight:600;padding:8px 22px;">
                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
              </button>
              <button type="button" class="btn btn-outline-dark shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal" style="border-radius:var(--radius-sm);font-weight:600;padding:8px 22px;">
                <i class="bi bi-person-plus me-1"></i> Đăng ký
              </button>
            data;
          }
        ?>
      </div>
    </div>
  </div>
</nav>

<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="login-form">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-shield-lock fs-3 me-2"></i> Đăng nhập
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-4" style="font-size:.9rem;">Chào mừng bạn quay trở lại! Đăng nhập để tiếp tục.</p>
          <div class="mb-3">
            <label class="form-label">Email / Số điện thoại</label>
            <input type="text" name="email_mob" required class="form-control shadow-none" placeholder="Nhập email hoặc số điện thoại">
          </div>
          <div class="mb-4">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="pass" required class="form-control shadow-none" placeholder="Nhập mật khẩu">
          </div>
          <div class="d-flex align-items-center justify-content-between mb-2">
            <button type="submit" class="btn text-white shadow-none custom-bg">
              <i class="bi bi-arrow-right-circle me-1"></i> Tiếp tục
            </button>
            <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal" style="font-size:.88rem;">
              Bạn quên mật khẩu?
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="register-form">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-person-plus-fill fs-3 me-2"></i> Tạo tài khoản mới
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-4" style="font-size:.9rem;">Điền thông tin bên dưới để tạo tài khoản của bạn.</p>
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Tên</label>
                <input name="name" type="text" class="form-control shadow-none" required placeholder="Nhập họ tên">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control shadow-none" required placeholder="example@email.com">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Số điện thoại</label>
                <input name="phonenum" type="number" class="form-control shadow-none" required placeholder="0912 345 678">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Ảnh đại diện</label>
                <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control shadow-none">
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label">Địa chỉ</label>
                <textarea name="address" class="form-control shadow-none" rows="1" required placeholder="Nhập địa chỉ"></textarea>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Mã định danh</label>
                <input name="pincode" type="number" class="form-control shadow-none" required placeholder="Nhập mã">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Sinh nhật</label>
                <input name="dob" type="date" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Mật khẩu</label>
                <input name="pass" type="password" class="form-control shadow-none" required placeholder="Tối thiểu 6 ký tự">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Xác nhận lại mật khẩu</label>
                <input name="cpass" type="password" class="form-control shadow-none" required placeholder="Nhập lại mật khẩu">
              </div>
            </div>
          </div>
          <div class="text-center my-1">
            <button type="submit" class="btn text-white shadow-none custom-bg px-5">
              <i class="bi bi-check-circle me-1"></i> Đăng ký
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="forgot-form">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-key-fill fs-3 me-2"></i> Quên mật khẩu
          </h5>
        </div>
        <div class="modal-body">
          <div class="p-3 rounded mb-3" style="background: var(--cream); border-left: 4px solid var(--gold);">
            <small class="text-muted">
              <i class="bi bi-info-circle me-1"></i>
              Liên kết sẽ được gửi tới địa chỉ email của bạn để tạo lại mật khẩu!
            </small>
          </div>
          <div class="mb-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" required class="form-control shadow-none" placeholder="Nhập email đã đăng ký">
          </div>
          <div class="mb-2 text-end">
            <button type="button" class="btn shadow-none p-0 me-3 text-muted" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
              Huỷ
            </button>
            <button type="submit" class="btn text-white shadow-none custom-bg">
              <i class="bi bi-send me-1"></i> Gửi
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>