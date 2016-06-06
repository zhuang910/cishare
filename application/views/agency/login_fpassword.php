<?//php $this->load->view('header_my.php')?>
<script src="<?=WEBJS?>jquery.min.js"></script>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<div class="Cipher pc_mt40">
  <h2><?=lang('forget_password')?></h2>
  <div class="login_box clearfix pc_mt40">
    <div class="login_input pc_center float_l">
    <form class="form-signin" role="form" id="myform" name="myform" action="/agency/login/fpassword" method="post">
      <ul>
        <li class="input_con">
          <input type="text" class="form-control" placeholder="Enter Your Email Address" validate="required:true,email:true,remote:'/agency/login/checkemail'" id="email" name="email" style='line-height:0px;width:97%;float:left;'>
        </li>
		<li style="height:0px;">
        </li>
        <li class="login_btn">
		    <div class="button">
		        <a href="javascript:;" onclick="sub()" class="White">tijiao</a>
			</div>
		</li>
      </ul>
      </form>
    </div>
  </div>
</div>

<script>
function sub () {
  $('#myform').submit();
}
</script>
<?//php $this->load->view('footer.php')?>
