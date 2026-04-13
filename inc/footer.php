<footer class="site-footer mt-5">
  <div class="container-fluid py-5 px-4 px-lg-5">
    <div class="row">
      <div class="col-lg-4 mb-4 mb-lg-0">
        <h3 class="h-font fw-bold fs-3 mb-3"><?php echo $settings_r['site_title'] ?></h3>
        <p style="line-height:1.8;font-size:.92rem;">
          <?php echo $settings_r['site_about'] ?>
        </p>
        <div class="d-flex gap-2 mt-3">
          <?php 
            if($contact_r['tw']!=''){
              echo<<<data
                <a href="$contact_r[tw]" class="d-flex align-items-center justify-content-center" style="width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,.08);transition:all .3s;">
                  <i class="bi bi-twitter"></i>
                </a>
              data;
            }
          ?>
          <a href="<?php echo $contact_r['fb'] ?>" class="d-flex align-items-center justify-content-center" style="width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,.08);transition:all .3s;">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="<?php echo $contact_r['insta'] ?>" class="d-flex align-items-center justify-content-center" style="width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,.08);transition:all .3s;">
            <i class="bi bi-instagram"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
        <h5 class="fw-bold">Liên kết nhanh</h5>
        <ul class="list-unstyled" style="line-height:2.2;">
          <li><a href="index.php"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i> Trang chủ</a></li>
          <li><a href="rooms.php"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i> Danh sách phòng</a></li>
          <li><a href="facilities.php"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i> Tiện ích</a></li>
          <li><a href="contact.php"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i> Liên hệ</a></li>
          <li><a href="about.php"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i> Về chúng tôi</a></li>
        </ul>
      </div>
      <div class="col-lg-4 col-md-6">
        <h5 class="fw-bold">Liên hệ</h5>
        <div style="line-height:2.2;font-size:.92rem;">
          <p class="mb-2">
            <i class="bi bi-telephone-fill me-2" style="color:var(--gold);"></i>
            <a href="tel: +<?php echo $contact_r['pn1'] ?>">+<?php echo $contact_r['pn1'] ?></a>
          </p>
          <?php if(!empty($contact_r['email'])){ ?>
          <p class="mb-2">
            <i class="bi bi-envelope-fill me-2" style="color:var(--gold);"></i>
            <a href="mailto:<?php echo $contact_r['email'] ?>"><?php echo $contact_r['email'] ?></a>
          </p>
          <?php } ?>
          <?php if(!empty($contact_r['address'])){ ?>
          <p class="mb-0">
            <i class="bi bi-geo-alt-fill me-2" style="color:var(--gold);"></i>
            <span><?php echo $contact_r['address'] ?></span>
          </p>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-copyright">
    <div class="container">
      <i class="bi bi-building me-1"></i> Hệ thống Quản lý Đặt phòng Khách sạn — VietChill
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

  function alert(type,msg,position='body')
  {
    let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
    let icon = (type == 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
    let element = document.createElement('div');
    element.innerHTML = `
      <div class="alert ${bs_class} alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi ${icon} me-2 fs-5"></i>
        <strong class="me-3">${msg}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `;

    if(position=='body'){
      document.body.append(element);
      element.classList.add('custom-alert');
    }
    else{
      document.getElementById(position).appendChild(element);
    }
    setTimeout(remAlert, 3000);
  }

  function remAlert(){
    let alerts = document.getElementsByClassName('alert');
    if(alerts.length > 0) alerts[0].remove();
  }

  function setActive()
  {
    let navbar = document.getElementById('nav-bar');
    let a_tags = navbar.getElementsByTagName('a');

    for(i=0; i<a_tags.length; i++)
    {
      let file = a_tags[i].href.split('/').pop();
      let file_name = file.split('.')[0];

      if(document.location.href.indexOf(file_name) >= 0){
        a_tags[i].classList.add('active');
      }
    }
  }

  // UC1: Đăng ký
  let register_form = document.getElementById('register-form');

  register_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData();
    data.append('name',register_form.elements['name'].value);
    data.append('email',register_form.elements['email'].value);
    data.append('phonenum',register_form.elements['phonenum'].value);
    data.append('address',register_form.elements['address'].value);
    data.append('pincode',register_form.elements['pincode'].value);
    data.append('dob',register_form.elements['dob'].value);
    data.append('pass',register_form.elements['pass'].value);
    data.append('cpass',register_form.elements['cpass'].value);
    data.append('profile',register_form.elements['profile'].files[0]);
    data.append('register','');

    var myModal = document.getElementById('registerModal');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/login_register.php",true);

    xhr.onload = function(){
      if(this.responseText == 'pass_mismatch'){
        alert('error',"Mật khẩu không trùng khớp!");
      }
      else if(this.responseText == 'email_already'){
        alert('error',"Email đã được đăng ký!");
      }
      else if(this.responseText == 'phone_already'){
        alert('error',"Số điện thoại đã được đăng ký!");
      }
      else if(this.responseText == 'inv_img'){
        alert('error',"Chỉ hỗ trợ định dạng JPG, WEBP & PNG!");
      }
      else if(this.responseText == 'upd_failed'){
        alert('error',"Tải lên hình ảnh thất bại!");
      }
      else if(this.responseText == 'ins_failed'){
        alert('error',"Đăng ký thất bại! Hệ thống đang bảo trì!");
      }
      else if(this.responseText == 'missing_fields'){
        alert('error',"Vui lòng nhập đầy đủ thông tin!");
      }
      else{
        alert('success',"Đăng ký thành công! Vui lòng đăng nhập.");
        register_form.reset();
      }
    }

    xhr.send(data);
  });

  // UC2: Đăng nhập
  let login_form = document.getElementById('login-form');

  login_form.addEventListener('submit', function(e) {
    e.preventDefault();
    let data = new FormData(this);
    data.append('login', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/login_register.php",true);

    xhr.onload = function(){
      if(this.responseText == 'login_success'){
        window.location.href = window.location.href;
      }
      else if(this.responseText == 'invalid_password'){
        alert('error',"Mật khẩu không chính xác!");
      }
      else if(this.responseText == 'invalid_email_mob'){
        alert('error',"Email hoặc số điện thoại không tồn tại!");
      }
      else if(this.responseText == 'missing_fields'){
        alert('error',"Vui lòng nhập đầy đủ thông tin!");
      }
      else{
        alert('error',"Đăng nhập thất bại!");
      }
    }

    xhr.send(data);
  });

  // UC3: Quên mật khẩu
  let forgot_form = document.getElementById('forgot-form');

  forgot_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData();
    data.append('email',forgot_form.elements['email'].value);
    data.append('forgot_pass','');

    var myModal = document.getElementById('forgotModal');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/login_register.php",true);

    xhr.onload = function(){
      if(this.responseText == 'inv_email'){
        alert('error',"Email không tồn tại trong hệ thống!");
      }
      else if(this.responseText == 'inactive'){
        alert('error',"Tài khoản đã bị khóa! Vui lòng liên hệ quản trị viên.");
      }
      else if(this.responseText == 'upd_failed'){
        alert('error',"Không thể đặt lại mật khẩu! Hệ thống đang bảo trì.");
      }
      else if(this.responseText == 'missing_fields'){
        alert('error',"Vui lòng nhập email!");
      }
      else if(this.responseText.startsWith('reset_success_')){
        let newPass = this.responseText.replace('reset_success_','');
        alert('success',"Mật khẩu mới của bạn là: " + newPass + " - Vui lòng kiểm tra email!");
        forgot_form.reset();
      }
      else{
        alert('success',"Mật khẩu mới đã được gửi vào email của bạn!");
        forgot_form.reset();
      }
    }

    xhr.send(data);
  });

  function checkLoginToBook(status,room_id){
    if(status){
      window.location.href='confirm_booking.php?id='+room_id;
    }
    else{
      alert('error','Vui lòng đăng nhập để đặt phòng!');
    }
  }

  setActive();

</script>