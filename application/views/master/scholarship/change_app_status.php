
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												修改状态
											</div>
										</div>

										<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/scholarship/change_scholarship_status/submit_app_status" >
											
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请ID:</label>

											<div class="col-xs-12 col-sm-9">
												<div class="clearfix">
														<?=!empty($result->number) ? $result->number : ''?>
												</div>
											</div>
										</div>

										<div class="space-2"></div>
										
										<!-- 申请状态修改下拉列表是否显示 -->
										
										
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请状态修改:</label>

											<div class="col-xs-12 col-sm-4">
												<select name="state" id="state">
												<option value="">请选择</option>
													<?php if(!empty($opeate[$label_id])){
														foreach($opeate[$label_id] as $k => $v){
													?>
													<option value="<?=$k?>"><?=$v?></option>
													<?php }}?>
												</select>
											</div>
										</div>
										<div class="space-2"></div>
										
										
										
										
										<!-- 审核阶段 - 标签值等于1时-待审核标签 -->
										
									
							
								
								<!-- 补充说明部分是否显示-如果状态操作下拉列表不存在，则不显示  -->
								
								<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">补充说明:</label>
									<div class="col-xs-12 col-sm-9">
										<label for="insertetips"><input type="checkbox" name="insertetips" id="insertetips" value="Y"/> 将已下内容插入邮件</label><br />
										<textarea name="tips" cols="25" rows="5" style="width: 262px; height: 131px;"></textarea>
									</div>
								</div>
							
								<input type="hidden" name="id" value="<?=!empty($result->appid) ? $result->appid : ''?>">
								<div class="modal-footer center">
									<button type="button" class="btn btn-sm btn-success" onclick="save()" id="sub"><i class="ace-icon fa fa-check"></i> Submit</button>
									<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
								 </div>
									
									</form>
								</div>
							</div>
</div>
<script type="text/javascript">

function save(){
	var h = '<i class="ace-icon fa fa-check">Process...</i>';
	$('#sub').html(h);
	var data=$('#pub_form').serialize();
	$.ajax({
		
		url: '/master/scholarship/change_scholarship_status/submit_app_status',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state == 1){
			pub_alert_success();
			window.location.reload();
		}else{
			var h = '<i class="ace-icon fa fa-check">Submit</i>';
			$('#sub').html(h);
			pub_alert_error(r.info);
		}
	})
	.fail(function() {
		pub_alert_error();
	})
}
</script>