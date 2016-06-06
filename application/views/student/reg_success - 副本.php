<?php $this->load->view('student/header.php')?>
<div class="find-password-main width_925 clearfix p50">
  <div class="success-main-container-1"> <span class="gongxi">恭喜，<?=!empty($email)?$email:''?> 已注册成功!</span>
  <p class="mg-t-10">验证邮箱的好处：XXXXXX</p>
    <a class="success-btn" href="<?=!empty($email_url)?$email_url:''?>" target="_blank">立即验证</a> </div>
  <div class="success-main-container-2">
    <p class="mg_b_20"><strong>超过80%的用户选择了<a class="mail-now" href="<?=!empty($email_url)?$email_url:''?>" target="_blank">立即验证邮箱</a></strong></p>
    <p class=" font-12">系统已向您的邮箱 <i class="xiahuaxian"><?=!empty($email)?$email:''?></i> 发送了一封验证邮件，请您登录邮箱，点击邮件中的链接完成邮箱验证。如果您超过2分钟未收到邮件，您可以<a href="javascript:;" class="replay" onclick="replay('<?=!empty($email)?$email:''?>')">重新发送</a></p>
  </div>
</div>
<div class="width_925 contact-shadow min"></div>
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
		url: '/student/reg/resendemail?email='+email,
		type: 'GET',
		success:function(msg){
				if(msg.state == 1){
				d.close().remove();
				d = dialog({
					content: ' 发送成功！'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				
			}else if(msg.state == 2){
				 d = dialog({
					content: '发送失败！'
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
<?php $this->load->view('student/footer.php')?>
