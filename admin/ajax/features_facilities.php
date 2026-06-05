<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  adminLogin();

  if(isset($_POST['add_feature']))
  {
    $frm_data = filteration($_POST);

    $q = "INSERT INTO `features`(`name`) VALUES (?)";
    $values = [$frm_data['name']];
    $res = insert($q,$values,'s');
    echo $res;
  }

  if(isset($_POST['get_features']))
  {
    $res = selectAll('features');
    $i=1;

    while($row = mysqli_fetch_assoc($res))
    {
      $feature_name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');

      echo <<<data
        <tr>
          <td>$i</td>
          <td>$row[name]</td>
          <td>
            <button type="button" onclick="editFeature(this)" data-id="$row[id]" data-name="$feature_name" class="btn btn-warning btn-sm shadow-none me-1" data-bs-toggle="modal" data-bs-target="#feature-e">
              <i class="bi bi-pencil-square"></i> Sửa
            </button>
            <button type="button" onclick="rem_feature($row[id])" class="btn btn-danger btn-sm shadow-none">
              <i class="bi bi-trash"></i> Xoá
            </button>
          </td>
        </tr>
      data;
      $i++;
    }
  }

  if(isset($_POST['edit_feature']))
  {
    $frm_data = filteration($_POST);
    $q = "UPDATE `features` SET `name`=? WHERE `id`=?";
    $values = [$frm_data['name'],$frm_data['feature_id']];
    $res = update($q,$values,'si');
    echo $res;
  }

  if(isset($_POST['rem_feature']))
  {
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_feature']];

    // Remove any existing room relations before deleting the feature
    $q1 = "DELETE FROM `room_features` WHERE `features_id`=?";
    $res1 = delete($q1,$values,'i');

    $q2 = "DELETE FROM `features` WHERE `id`=?";
    $res2 = delete($q2,$values,'i');
    echo $res2;
  }


  if(isset($_POST['add_facility']))
  {
    $frm_data = filteration($_POST);

    $img_r = uploadSVGImage($_FILES['icon'],FACILITIES_FOLDER);

    if($img_r == 'inv_img'){
      echo $img_r;
    }
    else if($img_r == 'inv_size'){
      echo $img_r;
    }
    else if($img_r == 'upd_failed'){
      echo $img_r;
    }
    else{
      $q = "INSERT INTO `facilities`(`icon`,`name`, `description`) VALUES (?,?,?)";
      $values = [$img_r,$frm_data['name'],$frm_data['desc']];
      $res = insert($q,$values,'sss');
      echo $res;
    }
  }

  if(isset($_POST['get_facilities']))
  {
    $res = selectAll('facilities');
    $i=1;
    $path = FACILITIES_IMG_PATH;

    while($row = mysqli_fetch_assoc($res))
    {
      $facility_name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
      $facility_desc = htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8');

      echo <<<data
        <tr class='align-middle'>
          <td>$i</td>
          <td><img src="$path$row[icon]" width="100px"></td>
          <td>$row[name]</td>
          <td>$row[description]</td>
          <td>
            <button type="button" onclick="editFacility(this)" data-id="$row[id]" data-name="$facility_name" data-desc="$facility_desc" class="btn btn-warning btn-sm shadow-none me-1" data-bs-toggle="modal" data-bs-target="#facility-e">
              <i class="bi bi-pencil-square"></i> Sửa
            </button>
            <button type="button" onclick="rem_facility($row[id])" class="btn btn-danger btn-sm shadow-none">
              <i class="bi bi-trash"></i> Xoá
            </button>
          </td>
        </tr>
      data;
      $i++;
    }
  }

  if(isset($_POST['edit_facility']))
  {
    $frm_data = filteration($_POST);
    $facility_id = $frm_data['facility_id'];
    $current = mysqli_fetch_assoc(select('SELECT * FROM `facilities` WHERE `id`=?',[$facility_id],'i'));

    if(!$current){
      echo 0;
      exit;
    }

    if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0)
    {
      $img_r = uploadSVGImage($_FILES['icon'],FACILITIES_FOLDER);

      if($img_r == 'Không hỗ trợ định dạng này!'){
        echo 'inv_img';
      }
      else if($img_r == 'Vui lòng chọn hình ảnh dưới 2MB!'){
        echo 'inv_size';
      }
      else if($img_r == 'Tải lên hình ảnh thất bại!'){
        echo 'upd_failed';
      }
      else{
        if(deleteImage($current['icon'],FACILITIES_FOLDER)){
          $q = "UPDATE `facilities` SET `icon`=?,`name`=?,`description`=? WHERE `id`=?";
          $values = [$img_r,$frm_data['name'],$frm_data['desc'],$facility_id];
          $res = update($q,$values,'sssi');
          echo $res;
        }
        else{
          echo 0;
        }
      }
    }
    else
    {
      $q = "UPDATE `facilities` SET `name`=?,`description`=? WHERE `id`=?";
      $values = [$frm_data['name'],$frm_data['desc'],$facility_id];
      $res = update($q,$values,'ssi');
      echo $res;
    }
  }

  if(isset($_POST['rem_facility']))
  {
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_facility']];

    // Remove any existing room relations before deleting the facility
    $q1 = "DELETE FROM `room_facilities` WHERE `facilities_id`=?";
    delete($q1,$values,'i');

    $pre_q = "SELECT * FROM `facilities` WHERE `id`=?";
    $res = select($pre_q,$values,'i');
    $img = mysqli_fetch_assoc($res);

    if(deleteImage($img['icon'],FACILITIES_FOLDER)){
      $q = "DELETE FROM `facilities` WHERE `id`=?";
      $res = delete($q,$values,'i');
      echo $res;      
    }
    else{
      echo 0;
    }
  }

?>