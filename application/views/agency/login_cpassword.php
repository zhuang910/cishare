<?//php $this->load->view('header_my.php')?>
<script src="<?=WEBJS?>jquery.min.js"></script>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<div class="Cipher pc_mt40">
  <h2><?=lang('forget_password')?></h2>
  <div class="login_box clearfix pc_mt40" style="margin-top:20px;">
    <div class="float_l">
    <?php 
      if($temp == 1){
    ?>
      <?php if($flag == 0){?>
       <div class="LogCon">
       <h2><?=lang('user_exist')?></h2>
       </div>
      
      <?php }else{?>
        <div class='ok_tip' style="font-size:16px;">
       <p><?=lang('edit_p1')?>&nbsp;<?=$email?></p> <p><?=lang('edit_p2')?><a href="<?=$email_url?>" target="_blank"> <?=lang('edit_p3')?> </a><?=lang('edit_p4')?></p>
        </div>
      <?php }?>

    <?php }else{?>
    <form class="form-signin" role="form"  id="myform" name="myform" action="/agency/login/docpassword" method="post">
      <ul>
        <li class="input_con">
      <input type="password" class="form-control" placeholder="New Password"    id="password" name="password" validate="required:true,minlength:6" style='line-height:0px;width:97%;float:left;'>
      </li>
	  <li style="height:20px;"></li>
      <li class="input_con">
      <input type="password" class="form-control" placeholder="Confirm Password"  validate="required:true,minlength:6,equalTo:'#password'"  id="repassword" name="repassword" style='line-height:0px;width:97%;float:left;'>
      <input type="hidden" name="uid" value="<?=$uid?>">
      </li>
	  <li style="height:0px;"></li>
        <li class="login_btn">
		    <div class="button">
		        <a href="javascript:;" onclick="sub()" class="White">tijiao</a>
			</div>
		</li>
		
      </ul>
      </form>

    <?php }?>
    </div>
  </div>
</div>

<script>
function sub () {
  $('#myform').submit();
}
  $(function(){
  $('#myform').ajaxForm({
    beforeSend:function(){
      art.dialog({
        id:'msg',
        content:'<img src="<?=WEBIMG?>public/loading.gif">',
        lock:true,
        opacity:0.1,
        cancel:false
      });
    },
    success:function(msg){
      if(msg.state == 1){
        art.dialog.zjjtop({
          content:'success!',
          close:function(){
            window.location.href='/agency/login';
          }
        }).time(3);
      }else if(msg.state ==0){
        art.dialog.zjjtop({
          content:'failure',
          close:function(){
            window.location.reload();
          }
        }).time(3);
      }
    },
    dataType:'json'
  
  });

});
</script>
<?//php $this->load->view('footer.php')?>
