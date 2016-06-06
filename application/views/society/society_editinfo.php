<?php $this->load->view('society/headermy.php')?>
<?php $this->load->view('public/js_My97DatePicker')?>

<div class="width_925">
  <h2 class="mt50 mb20"><span>
    <?=lang('personal_info')?>
    </span></h2>
  <div class="center_geren">
    <div class="xiugaimima">
      <form class="form-signin" role="form"  id="myform" name="myform" action="/<?=$puri?>/society/society/do_editinfo" method="post">
        <ul>
          <li class="font22">
            <?=lang('personal_info')?>
          </li>
		   <li class="mg_b_20">
           <?=!empty($userinfo['email'])?$userinfo['email']:''?>
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['cnname'])?$userinfo['cnname']:lang('society_cnname')?>"  onfocus="if(this.value=='<?=lang('society_cnname')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('society_cnname')?>'){this.value='<?=lang('society_cnname')?>';}" id="cnname" name="cnname" value="<?=!empty($userinfo['cnname'])?$userinfo['cnname']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['enname'])?$userinfo['enname']:lang('society_enname')?>" onfocus="if(this.value=='<?=lang('society_enname')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('society_enname')?>'){this.value='<?=lang('society_enname')?>';}"   id="enname" name="enname" value="<?=!empty($userinfo['enname'])?$userinfo['enname']:''?>">
            <div class="sign-up-right"></div>
          </li>
        
         
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['mobile'])?$userinfo['mobile']:lang('user_mobile')?>"  onfocus="if(this.value=='<?=lang('user_mobile')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_mobile')?>'){this.value='<?=lang('user_mobile')?>';}"  id="mobile" name="mobile" value="<?=!empty($userinfo['mobile'])?$userinfo['mobile']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['tel'])?$userinfo['tel']:lang('user_tel')?>"  onfocus="if(this.value=='<?=lang('user_tel')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_tel')?>'){this.value='<?=lang('user_tel')?>';}"  id="tel" name="tel" value="<?=!empty($userinfo['tel'])?$userinfo['tel']:''?>">
            <div class="sign-up-right"></div>
          </li>
         
         
          <li>
            <input class="login-btn" type="submit" name="" value=" <?=lang('submit')?> "/>
          </li>
        </ul>
      </form>
    </div>
  </div>
</div>
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
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				window.location.reload();
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
<?php $this->load->view('society/footer.php')?>