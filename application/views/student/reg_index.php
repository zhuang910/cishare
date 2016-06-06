<?php $this->load->view('student/header.php')?>
<div class="width_602 sign-up-main clearfix">
  <div class="sign-up-form">
     <form class="form-signin" role="form"  id="myform" name="myform" action="/<?=$puri?>/student/reg/" method="post">
      <ul>
        <li class="urestitle_font"><?=lang('register')?></li>
        <li class="mg_b_20">
          <input class="tongyong width_322" onfocus="if(this.value=='<?=lang('email')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('email')?>'){this.value='<?=lang('email')?>';}" type="text"  value="<?=lang('email')?>" validate="required:true,email:true,remote:'/<?=$puri?>/student/reg/checkemail'"  id="email" name="email">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		<input type="text" value="<?=lang('password')?>" class="hideinput2 tongyong width_322" onfocus="$('.hideinput').show();$(this).hide();$('.hideinput').focus();"/>
          <input class="tongyong width_322 hideinput"style="display:none;" onfocus="if(this.value=='<?=lang('password')?>'){this.value='';};" onblur="if(this.value==''||this.value=='<?=lang('password')?>'){this.value='<?=lang('password')?>';$('.hideinput2').show();$('.hideinput').hide();}" id="password" name="password"  type="password" validate="required:true,minlength:6" >
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
		<input type="text" value="<?=lang('repassword')?>" class="hideinput3 tongyong width_322" onfocus="$('.hideinput4').show();$(this).hide();$('.hideinput4').focus();"/>
          <input class="tongyong width_322 hideinput4" style="display:none;" onfocus="if(this.value=='<?=lang('repassword')?>'){this.value='';};" onblur="if(this.value==''||this.value=='<?=lang('repassword')?>'){this.value='<?=lang('repassword')?>';$('.hideinput3').show();$('.hideinput4').hide();}"  id="repassowrd" type="password"  validate="required:true,minlength:6,equalTo:'#password'"  name="repassowrd">
          <div class="sign-up-right"></div>
        </li>
        <li class="mg_b_20">
			  <select class="tongyong width_322"  validate="required:true" id="nationality" name="nationality" >
				  
				  <?php 
					if(!empty($nationality)){
						foreach($nationality as $k => $v){
				?>
				  <option value="<?=$k?>">
				  <?=$v?>
				  </option>
              <?php }}?>
            </select>
          <div class="sign-up-right"></div>
        </li>
		<li>
            <input class="tongyong width_322" onfocus="if(this.value=='<?=lang('code')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('code')?>'){this.value='<?=lang('code')?>';}" value="<?=lang('code')?>"  type="text" maxlength="30" id="code"  name="code" validate="required:true,remote:'/student/reg/checkcode'" >
          </li>
          <li class="Change2" style=" margin:20px 0; clear:both; height:30px; line-height:30px;"><span style="float:left;"><img src="/student/reg/verify?<?=time()?>" alt="<?=lang('code')?>" class='retina-ready' width="150" height="32" title="<?=lang('change_code')?>" onclick="$(this).attr('src','/student/reg/verify?'+ new Date().getTime())" id="virimg" style="border:1px solid #d7d7d7;"> </span>
              <a onclick="$('#virimg').attr('src','/student/reg/verify?'+ new Date().getTime())" style="display:inline-block; float:left; margin-left:10px; cursor:pointer;" style="
              float:left;">
		        <?=lang('code_nosee')?>
              </a>
          </li>
       <!-- <li class="mg_20">
          <label class="login-remember  txt-align-left mg_l_50">
            <input type="checkbox" class="self-checkbox"/>
            <span>我已阅读并同意《北信用户注册协议》</span> </label>
        </li>-->
        <li>
          <input class="login-btn" type="submit" name="submit" value=" <?=lang('register')?> "/>
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
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
				
		},
		success:function(msg){
			
			if(msg.state == 1){
				/*var d = dialog({
					content: '注册成功！'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);*/
				window.location.href="/<?=$puri?>/student/reg/success?email="+msg.info;
			}else if(msg.state == 2){
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
	
	$('#virimg').attr('src','/student/reg/verify?'+ new Date().getTime());

});


</script>
<?php $this->load->view('student/footer.php')?>
