<?php $this->load->view('student/header.php')?>
<div class="width_925 login-main clearfix">
  <div class="f_r login-main-form">
    <div class="form-login">
       <form  id="myform" name="myform" action="/<?=$puri?>/student/login/do_login<?=!empty($_GET['backurl']) ? '?backurl='.$_GET['backurl'] : ''?>" method="post">
        
        <ul class="zyj">
          <li class="urestitle_font"><?=lang('login')?></li>
		  <li class="mg_b_10" style="padding-bottom:20px;">
			<input type="radio" value="1" name="type"  class="radio2" checked id="account_student"/> <label for="account_student"><?=lang('account_student')?></label> 
			<input type="radio" value="2" class="radio2" name="type" id="account_society"/> <label for="account_society"><?=lang('account_society')?></label> 
			
          </li>
          <li class="mg_b_20">
            <input type="text" value="<?=lang('login_flag')?>" onfocus="if(this.value=='<?=lang('login_flag')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('login_flag')?>'){this.value='<?=lang('login_flag')?>';}" class="ft_12" validate="required:true"  id="email" name="email" />
          </li>
          <li class="mg_b_20">
			<input type="text" value="<?=lang('password')?>" class="hideinput2" onfocus="$('.hideinput').show();$('.hideinput2').hide();$('.hideinput').focus();"/>
            <input style="display:none;"  class="hideinput" type="password" onfocus="if(this.value=='<?=lang('password')?>'){this.value='';};" onblur="if(this.value==''||this.value=='<?=lang('password')?>'){this.value='<?=lang('password')?>';$('.hideinput2').show();$('.hideinput').hide();}" class="ft_12" id="password" name="password" validate="required:true" />
          </li>
		   <li  class="mg_b_20">
            <input class="form-ft_12" type="text" maxlength="30" id="code"  name="code" validate="required:true,remote:'/<?=$puri?>/student/reg/checkcode'"    value="<?=lang('code')?>" onfocus="if(this.value=='<?=lang('code')?>'){this.value='';};" onblur="if(this.value==''||this.value=='<?=lang('code')?>'){this.value='<?=lang('code')?>';}">
          </li>
          <li class="Change"> <span><img src="/<?=$puri?>/student/reg/verify?<?=time()?>" alt="<?=lang('code')?>" class='retina-ready' width="150" height="32" title="<?=lang('change_code')?>" onclick="$(this).attr('src','/<?=$puri?>/student/reg/verify?'+ new Date().getTime())" id="virimg"></span><a onclick="$('#virimg').attr('src','/<?=$puri?>/student/reg/verify?'+ new Date().getTime())" style="display:inline-block; float:left; margin-left:10px; cursor:pointer;">
            <?=lang('code_nosee')?>
            </a> </li>
		 
          <li class="mg_b_20 wangjimama">
            <!--<label class="login-remember">
              <input type="checkbox" class="self-checkbox"/>
              <span>自动登录</span></label>
            <span class="inline-xian"></span>--> <a href="/<?=$puri?>/student/login/fpassword" target="_blank" class="login-remember"><?=lang('forget_password')?>?</a> </li>
          <li>
            <input class="login-btn" type="submit" name="submit" value=" <?=lang('login')?> "/ style="border:none;">
          </li>
        </ul>
      </form>
    </div>
  </div>
</div>
<div class="contact-shadow"></div>
<script>
var d = [];
$(function(){
	
	$('#myform').ajaxForm({
		beforeSend:function(){
			 d = dialog({
			 		id:'cucasartdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
		},
		success:function(msg){
			dialog({id:'cucasartdialog'}).close();
			if(msg.state == 0){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				var field = msg.data.field;

				var input = $('.zyj').find("input[name='"+field+"']");

				input.css({border:"1px solid #FF0000"}).focus().blur(function(){

					$(this).css({border:""});

				});
			}else if(msg.state == 1){
				
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				if(msg.data != ''){
					window.location.href=msg.data;
				}else{
				  window.location.href='/<?=$puri?>/student/index';
				}

			}else if(msg.state == 4){
				var d = dialog({
						content:msg.data,
						padding:0
				});
				d.showModal();
			}
		},
		dataType:'json'
	
	});
	
	$('#virimg').attr('src','/student/reg/verify?'+ new Date().getTime());

});

</script>
<?php $this->load->view('student/footer.php')?>
