<?php 
	$applyuri = $this->uri->segment(4);
	if(empty($applyuri)){
		$applyuri = 'index';
	}
	$applyleft = array(
		'index' => 1,
		'apply' => 2,
		'upload_materials' =>3,
		'make_paymeznt' => 4,
		'submit_application' => 5,
	
	);
?>
<ul class="c<?=$applyleft[$applyuri]?>">
	<li class="d1"><a href="<?=!empty($isstart)?'javascript:;':'/'.$puri.'/student/apply?courseid='.cucas_base64_encode($courseid)?>"><span>1</span> <em><?=lang('apply_1')?></em></a></li>
	<li class="d2"><a href="<?=!in_array($apply_info['state'],array(0,2))?'javascript:;':'/'.$puri.'/student/fillingoutforms/apply?applyid='.cucas_base64_encode($apply_info['id'])?>"><span>2</span> <em><?=lang('apply_2')?></em></a></li>
	<li class="d3"><a href="<?=!in_array($apply_info['state'],array(0,2))?'javascript:;':'/'.$puri.'/student/apply/upload_materials?applyid='.cucas_base64_encode($apply_info['id'])?>"><span>3</span> <em><?=lang('apply_3')?></em></a></li>
	<li class="d4"><a href="<?=!in_array($apply_info['state'],array(0,2))?'javascript:;':'/'.$puri.'/student/apply/make_paymeznt?applyid='.cucas_base64_encode($apply_info['id'])?>"><span>4</span> <em><?=lang('apply_4')?></em></a></li>
	<li class="d5"><a href="<?=!in_array($apply_info['state'],array(0,2))?'javascript:;':'/'.$puri.'/student/apply/submit_application?applyid='.cucas_base64_encode($apply_info['id'])?>"><span>5</span> <em><?=lang('apply_5')?></em></a></li>	
</ul>