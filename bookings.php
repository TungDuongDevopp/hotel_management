<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - Lịch sử đặt phòng</title>
</head>
<body class="bg-light">

  <?php 
    require('inc/header.php'); 

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
      redirect('index.php');
    }
  ?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 px-4">
        <h2 class="fw-bold h-font">Lịch sử đặt phòng</h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">Trang chủ</a>
          <span class="text-secondary"> > </span>
          <a href="#" class="text-secondary text-decoration-none">Lịch sử đặt phòng</a>
        </div>
      </div>

      <?php 
        // UC7: Xem danh sách lịch sử đặt phòng
        $query = "SELECT bo.*, bd.* FROM `booking_order` bo
          INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
          WHERE (bo.booking_status='booked' OR bo.booking_status='cancelled')
          AND (bo.user_id=?)
          ORDER BY bo.booking_id DESC";

        $result = select($query,[$_SESSION['uId']],'i');
        $has_bookings = false;

        while($data = mysqli_fetch_assoc($result))
        {
          $has_bookings = true;
          $date = date("d-m-Y",strtotime($data['datentime']));
          $checkin = date("d-m-Y",strtotime($data['check_in']));
          $checkout = date("d-m-Y",strtotime($data['check_out']));

          $status_bg = "";
          $status_text = "";
          $btn = "";
          
          if($data['booking_status']=='booked')
          {
            $status_bg = "bg-success";
            $status_text = "Đã đặt";

            // THÊM NÚT THANH TOÁN VNPAY Ở ĐÂY
    $btn = "<form action='vnpay_create_payment.php' method='POST' class='d-inline-block mb-2'>
              <input type='hidden' name='booking_id' value='$data[booking_id]'>
              <input type='hidden' name='total_pay' value='$data[total_pay]'>
              <button type='submit' name='redirect' class='btn btn-primary btn-sm shadow-none'>
                <i class='bi bi-credit-card'></i> Thanh toán VNPAY
              </button>
            </form><br>";

            if($data['arrival']==1)
            {
              if($data['rate_review']==0){
                // UC9: Đánh giá phòng
                $btn.="<button type='button' onclick='review_room($data[booking_id],$data[room_id])' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-dark btn-sm shadow-none mt-2'><i class='bi bi-star'></i> Đánh giá</button>";
              } else {
                $btn.="<span class='badge bg-info mt-2'>Đã đánh giá</span>";
              }
            }
            else{
              // UC8: Hủy đặt phòng
              $btn="<button onclick='cancel_booking($data[booking_id])' type='button' class='btn btn-danger btn-sm shadow-none mt-2'><i class='bi bi-x-circle'></i> Hủy đặt phòng</button>";
            }
          }
          else if($data['booking_status']=='cancelled')
          {
            $status_bg = "bg-danger";
            $status_text = "Đã hủy";

            if($data['refund']==0){
              $btn="<span class='badge bg-primary mt-2'>Đang xử lý hoàn tiền</span>";
            }
          }
          else
          {
            $status_bg = "bg-secondary";
            $status_text = $data['booking_status'];
          }

          $formatted_price = number_format($data['price'],0,',','.');
          $formatted_total = number_format($data['total_pay'],0,',','.');

          echo<<<bookings
            <div class='col-md-4 px-4 mb-4'>
              <div class='bg-white p-3 rounded shadow-sm'>
                <h5 class='fw-bold'>$data[room_name]</h5>
                <p class='text-muted mb-2'>{$formatted_price} VND / đêm</p>
                <p>
                  <b>Ngày nhận phòng: </b> $checkin <br>
                  <b>Ngày trả phòng: </b> $checkout
                </p>
                <p>
                  <b>Tổng tiền: </b> {$formatted_total} VND <br>
                  <b>Mã đơn: </b> $data[order_id] <br>
                  <b>Ngày đặt: </b> $date
                </p>
                <p>
                  <span class='badge $status_bg'>$status_text</span>
                </p>
                $btn
              </div>
            </div>
          bookings;
        }

        if(!$has_bookings){
          echo "<div class='col-12 px-4 mb-5'><div class='bg-white p-4 rounded shadow-sm text-center'><h5 class='text-muted'><i class='bi bi-inbox'></i> Bạn chưa có lịch sử đặt phòng nào</h5></div></div>";
        }
      ?>

    </div>
  </div>

  <!-- UC9: Modal đánh giá phòng -->
  <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="review-form">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center">
              <i class="bi bi-star-fill fs-3 me-2 text-warning"></i> Đánh giá phòng
            </h5>
            <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-bold">Đánh giá</label>
              <select class="form-select shadow-none" name="rating">
                <option value="5">⭐⭐⭐⭐⭐ Xuất sắc</option>
                <option value="4">⭐⭐⭐⭐ Tốt</option>
                <option value="3">⭐⭐⭐ Bình thường</option>
                <option value="2">⭐⭐ Kém</option>
                <option value="1">⭐ Rất kém</option>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label fw-bold">Nội dung đánh giá</label>
              <textarea name="review" rows="3" required class="form-control shadow-none" placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
            </div>
            
            <input type="hidden" name="booking_id">
            <input type="hidden" name="room_id">

            <div class="text-end">
              <button type="button" class="btn btn-secondary shadow-none me-2" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-dark shadow-none">Gửi đánh giá</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php 
    if(isset($_GET['cancel_status'])){
      alert('success','Hủy đặt phòng thành công!');
    }  
    else if(isset($_GET['review_status'])){
      alert('success','Cảm ơn bạn đã để lại đánh giá!');
    }  
  ?>

  <?php require('inc/footer.php'); ?>

  <script>
    // UC8: Hủy đặt phòng
    function cancel_booking(id)
    {
      if(confirm('Bạn có chắc chắn muốn hủy đặt phòng này?'))
      {        
        let data = new FormData();
        data.append('cancel_booking','');
        data.append('booking_id', id);

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/cancel_booking.php",true);

        xhr.onload = function(){
          if(this.responseText == 1){
            window.location.href="bookings.php?cancel_status=true";
          }
          else if(this.responseText == 'expired'){
            alert('error','Không thể hủy: phòng đã quá hạn nhận phòng!');
          }
          else if(this.responseText == 'already_cancelled'){
            alert('error','Đơn đặt phòng này đã được hủy trước đó!');
          }
          else{
            alert('error','Hủy đặt phòng không thành công!');
          }
        }

        xhr.send(data);
      }
    }

    // UC9: Đánh giá phòng
    let review_form = document.getElementById('review-form');

    function review_room(bid,rid){
      review_form.elements['booking_id'].value = bid;
      review_form.elements['room_id'].value = rid;
    }

    review_form.addEventListener('submit',function(e){
      e.preventDefault();

      let data = new FormData();
      data.append('review_room','');
      data.append('rating',review_form.elements['rating'].value);
      data.append('review',review_form.elements['review'].value);
      data.append('booking_id',review_form.elements['booking_id'].value);
      data.append('room_id',review_form.elements['room_id'].value);

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/review_room.php",true);

      xhr.onload = function()
      {
        if(this.responseText == 1){
          window.location.href = 'bookings.php?review_status=true';
        }
        else if(this.responseText == 'already_reviewed'){
          var myModal = document.getElementById('reviewModal');
          var modal = bootstrap.Modal.getInstance(myModal);
          modal.hide();
          alert('error',"Bạn đã đánh giá đơn đặt phòng này rồi!");
        }
        else if(this.responseText == 'missing_fields'){
          alert('error',"Vui lòng nhập đầy đủ thông tin đánh giá!");
        }
        else{
          var myModal = document.getElementById('reviewModal');
          var modal = bootstrap.Modal.getInstance(myModal);
          modal.hide();
          alert('error',"Đánh giá thất bại!");
        }
      }

      xhr.send(data);
    })
  </script>

</body>
</html>