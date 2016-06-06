<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												导出
											</div>
										</div>

										<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>appmanager/export" >
											
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">专业:</label>
											
										
													<select id="majorid" class="input-medium valid" name="courseid" aria-required="true" aria-invalid="false">
														<option value="0" selected="selected">-请选择-</option>
														<?php foreach($major as $k => $v):?>
															<option value="<?=$v['id']?>"><?=$v['name']?></option>
														<?php endforeach;?>
													</select> 
											
											
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">状态:</label>
											
										
													<select id="state" class="input-medium valid" name="state" aria-required="true" aria-invalid="false">
														<option value="0">-请选择-</option>
														<option value="2">打回</option>
														<option value="3">打回提交</option>
														<option value="4">拒绝</option>
														<option value="5">调剂</option>
														<option value="6">预录取</option>
														<option value="7">录取</option>
														<option value="8">已入学</option>
														<option value="9">结束</option>
													</select> 
												
											
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请日期:</label>
												<input  id="starttime" style="width:100px;" name="starttime"  class="date-picker valid" type="text" data-date-format="yyyy-mm-dd" value="">至
										</div>
										<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name"></label>
												<input  id="endtime" style="width:100px;" name="endtime"  class="date-picker " type="text" data-date-format="yyyy-mm-dd" value="">
																							
										</div>
											
												
										
												
									
										<div class="space-2"></div>
										
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">国籍:</label>
											
												<select id="nationality" class="input-medium valid" name="nationality"  >
														<option value="0" selected="selected">-请选择-</option>
														<?php foreach($stu_nationality as $k => $v):?>
															<option value='<?=$k?>'><?=$v?></option>
														<?php endforeach;?>
												</select>
										</div>
										<div class="space-2"></div>
											
													
										<div class="modal-footer no-margin-top">
												
											<div class="space-2"></div>
													<div class="col-md-offset-3 col-md-9">
														
														<button class="btn btn-info" data-last="Finish">
															<i class="ace-icon fa fa-check bigger-110"></i>
																提交
														</button>
														<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
																重置
														</button>
													</div>
									
									
									</div>
									</form>
								</div>
							</div>
			
<script type="text/javascript">
function time(){
	var css=$('#time').attr('class');
	if(css=='ace-icon glyphicon glyphicon-ok green'){
		$('#starttime').attr('disabled','false');
			$('#starttime').removeAttr('name');
			$('#endtime').attr('disabled','false');
			$('#endtime').removeAttr('name');
		$('#time').attr('class','ace-icon glyphicon glyphicon-remove red');

	}else{
		$('#time').attr('class','ace-icon glyphicon glyphicon-ok green');
		$('#starttime').attr('name','starttime');
		$('#starttime').removeAttr('disabled');
		$('#endtime').attr('name','endtime');
		$('#endtime').removeAttr('disabled');
	}
}
function isstate(){
	var css=$('#isstate').attr('class');
	if(css=='ace-icon glyphicon glyphicon-ok green'){
		$('#state').attr('disabled','false');
			$('#state').removeAttr('name');
		$('#isstate').attr('class','ace-icon glyphicon glyphicon-remove red');

	}else{
		$('#isstate').attr('class','ace-icon glyphicon glyphicon-ok green');
		$('#state').attr('name','state');
		$('#state').removeAttr('disabled');
	}
}
function ismajor(){
	var css=$('#ismajor').attr('class');
	if(css=='ace-icon glyphicon glyphicon-ok green'){
		$('#majorid').attr('disabled','false');
			$('#majorid').removeAttr('name');
		$('#ismajor').attr('class','ace-icon glyphicon glyphicon-remove red');

	}else{
		$('#ismajor').attr('class','ace-icon glyphicon glyphicon-ok green');
		$('#majorid').attr('name','state');
		$('#majorid').removeAttr('disabled');
	}
}

	function isnationality(	){
	var css=$('#isnationality').attr('class');
	if(css=='ace-icon glyphicon glyphicon-ok green'){
		$('#nationality').attr('disabled','false');
			$('#nationality').removeAttr('name');
		$('#isnationality').attr('class','ace-icon glyphicon glyphicon-remove red');

	}else{
		$('#isnationality').attr('class','ace-icon glyphicon glyphicon-ok green');
		$('#nationality').attr('name','state');
		$('#nationality').removeAttr('disabled');
	}
}


</script>
<!--日期插件-->
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.date-picker').datepicker({
		autoclose: true,
		todayHighlight: true
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>