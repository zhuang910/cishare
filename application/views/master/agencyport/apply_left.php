<?php 
	if(empty($uri4)){
        $uri4 = 'index';
	}
	$applyleft = array(
		'index' => 1,
		'apply' => 2,
		'upload_materials' =>3,
		'make_paymeznt' => 4,
		'submit_application' => 5,
	
	);
?>
<ul class="c<?=$applyleft[$uri4]?>">
	<li class="d1"><a href="<?=!empty($isstart)?'javascript:;':'/master/agencyport/apply?userid='.$userid.'&courseid='.cucas_base64_encode($courseid)?>"><span>1</span> <em>开始申请</em></a></li>
	<li class="d2"><a href="<?='/master/agencyport/fillingoutforms/apply?userid='.$userid.'&applyid='.cucas_base64_encode($apply_info['id'])?>"><span>2</span> <em>填写在线申请表</em></a></li>
	<li class="d3"><a href="<?='/master/agencyport/apply/upload_materials?userid='.$userid.'&applyid='.cucas_base64_encode($apply_info['id'])?>"><span>3</span> <em>上传申请资料</em></a></li>
	<li class="d4"><a href="<?='/master/agencyport/apply/make_paymeznt?userid='.$userid.'&applyid='.cucas_base64_encode($apply_info['id'])?>"><span>4</span> <em>支付</em></a></li>
	<li class="d5"><a href="<?='/master/agencyport/apply/submit_application?userid='.$userid.'&applyid='.cucas_base64_encode($apply_info['id'])?>"><span>5</span> <em>提交申请</em></a></li>
</ul>