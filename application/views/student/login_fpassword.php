<?php $this->load->view('student/header.php')?>
<div class="width_925 find-password-main clearfix">
  <div class="find-password-title"><?=lang('zhmm')?></div>
  <div class="find-password-container">
    <div class="find-password-form setcent">
        <form class="form-signin" role="form" id="myform" name="myform" action="/<?=$puri?>/student/login/fpassword" method="post">
        <ul>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=lang('qsrzcyx')?>" onfocus="if(this.value=='<?=lang('qsrzcyx')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('qsrzcyx')?>'){this.value='<?=lang('qsrzcyx')?>';}"  validate="required:true,email:true,remote:'/<?=$puri?>/student/login/checkemail'" id="email" name="email">
          </li>
         <li  class="mg_b_20" style="width:289px;">
            <input class="tongyong width_180" type="text" maxlength="30" id="code"  name="code" style="margin-right: 139px;" validate="required:true,remote:'/<?=$puri?>/student/reg/checkcode'"   value="<?=lang('code')?>" onfocus="if(this.value=='<?=lang('code')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('code')?>'){this.value='<?=lang('code')?>';}" >
          </li>
          <li class="mg_b_20 change"><img src="/<?=$puri?>/student/reg/verify?<?=time()?>" alt="<?=lang('code')?>" class='retina-ready' width="150" height="32" title="<?=lang('change_code')?>" onclick="$(this).attr('src','/<?=$puri?>/student/reg/verify?'+ new Date().getTime())" id="virimg"><a onclick="$('#virimg').attr('src','/<?=$puri?>/student/reg/verify?'+ new Date().getTime())" style="display:inline-block; float:left; margin-left:10px; cursor:pointer;">
            <?=lang('code_nosee')?>
            </a> 
          <li class="mg_20" style="margin-top:57px;">
           <input class="login-btn" type="submit" name="findpassword" value="<?=lang('djzhmm')?>" onclick="javascript:this.value='<?=lang('process')?>'"/> </li>
          </li>
        </ul>
      </form>
    </div>
  </div>
</div>
<div class="width_925 contact-shadow min"></div>
<script type="text/javascript">
$(function(){
  $('#virimg').attr('src','/<?=$puri?>/student/reg/verify?'+ new Date().getTime());
});

 
</script>
<?php $this->load->view('student/footer.php')?>
