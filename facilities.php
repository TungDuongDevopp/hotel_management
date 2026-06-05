<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - Tiện ích</title>
  <style>
    .facility-card {
      border-radius: var(--radius) !important;
      border-top: 4px solid var(--primary) !important;
      transition: var(--transition) !important;
      position: relative;
      overflow: hidden;
    }
    .facility-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 4px;
      background: var(--gold-gradient);
      transform: scaleX(0);
      transition: transform .3s ease;
      transform-origin: left;
    }
    .facility-card:hover::before {
      transform: scaleX(1);
    }
    .facility-card:hover {
      transform: translateY(-6px) !important;
      box-shadow: var(--shadow-lg) !important;
    }
    .facility-card .icon-wrap {
      width: 60px; height: 60px;
      border-radius: 50%;
      background: var(--moss-light);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      transition: var(--transition);
    }
    .facility-card:hover .icon-wrap {
      background: var(--primary-gradient);
    }
    .facility-card:hover .icon-wrap img {
      filter: brightness(0) invert(1);
    }
    .facility-card p {
      color: var(--gray-700);
      font-size: .92rem;
      line-height: 1.6;
    }
    .facility-card h5 {
      color: var(--primary-dark);
      font-weight: 700;
    }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="page-hero">
    <div class="container text-center">
      <h2 class="fw-bold h-font">TIỆN ÍCH</h2>
      <div class="gold-divider mt-3"></div>
      <p class="mt-3" style="font-size:.95rem;">
      Khách sạn cung cấp đầy đủ tiện nghi hiện đại như Wi-Fi tốc độ cao, máy lạnh, truyền hình, và máy nước nóng. <br>
      Quý khách có thể thư giãn tại spa, tận hưởng không gian ban công thoáng mát, hoặc sử dụng khu bếp tiện nghi và ghế sofa êm ái. <br>
      Chúng tôi cam kết mang đến trải nghiệm nghỉ dưỡng thoải mái và trọn vẹn.
      </p>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <?php 
        $res = selectAll('facilities');
        $path = FACILITIES_IMG_PATH;

        while($row = mysqli_fetch_assoc($res)){
          echo<<<data
            <div class="col-lg-4 col-md-6 mb-5 px-4">
              <div class="bg-white rounded shadow p-4 border-top border-4 facility-card">
                <div class="d-flex align-items-center mb-3">
                  <div class="icon-wrap">
                    <img src="$path$row[icon]" width="30px">
                  </div>
                  <h5 class="m-0 ms-3">$row[name]</h5>
                </div>
                <p class="mb-0">$row[description]</p>
              </div>
            </div>
          data;
        }
      ?>
    </div>
  </div>


  <?php require('inc/footer.php'); ?>

</body>
</html>