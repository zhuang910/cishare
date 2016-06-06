<?php $this->load->view('society/headermy.php')?>
<div class="width_925">
  <h2 class="mt50 mb20"><span><?=lang('edit_password')?></span></h2>
  <div class="center_geren">
    <div class="xiugaimima">
     <form class="form-signin" role="form"  id="myform" name="myform" action="/society/society/do_editpassword" method="post">
      <ul>
        <li class="font22"><?=lang('edit_password')?></li>
        <li class="mg_b_20">
			<input type="text" value="<?=lang('old_password')?>" class="tongyong width_322 titinput1" onfocus="$('.titinput11').show();$('.titinput1').hide();$('.titinput11').focus();"/>
		  <input class="tongyong width_322 titinput11" style="display:none;" type="password" value="" validate="required:true,remote:'/society/society/checkpassword'"  id="oldpassword" name="oldpassword" onfocus="if(this.value=='<?=lang('old_password')?>'){this.value='';};" onblur="if(this.value==''||this.value=='<?=lang('old_password')?>'){this.value='<?=lang('old_password')?>';$('.titinput1').show();$('.titinput11').hide();}">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		<input type="text" value="<?=lang('password')?>" class="tongyong width_322 titinput2" onfocus="$('.titinput22').show();$('.titinput2').hide();$('.titinput22').focus();"/>
          <input class="tongyong width_322 titinput22" type="password" style="display:none;" value="" id="password" name="password" validate="required:true,minlength:6" onfocus="if(this.value=='<?=lang('password')?>'){this.value='';};" onblur="if(this.value==''||this.value=='<?=lang('password')?>'){this.value='<?=lang('password')?>';$('.titinput2').show();$('.titinput22').hide();}">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		<input type="text" value="<?=lang('repassword')?>" class="tongyong width_322 titinput3" onfocus="$('.titinput33').show();$('.titinput3').hide();$('.titinput33').focus();"/>
          <input class="tongyong width_322 titinput33" type="password" style="display:none;" value="" onfocus="if(this.value=='<?=lang('repassword')?>'){this.value='';};" onblur="if(this.value==''||this.value=='<?=lang('repassword')?>'){this.value='<?=lang('repassword')?>';$('.titinput3').show();$('.titinput33').hide();}" id="repassword" name="repassword" validate="required:true,minlength:6,equalTo:'#password'"/>
          <div class="sign-up-right"></div>
        </li>
        <li>
          <input class="login-btn" type="submit" name="submit" value=" <?=lang('submit')?> "/>
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