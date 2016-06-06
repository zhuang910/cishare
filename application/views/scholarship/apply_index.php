<?php $this->load->view('student/headermy.php')?>
<div class="width_925 clearfix">
	<?php $this->load->view('scholarship/apply_coursename')?>
</div>
<div class="width_925 clearfix applyonline-main">
	<div class="list_title">
		<?php $this->load->view('scholarship/apply_left')?>
	</div>
	<div class="zjlx">
	<div class="fl_zl">
		
		<?=lang('steps1')?>
	</div>
	<div class="fr_sq">
				<form action="/<?=$puri?>/scholarshipgrf/fillingoutforms/apply" name="" method="get">
				
				
				<?php if(!empty($scholarship_major)){
				
					foreach($scholarship_major as $k => $v){
				?>
					<input type="radio" name="issch" value="<?=$v['id']?>" class="radio1" id="txtInputBox<?=$v['id']?>" onclick="show_scholarship(<?=$v['id']?>)"><label for="txtInputBox<?=$v['id']?>"><?php if($puri == 'en'){?><?=!empty($v['entitle'])?$v['entitle']:''?><?php }else{?><?=!empty($v['title'])?$v['title']:''?><?php }?></label>
				
				<?php }}?>
				
				
				
				
				<input type="hidden" name="applyid" value="<?=cucas_base64_encode($apply_info['id'])?>">
				<div class="redbtn-middle2"><input type="submit" name="" value="<?=lang('apply_1')?>"></div>
				</form>
				</div>
				
			</div>
	
			</div>
	</div>
</div>

<?php $this->load->view('student/footer.php')?>