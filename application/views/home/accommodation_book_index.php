<?php $this->load->view('public/header.php')?>
<?php $this->load->view('public/js_My97DatePicker')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<?php 
	$uri2 = $this->uri->segment(2);
?>
<div class="width_1024 clearfix mg-t-50">

	<div class="three-left-nav">
		<ul>
		<li <?=!empty($uri2) && $uri2 == 'accommodation_introduce'?'class="selected"':''?>>
			<a href="/<?=$puri?>/accommodation_introduce"><?=lang('nav_92')?></a>
		</li>
		<li <?=!empty($uri2) && $uri2 == 'accommodation_book'?'class="selected"':''?>>
			<a href="/<?=$puri?>/accommodation_book"><?=lang('nav_94')?></a>
		</li>
		
		
		</ul>
	</div>
	<div class="pickup-main">
	<div class="crumbs-nav mb30"><a href="/<?=$puri?>/"><?=lang('nav_1')?></a><i> / </i><a href="/<?=$puri?>/accommodation"><?=lang('nav_67')?></a><i> / </i><span><?=lang('nav_94')?></span></div>
		<div class="borderbox">
			<div class="changebox">
				<?php if(empty($userinfo)){?>
				<h2 class="tithome"><?=lang('accommodation_login_tips')?></h2>
				<div class="attentionwrap">
					<h3><?=lang('accommodation_login_notice')?></h3>
					<p><?=lang('accommodation_login_content')?></p>
					  <input class="login-btn" name="" value="<?=lang('login')?>" onclick="login_accommodeation()">
				</div>
				<?php }else{?>
					<?php if($flag_accommodation == 0){?>
						<div class="attentionwrap">
						<h3><?=lang('accommodation_login_notice')?></h3>
						<p class="settext"><?=lang('accommodation_student')?></p>
						<input class="login-btn" name="" value="<?=lang('online_apply')?>" onclick="apply_now_accommodation()">
						</div>
					<?php }else{?>
						<div class="zhuce_cont">
						<h3 class="reg_titel"><?=lang('accommodation_login_book')?></h3>
						<div class="zc_wrap">
							<div class="xiugaimima">
							 <form class="form-signin" role="form" id="myform" name="myform" action="/<?=$puri?>/accommodation_book/save" method="post">
							 	<?php if(!empty($zyj_data)){?>
							 	<div id="zyj_lj">
								 	<p><?=lang('dqsqxxw')?>：<?=$zyj_data['zyj_room']?>
								 	<span><a href="javascript:;" onclick="dqsqxxw_edit()"><?=lang('dqsqxxw_edit')?></a></span></p>
							    </div>
							 	<?php }?>
							  <ul>
								   <li class="mg_b_20">
									  <?=!empty($userinfo['email'])?$userinfo['email']:''?>
									  <div class="sign-up-right"></div>
									</li>
							    <li class="mg_b_20">
								  <input class="tongyong width_322" type="text" placeholder="<?=lang('pickup_name')?>" validate="required:true"  id="name" name="name" value="<?=!empty($userinfo['enname'])?$userinfo['enname']:''?>">
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
								<?php if(empty($zyj_data)){?>
								<div id="CUCAS_a">
									<li class="mg_b_20">
									  <select name="campid" id="campid" validate="required:true" onchange="select_building()">
										<option value="">--Please select--</option>
										<?php if($camp){
										foreach($camp as $k => $v){
										?>
										<option value="<?=$k?>"><?=$v?></option>
										<?php }}?>
									  </select>
									  <div class="sign-up-right"></div>
									</li>
									<li class="mg_b_20">
									  <select name="buildingid" id="buildingid" validate="required:true" onchange="select_room()">
										<option value="">--Please select--</option>
										
									  </select>
									  <div class="sign-up-right"></div>
									</li>
									<li class="mg_b_20">
									  <select name="type" id="type" validate="required:true" onchange="select_info()">
										<option value="">--Please select--</option>
										
									  </select>
									  <div id="acc_info"></div>
									  <div class="sign-up-right"></div>
									</li>

								</div>
								<?php }else{?>
									<div id="zyjglj"></div>
								<?php }?>
									<?php if(!empty($zyj_data)){?>
									<div id="zyj_acc">
											<input type="hidden" name="campid" value="<?=$zyj_data['campid']?>">
												<input type="hidden" name="buildingid" value="<?=$zyj_data['buildingid']?>">
													<input type="hidden" name="type" value="<?=$zyj_data['type']?>">

									</div>

									<?php }?>

								<li class="mg_b_20">
								  <input class="tongyong width_322 valid" value="<?=lang('myacc_time')?>" onfocus="if(this.value=='<?=lang('myacc_time')?>'){this.value=''};this.style.color='#333';" style="color:#999" onblur="if(this.value==''||this.value=='<?=lang('myacc_time')?>'){this.value='<?=lang('myacc_time')?>';this.style.color='#333';}"  id="accstarttime" name="accstarttime"  validate="required:true" onclick="WdatePicker({dateFmt:'yyyy-MM-dd'})" type="text">
								  <div class="sign-up-right"></div>
								</li>
								
								
								<li class="mg_b_20">
								  <input class="tongyong width_322" value="<?=lang('myacc_sc')?>" onfocus="if(this.value=='<?=lang('myacc_sc')?>'){this.value=''};this.style.color='#333';" style="color:#999" onblur="if(this.value==''||this.value=='<?=lang('myacc_sc')?>'){this.value='<?=lang('myacc_sc')?>';this.style.color='#333';}"  id="accendtime" name="accendtime" validate="required:true" type="text">
								  <div class="sign-up-right"></div>
								</li>
								<li class="mg_b_20">
								  <font class="setmaer"><?=lang('myacc_tj')?>:</font><input class="radio2" validate="required:true" id="tj" name="tj" value="1" type="radio"><?=lang('myacc_yes')?>	
											
								<input class="radio2" id="tj" name="tj" value="2" type="radio"><?=lang('myacc_no')?><div class="sign-up-right"></div>
								</li>
								<li class="mg_b_20">
								  <textarea id="remark" name="remark" style="color:#999;padding: 7px;font-size:12px;" onfocus="if(this.value=='<?=lang('pickup_remark')?>'){this.value=''};this.style.color='#333';" onblur="if(this.value==''||this.value=='<?=lang('pickup_remark')?>'){this.value='<?=lang('pickup_remark')?>';this.style.color='#333';}" ><?=lang('pickup_remark')?></textarea>	
								  <div class="sign-up-right"></div>
								</li>
								<li>
								  <input class="login-btn" name="" value=" Submit " type="submit">
								</li>
							  </ul>
							  </form>
							</div>
						</div>
					</div>
					
					<?php }?>
					
					<?php }?>
			</div>
				
		</div>
	</div>
</div>
<script type="text/javascript">
function dqsqxxw_edit(){

	$('#zyj_lj').hide('slow').remove();
	$('#zyj_acc').hide('slow').remove();
	var html = '<li class="mg_b_20"><select name="campid" id="campid" validate="required:true" onchange="select_building()">'
			+'<option value="">--Please select--</option><?php if($camp){ foreach($camp as $k => $v){ ?><option value="<?=$k?>"><?=$v?></option><?php }}?></select>'
			+'<div class="sign-up-right"></div></li><li class="mg_b_20">'
			+'<select name="buildingid" id="buildingid" validate="required:true" onchange="select_room()">'
			+'<option value="">--Please select--</option></select> <div class="sign-up-right"></div></li>'
			+'<li class="mg_b_20"> <select name="type" id="type" validate="required:true" onchange="select_info()"><option value="">--Please select--</option> </select><div id="acc_info"></div><div class="sign-up-right"></div></li>';

							
			$('#zyjglj').html(html);

}

</script>


<script type="text/javascript">
	function login_accommodeation(){
		$.ajax({
			beforeSend:function(){
				var d = dialog({
						content: '<img src="<?=RES?>home/images/public/loading.gif">'
					});
					d.showModal();
					
			},
			type:'GET',
			url:'/<?=$puri?>/accommodation_book/login_accommodeation',
			success:function(r){
				if (r.state == 1) {
					var d = dialog({
							content:r.data
					});
					d.showModal();
				}
			},
			dataType:'json'

		});
	}
</script>
<script type="text/javascript">
	function select_building(){
		var campid = $('#campid').val();
		if(campid != ''){
			$.ajax({
				type:'GET',
				url:'/<?=$puri?>/accommodation_book/select_building?campid='+campid,
				success:function(r){
					if(r.state == 1){
						$('#buildingid').html(r.data);
					}
				},
				dataType:'json'
			
			
			});
		}else{
			return false;
		}
	}
	
	function select_room(){
		var buildingid = $('#buildingid').val();
		if(buildingid != ''){
			$.ajax({
				type:'GET',
				url:'/<?=$puri?>/accommodation_book/select_room?buildingid='+buildingid,
				success:function(r){
					if(r.state == 1){
						$('#type').html(r.data);
					}
				},
				dataType:'json'
			
			
			});
		}else{
			return false;
		}
	}
	
	function select_info(){
		var campid = $('#campid').val();
		var buildingid = $('#buildingid').val();
		var type = $('#type').val();
		if(buildingid != '' && campid != '' && type != ''){
			$.ajax({
				type:'GET',
				url:'/<?=$puri?>/accommodation_book/select_info?buildingid='+buildingid+'&campid='+campid+'&type='+type,
				success:function(r){
					if(r.state == 1){
						$('#acc_info').html('');
						$('#acc_info').html(r.data);
					}
				},
				dataType:'json'
			
			
			});
		}else{
			$('#acc_info').html('');
			return false;
		}
		
	}
</script>
<script>

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
				
				window.location.href=msg.data;
			}else if(msg.state == 0){
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
});
</script>
<script type="text/javascript">

	function apply_now_accommodation(){
		dialog({
		    title: '<?=lang('tsxx')?>',
			content:'<?=lang('apply_tips')?>',
		    ok: function () {
		    	window.location.href='/<?=$puri?>/course?programaid=0';
		    },
		    cancel: function () {
		       window.location.reload();
		    }
		}).show();
		
	}
</script>
<?php $this->load->view('public/footer.php')?>
