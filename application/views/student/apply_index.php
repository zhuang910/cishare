<?php $this->load->view('student/headermy.php')?>
<div class="width_925 clearfix">
	<?php $this->load->view('student/apply_coursename')?>
</div>
<div class="width_925 clearfix applyonline-main">
	<div class="list_title">
		<?php $this->load->view('student/apply_left')?>
	</div>
	<div class="zjlx">
	<div class="fl_zl">
		
		<?=lang('steps')?>
	</div>
	<div class="fr_sq"><h4><?=lang('apply_method')?></h4>
				<form action="/<?=$puri?>/student/fillingoutforms/apply" name="" method="get">
				
				
				<?php if(!empty($scholarship_major)){
				
					foreach($scholarship_major as $k => $v){
				?>
					<input type="radio" name="issch" value="<?=$v['id']?>" class="radio1" id="txtInputBox<?=$v['id']?>" onclick="show_scholarship(<?=$v['id']?>)"><label for="txtInputBox<?=$v['id']?>"><?php if($puri == 'en'){?><?=!empty($v['entitle'])?$v['entitle']:''?><?php }else{?><?=!empty($v['title'])?$v['title']:''?><?php }?></label><br />
				
				<?php }}?>
				
				
				<input type="radio" name="issch" value="0" class="radio2" id="txtInputBoxZf2" checked onclick="clear_scholarship()"> <label for="txtInputBoxZf2"><?=lang('apply_self')?></label>
				<?php if(!empty($is_s)&&$is_s==1):?>
					(<?=lang('jiangxuejinkeyi')?>)
				<?php endif;?>
			<?php if(!empty($scholarship_major)){
				foreach($scholarship_major as $key => $val){
			?>
		<div class="sqlis" id="scholarshipcontent_<?=$val['id']?>" style="display:none;">
			<ul>
				<li><div class="f_l width-35"><?=lang('schlorship_title')?></div><div class="f_l width-65">
				<?php if($puri == 'en'){?><?=!empty($val['entitle'])?$val['entitle']:''?><?php }else{?><?=!empty($val['title'])?$val['title']:''?><?php }?>
				</div></li>
				<li><div class="f_l width-35"><?=lang('scholarship_cost_state')?></div><div class="f_l width-65">
				<?php 
					if(!empty($val['cost_state'])){
				?>
				<?php if($val['cost_state'] == 1){?>
					<?=lang('scholarship_cost_state_'.$val['cost_state'])?>
				<?php if($val['cost_cover']){
				$cost_cover = json_decode($val['cost_cover'],true);?>
				(<?php foreach($cost_cover as $zk => $zv){ echo lang('scholarship_cost_cover_'.$zk).' &nbsp;'; } ?>)
				<?=!empty($val['trem_year'])?lang('scholarship_trem_year_'.$val['trem_year']):''?>
					
				<?php }?>
				<?php }else if($val['cost_state'] == 2){?>
				<?=lang('scholarship_cost_state_'.$val['cost_state'])?>
				<?php if(!empty($val['cost_ratio'])){
					echo $val['cost_ratio'].'%';
				?>
					
				<?php }?>
				<?php }else if($val['cost_state'] == 3){?>
				<?=lang('scholarship_cost_state_'.$val['cost_state'])?>
				<?php if(!empty($val['cost_money'])){
					echo $val['cost_money'];
				?>
					
				<?php }?>
				<?php }?>
				<?php }?>
				</div></li>
				<li><div class="f_l width-35"><?=lang('schlorship_info')?></div><div class="f_l width-65">
				<?php if($puri == 'en'){?><?=!empty($val['enintro'])?$val['enintro']:''?><?php }else{?><?=!empty($val['intro'])?$val['intro']:''?><?php }?>
				</div></li>
				<li><div class="f_l width-35"><?=lang('schlorship_content')?></div><div class="f_l width-65"><?php if($puri == 'en'){?><?=!empty($val['encondition'])?$val['encondition']:''?><?php }else{?><?=!empty($val['condition'])?$val['condition']:''?><?php }?></div></li>
				</ul>
		</div>
					
			<?php }}?>
				
				<input type="hidden" name="applyid" value="<?=cucas_base64_encode($apply_info['id'])?>">
				<input type="hidden" name="scholorshipid" value="" id="scholorshipid">
				<div class="redbtn-middle2"><input type="submit" name="" value="<?=lang('apply_1')?>"></div>
				</form>
				</div>
				
			</div>
	
			</div>
	</div>
</div>
<script type="text/javascript">
function show_scholarship(id){
	if(id != ''){
		$('.sqlis').hide();
		$('#scholarshipcontent_'+id).show();
		$('#scholorshipid').val(id);
	}else{
		return false;
	}

}

function clear_scholarship(){
	$('#scholorshipid').val('');
}
	/*
$('input:radio').click(function(){
			var v = $('input:radio:checked').val();
			if(v == 1){
				$('.sqlis').show('slow');
			}else{
				$('.sqlis').hide('slow');
			}
	
	});*/
</script>
<?php $this->load->view('student/footer_no.php')?>