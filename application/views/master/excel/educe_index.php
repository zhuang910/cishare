<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">基础设置</a>
	</li>
	<li>
		<a href="javascript:;">基本设置</a>
	</li>
	<li class="avtive">导出设置</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>


	
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		导出设置
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<!-- PAGE CONTENT BEGINS -->
							<div class="tabbable">
								<!-- #section:pages/faq -->
								<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
									<li id="major" class="active">
										<a data-toggle="tab" href="#faq-tab-1">
											
											专业导出
										</a>
									</li>

									<li id="couese">
										<a data-toggle="tab" href="#faq-tab-2">
										
											课程导出
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-3">
										
											申请导出
										</a>
									</li>
									<li id="score">
										<a data-toggle="tab" href="#faq-tab-4">
										
											成绩导出
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-5">
										
											考勤导出
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-6">
										
											学生导出
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-7">
										
											考试类型
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-8">
										
											书籍导出
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-9">
										
											老师导出
										</a>
									</li>
								</ul>

								<!-- /section:pages/faq -->
								<div class="tab-content no-border padding-24">
									<div id="faq-tab-1" class="tab-pane fade in active">
									<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											字段设置
									</h4>
									<form class="form-horizontal" method="post" id="form_major" action="<?=$zjjp?>educe/save_major" >
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php foreach($mfields as $k =>$v):?>
															<?php 
																$checked='';
																if(!empty($arr['major'])){
																	foreach ($arr['major'] as $kk => $vv) {
																		if($k==$kk){
																			$checked='checked="checked"';

																		}
																	}
																}
															?>	
																<label class="middle">
																<input id="id-disable-check" value="<?=$v?>" name="<?=$k?>" <?=$checked?> class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														<a onclick="save_major()" class="btn btn-info">
														保存
														</a>
													
									
										</form>
										
									
										<!---->
									</div>
									<div id="faq-tab-2" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											字段设置
											</h4>
											<form class="form-horizontal" id="form_course" method="post" >
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<h4>课程主表</h4>	
														<?php foreach($cfields as $k =>$v):?>
															<?php 
																$checked='';
																if(!empty($arr['course'])){
																	foreach ($arr['course'] as $kk => $vv) {
																		if($k==$kk){
																			$checked='checked="checked"';

																		}
																	}
																}
															?>	
																<label class="middle">
																<input id="id-disable-check" value="<?=$v?>" name="<?=$k?>" <?=$checked?> class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>	
													
															</div>
													</div>

												<div class="space-2"></div>
														<a onclick="save_course()" class="btn btn-info">
														保存
														</a>
													
									
										</form>
										
											</div>
										</div>
									</div>
									<div id="faq-tab-3" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-3" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											申请
											</h4>
											<!-- <form class="form-horizontal" id="emailset_defult" method="post" action="<?=$zjjp?>educe/educe_shenqing">
												 -->
												 <form class="form-horizontal" id="form_shenqing" method="post" >
													<?php foreach($apply as $k =>$v):?>
																<?php 
																	$checked='';
																	if(!empty($arr['shenqing'])){
																		foreach ($arr['shenqing'] as $kk => $vv) {
																			if($k==$kk){
																				$checked='checked="checked"';

																			}
																		}
																	}
																?>	
																<label class="middle">
																<input id="id-disable-check"  value="<?=$v?>" name="<?=$k?>" <?=$checked?> class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>	
												
													<div class="space-2"></div>
													
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div id="shenqing" class="col-xs-12 col-sm-9">
															

															</div>
													</div>

												<div class="space-2"></div>
														<a onclick="save_shenqing()" class="btn btn-info">
														保存
														</a>
													
									
										</form>
										
											</div>
										</div>
									</div>
									<!--end3-->
									<!--4-->
									<div id="faq-tab-4" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											成绩
											</h4>
											<form class="form-horizontal" id="form_score" method="post" action="<?=$zjjp?>educe/educe_score">
												
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php foreach($sfields as $k =>$v):?>
																<?php 
																	$checked='';
																	if(!empty($arr['chengji'])){
																		foreach ($arr['chengji'] as $kk => $vv) {
																			if($k==$kk){
																				$checked='checked="checked"';

																			}
																		}
																	}
																?>
																<label class="middle">
																<input id="id-disable-check" value="<?=$v?>" name="<?=$k?>" <?=$checked?> class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														
														<a onclick="save_score()" class="btn btn-info">
														保存
														</a>
													
									
										</form>
										
											</div>
										</div>
									</div>
									<!--end4-->
									<!--5-->
									<div id="faq-tab-5" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											考勤
											</h4>
											<form class="form-horizontal" id="form_checking" method="post" action="<?=$zjjp?>educe/save_checking">
													
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php foreach($checkfields as $k =>$v):?>
																<?php 
																	$checked='';
																	if(!empty($arr['checking'])){
																		foreach ($arr['checking'] as $kk => $vv) {
																			if($k==$kk){
																				$checked='checked="checked"';

																			}
																		}
																	}
																?>
																<label class="middle">
																<input id="id-disable-check" value="<?=$v?>" name="<?=$k?>" <?=$checked?> class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														<a onclick="save_checking()" class="btn btn-info">
														保存
														</a>
													
									
										</form>
										
											</div>
										</div>
									</div>
									<!--end5-->
									<!--6-->
									<div id="faq-tab-6" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											学生
											</h4>
											<form class="form-horizontal" id="form_student" method="post" action="<?=$zjjp?>educe/save_student">
													
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php foreach($student as $k =>$v):?>
																<?php 
																	$checked='';
																	if(!empty($arr['student'])){
																		foreach ($arr['student'] as $kk => $vv) {
																			if($k==$kk){
																				$checked='checked="checked"';

																			}
																		}
																	}
																?>
																<label class="middle">
																<input id="id-disable-check" value="<?=$v?>" name="<?=$k?>" <?=$checked?> class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														<a onclick="save_student()" class="btn btn-info">
														保存
														</a>
													
									
										</form>
										
											</div>
										</div>
									</div>
									<!--end6-->
									<!--7-->
									<div id="faq-tab-7" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											考试类型
											</h4>
											<form class="form-horizontal" id="form_itemsetting" method="post" action="<?=$zjjp?>educe/save_itemsetting">
													
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php foreach($itemsetting as $k =>$v):?>
																<?php 
																	$checked='';
																	if(!empty($arr['itemsetting'])){
																		foreach ($arr['itemsetting'] as $kk => $vv) {
																			if($k==$kk){
																				$checked='checked="checked"';

																			}
																		}
																	}
																?>
																<label class="middle">
																<input id="id-disable-check" value="<?=$v?>" name="<?=$k?>" <?=$checked?> class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														<a onclick="save_itemsetting()" class="btn btn-info">
														保存
														</a>
													
									
										</form>
										
											</div>
										</div>
									</div>
									<!--end7-->
									<!--8-->
									<div id="faq-tab-8" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											书籍导出
											</h4>
											<form class="form-horizontal" id="form_books" method="post" action="<?=$zjjp?>educe/save_books">
													
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php foreach($books as $k =>$v):?>
																<?php 
																	$checked='';
																	if(!empty($arr['books'])){
																		foreach ($arr['books'] as $kk => $vv) {
																			if($k==$kk){
																				$checked='checked="checked"';

																			}
																		}
																	}
																?>
																<label class="middle">
																<input id="id-disable-check" value="<?=$v?>" name="<?=$k?>" <?=$checked?> class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														<a onclick="save_books()" class="btn btn-info">
														保存
														</a>
													
									
										</form>
										
											</div>
										</div>
									</div>
									<!--end8-->
									<!--9-->
									<div id="faq-tab-9" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											老师导出
											</h4>
											<form class="form-horizontal" id="form_teacher" method="post" action="<?=$zjjp?>educe/save_teacher">
													
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php foreach($teacher as $k =>$v):?>
																<?php 
																	$checked='';
																	if(!empty($arr['teacher'])){
																		foreach ($arr['teacher'] as $kk => $vv) {
																			if($k==$kk){
																				$checked='checked="checked"';

																			}
																		}
																	}
																?>
																<label class="middle">
																<input id="id-disable-check" value="<?=$v?>" name="<?=$k?>" <?=$checked?> class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														<a onclick="save_teacher()" class="btn btn-info">
														保存
														</a>
													
									
										</form>
										
											</div>
										</div>
									</div>
									<!--end9-->
								</div>
							</div>
		</div>
	</div>
</div>	

<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
	<script src="<?=RES?>master/js/dropzone.min.js"></script>
<script type="text/javascript">
function save_shenqing(){
	var data=$('#form_shenqing').serialize();
	$.ajax({
			url: '<?=$zjjp?>educe/save_shenqing',
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
			}

			 
		})
		.fail(function() {
 
			
		})
}
function save_score(){
	var data=$('#form_score').serialize();
	$.ajax({
			url: '<?=$zjjp?>educe/save_score',
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
			}

			 
		})
		.fail(function() {
 
			
		})
}
function save_checking(){
	var data=$('#form_checking').serialize();
	$.ajax({
			url: '<?=$zjjp?>educe/save_checking',
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
			}

			 
		})
		.fail(function() {
 
			
		})
}
function save_major(){
	var data=$('#form_major').serialize();
	$.ajax({
			url: '<?=$zjjp?>educe/save_major',
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
			}

			 
		})
		.fail(function() {
 
			
		})
}
function save_course(){
	var data=$('#form_course').serialize();
	$.ajax({
			url: '<?=$zjjp?>educe/save_course',
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
			}

			 
		})
		.fail(function() {
 
			
		})
}
function save_itemsetting(){
	var data=$('#form_itemsetting').serialize();
	$.ajax({
			url: '<?=$zjjp?>educe/save_itemsetting',
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
			}

			 
		})
		.fail(function() {
 
			
		})
}
function save_teacher(){
	var data=$('#form_teacher').serialize();
	$.ajax({
			url: '<?=$zjjp?>educe/save_teacher',
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
			}

			 
		})
		.fail(function() {
 
			
		})
}
function save_books(){
	var data=$('#form_books').serialize();
	$.ajax({
			url: '<?=$zjjp?>educe/save_books',
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
			}

			 
		})
		.fail(function() {
 
			
		})
}
function save_student(){
	var data=$('#form_student').serialize();
	$.ajax({
			url: '<?=$zjjp?>educe/save_student',
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
			}

			 
		})
		.fail(function() {
 
			
		})
}
function course(){

	var cid=$('#courseid').val();
		$.ajax({
			url: '<?=$zjjp?>educe/get_attatemplateid/'+cid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			$("#attatemplate").empty();
			$('#shenqing').empty();
			 	 var opt = $("<option/>").text(r.data.s.ClassName).attr("value",r.data.s.tClass_id);
			 	  $("#attatemplate").append(opt); 
			 	  var str='';
			  $.each(r.data.k, function(k, v) {
			  	$.each(v, function(kk, vv) {
			  			str +='<label class="middle"><input id="id-disable-check" value="'+vv.formID+'" name="'+vv.topic_id+'" checked="checked" class="ace" type="checkbox"><span class="lbl"> '+vv.formTitle+'</span></label>';
			  		
			  	});
			  });

			  $('#shenqing').append(str);

			 
		})
		.fail(function() {
 
			
		})

}

</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>