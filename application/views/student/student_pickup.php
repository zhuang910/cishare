<?php $this->load->view('student/headermy.php')?>
<?php $this->load->view('public/js_My97DatePicker')?>
<div class="width_925">
  <h2 class="mt50 mb20" style="padding-bottom:20px;">
	  <span><?=lang('my_pickup')?></span>
	  <span class="shenqingbtn"><a href="/<?=$puri?>/student/student/pickuplist"><?=lang('come_back')?></a></span>
  </h2>
  <div class="center_geren">
    <div class="xiugaimima">
     <form class="form-signin" role="form"  id="myform" name="myform" action="/student/student/do_pickup" method="post">
      <ul>
        <li class="font22"><?=lang('my_pickup')?></li>
		 <li class="mg_b_20">
		  <span><?php if($puri == 'en'){?>
		  
		  <?=!empty($major_info->englishname)?$major_info->englishname:''?>
		  <?php }else{?>
		   <?=!empty($major_info->name)?$major_info->name:''?>		  
		  <?php }?>
		  </span>
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		  <input class="tongyong width_322" type="text" placeholder="<?=lang('pickup_name')?>" validate="required:true"  id="name" name="name" value="<?=!empty($userinfo['enname'])?$userinfo['enname']:''?>">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
          <input class="tongyong width_322" type="text" placeholder="<?=lang('pickup_email')?>" id="email" name="email" validate="required:true" value="<?=!empty($userinfo['email'])?$userinfo['email']:''?>">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
          <input type="radio" class="radio2" validate="required:true"    id="sex" name="sex" value="1" <?=!empty($userinfo['sex']) && $userinfo['sex'] == 1?'checked':''?>> <?=lang('pickup_sex1')?>	
					
		<input type="radio" class="radio2"   id="sex" name="sex" value="2" <?=!empty($userinfo['sex']) && $userinfo['sex'] == 2?'checked':''?>> <?=lang('pickup_sex2')?>
          <div class="sign-up-right"></div>
        </li>
		<li class="mg_b_20">
          <select name="nationality" id="nationality" validate="required:true">
          	<?php if($nationality){
			foreach($nationality as $k => $v){
			?>
			<option value="<?=$k?>" <?=!empty($userinfo['nationality']) && $userinfo['nationality'] == $k?'selected':''?>><?=$v?></option>
			<?php }}?>
          </select>
          <div class="sign-up-right"></div>
        </li>
         
        <li class="mg_b_20">
		  <input class="tongyong width_322" type="text" value="<?=lang('pickup_flightno')?>"  id="flightno" name="flightno" style="color:#999" onfocus="if(this.value=='<?=lang('pickup_flightno')?>'){this.value=''};this.style.color='#333';" onblur="if(this.value==''||this.value=='<?=lang('pickup_flightno')?>'){this.value='<?=lang('pickup_flightno')?>';this.style.color='#333';}" validate="required:true">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		  <input class="tongyong width_322" type="text" value="<?=lang('pickup_fairport')?>"  id="fairport" name="fairport" style="color:#999" onfocus="if(this.value=='<?=lang('pickup_fairport')?>'){this.value=''};this.style.color='#333';" onblur="if(this.value==''||this.value=='<?=lang('pickup_fairport')?>'){this.value='<?=lang('pickup_fairport')?>';this.style.color='#333';}" validate="required:true">
          <div class="sign-up-right"></div>
        </li>
         <li class="mg_b_20">
		  <input class="tongyong width_322" type="text" value="<?=lang('pickup_tairport')?>"  id="tairport" name="tairport" style="color:#999" onfocus="if(this.value=='<?=lang('pickup_tairport')?>'){this.value=''};this.style.color='#333';" onblur="if(this.value==''||this.value=='<?=lang('pickup_tairport')?>'){this.value='<?=lang('pickup_tairport')?>';this.style.color='#333';}" validate="required:true">
          <div class="sign-up-right"></div>
        </li>
		 <li class="mg_b_20">
		  <input class="tongyong width_322" type="text" value="<?=lang('pickup_arrivetime')?>"  id="arrivetime" name="arrivetime" style="color:#999" onfocus="if(this.value=='<?=lang('pickup_arrivetime')?>'){this.value=''};this.style.color='#333';" onblur="if(this.value==''||this.value=='<?=lang('pickup_arrivetime')?>'){this.value='<?=lang('pickup_arrivetime')?>';this.style.color='#333';}" class="Wdate" validate="required:true" onClick="WdatePicker({dateFmt:'yyyy-MM-dd H:m'})">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		  <input class="tongyong width_322" type="text" value="<?=lang('pickup_numbers')?>"  id="numbers" name="numbers" style="color:#999" onfocus="if(this.value=='<?=lang('pickup_numbers')?>'){this.value=''};this.style.color='#333';" onblur="if(this.value==''||this.value=='<?=lang('pickup_numbers')?>'){this.value='<?=lang('pickup_numbers')?>';this.style.color='#333';}" validate="required:true,number:true ">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		  <input class="tongyong width_322" type="text" value="<?=lang('pickup_mobile')?>"  onfocus="if(this.value=='<?=lang('pickup_mobile')?>'){this.value=''};this.style.color='#333';" style="color:#999" onblur="if(this.value==''||this.value=='<?=lang('pickup_mobile')?>'){this.value='<?=lang('pickup_mobile')?>';this.style.color='#333';}" id="mobile" name="mobile"  validate="required:true">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		  <input class="tongyong width_322" type="text" value="<?=lang('pickup_tel')?>" onfocus="if(this.value=='<?=lang('pickup_tel')?>'){this.value=''};this.style.color='#333';" style="color:#999" onblur="if(this.value==''||this.value=='<?=lang('pickup_tel')?>'){this.value='<?=lang('pickup_tel')?>';this.style.color='#333';}"  id="tel" name="tel" validate="required:true">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		  <input class="tongyong width_322" type="text" value="<?=lang('pickup_schoolname')?>" onfocus="if(this.value=='<?=lang('pickup_schoolname')?>'){this.value=''};this.style.color='#333';" style="color:#999" onblur="if(this.value==''||this.value=='<?=lang('pickup_schoolname')?>'){this.value='<?=lang('pickup_schoolname')?>';this.style.color='#333';}" id="schoolname" name="schoolname" validate="required:true">
          <div class="sign-up-right"></div>
        </li>
		<li class="mg_b_20">
          <select name="cityid" id="cityid" validate="required:true" onchange="city_fees()">
		  <option value=""><?=lang('pickup_city')?></option>
          	<?php if($city){
				unset($city[99999]);
			foreach($city as $k => $v){
				
			?>
			<option value="<?=$k?>"><?=$v?></option>
			<?php }}?>
          </select>
          <div class="sign-up-right"></div>
        </li>
		<li class="mg_b_20" id="shanghai" style="display:none;">
          
        </li>
		<li class="mg_b_20" id="city_fees_value" style="display:none;">
          <input type="hidden" class="tongyong width_322"  id="fees" name="fees" value="" > RMB
			<span id="fees_show"></span>
			
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		  <textarea id="remark" name="remark"  style="color:#999;padding: 7px;font-size:12px;" value="<?=lang('pickup_remark')?>" onfocus="if(this.value=='<?=lang('pickup_remark')?>'){this.value=''};this.style.color='#333';" onblur="if(this.value==''||this.value=='<?=lang('pickup_remark')?>'){this.value='<?=lang('pickup_remark')?>';this.style.color='#333';}"><?=lang('pickup_remark')?></textarea>	
          <div class="sign-up-right"></div>
        </li>

        <li>
        <input type="hidden" name="id" value="<?=!empty($info['id'])?$info['id']:''?>">
          <input class="login-btn" type="submit" name="" value=" <?=lang('submit')?> "/>
        </li>
       
      </ul>
    </form>
  </div>
  </div>
</div>
<script>

function city_fees(){
	var cityid = $('#cityid').val();
	var arrivetime = $('#arrivetime').val();
	
	if(cityid == 4){
		$('#shanghai').show();
		var html = '<input type="radio" class="radio2" validate="required:true"    id="bustype" name="bustype" value="1" onClick="city_type_fee(4)"> <?=lang('pickup_bustype1')?><input type="radio" class="radio2" validate="required:true"    id="bustype" name="bustype" value="2" onClick="city_type_fee(5)"> <?=lang('pickup_bustype2')?><div class="sign-up-right"></div>';
		$('#shanghai').html('');
		$('#shanghai').html(html);
	}else{
		$('#shanghai').hide();
		$('#shanghai').html('');
		$.ajax({
			type:'GET',
			url:'/<?=$puri?>/student/student/pickup_fees?type='+cityid+'&arrivetime='+arrivetime,
			success:function(r){
				if(r.state == 2){
					var d = dialog({
					content: ''+r.info+''
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 2000);
					window.location.reload();
				}else{
					$('#city_fees_value').show();
					$('#fees').val(r.data);
					$('#fees_show').html(r.data);
				}
			},
			dataType:'json'
		});
		
	}
}

function city_type_fee(cityid){
	var arrivetime = $('#arrivetime').val();
		$.ajax({
			type:'GET',
			url:'/<?=$puri?>/student/student/pickup_fees?type='+cityid+'&arrivetime='+arrivetime,
			success:function(r){
				if(r.state == 2){
					var d = dialog({
					content: ''+r.info+''
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 2000);
					window.location.reload();
				}else{
					$('#city_fees_value').show();
					$('#fees').val(r.data);
					$('#fees_show').html(r.data);
				}
			},
			dataType:'json'
		});
}

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
				dialog({id:'cucasdialog'}).close();
			if(msg.state == 1){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				window.location.href=msg.data;
			}else if(msg.state == 0){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
			}
		},
		dataType:'json'
	
	});
	
	

});
</script>
<?php $this->load->view('student/footer.php')?>