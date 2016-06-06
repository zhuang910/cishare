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

										<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>major/major/export" >
											
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">学位:</label>
											
													<select id="degree" class="input-medium valid" name="degree" aria-required="true" aria-invalid="false">
														<option value="0" selected="selected">-请选择-</option>
														<?php foreach($degree as $k => $v):?>
															<option value="<?=$v['id']?>"><?=$v['title']?></option>
														<?php endforeach;?>
													</select> 
											
										<!-- 	<a  class="red" title="点击启用" onclick="ismajor()" href="javascript:;">
												<i id="ismajor" class="ace-icon glyphicon glyphicon-ok green"></i>
											</a> -->
										</div>
										<div class="space-2"></div>
										
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">院系:</label>
											
												<select id="facultyid" class="input-medium valid" name="facultyid"  >
														<option value="0" selected="selected">-请选择-</option>
														<?php foreach($faculty as $k => $v):?>
															<option value='<?=$v['id']?>'><?=$v['name']?></option>
														<?php endforeach;?>
												</select>
											<!-- <a  class="red" title="点击启用" onclick="isnationality()" href="javascript:;">
												<i id="isnationality" class="ace-icon glyphicon glyphicon-ok green"></i>
											</a> -->
										
										</div>
										<div class="space-2"></div>
										
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">课程:</label>
											
												<select id="course" class="input-medium valid" name="course"  >
														<option value="0" selected="selected">-请选择-</option>
														<?php foreach($course as $k => $v):?>
															<option value='<?=$v['id']?>'><?=$v['name']?></option>
														<?php endforeach;?>
												</select>
											<!-- <a  class="red" title="点击启用" onclick="iscourse()" href="javascript:;">
												<i id="iscourse" class="ace-icon glyphicon glyphicon-ok green"></i>
											</a> -->
										
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

function iscourse(){
	var css=$('#iscourse').attr('class');
	if(css=='ace-icon glyphicon glyphicon-ok green'){
		$('#course').attr('disabled','false');
			$('#course').removeAttr('name');
		$('#iscourse').attr('class','ace-icon glyphicon glyphicon-remove red');

	}else{
		$('#iscourse').attr('class','ace-icon glyphicon glyphicon-ok green');
		$('#course').attr('name','course');
		$('#course').removeAttr('disabled');
	}
}
function ismajor(){
	var css=$('#ismajor').attr('class');
	if(css=='ace-icon glyphicon glyphicon-ok green'){
		$('#degree').attr('disabled','false');
			$('#degree').removeAttr('name');
		$('#ismajor').attr('class','ace-icon glyphicon glyphicon-remove red');

	}else{
		$('#ismajor').attr('class','ace-icon glyphicon glyphicon-ok green');
		$('#degree').attr('name','degree');
		$('#degree').removeAttr('disabled');
	}
}

	function isnationality(	){
	var css=$('#isnationality').attr('class');
	if(css=='ace-icon glyphicon glyphicon-ok green'){
		$('#facultyid').attr('disabled','false');
			$('#facultyid').removeAttr('name');
		$('#isnationality').attr('class','ace-icon glyphicon glyphicon-remove red');

	}else{
		$('#isnationality').attr('class','ace-icon glyphicon glyphicon-ok green');
		$('#facultyid').attr('name','facultyid');
		$('#facultyid').removeAttr('disabled');
	}
}


</script>
