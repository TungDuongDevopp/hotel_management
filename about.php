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
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="page-hero">
    <div class="container text-center">
      <h2 class="fw-bold h-font">VỀ CHÚNG TÔI</h2>
      <div class="gold-divider mt-3"></div>
      <p class="mt-3" style="font-size:.95rem;">
        Sinh viên K67 - Trường Đại học Mỏ-Địa chất - Khoa Công nghệ Thông tin <br>
        Nhóm 6 
      </p>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
        <h3 class="mb-3 fw-bold h-font" style="color:var(--primary-dark);">Lời cảm ơn</h3>
        <p style="line-height:1.8;color:var(--gray-700);">
        Chúng em xin được bày tỏ lòng biết ơn đến các thầy, cô trường Đại học Mỏ-Địa chất khoa Công Nghệ Thông Tin đã giúp đỡ, hỗ trợ nhiệt tình trong suốt quá trình học. <br><br>
        Đặc biệt gửi lời cảm ơn đến cô Nguyễn Thị Hữu Phương đã trực tiếp giúp đỡ, hỗ trợ, hướng dẫn em hoàn thành khóa luận này.
        </p>
      </div>
      <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
        <img src="images/about/about.jpg" class="w-100 about-img">
      </div>
    </div>
  </div>

  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center stat-box">
          <img src="images/about/hotel.svg" width="70px">
          <h4 class="mt-3">100+ PHÒNG</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center stat-box">
          <img src="images/about/customers.svg" width="70px">
          <h4 class="mt-3">200+ KHÁCH HÀNG</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center stat-box">
          <img src="images/about/rating.svg" width="70px">
          <h4 class="mt-3">150+ ĐÁNH GIÁ</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center stat-box">
          <img src="images/about/staff.svg" width="70px">
          <h4 class="mt-3">50+ NHÂN SỰ</h4>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center my-5">
    <h3 class="fw-bold h-font section-title" style="position:relative;display:inline-block;">CÁC THÀNH VIÊN TRONG NHÓM</h3>
    <div class="gold-divider mt-3"></div>
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
                <img src="$path$row[picture]" class="w-100">
                <h5 class="mt-3 mb-3 px-2">$row[name]</h5>
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
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });
  </script>


</body>
</html>