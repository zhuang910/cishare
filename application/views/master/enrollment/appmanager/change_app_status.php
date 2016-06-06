
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

										<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/change_app_status/submit_app_status" >
											
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
										<?php if(!empty($obj_select)):?>
										
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请状态修改:</label>

											<div class="col-xs-12 col-sm-4">
												<?=!empty($obj_select) ? $obj_select : ''?>
											</div>
										</div>
										<div class="space-2"></div>
										
										
										<div class="control-group" id='16' style='display:none;'>
												<label for="eoffer" class="control-label">上传图片</label>
												<div class="controls">			
													<label for="cucaseoffer"><input type="checkbox" name="cucaseoffer" id="cucaseoffer" value="Y"/> 使用CUCASE_OFFER</label><br />
													<div class="fileupload fileupload-new" data-provides="fileupload" id='file_16'> 
														<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="<?=!empty($result->eoffer) ? '/uploads/'.$result->eoffer : '/public/img/200_200_no_image.gif'?>" /></div>
														<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
														<div>
															<span class="btn btn-file"><span class="fileupload-new">浏览</span><span class="fileupload-exists">重选</span><input type="file" id="eoffer" name="eoffer"  /></span>
														</div>
													</div>
												</div>	
										</div>
										<?php endif ?>
										<!-- 审核阶段 - 标签值等于1时-待审核标签 -->
										
									<?php if(($label_id >5) && $result->state < 8 && $result->ispageoffer==1) :?> 
									<div class="control-group">
										<label class="control-label">辅助处理：</label>
										<div class="controls">
												<?php if ($result->ispageoffer==1): ?>
													<label for="ispageoffer"><input type="radio" name="ispageoffer" id="ispageoffer" value="1" <?php if($result->ispageoffer==1):?>checked='checked'<?php endif;?> />发送纸质offer通知</label>
												<?php else:?>
													 <label for="ispageoffer"><input type="radio" name="ispageoffer" id="ispageoffer" value="1" <?php if($result->ispageoffer==-1):?>checked='checked'<?php endif;?> />发送e-offer通知</label>
												<?php endif;?>
										</div>
									</div>
									<?php endif ?>
										
								<div class="form-group" id="idnum" style="display:none;">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学号:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="idnum" name="idnum" value="" class="col-xs-12 col-sm-5" />
									</div>
								</div>
								</div>

								<div class="space-2"></div>	
								
								<!-- 补充说明部分是否显示-如果状态操作下拉列表不存在，则不显示  -->
								<?php if(!empty($obj_select)):?>
								<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">补充说明:</label>
									<div class="col-xs-12 col-sm-9">
										<label for="insertetips"><input type="checkbox" name="insertetips" id="insertetips" value="Y"/> 将已下内容插入邮件</label><br />
										<textarea name="tips" cols="25" rows="5" style="width: 262px; height: 131px;"></textarea>
									</div>
								</div>
								<?php endif ?>
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
$('#status').change(function(){
	
	if($('#status').val()==16){
		$('#16').show();
	}
});
$('#cucaseoffer').click(function(){
	if($(this).attr('checked')==undefined){
		$('#file_16').hide();
		$(this).attr('checked','checked');
	}else{
		$('#file_16').show();
		$(this).removeAttr('checked');
	}
});
function save(){
	var h = '<i class="ace-icon fa fa-check">Process...</i>';
	$('#sub').html(h);
	var data=$('#pub_form').serialize();
	$.ajax({
		
		url: '/master/enrollment/change_app_status/submit_app_status',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state == 1){
			pub_alert_success();
			window.location.reload();
		}else{
			pub_alert_error();
		}
	})
	.fail(function() {
		pub_alert_error();
	})
}
</script>