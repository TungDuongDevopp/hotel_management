<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link  rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - Trang chủ</title>
  <style>
    .availability-form{
      margin-top: -50px;
      z-index: 2;
      position: relative;
    }

    @media screen and (max-width: 575px) {
      .availability-form{
        margin-top: 25px;
        padding: 0 35px;
      } 
    }

    .swiper-container .swiper-slide img {
      border-radius: var(--radius);
      max-height: 520px;
      object-fit: cover;
      width: 100%;
    }

    .room-card-img-wrap {
      overflow: hidden;
      border-radius: var(--radius) var(--radius) 0 0;
    }
    .room-card-img-wrap img {
      transition: transform .6s cubic-bezier(.4,0,.2,1);
      width: 100%;
      height: 220px;
      object-fit: cover;
    }
    .card:hover .room-card-img-wrap img {
      transform: scale(1.08);
    }
    .price-tag {
      position: absolute;
      top: 14px; right: 14px;
      background: var(--primary-gradient);
      color: #fff;
      padding: 6px 16px;
      border-radius: 99px;
      font-size: .82rem;
      font-weight: 700;
      box-shadow: 0 4px 12px rgba(27,107,58,.3);
    }
    .facility-icon-box {
      width: 80px; height: 80px;
      border-radius: 50%;
      background: var(--moss-light);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
      transition: var(--transition);
    }
    .facility-icon-box:hover {
      background: var(--primary-gradient);
      transform: scale(1.08);
    }
    .facility-icon-box:hover img {
      filter: brightness(0) invert(1);
    }
    .testimonial-quote {
      position: relative;
      padding-left: 20px;
    }
    .testimonial-quote::before {
      content: '\201C';
      position: absolute;
      left: -5px; top: -15px;
      font-size: 4rem;
      color: var(--gold);
      opacity: .3;
      font-family: serif;
      line-height: 1;
    }
    .section-title {
      position: relative;
      display: inline-block;
    }
    .section-title::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background: var(--gold-gradient);
      border-radius: 99px;
    }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <!-- Carousel -->

  <div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container" style="border-radius: var(--radius);">
      <div class="swiper-wrapper">
        <?php 
          $res = selectAll('carousel');
          while($row = mysqli_fetch_assoc($res))
          {
            $path = CAROUSEL_IMG_PATH;
            echo <<<data
              <div class="swiper-slide">
                <img src="$path$row[image]" class="w-100 d-block">
              </div>
            data;
          }
        ?>
      </div>
    </div>
  </div>

  <!-- check availability form -->

  <div class="container availability-form animate-fade-in">
    <div class="row">
      <div class="col-lg-12 bg-white shadow-lg p-4 rounded" style="border-radius: var(--radius-lg) !important;">
        <h5 class="mb-4 fw-bold h-font d-flex align-items-center">
          <span class="d-inline-flex align-items-center justify-content-center me-2" style="width:36px;height:36px;border-radius:50%;background:var(--moss-light);">
            <i class="bi bi-calendar-check" style="color:var(--primary);"></i>
          </span>
          Tiến hành đặt phòng
        </h5>
        <form action="rooms.php">
          <div class="row align-items-end">
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 600;">
                <i class="bi bi-box-arrow-in-right me-1 text-muted"></i> Nhận phòng
              </label>
              <input type="date" class="form-control shadow-none" name="checkin" required>
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 600;">
                <i class="bi bi-box-arrow-right me-1 text-muted"></i> Trả phòng
              </label>
              <input type="date" class="form-control shadow-none" name="checkout" required>
            </div>
            <div class="col-lg-2 mb-3">
              <label class="form-label" style="font-weight: 600;">
                <i class="bi bi-people me-1 text-muted"></i> Người lớn
              </label>
              <select class="form-select shadow-none" name="adult">
                <?php 
                  $guests_q = mysqli_query($con,"SELECT MAX(adult) AS `max_adult`, MAX(children) AS `max_children` 
                    FROM `rooms` WHERE `status`='1' AND `removed`='0'");  
                  $guests_res = mysqli_fetch_assoc($guests_q);
                  
                  for($i=1; $i<=$guests_res['max_adult']; $i++){
                    echo"<option value='$i'>$i</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-lg-2 mb-3">
              <label class="form-label" style="font-weight: 600;">
                <i class="bi bi-person me-1 text-muted"></i> Trẻ em
              </label>
              <select class="form-select shadow-none" name="children">
                <?php 
                  for($i=1; $i<=$guests_res['max_children']; $i++){
                    echo"<option value='$i'>$i</option>";
                  }
                ?>
              </select>
            </div>
            <input type="hidden" name="check_availability">
            <div class="col-lg-2 mb-lg-3 mt-2">
              <button type="submit" class="btn text-white shadow-none custom-bg w-100">
                <i class="bi bi-search me-1"></i> Tìm kiếm
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Our Rooms -->

  <div class="text-center mt-5 pt-4 mb-4">
    <h2 class="fw-bold h-font section-title">Danh sách phòng</h2>
    <div class="gold-divider mt-3"></div>
  </div>

  <div class="container">
    <div class="row">

      <?php 
            
        $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3",[1,0],'ii');

        while($room_data = mysqli_fetch_assoc($room_res))
        {
          // get features of room

          $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f 
            INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
            WHERE rfea.room_id = '$room_data[id]'");

          $features_data = "";
          while($fea_row = mysqli_fetch_assoc($fea_q)){
            $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
              $fea_row[name]
            </span>";
          }

          // get facilities of room

          $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` f 
            INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
            WHERE rfac.room_id = '$room_data[id]'");

          $facilities_data = "";
          while($fac_row = mysqli_fetch_assoc($fac_q)){
            $facilities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
              $fac_row[name]
            </span>";
          }

          // get thumbnail of image

          $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
          $thumb_q = mysqli_query($con,"SELECT * FROM `room_images` 
            WHERE `room_id`='$room_data[id]' 
            AND `thumb`='1'");

          if(mysqli_num_rows($thumb_q)>0){
            $thumb_res = mysqli_fetch_assoc($thumb_q);
            $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
          }

          $book_btn = "";

          if(!$settings_r['shutdown']){
            $login=0;
            if(isset($_SESSION['login']) && $_SESSION['login']==true){
              $login=1;
            }

            $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm text-white custom-bg shadow-none'><i class='bi bi-lightning-charge me-1'></i>Đặt ngay</button>";
          }

          $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
            WHERE `room_id`='$room_data[id]' ORDER BY `sr_no` DESC LIMIT 20";

          $rating_res = mysqli_query($con,$rating_q);
          $rating_fetch = mysqli_fetch_assoc($rating_res);

          $rating_data = "";

          if($rating_fetch['avg_rating']!=NULL)
          {
            $rating_data = "<div class='rating mb-3'>
              <h6 class='mb-1' style='font-size:.85rem;color:var(--gray-500);'>Đánh giá</h6>
              <span class='badge rounded-pill bg-light'>
            ";

            for($i=0; $i<$rating_fetch['avg_rating']; $i++){
              $rating_data .="<i class='bi bi-star-fill text-warning'></i> ";
            }

            $rating_data .= "</span>
              </div>
            ";
          }

          // print room card

          echo <<<data
            <div class="col-lg-4 col-md-6 my-3">
              <div class="card border-0 shadow" style="max-width: 370px; margin: auto;">
                <div class="room-card-img-wrap position-relative">
                  <img src="$room_thumb" class="card-img-top">
                  <span class="price-tag">$room_data[price] VND / đêm</span>
                </div>
                <div class="card-body">
                  <h5 class="fw-bold mb-2">$room_data[name]</h5>
                  <div class="features mb-3">
                    <h6 class="mb-1" style="font-size:.85rem;color:var(--gray-500);">Không gian</h6>
                    $features_data
                  </div>
                  <div class="facilities mb-3">
                    <h6 class="mb-1" style="font-size:.85rem;color:var(--gray-500);">Tiện ích</h6>
                    $facilities_data
                  </div>
                  <div class="guests mb-3">
                    <h6 class="mb-1" style="font-size:.85rem;color:var(--gray-500);">Sức chứa</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                      <i class="bi bi-people me-1"></i> $room_data[adult] Người lớn
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                      <i class="bi bi-person me-1"></i> $room_data[children] Trẻ em
                    </span>
                  </div>
                  $rating_data
                  <div class="d-flex justify-content-evenly mb-2 pt-2" style="border-top:1px solid var(--gray-100);">
                    $book_btn
                    <a href="room_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none"><i class="bi bi-eye me-1"></i>Chi tiết</a>
                  </div>
                </div>
              </div>
            </div>
          data;

        }

      ?>

      <div class="col-lg-12 text-center mt-5">
        <a href="rooms.php" class="btn btn-outline-dark fw-bold shadow-none px-4 py-2" style="border-radius:var(--radius-sm);">
          Xem tất cả phòng <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- Our Facilities -->

  <div class="text-center mt-5 pt-4 mb-4">
    <h2 class="fw-bold h-font section-title">Các tiện ích</h2>
    <div class="gold-divider mt-3"></div>
  </div>

  <div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
      <?php 
        $res = mysqli_query($con,"SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 5");
        $path = FACILITIES_IMG_PATH;

        while($row = mysqli_fetch_assoc($res)){
          echo<<<data
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3 hover-lift" style="border-radius:var(--radius) !important;">
              <div class="facility-icon-box">
                <img src="$path$row[icon]" width="36px">
              </div>
              <h6 class="mt-2 fw-bold">$row[name]</h6>
            </div>
          data;
        }
      ?>

      <div class="col-lg-12 text-center mt-5">
        <a href="facilities.php" class="btn btn-outline-dark fw-bold shadow-none px-4 py-2" style="border-radius:var(--radius-sm);">
          Xem tất cả tiện ích <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- Testimonials -->

  <div class="text-center mt-5 pt-4 mb-4">
    <h2 class="fw-bold h-font section-title">Đánh giá dịch vụ</h2>
    <div class="gold-divider mt-3"></div>
  </div>

  <div class="container mt-5">
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">
        <?php

          $review_q = "SELECT rr.*,uc.name AS uname, uc.profile, r.name AS rname FROM `rating_review` rr
            INNER JOIN `user_cred` uc ON rr.user_id = uc.id
            INNER JOIN `rooms` r ON rr.room_id = r.id
            ORDER BY `sr_no` DESC LIMIT 6";

          $review_res = mysqli_query($con,$review_q);
          $img_path = USERS_IMG_PATH;

          if(mysqli_num_rows($review_res)==0){
            echo '<div class="text-center text-muted py-4"><i class="bi bi-chat-square-text fs-1 d-block mb-2"></i>Chưa có đánh giá nào!</div>';
          }
          else
          {
            while($row = mysqli_fetch_assoc($review_res))
            {
              $stars = "<i class='bi bi-star-fill text-warning'></i> ";
              for($i=1; $i<$row['rating']; $i++){
                $stars .= " <i class='bi bi-star-fill text-warning'></i>";
              }

              echo<<<slides
                <div class="swiper-slide bg-white p-4" style="border-radius:var(--radius);">
                  <div class="profile d-flex align-items-center mb-3">
                    <img src="$img_path$row[profile]" class="rounded-circle" loading="lazy" width="40px" height="40px" style="object-fit:cover;border:2px solid var(--moss);">
                    <div class="ms-2">
                      <h6 class="m-0 fw-bold" style="font-size:.9rem;">$row[uname]</h6>
                      <small class="text-muted">$row[rname]</small>
                    </div>
                  </div>
                  <div class="testimonial-quote">
                    <p style="font-size:.9rem;color:var(--gray-700);line-height:1.6;">
                      $row[review]
                    </p>
                  </div>
                  <div class="rating mt-2">
                    $stars
                  </div>
                </div>
              slides;
            }
          }
        
        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>

  <!-- Reach us -->

  <div class="text-center mt-5 pt-4 mb-4">
    <h2 class="fw-bold h-font section-title">Liên hệ</h2>
    <div class="gold-divider mt-3"></div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded" style="border-radius:var(--radius) !important;">
        <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy" style="border-radius:var(--radius-sm) !important;"></iframe>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded mb-4 hover-lift" style="border-radius:var(--radius) !important;">
          <h5 class="d-flex align-items-center fw-bold">
            <span class="d-inline-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;border-radius:50%;background:var(--moss-light);">
              <i class="bi bi-telephone-fill" style="color:var(--primary);font-size:.85rem;"></i>
            </span>
            Tổng đài viên
          </h5>
          <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark ms-4" style="font-weight:500;">
            +<?php echo $contact_r['pn1'] ?>
          </a>
        </div>
        <div class="bg-white p-4 rounded mb-3 hover-lift" style="border-radius:var(--radius) !important;">
          <h5 class="d-flex align-items-center fw-bold">
            <span class="d-inline-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;border-radius:50%;background:var(--moss-light);">
              <i class="bi bi-share-fill" style="color:var(--primary);font-size:.85rem;"></i>
            </span>
            Theo dõi chúng tôi
          </h5>
          <div class="ms-4 mt-2">
            <?php 
              if($contact_r['tw']!=''){
                echo<<<data
                  <a href="$contact_r[tw]" class="d-inline-block mb-2">
                    <span class="badge bg-light text-dark fs-6 p-2" style="border-radius:var(--radius-sm);"> 
                    <i class="bi bi-twitter me-1" style="color:#1DA1F2;"></i> Twitter
                    </span>
                  </a>
                  <br>
                data;
              }
            ?>

            <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-2">
              <span class="badge bg-light text-dark fs-6 p-2" style="border-radius:var(--radius-sm);"> 
              <i class="bi bi-facebook me-1" style="color:#1877F2;"></i> Facebook
              </span>
            </a>
            <br>
            <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block">
              <span class="badge bg-light text-dark fs-6 p-2" style="border-radius:var(--radius-sm);"> 
              <i class="bi bi-instagram me-1" style="color:#E4405F;"></i> Instagram
              </span>
            </a>
          </div>
        </div>
        <div class="p-3">
          <a href="about.php" class="btn btn-outline-dark fw-bold shadow-none w-100" style="border-radius:var(--radius-sm);">
            Tìm hiểu thêm <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Password reset modal and code -->

  <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="recovery-form">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center">
              <i class="bi bi-shield-lock fs-3 me-2"></i> Tạo mật khẩu mới
            </h5>
          </div>
          <div class="modal-body">
            <div class="mb-4">
              <label class="form-label">Mật khẩu mới</label>
              <input type="password" name="pass" required class="form-control shadow-none" placeholder="Nhập mật khẩu mới">
              <input type="hidden" name="email">
              <input type="hidden" name="token">
            </div>
            <div class="mb-2 text-end">
              <button type="button" class="btn shadow-none me-2 text-muted" data-bs-dismiss="modal">Huỷ</button>
              <button type="submit" class="btn text-white shadow-none custom-bg">
                <i class="bi bi-check-lg me-1"></i> Tiếp tục
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php require('inc/footer.php'); ?>

  <?php
  
    if(isset($_GET['account_recovery']))
    {
      $data = filteration($_GET);

      $t_date = date("Y-m-d");

      $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
        [$data['email'],$data['token'],$t_date],'sss');

      if(mysqli_num_rows($query)==1)
      {
        echo<<<showModal
          <script>
            var myModal = document.getElementById('recoveryModal');

            myModal.querySelector("input[name='email']").value = '$data[email]';
            myModal.querySelector("input[name='token']").value = '$data[token]';

            var modal = bootstrap.Modal.getOrCreateInstance(myModal);
            modal.show();
          </script>
        showModal;
      }
      else{
        alert("error","Liên kết không còn khả dụng!");
      }

    }

  ?>
  
  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      }
    });

    var swiper = new Swiper(".swiper-testimonials", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      slidesPerView: "3",
      loop: true,
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
      },
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
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });

    // recover account
    
    let recovery_form = document.getElementById('recovery-form');

    recovery_form.addEventListener('submit', (e)=>{
      e.preventDefault();

      let data = new FormData();

      data.append('email',recovery_form.elements['email'].value);
      data.append('token',recovery_form.elements['token'].value);
      data.append('pass',recovery_form.elements['pass'].value);
      data.append('recover_user','');

      var myModal = document.getElementById('recoveryModal');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.hide();

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/login_register.php",true);

      xhr.onload = function(){
        if(this.responseText == 'failed'){
          alert('error',"Khôi phục tài khoản thất bại!");
        }
        else{
          alert('success',"Khôi phục tài khoản thành công!");
          recovery_form.reset();
        }
      }

      xhr.send(data);
    });

  </script>

</body>
</html>