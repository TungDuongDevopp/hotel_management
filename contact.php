<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - Liên hệ</title>
  <style>
    .contact-card {
      border-radius: var(--radius) !important;
      transition: var(--transition);
    }
    .contact-card:hover {
      box-shadow: var(--shadow-lg);
    }
    .contact-icon {
      width: 44px; height: 44px;
      border-radius: 50%;
      background: var(--moss-light);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      margin-right: 12px;
      flex-shrink: 0;
      transition: var(--transition);
    }
    .contact-icon:hover {
      background: var(--primary-gradient);
      color: #fff;
    }
    .social-icon {
      width: 42px; height: 42px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      transition: var(--transition);
      background: var(--gray-100);
      color: var(--gray-700);
    }
    .social-icon:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-sm);
    }
    .social-icon.twitter:hover { background: #1DA1F2; color: #fff; }
    .social-icon.facebook:hover { background: #1877F2; color: #fff; }
    .social-icon.instagram:hover { background: linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888); color: #fff; }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="page-hero">
    <div class="container text-center">
      <h2 class="fw-bold h-font">LIÊN HỆ</h2>
      <div class="gold-divider mt-3"></div>
      <p class="mt-3" style="font-size:.95rem;">
      Chúng tôi luôn sẵn sàng hỗ trợ bạn! <br>
      Liên hệ ngay qua hotline, email, hoặc biểu mẫu trực tuyến để được tư vấn và giải đáp thắc mắc. <br>
      Đội ngũ của chúng tôi sẽ phản hồi nhanh chóng, đảm bảo mang đến sự hài lòng cho quý khách.
      </p>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6 mb-5 px-4">

        <div class="bg-white rounded shadow p-4 contact-card">
          <iframe class="w-100 rounded mb-4" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy" style="border-radius:var(--radius-sm) !important;"></iframe>

          <h5 class="fw-bold d-flex align-items-center">
            <span class="contact-icon"><i class="bi bi-geo-alt-fill"></i></span>
            Địa chỉ
          </h5>
          <a href="<?php echo $contact_r['gmap'] ?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-4 ms-5" style="font-size:.92rem;">
            <?php echo $contact_r['address'] ?>
          </a>

          <h5 class="fw-bold d-flex align-items-center mt-2">
            <span class="contact-icon"><i class="bi bi-telephone-fill"></i></span>
            Tổng đài viên
          </h5>
          <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-4 text-decoration-none text-dark ms-5" style="font-weight:500;">
            +<?php echo $contact_r['pn1'] ?>
          </a>

          <h5 class="fw-bold d-flex align-items-center mt-2">
            <span class="contact-icon"><i class="bi bi-envelope-fill"></i></span>
            Email
          </h5>
          <a href="mailto: <?php echo $contact_r['email'] ?>" class="d-inline-block text-decoration-none text-dark mb-4 ms-5">
            <?php echo $contact_r['email'] ?>
          </a>

          <h5 class="fw-bold mt-2 mb-3">Theo dõi chúng tôi</h5>
          <div class="d-flex gap-2 ms-1">
            <?php 
              if($contact_r['tw']!=''){
                echo<<<data
                  <a href="$contact_r[tw]" class="social-icon twitter">
                    <i class="bi bi-twitter"></i>
                  </a>
                data;
              }
            ?>

            <a href="<?php echo $contact_r['fb'] ?>" class="social-icon facebook">
              <i class="bi bi-facebook"></i>
            </a>
            <a href="<?php echo $contact_r['insta'] ?>" class="social-icon instagram">
              <i class="bi bi-instagram"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 px-4">
        <div class="bg-white rounded shadow p-4 contact-card">
          <form method="POST">
            <h5 class="fw-bold h-font d-flex align-items-center mb-1">
              <span class="contact-icon"><i class="bi bi-chat-dots-fill"></i></span>
              Để lại lời nhắn
            </h5>
            <p class="text-muted mb-4 ms-5" style="font-size:.88rem;">Chúng tôi sẽ phản hồi nhanh nhất có thể.</p>
            <div class="mt-3">
              <label class="form-label">Tên</label>
              <input name="name" required type="text" class="form-control shadow-none" placeholder="Nhập họ tên">
            </div>
            <div class="mt-3">
              <label class="form-label">Email</label>
              <input name="email" required type="email" class="form-control shadow-none" placeholder="example@email.com">
            </div>
            <div class="mt-3">
              <label class="form-label">Tiêu đề</label>
              <input name="subject" required type="text" class="form-control shadow-none" placeholder="Tiêu đề tin nhắn">
            </div>
            <div class="mt-3">
              <label class="form-label">Nội dung</label>
              <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;" placeholder="Nội dung chi tiết..."></textarea>
            </div>
            <button type="submit" name="send" class="btn text-white custom-bg mt-4 w-100">
              <i class="bi bi-send me-1"></i> Gửi tin nhắn
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <?php 

    if(isset($_POST['send']))
    {
      $frm_data = filteration($_POST);

      $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
      $values = [$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message']];

      $res = insert($q,$values,'ssss');
      if($res==1){
        alert('success','Email đã được gửi đi!');
      }
      else{
        alert('error','Hệ thống đang được bảo trì! Hãy thử lại sau ít phút.');
      }
    }
  ?>

  <?php require('inc/footer.php'); ?>

</body>
</html>