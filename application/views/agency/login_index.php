<?//php $this->load->view('header_my.php')?>
<script src="<?=WEBJS?>jquery.min.js"></script>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<div class="reg_w pc_mt40">
  <h2><?=lang('login')?></h2>
  <div class="login_box clearfix pc_mt40">
    <div class="login_input float_l">
      <div style="display:<?=!empty($error)?'block':'none'?>"><font color='red'>
        <?=!empty($error)?$error:''?>
        </font></div>
      <form class="form-signin" role="form"  id="myform" name="myform" action="" method="post">
        <ul class="QXForm">
          <li>
            <input type="text" class="form-control" placeholder="<?=lang('email')?>" validate="required:true,email:true"  id="email" name="email" value="">
          </li>
          <li>
            <input id="password" name="password" class="input-xlarge" type="password" placeholder="<?=lang('password')?>" validate="required:true" autocomplete="off"  value="">
          </li>
          <li>
            <input class="form-control" type="text" maxlength="30" id="code"  name="code" validate="required:true,remote:'/agency/reg/checkcode'"   value="" placeholder="<?=lang('code')?> ">
          </li>
          <li class="Check"> <img src="/agency/reg/verify?<?=time()?>" alt="验证码" class='retina-ready' width="150" height="32" title="<?=lang('change_code')?>" onclick="$(this).attr('src','/agency/reg/verify?'+ new Date().getTime())" id="virimg"> <a onclick="$('#virimg').attr('src','/agency/reg/verify?'+ new Date().getTime())" style="display:inline-block; float:left; margin-left:10px; cursor:pointer;">
            <?=lang('code_nosee')?>
            </a> </li>
          <li class="Check">
            <!--<span><input name="" type="checkbox" value="" / >
            <?=lang('auto_login')?>
            </span> <span class="color_7">|</span>--> <span><a href="/agency/login/fpassword">
            <?=lang('forget_password')?>
            </a></span></li>
          <li class="login_btn">
            <input type="submit" value="<?=lang('login')?>">
          </li>
        </ul>
      </form>
    </div>
  </div>
</div>
<script>
var msg_win = '';
$(function(){
	$('#myform').ajaxForm({
		beforeSend:function(){
			 msg_win = art.dialog({
					id:'msg',
					content:'<img src="<?=WEBIMG?>public/loading.gif">',
					lock:true,
					opacity:0.1,
					cancel:false
				});
		},
		success:function(msg){
			msg_win.close();
			if(msg.state == 0){
				art.dialog.tips(msg.info);
				field = msg.data.field;

				var input = $('.QXForm').find("input[name='"+field+"']");

				input.css({border:"1px solid #FF0000"}).focus().blur(function(){

					$(this).css({border:""});

				});
			}else if(msg.state == 1){
				art.dialog.tips(msg.info);
        if(msg.data != ''){
            window.location.href=msg.data;
        }else{
          window.location.href='/agency';
        }
				
			}
		},
		dataType:'json'
	
	});
	
	$('#virimg').attr('src','/agency/reg/verify?'+ new Date().getTime());

});

</script>
<?//php $this->load->view('footer.php')?>
