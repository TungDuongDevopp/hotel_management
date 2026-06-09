<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link  rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - Về chúng tôi</title>
  <style>
    .stat-box {
      border-top-color: var(--primary) !important;
      border-radius: var(--radius) !important;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }
    .stat-box::after {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 4px;
      background: var(--primary-gradient);
      transition: var(--transition);
    }
    .stat-box:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-lg);
    }
    .stat-box:hover::after {
      background: var(--gold-gradient);
    }
    .stat-box img {
      transition: var(--transition);
    }
    .stat-box:hover img {
      transform: scale(1.1);
    }
    .stat-box h4 {
      color: var(--primary-dark);
      font-weight: 800;
    }
    .about-img {
      border-radius: var(--radius);
      box-shadow: var(--shadow-md);
      transition: var(--transition);
    }
    .about-img:hover {
      transform: scale(1.02);
      box-shadow: var(--shadow-lg);
    }
    .team-slide {
      border-radius: var(--radius) !important;
      overflow: hidden;
      box-shadow: var(--shadow-sm);
      transition: var(--transition);
    }
    .team-slide:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-4px);
    }
    .team-slide img {
      transition: transform .5s ease;
    }
    .team-slide:hover img {
      transform: scale(1.05);
    }
    .team-slide h5 {
      color: var(--primary-dark);
      font-weight: 700;
    }
    .page-hero {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      padding: 60px 0;
      margin-bottom: 40px;
    }
    .page-hero h2 {
      font-size: 2.5rem;
      text-shadow: 2px 2px 4px rgba(0,0,0,.1);
    }
    .page-hero p {
      color: rgba(255,255,255,.9);
      font-size: 1rem;
    }
    .about-section {
      background: white;
      padding: 60px 0;
      margin-bottom: 40px;
      border-radius: var(--radius);
    }
    .about-section h3 {
      font-size: 2rem;
      margin-bottom: 30px;
      color: var(--primary-dark);
    }
    .about-section p {
      font-size: 1.05rem;
      line-height: 1.9;
      color: var(--gray-700);
      margin-bottom: 15px;
    }
    .stat-box {
      border-top-color: var(--primary) !important;
      border-radius: var(--radius) !important;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, #fff 0%, #f9f9f9 100%);
      border: 1px solid #f0f0f0;
    }
    .stat-box::after {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 4px;
      background: var(--primary-gradient);
      transition: var(--transition);
    }
    .stat-box:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(27,107,58,.15);
      background: white;
      border-color: var(--primary);
    }
    .stat-box:hover::after {
      background: var(--gold-gradient);
      height: 6px;
    }
    .stat-box img {
      transition: var(--transition);
      filter: drop-shadow(0 2px 4px rgba(0,0,0,.1));
    }
    .stat-box:hover img {
      transform: scale(1.15) rotate(5deg);
      filter: drop-shadow(0 4px 8px rgba(27,107,58,.2));
    }
    .stat-box h4 {
      color: var(--primary-dark);
      font-weight: 800;
      font-size: 1.1rem;
      letter-spacing: 0.5px;
    }
    .section-title {
      font-size: 2rem;
      color: var(--primary-dark);
    }
    .section-title::after {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--gold-gradient);
      border-radius: 99px;
    }
    .team-slide {
      border-radius: var(--radius) !important;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,.08);
      transition: var(--transition);
      background: white;
      border: 1px solid #f0f0f0;
    }
    .team-slide:hover {
      box-shadow: 0 10px 30px rgba(27,107,58,.2);
      transform: translateY(-8px);
      border-color: var(--primary);
    }
    .team-slide img {
      transition: transform .5s ease;
      width: 100%;
      height: 280px;
      object-fit: cover;
    }
    .team-slide:hover img {
      transform: scale(1.08);
    }
    .team-slide h5 {
      color: var(--primary-dark);
      font-weight: 700;
      font-size: 1.1rem;
      margin-bottom: 8px !important;
    }
    .team-slide p {
      color: var(--gray-600);
      font-size: 0.9rem;
      margin: 0;
    }
    .stats-container {
      background: white;
      padding: 50px 0;
      border-radius: var(--radius);
      margin-bottom: 40px;
    }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="page-hero">
    <div class="container text-center">
      <h2 class="fw-bold h-font mb-3">VỀ CHÚNG TÔI</h2>
      <div class="gold-divider" style="margin: 0 auto 20px; width: 80px; height: 4px; background: var(--gold-gradient); border-radius: 99px;"></div>
      <p class="mt-3" style="font-size:.95rem;">
        <i class="bi bi-building me-2"></i> Sinh viên K67 - Trường Đại học Mỏ-Địa chất - Khoa Công nghệ Thông tin <br>
        <i class="bi bi-people me-2"></i> Nhóm 6 
      </p>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
        <h3 class="mb-4 fw-bold h-font" style="color:var(--primary-dark);">Lời cảm ơn</h3>
        <p style="line-height:1.8;color:var(--gray-700);">
        Chúng em xin được bày tỏ lòng biết ơn đến các thầy, cô trường Đại học Mỏ-Địa chất khoa Công Nghệ Thông Tin đã giúp đỡ, hỗ trợ nhiệt tình trong suốt quá trình học. <br><br>
        Đặc biệt gửi lời cảm ơn đến cô <span style="color:var(--primary);font-weight:600;">Nguyễn Thị Hữu Phương</span> đã trực tiếp giúp đỡ, hỗ trợ, hướng dẫn em hoàn thành khóa luận này.
        </p>
        <div class="mt-4 pt-3" style="border-top:2px solid var(--gold);">
          <p style="color:var(--primary-dark);font-weight:600;margin-top:15px;">
            <i class="bi bi-check-circle-fill me-2" style="color:var(--primary);"></i>Hệ thống quản lý phòng khách sạn
          </p>
          <p style="color:var(--primary-dark);font-weight:600;">
            <i class="bi bi-check-circle-fill me-2" style="color:var(--primary);"></i>Công nghệ web hiện đại
          </p>
        </div>
      </div>
      <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
        <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&h=500&fit=crop" class="w-100 about-img" loading="lazy" alt="Về chúng tôi">
      </div>
    </div>
  </div>

  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center stat-box">
          <img src="images/about/hotel.svg" width="70px" loading="lazy">
          <h4 class="mt-4">100+</h4>
          <p style="color:var(--gray-600);margin:0;">PHÒNG KHÁCH SẠN</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center stat-box">
          <img src="images/about/customers.svg" width="70px" loading="lazy">
          <h4 class="mt-4">200+</h4>
          <p style="color:var(--gray-600);margin:0;">KHÁCH HÀNG HÀI LÒNG</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center stat-box">
          <img src="images/about/rating.svg" width="70px" loading="lazy">
          <h4 class="mt-4">150+</h4>
          <p style="color:var(--gray-600);margin:0;">ĐÁNH GIÁ 5 SAO</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center stat-box">
          <img src="images/about/staff.svg" width="70px" loading="lazy">
          <h4 class="mt-4">50+</h4>
          <p style="color:var(--gray-600);margin:0;">NHÂN VIÊN CHUYÊN NGHIỆP</p>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center my-5">
    <h3 class="fw-bold h-font section-title" style="position:relative;display:inline-block;">CÁC THÀNH VIÊN TRONG NHÓM</h3>
    <div style="margin-top: 30px;"></div>
  </div>

  <div class="container px-4">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper mb-5">
        <?php 
          $about_r = selectAll('team_details');
          $path=ABOUT_IMG_PATH;
          while($row = mysqli_fetch_assoc($about_r)){
            echo<<<data
              <div class="swiper-slide bg-white text-center overflow-hidden team-slide">
                <img src="$path$row[picture]" class="w-100" loading="lazy">
                <div style="padding: 20px;">
                  <h5 class="mt-2 mb-1 px-2">$row[name]</h5>
                  <p class="px-2 mb-0" style="font-size:0.9rem;color:var(--primary);">
                    <i class="bi bi-code-slash me-1"></i> Thành viên nhóm
                  </p>
                </div>
              </div>
            data;
          }
        
        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>


  <?php require('inc/footer.php'); ?>

  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".mySwiper", {
      spaceBetween: 40,
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 4,
        },
      }
    });
  </script>


</body>
</html>