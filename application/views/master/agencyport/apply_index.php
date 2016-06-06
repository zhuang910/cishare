<?php $this->load->view('public/css_basic')?>
<?php $this->load->view('public/js_basic')?>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<link href="<?=RES?>home/css/applyonline.css" rel="stylesheet" type="text/css" media="screen">
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=RES?>home/css/chosen.css">
<script src="<?=RES?>home/js/chosen.jquery.min.js"></script>
<div class="width_925 clearfix">
	<?php $this->load->view('/master/agencyport/apply_coursename')?>
</div>
<div class="width_925 clearfix applyonline-main">
	<div class="list_title">
		<?php $this->load->view('/master/agencyport/apply_left')?>
	</div>
	<div class="zjlx">
	<div class="fl_zl">
		
		<?=lang('steps')?>
	</div>
	<div class="fr_sq"><h4><?=lang('apply_method')?></h4>
				<form action="/master/agencyport/fillingoutforms/apply" name="" method="get">
				<input type="radio" name="issch" value="1" class="radio1" id="txtInputBox1" <?php if($flag == 0){echo 'disabled';}?>><label for="txtInputBox1"><?=lang('apply_schlorship')?></label>
				<input type="radio" name="issch" value="2" class="radio2" id="txtInputBox2" <?php if($flag == 0){echo 'checked';}?>> <label for="txtInputBox2"><?=lang('apply_self')?></label>
				
			<?php if(!empty($scholarship_major)){?>
					<div class="sqlis">
			<ul>
				<li><div class="f_l width-35"><?=lang('schlorship_title')?></div><div class="f_l width-65"><?=!empty($scholarship_major['title'])?$scholarship_major['title']:''?></div></li>
				<li><div class="f_l width-35"><?=lang('schlorship_count')?></div><div class="f_l width-65"><?=$flag_count?></div></li>
				<li><div class="f_l width-35"><?=lang('schlorship_money')?></div><div class="f_l width-65"><?=!empty($scholarship_major['money'])?$scholarship_major['money']:''?></div></li>
				<li><div class="f_l width-35"><?=lang('schlorship_info')?></div><div class="f_l width-65"><?=!empty($scholarship_major['intro'])?$scholarship_major['intro']:''?></div></li>
				<li><div class="f_l width-35"><?=lang('schlorship_content')?></div><div class="f_l width-65"><?=!empty($scholarship_major['condition'])?$scholarship_major['condition']:''?></div></li>
				</ul>
		</div>
					
			<?php }?>
				<input type="hidden" name="applyid" value="<?=cucas_base64_encode($apply_info['id'])?>">
				<input type="hidden" name="scholorshipid" value="<?=!empty($scholarship_major['id'])?$scholarship_major['id']:''?>">
				<input type="hidden" name="userid" value="<?=!empty($userid)?$userid:''?>">
				<div class="redbtn-middle2"><input type="submit" name="" value="<?=lang('apply_1')?>"></div>
				</form>
				</div>
				
			</div>
	
	
			</div>
	</div>
</div>
<script type="text/javascript">
	
$('input:radio').click(function(){
			var v = $('input:radio:checked').val();
			if(v == 1){
				$('.sqlis').show('slow');
			}else{
				$('.sqlis').hide('slow');
			}
	
	});
</script>
