<?php $this->load->view('student/header.php')?>
<?php if($temp == 1){?>
    <?php if($flag == 0){?>
        <div class="find-password-main width_925 clearfix p50">
        <div class="success-main-container-1"> <span class="gongxi"><?=lang('sorry')?><?=$email?><?=lang('no_exist')?>!</span>
        </div>
      
      </div>
      <div class="width_925 contact-shadow min"></div>

    <?php }else{?>
        <div class="find-password-main width_925 clearfix p50">
        <div class="success-main-container-1"> <span class="gongxi"><?=lang('hi')?></span>
          <p class="mg-t-10"><?=lang('fpassword_1')?><?=$email?><?=lang('fpassword_2')?></p>
          <a class="success-btn" href="<?=$email_url?>" target="_blank"><?=lang('ljdl')?></a> </div>
        <div class="success-main-container-2">
        
          <p class=" font-12"><?=lang('fpassword_3')?> <i class="xiahuaxian"></i> <?=lang('fpassword_4')?>: <?=$email?>,<?=lang('fpassword_9')?><a href="javascript:;" class="replay" onclick="replay('<?=$email?>')"> <?=lang('fpassword_5')?> </a></p>
        </div>
      </div>
      <div class="width_925 contact-shadow min"></div>

    <?php }?>

<?php }else{?>
<div class="width_925 find-password-main clearfix">
  <div class="find-password-title"><?=lang('zhmm')?></div>
  <div class="find-password-container">
    <div class="find-password-form">
  <form class="form-signin" role="form"  id="myform" name="myform" action="/<?=$puri?>/student/login/docpassword" method="post">
        <ul>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="password" placeholder="<?=lang('fpassword_6')?>"  id="password" name="password" validate="required:true,minlength:6" value="">
          </li>
         <li  class="mg_b_20">
             <input type="password" class="tongyong width_322" placeholder="<?=lang('fpassword_7')?>"  validate="required:true,minlength:6,equalTo:'#password'"  id="repassword" name="repassword">
      <input type="hidden" name="uid" value="<?=$uid?>">
          </li>
          
          <li class="mg_20" style="padding-bottom:0;">
            <input class="login-btn" type="submit" name="findpassword" value="<?=lang('fpassword_8')?>"/>
          </li>
        </ul>
      </form>
      </div>
    </div>
  </div>

<?php }?>


<script>
var  d = '';
function replay(email){
  $.ajax({
    beforeSend:function(){
      d = dialog({
          content: '<img src="<?=RES?>home/images/public/loading.gif">'
        });
      d.showModal();
        
    },
    url: '/<?=$puri?>/student/login/cucasemail?email='+email,
    type: 'GET',
    success:function(msg){
        if(msg.state == 1){
        d.close().remove();
        d = dialog({
          content: ' <?=lang('fscg')?>'
        });
        d.show();
        setTimeout(function () {
          d.close().remove();
        }, 2000);
        
      }else if(msg.state == 2){
         d = dialog({
          content: '<?=lang('fssb')?>'
        });
        d.show();
        setTimeout(function () {
          d.close().remove();
        }, 2000);
      }
    },
    dataType: 'json'
  });

}
</script>
<script type="text/javascript">
  
    $('#myform').ajaxForm({
    beforeSend:function(){
      d = dialog({
          content: '<img src="<?=RES?>home/images/public/loading.gif">'
        });
      d.showModal();
    },
    success:function(msg){
      if(msg.state == 1){
         d = dialog({
          content: ' 修改成功！'
        });
        d.show();
        setTimeout(function () {
          d.close().remove();
        }, 2000);
        window.location.href='/<?=$puri?>/student/login';
      }else if(msg.state ==0){
        d = dialog({
          content: ' 修改失败！'
        });
        d.show();
        setTimeout(function () {
          d.close().remove();
        }, 2000);
        window.location.reload();
      }
    },
    dataType:'json'
  
  });
</script>
<?php $this->load->view('student/footer.php')?>