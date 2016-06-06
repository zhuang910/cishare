<?php $this->load->view('student/header.php')?>
<div class="find-password-main width_925 clearfix p50">
  <div class="success-main-container-1"> <span class="gongxi"><?=lang('gongxi')?> <?=lang('yjhcg')?>!</span>
  <p class="mg-t-10"><?=lang('yzyxdhc')?>:<?=lang('email_advantage')?></p>
    <a class="success-btn" href="<?=!empty($email_url)?$email_url:''?>" target="_blank"><?=lang('ljdl')?></a> </div>
  <div class="success-main-container-2">
    <p class="mg_b_20"><strong><?=lang('zyj_1')?><a class="mail-now" href="<?=!empty($email_url)?$email_url:''?>" target="_blank"><?=lang('zyj_2')?></a></strong></p>
    <p class=" font-12"><?=lang('zyj_3')?> <i class="xiahuaxian"></i> <?=lang('zyj_4')?>: <?=!empty($email)?$email:''?><?=lang('zyj_5')?><a href="javascript:;" class="replay" onclick="replay('<?=!empty($email)?$email:''?>')"><?=lang('ljyz')?></a></p>
  </div>
</div>
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
		url: '/<?=$puri?>/student/reg/resendemail?email='+email,
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
					content: ' <?=lang('fssb')?>'
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
<?php $this->load->view('student/footer_no.php')?>
