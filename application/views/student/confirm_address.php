<?php $this->load->view('student/header.php')?>
<div class="width_602 sign-up-main clearfix">
  <div class="sign-up-form">
     <form class="form-signin" role="form"  id="myform" name="myform" action="/<?=$puri?>/student/index/confirmaddress_do" method="post">
      <ul>
	  <li class="urestitle_font"><?=lang('confirmaddress')?></li>
        <li class="mg_b_20">
          <input class="tongyong width_322" type="text" placeholder="<?=lang('pickup_name')?>" validate="required:true"  id="name" name="name" value="<?=!empty($address->name)?$address->name:''?>">
          <div class="sign-up-right"></div>
        </li>
		
		 <li class="mg_b_20">
			  <select class="tongyong width_322"   name="country" id="country" validate="required:true">
				  
				  <?php 
					if(!empty($nationality)){
						foreach($nationality as $k => $v){
				?>
			<option value="<?=$k?>" <?=!empty($address->country) && $address->country == $k?'selected':''?>><?=$v?></option>
              <?php }}?>
            </select>
          <div class="sign-up-right"></div>
        </li>
		<li class="mg_b_20">
          <input class="tongyong width_322" type="text" placeholder="<?=lang('address_tel')?>" validate="required:true"  d="tel" name="tel" value="<?=!empty($address->tel)?$address->tel:''?>">
          <div class="sign-up-right"></div>
        </li>
		<li class="mg_b_20">
          <input class="tongyong width_322" type="text" placeholder="<?=lang('address_building')?>" id="building" name="building" value="<?=!empty($address->building)?$address->building:''?>">
          <div class="sign-up-right"></div>
        </li><li class="mg_b_20">
          <input class="tongyong width_322" type="text" placeholder="<?=lang('address_street')?>" id="street" name="street" value="<?=!empty($address->street)?$address->street:''?>">
          <div class="sign-up-right"></div>
        </li>
		<li class="mg_b_20">
          <input class="tongyong width_322" type="text" placeholder="<?=lang('address_city')?>" validate="required:true"  id="city" name="city" value="<?=!empty($address->city)?$address->city:''?>">
          <div class="sign-up-right"></div>
        </li><li class="mg_b_20">
          <input class="tongyong width_322" type="text" placeholder="<?=lang('address_postcode')?>" id="postcode" name="postcode" value="<?=!empty($address->postcode)?$address->postcode:''?>">
          <div class="sign-up-right"></div>
        </li><li class="mg_b_20">
          <input class="tongyong width_322" type="text" placeholder="<?=lang('address_address')?>" id="address" name="address" value="<?=!empty($address->address)?$address->address:''?>">
          <div class="sign-up-right"></div>
        </li>
		<input type="hidden" name="appid" value="<?=!empty($applyid)?$applyid:''?>">
        <li>
          <input class="login-btn" type="submit" name="submit" value=" <?=lang('submit')?> "/>
        </li>
      
      </ul>
    </form>
  </div>
</div>
<div class="width_602 contact-shadow bg-size"></div>
<script>
var d = '';
$(function(){
	$('#myform').ajaxForm({
		beforeSend:function(){
			var d = dialog({
					id:'cucasdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
				
		},
		success:function(msg){
			
			if(msg.state == 1){
				dialog({id:'cucasdialog'}).close();
				var d = dialog({
					content: '<?=lang('update_success')?>'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				window.location.href='/<?=$puri?>/student/index';
			}else if(msg.state == 2){
				var d = dialog({
					content: '<?=lang('update_error')?>'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
			}
		},
		dataType:'json'
	
	});
	
	$('#virimg').attr('src','/student/reg/verify?'+ new Date().getTime());

});


</script>
<?php $this->load->view('student/footer_no.php')?>
