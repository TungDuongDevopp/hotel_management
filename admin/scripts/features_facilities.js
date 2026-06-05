const feature_s_form = document.getElementById('feature_s_form');
const facility_s_form = document.getElementById('facility_s_form');

if(feature_s_form){
  feature_s_form.addEventListener('submit',function(e){
    e.preventDefault();
    add_feature();
  });
}

if(facility_s_form){
  facility_s_form.addEventListener('submit',function(e){
    e.preventDefault();
    add_facility();
  });
}

function add_feature()
{
  let data = new FormData();
  data.append('name',feature_s_form.elements['feature_name'].value);
  data.append('add_feature','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/features_facilities.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('feature-s');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','New feature added!');
      feature_s_form.elements['feature_name'].value='';
      get_features();
    }
    else{
      alert('error','Server Down!');
    }
  }

  xhr.send(data);
}

function get_features()
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/features_facilities.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('features-data').innerHTML = this.responseText;
  }

  xhr.send('get_features');
}

window.rem_feature = function(val)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/features_facilities.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(this.responseText==1){
      alert('success','Feature removed!');
      get_features();
    }
    else if(this.responseText == 'room_added'){
      alert('error','Feature is added in room!');
    }
    else{
      alert('error','Server down!');
    }
  }

  xhr.send('rem_feature='+val);
}

facility_s_form.addEventListener('submit',function(e){
  e.preventDefault();
  add_facility();
});

function add_facility()
{
  let data = new FormData();
  data.append('name',facility_s_form.elements['facility_name'].value);
  data.append('icon',facility_s_form.elements['facility_icon'].files[0]);
  data.append('desc',facility_s_form.elements['facility_desc'].value);
  data.append('add_facility','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/features_facilities.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('facility-s');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 'inv_img'){
      alert('error','Only SVG images are allowed!');
    }
    else if(this.responseText == 'inv_size'){
      alert('error','Image should be less than 1MB!');
    }
    else if(this.responseText == 'upd_failed'){
      alert('error','Image upload failed. Server Down!');
    }
    else{
      alert('success','New facility added!');
      facility_s_form.reset();
      get_facilities();
    }
  }

  xhr.send(data);
}

function get_facilities()
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/features_facilities.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('facilities-data').innerHTML = this.responseText;
  }

  xhr.send('get_facilities');
}

window.rem_facility = function(val)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/features_facilities.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(this.responseText==1){
      alert('success','Facility removed!');
      get_facilities();
    }
    else if(this.responseText == 'room_added'){
      alert('error','Facility is added in room!');
    }
    else{
      alert('error','Server down!');
    }
  }

  xhr.send('rem_facility='+val);
}

const feature_e_form = document.getElementById('feature_e_form');
const facility_e_form = document.getElementById('facility_e_form');

window.editFeature = function(button)
{
  document.getElementById('edit_feature_id').value = button.dataset.id;
  document.getElementById('edit_feature_name').value = button.dataset.name;
}

if(feature_e_form){
  feature_e_form.addEventListener('submit',function(e){
    e.preventDefault();
    edit_feature();
  });
}

function edit_feature()
{
  let data = new FormData();
  data.append('edit_feature','');
  data.append('feature_id',feature_e_form.elements['feature_id'].value);
  data.append('name',feature_e_form.elements['feature_name'].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/features_facilities.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('feature-e');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','Feature updated!');
      get_features();
    }
    else{
      alert('error','Server Down!');
    }
  }

  xhr.send(data);
}

window.editFacility = function(button)
{
  document.getElementById('edit_facility_id').value = button.dataset.id;
  document.getElementById('edit_facility_name').value = button.dataset.name;
  document.getElementById('edit_facility_desc').value = button.dataset.desc;
  document.getElementById('edit_facility_icon').value = '';
}

if(facility_e_form){
  facility_e_form.addEventListener('submit',function(e){
    e.preventDefault();
    edit_facility();
  });
}

function edit_facility()
{
  let data = new FormData();
  data.append('edit_facility','');
  data.append('facility_id',facility_e_form.elements['facility_id'].value);
  data.append('name',facility_e_form.elements['facility_name'].value);
  data.append('desc',facility_e_form.elements['facility_desc'].value);

  if(facility_e_form.elements['facility_icon'].files.length > 0){
    data.append('icon',facility_e_form.elements['facility_icon'].files[0]);
  }

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/features_facilities.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('facility-e');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 'inv_img'){
      alert('error','Only SVG images are allowed!');
    }
    else if(this.responseText == 'inv_size'){
      alert('error','Image should be less than 1MB!');
    }
    else if(this.responseText == 'upd_failed'){
      alert('error','Image upload failed. Server Down!');
    }
    else if(this.responseText == 1){
      alert('success','Facility updated!');
      get_facilities();
    }
    else{
      alert('error','Server down!');
    }
  }

  xhr.send(data);
}

window.onload = function(){
  get_features();
  get_facilities();
}
